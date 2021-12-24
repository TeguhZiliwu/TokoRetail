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
		$FormID = mysqli_real_escape_string($conn, $postdata->FormID);
		$FormName = mysqli_real_escape_string($conn, $postdata->FormName);
		$FormType = mysqli_real_escape_string($conn, $postdata->FormType);
		$edit = mysqli_real_escape_string($conn, $postdata->edit);

		if ($edit != "true") {
			$query = ("SELECT formid FROM tform WHERE formid = ?");
			$stmt = $conn->prepare($query);
			$stmt->bind_param("s", $FormID);
			$stmt->execute();
			$result = $stmt->get_result();

			if ($result->num_rows > 0) {
				$json->success = false;
				$json->msg = "Form ID sudah terdaftar.";
				$jsonstring = json_encode($json);
				echo $jsonstring;
				$stmt->close();
				closeConn($conn);
				exit();
			} else {
				$query = ("INSERT INTO tform (formid, formname, formtype, createdby, createddate) VALUES (?,?,?,?, CURRENT_TIMESTAMP)");
				$stmt = $conn->prepare($query);
				$stmt->bind_param("ssss", $FormID, $FormName, $FormType, $userLogin);
				$stmt->execute();
			}
		} else {
			$query = ("UPDATE tform SET formname=?, formtype=?, updatedby=?, updateddate=CURRENT_TIMESTAMP WHERE formid=?");
			$stmt = $conn->prepare($query);
			$stmt->bind_param("ssss", $FormName, $FormType, $userLogin, $FormID);
			$stmt->execute();
		}

		if ($stmt->affected_rows > 0) {
			$json->success = true;
			$json->msg = "Data berhasil disimpan.";
			$jsonstring = json_encode($json);
			echo $jsonstring;
		} else {
			saveErrorLog($stmt->error, "Form", "Create-Update", $userLogin, $conn);
			$json->success = false;
			$json->msg = "[ERROR] Terjadi kesalahan, harap hubungi teknisi.";
			$jsonstring = json_encode($json);
			echo $jsonstring;
		}
		$stmt->close();
	} catch (\Throwable $e) {
		$errorMsg = 'Error on line ' . $e->getLine() . ' in ' . $e->getFile() . ': ' . $e->getMessage();
		saveErrorLog($errorMsg, "Form", "Create-Update", $userLogin, $conn);
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
