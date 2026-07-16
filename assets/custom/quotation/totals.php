<?php 
 
include ("../connect.php");
 
$output = array('success' => false, 'messages' => 'Error while removing the information', 'so'=>'');
 
$id = $_REQUEST['member_id'];
 
$sql = "SELECT * FROM quotation WHERE id = '$id'";
$query = $db->query($sql);
$row = $query->fetch_assoc();

$totals = $row['display_totals'];
if($totals == '1')
    $totals = '0';
else
    $totals = '1';

$sql = "UPDATE quotation SET `display_totals` = '$totals' WHERE id = '$id'";
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