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

        if ($FetchData == "getPictureData") { 
            $ItemCode = mysqli_real_escape_string($conn, $_GET['ItemCode']);
            $resultdata = array();
            $query = ("SELECT itempictureid, itemcode, picturename FROM titempicture WHERE itemcode = ? ORDER BY itempictureid ASC");
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $ItemCode);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($data = $result->fetch_assoc()) {
                    $data = array(
                        'ItemPictureID' => $data['itempictureid'],
                        'ItemCode' => $data['itemcode'],
                        'PictureName' => $data['picturename']
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
        } else if ($FetchData == "findItemCode") { 
            $Search = mysqli_real_escape_string($conn, $_GET['search']);
            $resultdata = array();
            $query = ("SELECT A.itemcode, A.itembarcode, A.itemname, IFNULL(D.qty, 0) AS qty, A.itemdesc, A.categorycode, B.category, A.itemtype, A.uomcode, C.uom, A.sellingprice, A.createdby, A.createddate, A.updatedby, A.updateddate
                       FROM titem A
                       INNER JOIN tcategory B ON A.categorycode = B.categorycode
                       INNER JOIN tuom C ON A.uomcode = C.uomcode
                       LEFT JOIN tstock D ON A.itemcode = D.itemcode
                       WHERE (A.itemcode LIKE ? OR A.itemname LIKE ?) OR (A.itembarcode = ?)
                       ORDER BY A.itemcode ASC");
            $stmt = $conn->prepare($query);
            $LikeSearch = '%' . $Search . '%';
            $stmt->bind_param("sss", $LikeSearch, $LikeSearch, $Search);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($data = $result->fetch_assoc()) {
                    $data = array(
                        'ItemCode' => $data['itemcode'],
                        'ItemBarcode' => $data['itembarcode'],
                        'ItemName' => $data['itemname'],
                        'Category' => $data['category'],
                        'UOM' => $data['uom'],
                        'ItemType' => $data['itemtype'],
                        'ItemDesc' => $data['itemdesc'],
                        'SellingPrice' => $data['sellingprice'],
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
