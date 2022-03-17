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
        $FromDate = mysqli_real_escape_string($conn, $_GET['FromDate']);
        $ToDate = mysqli_real_escape_string($conn, $_GET['ToDate']);
        $resultdata = array();

        $query = ("SELECT IFNULL(TotalOut, 0) AS TotalOut, IFNULL(TotalIn, 0) AS TotalIn, IFNULL((TotalOut - TotalIn), 0) AS TotalProfit
        FROM (SELECT SUM(CASE
                WHEN A.transactiontype = 'OUT' THEN ((B.purchaseprice * B.qty) - B.discount)
            END) AS 'TotalOut', SUM(CASE
                WHEN A.transactiontype = 'IN' THEN (B.purchaseprice * B.qty)
            END) AS 'TotalIn'
                FROM ttransaction A
                INNER JOIN ttransactiondet B ON A.transactionid = B.transactionid
                INNER JOIN titem C ON B.itemcode = C.itemcode
                INNER JOIN tcategory D ON C.categorycode = D.categorycode
                INNER JOIN tuom E ON C.uomcode = E.uomcode
                INNER JOIN tuser F ON A.createdby = F.userid
                WHERE (A.transactiondate BETWEEN ? AND ?) ORDER BY A.transactiondate DESC) A");

        $defaultParam = "ss";
        $arrParam = array($FromDate, $ToDate);

        $stmt = $conn->prepare($query);
        $stmt->bind_param($defaultParam, ...$arrParam);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($data = $result->fetch_assoc()) {
                $data = array(
                    'TotalOut' => $data['TotalOut'],
                    'TotalIn' => $data['TotalIn'],
                    'TotalProfit' => $data['TotalProfit']
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
        saveErrorLog($errorMsg, "Gain Loss", "Fetch Profit Loss", $userLogin, $conn);
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
