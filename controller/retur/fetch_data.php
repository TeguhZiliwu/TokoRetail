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
        $FetchData = mysqli_real_escape_string($conn, $_GET['FetchData']);
        $result = "";

        if ($FetchData == "getTransactionDet") { 
            $TransactionID = mysqli_real_escape_string($conn, $_GET['TransactionID']);
            $resultdata = array();
            $query = ("SELECT A.transactionid, A.transactiondate, f.fullname AS 'cashier', B.itemcode,
            C.itemname, D.categorydesc, E.uom, C.itemtype, B.qty, B.purchaseprice, IFNULL(B.discount, 0) AS 'discount', ((B.purchaseprice * B.qty) - B.discount) AS 'subtotal'
            FROM ttransaction A
            LEFT JOIN ttransactiondet B ON A.transactionid = B.transactionid
            LEFT JOIN titem C ON B.itemcode = C.itemcode
            LEFT JOIN tcategory D ON D.categorycode = C.categorycode
            LEFT JOIN tuom E ON C.uomcode = E.uomcode
            LEFT JOIN tuser F ON A.createdby = F.userid
            WHERE A.transactiontype = 'OUT' AND A.transactionid = ?");
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $TransactionID);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($data = $result->fetch_assoc()) {
                    $data = array(
                        'TransactionID' => $data['transactionid'],
                        'TransactionDate' => $data['transactiondate'],
                        'Cashier' => $data['cashier'],
                        'ItemCode' => $data['itemcode'],
                        'ItemName' => $data['itemname'],
                        'Category' => $data['categorydesc'],
                        'UOM' => $data['uom'],
                        'ItemType' => $data['itemtype'],
                        'PurchasePrice' => $data['purchaseprice'],
                        'Qty' => $data['qty'],
                        'Discount' => $data['discount'],
                        'SubTotal' => $data['subtotal'],
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
                $json->msg = "Data transaksi tidak ditemukan.";
                $jsonstring = json_encode($json);
                echo $jsonstring;
            }
            $stmt->close();
        }
    } catch (\Throwable $e) {
        $errorMsg = 'Error on line ' . $e->getLine() . ' in ' . $e->getFile() . ': ' . $e->getMessage();
        saveErrorLog($errorMsg, "Retur", "Fetch Data", $userLogin, $conn);
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
