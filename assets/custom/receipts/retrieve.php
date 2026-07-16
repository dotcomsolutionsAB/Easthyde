<?php
session_start();
require_once "../connect.php";


//ini_set('display_errors', 1);


$pagination = $_REQUEST['pagination'];  
$query_array = $_REQUEST['query'];  
$sort_array = $_REQUEST['sort']; 

$start_date = $_SESSION['start'];
$end_date = $_SESSION['end']; 
$series = '%';

if(isset($query_array['rc_type']) && $query_array['rc_type'] != ''){
$series= $query_array['rc_type'];
}
// else{
//     $series ='%';
// }



$query = $query_array['generalSearch'];
$query=str_replace(" ","",$query);
$query=str_replace("-","",$query);
$query=str_replace(".","",$query);

$sql_1 = "SELECT COUNT(*) AS total FROM receipts WHERE (REPLACE(REPLACE(REPLACE(`client`, '.', ''), ' ', ''), '-', '') LIKE '%$query%' OR amount like '%$query%' ) AND `series` LIKE '$series' AND `status` != '9' AND `date` BETWEEN '$start_date' AND '$end_date'";
$query_1 = $db->query($sql_1);
$row_1 = $query_1->fetch_assoc();

$perpage = $pagination['perpage'];
$start = ($pagination['page']-1)*$perpage;
$pages = $row_1['total'] / $perpage;

$output = array('meta'=> array("page"=> $pagination['page'], "pages"=> $pages, "perpage"=> $perpage,"total"=> $row_1['total'],"sort"=> 'asc', "field"=> 'SN'), 'data' => array());

$count=1;
$sql = "SELECT * FROM receipts WHERE( REPLACE(REPLACE(REPLACE(`client`, '.', ''), ' ', ''), '-', '') LIKE '%$query%' OR amount like '%$query%')  AND `series` LIKE '$series' AND `status` != '9' AND `date` BETWEEN '$start_date' AND '$end_date' ORDER BY `id` DESC LIMIT ".$start.','.$perpage;
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

    if($menu_access['receipt']['edit'] == '1' || $userlevel == "sadmin_df56fdg"){

            $edit = '<li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#kt_modal_e_receipt" title="Edit" onclick=""class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-edit"></i><span class="kt-nav__link-text">Edit</span></a></li>';
    }
    
    if($menu_access['receipt']['delete'] == '1' || $userlevel == "sadmin_df56fdg"){

            $delete = '<li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#kt_modal_d_receipt" title="Delete" onclick="removeReceipts(\''.$row['id'].'\')"class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-trash"></i><span class="kt-nav__link-text">Delete</span></a></li>';
    }


        $actionBtn = '<div class="dropdown"><a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown"><i class="flaticon-more-1"></i></a><div class="dropdown-menu dropdown-menu-right">
    <ul class="kt-nav">
        <li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#kt_modal_whatsapp" onclick="Wa_receipt(\''.$row['id'].'\')" title="Send Whatsapp"class="kt-nav__link"><i class="kt-nav__link-icon flaticon-whatsapp"></i><span class="kt-nav__link-text">Send Whatsapp</span></a></li>
        '.$edit.$delete.'
        
    </ul>
    </div></div>';

    $si_arr = json_decode($row['sales_invoice'], true);
    $l=sizeof($si_arr['si_no']);

    $si_nos = "";
    for($i=0;$i<$l;$i++){
        $si_nos .= $si_arr['si_no'][$i].' ,';
    }

    $si_nos = rtrim($si_nos, ',');

	$output['data'][] = array(		
        'SN' => $count,
        'ID' => $count++,
		'RecordID' => $row['id'],
        'Receipt_No' => $row['r_no'],
        'Date' => date('d-m-Y', strtotime($row['date'])),
        'Client' => $row['client'],
        'Sale_Invoice'=>$si_nos,
        'Bank'=>$row['account'],
        'Mode'=>$row['mode'],
        'Bank_Name'=>$row['bank_name'],
        'Cheque'=>$row['cheque'],
        'IFSC'=>$row['ifsc'],
        'Amount' => number_format($row['amount'], 2),
        'Status'=>$row['status'],
        'Actions' => $actionBtn
	);
    
}

echo json_encode($output);

?> 
