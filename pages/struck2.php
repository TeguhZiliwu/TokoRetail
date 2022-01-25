<?php

require '../assets/library/printdriver/vendor/autoload.php';

use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

/* Open the printer; this will change depending on how it is connected */
$connector = new WindowsPrintConnector("POS-58-Series");
$printer = new Printer($connector);

/* Information for the receipt */
$items = array(
    new item("Example item #1", "Rp3.000.000"),
    new item("2x @Rp1.500.000 Rp3.000.000"),
    new item("Another thing", "Rp100.000"),
    new item("Something else", "1.00"),
    new item("A final item", "4.45"),
);
$subtotal = new item('Subtotal', '12.95');
$tax = new item('A local tax', '1.30');
$total = new item('Total', '14.25', true);
/* Date is kept the same for testing */
// $date = date('l jS \of F Y h:i:s A');
$date = date("Y-m-d H:i:s");

/* Start the printer */
// $logo = EscposImage::load("resources/escpos-php.png", false);
$printer = new Printer($connector);

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

/* Line */
$printer -> setJustification(Printer::JUSTIFY_LEFT);
$printer -> text("Tanggal : ". $date . "\n");
$printer -> text("Kasir : Teguh Ziliwu\n");
$printer -> setEmphasis(false);
$printer -> text("--------------------------------\n");
$printer -> setEmphasis(false);

/* Items */
$printer -> setJustification(Printer::JUSTIFY_CENTER);
$printer -> setEmphasis(false);
foreach ($items as $item) {
    $printer -> text($item);
}
$printer -> setEmphasis(true);
$printer -> text($subtotal);
$printer -> setEmphasis(false);
$printer -> feed();

/* Tax and total */
$printer -> text($tax);
$printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
$printer -> text($total);
$printer -> selectPrintMode();


/* Line */
$printer -> feed(2);
$printer -> setJustification(Printer::JUSTIFY_CENTER);
$printer -> setEmphasis(false);
$printer -> text("--------------------------------\n");
$printer -> setEmphasis(false);

/* Footer */
$printer -> setJustification(Printer::JUSTIFY_CENTER);
$printer -> text("Mohon diperiksa kembali \n");
$printer -> text("produk yang telah dibeli. \n");
$printer -> text("Terimakasih telah berbelanja di toko kami.\n");

/* Cut the receipt and open the cash drawer */
$printer -> cut();
$printer -> pulse();

$printer -> close();

/* A wrapper to do organise item names & prices into columns */
class item
{
    private $name;
    private $price;
    private $dollarSign;

    public function __construct($name = '', $price = '', $dollarSign = false)
    {
        $this -> name = $name;
        $this -> price = $price;
        $this -> dollarSign = $dollarSign;
    }

    public function __toString()
    {
        $rightCols = 10;
        $leftCols = 22;
        if ($this -> dollarSign) {
            $leftCols = $leftCols / 2 - $rightCols / 2;
        }
        $left = str_pad($this -> name, $leftCols) ;

        $sign = ($this -> dollarSign ? '$ ' : '');
        $right = str_pad($sign . $this -> price, $rightCols, ' ', STR_PAD_LEFT);
        return "$left$right\n";
    }
}