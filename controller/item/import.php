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
$coba = "";

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
                $header5 = $sheetData[0][4];
                $header6 = $sheetData[0][5];

                if ($header1 == "NamaBarang" && $header2 == "Kategori" && $header3 == "Satuan" && $header4 == "JenisBarang" && $header5 == "DeskripsiBarang" && $header6 == "HargaJual") {

                    if (count($sheetData) > 1) {
                        for ($i = 1; $i < count($sheetData); $i++) { //skipping first row

                            $ItemName = $sheetData[$i][0];
                            $Category = $sheetData[$i][1];
                            $UOM = $sheetData[$i][2];
                            $ItemType = $sheetData[$i][3];
                            $ItemDesc = $sheetData[$i][4];
                            $SellingPrice = $sheetData[$i][5];

                            $resultvalidata = checkvaliddata($conn, $ItemName, $Category, $UOM, $ItemType, $ItemDesc, $SellingPrice);

                            if ($resultvalidata[0]["valid"] != true) {
                                $data = array(
                                    'ItemName' => $ItemName,
                                    'Category' => $Category,
                                    'UOM' => $UOM,
                                    'ItemType' => $ItemType,
                                    'ItemDesc' => $ItemDesc,
                                    'SellingPrice' => $SellingPrice,
                                    'Remark' => $resultvalidata[0]["msg"]
                                );
                                array_push($invaliddata, $data);
                                $invalidcount++;
                            } else {

                                $query = ("SELECT itemcode 
                                           FROM titem 
                                           WHERE itemname = ? 
                                           AND categorycode = (SELECT categorycode FROM tcategory WHERE category = ?) 
                                           AND uomcode = (SELECT uomcode FROM tuom WHERE uom = ?) 
                                           AND itemtype = ?");
                                $stmt = $conn->prepare($query);
                                $stmt->bind_param("ssss", $ItemName, $Category, $UOM, $ItemType);
                                $stmt->execute();
                                $result = $stmt->get_result();

                                if ($result->num_rows > 0) {
                                    $data = array(
                                        'ItemName' => $ItemName,
                                        'Category' => $Category,
                                        'UOM' => $UOM,
                                        'ItemType' => $ItemType,
                                        'ItemDesc' => $ItemDesc,
                                        'SellingPrice' => $SellingPrice,
                                        'Remark' => "Barang sudah terdaftar."
                                    );
                                    array_push($invaliddata, $data);
                                    $invalidcount++;
                                } else {

                                    $AutoItemCode = generatedItemCode($conn);
                                    $query = ("INSERT INTO titem (itemcode, itemname, itemdesc, categorycode, uomcode, itemtype, sellingprice, createdby, createddate) 
                                               VALUES (?,?,?,(SELECT categorycode FROM tcategory WHERE category = ?),(SELECT uomcode FROM tuom WHERE uom = ?),?,?,?, CURRENT_TIMESTAMP)");
                                    $stmt = $conn->prepare($query);
                                    $stmt->bind_param("ssssssss", $AutoItemCode, $ItemName, $ItemDesc, $Category, $UOM, $ItemType, $SellingPrice, $userLogin);
                                    $stmt->execute();

                                    if ($stmt->affected_rows > 0) {
                                        $coba .= $AutoItemCode . ",";
                                        $successcount++;
                                    } else {
                                        saveErrorLog($stmt->error, "Item", "Import", $userLogin, $conn);
                                    }
                                }
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

                    if ($invalidcount == 0 && $successcount > 0) {
                        $json->success = true;
                        $json->datainvalid = $invaliddata;
                        $json->totalsuccess = $coba;
                        $json->totalinvalid = $invalidcount;
                        $json->msg = "Import berhasil.";
                        $jsonstring = json_encode($json);
                        echo $jsonstring;
                    } else if ($invalidcount > 0 && $successcount > 0) {
                        $json->success = true;
                        $json->datainvalid = $invaliddata;
                        $json->totalsuccess = $successcount;
                        $json->totalinvalid = $invalidcount;
                        $json->msg = "Import berhasil dengan kesalahan. Lihat ringkasan Import.";
                        $jsonstring = json_encode($json);
                        echo $jsonstring;
                    } else if ($invalidcount > 0 && $successcount == 0) {
                        $json->success = false;
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
        $errorMsg = 'Error on line ' . $e->getLine() . ' in ' . $e->getFile() . ': ' . $e->getMessage();
        saveErrorLog($errorMsg, "Item", "Import", $userLogin, $conn);
        $json->success = false;
        $json->datainvalid = $invaliddata;
        $json->totalsuccess = $successcount;
        $json->totalinvalid = $invalidcount;
        $json->msg = "[ERROR] Terjadi kesalahan, harap hubungi teknisi.";
        $jsonstring = json_encode($json);
        echo $jsonstring;
    } finally {
        closeConn($conn);
    }
} else {
    $json->success = false;
    $json->msg = "Silahkan login kedalam aplikasi!";
    $jsonstring = json_encode($json);
    echo $jsonstring;
}

function checkvaliddata($conn, $itemname, $category, $uom, $itemtype, $itemdesc, $sellingprice)
{
    $returnarr = array();

    $data = array(
        'valid' => true,
        'msg' => ""
    );
    array_push($returnarr, $data);

    if ($itemname == "") {
        $returnarr[0]["valid"] = false;
        if ($returnarr[0]["msg"] == "") {
            $returnarr[0]["msg"] = "Silahkan isi kolom Nama Barang.";
        }
    }

    if ($category == "") {
        $returnarr[0]["valid"] = false;
        if ($returnarr[0]["msg"] == "") {
            $returnarr[0]["msg"] = "Silahkan isi kolom Kategori.";
        }
    }

    if ($uom == "") {
        $returnarr[0]["valid"] = false;
        if ($returnarr[0]["msg"] == "") {
            $returnarr[0]["msg"] = "Silahkan isi kolom Satuan.";
        }
    }

    if ($itemtype == "" && ($uom == "KG" || $uom == "SAK")) {
        $returnarr[0]["valid"] = false;
        if ($returnarr[0]["msg"] == "") {
            $returnarr[0]["msg"] = "Silahkan isi kolom Jenis Barang.";
        }
    }

    if ($itemdesc == "") {
        $returnarr[0]["valid"] = false;
        if ($returnarr[0]["msg"] == "") {
            $returnarr[0]["msg"] = "Silahkan isi kolom Deskripsi Item.";
        }
    }

    if ($sellingprice == "") {
        $returnarr[0]["valid"] = false;
        if ($returnarr[0]["msg"] == "") {
            $returnarr[0]["msg"] = "Silahkan isi kolom Harga Jual.";
        }
    }

    if (strlen($itemname) > 100) {
        $returnarr[0]["valid"] = false;
        if ($returnarr[0]["msg"] == "") {
            $returnarr[0]["msg"] = "Maximum karakter untuk Nama Barang adalah 100.";
        }
    }

    if (strlen($category) > 20) {
        $returnarr[0]["valid"] = false;
        if ($returnarr[0]["msg"] == "") {
            $returnarr[0]["msg"] = "Maximum karakter untuk Kategori adalah 20.";
        }
    }

    if (strlen($uom) > 6) {
        $returnarr[0]["valid"] = false;
        if ($returnarr[0]["msg"] == "") {
            $returnarr[0]["msg"] = "Maximum karakter untuk Satuan adalah 6.";
        }
    }

    if (strlen($itemtype) > 10) {
        $returnarr[0]["valid"] = false;
        if ($returnarr[0]["msg"] == "") {
            $returnarr[0]["msg"] = "Maximum karakter untuk Jenis Barang adalah 10.";
        }
    }

    if (strlen($itemdesc) > 2000) {
        $returnarr[0]["valid"] = false;
        if ($returnarr[0]["msg"] == "") {
            $returnarr[0]["msg"] = "Maximum karakter untuk Deskripsi Barang adalah 2000.";
        }
    }

    if ($category != "") {
        $query = ("SELECT categorycode FROM tcategory WHERE category = ?");
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $category);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows <= 0) {
            $returnarr[0]["valid"] = false;
            if ($returnarr[0]["msg"] == "") {
                $returnarr[0]["msg"] = "Kategori tidak terdaftar didalam sistem.";
            }
        }
    }

    if ($uom != "") {
        $query = ("SELECT uomcode FROM tuom WHERE uom = ?");
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $uom);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows <= 0) {
            $returnarr[0]["valid"] = false;
            if ($returnarr[0]["msg"] == "") {
                $returnarr[0]["msg"] = "Satuan tidak terdaftar didalam sistem.";
            }
        }
    }

    if ($itemtype != "" && ($uom == "KG" || $uom == "SAK")) {
        $settingid = "";
        if ($uom == "KG") {
            $settingid = "ItemTypeUOMKG";
        } else if ($uom == "SAK") {
            $settingid = "ItemTypeUOMSAK";
        }

        $query = ("SELECT settingvalue FROM tglobalsetting WHERE settingid = ?");
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $settingid);
        $stmt->execute();
        $result = $stmt->get_result();

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
                    if ($itemtype == $settingvalue) {
                        $hasvalue = true;
                    }
                }

                if (!$hasvalue) {
                    $returnarr[0]["valid"] = false;
                    if ($returnarr[0]["msg"] == "") {
                        $returnarr[0]["msg"] = "Jenis Barang tidak terdaftar didalam sistem.";
                    }
                }
            } else {
                $returnarr[0]["valid"] = false;
                if ($returnarr[0]["msg"] == "") {
                    $returnarr[0]["msg"] = "Jenis Barang tidak terdaftar didalam sistem.";
                }
            }
        } else {
            $returnarr[0]["valid"] = false;
            if ($returnarr[0]["msg"] == "") {
                $returnarr[0]["msg"] = "Jenis Barang tidak terdaftar didalam sistem.";
            }
        }
    }

    if ($sellingprice != "") {
        $isINT = is_int($sellingprice);
        if (!$isINT) {
            $returnarr[0]["valid"] = false;
            if ($returnarr[0]["msg"] == "") {
                $returnarr[0]["msg"] = "Silahkan isi kolom Harga Jual dengan Angka.";
            }
        } else {

            $sellingprice = (int)$sellingprice;
            if ($sellingprice > 2147483647) {
                $returnarr[0]["valid"] = false;
                if ($returnarr[0]["msg"] == "") {
                    $returnarr[0]["msg"] = "Maximum nilai untuk Harga Jual adalah 2147483647.";
                }
            }

            if ($sellingprice <= 0) {
                $returnarr[0]["valid"] = false;
                if ($returnarr[0]["msg"] == "") {
                    $returnarr[0]["msg"] = "Harga Jual tidak boleh kurang dari 0.";
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
