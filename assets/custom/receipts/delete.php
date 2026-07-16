<?php 
 
include ("../connect.php");
 
$output = array('success' => false, 'messages' => 'Error while removing the member information');
 
$id = $_REQUEST['member_id'] ?? '';
$r_no = '';

$sql_counter = "SELECT * FROM counter WHERE `key` = 'receipt'";
$query_counter = $db->query($sql_counter);
$row_counter_arr = [];
if ($query_counter && $query_counter->num_rows > 0) {
    $row_counter = $query_counter->fetch_assoc();
    $row_counter_arr = json_decode($row_counter['value'] ?? '', true);
    if (!is_array($row_counter_arr)) { $row_counter_arr = []; }
}

$sql_temp = "SELECT * FROM receipts ORDER BY id DESC LIMIT 1";
$query_temp = $db->query($sql_temp);
$row_temp = ($query_temp && ($tmp = $query_temp->fetch_assoc())) ? $tmp : null;

if ($row_temp) {
    $r_no = $row_temp['r_no'] ?? '';
    $client = $row_temp['client'] ?? '';

    $sales_invoice = json_decode($row_temp['sales_invoice'] ?? '', true);
    if (!is_array($sales_invoice)) { $sales_invoice = []; }
    $len = (isset($sales_invoice['si_no']) && is_array($sales_invoice['si_no'])) ? sizeof($sales_invoice['si_no']) : 0;

    for($j=0;$j<$len;$j++){
        $si_no = $sales_invoice['si_no'][$j];
        $amount = $sales_invoice['amount'][$j];

        if($si_no == 'Opening'){
            $sql_temp = "SELECT * FROM clients WHERE name = '$client'";
            $query_temp = $db->query($sql_temp);
            $row_temp = ($query_temp && ($tmp = $query_temp->fetch_assoc())) ? $tmp : null;

            if ($row_temp) {
                $paid = $row_temp['paid'] - $amount;

                $sql_update = "UPDATE clients SET paid ='$paid' WHERE name = '$client'";
                $query_update = $db->query($sql_update);
            }
        }

        $received = 0;

        $sql_temp = "SELECT * FROM receipts WHERE sales_invoice LIKE '%$si_no%' AND status = '1' AND id != '$id'";
        $query_temp = $db->query($sql_temp);
        if ($query_temp) {
            while($row_temp = $query_temp->fetch_assoc()){

                $si_arr = json_decode($row_temp['sales_invoice'] ?? '', true);
                if (!is_array($si_arr)) { $si_arr = []; }
                $l = (isset($si_arr['si_no']) && is_array($si_arr['si_no'])) ? sizeof($si_arr['si_no']) : 0;
                for($i=0;$i<$l;$i++){
                    if($si_arr['si_no'][$i] == $si_no){
                        $received += $si_arr['amount'][$i];
                    }
                }
            }
        }
        $due = $amount - $received;
        if($received == '0'){
            $sql_update = "UPDATE sales_invoice SET status ='0' WHERE si_no = '$si_no'";
            $query_update = $db->query($sql_update);
        }	
        else
        {
            if($amount >= $due){
                $sql_update = "UPDATE sales_invoice SET status ='1' WHERE si_no = '$si_no'";
                $query_update = $db->query($sql_update);
            }else{
                $sql_update = "UPDATE sales_invoice SET status ='2' WHERE si_no = '$si_no'";
                $query_update = $db->query($sql_update);
            }
        }


    }

    if(($row_temp['id'] ?? '') == $id)
    {
        if (is_array($row_counter_arr) && isset($row_counter_arr['number'][0])) {
            $row_counter_arr['number'][0] = $row_counter_arr['number'][0] - 1;
            $counter_array = json_encode($row_counter_arr);
            $sql_counter = "UPDATE counter SET `value` = '$counter_array' WHERE `key` = 'receipt'";
            $query_counter = $db->query($sql_counter);
        }
    }
}
 
$sql = "DELETE FROM receipts WHERE id = '$id'";
$query = $db->query($sql);

if($query === TRUE) {
    $output['success'] = true;
    $output['messages'] = 'Successfully Deleted';
    $output['r_no'] = $r_no;

} else {
    $output['success'] = false;
    $output['messages'] = 'Error while removing the member information';
}
 
echo json_encode($output);
?>
