<?php
include '../config/appname.php';
include '../config/dbconnection.php';
include '../config/errorlog.php';
include '../assets/library/fpdf184/fpdf.php';

session_name($encryptedAppName);
session_start();

$json = new stdClass();
$conn = openConn();
$userLogin = mysqli_real_escape_string($conn, $_SESSION['userid']);
$query = "";

if (!empty($userLogin)) {
    try {
        $Search = "";
        $resultdata = array();

        $query = ("SELECT categorycode, category, categorydesc, createdby, createddate, updatedby, updateddate FROM tcategory WHERE (categorycode LIKE ? OR category LIKE ? OR categorydesc LIKE ?)");
        $stmt = $conn->prepare($query);
        $Search = '%' . $Search . '%';
        $stmt->bind_param("sss", $Search, $Search, $Search);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            //A4 width : 219mm
            //default margin : 10mm each side
            //writable horizontal : 219-(10*2)=189mm

            //create pdf object
            $pdf = new FPDF('P', 'mm', 'A4');
            //add new page
            $pdf->AddPage();
            //set font to arial, bold, 14pt
            $pdf->SetFont('Arial', 'B', 14);

            //Cell(width , height , text , border , end line , [align] )

            $pdf->Cell(130, 5, 'TOKO BERKAT TANI & TERNAK', 0, 1);

            //set font to arial, regular, 12pt
            $pdf->SetFont('Arial', '', 12);

            $pdf->Cell(130, 5, 'Jl. Laksamana Madya, Batu Aji', 0, 0);
            $pdf->Cell(59, 5, '', 0, 1); //end of line

            $pdf->Cell(130, 5, 'Batam, Indonesia', 0, 0);
            $pdf->Cell(30, 5, 'Date', 0, 0);
            $pdf->Cell(34, 5, '[dd/mm/yyyy]', 0, 1); //end of line

            $pdf->Cell(130, 5, 'Phone [+12345678]', 0, 0);
            $pdf->Cell(30, 5, 'Transaction ID', 0, 0);
            $pdf->Cell(34, 5, '[1234567]', 0, 1); //end of line

            //make a dummy empty cell as a vertical spacer
            $pdf->Cell(189, 10, '', 0, 1); //end of line

            //invoice contents
            $pdf->SetFont('Arial', 'B', 12);

            $pdf->Cell(80, 5, 'Nama Barang', 1, 0);
            $pdf->Cell(25, 5, 'Jumlah', 1, 0);
            $pdf->Cell(25, 5, 'Harga', 1, 0);
            $pdf->Cell(25, 5, 'Diskon', 1, 0);
            $pdf->Cell(34, 5, 'Sub Total', 1, 1); //end of line

            $pdf->SetFont('Arial', '', 12);

            //Numbers are right-aligned so we give 'R' after new line parameter
            
            $pdf->Cell(80, 5, 'UltraCool Fridge', 1, 0);
            $pdf->Cell(25, 5, '2', 1, 0, 'R');
            $pdf->Cell(25, 5, 'Rp 6.500', 1, 0, 'R');
            $pdf->Cell(25, 5, 'Rp 0', 1, 0, 'R');
            $pdf->Cell(34, 5, '6500', 1, 1, 'R'); //end of line
            
            $pdf->Cell(80, 5, 'UltraCool FridgeSupaclean Diswasher', 1, 0);
            $pdf->Cell(25, 5, '2', 1, 0, 'R');
            $pdf->Cell(25, 5, 'Rp 6.500', 1, 0, 'R');
            $pdf->Cell(25, 5, 'Rp 0', 1, 0, 'R');
            $pdf->Cell(34, 5, '6500', 1, 1, 'R'); //end of line

            $pdf->Cell(130, 5, 'Something Else', 1, 0);
            $pdf->Cell(25, 5, '-', 1, 0);
            $pdf->Cell(34, 5, '1,000', 1, 1, 'R'); //end of line

            //summary
            $pdf->Cell(130, 5, '', 0, 0);
            $pdf->Cell(25, 5, 'Subtotal', 0, 0);
            $pdf->Cell(4, 5, '$', 1, 0);
            $pdf->Cell(30, 5, '4,450', 1, 1, 'R'); //end of line

            $pdf->Cell(130, 5, '', 0, 0);
            $pdf->Cell(25, 5, 'Taxable', 0, 0);
            $pdf->Cell(4, 5, '$', 1, 0);
            $pdf->Cell(30, 5, '0', 1, 1, 'R'); //end of line

            $pdf->Cell(130, 5, '', 0, 0);
            $pdf->Cell(25, 5, 'Tax Rate', 0, 0);
            $pdf->Cell(4, 5, '$', 1, 0);
            $pdf->Cell(30, 5, '10%', 1, 1, 'R'); //end of line

            $pdf->Cell(130, 5, '', 0, 0);
            $pdf->Cell(25, 5, 'Total Due', 0, 0);
            $pdf->Cell(4, 5, '$', 1, 0);
            $pdf->Cell(30, 5, '4,450', 1, 1, 'R'); //end of line
            //output the result
            $pdf->Output();
        } else {
            $json->success = true;
            $json->data = $resultdata;
            $jsonstring = json_encode($json);
            echo $jsonstring;
        }
        $stmt->close();
    } catch (\Throwable $e) {
        $errorMsg = 'Error on line ' . $e->getLine() . ' in ' . $e->getFile() . ': ' . $e->getMessage();
        saveErrorLog($errorMsg, "Category", "Load Data", $userLogin, $conn);
        $json->success = false;
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
