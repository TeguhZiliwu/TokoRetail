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
		$ItemCode = mysqli_real_escape_string($conn, $postdata->ItemCode);
		$ItemName = mysqli_real_escape_string($conn, $postdata->ItemName);
		$ItemDesc = mysqli_real_escape_string($conn, $postdata->ItemDesc);
		$Category = mysqli_real_escape_string($conn, $postdata->Category);
		$UOM = mysqli_real_escape_string($conn, $postdata->UOM);
		$ItemType = mysqli_real_escape_string($conn, $postdata->ItemType);
		$SellingPrice = mysqli_real_escape_string($conn, $postdata->SellingPrice);

		$edit = mysqli_real_escape_string($conn, $postdata->edit);

		if ($edit != "true") {
			$query = ("SELECT itemcode FROM titem WHERE itemname = ? AND categorycode = ? AND uomcode = ? AND itemtype = ?");
			$stmt = $conn->prepare($query);
			$stmt->bind_param("ssss", $ItemName, $Category, $UOM, $ItemType);
			$stmt->execute();
			$result = $stmt->get_result();

			if ($result->num_rows > 0) {
				$json->success = false;
				$json->msg = "Barang sudah terdaftar.";
				$jsonstring = json_encode($json);
				echo $jsonstring;
				$stmt->close();
				closeConn($conn);
				exit();
			} else {
                $AutoItemCode = generatedItemCode($conn);
				$query = ("INSERT INTO titem (itemcode, itemname, itemdesc, categorycode, uomcode, itemtype, sellingprice, createdby, createddate) VALUES (?,?,?,?,?,?,?,?, CURRENT_TIMESTAMP)");
				$stmt = $conn->prepare($query);
				$stmt->bind_param("ssssssss", $AutoItemCode, $ItemName, $ItemDesc, $Category, $UOM, $ItemType, $SellingPrice, $userLogin);
				$stmt->execute();
			}
		} else {

			$query = ("SELECT itemcode FROM titem WHERE itemname = ? AND categorycode = ? AND uomcode = ? AND itemtype = ? AND itemcode != ?");
			$stmt = $conn->prepare($query);
			$stmt->bind_param("sssss", $ItemName, $Category, $UOM, $ItemType, $ItemCode);
			$stmt->execute();
			$result = $stmt->get_result();

			if ($result->num_rows > 0) {
				$json->success = false;
				$json->msg = "Barang sudah terdaftar.";
				$jsonstring = json_encode($json);
				echo $jsonstring;
				$stmt->close();
				closeConn($conn);
				exit();
			} else {
				$query = ("UPDATE titem SET itemname=?, itemdesc=?, categorycode=?, uomcode=?, itemtype=?, sellingprice=?, updatedby=?, updateddate=CURRENT_TIMESTAMP WHERE itemcode=?");
				$stmt = $conn->prepare($query);
				$stmt->bind_param("ssssssss", $ItemName, $ItemDesc, $Category, $UOM, $ItemType, $SellingPrice, $userLogin, $ItemCode);
				$stmt->execute();
			}
		}

		if ($stmt->affected_rows > 0) {
			$json->success = true;
			$json->msg = "Data berhasil disimpan.";
			$json->itemcode = ($edit != "true" ? $AutoItemCode : $ItemCode);
			$jsonstring = json_encode($json);
			echo $jsonstring;
		} else {
			saveErrorLog($stmt->error, "Item", "Create-Update", $userLogin, $conn);
			$json->success = false;
			$json->msg = "[ERROR] Terjadi kesalahan, harap hubungi teknisi.";
			$jsonstring = json_encode($json);
			echo $jsonstring;
		}
		$stmt->close();
	} catch (\Throwable $e) {
		$errorMsg = 'Error on line ' . $e->getLine() . ' in ' . $e->getFile() . ': ' . $e->getMessage();
		saveErrorLog($errorMsg, "Item", "Create-Update", $userLogin, $conn);
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

function generatedItemCode($conn){
	$return = "";
	$query = ("SELECT CASE WHEN EXISTS (SELECT itemcode FROM titem WHERE itemcode LIKE CONCAT('IC',YEAR(NOW()),LPAD(MONTH(NOW()), 2, '0'),'%')) THEN CONCAT('IC', YEAR(NOW()),LPAD(MONTH(NOW()), 2, '0'),RIGHT(CONCAT('0000',CAST((CAST((SELECT RIGHT(itemcode,4) FROM titem WHERE itemcode LIKE CONCAT('IC',YEAR(NOW()),LPAD(MONTH(NOW()), 2, '0'),'%') ORDER BY itemcode DESC LIMIT 1) as int) + 1) as varchar(20))), 4)) ELSE CONCAT('IC',YEAR(NOW()),LPAD(MONTH(NOW()), 2, '0'),'0001') END AS ID");
	$id = mysqli_query($conn, $query);				
	if (mysqli_num_rows($id) > 0) {  
		while ($rows = mysqli_fetch_array($id))
		{	
			$return = $rows['ID'];
		}
	}
	return $return;
}