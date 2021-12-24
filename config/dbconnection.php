<?php
/**
 * 
 */
function openConn()
{
	try {
		$filepath = 'db.ini';
		$json = new stdClass();

	    //parse the ini file using default parse_ini_file() PHP function
	    $config = parse_ini_file($filepath, true);

		$servername = $config['servername'];
		$database = $config['database'];
		$username = $config['username'];
		$password = $config['password'];

	 	$conn = new mysqli($servername, $username, $password, $database) or die("Connect failed: %s\n". $conn -> error);

	 	return $conn;
	} catch (\Throwable $e) {
        $errorMsg = 'Error on line '.$e->getLine().' in '.$e->getFile() .': '.$e->getMessage();
        $json->success = false;
        $json->msg = "Error connection, please contact Administrator.";
        $jsonstring = json_encode($json);
        echo $jsonstring;
		exit();
	}
}

function closeConn($conn)
{
	$conn -> close();
}
?>