<?php

include '../../config/appname.php';
include '../../config/dbconnection.php';
include '../../config/errorlog.php';
include '../../assets/library/printdriver/vendor/autoload.php';

use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

session_name($encryptedAppName);
session_start();

$json = new stdClass();
$conn = openConn();
$userLogin = mysqli_real_escape_string($conn, $_SESSION['userid']);
$query = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $postdata = file_get_contents('php://input');
    $postdata = json_decode($postdata);
}

if (!empty($userLogin)) {
    try {

        $isPrintActive = getGlobalSetting($conn, "isPrintActive");

        if (strtoupper($isPrintActive) == "TRUE") {

            $ItemList = $postdata->ItemList;
            $printerName = getGlobalSetting($conn, "PrinterName");
            $addressStore = getGlobalSetting($conn, "AddressStore");
            $cashierName = getFullName($conn, $userLogin);
            $items = array();

            $tz = 'Asia/Jakarta';
            $dt = new DateTime("now", new DateTimeZone($tz));
            $date = $dt->format('Y-m-d G:i:s');

            /* Open the printer; this will change depending on how it is connected */
            $connector = new WindowsPrintConnector($printerName);
            $printer = new Printer($connector);

            /* Print top logo */
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            // $printer -> graphics($logo);

            /* Name of shop */
            $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $printer->text("Toko Berkat\n");
            $printer->text("Tani & Ternak\n");
            $printer->selectPrintMode();
            $printer->setFont(Printer::FONT_B);
            $printer->text($addressStore . "\n");
            $printer->feed();

            // Data transaksi
            $printer->initialize();
            $printer->text("Kasir : " . $cashierName . "\n");
            $printer->text("Waktu : " . $date . "\n");

            // Membuat tabel
            $printer->initialize(); // Reset bentuk/jenis teks
            $printer->text("--------------------------------\n");

            $Total = 0;

            if (count($ItemList) > 0) {
                for ($i = 0; $i < count($ItemList); $i++) {
                    $ItemCode = $ItemList[$i]->ItemCode;
                    $ItemName = $ItemList[$i]->ItemName;
                    $Qty = $ItemList[$i]->Qty;
                    $PurchasePrice = $ItemList[$i]->PurchasePrice;
                    $Discount = $ItemList[$i]->Discount;
                    $SubTotal = (int)$Qty * (int)$PurchasePrice;
                    $SubTotal = $SubTotal - $Discount;

                    $Total = $Total + $SubTotal;

                    $printer->setEmphasis(true);
                    $printer->text($ItemName . "\n");
                    $printer->setEmphasis(false);
                    $printer->text(buatBaris3Kolom($Qty . "x", "@Rp" . number_format($PurchasePrice, 0, ",", "."), "Rp" . number_format($SubTotal, 0, ",", ".")));

                    if ((int)$Discount > 0) {
                        $printer->setFont(Printer::FONT_B);
                        $printer->text(buatBaris2Kolom("", "", "Diskon Rp" . number_format($Discount, 0, ",", ".")));
                    }

                    $printer->text("\n");
                    $printer->initialize();
                }
            }

            $printer->text("--------------------------------\n");
            $printer->text(buatBaris3Kolom('', 'Total', "Rp" . number_format($Total, 0, ",", ".")));
            $printer->feed(3);

            // Pesan penutup
            $printer->initialize();
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("Mohon diperiksa kembali \n");
            $printer->text("produk yang telah dibeli. \n");
            $printer->text("Terimakasih telah berbelanja di toko kami.\n");

            $printer->feed(3); // mencetak 5 baris kosong agar terangkat (pemotong kertas saya memiliki jarak 5 baris dari toner)
            $printer->close();

            $json->success = true;
            $json->msg = "Struk berhasil dicetak.";
            $jsonstring = json_encode($json);
            echo $jsonstring;
        } else {

            $json->success = true;
            $json->msg = "Fitur cetak struk di nonaktifkan, tidak ada struk yang di cetak.";
            $jsonstring = json_encode($json);
            echo $jsonstring;
        }
    } catch (\Throwable $e) {
        $errorMsg = 'Error on line ' . $e->getLine() . ' in ' . $e->getFile() . ': ' . $e->getMessage();
        saveErrorLog($errorMsg, "Print Reciept", "Print", $userLogin, $conn);
        $stmt->close();
        $json->success = false;
        $json->msg = "[ERROR] Terjadi kesalahan saat print struk, harap hubungi teknisi.";
        $jsonstring = json_encode($json);
        echo $jsonstring;
    }
} else {
    if (strtoupper($isPrintActive) == "TRUE") {
        $printer->close();
    }
    $json->success = false;
    $json->msg = "Silahkan login kedalam aplikasi!";
    $jsonstring = json_encode($json);
    echo $jsonstring;
}

function getGlobalSetting($conn, $SettingID)
{
    $query = ("SELECT settingvalue
               FROM tglobalsetting
               WHERE settingid = ?");
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $SettingID);
    $stmt->execute();
    $result = $stmt->get_result();
    $SettingValue = "";

    if ($result->num_rows > 0) {
        while ($data = $result->fetch_assoc()) {
            $SettingValue = $data['settingvalue'];
        }
    }

    return $SettingValue;
}

function getFullName($conn, $UserID)
{
    $query = ("SELECT fullname
               FROM tuser
               WHERE userid = ?");
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $UserID);
    $stmt->execute();
    $result = $stmt->get_result();
    $FullName = "";

    if ($result->num_rows > 0) {
        while ($data = $result->fetch_assoc()) {
            $FullName = $data['fullname'];
        }
    }

    return $FullName;
}

// membuat fungsi untuk membuat 1 baris tabel, agar dapat dipanggil berkali-kali dgn mudah
function buatBaris3Kolom($kolom1, $kolom2, $kolom3)
{
    // Mengatur lebar setiap kolom (dalam satuan karakter)
    $lebar_kolom_1 = 7;
    $lebar_kolom_2 = 10;
    $lebar_kolom_3 = 13;

    // Melakukan wordwrap(), jadi jika karakter teks melebihi lebar kolom, ditambahkan \n 
    $kolom1 = wordwrap($kolom1, $lebar_kolom_1, "\n", true);
    $kolom2 = wordwrap($kolom2, $lebar_kolom_2, "\n", true);
    $kolom3 = wordwrap($kolom3, $lebar_kolom_3, "\n", true);

    // Merubah hasil wordwrap menjadi array, kolom yang memiliki 2 index array berarti memiliki 2 baris (kena wordwrap)
    $kolom1Array = explode("\n", $kolom1);
    $kolom2Array = explode("\n", $kolom2);
    $kolom3Array = explode("\n", $kolom3);

    // Mengambil jumlah baris terbanyak dari kolom-kolom untuk dijadikan titik akhir perulangan
    $jmlBarisTerbanyak = max(count($kolom1Array), count($kolom2Array), count($kolom3Array));

    // Mendeklarasikan variabel untuk menampung kolom yang sudah di edit
    $hasilBaris = array();

    // Melakukan perulangan setiap baris (yang dibentuk wordwrap), untuk menggabungkan setiap kolom menjadi 1 baris 
    for ($i = 0; $i < $jmlBarisTerbanyak; $i++) {

        // memberikan spasi di setiap cell berdasarkan lebar kolom yang ditentukan, 
        $hasilKolom1 = str_pad((isset($kolom1Array[$i]) ? $kolom1Array[$i] : ""), $lebar_kolom_1, " ");
        $hasilKolom2 = str_pad((isset($kolom2Array[$i]) ? $kolom2Array[$i] : ""), $lebar_kolom_2, " ");

        // memberikan rata kanan pada kolom 3 dan 4 karena akan kita gunakan untuk harga dan total harga
        $hasilKolom3 = str_pad((isset($kolom3Array[$i]) ? $kolom3Array[$i] : ""), $lebar_kolom_3, " ", STR_PAD_LEFT);

        // Menggabungkan kolom tersebut menjadi 1 baris dan ditampung ke variabel hasil (ada 1 spasi disetiap kolom)
        $hasilBaris[] = $hasilKolom1 . " " . $hasilKolom2 . " " . $hasilKolom3;
    }

    // Hasil yang berupa array, disatukan kembali menjadi string dan tambahkan \n disetiap barisnya.
    return implode("\n", $hasilBaris);
}


// membuat fungsi untuk membuat 1 baris tabel, agar dapat dipanggil berkali-kali dgn mudah
function buatBaris2Kolom($kolom1, $kolom2, $kolom3)
{
    // Mengatur lebar setiap kolom (dalam satuan karakter)    
    $lebar_kolom_1 = 7;
    $lebar_kolom_2 = 10;
    $lebar_kolom_3 = 23;

    // Melakukan wordwrap(), jadi jika karakter teks melebihi lebar kolom, ditambahkan \n 
    $kolom1 = wordwrap($kolom1, $lebar_kolom_1, "\n", true);
    $kolom2 = wordwrap($kolom2, $lebar_kolom_2, "\n", true);
    $kolom3 = wordwrap($kolom3, $lebar_kolom_3, "\n", true);

    // Merubah hasil wordwrap menjadi array, kolom yang memiliki 2 index array berarti memiliki 2 baris (kena wordwrap)
    $kolom1Array = explode("\n", $kolom1);
    $kolom2Array = explode("\n", $kolom2);
    $kolom3Array = explode("\n", $kolom3);

    // Mengambil jumlah baris terbanyak dari kolom-kolom untuk dijadikan titik akhir perulangan
    $jmlBarisTerbanyak = max(count($kolom1Array), count($kolom2Array), count($kolom3Array));

    // Mendeklarasikan variabel untuk menampung kolom yang sudah di edit
    $hasilBaris = array();

    // Melakukan perulangan setiap baris (yang dibentuk wordwrap), untuk menggabungkan setiap kolom menjadi 1 baris 
    for ($i = 0; $i < $jmlBarisTerbanyak; $i++) {

        // memberikan spasi di setiap cell berdasarkan lebar kolom yang ditentukan, 
        $hasilKolom1 = str_pad((isset($kolom1Array[$i]) ? $kolom1Array[$i] : ""), $lebar_kolom_1, " ");
        $hasilKolom2 = str_pad((isset($kolom2Array[$i]) ? $kolom2Array[$i] : ""), $lebar_kolom_2, " ");

        // memberikan rata kanan pada kolom 3 dan 4 karena akan kita gunakan untuk harga dan total harga
        $hasilKolom3 = str_pad((isset($kolom3Array[$i]) ? $kolom3Array[$i] : ""), $lebar_kolom_3, " ", STR_PAD_LEFT);

        // Menggabungkan kolom tersebut menjadi 1 baris dan ditampung ke variabel hasil (ada 1 spasi disetiap kolom)
        $hasilBaris[] = $hasilKolom1 . " " . $hasilKolom2 . " " . $hasilKolom3;
    }

    // Hasil yang berupa array, disatukan kembali menjadi string dan tambahkan \n disetiap barisnya.
    return implode("\n", $hasilBaris);
}
