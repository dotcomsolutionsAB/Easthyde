<?php
session_start();
require_once "../connect.php";
setlocale(LC_MONETARY, 'en_IN');

$start_date = $_SESSION['start'];
$end_date = $_SESSION['end'];

$pagination = $_REQUEST['pagination'];  
$query_array = $_REQUEST['query'];  
$sort_array = $_REQUEST['sort'];  

$query = $query_array['generalSearch'];

$sql_1 = "SELECT COUNT(*) AS total FROM sales_invoice WHERE (`client_name` LIKE '%$query%' OR `si_no` LIKE '%$query%') AND `si_date` BETWEEN '$start_date' AND '$end_date' AND `series` != 'SECONDARY' ORDER BY `id` DESC";
$query_1 = $db->query($sql_1);
$row_1 = $query_1->fetch_assoc();

$perpage = $pagination['perpage'];
$start = ($pagination['page']-1)*$perpage;
$pages = $row_1['total'] / $perpage;

$output = array('meta'=> array("page"=> $pagination['page'], "pages"=> $pages, "perpage"=> $perpage,"total"=> $row_1['total'],"sort"=> 'asc', "field"=> 'SN'), 'data' => array());

$count=1;
$sql = "SELECT * FROM sales_invoice WHERE (`client_name` LIKE '%$query%' OR `si_no` LIKE '%$query%') AND `si_date` BETWEEN '$start_date' AND '$end_date' AND `series` != 'SECONDARY' ORDER BY `id` DESC LIMIT ".$start.','.$perpage;
$query = $db->query($sql);
while($row = $query->fetch_assoc()){

    $actionBtn = '<div class="dropdown"><a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown"><i class="flaticon-more-1"></i></a><div class="dropdown-menu dropdown-menu-right">
        <ul class="kt-nav">
            <li class="kt-nav__item"><a href="/assets/custom/sales_print.php?id='.$row['si_no'].'&type=ledger" target="_blank" class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-printer"></i><span class="kt-nav__link-text">Print</span></a></li>
        </ul>
        </div></div>';
    
    $tax_details = json_decode($row['tax'], true);

    $total = $row['total'];
    $tax = $tax_details['cgst'] + $tax_details['sgst'] + $tax_details['igst'];

    $c_name = $row['client_name'];

    $sql_temp = "SELECT * FROM clients WHERE name = '$c_name'";
    $query_temp = $db->query($sql_temp);
    $row_temp = $query_temp->fetch_assoc();

    $output['data'][] = array(      
            'RecordID' => $count++,
            'Name' => $row['client_name'],
            'Date' => date('d-m-Y',strtotime($row['si_date'])),
            'Number'=>$row['si_no'],
            'Product'=>$item_details['product'][0],
            'Quantity'=>$item_details['quantity'][0],
            'Price'=>$item_details['price'][0],
            'Discount'=>$item_details['discount'][0],
            'Tax'=>$tax,
            'Description'=>$item_details['desc'][0],
            'Status'=>$row['status'],
            'KT_Class'=>$row_temp['kt-class'],
            'Amount'=>money_format('%!i', $total),
            'User'=>$row['log_user'],
            'Log_Date'=>date('d-m-Y',strtotime($row['log_date'])),
            'Actions' => $actionBtn
    );
}
echo json_encode($output);
?>