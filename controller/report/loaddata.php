<?php
include '../../config/appname.php';
include '../../config/dbconnection.php';
include '../../config/errorlog.php';

session_name($encryptedAppName);
session_start();

$json = new stdClass();
$conn = openConn();
$userLogin = mysqli_real_escape_string($conn, $_SESSION['userid']);
$query = "";

if (!empty($userLogin)) {
    try {
        $Search = mysqli_real_escape_string($conn, $_GET['search']);
        $ItemCode = mysqli_real_escape_string($conn, $_GET['ItemCode']);
        $FromDate = mysqli_real_escape_string($conn, $_GET['FromDate']);
        $ToDate = mysqli_real_escape_string($conn, $_GET['ToDate']);
        $TransType = mysqli_real_escape_string($conn, $_GET['TransType']);
        $resultdata = array();

        $query = ("SELECT A.transactionid, A.transactiontype, A.transactiondate, B.itemcode, C.itemname, C.categorycode, D.category, C.uomcode, E.uom, C.itemtype, B.purchaseprice, B.qty, IFNULL(B.discount, 0) as discount, (B.purchaseprice * B.qty) AS subtotal, A.remark, F.fullname as cashiername
        FROM ttransaction A
        INNER JOIN ttransactiondet B ON A.transactionid = B.transactionid
        INNER JOIN titem C ON B.itemcode = C.itemcode
        INNER JOIN tcategory D ON C.categorycode = D.categorycode
        INNER JOIN tuom E ON C.uomcode = E.uomcode
        INNER JOIN tuser F ON A.createdby = F.userid
        WHERE (A.transactiontype LIKE ? OR B.itemcode LIKE ? OR C.itemname LIKE ? OR D.category LIKE ? OR E.UOM LIKE ? OR C.itemtype LIKE ?) AND (A.transactiondate BETWEEN ? AND ?)");

        $defaultParam = "ssssssss";
        $Search = '%' . $Search . '%';
        $arrParam = array($Search, $Search, $Search, $Search, $Search, $Search, $FromDate, $ToDate);

        if ($ItemCode != "") {
            $query .= " AND B.itemcode = ?";
            $defaultParam .= "s";
            array_push($arrParam, $ItemCode);
        }

        if ($TransType != "") {
            $query .= " AND A.transactiontype = ?";
            $defaultParam .= "s";
            array_push($arrParam, $TransType);
        }

        $query .= " ORDER BY A.transactiondate DESC";

        $stmt = $conn->prepare($query);
        $stmt->bind_param($defaultParam, ...$arrParam);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($data = $result->fetch_assoc()) {
                $data = array(
                    'TransactionID' => $data['transactionid'],
                    'TransactionType' => $data['transactiontype'],
                    'TransactionDate' => $data['transactiondate'],
                    'ItemCode' => $data['itemcode'],
                    'ItemName' => $data['itemname'],
                    'CategoryCode' => $data['categorycode'],
                    'Category' => $data['category'],
                    'UOMCode' => $data['uomcode'],
                    'UOM' => $data['uom'],
                    'ItemType' => $data['itemtype'],
                    'PurchasePrice' => $data['purchaseprice'],
                    'Qty' => $data['qty'],
                    'Discount' => $data['discount'],
                    'SubTotal' => $data['subtotal'],                    
                    'Remark' => $data['remark'],                  
                    'CashierName' => $data['cashiername']
                );
                array_push($resultdata, $data);
            }

            $json->success = true;
            $json->data = $resultdata;
            $jsonstring = json_encode($json);
            echo $jsonstring;
        } else {
            $json->success = true;
            $json->data = $resultdata;
            $jsonstring = json_encode($json);
            echo $jsonstring;
        }
        $stmt->close();
    } catch (\Throwable $e) {
        $errorMsg = 'Error on line ' . $e->getLine() . ' in ' . $e->getFile() . ': ' . $e->getMessage();
        saveErrorLog($errorMsg, "Report", "Load Data", $userLogin, $conn);
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
