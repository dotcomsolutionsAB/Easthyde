<?php

require_once "../connect.php";

$memberId = $_REQUEST['member_id'];

$result = array("email"=>"", "subject"=>"", "em_message"=>"", "status"=>"400");

$sql = "SELECT * FROM clients WHERE `id` = '$memberId'";
$query = $db->query($sql);
$row = $query->fetch_assoc();

$contacts = json_decode($row['contacts'], true);

$result['email'] = strtolower($contacts['email'][0]);
$result['subject'] = "Ledger";
$result['em_message'] = "Dear Sir/Madam,<br/> Please find the account ledger attached to this email.<br/><br/><i>Thanking You,</i><br/><strong>M.M. Lucky Enterprise</strong><br/>26, Strand Road, Ground Floor,<br/>Kolkata - 700 001, West Bengal, India<br/>mmleind@gmail.com<br/>Ph No. +91 6289778473 <br/>Website : www.easthyde.com";
$result['status'] = "200";

$db->close();
 
echo json_encode($result);

?>