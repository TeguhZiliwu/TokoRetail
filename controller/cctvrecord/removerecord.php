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
        $RecordID = mysqli_real_escape_string($conn, $postdata->RecordID);
        $globalPath = getGlobalSetting($conn, "PathCCTVRecord");
        $globalPath = "../../" . $globalPath;

        $query = ("SELECT cctvrecordname
                   FROM tcctvrecord
                   WHERE cctvrecordid = ?");
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $RecordID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($data = $result->fetch_assoc()) {
                $FileName = $target_dir . $RecordID . "_" . $data['cctvrecordname'];
                if (file_exists($FileName)) {
                    unlink($FileName);
                }
            }
        }

        if ($stmt->affected_rows > 0) {
            $json->success = true;
            $json->msg = "Data berhasil dihapus.";
            $jsonstring = json_encode($json);
            echo $jsonstring;
        } else {
            saveErrorLog($stmt->error, "CCTV Record", "Remove Record", $userLogin, $conn);
            $json->success = false;
            $json->msg = "[ERROR] Terjadi kesalahan, harap hubungi teknisi.";
            $jsonstring = json_encode($json);
            echo $jsonstring;
        }
        $stmt->close();
    } catch (\Throwable $e) {
        $errorMsg = 'Error on line ' . $e->getLine() . ' in ' . $e->getFile() . ': ' . $e->getMessage();
        saveErrorLog($errorMsg, "CCTV Record", "Remove Record", $userLogin, $conn);
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

function getGlobalSetting($conn, $SettingID)
{
    $query = ("SELECT settingvalue
               FROM tglobalsetting
               WHERE settingid = ?");
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $SettingID);
    $stmt->execute();
    $result = $stmt->get_result();
    $SettingValue = "";

    if ($result->num_rows > 0) {
        while ($data = $result->fetch_assoc()) {
            $SettingValue = $data['settingvalue'];
        }
    }

    return $SettingValue;
}
