<?php 
 
include ("../connect.php");
 
$output = array('success' => false, 'messages' => 'Error while removing the information', 'so'=>'');
 
$id = $_REQUEST['member_id'];
$awb = $_REQUEST['awb_no'];
 
$sql = "SELECT * FROM sales_invoice WHERE id = '$id'";
$query = $db->query($sql);
$row = $query->fetch_assoc();

$invoice_details = json_decode($row['invoice_details'], true);

$invoice_details['despatch_doc_no'] = $awb;

$invoice = json_encode($invoice_details);

$sql = "UPDATE sales_invoice SET `invoice_details` = '$invoice' WHERE id = '$id'";
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