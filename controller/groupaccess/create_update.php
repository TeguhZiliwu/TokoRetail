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
        $FormList = $postdata->FormList;
        
        if (count($FormList) > 0) {
            $conn->autocommit(FALSE);
            
            // rollback should revert here
            $conn->begin_transaction();

            for ($i = 0; $i < count($FormList); $i++) {
                $FormID = $FormList[$i]->FormID;
                $status = $FormList[$i]->status;

                if ($status === "new") {
                    $query = ("INSERT INTO tgroupaccess (groupid, formid, createdby, createddate) VALUES (?,?,?, CURRENT_TIMESTAMP);");
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("sss", $GroupID, $FormID, $userLogin);
                    if (!$stmt->execute()) {
                        echo $stmt->error;
                        throw new Exception($stmt->error);
                    }
                } else if ($status === "updated") {
                    $query = ("UPDATE tgroupaccess SET updatedby=?, updateddate=CURRENT_TIMESTAMP WHERE groupid=? AND formid=?");
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("sss", $userLogin, $GroupID, $FormID);
                    if (!$stmt->execute()) {
                        throw new Exception($stmt->error);
                    }
                } else if ($status === "deleted") {
                    $query = ("DELETE FROM tgroupaccess WHERE groupid=? AND formid=?");
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("ss", $GroupID, $FormID);
                    if (!$stmt->execute()) {
                        throw new Exception($stmt->error);
                    }
                }
            }
            
            if ($conn->commit()) {
                $stmt->close();
                $json->success = true;
                $json->msg = "Data berhasil disimpan.";
                $jsonstring = json_encode($json);
                echo $jsonstring;
            } else {
                throw new Exception('Transaction commit failed.');
            }
        }
	} catch (\Throwable $e) {
		$errorMsg = 'Error on line ' . $e->getLine() . ' in ' . $e->getFile() . ': ' . $e->getMessage();
        $conn->rollback();
		saveErrorLog($errorMsg, "Group Access", "Create-Update", $userLogin, $conn);
		$json->success = false;
		$json->msg = "[ERROR] Terjadi kesalahan, harap hubungi teknisi.";
		$jsonstring = json_encode($json);
		echo $jsonstring;
	} finally {
        $conn->autocommit(TRUE);
		closeConn($conn);
	}
} else {
	$json->success = false;
	$json->msg = "Silahkan login kedalam aplikasi!";
	$jsonstring = json_encode($json);
	echo $jsonstring;
}
