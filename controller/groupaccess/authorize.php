<?php
include '../../config/appname.php';
include '../../config/dbconnection.php';
include '../../config/errorlog.php';

session_name($encryptedAppName);
session_start();

$json = new stdClass();
$conn = openConn();
$userLogin = mysqli_real_escape_string($conn, $_SESSION['userid']);
$GroupID = mysqli_real_escape_string($conn, $_SESSION['groupid']);
$query = "";

if (!empty($userLogin)) {
    try {
        $FormID = mysqli_real_escape_string($conn, $_GET['FormID']);

        $resultdata = array();
        $query = ("SELECT groupid FROM tgroupaccess WHERE groupid = ? AND formid = ?");
        $stmt = $conn->prepare($query);
		$stmt->bind_param("ss", $GroupID, $FormID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
			$json->success = true;
			$json->data = "allowed";
			$jsonstring = json_encode($json);
			echo $jsonstring;
		} else {
			$json->success = true;
			$json->data = "not allowed";
			$jsonstring = json_encode($json);
			echo $jsonstring;
		}
        $stmt->close();
    } catch (\Throwable $e) {
        $errorMsg = 'Error on line ' . $e->getLine() . ' in ' . $e->getFile() . ': ' . $e->getMessage();
        saveErrorLog($errorMsg, "Group Access", "Authorize", $userLogin, $conn);
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
