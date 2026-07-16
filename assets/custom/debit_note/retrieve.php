<?php
session_start();
require_once "../connect.php";
setlocale(LC_MONETARY, 'en_IN');

$dt_start = $_SESSION['start'];
$dt_end = $_SESSION['end'];

$pagination = $_REQUEST['pagination'];  
$query_array = $_REQUEST['query'];  
$sort_array = $_REQUEST['sort'];  

$sql_fetch = "SELECT * FROM debit_note ORDER BY id DESC LIMIT 1";
$query_fetch = $db->query($sql_fetch);
$row_fetch = $query_fetch->fetch_assoc();

$query = $query_array['generalSearch'];
$query=str_replace(" ","",$query);
$query=str_replace("-","",$query);
$query=str_replace(".","",$query);

$status = $query_array['status'];
$user = $query_array['user'];
$product = $query_array['product'];

if($status=="")
{
    $status='%';
}

if($user=="")
{
    $user='%';
}

if($product=="")
{
    $product='%';
}

$sql_1 = "SELECT COUNT(*) AS total FROM debit_note WHERE (REPLACE(REPLACE(`dn_no`, ' ', ''), '-', '') LIKE '%$query%' || REPLACE(REPLACE(REPLACE(`supplier`, '-', ''), ' ', ''), '.', '') LIKE '%$query%' || `total` LIKE '%$query%') AND `status` LIKE '$status' AND `log_user` LIKE '$user' AND `items` LIKE '%$product%' AND `dn_date` BETWEEN '$dt_start' AND '$dt_end' ORDER BY `id` DESC";
$query_1 = $db->query($sql_1);
$row_1 = $query_1->fetch_assoc();

$perpage = $pagination['perpage'];
$start = ($pagination['page']-1)*$perpage;
$pages = $row_1['total'] / $perpage;

$output = array('meta'=> array("page"=> $pagination['page'], "pages"=> $pages, "perpage"=> $perpage,"total"=> $row_1['total'],"sort"=> 'asc', "field"=> 'SN'), 'data' => array());

$count=1;
$sql = "SELECT * FROM debit_note WHERE (REPLACE(REPLACE(`dn_no`, ' ', ''), '-', '') LIKE '%$query%' || REPLACE(REPLACE(REPLACE(`supplier`, '-', ''), ' ', ''), '.', '') LIKE '%$query%' || `total` LIKE '%$query%') AND `status` LIKE '$status' AND `log_user` LIKE '$user' AND `items` LIKE '%$product%' AND `dn_date` BETWEEN '$dt_start' AND '$dt_end' ORDER BY `id` DESC LIMIT ".$start.','.$perpage;
$query = $db->query($sql);
while($row = $query->fetch_assoc()){
    
    $actionBtn = '<div class="dropdown"><a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown"><i class="flaticon-more-1"></i></a><div class="dropdown-menu dropdown-menu-right">
    <ul class="kt-nav">
        <li class="kt-nav__item"><a target="_blank" href="/assets/custom/debit_note_print.php?id='.$row['dn_no'].'&type=print" class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-printer"></i><span class="kt-nav__link-text">Print</span></a></li>
        <li class="kt-nav__item"><a href="javascript:;" onclick="editDebitNote(\''.$row['id'].'\')" title="Edit" class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-contract"></i><span class="kt-nav__link-text">Edit</span></a></li>
        <li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#delete_debit_note" title="Delete" onclick="removeDebitNote(\''.$row['id'].'\')"class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-trash"></i><span class="kt-nav__link-text">Delete</span></a></li>
    </ul>
    </div></div>';
       
    
    $total = $row['total'];

    $c_name = $row['supplier'];

    $sql_temp = "SELECT * FROM suppliers WHERE name = '$c_name'";
    $query_temp = $db->query($sql_temp);
    $row_temp = $query_temp->fetch_assoc();

    $output['data'][] = array(      
        'RecordID' => $count++,
        'RecordID2' => $row['id'],
        'Name' => $row['supplier'],
        'Supplier_ID' => $row_temp['id'],
        'Date' => date('d-m-Y',strtotime($row['dn_date'])),
        'Number'=>$row['dn_no'],
        'Purchase_No'=>$row['purchase_invoice'],
        'Amount'=>money_format('%!i', $total),
        'User'=>$row['log_user'],
        'Log_Date'=>date('d-m-Y',strtotime($row['log_date'])),
        'Actions' => $actionBtn
    );
}
echo json_encode($output);
?>