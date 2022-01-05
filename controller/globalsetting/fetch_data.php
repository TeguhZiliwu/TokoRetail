<?php
include '../../config/appname.php';
include '../../config/dbconnection.php';
include '../../config/errorlog.php';

session_name($encryptedAppName);
session_start();

$json = new stdClass();
$conn = openConn();
$isPromotionPage = mysqli_real_escape_string($conn, $_GET['isPromotionPage']);
if ($isPromotionPage == "false") {
    $userLogin = mysqli_real_escape_string($conn, $_SESSION['userid']);
}
$query = "";

if (!empty($userLogin) || (empty($userLogin) && $isPromotionPage == "true")) {
    try {
        $FetchData = mysqli_real_escape_string($conn, $_GET['FetchData']);

        if ($FetchData == "getGlobalValue") {
            $SettingID = mysqli_real_escape_string($conn, $_GET['SettingID']);
            $resultdata = array();
            $query = ("SELECT settingvalue FROM tglobalsetting WHERE settingid = ?");
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $SettingID);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($data = $result->fetch_assoc()) {
                    $data = array(
                        'SettingValue' => $data['settingvalue']
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
        saveErrorLog($errorMsg, "Global Setting", "Fetch Data", $userLogin, $conn);
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
