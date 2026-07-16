<?php
session_start();
require_once "../connect.php";

$pagination = $_REQUEST['pagination'];  
$query_array = $_REQUEST['query'];  
$sort_array = $_REQUEST['sort'];  

$query = $query_array['generalSearch'];

$sql_1 = "SELECT COUNT(*) AS total FROM materials_received WHERE `supplier_name` LIKE '%$query%' OR `voucher_no` LIKE '%$query%'";
$query_1 = $db->query($sql_1);
$row_1 = $query_1->fetch_assoc();

$perpage = $pagination['perpage'];
$start = ($pagination['page']-1)*$perpage;
$pages = $row_1['total'] / $perpage;

$output = array('meta'=> array("page"=> $pagination['page'], "pages"=> $pages, "perpage"=> $perpage,"total"=> $row_1['total'],"sort"=> 'asc', "field"=> 'SN'), 'data' => array());

$count=1;
$sql = "SELECT * FROM materials_received WHERE `supplier_name` LIKE '%$query%' OR `voucher_no` LIKE '%$query%' ORDER BY `date` DESC, `id` DESC LIMIT ".$start.','.$perpage;
$query = $db->query($sql);

while($row = $query->fetch_assoc()){
    $actionBtn = '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-sm" onclick="editMaterialsReceived(\''.$row['id'].'\')" title="Edit details">
                    <i class="flaticon2-paper"></i>
                </a>
                <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-sm" data-toggle="modal" data-target="#kt_modal_d_materials_received" title="Delete" onclick="removeMaterialsReceived(\''.$row['id'].'\')">
                    <i class="flaticon2-trash"></i>
                </a>';

	$output['data'][] = array(		
		'SN' => $count++,
        'ID' => $row['id'],
        'Voucher_no' => ($row['voucher_no'] != '' ? $row['voucher_no'] : ('MRN-LEGACY-'.$row['id'])),
        'Voucher_type' => ($row['voucher_type'] != '' ? $row['voucher_type'] : 'MRN'),
        'Supplier' => $row['supplier_name'],
        'Date' => date('d-m-Y',strtotime($row['date'])),
        'Log_user'=>$row['log_user'],
        'Log_date'=>date('d-m-Y',strtotime($row['log_date'])),
        'Actions' => $actionBtn
	);
}

echo json_encode($output);

?>