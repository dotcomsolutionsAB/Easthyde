<?php

require_once "../connect.php";

$memberId = $_REQUEST['member_id'];

$result = array("email"=>"", "subject"=>"", "em_message"=>"", "status"=>"400");

$sql = "SELECT * FROM sales_invoice WHERE si_no = '$memberId'";
$query = $db->query($sql);
$row = $query->fetch_assoc();

$sql_temp = "SELECT * FROM settings";
$query_temp = $db->query($sql_temp);
$row_temp = $query_temp->fetch_assoc();

$company = $row_temp['company'];
$address1 = $row_temp['address_1'];
$address2 = $row_temp['address_2'];
$city = $row_temp['city'];
$pincode = $row_temp['pincode'];
$state = $row_temp['state'];
$country = $row_temp['country'];
$email = $row_temp['email'];
$company_website = $row_temp['company_website'];

$result['email'] = "";
$result['subject'] = "Sales Invoice - ".$row['si_no'];
$result['em_message'] = "Dear Sir/Madam,<br/> Please find the sales invoice attached to this email.<br/><br/><i>Thanking You,</i><br/><strong>M.M. Lucky Enterprise</strong><br/>26, Strand Road, Ground Floor,<br/>Kolkata - 700 001, West Bengal, India<br/>mmleind@gmail.com<br/>Ph No. +91 6289778473 <br/>Website : www.easthyde.com";
$result['status'] = "200";

$db->close();
 
echo json_encode($result);

?>