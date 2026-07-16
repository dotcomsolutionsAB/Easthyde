<?php
// ini_set('display_errors', 1);
session_start();
require_once "../connect.php";

setlocale(LC_MONETARY, 'en_IN');

$dt_start = $_SESSION['start'] ?? '';
$dt_end = $_SESSION['end'] ?? '';

$pagination = $_REQUEST['pagination'] ?? [];
$query_array = $_REQUEST['query'] ?? [];
$sort_array = $_REQUEST['sort'] ?? [];

$query = $query_array['generalSearch'] ?? '';
$query=str_replace(" ","",$query);
$query=str_replace("-","",$query);
$query=str_replace(".","",$query);

$status = $query_array['status'] ?? '';
$user = $query_array['user'] ?? '';
$product = $query_array['product'] ?? '';

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

$sql_1 = "SELECT COUNT(*) AS total FROM purchase_invoice WHERE (REPLACE(REPLACE(`pi_no`, ' ', ''), '-', '') LIKE '%$query%' || REPLACE(REPLACE(REPLACE(`supplier_name`, '-', ''), ' ', ''), '.', '') LIKE '%$query%' || `total` LIKE '%$query%')  AND `status` LIKE '$status' AND `pi_date` BETWEEN '$dt_start' AND '$dt_end' AND `log_user` LIKE '$user' AND `items` LIKE '%$product%' AND `series` = 'PRIMARY' ORDER BY `pi_date` DESC";
$query_1 = $db->query($sql_1);
$row_1 = ($query_1 && ($tmp = $query_1->fetch_assoc())) ? $tmp : ['total' => 0];

$perpage = (int)($pagination['perpage'] ?? 10);
$page = (int)($pagination['page'] ?? 1);
if ($perpage < 1) { $perpage = 10; }
if ($page < 1) { $page = 1; }
$start = ($page - 1) * $perpage;
$pages = $row_1['total'] / $perpage;

$output = array('meta'=> array("page"=> $page, "pages"=> $pages, "perpage"=> $perpage,"total"=> $row_1['total'],"sort"=> 'asc', "field"=> 'SN'), 'data' => array());

$count=1;
$sql = "SELECT * FROM purchase_invoice WHERE (REPLACE(REPLACE(`pi_no`, ' ', ''), '-', '') LIKE '%$query%' || REPLACE(REPLACE(REPLACE(`supplier_name`, '-', ''), ' ', ''), '.', '') LIKE '%$query%' || `total` LIKE '%$query%') AND `status` LIKE '$status' AND `pi_date` BETWEEN '$dt_start' AND '$dt_end' AND `log_user` LIKE '$user' AND `items` LIKE '%$product%' AND `series` = 'PRIMARY' ORDER BY `pi_date` DESC LIMIT ".$start.','.$perpage;

$query = $db->query($sql);
if ($query) {
while($row = $query->fetch_assoc()){
    
    $username = $_SESSION['username'] ?? '';
    $userlevel = $_SESSION['userlevel'] ?? '';

    $sql_access = "SELECT * FROM users WHERE `username` = '$username'";
    $query_access = $db->query($sql_access);
    $row_access = ($query_access && ($tmp = $query_access->fetch_assoc())) ? $tmp : [];

    $menu_access = json_decode($row_access['access'] ?? '', true);
    if (!is_array($menu_access)) {
        $menu_access = [];
    }
    
    $edit = '';
    $delete = '';

    if(($menu_access['purchase_invoice']['edit'] ?? '') == '1' || $userlevel == "sadmin_df56fdg"){

            $edit = '<li class="kt-nav__item"><a href="javascript:;" onclick="editPurchaseInvoice(\''.$row['id'].'\')" title="Edit Purchase Order"class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-contract"></i><span class="kt-nav__link-text">Edit</span></a></li>';
    }
    
    if(($menu_access['purchase_invoice']['delete'] ?? '') == '1' || $userlevel == "sadmin_df56fdg"){

            $delete = '<li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#delete_purchase_invoice" title="Delete" onclick="removePurchaseInvoice(\''.$row['id'].'\')"class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-trash"></i><span class="kt-nav__link-text">Delete</span></a></li>';
    }


    if((string)($row['status'] ?? '') === '0')
    {
        $option='<li class="kt-nav__item"><a href="javascript:;" onclick="setStatus(\''.$row['pi_no'].'\', \'1\', \'purchase_invoice\')" title="Completed"class="kt-nav__link"><i class="kt-nav__link-icon flaticon-like"></i><span class="kt-nav__link-text">Completed</span></a>
        </li>
        ';
    }
    else 
    {
        $option='<li class="kt-nav__item"><a href="javascript:;" onclick="setStatus(\''.$row['pi_no'].'\', \'0\', \'purchase_invoice\')" title="Pending"class="kt-nav__link"><i class="kt-nav__link-icon flaticon-like"></i><span class="kt-nav__link-text">Pending</span></a>
        </li>';
    }

    if(($_SESSION['userlevel'] ?? '') == 'sadmin_df56fdg'){
        $actionBtn = '<div class="dropdown"><a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown"><i class="flaticon-more-1"></i></a><div class="dropdown-menu dropdown-menu-right">
        <ul class="kt-nav">
            <li class="kt-nav__item"><a href="/assets/custom/purchase_invoice_print.php?id='.$row['id'].'&type=print" target="_blank" class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-printer"></i><span class="kt-nav__link-text">Print</span></a></li>
            <li class="kt-nav__item"><a href="/assets/custom/purchase_invoice_print.php?id='.$row['id'].'&type=download" target="_blank" class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-download"></i><span class="kt-nav__link-text">Download</span></a></li>
            <li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#kt_modal_purchase_invoice_whatsapp" onclick="Wa_purchase_invoice(\''.$row['id'].'\')" title="Whatsapp Invoice"class="kt-nav__link"><i class="kt-nav__link-icon flaticon-whatsapp"></i><span class="kt-nav__link-text">Whatsapp Invoice</span></a></li>
            '.$option.'
            <li class="kt-nav__item"><a href="javascript:;" onclick="editPurchaseInvoice(\''.$row['id'].'\')" title="Edit Purchase Order"class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-contract"></i><span class="kt-nav__link-text">Edit</span></a></li>
            <li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#delete_purchase_invoice" title="Delete" onclick="removePurchaseInvoice(\''.$row['id'].'\')"class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-trash"></i><span class="kt-nav__link-text">Delete</span></a></li>
             <li class="kt-nav__item"><a href="javascript:;" onclick="paymentt(\''.$row['id'].'\')" title="Pay"class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-rocket"></i><span class="kt-nav__link-text">Pay</span></a></li>
            </ul>
        </div></div>';

    }else{
        $actionBtn = '<div class="dropdown"><a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown"><i class="flaticon-more-1"></i></a><div class="dropdown-menu dropdown-menu-right">
        <ul class="kt-nav">
            <li class="kt-nav__item"><a href="/assets/custom/purchase_invoice_print.php?id='.$row['id'].'&type=print" target="_blank" class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-printer"></i><span class="kt-nav__link-text">Print</span></a></li>
            <li class="kt-nav__item"><a href="/assets/custom/purchase_invoice_print.php?id='.$row['id'].'&type=download" target="_blank" class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-download"></i><span class="kt-nav__link-text">Download</span></a></li>
            <li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#kt_modal_purchase_invoice_whatsapp" onclick="Wa_purchase_invoice(\''.$row['id'].'\')" title="Whatsapp Invoice"class="kt-nav__link"><i class="kt-nav__link-icon flaticon-whatsapp"></i><span class="kt-nav__link-text">Whatsapp Invoice</span></a></li>
            '.$option.$edit.'
            
        </ul>
        </div></div>';
    }

    $total = $row['total'];

    $s_name = $row['supplier_name'];

    $sql_temp = "SELECT * FROM suppliers WHERE name = '$s_name'";
    $query_temp = $db->query($sql_temp);
    $row_temp = ($query_temp && ($tmp = $query_temp->fetch_assoc())) ? $tmp : [];

    $item_details = json_decode($row['items'] ?? '', true);
    if (!is_array($item_details)) {
        $item_details = [];
    }

	$output['data'][] = array(		
            'RecordID' => $count++,
        	'RecordID2' => $row['id'],
            'Name' => $row['supplier_name'],
            'Supplier_ID' => $row_temp['id'] ?? '',
            'Date' => !empty($row['pi_date']) ? date('d-m-Y',strtotime($row['pi_date'])) : '',
            'Number'=>$row['pi_no'],
            'ID'=>$row['id'],
            'Product'=>$item_details['product'][0] ?? '',
            'Quantity'=>$item_details['quantity'][0] ?? '',
            'Price'=>$item_details['price'][0] ?? '',
            'Discount'=>$item_details['discount'][0] ?? '',
            'Tax'=>$item_details['tax'][0] ?? '',
            'Description'=>$item_details['desc'][0] ?? '',
            'Status'=>$row['status'],
            'KT_Class'=>$row_temp['kt-class'] ?? '',
            'Amount'=>number_format((float)$total, 2),
            'User'=>$row['log_user'],
            'Log_Date'=>!empty($row['log_date']) ? date('d-m-Y',strtotime($row['log_date'])) : '',
            'Actions' => $actionBtn,
            'sql' => $sql
	);
}
}

echo json_encode($output);
?>
