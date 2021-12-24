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
		$UOMCode = mysqli_real_escape_string($conn, $postdata->UOMCode);
		$UOM = mysqli_real_escape_string($conn, $postdata->UOM);
		$UOMDesc = mysqli_real_escape_string($conn, $postdata->UOMDesc);
		$edit = mysqli_real_escape_string($conn, $postdata->edit);

		if ($edit != "true") {
			$query = ("SELECT uom FROM tuom WHERE uom = ?");
			$stmt = $conn->prepare($query);
			$stmt->bind_param("s", $UOM);
			$stmt->execute();
			$result = $stmt->get_result();

			if ($result->num_rows > 0) {
				$json->success = false;
				$json->msg = "Satuan sudah terdaftar.";
				$jsonstring = json_encode($json);
				echo $jsonstring;
				$stmt->close();
				closeConn($conn);
				exit();
			} else {
                $AutoUOMCode = generatedUOMCode($conn);
				$query = ("INSERT INTO tuom (uomcode, uom, uomdesc, createdby, createddate) VALUES (?,?,?,?, CURRENT_TIMESTAMP)");
				$stmt = $conn->prepare($query);
				$stmt->bind_param("ssss", $AutoUOMCode, $UOM, $UOMDesc, $userLogin);
				$stmt->execute();
			}
		} else {
			$query = ("UPDATE tuom SET uomdesc=?, updatedby=?, updateddate=CURRENT_TIMESTAMP WHERE uomcode=?");
			$stmt = $conn->prepare($query);
			$stmt->bind_param("sss", $UOMDesc, $userLogin, $UOMCode);
			$stmt->execute();
		}

		if ($stmt->affected_rows > 0) {
			$json->success = true;
			$json->msg = "Data berhasil disimpan.";
			$jsonstring = json_encode($json);
			echo $jsonstring;
		} else {
			saveErrorLog($stmt->error, "UOM", "Create-Update", $userLogin, $conn);
			$json->success = false;
			$json->msg = "[ERROR] Terjadi kesalahan, harap hubungi teknisi.";
			$jsonstring = json_encode($json);
			echo $jsonstring;
		}
		$stmt->close();
	} catch (\Throwable $e) {
		$errorMsg = 'Error on line ' . $e->getLine() . ' in ' . $e->getFile() . ': ' . $e->getMessage();
		saveErrorLog($errorMsg, "UOM", "Create-Update", $userLogin, $conn);
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

function generatedUOMCode($conn){
	$return = "";
	$query = ("SELECT CASE WHEN EXISTS (SELECT uomcode FROM tuom WHERE uomcode LIKE CONCAT('UOM','%')) THEN CONCAT('UOM', RIGHT(CONCAT('0000',CAST((CAST((SELECT RIGHT(uomcode,4) FROM tuom WHERE uomcode LIKE CONCAT('UOM','%') ORDER BY uomcode DESC LIMIT 1) as int) + 1) as varchar(20))), 4)) ELSE CONCAT('UOM','0001') END AS ID;");
	$id = mysqli_query($conn, $query);				
	if (mysqli_num_rows($id) > 0) {  
		while ($rows = mysqli_fetch_array($id))
		{	
			$return = $rows['ID'];
		}
	}
	return $return;
}
