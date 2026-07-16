<?php
session_start();
require_once "../connect.php";

$pagination = $_REQUEST['pagination'] ?? [];
$query_array = $_REQUEST['query'] ?? [];
$sort_array = $_REQUEST['sort'] ?? [];

$query = $query_array['generalSearch'] ?? '';

$sql_1 = "SELECT COUNT(*) AS total FROM contra_entry";
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
$sql = "SELECT * FROM contra_entry LIMIT ".$start.','.$perpage;
$query = $db->query($sql);
if ($query) {
while($row = $query->fetch_assoc()){

    if(($_SESSION['userlevel'] ?? '') == 'sadmin_df56fdg'){
        $actionBtn = '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-sm" onclick="editContra(\''.$row['id'].'\')" title="Edit details">
                        <i class="flaticon2-paper"></i>
                    </a>
                    <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-sm" data-toggle="modal" data-target="#kt_modal_d_contra" title="Delete" onclick="removeContra(\''.$row['id'].'\')">
                        <i class="flaticon2-trash"></i>
                    </a>';
    }else{
        $actionBtn = '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-sm" onclick="editContra(\''.$row['id'].'\')" title="Edit details">
                        <i class="flaticon2-paper"></i>
                    </a>';
    }

    $output['data'][] = array(      
        'SN' => $count++,
        'Id'=>$row['id'],
        'Date' => $row['date'],
        'Transfer_from' => $row['transfer_from'],
        'Transfer_to' => $row['transfer_to'],
        'Amount' => $row['amount'],
        'Log_user' => $row['log_user'],
        'Log_date' => $row['log_date'],
        'Actions' => $actionBtn
    );
}
}

echo json_encode($output);

?>
