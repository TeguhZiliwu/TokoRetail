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
		$CategoryCode = mysqli_real_escape_string($conn, $postdata->CategoryCode);
		$Category = mysqli_real_escape_string($conn, $postdata->Category);
		$CategoryDesc = mysqli_real_escape_string($conn, $postdata->CategoryDesc);
		$edit = mysqli_real_escape_string($conn, $postdata->edit);

		if ($edit != "true") {
			$query = ("SELECT category FROM tcategory WHERE category = ?");
			$stmt = $conn->prepare($query);
			$stmt->bind_param("s", $Category);
			$stmt->execute();
			$result = $stmt->get_result();

			if ($result->num_rows > 0) {
				$json->success = false;
				$json->msg = "Kategori sudah terdaftar.";
				$jsonstring = json_encode($json);
				echo $jsonstring;
				$stmt->close();
				closeConn($conn);
				exit();
			} else {
                $AutoCategoryCode = generatedCategoryID($conn);
				$query = ("INSERT INTO tcategory (categorycode, category, categorydesc, createdby, createddate) VALUES (?,?,?,?, CURRENT_TIMESTAMP)");
				$stmt = $conn->prepare($query);
				$stmt->bind_param("ssss", $AutoCategoryCode, $Category, $CategoryDesc, $userLogin);
				$stmt->execute();
			}
		} else {
			$query = ("UPDATE tcategory SET categorydesc=?, updatedby=?, updateddate=CURRENT_TIMESTAMP WHERE categorycode=?");
			$stmt = $conn->prepare($query);
			$stmt->bind_param("sss", $CategoryDesc, $userLogin, $CategoryCode);
			$stmt->execute();
		}

		if ($stmt->affected_rows > 0) {
			$json->success = true;
			$json->msg = "Data berhasil disimpan.";
			$jsonstring = json_encode($json);
			echo $jsonstring;
		} else {
			saveErrorLog($stmt->error, "Category", "Create-Update", $userLogin, $conn);
			$json->success = false;
			$json->msg = "[ERROR] Terjadi kesalahan, harap hubungi teknisi.";
			$jsonstring = json_encode($json);
			echo $jsonstring;
		}
		$stmt->close();
	} catch (\Throwable $e) {
		$errorMsg = 'Error on line ' . $e->getLine() . ' in ' . $e->getFile() . ': ' . $e->getMessage();
		saveErrorLog($errorMsg, "Category", "Create-Update", $userLogin, $conn);
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

function generatedCategoryID($conn){
	$return = "";
	$query = ("SELECT CASE WHEN EXISTS (SELECT categorycode FROM tcategory WHERE categorycode LIKE CONCAT('CC','%')) THEN CONCAT('CC', RIGHT(CONCAT('0000',CAST((CAST((SELECT RIGHT(categorycode,4) FROM tcategory WHERE categorycode LIKE CONCAT('CC','%') ORDER BY categorycode DESC LIMIT 1) as int) + 1) as varchar(20))), 4)) ELSE CONCAT('CC','0001') END AS ID;");
	$id = mysqli_query($conn, $query);				
	if (mysqli_num_rows($id) > 0) {  
		while ($rows = mysqli_fetch_array($id))
		{	
			$return = $rows['ID'];
		}
	}
	return $return;
}
