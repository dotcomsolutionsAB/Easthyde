<?php

require_once "../connect.php";

$memberId = $_REQUEST['member_id'] ?? '';

$sql = "SELECT * FROM purchase_quotation WHERE id = '$memberId'";
$query = $db->query($sql);
$result = ($query) ? $query->fetch_assoc() : null;

if ($result) {
    $supplier = $result['supplier_name'] ?? '';

    $sql_2 = "SELECT * FROM suppliers WHERE name = '$supplier'";
    $query_2 = $db->query($sql_2);
    $result_2 = ($query_2) ? $query_2->fetch_assoc() : null;

    $result['state'] = $result_2['state'] ?? '';
}

$db->close();

echo json_encode($result ?? (object)[]);

?>
