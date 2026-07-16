<?php
session_start();
require_once "../connect.php";
setlocale(LC_MONETARY, 'en_IN');


$pagination = $_REQUEST['pagination'] ?? [];
$query_array = $_REQUEST['query'] ?? [];
$sort_array = $_REQUEST['sort'] ?? [];

$query = $query_array['generalSearch'] ?? '';

$status = $query_array['status'] ?? '';
if($status=="")
{
    $status='%';
}

$sql_1 = "SELECT COUNT(*) AS total FROM sales_invoice WHERE `client_name` LIKE '%$query%' AND `series` = 'SECONDARY' AND `status` LIKE '$status' ORDER BY `id` DESC";
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
$sql = "SELECT * FROM sales_invoice WHERE (`client_name` LIKE '%$query%'|| `mobile` LIKE '%$query%') AND `series` = 'SECONDARY'  AND `status` LIKE '$status' ORDER BY `id` DESC LIMIT ".$start.','.$perpage;
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

    if(($menu_access['secondary_sales']['edit'] ?? '') == '1' || $userlevel == "sadmin_df56fdg"){

            $edit = '<li class="kt-nav__item"><a href="javascript:;" onclick="editSalesInvoice(\''.$row['id'].'\')" title="Edit Sales Order"class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-contract"></i><span class="kt-nav__link-text">Edit</span></a></li>';
    }
    
    if(($menu_access['secondary_sales']['delete'] ?? '') == '1' || $userlevel == "sadmin_df56fdg"){

            $delete = '<li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#delete_sales_invoice" title="Delete" onclick="removeSalesInvoice(\''.$row['id'].'\')"class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-trash"></i><span class="kt-nav__link-text">Delete</span></a></li>';
    }

    if(($_SESSION['userlevel'] ?? '') == 'sadmin_df56fdg'){
        $actionBtn = '<div class="dropdown"><a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown"><i class="flaticon-more-1"></i></a><div class="dropdown-menu dropdown-menu-right">
        <ul class="kt-nav">
            <li class="kt-nav__item"><a href="/assets/custom/sales_secondary_print.php?id='.$row['si_no'].'&type=print" target="_blank" title="Print Sales Invoice"class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-printer"></i><span class="kt-nav__link-text">Print</span></a></li>
            <li class="kt-nav__item"><a href="/assets/custom/sales_print.php?id='.$row['si_no'].'&type=download" target="_blank" class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-download"></i><span class="kt-nav__link-text">Download</span></a></li>
            <li class="kt-nav__item"><a href="javascript:;" onclick="editSalesInvoice(\''.$row['id'].'\')" title="Edit Sales Order"class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-contract"></i><span class="kt-nav__link-text">Edit</span></a></li>
            <li class="kt-nav__item"><a href="javascript:;" onclick="makePrimaryInvoice(\''.$row['id'].'\')" title="Make Primary Invoice"class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-contract"></i><span class="kt-nav__link-text">Convert to Primary</span></a></li>

            <li class="kt-nav__item"><a href="/assets/custom/sales_secondary_print_a4.php?id='.$row['si_no'].'&type=print" target="_blank" title="Print Sales Invoice"class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-print"></i><span class="kt-nav__link-text">Print in A4</span></a></li>
            <li class="kt-nav__item"><a href="javascript:;" onclick="pay(\''.$row['id'].'\')" title="Pay"class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-shopping-cart-1"></i><span class="kt-nav__link-text">Recieve</span></a></li>';
            if($count == 1){
                $actionBtn .= '<li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#delete_sales_invoice" title="Delete" onclick="removeSalesInvoice(\''.$row['id'].'\')"class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-trash"></i><span class="kt-nav__link-text">Delete</span></a></li>';
            }else{
                $actionBtn .= '<li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#cancel_sales_invoice" title="Cancel" onclick="cancelSalesInvoice(\''.$row['id'].'\')"class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-trash"></i><span class="kt-nav__link-text">Cancel</span></a></li>';
            }
        $actionBtn .= '</ul>
        </div></div>';
    }else{
        $actionBtn = '<div class="dropdown"><a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown"><i class="flaticon-more-1"></i></a><div class="dropdown-menu dropdown-menu-right">
        <ul class="kt-nav">
            <li class="kt-nav__item"><a href="/assets/custom/sales_secondary_print.php?id='.$row['si_no'].'&type=print" target="_blank" title="Print Sales Invoice"class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-printer"></i><span class="kt-nav__link-text">Print</span></a></li>
            <li class="kt-nav__item"><a href="/assets/custom/sales_print.php?id='.$row['si_no'].'&type=download" target="_blank" class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-download"></i><span class="kt-nav__link-text">Download</span></a></li>
            '.$edit.'
        </ul>
        </div></div>';
    }

    $total = $row['total'];

    $c_name = $row['client_name'];

    $sql_temp = "SELECT * FROM clients WHERE name = '$c_name'";
    $query_temp = $db->query($sql_temp);
    $row_temp = ($query_temp && ($tmp = $query_temp->fetch_assoc())) ? $tmp : [];

    if((string)($row['mobile'] ?? '') !== '' && (string)$row['mobile'] !== '0'){
        $tmp = $row['client_name']."<br>Mob: ".$row['mobile'];
         }
         else{
             $tmp = $row['client_name'];
         }

    $item_details = json_decode($row['items'] ?? '', true);
    if (!is_array($item_details)) {
        $item_details = [];
    }

	$output['data'][] = array(		
        	'RecordID' => $count++,
            'RecordID2' => $row['id'],
            'Name' => $tmp,
            'Date' => !empty($row['si_date']) ? date('d-m-Y',strtotime($row['si_date'])) : '',
            'Number'=>$row['si_no'],
            'Product'=>$item_details['product'][0] ?? '',
            'Quantity'=>$item_details['quantity'][0] ?? '',
            'Price'=>$item_details['price'][0] ?? '',
            'Discount'=>$item_details['discount'][0] ?? '',
            'Tax'=>$item_details['tax'][0] ?? '',
            'Description'=>$item_details['desc'][0] ?? '',
            'Status'=>$row['status'],
            'KT_Class'=>$row_temp['kt-class'] ?? '',
            'Amount'=>number_format((float)$total, 2),
           'Notes' => isset($row['notes']) && is_string($row['notes']) ? $row['notes'] : '',

            'User'=>$row['log_user'],
            'Log_Date'=>!empty($row['log_date']) ? date('d-m-Y',strtotime($row['log_date'])) : '',
            'Cancelled'=>$row['cancelled'],
            'Actions' => $actionBtn
	);
}
}
echo json_encode($output);
?>
