<?php

require_once "../connect.php";

$memberId = $_REQUEST['member_id'];

$sql = "SELECT * FROM proforma WHERE pr_no = '$memberId'";
$query = $db->query($sql);
$result = $query->fetch_assoc();

$client = $result['client_name'];

$sql_temp = "SELECT * FROM clients WHERE name = '$client'";
$query_temp = $db->query($sql_temp);
$result_temp = $query_temp->fetch_assoc();

$result['state'] = $result_temp['state'];

$db->close();
 
echo json_encode($result);

?>