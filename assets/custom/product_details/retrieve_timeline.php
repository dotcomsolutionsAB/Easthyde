<?php
session_start();
require_once "../connect.php";
setlocale(LC_MONETARY, 'en_IN');

$dt_start = $_SESSION['start'] ?? '';
$dt_end = $_SESSION['end'] ?? '';

$pagination = $_REQUEST['pagination'] ?? [];
$query_array = $_REQUEST['query'] ?? [];
$sort_array = $_REQUEST['sort'] ?? [];

$search = $query_array['search_pd_timeline'] ?? '';
if($search == '')
    $search = '%';
$result=array('date'=>array(),'masters'=>array(),'type'=>array(),'reference'=>array(),'qty'=>array(),'rate'=>array());
$output = array('data' => array());

$pr = $_SESSION['pd_product_name'] ?? '';
$pr_search="\"".$pr."\"";

$count=1;

//Purchase
$sql = "SELECT * FROM purchase_invoice WHERE items LIKE '%$pr_search%' AND (`pi_no` LIKE '%$search%' OR `supplier_name` LIKE '%$search%') AND `pi_date` BETWEEN '$dt_start' AND '$dt_end'";
$query = $db->query($sql);
if ($query) {
while($row = $query->fetch_assoc()){

    $qty = '0';
    $rate = '';
    $amount = '';

    $items = json_decode($row['items'] ?? '', true);
    $len = (is_array($items) && isset($items['product']) && is_array($items['product'])) ? sizeof($items['product']) : 0;

    for($i=0;$i<$len;$i++){
        if(($items['product'][$i] ?? '') == $pr){
            $qty += $items['quantity'][$i] ?? 0;
            $rate = $items['price'][$i] ?? '';
            $amount = ($items['price'][$i] ?? 0) * ($items['quantity'][$i] ?? 0);
            if(($items['discount'][$i] ?? 0) > 0){
                $amount = $amount * (100 - ($items['discount'][$i] ?? 0)) / 100;
            }

        }
    }


    $result['date'][] = !empty($row['pi_date']) ? date('d-m-Y', strtotime($row['pi_date'])) : '';
    $result['masters'][] = $row['supplier_name'];
    $result['type'][] = 'Purchase';
    $result['reference'][] = "<a href='../assets/custom/purchase_invoice_print.php?id=".$row['id']."&type=print' target='_blank'>".$row['pi_no']."</a>";
    $result['qty'][] = $qty;
    $result['rate'][] = number_format((float)$amount, 2);
}
}

//Sales
$sql = "SELECT * FROM sales_invoice WHERE `items` LIKE '%$pr_search%' AND (`si_no` LIKE '%$search%' OR `client_name` LIKE '%$search%') AND `si_date` BETWEEN '$dt_start' AND '$dt_end' ";
$query = $db->query($sql);
if ($query) {
while($row = $query->fetch_assoc()){

    $qty = '0';
    $rate = '';
    $amount = '';

    $items = json_decode($row['items'] ?? '', true);
    $len = (is_array($items) && isset($items['product']) && is_array($items['product'])) ? sizeof($items['product']) : 0;

    for($i=0;$i<$len;$i++){
        if(($items['product'][$i] ?? '') == $pr){
            $qty += $items['quantity'][$i] ?? 0;
            $rate = $items['price'][$i] ?? '';
            $amount = ($items['price'][$i] ?? 0) * ($items['quantity'][$i] ?? 0);
            if(($items['discount'][$i] ?? 0) > 0){
                $amount = $amount * (100 - ($items['discount'][$i] ?? 0)) / 100;
            }
        }
    }

    $result['date'][] = !empty($row['si_date']) ? date('d-m-Y', strtotime($row['si_date'])) : '';
    $result['masters'][] = $row['client_name'];
    $result['type'][] = 'Sales';
    $result['reference'][] = "<a href='../assets/custom/sales_print.php?id=".$row['si_no']."&type=print' target='_blank'>".$row['si_no']."</a>";
    $result['qty'][] = $qty;
    $result['rate'][] = number_format((float)$amount, 2);
}
}

//Credit Note
$sql = "SELECT * FROM credit_note WHERE `items` LIKE '%$pr_search%' AND (`cn_no` LIKE '%$search%' OR `client` LIKE '%$search%') AND `cn_date` BETWEEN '$dt_start' AND '$dt_end' ";
$query = $db->query($sql);
if ($query) {
while($row = $query->fetch_assoc()){

    $qty = '0';
    $rate = '';
    $amount = '';

    $items = json_decode($row['items'] ?? '', true);
    $len = (is_array($items) && isset($items['product']) && is_array($items['product'])) ? sizeof($items['product']) : 0;

    for($i=0;$i<$len;$i++){
        if(($items['product'][$i] ?? '') == $pr){
            $qty += $items['quantity'][$i] ?? 0;
            $rate = $items['price'][$i] ?? '';
            $amount = ($items['price'][$i] ?? 0) * ($items['quantity'][$i] ?? 0);
            if(($items['discount'][$i] ?? 0) > 0){
                $amount = $amount * (100 - ($items['discount'][$i] ?? 0)) / 100;
            }
        }
    }

    $result['date'][] = !empty($row['cn_date']) ? date('d-m-Y', strtotime($row['cn_date'])) : '';
    $result['masters'][] = $row['client'];
    $result['type'][] = 'Credit Note';
    $result['reference'][] = "<a href='../assets/custom/credit_note_print.php?id=".$row['cn_no']."&type=print' target='_blank'>".$row['cn_no']."</a>";
    $result['qty'][] = $qty;
    $result['rate'][] = number_format((float)$amount, 2);
}
}

//Debit Note
$sql = "SELECT * FROM debit_note WHERE `items` LIKE '%$pr_search%' AND (`dn_no` LIKE '%$search%' OR `supplier` LIKE '%$search%') AND `dn_date` BETWEEN '$dt_start' AND '$dt_end' ";
$query = $db->query($sql);
if ($query) {
while($row = $query->fetch_assoc()){

    $qty = '0';
    $rate = '';
    $amount = '';

    $items = json_decode($row['items'] ?? '', true);
    $len = (is_array($items) && isset($items['product']) && is_array($items['product'])) ? sizeof($items['product']) : 0;

    for($i=0;$i<$len;$i++){
        if(($items['product'][$i] ?? '') == $pr){
            $qty += $items['quantity'][$i] ?? 0;
            $rate = $items['price'][$i] ?? '';
            $amount = ($items['price'][$i] ?? 0) * ($items['quantity'][$i] ?? 0);
            if(($items['discount'][$i] ?? 0) > 0){
                $amount = $amount * (100 - ($items['discount'][$i] ?? 0)) / 100;
            }
        }
    }

    $result['date'][] = !empty($row['dn_date']) ? date('d-m-Y', strtotime($row['dn_date'])) : '';
    $result['masters'][] = $row['supplier'];
    $result['type'][] = 'Debit Note';
    $result['reference'][] = "<a href='../assets/custom/debit_note_print.php?id=".$row['dn_no']."&type=print' target='_blank'>".$row['dn_no']."</a>";
    $result['qty'][] = $qty;
    $result['rate'][] = number_format((float)$amount, 2);
}
}


//Quotations
$sql = "SELECT * FROM quotation WHERE `items` LIKE '%$pr_search%' AND (`quotation_no` LIKE '%$search%' OR `client` LIKE '%$search%') AND `quotation_date` BETWEEN '$dt_start' AND '$dt_end'";
$query = $db->query($sql);
if ($query) {
while($row = $query->fetch_assoc()){

    $qty = '0';
    $rate = '';
    $amount = '';

    $items = json_decode($row['items'] ?? '', true);
    $len = (is_array($items) && isset($items['product']) && is_array($items['product'])) ? sizeof($items['product']) : 0;

    for($i=0;$i<$len;$i++){
        if(($items['product'][$i] ?? '') == $pr){
            $qty += $items['quantity'][$i] ?? 0;
            $rate = $items['price'][$i] ?? '';
            $amount = ($items['price'][$i] ?? 0) * ($items['quantity'][$i] ?? 0);
            if(($items['discount'][$i] ?? 0) > 0){
                $amount = $amount * (100 - ($items['discount'][$i] ?? 0)) / 100;
            }
        }
    }

    $result['date'][] = !empty($row['quotation_date']) ? date('d-m-Y', strtotime($row['quotation_date'])) : '';
    $result['masters'][] = $row['client'];
    $result['type'][] = 'Quotation';
    $result['reference'][] = "<a href='../assets/custom/quotation_print.php?id=".$row['quotation_no']."&type=print' target='_blank'>".$row['quotation_no']."</a>";
    $result['qty'][] = $qty;
    $result['rate'][] = number_format((float)$amount, 2);
}
}

//Purchase Order
$sql = "SELECT * FROM purchase_order WHERE `items` LIKE '%$pr_search%' AND (`po_no` LIKE '%$search%' OR `supplier_name` LIKE '%$search%') AND `po_date` BETWEEN '$dt_start' AND '$dt_end'";
$query = $db->query($sql);
if ($query) {
while($row = $query->fetch_assoc()){

    $qty = '';
    $rate = '';
    $amount = '';

    $items = json_decode($row['items'] ?? '', true);
    $len = (is_array($items) && isset($items['product']) && is_array($items['product'])) ? sizeof($items['product']) : 0;

    for($i=0;$i<$len;$i++){
        if(($items['product'][$i] ?? '') == $pr){
            $qty = $items['quantity'][$i] ?? '';
            $rate = $items['price'][$i] ?? '';
            $amount = ($items['price'][$i] ?? 0) * ($items['quantity'][$i] ?? 0);
            if(($items['discount'][$i] ?? 0) > 0){
                $amount = $amount * (100 - ($items['discount'][$i] ?? 0)) / 100;
            }
        }
    }

    $result['date'][] = !empty($row['po_date']) ? date('d-m-Y', strtotime($row['po_date'])) : '';
    $result['masters'][] = $row['supplier_name'];
    $result['type'][] = 'Purchase Order';
    $result['reference'][] = "<a href='../assets/custom/purchase_order_print.php?id=".$row['po_no']."&type=print' target='_blank'>".$row['po_no']."</a>";
    $result['qty'][] = $qty;
    $result['rate'][] = number_format((float)$amount, 2);
}
}

//Sales Order
$sql = "SELECT * FROM sales_order WHERE `items` LIKE '%$pr_search%' AND (`so_no` LIKE '%$search%' OR `client_name` LIKE '%$search%') AND `so_date` BETWEEN '$dt_start' AND '$dt_end'";
$query = $db->query($sql);
if ($query) {
while($row = $query->fetch_assoc()){

    $qty = '';
    $rate = '';
    $amount = '';

    $items = json_decode($row['items'] ?? '', true);
    $len = (is_array($items) && isset($items['product']) && is_array($items['product'])) ? sizeof($items['product']) : 0;

    for($i=0;$i<$len;$i++){
        if(($items['product'][$i] ?? '') == $pr){
            $qty = $items['quantity'][$i] ?? '';
            $rate = $items['price'][$i] ?? '';
            $amount = ($items['price'][$i] ?? 0) * ($items['quantity'][$i] ?? 0);
            if(($items['discount'][$i] ?? 0) > 0){
                $amount = $amount * (100 - ($items['discount'][$i] ?? 0)) / 100;
            }
        }
    }

    $result['date'][] = !empty($row['so_date']) ? date('d-m-Y', strtotime($row['so_date'])) : '';
    $result['masters'][] = $row['client_name'];
    $result['type'][] = 'Sales Order';
    $result['reference'][] = "<a href='../assets/custom/sales_order_print.php?id=".$row['so_no']."&type=print' target='_blank'>".$row['so_no']."</a>";
    $result['qty'][] = $qty;
    $result['rate'][] = number_format((float)$amount, 2);
}
}

//Assembly Operations
$sql = "SELECT * FROM assembly_operation WHERE `composite` = '$pr' AND `log_date` BETWEEN '$dt_start' AND '$dt_end'";
$query = $db->query($sql);
if ($query) {
while($row = $query->fetch_assoc()){

    $result['date'][] = !empty($row['log_date']) ? date('d-m-Y', strtotime($row['log_date'])) : '';
    $result['masters'][] = "<a href='../assets/custom/sales_print.php?id=".$row['invoice']."&type=print' target='_blank'>".$row['invoice']."</a>";
    $result['type'][] = $row['operation'];
    $result['reference'][] = "<a href='../assets/custom/assembly_print.php?id=".$row['id']."&type=print' target='_blank'>Print</a>";
    $result['qty'][] = $row['quantity'];
    $result['rate'][] = '';
}
}

//Assembled or disassembled items
$sql = "SELECT * FROM assembly_operation WHERE `items` LIKE '%$pr_search%' AND `log_date` BETWEEN '$dt_start' AND '$dt_end'";
$query = $db->query($sql);
if ($query) {
while($row = $query->fetch_assoc()){

    $qty = '';

    $items = json_decode($row['items'] ?? '', true);
    $len = (is_array($items) && isset($items['product']) && is_array($items['product'])) ? sizeof($items['product']) : 0;

    for($i=0;$i<$len;$i++){
        if(($items['product'][$i] ?? '') == $pr){
            $qty = $items['quantity'][$i] ?? '';
        }
    }

    $qty = ($qty !== '' ? $qty : 0) * ($row['quantity'] ?? 0);

    $result['date'][] = !empty($row['log_date']) ? date('d-m-Y', strtotime($row['log_date'])) : '';
    $result['masters'][] = "<a href='../assets/custom/sales_print.php?id=".$row['invoice']."&type=print' target='_blank'>".$row['invoice']."</a>";
    if($row['operation']=="Assembled")
    {
        $result['type'][] = 'Assembled to '.$row['composite'];
    }
    else{
        $result['type'][] = 'Disassembled from '.$row['composite'];
    }
    $result['reference'][] = "<a href='../assets/custom/assembly_print.php?id=".$row['id']."&type=print' target='_blank'>Print</a>";
    $result['qty'][] = $qty;
    $result['rate'][] = '';
}
}

//Materials received
$sql = "SELECT * FROM materials_received WHERE `items` LIKE '%$pr_search%' AND `log_date` BETWEEN '$dt_start' AND '$dt_end'";
$query = $db->query($sql);
if ($query) {
while($row = $query->fetch_assoc()){

    $qty = '';

    $items = json_decode($row['items'] ?? '', true);
    $len = (is_array($items) && isset($items['product']) && is_array($items['product'])) ? sizeof($items['product']) : 0;

    for($i=0;$i<$len;$i++){
        if(($items['product'][$i] ?? '') == $pr){
            $qty = $items['quantity'][$i] ?? '';
        }
    }

    $result['date'][] = !empty($row['date']) ? date('d-m-Y', strtotime($row['date'])) : '';
    $result['masters'][] = $row['supplier_name'];
    $result['type'][] = 'Material Received';
    $result['reference'][] = '';
    $result['qty'][] = $qty;
    $result['rate'][] = '';
}
}

$len = sizeof($result['date']);

for($m=0;$m<$len-1;$m++){
    for($n=$m+1;$n<$len;$n++){
        if(strtotime($result['date'][$m]) < strtotime($result['date'][$n])){
            $temp = $result['date'][$m];
            $result['date'][$m] = $result['date'][$n];
            $result['date'][$n] = $temp;

            $temp = $result['masters'][$m];
            $result['masters'][$m] = $result['masters'][$n];
            $result['masters'][$n] = $temp;

            $temp = $result['type'][$m];
            $result['type'][$m] = $result['type'][$n];
            $result['type'][$n] = $temp;

            $temp = $result['reference'][$m];
            $result['reference'][$m] = $result['reference'][$n];
            $result['reference'][$n] = $temp;

            $temp = $result['qty'][$m];
            $result['qty'][$m] = $result['qty'][$n];
            $result['qty'][$n] = $temp;

            $temp = $result['rate'][$m];
            $result['rate'][$m] = $result['rate'][$n];
            $result['rate'][$n] = $temp;
        }
    }
}

if($len == 0)
{
    $output['data'][] = array(  
            'SN' => '',    
            'Date' => '',
            'Masters' => '',
            'Type' => '',
            'Reference' => '',
            'Qty' => '',
            'Rate' => '',
        );

}



for($i=0;$i<$len;$i++){

    $a = $result['masters'][$i];

    // if (contains($query, $a)) {
        $output['data'][] = array(  
            'SN' => $count++,    
            'Date' => $result['date'][$i],
            'Masters' => $result['masters'][$i],
            'Type' => $result['type'][$i] ,
            'Reference' => $result['reference'][$i] ,
            'Qty' => $result['qty'][$i],
            'Rate' => $result['rate'][$i],
        );
    // }
}

echo json_encode($output);

// returns true if $needle is a substring of $haystack
function contains($needle, $haystack)
{
    return strpos($haystack, $needle) !== false;
}

?>
