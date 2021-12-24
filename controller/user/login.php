<?php
include '../../config/appname.php';
include '../../config/dbconnection.php';
include '../../config/errorlog.php';

session_name($encryptedAppName);
session_start();

$json = new stdClass();
$conn = openConn();
$query = "";
$postdata = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$postdata = file_get_contents('php://input');
	$postdata = json_decode($postdata);
}

$UserID = mysqli_real_escape_string($conn, $postdata->UserID);
$Password = mysqli_real_escape_string($conn, $postdata->Password);

if (!empty($UserID) && !empty($Password)) {
    try {
        $resultdata = array();

        $query = ("SELECT userid, fullname, email, telpno, groupid FROM tuser WHERE userid = ? AND password = SHA2(?, 512)");
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $UserID, $Password);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            while ($data = $result->fetch_assoc()) {
                $data = array(
					'UserID' => $data['userid'],
					'FullName' => $data['fullname'],
					'Email' => $data['email'],
					'TelpNo' => $data['telpno'],
					'GroupID' => $data['groupid']
                );
                array_push($resultdata, $data);
            }            
				
			$_SESSION['userid'] = $resultdata[0]['UserID'];
			$_SESSION['fullname'] = $resultdata[0]['FullName'];
			$_SESSION['groupid'] = $resultdata[0]['GroupID'];
			$_SESSION['email'] = $resultdata[0]['Email'];
			$_SESSION['telpno'] = $resultdata[0]['TelpNo'];

            $json->success = true;
			$json->msg = "Login berhasil.";
            $json->data = $resultdata;
            $jsonstring = json_encode($json);
            echo $jsonstring;
        } else {
            $json->success = false;
		    $json->msg = "User ID atau Password salah.";
            $json->data = $resultdata;
            $jsonstring = json_encode($json);
            echo $jsonstring;
        }
        $stmt->close();
    } catch (\Throwable $e) {
        $errorMsg = 'Error on line ' . $e->getLine() . ' in ' . $e->getFile() . ': ' . $e->getMessage();
        saveErrorLog($errorMsg, "User", "Login", $userLogin, $conn);
        $json->success = false;
        $json->msg = "[ERROR] Terjadi kesalahan, harap hubungi teknisi.";
        $jsonstring = json_encode($json);
        echo $jsonstring;
    } finally {
        closeConn($conn);
    }
} else {
    $json->success = false;
    $json->msg = "User ID dan Password kosong.";
    $jsonstring = json_encode($json);
    echo $jsonstring;
}
