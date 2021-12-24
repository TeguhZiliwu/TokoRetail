<?php
include '../../config/appname.php';
include '../../config/dbconnection.php';
include '../../config/errorlog.php';

session_name($encryptedAppName);
session_start();

$json = new stdClass();
$conn = openConn();
$userLogin = mysqli_real_escape_string($conn, $_SESSION['userid']);
$userGroupID = mysqli_real_escape_string($conn, $_SESSION['groupid']);
$query = "";
$postdata = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $postdata = file_get_contents('php://input');
    $postdata = json_decode($postdata);
}

if (!empty($userLogin)) {
    try {
        $UserID = mysqli_real_escape_string($conn, $postdata->UserID);
        $GroupID = mysqli_real_escape_string($conn, $postdata->GroupID);

        if ($GroupID != "Pemilik Usaha") {

            $query = "DELETE FROM tuser WHERE userid=?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $UserID);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($stmt->affected_rows > 0) {

                $json->success = true;
                $json->msg = "Data berhasil dihapus.";
                $jsonstring = json_encode($json);
                echo $jsonstring;
            } else {
                saveErrorLog($stmt->error, "User", "Delete", $userLogin, $conn);
                $json->success = false;
                $json->msg = "[ERROR] Terjadi kesalahan, harap hubungi teknisi.";
                $jsonstring = json_encode($json);
                echo $jsonstring;
            }
            $stmt->close();
        } else {

            if ($GroupID == "Pemilik Usaha" && $userGroupID == "Pemilik Usaha") {

                $query = "DELETE FROM tuser WHERE userid=?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("s", $UserID);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($stmt->affected_rows > 0) {

                    $json->success = true;
                    $json->msg = "Data berhasil dihapus.";
                    $jsonstring = json_encode($json);
                    echo $jsonstring;
                } else {
                    saveErrorLog($stmt->error, "User", "Delete", $userLogin, $conn);
                    $json->success = false;
                    $json->msg = "[ERROR] Terjadi kesalahan, harap hubungi teknisi.";
                    $jsonstring = json_encode($json);
                    echo $jsonstring;
                }
                $stmt->close();
            } else {
                $json->success = false;
                $json->msg = "[ERROR] Maaf User dengan Group ID Pemilik Usaha tidak dapat dihapus.";
                $jsonstring = json_encode($json);
                echo $jsonstring;
            }
        }
    } catch (\Throwable $e) {
        $errorMsg = 'Error on line ' . $e->getLine() . ' in ' . $e->getFile() . ': ' . $e->getMessage();
        saveErrorLog($errorMsg, "User", "Delete", $userLogin, $conn);
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
