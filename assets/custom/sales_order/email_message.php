<?php

require_once "../connect.php";

$memberId = $_REQUEST['member_id'];

$result = array("email"=>"", "subject"=>"", "em_message"=>"", "status"=>"400");

$sql = "SELECT * FROM sales_order WHERE so_no = '$memberId'";
$query = $db->query($sql);
$row = $query->fetch_assoc();

$result['email'] = "";
$result['subject'] = "Sales Order - ".$row['so_no'];
$result['em_message'] = "Dear Sir/Madam,<br/> Please find the sales order attached to this email.<br/><br/><i>Thanking You,</i><br/><strong>Ammar Industrial Corporation</strong><br/>83/85 NETAJI SUBHASH ROAD,<br/>ROOM NO, A33, GROUND FLOOR<br/>KOLKATA - 700 001, WEST BENGAL, INDIA<br/>info@ammarindustrial.in<br/>Whatsapp : 79806 84655<br/>Ph No. +91 79806 84655 / (033) 2231-6239 / 3316-5010 <br/>Website : www.easthyde.com";
$result['status'] = "200";

$db->close();
 
echo json_encode($result);

?>