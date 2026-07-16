<?php
session_start();
require_once "../connect.php";
setlocale(LC_MONETARY, 'en_IN');

$pagination = $_REQUEST['pagination'] ?? [];
$query_array = $_REQUEST['query'] ?? [];
$sort_array = $_REQUEST['sort'] ?? [];

$query = $query_array['generalSearch'] ?? '';

$output = array('data' => array());

$count=1;
$sql = "SELECT * FROM clients WHERE `name` LIKE '%$query%' ORDER BY `name`";
$query = $db->query($sql);
if ($query) {
while($row = $query->fetch_assoc()){

    $due            = 0;
    $particulars    = '';
    $client         = $row['name'];
    $credit_period  = $row['credit_period'];

    if($credit_period == '' || $credit_period == null){
        $credit_period = 0;
    }

    //Comment out the below code to use credit days
    $credit_period = 0;

    $opening_due = $row['opening_balance'] - $row['paid'];
    if($opening_due > 0){
        $due            += $opening_due;
        $particulars    = $particulars."Opening, ";
    }

    $term = '-'.$credit_period.' days';
    $start_date = date("Y-m-d", strtotime($term));
    $actionBtn = '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-sm" data-toggle="modal" data-target="#kt_modal_whatsapp" onclick="Wa_payment_followup(\''.$row['id'].'\')">
                            <i class="flaticon-whatsapp"></i>
                        </a>';

    $sql_2 = "SELECT * FROM sales_invoice WHERE client_name = '$client' AND (status = '0' OR status = '2') AND `series` = 'PRIMARY' AND `si_date` < '$start_date' ORDER BY si_date";
    $query_2 = $db->query($sql_2);
    $am_paid = 0;
    if ($query_2) {
    while($row_2 = $query_2->fetch_assoc()){
        
        $am_paid = 0;
        $si_no = $row_2['si_no'];
        $sql_check = "SELECT * FROM receipts WHERE `sales_invoice` LIKE '%$si_no%'";
        $query_check = $db->query($sql_check);
        if ($query_check) {
        while($row_check = $query_check->fetch_assoc()){
            $inv_array = json_decode($row_check['sales_invoice'] ?? '', true);
            $len = (is_array($inv_array) && isset($inv_array['si_no']) && is_array($inv_array['si_no'])) ? sizeof($inv_array['si_no']) : 0;
            for($i=0;$i<$len;$i++){
                if($inv_array['si_no'][$i] == $si_no){
                    $am_paid += $inv_array['amount'][$i];
                }
            }
        }
        }

        $due            += $row_2['total'] - $am_paid;
        $particulars    = $particulars.$row_2['si_no'].", ";
    }
    }

    if($particulars != '' && $due > 0)
    {
        if($am_paid > 0){
            $output['data'][] = array(      
                'SN'            => $row['id'],
                'Id'            => $row['id'],
                'Name'          => $row['name'],
                'Particulars'   => $particulars,
                'Due'           => number_format((float)$due, 2),
                'Actions'        =>$actionBtn,
            );
        }else{
            $output['data'][] = array(      
                'SN'            => $row['id'],
                'Id'            => $row['id'],
                'Name'          => $row['name'],
                'Particulars'   => $particulars,
                'Due'           => number_format((float)$due, 2),
                'Actions'        =>$actionBtn,
            );
        }
    }
}
}

echo json_encode($output);

?>
