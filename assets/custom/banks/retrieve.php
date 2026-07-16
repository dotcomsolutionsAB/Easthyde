<?php
session_start();
require_once "../connect.php";

$pagination = $_REQUEST['pagination'];  
$query_array = $_REQUEST['query'];  
$sort_array = $_REQUEST['sort'];  

$query = $query_array['generalSearch'];

$sql_1 = "SELECT COUNT(*) AS total FROM bank WHERE `account_name` LIKE '%$query%'";
$query_1 = $db->query($sql_1);
$row_1 = $query_1->fetch_assoc();

$perpage = $pagination['perpage'];
$start = ($pagination['page']-1)*$perpage;
$pages = $row_1['total'] / $perpage;

$output = array('meta'=> array("page"=> $pagination['page'], "pages"=> $pages, "perpage"=> $perpage,"total"=> $row_1['total'],"sort"=> 'asc', "field"=> 'SN'), 'data' => array());

$count=1;
$sql = "SELECT * FROM bank WHERE `account_name` LIKE '%$query%' ORDER BY `account_name` LIMIT ".$start.','.$perpage;
$query = $db->query($sql);
while($row = $query->fetch_assoc()){

    // Only Account_Name is clickable
    $account_name_link = '<a href="?page=bank_ledger?id=' . $row['id'] . '" target="_blank">' . $row['account_name'] . '</a>';
    
    $username = $_SESSION['username'];
    $userlevel = $_SESSION['userlevel'];

    $sql_access = "SELECT * FROM users WHERE `username` = '$username'";
    $query_access = $db->query($sql_access);
    $row_access = $query_access->fetch_assoc();

    $menu_access = json_decode($row_access['access'], true);
    
    $edit = '';
    $delete = '';

    if($menu_access['banks']['edit'] == '1' || $userlevel == "sadmin_df56fdg"){

            $edit = '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-sm" onclick="editBank(\''.$row['id'].'\')" title="Edit details">
                    <i class="flaticon2-paper"></i>
                </a>';
    }
    
    if($menu_access['banks']['delete'] == '1' || $userlevel == "sadmin_df56fdg"){

            $delete = '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-sm" data-toggle="modal" data-target="#kt_modal_d_bank" title="Delete" onclick="removeBank(\''.$row['id'].'\')">
                    <i class="flaticon2-trash"></i>
                </a>';
    }

    $actionBtn = $edit.$bank;

    $output['data'][] = array(        
        'SN' => $count++,
        'Account_Name' => $account_name_link,  // Only Account Name is clickable
        'Bank_Name' => $row['bank_name'],  // Plain text Bank Name
        'Account_Number' => $row['account_number'],  // Plain text Account Number
        'Bank_IFSC' => $row['ifsc'],  // Plain text IFSC
        'Opening_Balance'=>$row['opening_balance'].' <br>'.$row['updated_on'],
        
        'Actions' => $actionBtn
    );
}

echo json_encode($output);
?>
