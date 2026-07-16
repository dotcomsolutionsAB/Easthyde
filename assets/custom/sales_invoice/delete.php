<?php 
 
include ("../connect.php");
 
$output = array('success' => false, 'messages' => 'Error while removing the information', 'so'=>'');
 
$id = $_REQUEST['member_id'];

$sql_series = "SELECT series FROM sales_invoice WHERE id = '$id'";
$query_series = $db->query($sql_series);
$row_series = $query_series->fetch_assoc();
$series = $row_series['series'];

if($series == 'PRIMARY')
{
    $sql_counter = "SELECT * FROM counter WHERE `key` = 'sales_invoice'";
    $query_counter = $db->query($sql_counter);
    $row_counter = $query_counter -> fetch_assoc();
    $row_counter_arr = json_decode($row_counter['value'], true);
}else{
    $sql_counter = "SELECT * FROM counter WHERE `key` = 'secondary'";
    $query_counter = $db->query($sql_counter);
    $row_counter = $query_counter -> fetch_assoc();
    $row_counter_arr = json_decode($row_counter['value'], true);
}

$sql_tmp = "SELECT * FROM sales_invoice WHERE `series` = '$series' ORDER BY id DESC LIMIT 1";
$query_tmp = $db->query($sql_tmp);
$row_tmp = $query_tmp->fetch_assoc();

$so_numbers = json_decode($row_tmp['so_no'], true);
$len = sizeof($so_numbers);

// for($i=0;$i<$len;$i++){
//     $so = $so_numbers[$i];
//     $sql_temp = "SELECT * FROM sales_order WHERE so_no LIKE '%$so%'";
//     $query_temp = $db->query($sql_temp);

//     while($row_temp = $query_temp->fetch_assoc()){
//         $items = json_decode($row_temp['items'], true);
//         $l = sizeof($items['product']);

//         for($i=0;$i<$l;$i++){
//             $items['received'][$i] = 0;
//         }

//         $flag = 0;
//         $sql1 = "SELECT * FROM sales_invoice WHERE so_no LIKE '%$so%' AND id != '$id'";
//         $query1 = $db->query($sql1);
//         while($row1 = $query1->fetch_assoc())
//         {
//             $flag = 1;
//             $items1 = json_decode($row1['items'], true);
//             $len = sizeof($items1['product']);
//             for($j=0;$j<$len;$j++)
//             {
//                 $pr = $items1['product'][$j];
//                 $qty = $items1['quantity'][$j];
//                 for($i=0;$i<$l;$i++)
//                 {
//                     $pr_name = $items['product'][$i];

//                     if($pr_name == $pr)
//                     {
//                         $temp = $items['received'][$i] + $qty;
//                         if($temp > $items['quantity'][$i])
//                         {
//                             $balance = $items['quantity'][$i] - $items['received'][$i];
//                             $items['received'][$i] += $balance;
//                             $qty = $qty - $balance;
//                         }
//                         else
//                         {
//                             $items['received'][$i] += $qty;
//                             break;
//                         }
                        
//                     }
//                 }
//             }

//         }
//         for($i=0;$i<$l;$i++)
//         {
//             $quantity = $items['quantity'][$i];
//             $received = $items['received'][$i];
//             if($quantity > $received)
//             {
//                 $flag = $flag*0;
//             }
//             else
//             {
//                 $flag = $flag*1;
//             }
//         }

//         $items_arr = json_encode($items);

//         if($flag == 1)
//         {
//             $sql3 = "UPDATE sales_order SET items = '$items_arr', status='1' WHERE so_no = '$so'";
//             $query3 = $db->query($sql3);
//         }
//         else
//         {
//             $sql3 = "UPDATE sales_order SET items = '$items_arr', status='0' WHERE so_no = '$so'";
//             $query3 = $db->query($sql3);
//         }
//     }
// }

if($row_tmp['id'] == $id)
{
    if($series == 'PRIMARY'){
    	$row_counter_arr['number'][0] = $row_counter_arr['number'][0] - 1;
    	$counter_array = json_encode($row_counter_arr);
        $sql_counter = "UPDATE counter SET `value` = '$counter_array' WHERE `key` = 'sales_invoice'";
        $query_counter = $db->query($sql_counter);
    }else{
        $row_counter_arr['number'][0] = $row_counter_arr['number'][0] - 1;
        $counter_array = json_encode($row_counter_arr);
        $sql_counter = "UPDATE counter SET `value` = '$counter_array' WHERE `key` = 'secondary'";
        $query_counter = $db->query($sql_counter);
    }
}
 
$sql = "DELETE FROM sales_invoice WHERE id = '$id'";
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