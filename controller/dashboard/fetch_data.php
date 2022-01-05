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

        if ($FetchData == "getStockRunningOut") {
            $Search = mysqli_real_escape_string($conn, $_GET['search']);
            $resultdata = array();
            $query = ("SELECT A.itemcode, A.itemname, IFNULL(D.qty, 0) AS qty
                       FROM titem A
                       INNER JOIN tcategory B ON A.categorycode = B.categorycode
                       INNER JOIN tuom C ON A.uomcode = C.uomcode
                       LEFT JOIN tstock D ON A.itemcode = D.itemcode
                       WHERE IFNULL(D.qty, 0) <= 15
                       ORDER BY D.qty ASC
                       LIMIT 10;");
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($data = $result->fetch_assoc()) {
                    $data = array(
                        'ItemCode' => $data['itemcode'],
                        'ItemName' => $data['itemname'],
                        'Qty' => $data['qty'],
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
        } else if ($FetchData == "getIncomeDashboard") { 
            $Search = mysqli_real_escape_string($conn, $_GET['search']);
            $resultdata = array();
            $query = ("SELECT MONTH(CURRENT_DATE()) AS month, (SUM(B.purchaseprice * B.qty) - SUM(B.discount)) AS total
            FROM ttransaction A
            INNER JOIN ttransactiondet B ON A.transactionid = B.transactionid
            WHERE A.transactiontype = 'OUT'
            GROUP BY MONTH(A.transactiondate);");
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($data = $result->fetch_assoc()) {
                    $data = array(
                        'Bulan' => $data['month'],
                        'Total' => $data['total']
                    );
                    array_push($resultdata, $data);
                }

                $json->success = true;
                $json->data = $resultdata;
                $jsonstring = json_encode($json);
                echo $jsonstring;
            } else {
                $data = array(
                    'Bulan' => null,
                    'Total' => null
                );
                array_push($resultdata, $data);
                $json->success = true;
                $json->data = $resultdata;
                $jsonstring = json_encode($json);
                echo $jsonstring;
            }
            $stmt->close();
        } else if ($FetchData == "getTotalSale") { 
            $Search = mysqli_real_escape_string($conn, $_GET['search']);
            $resultdata = array();
            $query = ("SELECT COUNT(A.transactionid) AS totaltransaction
            FROM ttransaction A
            WHERE A.transactiontype = 'OUT'
            GROUP BY MONTH(A.transactiondate);");
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($data = $result->fetch_assoc()) {
                    $data = array(
                        'TotalTransaction' => $data['totaltransaction']
                    );
                    array_push($resultdata, $data);
                }

                $json->success = true;
                $json->data = $resultdata;
                $jsonstring = json_encode($json);
                echo $jsonstring;
            } else {
                $data = array(
                    'TotalTransaction' => 0
                );
                array_push($resultdata, $data);
                $json->success = true;
                $json->data = $resultdata;
                $jsonstring = json_encode($json);
                echo $jsonstring;
            }
            $stmt->close();
        } else if ($FetchData == "getTotalStockIn") { 
            $Search = mysqli_real_escape_string($conn, $_GET['search']);
            $resultdata = array();
            $query = ("SELECT COUNT(A.transactionid) AS totaltransaction
            FROM ttransaction A
            WHERE A.transactiontype = 'IN'
            GROUP BY MONTH(A.transactiondate);");
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($data = $result->fetch_assoc()) {
                    $data = array(
                        'TotalTransaction' => $data['totaltransaction']
                    );
                    array_push($resultdata, $data);
                }

                $json->success = true;
                $json->data = $resultdata;
                $jsonstring = json_encode($json);
                echo $jsonstring;
            } else {
                $data = array(
                    'TotalTransaction' => 0
                );
                array_push($resultdata, $data);
                $json->success = true;
                $json->data = $resultdata;
                $jsonstring = json_encode($json);
                echo $jsonstring;
            }
            $stmt->close();
        }
    } catch (\Throwable $e) {
        $errorMsg = 'Error on line ' . $e->getLine() . ' in ' . $e->getFile() . ': ' . $e->getMessage();
        saveErrorLog($errorMsg, "Item", "Fetch Data", $userLogin, $conn);
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
