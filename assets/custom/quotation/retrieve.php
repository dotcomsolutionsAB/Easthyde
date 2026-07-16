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
$query=str_replace("(","",$query);
$query=str_replace(")","",$query);
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

$sql_fetch = "SELECT * FROM quotation ORDER BY id DESC LIMIT 1";
$query_fetch = $db->query($sql_fetch);
$row_fetch = $query_fetch->fetch_assoc();

$sql_1 = "SELECT COUNT(*) AS total FROM quotation WHERE (REPLACE(REPLACE(`quotation_no`, ' ', ''), '-', '') LIKE '%$query%' || REPLACE(REPLACE(REPLACE(`client`, '-', ''), ' ', ''), '.', '') LIKE '%$query%' LIKE '%$query%' || `total` LIKE '%$query%'|| `mobile` LIKE '%$query%') AND `status` LIKE '$status' AND `log_user` LIKE '$user' AND `items` LIKE '%$product%'";
$query_1 = $db->query($sql_1);
$row_1 = $query_1->fetch_assoc();

$perpage = $pagination['perpage'];
$start = ($pagination['page']-1)*$perpage;
$pages = $row_1['total'] / $perpage;

$output = array('meta'=> array("page"=> $pagination['page'], "pages"=> $pages, "perpage"=> $perpage,"total"=> $row_1['total'],"sort"=> 'asc', "field"=> 'SN'), 'data' => array());

$count=1;
$sql = "SELECT * FROM quotation WHERE (REPLACE(REPLACE(`quotation_no`, ' ', ''), '-', '') LIKE '%$query%' || REPLACE(REPLACE(REPLACE(`client`, '-', ''), ' ', ''), '.', '') LIKE '%$query%' || `total` LIKE '%$query%'|| `mobile` LIKE '%$query%' ) AND `status` LIKE '$status' AND `log_user` LIKE '$user' AND `items` LIKE '%$product%' ORDER BY `quotation_date` DESC,`quotation_no`  DESC LIMIT ".$start.','.$perpage;
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

    if($menu_access['quotation']['edit'] == '1' || $userlevel == "sadmin_df56fdg"){

            $edit = '<li class="kt-nav__item"><a href="javascript:;" onclick="editQuotation(\''.$row['quotation_no'].'\')" title="Edit Quotation"class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-contract"></i><span class="kt-nav__link-text">Edit</span></a></li>';
    }
    
    if($menu_access['quotation']['delete'] == '1' || $userlevel == "sadmin_df56fdg"){

            $delete = '<li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#kt_modal_d_quotation" title="Delete" onclick="removeQuotation(\''.$row['id'].'\')"class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-trash"></i><span class="kt-nav__link-text">Delete</span></a></li>';
    }
            
    
    
    if($row['status']==0)
    {
        $option='<li class="kt-nav__item"><a href="javascript:;" onclick="setStatus(\''.$row['quotation_no'].'\', \'1\', \'quotation\')" title="Completed"class="kt-nav__link"><i class="kt-nav__link-icon flaticon-like"></i><span class="kt-nav__link-text">Completed</span></a>
        </li>
        <li class="kt-nav__item"><a href="javascript:;" onclick="setStatus(\''.$row['quotation_no'].'\', \'2\', \'quotation\')" title="Rejected"class="kt-nav__link"><i class="kt-nav__link-icon flaticon-circle"></i><span class="kt-nav__link-text">Rejected</span></a>
        </li>';
    }
    else 
    {
        $option='<li class="kt-nav__item"><a href="javascript:;" onclick="setStatus(\''.$row['quotation_no'].'\', \'0\', \'quotation\')" title="Pending"class="kt-nav__link"><i class="kt-nav__link-icon flaticon-like"></i><span class="kt-nav__link-text">Pending</span></a>
        </li>';
    }

    if($_SESSION['userlevel'] == 'sadmin_df56fdg'){

       $actionBtn = '<div class="dropdown"><a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown"><i class="flaticon-more-1"></i></a><div class="dropdown-menu dropdown-menu-right">
    <ul class="kt-nav">
        <li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#kt_modal_a_qnote" onclick="addNoteQuotation(\''.$row['quotation_no'].'\')" title="Add Note"class="kt-nav__link"><i class="kt-nav__link-icon flaticon-chat"></i><span class="kt-nav__link-text">Add Note</span></a></li>
        
        <li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#kt_modal_q_email" onclick="sendQEmail(\''.$row['quotation_no'].'\')" title="Add Note"class="kt-nav__link"><i class="kt-nav__link-icon flaticon-email"></i><span class="kt-nav__link-text">Send Email</span></a></li>

        <li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#kt_modal_quotation_whatsapp" onclick="Wa_quotation(\''.$row['id'].'\')" title="Send Whatsapp"class="kt-nav__link"><i class="kt-nav__link-icon flaticon-whatsapp"></i><span class="kt-nav__link-text">Send Whatsapp</span></a></li>

        <li class="kt-nav__item"><a href="/assets/custom/quotation_print.php?id='.$row['quotation_no'].'&type=print" target="_blank" class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-printer"></i><span class="kt-nav__link-text">Print</span></a></li>

        <li class="kt-nav__item"><a href="/assets/custom/quotation_print.php?id='.$row['quotation_no'].'&type=download" target="_blank" class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-download"></i><span class="kt-nav__link-text">Download</span></a></li>

        <li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#kt_modal_duplicate_quotation" onclick="duplicateQuotation(\''.$row['id'].'\')" title="Duplicate Quotation Order"class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-copy"></i><span class="kt-nav__link-text">Duplicate</span></a></li>

       '.$option.'
        <li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#toggle_quotation_hsn" title="Toggle HSN" onclick="toggleQHSN(\''.$row['id'].'\')"class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-size"></i><span class="kt-nav__link-text">Toggle HSN</span></a></li>

        <li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#toggle_quotation_totals" title="Toggle Totals" onclick="toggleQTotals(\''.$row['id'].'\')"class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-size"></i><span class="kt-nav__link-text">Toggle Totals</span></a></li>
        <li class="kt-nav__item"><a href="javascript:;" onclick="editQuotation(\''.$row['quotation_no'].'\')" title="Edit Quotation"class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-contract"></i><span class="kt-nav__link-text">Edit</span></a></li>';
        if($row_fetch['id'] == $row['id']){
                $actionBtn .= '<li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#kt_modal_d_quotation" title="Delete" onclick="removeQuotation(\''.$row['id'].'\')"class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-trash"></i><span class="kt-nav__link-text">Delete</span></a></li>';
            }else{
                $actionBtn .= '<li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#cancel_quotation" title="Cancel" onclick="cancelQuotation(\''.$row['id'].'\')"class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-trash"></i><span class="kt-nav__link-text">Cancel</span></a></li>';
            }
        $actionBtn .= '</ul>
        </div></div>';

    }else{
        $actionBtn = '<div class="dropdown"><a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown"><i class="flaticon-more-1"></i></a><div class="dropdown-menu dropdown-menu-right">
    <ul class="kt-nav">
        <li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#kt_modal_a_qnote" onclick="addNoteQuotation(\''.$row['quotation_no'].'\')" title="Add Note"class="kt-nav__link"><i class="kt-nav__link-icon flaticon-chat"></i><span class="kt-nav__link-text">Add Note</span></a></li>
        <li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#kt_modal_q_email" onclick="sendQEmail(\''.$row['quotation_no'].'\')" title="Add Note"class="kt-nav__link"><i class="kt-nav__link-icon flaticon-email"></i><span class="kt-nav__link-text">Send Email</span></a></li>
        <li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#kt_modal_quotation_whatsapp" onclick="Wa_quotation(\''.$row['id'].'\')" title="Send Whatsapp"class="kt-nav__link"><i class="kt-nav__link-icon flaticon-whatsapp"></i><span class="kt-nav__link-text">Send Whatsapp</span></a></li>
        <li class="kt-nav__item"><a href="/assets/custom/quotation_print.php?id='.$row['quotation_no'].'&type=print" target="_blank" class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-printer"></i><span class="kt-nav__link-text">Print</span></a></li>
        <li class="kt-nav__item"><a href="/assets/custom/quotation_print.php?id='.$row['quotation_no'].'&type=download" target="_blank" class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-download"></i><span class="kt-nav__link-text">Download</span></a></li>
        '.$option.'
        <li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#kt_modal_duplicate_quotation" onclick="duplicateQuotation(\''.$row['id'].'\')" title="Duplicate Quotation Order"class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-copy"></i><span class="kt-nav__link-text">Duplicate</span></a></li>
        <li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#toggle_quotation_hsn" title="Toggle HSN" onclick="toggleQHSN(\''.$row['id'].'\')"class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-size"></i><span class="kt-nav__link-text">Toggle HSN</span></a></li>
        <li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#toggle_quotation_totals" title="Toggle Totals" onclick="toggleQTotals(\''.$row['id'].'\')"class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-size"></i><span class="kt-nav__link-text">Toggle Totals</span></a></li>
        '.$edit.$delete.'
    </ul>
    </div></div>'; 
    }

    $quotation = '<a href="?page=quotation_notes&q_no='.$row['quotation_no'].'" target="_blank">'.$row['quotation_no'].'</a>';
    
    if($row['mobile']!=0){
       $tmp = $row['client']."<br>Mob: ".$row['mobile'];
        }
        else{
            $tmp = $row['client'];
        }


	$output['data'][] = array(
        'SN' => $count++,
        'RecordID' => $count,
        'Date' => date('d-m-Y', strtotime($row['quotation_date'])),   
        
        'Client' => $tmp,
       

        'Amount' => money_format('%!i', $row['total']),
        'Quotation'=>$quotation,
        'Quotation_no'=>$row['quotation_no'],
        'Enquiry'=>$row['quotation_top'],
        'Terms'=>$row['terms'],
        'Status'=>$row['status'],
        'User'=>$row['log_user'],
        'Cancelled'=>$row['cancelled'],
        'Log_Date'=>$row['log_date'],
        'Actions' => $actionBtn
	);
}

echo json_encode($output);

?>