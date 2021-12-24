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
$postdata = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $postdata = file_get_contents('php://input');
    $postdata = json_decode($postdata);
}

if (!empty($userLogin)) {
    try {
        $GroupID = mysqli_real_escape_string($conn, $postdata->GroupID);
        $query = ("SELECT groupid FROM tuser WHERE groupid = ?");
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $GroupID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($GroupID != "Administrator" && $GroupID != "Pemilik Usaha") {

            if ($result->num_rows > 0) {
                $json->success = false;
                $json->msg = "Group ID sedang digunakan oleh User!";
                $jsonstring = json_encode($json);
                echo $jsonstring;
                $stmt->close();
                closeConn($conn);
                exit();
            } else {
                $query = "DELETE FROM tgroupaccess WHERE groupid=?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("s", $GroupID);
                $stmt->execute();
                $result = $stmt->get_result();
            }

            if ($stmt->affected_rows > 0) {

                $json->success = true;
                $json->msg = "Data berhasil dihapus.";
                $jsonstring = json_encode($json);
                echo $jsonstring;
            } else {
                saveErrorLog($stmt->error, "Group", "Delete", $userLogin, $conn);
                $json->success = false;
                $json->msg = "[ERROR] Terjadi kesalahan, harap hubungi teknisi.";
                $jsonstring = json_encode($json);
                echo $jsonstring;
            }
            $stmt->close();
        } else {            
            $json->success = false;
            $json->msg = "[ERROR] Maaf, Group ID Administrator dan Pemilik Usaha tidak dapat dihapus.";
            $jsonstring = json_encode($json);
            echo $jsonstring;
        }
    } catch (\Throwable $e) {
        $errorMsg = 'Error on line ' . $e->getLine() . ' in ' . $e->getFile() . ': ' . $e->getMessage();
        saveErrorLog($errorMsg, "Group", "Delete", $userLogin, $conn);
        $stmt->close();
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
