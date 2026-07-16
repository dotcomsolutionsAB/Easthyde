<?php
session_start();
require_once "../connect.php";
setlocale(LC_MONETARY, 'en_IN');

$pagination = $_REQUEST['pagination'] ?? [];
$query_array = $_REQUEST['query'] ?? [];
$sort_array = $_REQUEST['sort'] ?? [];

$query = $query_array['generalSearch'] ?? '';

$user = $query_array['user'] ?? '';
$product = $query_array['product'] ?? '';

if($user=="")
{
    $user='%';
}

if($product=="")
{
    $product='%';
}

$sql_1 = "SELECT COUNT(*) AS total FROM proforma WHERE `client_name` LIKE '%$query%'|| `mobile` like '%$query%' AND `log_user` LIKE '$user' AND `items` LIKE '%$product%' ORDER BY `id` DESC";
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
$sql = "SELECT * FROM proforma WHERE `client_name` LIKE '%$query%' || `mobile` like '%$query%' AND `log_user` LIKE '$user' AND `items` LIKE '%$product%' ORDER BY `id` DESC LIMIT ".$start.','.$perpage;
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

    if(($menu_access['proforma_invoice']['edit'] ?? '') == '1' || $userlevel == "sadmin_df56fdg"){

            $edit = '<li class="kt-nav__item"><a id="edit_so" href="javascript:;" onclick="editProformaInvoice(\''.$row['pr_no'].'\')" title="Edit Sales Order"class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-contract"></i><span class="kt-nav__link-text">Edit</span></a></li>';
    }
    
    if(($menu_access['proforma_invoice']['delete'] ?? '') == '1' || $userlevel == "sadmin_df56fdg"){

            $delete = '<li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#delete_proforma_invoice" title="Delete" onclick="removeProformaInvoice(\''.$row['id'].'\')"class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-trash"></i><span class="kt-nav__link-text">Delete</span></a></li>';
    }

    $actionBtn = '<div class="dropdown"><a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown"><i class="flaticon-more-1"></i></a><div class="dropdown-menu dropdown-menu-right">
    <ul class="kt-nav">
        <li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#kt_modal_pr_note" onclick="addNoteProforma(\''.$row['pr_no'].'\')" title="Add Note"class="kt-nav__link"><i class="kt-nav__link-icon flaticon-chat"></i><span class="kt-nav__link-text">Add Note</span></a></li>
        <li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#kt_modal_proforma_whatsapp" onclick="Wa_proforma_invoice(\''.$row['id'].'\')" title="Send Whatsapp"class="kt-nav__link"><i class="kt-nav__link-icon flaticon-whatsapp"></i><span class="kt-nav__link-text">Send Whatsapp</span></a></li>

        <li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#kt_modal_pr_email" onclick="sendPIEmail(\''.$row['pr_no'].'\')" title="Add Note"class="kt-nav__link"><i class="kt-nav__link-icon flaticon-email"></i><span class="kt-nav__link-text">Send Email</span></a></li>
        <li class="kt-nav__item"><a target="_blank" href="/assets/custom/proforma_invoice_print.php?id='.$row['pr_no'].'&type=print" class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-printer"></i><span class="kt-nav__link-text">Print</span></a></li>
        <li class="kt-nav__item"><a target="_blank" href="/assets/custom/proforma_invoice_print.php?id='.$row['pr_no'].'&type=download" class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-download"></i><span class="kt-nav__link-text">Download</span></a></li>
        '.$edit.$delete.'
    </ul>
    </div></div>';
    
    $total = $row['total'];

    $c_name = $row['client_name'];

    $sql_temp = "SELECT * FROM clients WHERE name = '$c_name'";
    $query_temp = $db->query($sql_temp);
    $row_temp = ($query_temp && ($tmp = $query_temp->fetch_assoc())) ? $tmp : [];

    $proforma = '<a href="?page=proforma_notes&pr_no='.$row['pr_no'].'" target="_blank">'.$row['pr_no'].'</a>';
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
            'Name' => $tmp,
            'Date' => !empty($row['pr_date']) ? date('d-m-Y',strtotime($row['pr_date'])) : '',
            'Number'=>$row['pr_no'],
            'Proforma'=>$proforma,
            'Product'=>$item_details['product'][0] ?? '',
            'Quantity'=>$item_details['quantity'][0] ?? '',
            'Price'=>$item_details['price'][0] ?? '',
            'Discount'=>$item_details['discount'][0] ?? '',
            'Tax'=>$item_details['tax'][0] ?? '',
            'Description'=>$item_details['desc'][0] ?? '',
            'Status'=>$row['status'],
            'KT_Class'=>$row_temp['kt-class'] ?? '',
            'User'=>$row['log_user'],
            'Amount'=>number_format((float)$total, 2),
            'Log_Date'=>!empty($row['log_date']) ? date('d-m-Y',strtotime($row['log_date'])) : '',
            'Actions' => $actionBtn
    );
}
}
echo json_encode($output);
?>
