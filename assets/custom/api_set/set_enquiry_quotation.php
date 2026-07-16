<?php 

session_start();
require_once "../connect.php";

$q_no = $_REQUEST['q_no'];

$sql = "SELECT * FROM quotation WHERE quotation_no LIKE '%$q_no%'";
$query = $db->query($sql);
$row = $query->fetch_assoc();

$quotation_top = json_decode($row['quotation_top'], true);
$l = sizeof($quotation_top['enquiry_no']);

for($i=0;$i<$l;$i++){
    $enquiry_no = $quotation_top['enquiry_no'][$i];
    $sql_update = "UPDATE enquiry SET status = '1' WHERE enquiry_no = '$enquiry_no'";
    $query_update = $db->query($sql_update);
}

?>