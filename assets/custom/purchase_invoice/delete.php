<?php 
 
include ("../connect.php");
 
$output = array('success' => false, 'messages' => 'Error while removing the information', 'so'=>'');
 
$id = $_REQUEST['member_id'];

$sql_counter = "SELECT * FROM counter WHERE `key` = 'purchase_invoice'";
$query_counter = $db->query($sql_counter);
$row_counter = $query_counter -> fetch_assoc();
$row_counter_arr = json_decode($row_counter['value'], true);

$sql_tmp = "SELECT * FROM purchase_invoice ORDER BY id DESC LIMIT 1";
$query_tmp = $db->query($sql_tmp);
$row_tmp = $query_tmp->fetch_assoc();

$series = $row_tmp['series'];

$po_numbers = json_decode($row_tmp['po_no'], true);
$len = sizeof($po_numbers);

for($i=0;$i<$len;$i++){
    $po = $po_numbers[$i];
    // echo $po.'<br/>';
    $sql_temp = "SELECT * FROM purchase_order WHERE po_no LIKE '%$po%'";
    $query_temp = $db->query($sql_temp);

    while($row_temp = $query_temp->fetch_assoc()){
        $items = json_decode($row_temp['items'], true);
        $l = sizeof($items['product']);

        for($i=0;$i<$l;$i++){
            $items['received'][$i] = 0;
        }

        $flag = 0;
        $sql1 = "SELECT * FROM purchase_invoice WHERE po_no LIKE '%$po%'";
        $query1 = $db->query($sql1);
        // $phase = 1;
        while($row1 = $query1->fetch_assoc())
        {
            $flag = 1;
            $items1 = json_decode($row1['items'], true);
            $len = sizeof($items1['product']);
            for($j=0;$j<$len;$j++)
            {
                $pr = $items1['product'][$j];
                $qty = $items1['quantity'][$j];
                // echo "Phase ".$phase++."<br/>";
                for($i=0;$i<$l;$i++)
                {
                    $pr_name = $items['product'][$i];

                    if($pr_name == $pr)
                    {
                        // echo "Loop Variable i ".$i."<br/>";
                        // echo $pr_name." --- </br>";
                        $temp = $items['received'][$i] + $qty;
                        // echo "Temp --- ".$temp."</br>";
                        if($temp > $items['quantity'][$i])
                        {
                            $balance = $items['quantity'][$i] - $items['received'][$i];
                            // echo "Balance --- ".$balance."</br>";
                            $items['received'][$i] += $balance;
                            $qty = $qty - $balance;
                            // echo $i." - Received : ".$items['received'][$i]."<br/>";
                        }
                        else
                        {
                            $items['received'][$i] += $qty;
                            // echo "Break --- ".$items['received'][$i]."</br>";
                            break;
                        }
                        
                    }
                }
            }

        }
        for($i=0;$i<$l;$i++)
        {
            $quantity = $items['quantity'][$i];
            $received = $items['received'][$i];
            // echo $quantity."<br/>";
            // echo $received."<br/>";
            if($quantity > $received)
            {
                $flag = $flag*0;
            }
            else
            {
                $flag = $flag*1;
            }
        }

        $items_arr = json_encode($items);

        if($flag == 1)
        {
            $sql3 = "UPDATE purchase_order SET items = '$items_arr', status='1' WHERE po_no = '$po'";
            $query3 = $db->query($sql3);
        }
        else
        {
            $sql3 = "UPDATE purchase_order SET items = '$items_arr', status='0' WHERE po_no = '$po'";
            $query3 = $db->query($sql3);
        }
    }
}

if($row_tmp['id'] == $id)
{
	$row_counter_arr['number'][0] = $row_counter_arr['number'][0] - 1;
	$counter_array = json_encode($row_counter_arr);
    $sql_counter = "UPDATE counter SET `value` = '$counter_array' WHERE `key` = 'purchase_invoice'";
    $query_counter = $db->query($sql_counter);
}

if($series == 'SECONDARY')
{
    $sql_counter = "SELECT * FROM counter WHERE `key` = 'secondary_purchase'";
    $query_counter = $db->query($sql_counter);
    $row_counter = $query_counter -> fetch_assoc();
    $row_counter_arr = json_decode($row_counter['value'], true);

    $order_no = $row_counter_arr['prefix'][0].$row_counter_arr['number'][0].$row_counter_arr['postfix'][0];
    $row_counter_arr['number'][0] = $row_counter_arr['number'][0] - 1;

    $counter_array = json_encode($row_counter_arr);
    $sql_counter = "UPDATE counter SET `value` = '$counter_array' WHERE `key` = 'secondary_purchase'";
    $query_counter = $db->query($sql_counter);
}
else if($series == 'PRIMARY')
{
    $sql_counter = "SELECT * FROM counter WHERE `key` = 'purchase_invoice'";
    $query_counter = $db->query($sql_counter);
    $row_counter = $query_counter -> fetch_assoc();
    $row_counter_arr = json_decode($row_counter['value'], true);

    $order_no = $row_counter_arr['prefix'][0].$row_counter_arr['number'][0].$row_counter_arr['postfix'][0];
    $row_counter_arr['number'][0] = $row_counter_arr['number'][0] - 1;

    $counter_array = json_encode($row_counter_arr);
    $sql_counter = "UPDATE counter SET `value` = '$counter_array' WHERE `key` = 'purchase_invoice'";
    $query_counter = $db->query($sql_counter);
}
 
$sql = "DELETE FROM purchase_invoice WHERE id = '$id'";
$query = $db->query($sql);
$db->query("DELETE FROM consignment_settlements WHERE purchase_invoice_id = '$id'");

if($query === TRUE) {
    $output['success'] = true;
    $output['messages'] = 'Successfully Deleted';
} else {
    $output['success'] = false;
    $output['messages'] = 'Error while removing the information';
}
 
echo json_encode($output);
?>