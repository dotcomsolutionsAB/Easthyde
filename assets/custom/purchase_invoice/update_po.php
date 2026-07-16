<?php 
 
include ("../connect.php");
 
$output = array('success' => false, 'messages' => 'Error while saving the information', 'so'=>'');
 
$id = $_REQUEST['member_id'];
$oa_no = $_REQUEST['oa_no'];
 
$sql = "SELECT * FROM purchase_invoice WHERE id = '$id'";
$query = $db->query($sql);
$row = $query->fetch_assoc();

$sql = "UPDATE purchase_invoice SET `oa_no` = '$oa_no' WHERE id = '$id'";
$query = $db->query($sql);

if($query === TRUE) {
    $output['success'] = true;
    $output['messages'] = 'Successfully Updated';
} else {
    $output['success'] = false;
    $output['messages'] = 'Error while saving the information';
}
 
echo json_encode($output);
?>