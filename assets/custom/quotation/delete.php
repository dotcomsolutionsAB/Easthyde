<?php 
 
include ("../connect.php");
 
$output = array('success' => false, 'messages' => 'Error while removing the information');
 
$id = $_REQUEST['member_id'];

$sql_counter = "SELECT * FROM counter WHERE `key` = 'quotation'";
$query_counter = $db->query($sql_counter);
$row_counter = $query_counter -> fetch_assoc();
$row_counter_arr = json_decode($row_counter['value'], true);

$sql_temp = "SELECT * FROM quotation ORDER BY id DESC LIMIT 1";
$query_temp = $db->query($sql_temp);
$row_temp = $query_temp->fetch_assoc();

$quotation_top = json_decode($row_temp['quotation_top'], true);
$l = sizeof($quotation_top['enquiry_no']);

for($i=0;$i<$l;$i++){
    $enquiry_no = $quotation_top['enquiry_no'][$i];
    $sql_update = "UPDATE enquiry SET status = '0' WHERE enquiry_no = '$enquiry_no'";
    $query_update = $db->query($sql_update);
}

if($row_temp['id'] == $id)
{
	$row_counter_arr['number'][0] = $row_counter_arr['number'][0] - 1;
	$counter_array = json_encode($row_counter_arr);
    $sql_counter = "UPDATE counter SET `value` = '$counter_array' WHERE `key` = 'quotation'";
    $query_counter = $db->query($sql_counter);
}
 
$sql = "DELETE FROM quotation WHERE id = '$id'";
$query = $db->query($sql);

if($query === TRUE) {
    $output['success'] = true;
    $output['messages'] = 'Successfully Deleted';
} else {
    $output['success'] = false;
    $output['messages'] = 'Error while removing the information';
}
 
echo json_encode($output);
?>