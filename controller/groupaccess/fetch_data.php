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

        if ($FetchData == "ExistingFormList") { 

            $GroupID = mysqli_real_escape_string($conn, $_GET['GroupID']);
            $resultdata = array();
            $query = ("SELECT formid FROM tgroupaccess WHERE groupid = ? ORDER BY formid ASC");
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $GroupID);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($data = $result->fetch_assoc()) {
                    $data = array(
                        'FormID' => $data['formid']
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
        } else if ($FetchData == "getMenuList") {

            $GroupID = mysqli_real_escape_string($conn, $_SESSION['groupid']);
            $resultdata = array();
            $query = ("SELECT A.formid, B.formname, B.formtype 
                       FROM tgroupaccess A 
                       INNER JOIN tform B ON A.formid = B.formid
                       WHERE A.groupid = ? 
                       ORDER BY B.formname ASC;");
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $GroupID);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($data = $result->fetch_assoc()) {
                    $data = array(
                        'FormID' => $data['formid'],
                        'FormName' => $data['formname'],
                        'FormType' => $data['formtype']
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
        $stmt->close();
    } catch (\Throwable $e) {
        $errorMsg = 'Error on line ' . $e->getLine() . ' in ' . $e->getFile() . ': ' . $e->getMessage();
        saveErrorLog($errorMsg, "Group Access", "Fetch Data", $userLogin, $conn);
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
