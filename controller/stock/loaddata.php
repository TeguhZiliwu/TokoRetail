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
        $resultdata = array();

        $query = ("SELECT A.itemcode, A.itemname, C.category, D.uom, A.itemtype, A.sellingprice, IFNULL(B.qty, 0) AS qty
                   FROM titem A
                   LEFT JOIN tstock B ON A.itemcode = B.itemcode
                   LEFT JOIN tcategory C ON A.categorycode = C.categorycode
                   LEFT JOIN tuom D ON A.uomcode = D.uomcode
                   WHERE (A.itemcode LIKE ? OR A.itemname LIKE ? OR C.category LIKE ? OR D.uom LIKE ? OR A.itemtype LIKE ? OR B.qty LIKE ?)");
        $stmt = $conn->prepare($query);
        $Search = '%' . $Search . '%';
        $stmt->bind_param("ssssss", $Search, $Search, $Search, $Search, $Search, $Search);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            while ($data = $result->fetch_assoc()) {
                $data = array(
                    'ItemCode' => $data['itemcode'],
                    'ItemName' => $data['itemname'],
                    'Category' => $data['category'],
                    'UOM' => $data['uom'],
                    'ItemType' => $data['itemtype'],
                    'SellingPrice' => $data['sellingprice'],
                    'Qty' => $data['qty']
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
        saveErrorLog($errorMsg, "Item", "Load Data", $userLogin, $conn);
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
