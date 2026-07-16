<?php
session_start();
require_once "../connect.php";
setlocale(LC_MONETARY, 'en_IN');
//ini_set("display_errors",1);
function checkAndCreateInvoiceFolder($invoice_no) {
    $folder_path = "../../vendor/file-manager/files/sales/" . $invoice_no;
    if (!is_dir($folder_path)) {
        mkdir($folder_path, 0777, true); // Create the directory if it does not exist
    }
    return "https://easthyde.com/assets/vendor/file-manager/projects.php?folder=sales/" . $invoice_no;
}

$dt_start = $_SESSION['start'] ?? '';
$dt_end = $_SESSION['end'] ?? '';

$pagination = $_REQUEST['pagination'] ?? [];
$query_array = $_REQUEST['query'] ?? [];
$sort_array = $_REQUEST['sort'] ?? [];

$sql_fetch = "SELECT * FROM sales_invoice WHERE `series` = 'PRIMARY' ORDER BY id DESC LIMIT 1";
$query_fetch = $db->query($sql_fetch);
$row_fetch = ($query_fetch && ($tmp = $query_fetch->fetch_assoc())) ? $tmp : [];

$query = $query_array['generalSearch'] ?? '';
$query=str_replace(" ","",$query);
$query=str_replace("-","",$query);
$query=str_replace(".","",$query);

$series = $query_array['series'] ?? '';
$status = $query_array['status'] ?? '';
$user = $query_array['user'] ?? '';
$product = $query_array['product'] ?? '';


if($series=="")
{
    $series='%';
}

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

$sql_1 = "SELECT COUNT(*) AS total FROM sales_invoice WHERE (REPLACE(REPLACE(`si_no`, ' ', ''), '-', '') LIKE '%$query%' || REPLACE(REPLACE(REPLACE(`client_name`, '-', ''), ' ', ''), '.', '') LIKE '%$query%' || `total` LIKE '%$query%'|| `mobile` LIKE '%$query%') AND (`series` = 'PRIMARY' OR `series` = 'SECONDARY') AND `series` LIKE '%$series%' AND `status` LIKE '$status' AND `log_user` LIKE '$user' AND `items` LIKE '%$product%' AND `si_date` BETWEEN '$dt_start' AND '$dt_end'";
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
$sql = "SELECT * FROM sales_invoice WHERE (REPLACE(REPLACE(`si_no`, ' ', ''), '-', '') LIKE '%$query%' || REPLACE(REPLACE(REPLACE(`client_name`, '-', ''), ' ', ''), '.', '') LIKE '%$query%' || `total` LIKE '%$query%'|| `mobile` LIKE '%$query%') AND (`series` = 'PRIMARY' OR `series` = 'SECONDARY') AND `series` LIKE '%$series%' AND `status` LIKE '$status' AND `log_user` LIKE '$user' AND `items` LIKE '%$product%' AND `si_date` BETWEEN '$dt_start' AND '$dt_end' ORDER BY `si_date` DESC,`si_no` DESC  LIMIT ".$start.','.$perpage;
$query = $db->query($sql);
if ($query) {
while($row = $query->fetch_assoc()){
    if((string)($row['status'] ?? '') === '0')
    {
        $option='<li class="kt-nav__item"><a href="javascript:;" onclick="setStatus(\''.$row['si_no'].'\', \'1\', \'sales_invoice\')" title="Completed"class="kt-nav__link"><i class="kt-nav__link-icon flaticon-like"></i><span class="kt-nav__link-text">Completed</span></a>
        </li>';
    }
    else 
    {
        $option='<li class="kt-nav__item"><a href="javascript:;" onclick="setStatus(\''.$row['si_no'].'\', \'0\', \'sales_invoice\')" title="Pending"class="kt-nav__link"><i class="kt-nav__link-icon flaticon-like"></i><span class="kt-nav__link-text">Pending</span></a>
        </li>';
    }
    
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

    if(($menu_access['sales_invoice']['edit'] ?? '') == '1' || $userlevel == "sadmin_df56fdg"){

            $edit = '<li class="kt-nav__item"><a href="javascript:;" onclick="editSalesInvoice(\''.$row['id'].'\')" title="Edit Sales Order"class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-contract"></i><span class="kt-nav__link-text">Edit</span></a></li>';
    }
    
    if(($menu_access['sales_invoice']['delete'] ?? '') == '1' || $userlevel == "sadmin_df56fdg"){

            $delete = '<li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#delete_sales_invoice" title="Delete" onclick="removeSalesInvoice(\''.$row['id'].'\')"class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-trash"></i><span class="kt-nav__link-text">Delete</span></a></li>';
    }


    if(($_SESSION['userlevel'] ?? '') == 'sadmin_df56fdg'){
        $viewFilesUrl = checkAndCreateInvoiceFolder($row['id']);
        $actionBtn = '<div class="dropdown"><a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown"><i class="flaticon-more-1"></i></a><div class="dropdown-menu dropdown-menu-right">
        <ul class="kt-nav">
            <li class="kt-nav__item"><a target="_blank" href="/assets/custom/re_calculate_sales.php?id='.$row['si_no'].'" class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-docs"></i><span class="kt-nav__link-text">Re-Calculate</span></a></li>
           <li class="kt-nav__item">
            <a href="' . $viewFilesUrl . '" target="_blank" class="kt-nav__link">
                <i class="kt-nav__link-icon flaticon-folder"></i>
                <span class="kt-nav__link-text">View Files</span>
            </a>
        </li>
            <li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#print_sales_invoice" onclick="printSalesInvoice(\''.$row['si_no'].'\')" title="Print Sales Invoice"class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-printer"></i><span class="kt-nav__link-text">Print</span></a></li>
            <li class="kt-nav__item"><a href="/assets/custom/sales_print.php?id='.$row['si_no'].'&type=print" target="_blank" class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-download"></i><span class="kt-nav__link-text">Download</span></a></li>
            <li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#kt_modal_si_email" onclick="sendSIEmail(\''.$row['si_no'].'\')" title="Send Email"class="kt-nav__link"><i class="kt-nav__link-icon flaticon-email"></i><span class="kt-nav__link-text">Send Email</span></a></li>
            <li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#kt_modal_sales_invoice_whatsapp" onclick="Wa_sales_invoice(\''.$row['id'].'\')" title="Whatsapp Invoice"class="kt-nav__link"><i class="kt-nav__link-icon flaticon-whatsapp"></i><span class="kt-nav__link-text">Whatsapp Invoice</span></a></li>
            <li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#kt_modal_whatsapp" onclick="Wa_despatch_details(\''.$row['id'].'\')" title="Whatsapp Despatch"class="kt-nav__link"><i class="kt-nav__link-icon flaticon-whatsapp"></i><span class="kt-nav__link-text">Whatsapp Despatch</span></a></li>
            '.$option.'
            <li class="kt-nav__item"><a href="javascript:;" onclick="editSalesInvoice(\''.$row['id'].'\')" title="Edit Sales Order"class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-contract"></i><span class="kt-nav__link-text">Edit</span></a></li>
            <li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#view_assembly" title="View Assemblies" onclick="viewAssembly(\''.$row['si_no'].'\')"class="kt-nav__link"><i class="kt-nav__link-icon flaticon-menu-button"></i><span class="kt-nav__link-text">View Assembly</span></a></li>
            <li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#update_awb_sales" title="Update AWB" onclick="updateAWB(\''.$row['id'].'\')"class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-lorry"></i><span class="kt-nav__link-text">Update AWB#</span></a></li>
            <li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#toggle_sales_hsn" title="Toggle HSN" onclick="toggleHSN(\''.$row['id'].'\')"class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-size"></i><span class="kt-nav__link-text">Toggle HSN</span></a></li>
             <li class="kt-nav__item"><a href="javascript:;" onclick="pay(\''.$row['id'].'\')" title="Pay"class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-shopping-cart-1"></i><span class="kt-nav__link-text">Recieve</span></a></li>';
          
            if(($row_fetch['id'] ?? '') == $row['id']){
                $actionBtn .= '<li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#delete_sales_invoice" title="Delete" onclick="removeSalesInvoice(\''.$row['id'].'\')"class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-trash"></i><span class="kt-nav__link-text">Delete</span></a></li>';
            }else{
                $actionBtn .= '<li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#cancel_sales_invoice" title="Cancel" onclick="cancelSalesInvoice(\''.$row['id'].'\')"class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-trash"></i><span class="kt-nav__link-text">Cancel</span></a></li>';
            }
        $actionBtn .= '</ul>
        </div></div>';
    }else{
        $actionBtn = '<div class="dropdown"><a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown"><i class="flaticon-more-1"></i></a><div class="dropdown-menu dropdown-menu-right">
        <ul class="kt-nav">
            <li class="kt-nav__item"><a target="_blank" href="/assets/custom/re_calculate_sales.php?id='.$row['si_no'].'" class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-docs"></i><span class="kt-nav__link-text">Re-Calculate</span></a></li>
            <li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#print_sales_invoice" onclick="printSalesInvoice(\''.$row['si_no'].'\')" title="Print Sales Invoice"class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-printer"></i><span class="kt-nav__link-text">Print</span></a></li>
            <li class="kt-nav__item"><a href="/assets/custom/sales_print.php?id='.$row['si_no'].'&type=download" target="_blank" class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-download"></i><span class="kt-nav__link-text">Download</span></a></li>
            <li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#kt_modal_si_email" onclick="sendSIEmail(\''.$row['si_no'].'\')" title="Send Email"class="kt-nav__link"><i class="kt-nav__link-icon flaticon-email"></i><span class="kt-nav__link-text">Send Email</span></a></li>
            <li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#kt_modal_sales_invoice_whatsapp" onclick="Wa_sales_invoice(\''.$row['id'].'\')" title="Send Whatsapp"class="kt-nav__link"><i class="kt-nav__link-icon flaticon-whatsapp"></i><span class="kt-nav__link-text">Send Whatsapp</span></a></li>
            <li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#kt_modal_whatsapp" onclick="Wa_despatch_details(\''.$row['id'].'\')" title="Whatsapp Despatch"class="kt-nav__link"><i class="kt-nav__link-icon flaticon-whatsapp"></i><span class="kt-nav__link-text">Whatsapp Despatch</span></a></li>
            '.$option.$edit.'
            
            <li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#update_awb_sales" title="Update AWB" onclick="updateAWB(\''.$row['id'].'\')"class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-lorry"></i><span class="kt-nav__link-text">Update AWB#</span></a></li>
            <li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#toggle_sales_hsn" title="Toggle HSN" onclick="toggleHSN(\''.$row['id'].'\')"class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-size"></i><span class="kt-nav__link-text">Toggle HSN</span></a></li>
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
        'Client_ID' => $row_temp['id'] ?? '',
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
