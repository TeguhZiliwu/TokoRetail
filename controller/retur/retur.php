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
        $RelatedTransactionID = mysqli_real_escape_string($conn, $postdata->TransactionID);
        $ItemList = $postdata->ItemList;

        if (count($ItemList) > 0) {
            $conn->autocommit(FALSE);

            // rollback should revert here
            $conn->begin_transaction();

            $TransactionID = generatedTransactionID($conn);
            $TransactionType = "RETUR";
            $query = ("INSERT INTO ttransaction (transactionid, transactiontype, relatedtransaction, transactiondate, remark, createdby, createddate) VALUES (?,?,?,CURRENT_TIMESTAMP,?,?,CURRENT_TIMESTAMP)");
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssss", $TransactionID, $TransactionType, $RelatedTransactionID, $Remark, $userLogin);
            if (!$stmt->execute()) {
                echo $stmt->error;
                throw new Exception($stmt->error);
            }

            for ($i = 0; $i < count($ItemList); $i++) {
                $ItemCode = $ItemList[$i]->ItemCode;
                $Qty = $ItemList[$i]->Qty;
                $PurchasePrice = $ItemList[$i]->PurchasePrice;
                $Discount = $ItemList[$i]->Discount;

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
                    $FixedQty = ($existingQty + $Qty);

                    $query = ("UPDATE tstock SET qty=?, updatedby=?, updateddate=CURRENT_TIMESTAMP WHERE itemcode=?");
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("sss", $FixedQty, $userLogin, $ItemCode);
                    if (!$stmt->execute()) {
                        throw new Exception($stmt->error);
                    }
                }

                $query = ("SELECT itemcode, qty FROM ttransactiondet WHERE itemcode = ? AND transactionid = ?");
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ss", $ItemCode, $RelatedTransactionID);
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
                    $FixedQty = ($existingQty - $Qty);

                    $query = ("UPDATE ttransactiondet SET qty=? WHERE itemcode=? AND transactionid = ?");
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("sss", $FixedQty, $ItemCode, $RelatedTransactionID);
                    if (!$stmt->execute()) {
                        throw new Exception($stmt->error);
                    }
                }

                $query = ("INSERT INTO ttransactiondet (transactionid, itemcode, qty, purchaseprice, discount) VALUES (?,?,?,?,?)");
                $stmt = $conn->prepare($query);
                $stmt->bind_param("sssss", $TransactionID, $ItemCode, $Qty, $PurchasePrice, $Discount);
                if (!$stmt->execute()) {
                    throw new Exception($stmt->error);
                }
            }

            if ($conn->commit()) {
                $stmt->close();
                $json->success = true;
                $json->msg = "Retur barang berhasil dicatat.";
                $jsonstring = json_encode($json);
                echo $jsonstring;
            } else {
                throw new Exception('Transaction commit failed.');
            }
        }
    } catch (\Throwable $e) {
        $conn->rollback();
        $errorMsg = 'Error on line ' . $e->getLine() . ' in ' . $e->getFile() . ': ' . $e->getMessage();
        saveErrorLog($errorMsg, "Retur", "Retur", $userLogin, $conn);
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
    $query = ("SELECT CASE WHEN EXISTS (SELECT transactionid FROM ttransaction WHERE transactionid LIKE CONCAT('RETRN',YEAR(NOW()),LPAD(MONTH(NOW()), 2, '0'),'%')) THEN CONCAT('RETRN', YEAR(NOW()),LPAD(MONTH(NOW()), 2, '0'),RIGHT(CONCAT('0000',CAST((CAST((SELECT RIGHT(transactionid,4) FROM ttransaction WHERE transactionid LIKE CONCAT('RETRN',YEAR(NOW()),LPAD(MONTH(NOW()), 2, '0'),'%') ORDER BY transactionid DESC LIMIT 1) as int) + 1) as varchar(20))), 4)) ELSE CONCAT('RETRN',YEAR(NOW()),LPAD(MONTH(NOW()), 2, '0'),'0001') END AS ID;");
    $id = mysqli_query($conn, $query);
    if (mysqli_num_rows($id) > 0) {
        while ($rows = mysqli_fetch_array($id)) {
            $return = $rows['ID'];
        }
    }
    return $return;
}
