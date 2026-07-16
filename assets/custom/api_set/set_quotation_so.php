<?php 

session_start();
require_once "../connect.php";

$so = $_REQUEST['so'];

$sql = "SELECT * FROM sales_order WHERE so_no LIKE '%$so%'";
$query = $db->query($sql);
$row = $query->fetch_assoc();

$quotations = json_decode($row['q_no'], true);
$l = sizeof($quotations);

for($i=0;$i<$l;$i++){
    $quotation = $quotations[$i];
    $sql_update = "UPDATE quotation SET status = '1' WHERE quotation_no = '$quotation'";
    $query_update = $db->query($sql_update);
}

?>