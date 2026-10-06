<?php

require_once "../connect.php";

$memberId = $_REQUEST['member_id'];

$result = array("email"=>"", "subject"=>"", "em_message"=>"", "status"=>"400");

$sql = "SELECT * FROM purchase_order WHERE po_no = '$memberId'";
$query = $db->query($sql);
$row = $query->fetch_assoc();

$result['email'] = "";
$result['subject'] = "Purchase Order - ".$row['po_no'];
$result['em_message'] = "Dear Sir/Madam,<br/> Please find the purchase order attached to this email.<br/><br/><i>Thanking You,</i><br/><strong>M.M. Lucky Enterprise</strong><br/>26, Strand Road, Ground Floor,<br/>Kolkata - 700 001, West Bengal, India<br/>mmleind@gmail.com<br/>Ph No. +91 6289778473 <br/>Website : www.easthyde.com";
$result['status'] = "200";

$db->close();
 
echo json_encode($result);

?>