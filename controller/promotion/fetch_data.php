<?php
include '../../config/appname.php';
include '../../config/dbconnection.php';
include '../../config/errorlog.php';

session_name($encryptedAppName);
session_start();

$json = new stdClass();
$conn = openConn();
$FetchData = mysqli_real_escape_string($conn, $_GET['FetchData']);
$query = "";

if (!empty($FetchData)) {
    try {
        $result = "";

        if ($FetchData == "getItem") {
            $Search = mysqli_real_escape_string($conn, $_GET['Search']);
            $Category = mysqli_real_escape_string($conn, $_GET['Category']);
            $resultdata = array();
            $query = ("SELECT A.itemcode, A.itemname, A.itemdesc, A.categorycode, A.uomcode, A.itemtype, A.sellingprice
            FROM titem A
            LEFT JOIN tstock B ON A.itemcode = B.itemcode
            INNER JOIN tcategory C ON A.categorycode = C.categorycode
            INNER JOIN tuom D ON A.uomcode = D.uomcode
            WHERE (A.itemname LIKE ? OR A.itemdesc LIKE ?)");

            $defaultParam = "ss";
            $Search = '%' . $Search . '%';
            $arrParam = array($Search, $Search);

            if ($Category != null) {
                $query .= " AND A.categorycode = ?";
                $defaultParam .= "s";
                array_push($arrParam, $Category);
            }

            $query .= " ORDER BY A.itemname ASC";

            $stmt = $conn->prepare($query);
            $stmt->bind_param($defaultParam, ...$arrParam);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($data = $result->fetch_assoc()) {
                    $data = array(
                        'ItemCode' => $data['itemcode'],
                        'ItemName' => $data['itemname'],
                        'ItemDesc' => $data['itemdesc'],
                        'SellingPrice' => $data['sellingprice']
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
        } else if ($FetchData == "getCategory") {
            $resultdata = array();
            $query = ("SELECT categorycode, category FROM tcategory ORDER BY category ASC");
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($data = $result->fetch_assoc()) {
                    $data = array(
                        'CategoryCode' => $data['categorycode'],
                        'Category' => $data['category']
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
        } else if ($FetchData == "getImageItem") {
            $resultdata = array();
            $ItemCode = mysqli_real_escape_string($conn, $_GET['ItemCode']);
            $query = ("SELECT picturename FROM titempicture WHERE itemcode = ? ORDER BY picturename ASC");
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $ItemCode);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($data = $result->fetch_assoc()) {
                    $data = array(
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
        }
    } catch (\Throwable $e) {
        $errorMsg = 'Error on line ' . $e->getLine() . ' in ' . $e->getFile() . ': ' . $e->getMessage();
        saveErrorLog($errorMsg, "Item", "Fetch Data", "Pengunjung", $conn);
        $json->success = false;
        $json->msg = "[ERROR] Terjadi kesalahan, harap hubungi teknisi.";
        $jsonstring = json_encode($json);
        echo $jsonstring;
    } finally {
        closeConn($conn);
    }
} else {
    $json->success = false;
    $json->msg = "Masukkan kata kunci!";
    $jsonstring = json_encode($json);
    echo $jsonstring;
}
