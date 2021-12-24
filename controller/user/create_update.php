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
		$UserID = mysqli_real_escape_string($conn, $postdata->UserID);
		$FullName = mysqli_real_escape_string($conn, $postdata->FullName);
		$Password = mysqli_real_escape_string($conn, $postdata->Password);
		$Email = mysqli_real_escape_string($conn, $postdata->Email);
		$TelpNo = mysqli_real_escape_string($conn, $postdata->TelpNo);
		$GroupID = mysqli_real_escape_string($conn, $postdata->GroupID);
		$edit = mysqli_real_escape_string($conn, $postdata->edit);
		$editPassword = mysqli_real_escape_string($conn, $postdata->editPassword);

		if ($edit != "true") {
			$query = ("SELECT userid FROM tuser WHERE userid = ?");
			$stmt = $conn->prepare($query);
			$stmt->bind_param("s", $UserID);
			$stmt->execute();
			$result = $stmt->get_result();

			if ($result->num_rows > 0) {
				$json->success = false;
				$json->msg = "User ID sudah terdaftar.";
				$jsonstring = json_encode($json);
				echo $jsonstring;
				$stmt->close();
				closeConn($conn);
				exit();
			} else {
				$query = ("INSERT INTO tuser (userid, fullname, password, email, telpno, groupid, createdby, createddate) VALUES (?,?,SHA2(?, 512),?,?,?,?, CURRENT_TIMESTAMP)");
				$stmt = $conn->prepare($query);
				$stmt->bind_param("sssssss", $UserID, $FullName, $Password, $Email, $TelpNo, $GroupID, $userLogin);
				$stmt->execute();
			}
		} else {
			$query = ("UPDATE tuser SET fullname=?, email=?, telpno=?, groupid=?, updatedby=?, updateddate=CURRENT_TIMESTAMP WHERE userid=?");
			$stmt = $conn->prepare($query);
			$stmt->bind_param("ssssss", $FullName, $Email, $TelpNo, $GroupID, $userLogin, $UserID);
			$stmt->execute();

            if ($editPassword == "true") {
                $query = ("UPDATE tuser SET password=SHA2(?, 512), updatedby=?, updateddate=CURRENT_TIMESTAMP WHERE userid=?");
                $stmt = $conn->prepare($query);
                $stmt->bind_param("sss", $Password, $userLogin, $UserID);
                $stmt->execute();
            }
		}

		if ($stmt->affected_rows > 0) {
			$json->success = true;
			$json->msg = "Data berhasil disimpan.";
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
		saveErrorLog($errorMsg, "User", "Create-Update", $userLogin, $conn);
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
