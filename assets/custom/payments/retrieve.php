<?php
session_start();
require_once "../connect.php";

$pagination = $_REQUEST['pagination'];  
$query_array = $_REQUEST['query'];  
$sort_array = $_REQUEST['sort'];  

$start_date = $_SESSION['start'];
$end_date = $_SESSION['end'];

$query = $query_array['generalSearch'];
$query=str_replace(" ","",$query);
$query=str_replace(".","",$query);
$query=str_replace("-","",$query);

$sql_1 = "SELECT COUNT(*) AS total FROM payments WHERE  REPLACE(REPLACE(REPLACE(`supplier`, '-', ''), ' ', ''), '.', '')  LIKE '%$query%' AND `status` != '9' AND `date` BETWEEN '$start_date' AND '$end_date'";
$query_1 = $db->query($sql_1);
$row_1 = $query_1->fetch_assoc();

$perpage = $pagination['perpage'];
$start = ($pagination['page']-1)*$perpage;
$pages = $row_1['total'] / $perpage;

$output = array('meta'=> array("page"=> $pagination['page'], "pages"=> $pages, "perpage"=> $perpage,"total"=> $row_1['total'],"sort"=> 'asc', "field"=> 'SN'), 'data' => array());

$count=1;
$sql = "SELECT * FROM payments WHERE  REPLACE(REPLACE(REPLACE(`supplier`, '-', ''), ' ', ''), '.', '')  LIKE '%$query%' AND `date` BETWEEN '$start_date' AND '$end_date' ORDER BY `date` DESC LIMIT ".$start.','.$perpage;
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

    if($menu_access['payments']['edit'] == '1' || $userlevel == "sadmin_df56fdg"){

            $edit = '';
    }
    
    if($menu_access['payments']['delete'] == '1' || $userlevel == "sadmin_df56fdg"){

            $delete = '<li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#kt_modal_d_payment" title="Delete" onclick="removePayments(\''.$row['id'].'\')"class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-trash"></i><span class="kt-nav__link-text">Delete</span></a></li>';
    }

        $actionBtn = '<div class="dropdown"><a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown"><i class="flaticon-more-1"></i></a><div class="dropdown-menu dropdown-menu-right">
    <ul class="kt-nav">
        <li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#kt_modal_whatsapp" onclick="Wa_payment(\''.$row['id'].'\')" title="Send Whatsapp"class="kt-nav__link"><i class="kt-nav__link-icon flaticon-whatsapp"></i><span class="kt-nav__link-text">Send Whatsapp</span></a></li>
        '.$delete.'
    </ul>
    </div></div>';

    $pi_arr = json_decode($row['purchase_invoice'], true);
    $l=sizeof($pi_arr['pi_no']);

    $pi_nos = "";
    for($i=0;$i<$l;$i++){
        $pi_nos .= $pi_arr['pi_no'][$i].' ,';
    }

    $pi_nos = rtrim($pi_nos, ',');

    $output['data'][] = array(      
        'SN' => $count,
        'ID' => $count++,
        'RecordID' => $row['id'],
        'Payment_No' => $row['py_no'],
        'Date' => date('d-m-Y', strtotime($row['date'])),
        'Supplier' => $row['supplier'],
        'Purchase_Invoice'=>$pi_nos,
        'Bank'=>$row['account'],
        'Mode'=>$row['mode'],
        'Bank_Name'=>$row['bank_name'],
        'Cheque'=>$row['cheque'],
        'IFSC'=>$row['ifsc'],
        'Amount'=>$row['amount'],
        'Status'=>$row['status'],
        'Actions' => $actionBtn
    );
}

echo json_encode($output);

?>