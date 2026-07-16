<?php 
 
include ("../connect.php");
 
$output = array('success' => false, 'messages' => 'Error while removing the information', 'so'=>'');
 
$id = $_REQUEST['member_id'];
 
$sql = "SELECT * FROM sales_invoice WHERE id = '$id'";
$query = $db->query($sql);
$row = $query->fetch_assoc();

$hsn = $row['hsn_table'];
if($hsn == '1')
    $hsn = '0';
else
    $hsn = '1';

$sql = "UPDATE sales_invoice SET `hsn_table` = '$hsn' WHERE id = '$id'";
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