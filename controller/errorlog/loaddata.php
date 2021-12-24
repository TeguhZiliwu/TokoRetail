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

if (!empty($userLogin)) {
    try {
        $Search = mysqli_real_escape_string($conn, $_GET['search']);
        $resultdata = array();

        $query = ("SELECT errorid, errordesc, form, module, createdby, createddate 
                   FROM terrorlog 
                   WHERE (errorid LIKE ? OR errordesc LIKE ? OR form LIKE ? OR module LIKE ?) 
                   ORDER BY CreatedDate Desc");
        $stmt = $conn->prepare($query);
        $Search = '%' . $Search . '%';
		$stmt->bind_param("ssss", $Search, $Search, $Search, $Search);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            while ($data = $result->fetch_assoc()) {
                $data = array(
					'ErrorID' => $data['errorid'],
					'ErrorDesc' => $data['errordesc'],
					'Form' => $data['form'],
					'Module' => $data['module'],
					'CreatedBy' => $data['createdby'],
					'CreatedDate' => $data['createddate']
                );
                array_push($resultdata, $data);
            }

            $json->success = true;
            $json->data = $resultdata;
            $jsonstring = json_encode($json);
            echo $jsonstring;
        } else {
            $json->success = true;
            $json->data = $resultdata;
            $jsonstring = json_encode($json);
            echo $jsonstring;
        }
        $stmt->close();
    } catch (\Throwable $e) {
        $errorMsg = 'Error on line ' . $e->getLine() . ' in ' . $e->getFile() . ': ' . $e->getMessage();
        saveErrorLog($errorMsg, "Error Log", "Load Data", $userLogin, $conn);
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
