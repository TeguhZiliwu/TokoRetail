<?php
require '../assets/library/printdriver/vendor/autoload.php';

use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

/* Open the printer; this will change depending on how it is connected */
$connector = new WindowsPrintConnector("POS-58-Series");
$printer = new Printer($connector);
$date = date("Y-m-d H:i:s");
  
/* Print top logo */
$printer -> setJustification(Printer::JUSTIFY_CENTER);
// $printer -> graphics($logo);

/* Name of shop */
$printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
$printer -> text("Toko Berkat\n");
$printer -> text("Tani & Ternak\n");
$printer -> selectPrintMode();
$printer -> text("Jl. Pertanian Blok B No 2 Kebun Raya.\n");
$printer -> feed();

// Data transaksi
$printer->initialize();
$printer->text("Kasir : Teguh Ziliwu\n");
$printer->text("Waktu : " . $date . "\n");

// Membuat tabel
$printer->initialize(); // Reset bentuk/jenis teks
$printer->text("--------------------------------\n");
$printer->setEmphasis(true);
$printer->text("Ice Lychee Tea\n");
$printer->setEmphasis(false);
$printer->text(buatBaris3Kolom("2x", "@Rp15.000", "Rp30.000"));
$printer->setEmphasis(true);
$printer->text("Hot/Ice Matcha\n");
$printer->setEmphasis(false);
$printer->text(buatBaris3Kolom("2x", "@Rp5.000", "Rp10.000"));
$printer->setEmphasis(true);
$printer->text("Cajun Spicy Fries\n");
$printer->setEmphasis(false);
$printer->text(buatBaris3Kolom("1x", "@Rp8.200", "Rp16.400"));
$printer -> text("--------------------------------\n");
$printer->text(buatBaris3Kolom('', 'Total', "56.400"));
$printer->text("\n");
$printer->feed(3);

 // Pesan penutup
$printer->initialize();
$printer -> setJustification(Printer::JUSTIFY_CENTER);
$printer -> text("Mohon diperiksa kembali \n");
$printer -> text("produk yang telah dibeli. \n");
$printer -> text("Terimakasih telah berbelanja di toko kami.\n");

$printer->feed(3); // mencetak 5 baris kosong agar terangkat (pemotong kertas saya memiliki jarak 5 baris dari toner)
$printer->close();


// membuat fungsi untuk membuat 1 baris tabel, agar dapat dipanggil berkali-kali dgn mudah
function buatBaris3Kolom($kolom1, $kolom2, $kolom3) {
    // Mengatur lebar setiap kolom (dalam satuan karakter)
    $lebar_kolom_1 = 10;
    $lebar_kolom_2 = 10;
    $lebar_kolom_3 = 10;

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