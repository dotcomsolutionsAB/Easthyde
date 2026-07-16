<?php
session_start();
require_once "../connect.php";

setlocale(LC_MONETARY, 'en_IN');


$pagination = $_REQUEST['pagination'];  
$query_array = $_REQUEST['query'];  
$sort_array = $_REQUEST['sort'];  

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

$sql_fetch = "SELECT * FROM purchase_order ORDER BY id DESC LIMIT 1";
$query_fetch = $db->query($sql_fetch);
$row_fetch = $query_fetch->fetch_assoc();

$sql_1 = "SELECT COUNT(*) AS total FROM purchase_order WHERE (REPLACE(REPLACE(`po_no`, ' ', ''), '-', '') LIKE '%$query%' || REPLACE(REPLACE(REPLACE(`supplier_name`, '-', ''), ' ', ''), '.', '') LIKE '%$query%' || `total` LIKE '%$query%')  AND `status` LIKE '$status' AND `log_user` LIKE '$user' AND `items` LIKE '%$product%' ORDER BY `id` DESC";
$query_1 = $db->query($sql_1);
$row_1 = $query_1->fetch_assoc();

$perpage = $pagination['perpage'];
$start = ($pagination['page']-1)*$perpage;
$pages = $row_1['total'] / $perpage;

$output = array('meta'=> array("page"=> $pagination['page'], "pages"=> $pages, "perpage"=> $perpage,"total"=> $row_1['total'],"sort"=> 'asc', "field"=> 'SN'), 'data' => array());

$count=1;
$sql = "SELECT * FROM purchase_order WHERE (REPLACE(REPLACE(`po_no`, ' ', ''), '-', '') LIKE '%$query%' || REPLACE(REPLACE(REPLACE(`supplier_name`, '-', ''), ' ', ''), '.', '') LIKE '%$query%' || `total` LIKE '%$query%')  AND `status` LIKE '$status' AND `log_user` LIKE '$user' AND `items` LIKE '%$product%' ORDER BY `id` DESC LIMIT ".$start.','.$perpage;
$query = $db->query($sql);
while($row = $query->fetch_assoc()){
    
    $username = $_SESSION['username'];
    $userlevel = $_SESSION['userlevel'];

    $sql_access = "SELECT * FROM users WHERE `username` = '$username'";
    $query_access = $db->query($sql_access);
    $row_access = $query_access->fetch_assoc();

    $menu_access = json_decode($row_access['access'], true);
    
    $edit = '';
    $delete = '';

    if($menu_access['purchase_order']['edit'] == '1' || $userlevel == "sadmin_df56fdg"){

            $edit = '<li class="kt-nav__item"><a href="javascript:;" onclick="editPurchaseOrder(\''.$row['po_no'].'\')" title="Edit Purchase Order"class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-contract"></i><span class="kt-nav__link-text">Edit</span></a></li>';
    }
    
    if($menu_access['purchase_order']['delete'] == '1' || $userlevel == "sadmin_df56fdg"){

            $delete = '<li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#delete_purchase_order" title="Delete" onclick="removePurchaseOrder(\''.$row['id'].'\')"class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-trash"></i><span class="kt-nav__link-text">Delete</span></a></li>';
    }

    if($row['status']==0)
    {
        $option='<li class="kt-nav__item"><a href="javascript:;" onclick="setStatus(\''.$row['po_no'].'\', \'1\', \'purchase_order\')" title="Completed"class="kt-nav__link"><i class="kt-nav__link-icon flaticon-like"></i><span class="kt-nav__link-text">Completed</span></a>
        </li>
        ';
    }
    else 
    {
        $option='<li class="kt-nav__item"><a href="javascript:;" onclick="setStatus(\''.$row['po_no'].'\', \'0\', \'purchase_order\')" title="Pending"class="kt-nav__link"><i class="kt-nav__link-icon flaticon-like"></i><span class="kt-nav__link-text">Pending</span></a>
        </li>';
    }

    if($_SESSION['userlevel'] == 'sadmin_df56fdg'){
        $actionBtn = '<div class="dropdown"><a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown"><i class="flaticon-more-1"></i></a><div class="dropdown-menu dropdown-menu-right">
        <ul class="kt-nav">
            <li class="kt-nav__item"><a target="_blank" href="/assets/custom/purchase_order_print.php?id='.$row['po_no'].'&type=print" class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-printer"></i><span class="kt-nav__link-text">Print</span></a></li>
            <li class="kt-nav__item"><a target="_blank" href="/assets/custom/purchase_order_print.php?id='.$row['po_no'].'&type=download" class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-download"></i><span class="kt-nav__link-text">Download</span></a></li>
            <li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#kt_modal_po_email" onclick="sendPOEmail(\''.$row['po_no'].'\')" title="Send Email"class="kt-nav__link"><i class="kt-nav__link-icon flaticon-email"></i><span class="kt-nav__link-text">Send Email</span></a></li>
            <li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#kt_modal_purchase_order_whatsapp" onclick="Wa_purchase_order(\''.$row['id'].'\')" title="Send Whatsapp"class="kt-nav__link"><i class="kt-nav__link-icon flaticon-whatsapp"></i><span class="kt-nav__link-text">Send Whatsapp</span></a></li>
            '.$option.'
            <li class="kt-nav__item"><a href="javascript:;" onclick="editPurchaseOrder(\''.$row['po_no'].'\')" title="Edit Purchase Order"class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-contract"></i><span class="kt-nav__link-text">Edit</span></a></li>';
            if($row_fetch['id'] == $row['id']){
                $actionBtn .= '<li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#delete_purchase_order" title="Delete" onclick="removePurchaseOrder(\''.$row['id'].'\')"class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-trash"></i><span class="kt-nav__link-text">Delete</span></a></li>';
            }else{
                $actionBtn .= '<li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#cancel_purchase_order" title="Cancel" onclick="cancelPurchaseOrder(\''.$row['id'].'\')"class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-trash"></i><span class="kt-nav__link-text">Cancel</span></a></li>';
            }
        $actionBtn .= '</ul>
        </div></div>';

    }else{
        $actionBtn = '<div class="dropdown"><a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown"><i class="flaticon-more-1"></i></a><div class="dropdown-menu dropdown-menu-right">
        <ul class="kt-nav">
            <li class="kt-nav__item"><a target="_blank" href="/assets/custom/purchase_order_print.php?id='.$row['po_no'].'&type=print" class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-printer"></i><span class="kt-nav__link-text">Print</span></a></li>
            <li class="kt-nav__item"><a target="_blank" href="/assets/custom/purchase_order_print.php?id='.$row['po_no'].'&type=download" class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-download"></i><span class="kt-nav__link-text">Download</span></a></li>
            <li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#kt_modal_po_email" onclick="sendPOEmail(\''.$row['po_no'].'\')" title="Send Email"class="kt-nav__link"><i class="kt-nav__link-icon flaticon-email"></i><span class="kt-nav__link-text">Send Email</span></a></li>
            <li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#kt_modal_purchase_order_whatsapp" onclick="Wa_purchase_order(\''.$row['id'].'\')" title="Send Whatsapp"class="kt-nav__link"><i class="kt-nav__link-icon flaticon-whatsapp"></i><span class="kt-nav__link-text">Send Whatsapp</span></a></li>
            '.$option.$edit.'
            
        </ul>
        </div></div>';
    }
    
    $total = $row['total'];

    $s_name = $row['supplier_name'];

    $sql_temp = "SELECT * FROM suppliers WHERE name = '$s_name'";
    $query_temp = $db->query($sql_temp);
    $row_temp = $query_temp->fetch_assoc();

	$output['data'][] = array(		
        	'RecordID' => $count++,
            'Name' => $row['supplier_name'],
            'Date' => date('d-m-Y',strtotime($row['po_date'])),
            'Number'=>$row['po_no'],
            'Product'=>$item_details['product'][0],
            'Quantity'=>$item_details['quantity'][0],
            'Price'=>$item_details['price'][0],
            'Discount'=>$item_details['discount'][0],
            'Tax'=>$item_details['tax'][0],
            'Description'=>$item_details['desc'][0],
            'Status'=>$row['status'],
            'KT_Class'=>$row_temp['kt-class'],
            'User'=>$row['log_user'],
            'Amount'=>money_format('%!i', $total),
            'Cancelled'=>$row['cancelled'],
            'Log_Date'=>date('d-m-Y',strtotime($row['log_date'])),
            'Actions' => $actionBtn
	);
}
echo json_encode($output);
?>