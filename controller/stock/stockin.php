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
        $Remark = mysqli_real_escape_string($conn, $postdata->Remark);
        $ItemList = $postdata->ItemList;

        if (count($ItemList) > 0) {
            $conn->autocommit(FALSE);

            // rollback should revert here
            $conn->begin_transaction();

            $TransactionID = generatedTransactionID($conn);
            $TransactionType = "IN";
            $query = ("INSERT INTO ttransaction (transactionid, transactiontype, transactiondate, remark, createdby, createddate) VALUES (?,?,CURRENT_TIMESTAMP,?,?,CURRENT_TIMESTAMP)");
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssss", $TransactionID, $TransactionType, $Remark, $userLogin);
            if (!$stmt->execute()) {
                echo $stmt->error;
                throw new Exception($stmt->error);
            }

            for ($i = 0; $i < count($ItemList); $i++) {
                $ItemCode = $ItemList[$i]->ItemCode;
                $Qty = $ItemList[$i]->Qty;
                $PurchasePrice = $ItemList[$i]->PurchasePrice;

                $query = ("SELECT itemcode, qty FROM tstock WHERE itemcode = ?");
                $stmt = $conn->prepare($query);
                $stmt->bind_param("s", $ItemCode);
                if (!$stmt->execute()) {
                    echo $stmt->error;
                    throw new Exception($stmt->error);
                }
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $existingQty = 0;

                    while ($data = $result->fetch_assoc()) {
                        $existingQty = $data['qty'];
                    }

                    $Qty = (int)$Qty;
                    $existingQty = (int)$existingQty;
                    $Qty = ($Qty + $existingQty);

                    $query = ("UPDATE tstock SET qty=?, updatedby=?, updateddate=CURRENT_TIMESTAMP WHERE itemcode=?");
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("sss", $Qty, $userLogin, $ItemCode);
                    if (!$stmt->execute()) {
                        throw new Exception($stmt->error);
                    }
                } else {
                    $query = ("INSERT INTO tstock (itemcode, qty, createdby, createddate) VALUES (?,?,?, CURRENT_TIMESTAMP)");
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("sss", $ItemCode, $Qty, $userLogin);
                    if (!$stmt->execute()) {
                        throw new Exception($stmt->error);
                    }
                }
                
                $query = ("INSERT INTO ttransactiondet (transactionid, itemcode, qty, purchaseprice) VALUES (?,?,?,?)");
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ssss", $TransactionID, $ItemCode, $Qty, $PurchasePrice);
                if (!$stmt->execute()) {
                    throw new Exception($stmt->error);
                }
            }

            if ($conn->commit()) {
                $stmt->close();
                $json->success = true;
                $json->msg = "Stock berhasil ditambahkan.";
                $jsonstring = json_encode($json);
                echo $jsonstring;
            } else {
                throw new Exception('Transaction commit failed.');
            }
        }
    } catch (\Throwable $e) {
        $conn->rollback();
        $errorMsg = 'Error on line ' . $e->getLine() . ' in ' . $e->getFile() . ': ' . $e->getMessage();
        saveErrorLog($errorMsg, "Stock", "Stock In", $userLogin, $conn);
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

function generatedTransactionID($conn)
{
    $return = "";
    $query = ("SELECT CASE WHEN EXISTS (SELECT transactionid FROM ttransaction WHERE transactionid LIKE CONCAT('TRIN', YEAR(NOW()), LPAD(MONTH(NOW()), 2, '0'), '%')) THEN CONCAT('TRIN', YEAR(NOW()), LPAD(MONTH(NOW()), 2, '0'), LPAD(CAST(COALESCE((SELECT RIGHT(transactionid, 4) FROM ttransaction WHERE transactionid LIKE CONCAT('TRIN', YEAR(NOW()), LPAD(MONTH(NOW()), 2, '0'), '%') ORDER BY transactionid DESC LIMIT 1), '0000') + 1 AS CHAR), 4, '0')) ELSE CONCAT('TRIN', YEAR(NOW()), LPAD(MONTH(NOW()), 2, '0'), '0001') END AS ID;");
    $id = mysqli_query($conn, $query);
    if (mysqli_num_rows($id) > 0) {
        while ($rows = mysqli_fetch_array($id)) {
            $return = $rows['ID'];
        }
    }
    return $return;
}
