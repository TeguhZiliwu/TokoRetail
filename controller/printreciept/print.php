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
        $DataPrinter = $postdata->DataPrinter;
        $printerName = getGlobalSetting($conn, "PrinterName");
        $items = array();

        /* Open the printer; this will change depending on how it is connected */
        $connector = new WindowsPrintConnector($printerName);
        $printer = new Printer($connector);

        if (count($DataPrinter) > 0) {
            for ($i = 0; $i < count($DataPrinter); $i++) {
                $ItemName = $DataPrinter[$i]->ItemName;
                $Qty = $DataPrinter[$i]->Qty;
                $ItemPrice = $DataPrinter[$i]->status;
                /* Information for the receipt */
                array_push($items, new item("Example item #1", "Rp2.500.000"));
            }
        }
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
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        // $printer -> graphics($logo);

        /* Name of shop */
        $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
        $printer->text("Toko Berkat\n");
        $printer->text("Tani & Ternak\n");
        $printer->selectPrintMode();
        $printer->text("Jl. Pertanian Blok B No 2 Kebun Raya.\n");
        $printer->feed();

        /* Line */
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("Tanggal : " . $date . "\n");
        $printer->text("Kasir : Teguh Ziliwu\n");
        $printer->setEmphasis(false);
        $printer->text("--------------------------------\n");
        $printer->setEmphasis(false);

        /* Items */
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->setEmphasis(false);
        foreach ($items as $item) {
            $printer->text($item);
        }
        $printer->setEmphasis(true);
        $printer->text($subtotal);
        $printer->setEmphasis(false);
        $printer->feed();

        /* Tax and total */
        $printer->text($tax);
        $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
        $printer->text($total);
        $printer->selectPrintMode();

        /* Line */
        $printer->feed(2);
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->setEmphasis(false);
        $printer->text("--------------------------------\n");
        $printer->setEmphasis(false);

        /* Footer */
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("Mohon diperiksa kembali \n");
        $printer->text("produk yang telah dibeli. \n");
        $printer->text("Terimakasih telah berbelanja di toko kami.\n");

        /* Cut the receipt and open the cash drawer */
        $printer->cut();
        $printer->pulse();

        $printer->close();
    } catch (\Throwable $e) {
        $errorMsg = 'Error on line ' . $e->getLine() . ' in ' . $e->getFile() . ': ' . $e->getMessage();
        saveErrorLog($errorMsg, "Print Reciept", "Print", $userLogin, $conn);
        $stmt->close();
        $json->success = false;
        $json->msg = "[ERROR] Terjadi kesalahan, harap hubungi teknisi.";
        $jsonstring = json_encode($json);
        echo $jsonstring;
    }
} else {
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

/* A wrapper to do organise item names & prices into columns */
class item
{
    private $name;
    private $price;
    private $dollarSign;

    public function __construct($name = '', $price = '', $dollarSign = false)
    {
        $this->name = $name;
        $this->price = $price;
        $this->dollarSign = $dollarSign;
    }

    public function __toString()
    {
        $rightCols = 25;
        $leftCols = 38;
        if ($this->dollarSign) {
            $leftCols = $leftCols / 2 - $rightCols / 2;
        }
        $left = str_pad($this->name, $leftCols);

        $sign = ($this->dollarSign ? '$ ' : '');
        $right = str_pad($sign . $this->price, $rightCols, ' ', STR_PAD_LEFT);
        return "$left$right\n";
    }
}
