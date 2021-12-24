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
		$GroupDesc = mysqli_real_escape_string($conn, $postdata->GroupDesc);
		$edit = mysqli_real_escape_string($conn, $postdata->edit);

		if ($edit != "true") {
			$query = ("SELECT groupid FROM tgroup WHERE groupid = ?");
			$stmt = $conn->prepare($query);
			$stmt->bind_param("s", $GroupID);
			$stmt->execute();
			$result = $stmt->get_result();

			if ($result->num_rows > 0) {
				$json->success = false;
				$json->msg = "Group ID sudah terdaftar.";
				$jsonstring = json_encode($json);
				echo $jsonstring;
				$stmt->close();
				closeConn($conn);
				exit();
			} else {
				$query = ("INSERT INTO tgroup (groupid, groupdesc, createdby, createddate) VALUES (?,?,?, CURRENT_TIMESTAMP)");
				$stmt = $conn->prepare($query);
				$stmt->bind_param("sss", $GroupID, $GroupDesc, $userLogin);
				$stmt->execute();
			}
		} else {
			$query = ("UPDATE tgroup SET groupdesc=?, updatedby=?, updateddate=CURRENT_TIMESTAMP WHERE groupid=?");
			$stmt = $conn->prepare($query);
			$stmt->bind_param("sss", $GroupDesc, $userLogin, $GroupID);
			$stmt->execute();
		}

		if ($stmt->affected_rows > 0) {
			$json->success = true;
			$json->msg = "Data berhasil disimpan.";
			$jsonstring = json_encode($json);
			echo $jsonstring;
		} else {
			saveErrorLog($stmt->error, "Group", "Create-Update", $userLogin, $conn);
			$json->success = false;
			$json->msg = "[ERROR] Terjadi kesalahan, harap hubungi teknisi.";
			$jsonstring = json_encode($json);
			echo $jsonstring;
		}
		$stmt->close();
	} catch (\Throwable $e) {
		$errorMsg = 'Error on line ' . $e->getLine() . ' in ' . $e->getFile() . ': ' . $e->getMessage();
		saveErrorLog($errorMsg, "Group", "Create-Update", $userLogin, $conn);
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
