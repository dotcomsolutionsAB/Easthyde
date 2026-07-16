<?php 
 
include ("../connect.php");
 
$output = array('success' => false, 'messages' => 'Error while removing the information');
 
$id = $_REQUEST['member_id'] ?? '';

$sql_counter = "SELECT * FROM counter WHERE `key` = 'proforma'";
$query_counter = $db->query($sql_counter);
$row_counter_arr = [];
if ($query_counter && $query_counter->num_rows > 0) {
    $row_counter = $query_counter->fetch_assoc();
    $row_counter_arr = json_decode($row_counter['value'] ?? '', true);
    if (!is_array($row_counter_arr)) { $row_counter_arr = []; }
}

$sql_temp = "SELECT * FROM proforma ORDER BY id DESC LIMIT 1";
$query_temp = $db->query($sql_temp);
$row_temp = ($query_temp && ($tmp = $query_temp->fetch_assoc())) ? $tmp : null;

if ($row_temp) {
    $sales_order = json_decode($row_temp['so_no'] ?? '', true);
    if (!is_array($sales_order)) { $sales_order = []; }
    $l = sizeof($sales_order);

    for($i=0;$i<$l;$i++){
        $so = $sales_order[$i];
        $sql_update = "UPDATE sales_order SET status = '0' WHERE so_no = '$so'";
        $query_update = $db->query($sql_update);
    }

    if(($row_temp['id'] ?? '') == $id)
    {
        if (is_array($row_counter_arr) && isset($row_counter_arr['number'][0])) {
            $row_counter_arr['number'][0] = $row_counter_arr['number'][0] - 1;
            $counter_array = json_encode($row_counter_arr);
            $sql_counter = "UPDATE counter SET `value` = '$counter_array' WHERE `key` = 'proforma'";
            $query_counter = $db->query($sql_counter);
        }
    }
}
 
$sql = "DELETE FROM proforma WHERE id = '$id'";
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
