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
        $OldPassword = mysqli_real_escape_string($conn, $postdata->OldPassword);
        $NewPassword = mysqli_real_escape_string($conn, $postdata->NewPassword);
        $ConfirmPassword = mysqli_real_escape_string($conn, $postdata->ConfirmPassword);

        $query = ("SELECT userid FROM tuser WHERE userid = ? AND password = SHA2(?, 512)");
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $userLogin, $OldPassword);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            if ($NewPassword == $ConfirmPassword) {
                $query = ("UPDATE tuser SET password=SHA2(?, 512), updatedby=?, updateddate=CURRENT_TIMESTAMP WHERE userid=?");
                $stmt = $conn->prepare($query);
                $stmt->bind_param("sss", $NewPassword, $userLogin, $userLogin);
                $stmt->execute();
            } else {
                $json->success = false;
                $json->msg = "Password baru tidak sesuai!";
                $jsonstring = json_encode($json);
                echo $jsonstring;
                $stmt->close();
                closeConn($conn);
                exit();
            }
        } else {
            $json->success = false;
            $json->msg = "Password lama tidak sesuai!";
            $jsonstring = json_encode($json);
            echo $jsonstring;
            $stmt->close();
            closeConn($conn);
            exit();
        }

        if ($stmt->affected_rows > 0) {
            $json->success = true;
            $json->msg = "Password berhasil diperbaharui.";
            $jsonstring = json_encode($json);
            echo $jsonstring;
        } else {
            saveErrorLog($stmt->error, "User", "Create-Update", $userLogin, $conn);
            $json->success = false;
            $json->msg = "[ERROR] Terjadi kesalahan, harap hubungi teknisi.";
            $jsonstring = json_encode($json);
            echo $jsonstring;
        }
        $stmt->close();
    } catch (\Throwable $e) {
        $errorMsg = 'Error on line ' . $e->getLine() . ' in ' . $e->getFile() . ': ' . $e->getMessage();
        saveErrorLog($errorMsg, "User", "Change Password", $userLogin, $conn);
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
