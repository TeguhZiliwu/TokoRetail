<?php
include '../../config/appname.php';
include '../../config/dbconnection.php';
include '../../config/errorlog.php';
require '../../assets/library/phpspreadsheet/vendor/autoload.php';

session_name($encryptedAppName);
session_start();

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

$json = new stdClass();
$conn = openConn();
$userLogin = mysqli_real_escape_string($conn, $_SESSION['userid']);
$query = "";
$postdata = "";
$invaliddata = array();
$invalidcount = 0;
$successcount = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $postdata = file_get_contents('php://input');
    $postdata = json_decode($postdata);
}

if (!empty($userLogin)) {
    try {
        $file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        if (isset($_FILES['fileUpload']['name']) && in_array($_FILES['fileUpload']['type'], $file_mimes)) {

            $arr_file = explode('.', $_FILES['fileUpload']['name']);
            $extension = end($arr_file);
            $Reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

            if ($extension == 'csv') {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
            } else {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }

            $spreadsheet = $reader->load($_FILES['fileUpload']['tmp_name']);
            $sheetData = $spreadsheet->getActiveSheet()->toArray();

            if (!empty($sheetData)) {

                $header1 = $sheetData[0][0];
                $header2 = $sheetData[0][1];
                $header3 = $sheetData[0][2];
                $header4 = $sheetData[0][3];

                if ($header1 == "Urutan" && $header2 == "KodeBarang" && $header3 == "Jumlah" && $header4 == "Diskon") {

                    if (count($sheetData) > 1) {
                        array_shift($sheetData);
                        // print_r($sheetData);
                        $DistinctArr = unique_multidim_array($sheetData, 0);
                        $conn->autocommit(FALSE);

                        // rollback should revert here
                        $conn->begin_transaction();
                        $successInsertDet = true;

                        for ($x = 0; $x < count($DistinctArr); $x++) {
                            $TransactionID = generatedTransactionID($conn);
                            $TransactionType = "OUT";
                            $Remark = "Input transaksi dilakukan dengan upload file";
                            $query = ("INSERT INTO ttransaction (transactionid, transactiontype, transactiondate, remark, createdby, createddate) VALUES (?,?,CURRENT_TIMESTAMP,?,?,CURRENT_TIMESTAMP)");
                            $stmt = $conn->prepare($query);
                            $stmt->bind_param("ssss", $TransactionID, $TransactionType, $Remark, $userLogin);
                            if (!$stmt->execute()) {
                                echo $stmt->error;
                                throw new Exception($stmt->error);
                            }

                            for ($i = 0; $i < count($sheetData); $i++) {
                                if ($DistinctArr[$x][0] == $sheetData[$i][0]) {
                                    $Urutan = $sheetData[$i][0];
                                    $KodeBarang = $sheetData[$i][1];
                                    $Jumlah = $sheetData[$i][2];
                                    $Diskon = $sheetData[$i][3];
                                    $SellingPrice = getPurchasePrice($conn, $KodeBarang);

                                    $resultvalidata = checkvaliddata($conn, $Urutan, $KodeBarang, $Jumlah, $Diskon);

                                    if ($resultvalidata[0]["valid"] == true) {
                                        if ($resultvalidata[0]["fixdiskon"] != "") {
                                            $Diskon = $resultvalidata[0]["fixdiskon"];
                                        }
                                        $intialPrice = getInitialPrice($conn, $KodeBarang);

                                        $query = ("INSERT INTO ttransactiondet (transactionid, itemcode, qty, initialprice, purchaseprice, discount) VALUES (?,?,?,?,?,?)");
                                        $stmt = $conn->prepare($query);
                                        $stmt->bind_param("ssssss", $TransactionID, $KodeBarang, $Jumlah, $intialPrice, $SellingPrice, $Diskon);
                                        if (!$stmt->execute()) {
                                            $successInsertDet = false;
                                            throw new Exception($stmt->error);
                                        }

                                        $query = ("SELECT A.itemcode, IFNULL(B.qty, 0) AS qty
                                        FROM titem  A
                                        LEFT JOIN tstock B ON A.itemcode = B.itemcode
                                        WHERE A.itemcode = ?");
                                        $stmt = $conn->prepare($query);
                                        $stmt->bind_param("s", $KodeBarang);
                                        if (!$stmt->execute()) {
                                            throw new Exception($stmt->error);
                                        }

                                        $result = $stmt->get_result();

                                        if ($result->num_rows > 0) {
                                            $existingQty = 0;

                                            while ($data = $result->fetch_assoc()) {
                                                $existingQty = $data['qty'];
                                            }

                                            $Jumlah = (int)$Jumlah;
                                            $existingQty = (int)$existingQty;
                                            $FixedQty = ($existingQty - $Jumlah);

                                            $query = ("UPDATE tstock SET qty=?, updatedby=?, updateddate=CURRENT_TIMESTAMP WHERE itemcode=?");
                                            $stmt = $conn->prepare($query);
                                            $stmt->bind_param("sss", $FixedQty, $userLogin, $KodeBarang);
                                            if (!$stmt->execute()) {
                                                throw new Exception($stmt->error);
                                            }
                                            $successcount++;
                                        }
                                    } else {
                                        $successInsertDet = false;
                                    }

                                    $data = array(
                                        'Seq' => $Urutan,
                                        'ItemCode' => $KodeBarang,
                                        'Qty' => $Jumlah,
                                        'Diskon' => $Diskon,
                                        'Remark' => $resultvalidata[0]["msg"]
                                    );
                                    array_push($invaliddata, $data);
                                    $invalidcount++;
                                }
                            }
                        }

                        if (!$successInsertDet) {
                            $conn->rollback();
                        } else {
                            if ($conn->commit()) {
                                $stmt->close();
                            } else {
                                throw new Exception('Transaction commit failed.');
                            }
                        }
                    } else {
                        $json->success = false;
                        $json->datainvalid = $invaliddata;
                        $json->totalsuccess = $successcount;
                        $json->totalinvalid = $invalidcount;
                        $json->msg = "Import gagal. Tidak ada data didalam file.";
                        $jsonstring = json_encode($json);
                        echo $jsonstring;
                    }

                    if ($invalidcount >= 0 && $successcount > 0 && $successInsertDet) {
                        $json->success = true;
                        $json->successInsert = $successInsertDet;
                        $json->datainvalid = $invaliddata;
                        $json->totalsuccess = $successcount;
                        $json->totalinvalid = $invalidcount;
                        $json->msg = "Penjualan berhasil dicatat.";
                        $jsonstring = json_encode($json);
                        echo $jsonstring;
                    } else if ($invalidcount > 0 && $successcount >= 0 && !$successInsertDet) {
                        $json->success = true;
                        $json->datainvalid = $invaliddata;
                        $json->totalsuccess = $successcount;
                        $json->totalinvalid = $invalidcount;
                        $json->msg = "Import gagal. Lihat ringkasan Import.";
                        $jsonstring = json_encode($json);
                        echo $jsonstring;
                    }
                } else {
                    $json->success = false;
                    $json->datainvalid = $invaliddata;
                    $json->totalsuccess = $successcount;
                    $json->totalinvalid = $invalidcount;
                    $json->msg = "Format template salah.";
                    $jsonstring = json_encode($json);
                    echo $jsonstring;
                    closeConn($conn);
                    exit();
                }
            }
        }
    } catch (\Throwable $e) {
        $conn->rollback();
        $errorMsg = 'Error on line ' . $e->getLine() . ' in ' . $e->getFile() . ': ' . $e->getMessage();
        saveErrorLog($errorMsg, "Sale", "Import", $userLogin, $conn);
        $json->success = false;
        $json->datainvalid = $invaliddata;
        $json->totalsuccess = $successcount;
        $json->totalinvalid = $invalidcount;
        $json->msg = "[ERROR] Terjadi kesalahan, harap hubungi teknisi.";
        $jsonstring = json_encode($json);
        echo $jsonstring;
    } finally {
        $conn->autocommit(TRUE);
        closeConn($conn);
    }
} else {
    $json->success = false;
    $json->msg = "Silahkan login kedalam aplikasi!";
    $jsonstring = json_encode($json);
    echo $jsonstring;
}

function checkvaliddata($conn, $urutan, $kodebarang, $jumlah, $diskon)
{
    $returnarr = array();

    $data = array(
        'valid' => true,
        'msg' => "",
        'fixdiskon' => ""
    );
    array_push($returnarr, $data);

    if ($urutan == "") {
        $returnarr[0]["valid"] = false;
        if ($returnarr[0]["msg"] == "") {
            $returnarr[0]["msg"] = "Silahkan isi kolom Urutan.";
        }
    }

    if ($kodebarang == "") {
        $returnarr[0]["valid"] = false;
        if ($returnarr[0]["msg"] == "") {
            $returnarr[0]["msg"] = "Silahkan isi kolom Kode Barang.";
        }
    }

    if ($jumlah == "") {
        $returnarr[0]["valid"] = false;
        if ($returnarr[0]["msg"] == "") {
            $returnarr[0]["msg"] = "Silahkan isi kolom Jumlah.";
        }
    }

    if ($diskon == "") {
        $returnarr[0]["valid"] = false;
        if ($returnarr[0]["msg"] == "") {
            $returnarr[0]["msg"] = "Silahkan isi kolom Diskon.";
        }
    }

    if (!is_int((int)$urutan)) {
        $returnarr[0]["valid"] = false;
        if ($returnarr[0]["msg"] == "") {
            $returnarr[0]["msg"] = "Silahkan isi kolom Urutan dengan Angka.";
        }
    }

    if (strlen($kodebarang) > 20) {
        $returnarr[0]["valid"] = false;
        if ($returnarr[0]["msg"] == "") {
            $returnarr[0]["msg"] = "Maximum karakter untuk Nama Barang adalah 100.";
        }
    }

    if ($kodebarang != "") {
        $query = ("SELECT itemcode FROM titem WHERE itemcode = ?");
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $kodebarang);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows <= 0) {
            if ($returnarr[0]["msg"] == "") {
                $returnarr[0]["msg"] = "Kode Barang tidak terdaftar didalam sistem.";
            }
        }
    }

    if ($jumlah != "") {
        $isINT = is_int($jumlah);
        if (!$isINT) {
            $returnarr[0]["valid"] = false;
            if ($returnarr[0]["msg"] == "") {
                $returnarr[0]["msg"] = "Silahkan isi kolom Jumlah dengan Angka.";
            }
        } else {
            $jumlah = (int)$jumlah;
            $query = ("SELECT qty FROM tstock WHERE itemcode = ?");
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $kodebarang);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $qty = 0;
                while ($rows = mysqli_fetch_array($result)) {
                    $qty = (int)$rows['qty'];
                }
                if ($qty <= 0) {
                    $returnarr[0]["valid"] = false;
                    if ($returnarr[0]["msg"] == "") {
                        $returnarr[0]["msg"] = "Stok untuk barang ini tidak tersedia.";
                    }
                }
            } else {
                $returnarr[0]["valid"] = false;
                if ($returnarr[0]["msg"] == "") {
                    $returnarr[0]["msg"] = "Stok untuk barang ini tidak tersedia.";
                }
            }
        }
    }

    if ($diskon != "") {
        if (strpos($diskon, '%') !== false) {

            $totalpercentChar = substr_count($diskon, "%");

            if ($totalpercentChar == 1) {
                $settingid = "DiscountPercentage";

                $query = ("SELECT settingvalue FROM tglobalsetting WHERE settingid = ?");
                $stmt = $conn->prepare($query);
                $stmt->bind_param("s", $settingid);
                $stmt->execute();
                $result = $stmt->get_result();;

                if ($result->num_rows > 0) {
                    $resultdata = "";
                    while ($data = $result->fetch_assoc()) {
                        $resultdata = $data['settingvalue'];
                    }
                    $resultdata = explode(",", $resultdata);
                    if (count($resultdata) > 0) {
                        $hasvalue = false;
                        for ($i = 0; $i < count($resultdata); $i++) {
                            $settingvalue = $resultdata[$i];
                            $settingvalue = preg_replace('/\s+/', '', $settingvalue);
                            if ($diskon == $settingvalue) {
                                $hasvalue = true;
                            }
                        }

                        if (!$hasvalue) {
                            $returnarr[0]["valid"] = false;
                            if ($returnarr[0]["msg"] == "") {
                                $returnarr[0]["msg"] = "Jenis diskon persentase tidak terdaftar didalam sistem.";
                            }
                        } else {
                            $query = ("SELECT sellingprice FROM titem WHERE itemcode = ?");
                            $stmt = $conn->prepare($query);
                            $stmt->bind_param("s", $kodebarang);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $sellingprice = 0;

                            if ($result->num_rows > 0) {
                                $sellingprice = 0;
                                while ($rows = mysqli_fetch_array($result)) {
                                    $sellingprice = (int)$rows['sellingprice'];
                                }

                                $sellingprice = (int)$sellingprice;
                            }

                            if ($jumlah != "") {
                                $isINT = is_int($jumlah);
                                if ($isINT) {
                                    $jumlah = (int)$jumlah;
                                    $subtotal = ($sellingprice * $jumlah);
                                    $diskon = ((int)$diskon * $subtotal) / 100;
                                    $returnarr[0]["fixdiskon"] = $diskon;
                                }
                            }
                        }
                    } else {
                        $returnarr[0]["valid"] = false;
                        if ($returnarr[0]["msg"] == "") {
                            $returnarr[0]["msg"] = "Jenis diskon persentase tidak terdaftar didalam sistem.";
                        }
                    }
                } else {
                    $returnarr[0]["valid"] = false;
                    if ($returnarr[0]["msg"] == "") {
                        $returnarr[0]["msg"] = "Jenis diskon persentase tidak terdaftar didalam sistem.";
                    }
                }
            } else {
                $returnarr[0]["valid"] = false;
                if ($returnarr[0]["msg"] == "") {
                    $returnarr[0]["msg"] = "Silahkan isi kolom Diskon dengan Angka atau Nilai Persentase.";
                }
            }
        } else {
            $isINT = is_int($diskon);
            if (!$isINT) {
                $returnarr[0]["valid"] = false;
                if ($returnarr[0]["msg"] == "") {
                    $returnarr[0]["msg"] = "Silahkan isi kolom Diskon dengan Angka atau Nilai Persentase.";
                }
            } else {
                $diskon = (int)$diskon;
                $query = ("SELECT sellingprice FROM titem WHERE itemcode = ?");
                $stmt = $conn->prepare($query);
                $stmt->bind_param("s", $kodebarang);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $sellingprice = 0;
                    while ($rows = mysqli_fetch_array($result)) {
                        $sellingprice = (int)$rows['sellingprice'];
                    }

                    $sellingprice = (int)$sellingprice;
                    $diskon = (int)$diskon;
                    $subtotal = 0;
                    
                    if ($jumlah != "") {
                        $isINT = is_int($jumlah);
                        if ($isINT) {
                            $jumlah = (int)$jumlah;
                            $subtotal = ($sellingprice * $jumlah);
                        }
                    }

                    if ($diskon > $subtotal) {
                        $returnarr[0]["valid"] = false;
                        if ($returnarr[0]["msg"] == "") {
                            $returnarr[0]["msg"] = "Diskon tidak boleh lebih dari total penjualan.";
                        }
                    }
                }
            }
        }
    }

    return $returnarr;
}

function generatedItemCode($conn)
{
    $return = "";
    $query = ("SELECT CASE WHEN EXISTS (SELECT itemcode FROM titem WHERE itemcode LIKE CONCAT('IC',YEAR(NOW()),LPAD(MONTH(NOW()), 2, '0'),'%')) THEN CONCAT('IC', YEAR(NOW()),LPAD(MONTH(NOW()), 2, '0'),RIGHT(CONCAT('0000',CAST((CAST((SELECT RIGHT(itemcode,4) FROM titem WHERE itemcode LIKE CONCAT('IC',YEAR(NOW()),LPAD(MONTH(NOW()), 2, '0'),'%') ORDER BY itemcode DESC LIMIT 1) as int) + 1) as varchar(20))), 4)) ELSE CONCAT('IC',YEAR(NOW()),LPAD(MONTH(NOW()), 2, '0'),'0001') END AS ID");
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while ($data = $result->fetch_assoc()) {
            $return = $data['ID'];
        }
    }
    $stmt->close();
    return $return;
}

function unique_multidim_array($array, $key)
{
    $temp_array = array();
    $i = 0;
    $key_array = array();

    foreach ($array as $val) {
        if (!in_array($val[$key], $key_array)) {
            $key_array[$i] = $val[$key];
            $temp_array[$i] = $val;
        }
        $i++;
    }
    return $temp_array;
}

function generatedTransactionID($conn)
{
    $return = "";
    $query = ("SELECT CASE WHEN EXISTS (SELECT transactionid FROM ttransaction WHERE transactionid LIKE CONCAT('TROUT',YEAR(NOW()),LPAD(MONTH(NOW()), 2, '0'),'%')) THEN CONCAT('TROUT', YEAR(NOW()),LPAD(MONTH(NOW()), 2, '0'),RIGHT(CONCAT('0000',CAST((CAST((SELECT RIGHT(transactionid,4) FROM ttransaction WHERE transactionid LIKE CONCAT('TROUT',YEAR(NOW()),LPAD(MONTH(NOW()), 2, '0'),'%') ORDER BY transactionid DESC LIMIT 1) as int) + 1) as varchar(20))), 4)) ELSE CONCAT('TROUT',YEAR(NOW()),LPAD(MONTH(NOW()), 2, '0'),'0001') END AS ID;");
    $id = mysqli_query($conn, $query);
    if (mysqli_num_rows($id) > 0) {
        while ($rows = mysqli_fetch_array($id)) {
            $return = $rows['ID'];
        }
    }
    return $return;
}

function getPurchasePrice($conn, $ItemCode)
{
    $query = ("SELECT sellingprice
               FROM titem
               WHERE itemcode = ?");
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $ItemCode);
    $stmt->execute();
    $result = $stmt->get_result();
    $SellingPrice = "";

    if ($result->num_rows > 0) {
        while ($data = $result->fetch_assoc()) {
            $SellingPrice = $data['sellingprice'];
        }
    }

    return $SellingPrice;
}

function getInitialPrice($conn, $itemid){
    $return = "";
    $query = "(SELECT itemcode, itemname, sellingprice, IFNULL((SELECT A.purchaseprice FROM ttransactiondet A INNER JOIN ttransaction B ON A.transactionid = B.transactionid WHERE B.transactiontype = 'IN' AND A.itemcode = C.itemcode ORDER BY B.transactiondate DESC LIMIT 1), 0) AS buyprice, (sellingprice - IFNULL((SELECT A.purchaseprice FROM ttransactiondet A INNER JOIN ttransaction B ON A.transactionid = B.transactionid WHERE B.transactiontype = 'IN' AND A.itemcode = C.itemcode ORDER BY B.transactiondate DESC LIMIT 1), 0)) AS gainloss
    FROM titem C
    WHERE itemcode LIKE ?
    ORDER BY itemcode ASC)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $itemid);
    if (!$stmt->execute()) {
        echo $stmt->error;
        throw new Exception($stmt->error);
    }
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while ($data = $result->fetch_assoc()) {
            $return = $data['buyprice'];
        }
    }
    return $return;
}