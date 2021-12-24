<?php

function error_handler( $errno, $errmsg, $filename, $linenum, $vars )
{
    // error was suppressed with the @-operator
    if ( 0 === error_reporting() ){
		return false;
	}

	throw new \ErrorException( sprintf('%s: %s', $errno, $errmsg ), 0, $errno, $filename, $linenum );
    // if ( $errno !== E_ERROR )
    //   throw new \ErrorException( sprintf('%s: %s', $errno, $errmsg ), 0, $errno, $filename, $linenum );
}

set_error_handler('error_handler');

function saveErrorLog($errordesc, $form, $module, $userLogin, $conn){
	$conn->autocommit(TRUE);
	$query = ("INSERT INTO terrorlog (errorid, errordesc, form, module, createdby, createddate) VALUES (?,?,?,?,?, CURRENT_TIMESTAMP)");
	$stmt = $conn->prepare($query); 
	$errorid = generatedErrorID($conn);
	$userLogin = 'Admin';
	$stmt->bind_param("sssss", $errorid, $errordesc, $form, $module, $userLogin);
	$stmt->execute();
}

function generatedErrorID($conn){
	$return = "";
	$query = ("SELECT CASE WHEN EXISTS (SELECT errorid FROM terrorlog WHERE errorid LIKE CONCAT('ER',YEAR(NOW()),LPAD(MONTH(NOW()), 2, '0'),'%')) THEN CONCAT('ER', YEAR(NOW()),LPAD(MONTH(NOW()), 2, '0'),RIGHT(CONCAT('0000',CAST((CAST((SELECT RIGHT(errorid,4) FROM terrorlog WHERE errorid LIKE CONCAT('ER',YEAR(NOW()),LPAD(MONTH(NOW()), 2, '0'),'%') ORDER BY errorid DESC LIMIT 1) as int) + 1) as varchar(20))), 4)) ELSE CONCAT('ER',YEAR(NOW()),LPAD(MONTH(NOW()), 2, '0'),'0001') END AS ID");
	$id = mysqli_query($conn, $query);				
	if (mysqli_num_rows($id) > 0) {  
		while ($rows = mysqli_fetch_array($id))
		{	
			$return = $rows['ID'];
		}
	}
	return $return;
}
?>