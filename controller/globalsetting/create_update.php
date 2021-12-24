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
		$SettingID = mysqli_real_escape_string($conn, $postdata->SettingID);
		$SettingValue = mysqli_real_escape_string($conn, $postdata->SettingValue);
		$Remark = mysqli_real_escape_string($conn, $postdata->Remark);
		$edit = mysqli_real_escape_string($conn, $postdata->edit);

		if ($edit != "true") {
			$query = ("SELECT settingid FROM tglobalsetting WHERE settingid = ?");
			$stmt = $conn->prepare($query);
			$stmt->bind_param("s", $SettingID);
			$stmt->execute();
			$result = $stmt->get_result();

			if ($result->num_rows > 0) {
				$json->success = false;
				$json->msg = "Setting ID sudah terdaftar.";
				$jsonstring = json_encode($json);
				echo $jsonstring;
				$stmt->close();
				closeConn($conn);
				exit();
			} else {
				$query = ("INSERT INTO tglobalsetting (settingid, settingvalue, remark, createdby, createddate) VALUES (?,?,?,?, CURRENT_TIMESTAMP)");
				$stmt = $conn->prepare($query);
				$stmt->bind_param("ssss", $SettingID, $SettingValue, $Remark, $userLogin);
				$stmt->execute();
			}
		} else {
			$query = ("UPDATE tglobalsetting SET settingvalue=?, remark=?, updatedby=?, updateddate=CURRENT_TIMESTAMP WHERE settingid=?");
			$stmt = $conn->prepare($query);
			$stmt->bind_param("ssss", $SettingValue, $Remark, $userLogin, $SettingID);
			$stmt->execute();
		}

		if ($stmt->affected_rows > 0) {
			$json->success = true;
			$json->msg = "Data berhasil disimpan.";
			$jsonstring = json_encode($json);
			echo $jsonstring;
		} else {
			saveErrorLog($stmt->error, "Global Setting", "Create-Update", $userLogin, $conn);
			$json->success = false;
			$json->msg = "[ERROR] Terjadi kesalahan, harap hubungi teknisi.";
			$jsonstring = json_encode($json);
			echo $jsonstring;
		}
		$stmt->close();
	} catch (\Throwable $e) {
		$errorMsg = 'Error on line ' . $e->getLine() . ' in ' . $e->getFile() . ': ' . $e->getMessage();
		saveErrorLog($errorMsg, "Global Setting", "Create-Update", $userLogin, $conn);
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
