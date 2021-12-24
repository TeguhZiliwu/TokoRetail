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
$target_dir = "../../file/cctvrecord/";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $postdata = file_get_contents('php://input');
    $postdata = json_decode($postdata);
}

if (!empty($userLogin)) {
    try {

        $hasFileToUpload = mysqli_real_escape_string($conn, $_POST['hasFileToUpload']);
        if ($hasFileToUpload == "yes") {
            $file = $_FILES['fileUpload']['name'];
            $path = pathinfo($file);
            $filename = $path['filename'];
            $ext = $path['extension'];
            $temp_name = $_FILES['fileUpload']['tmp_name'];
        }
        $RecordID = mysqli_real_escape_string($conn, $_POST['RecordID']);
        $Remark = mysqli_real_escape_string($conn, $_POST['Remark']);
        $edit = mysqli_real_escape_string($conn, $_POST['edit']);
        $isSuccess = true;
        $resultdata = array();

        if ($edit != "true") {
            if (($_FILES['fileUpload']['name'] != "")) {

                $AutoID = generatedCCTVRecordID($conn);
                $currentFileName = $filename . "." . $ext;
                $query = ("INSERT INTO tcctvrecord (cctvrecordid, cctvrecordname, remark, createdby, createddate) VALUES (?,?,?,?,CURRENT_TIMESTAMP)");
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ssss", $AutoID, $currentFileName, $Remark, $userLogin);
                $stmt->execute();

                if ($stmt->affected_rows > 0) {
                    $path_filename_ext = $target_dir . $AutoID . "_" . $filename . "." . $ext;
                    $fixedFileName = $AutoID . "_" . $filename . "." . $ext;
                    $isSuccess = true;
                } else {
                    saveErrorLog($stmt->error, "CCTV Record", "Create-Update", $userLogin, $conn);
                    $isSuccess = false;
                }

                if (!file_exists($path_filename_ext)) {
                    if (move_uploaded_file($temp_name, $path_filename_ext)) {
                        $isSuccess = true;
                    } else {
                        $isSuccess = false;
                    }
                } else {
                    $isSuccess = false;
                }
            }
        } else {
			$query = ("UPDATE tcctvrecord SET remark=?, updatedby=?, updateddate=CURRENT_TIMESTAMP WHERE cctvrecordid=?");
			$stmt = $conn->prepare($query);
			$stmt->bind_param("sss", $Remark, $userLogin, $RecordID);
			$stmt->execute();
        }

        if (!$isSuccess) {
            $json->success = false;
            $json->msg = "[ERROR] Terjadi kesalahan, harap hubungi teknisi.";
            $json->data = $resultdata;
            $jsonstring = json_encode($json);
            echo $jsonstring;
        } else {
            $json->success = true;
            $json->msg = "Data berhasil disimpan.";
            $jsonstring = json_encode($json);
            echo $jsonstring;
        }
        $stmt->close();
    } catch (\Throwable $e) {
        $errorMsg = 'Error on line ' . $e->getLine() . ' in ' . $e->getFile() . ': ' . $e->getMessage();
        saveErrorLog($errorMsg, "CCTV Record", "Create-Update", $userLogin, $conn);
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

function generatedCCTVRecordID($conn)
{
    $return = "";
    $query = ("SELECT CASE WHEN EXISTS (SELECT cctvrecordid FROM tcctvrecord WHERE cctvrecordid LIKE CONCAT('RCID',YEAR(NOW()),LPAD(MONTH(NOW()), 2, '0'),'%')) THEN CONCAT('RCID', YEAR(NOW()),LPAD(MONTH(NOW()), 2, '0'),RIGHT(CONCAT('0000',CAST((CAST((SELECT RIGHT(cctvrecordid,4) FROM tcctvrecord WHERE cctvrecordid LIKE CONCAT('RCID',YEAR(NOW()),LPAD(MONTH(NOW()), 2, '0'),'%') ORDER BY cctvrecordid DESC LIMIT 1) as int) + 1) as varchar(20))), 4)) ELSE CONCAT('RCID',YEAR(NOW()),LPAD(MONTH(NOW()), 2, '0'),'0001') END AS ID;");
    $id = mysqli_query($conn, $query);
    if (mysqli_num_rows($id) > 0) {
        while ($rows = mysqli_fetch_array($id)) {
            $return = $rows['ID'];
        }
    }
    return $return;
}
