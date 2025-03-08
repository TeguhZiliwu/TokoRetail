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

        $query = ("SELECT A.itemcode, A.itembarcode, A.itemname, A.itemdesc, A.categorycode, B.category, A.itemtype, A.uomcode, C.uom, A.sellingprice, A.createdby, A.createddate, A.updatedby, A.updateddate
                   FROM titem A
                   INNER JOIN tcategory B ON A.categorycode = B.categorycode
                   INNER JOIN tuom C ON A.uomcode = C.uomcode
                   WHERE (A.itemcode LIKE ? OR A.itemname LIKE ? OR A.itemdesc LIKE ? OR B.category LIKE ? OR C.uom LIKE ? OR A.itemtype LIKE ?)
                   ORDER BY itemname ASC");
        $stmt = $conn->prepare($query);
        $Search = '%' . $Search . '%';
        $stmt->bind_param("ssssss", $Search, $Search, $Search, $Search, $Search, $Search);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            while ($data = $result->fetch_assoc()) {
                $data = array(
                    'ItemCode' => $data['itemcode'],
                    'ItemBarcode' => $data['itembarcode'],
                    'ItemName' => $data['itemname'],
                    'ItemDesc' => $data['itemdesc'],
                    'CategoryCode' => $data['categorycode'],
                    'Category' => $data['category'],
                    'UOMCode' => $data['uomcode'],
                    'ItemType' => $data['itemtype'],
                    'UOM' => $data['uom'],
                    'SellingPrice' => $data['sellingprice'],
                    'CreatedBy' => $data['createdby'],
                    'CreatedDate' => $data['createddate'],
                    'UpdatedBy' => $data['updatedby'],
                    'UpdatedDate' => $data['updateddate']
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
