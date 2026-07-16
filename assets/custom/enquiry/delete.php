<?php 
 
include ("../connect.php");
 
$output = array('success' => false, 'messages' => 'Error while removing the information');
 
$id = $_REQUEST['member_id'] ?? '';

$sql_counter = "SELECT * FROM counter WHERE `key` = 'enquiry'";
$query_counter = $db->query($sql_counter);
$row_counter_arr = null;
if ($query_counter && $query_counter->num_rows > 0) {
    $row_counter = $query_counter->fetch_assoc();
    $row_counter_arr = json_decode($row_counter['value'] ?? '', true);
}

$sql_temp = "SELECT * FROM enquiry ORDER BY id DESC LIMIT 1";
$query_temp = $db->query($sql_temp);
$row_temp = ($query_temp && ($tmp = $query_temp->fetch_assoc())) ? $tmp : null;

if ($row_temp && isset($row_temp['id']) && $row_temp['id'] == $id)
{
    if (is_array($row_counter_arr) && isset($row_counter_arr['number'][0])) {
        $row_counter_arr['number'][0] = $row_counter_arr['number'][0] - 1;
        $counter_array = json_encode($row_counter_arr);
        $sql_counter = "UPDATE counter SET `value` = '$counter_array' WHERE `key` = 'enquiry'";
        $query_counter = $db->query($sql_counter);
    }
}
 
$sql = "DELETE FROM enquiry WHERE id = '$id'";
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
