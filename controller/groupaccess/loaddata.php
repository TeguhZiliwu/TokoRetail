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

        $query = ("SELECT A.groupid, C.groupdesc, A.formid, B.formname, (SELECT COUNT(*) FROM tgroupaccess WHERE groupid = A.groupid) AS totalform, A.createdby, A.createddate, A.updatedby, A.updateddate
        FROM tgroupaccess A
        INNER JOIN tform B ON A.formid = B.formid
        INNER JOIN tgroup C ON A.groupid = C.groupid
        WHERE A.groupid LIKE ?");
        $stmt = $conn->prepare($query);
        $Search = '%' . $Search . '%';
        $stmt->bind_param("s", $Search);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            while ($data = $result->fetch_assoc()) {
                $data = array(
					'GroupID' => $data['groupid'],
					'GroupDesc' => $data['groupdesc'],
					'FormID' => $data['formid'],
					'FormName' => $data['formname'],
					'TotalForm' => $data['totalform'],
					'CreatedBy' => $data['createdby'],
					'CreatedDate' => $data['createddate'],
					'UpdatedBy' => $data['updatedby'],
					'UpdatedDate' => $data['updateddate']
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
        saveErrorLog($errorMsg, "Group", "Load Data", $userLogin, $conn);
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
