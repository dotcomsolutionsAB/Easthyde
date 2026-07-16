<?php

require_once "../connect.php";

$memberId = $_REQUEST['member_id'] ?? '';

$sql = "SELECT * FROM quotation WHERE quotation_no = '$memberId'";
$query = $db->query($sql);
$result = ($query) ? $query->fetch_assoc() : null;

if ($result) {
    $client = $result['client'] ?? '';
    $address = $result['address'] ?? '';
    $quotation_no = $result['quotation_no'] ?? '';
    $mobile = $result['mobile'] ?? '';

    $sql_temp = "SELECT * FROM clients WHERE name = '$client'";
    $query_temp = $db->query($sql_temp);
    $result_temp = ($query_temp) ? $query_temp->fetch_assoc() : null;

    $result['state'] = $result_temp['state'] ?? '';
    $result['country'] = $result_temp['country'] ?? '';
}

$db->close();

echo json_encode($result ?? (object)[]);

?>
