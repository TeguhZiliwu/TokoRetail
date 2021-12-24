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
$target_dir = "../../file/promotionpict/";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $postdata = file_get_contents('php://input');
    $postdata = json_decode($postdata);
}

if (!empty($userLogin)) {
    try {    
        $hasFileToUpload = $_POST['hasFileToUpload'];        
        $DeleteItem = json_decode($_POST['pictureItemDeletion'], true);
        $isSuccess = true;
        $resultdata = array();

        for ($i=0; $i < count($DeleteItem); $i++) { 
            deleteExistingPicture($DeleteItem[$i]["OldPictureName"], $target_dir);
        }

        if ($hasFileToUpload == "yes") {
            if (($_FILES['fileImage']['name'] != "")) {
                $totalFile = count($_FILES['fileImage']['name']);
    
                for ($i = 0; $i < $totalFile; $i++) {
                    $file = $_FILES['fileImage']['name'][$i];
                    $path = pathinfo($file);
                    $filename = $path['filename'];
                    $ext = $path['extension'];
                    $temp_name = $_FILES['fileImage']['tmp_name'][$i];
                    $path_filename_ext = $target_dir . $filename . "." . $ext;
                    $fixedFileName = $filename . "." . $ext;
    
                    if (!file_exists($path_filename_ext)) {
                        if (move_uploaded_file($temp_name, $path_filename_ext)) {
                            $isSuccess = true;
                        } else {
                            $isSuccess = false;
                            $data = array(
                                'FileName' => $filename . "." . $ext
                            );
                            array_push($resultdata, $data);
                        }
                    } else {
                        $isSuccess = false;
                        $data = array(
                            'FileName' => $filename . "." . $ext
                        );
                        array_push($resultdata, $data);
                    }
                }
            }
        }
    
        if (!$isSuccess) {
            $json->success = false;
            $json->msg = "Gagal menyimpan foto.";
            $json->data = $resultdata;
            $jsonstring = json_encode($json);
            echo $jsonstring;
        } else {
            $json->success = true;
            $json->msg = "Foto berhasil disimpan.";
            $jsonstring = json_encode($json);
            echo $jsonstring;
        }
    } catch (\Throwable $e) {
        $errorMsg = 'Error on line ' . $e->getLine() . ' in ' . $e->getFile() . ': ' . $e->getMessage();
        saveErrorLog($errorMsg, "Item", "Upload Image", $userLogin, $conn);
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

function generatedItemPictureID($conn)
{
    $return = "";
    $query = ("SELECT CASE WHEN EXISTS (SELECT itempictureid FROM titempicture WHERE itempictureid LIKE CONCAT('IPID',YEAR(NOW()),LPAD(MONTH(NOW()), 2, '0'),'%')) THEN CONCAT('IPID', YEAR(NOW()),LPAD(MONTH(NOW()), 2, '0'),RIGHT(CONCAT('0000',CAST((CAST((SELECT RIGHT(itempictureid,4) FROM titempicture WHERE itempictureid LIKE CONCAT('IPID',YEAR(NOW()),LPAD(MONTH(NOW()), 2, '0'),'%') ORDER BY itempictureid DESC LIMIT 1) as int) + 1) as varchar(20))), 4)) ELSE CONCAT('IPID',YEAR(NOW()),LPAD(MONTH(NOW()), 2, '0'),'0001') END AS ID;");
    $id = mysqli_query($conn, $query);
    if (mysqli_num_rows($id) > 0) {
        while ($rows = mysqli_fetch_array($id)) {
            $return = $rows['ID'];
        }
    }
    return $return;
}

function deleteExistingPicture($OldPictureName, $target_dir)
{
    $FileName = $target_dir . $OldPictureName;
    if (file_exists($FileName)) {
        unlink($FileName);
    }
}
