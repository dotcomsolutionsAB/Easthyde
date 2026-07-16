<?php
session_start();
include ("../connect.php");

$pagination = $_REQUEST['pagination'];  
$query_array = $_REQUEST['query'];  
$sort_array = $_REQUEST['sort'];  

$query = $query_array['generalSearch'];

$sql_1 = "SELECT COUNT(*) AS total FROM assembly_operation WHERE `composite` LIKE '%$query%'";
$query_1 = $db->query($sql_1);
$row_1 = $query_1->fetch_assoc();

$perpage = $pagination['perpage'];
$start = ($pagination['page']-1)*$perpage;
$pages = $row_1['total'] / $perpage;

$output = array('meta'=> array("page"=> $pagination['page'], "pages"=> $pages, "perpage"=> $perpage,"total"=> $row_1['total'],"sort"=> 'asc', "field"=> 'SN'), 'data' => array());

$count=1;
$sql = "SELECT * FROM assembly_operation WHERE `composite` LIKE '%$query%' ORDER BY id DESC LIMIT ".$start.','.$perpage;
$query = $db->query($sql);
while($row = $query->fetch_assoc()){

    $items = json_decode($row['items'], true);
    $len = sizeof($items['product']);

    $sp = '';
    for($i=0;$i<$len;$i++){
        $sp .= $items['product'][$i].' ('.$items['quantity'][$i].'), </br>';
    }

    $sp = rtrim($sp, ', ');

    $actionBtn = '
    <a href="/assets/custom/assembly_print.php?id='.$row['id'].'&type=print" target="_blank" class="btn btn-sm btn-clean btn-icon btn-icon-sm" title="Print" >
                    <i class="flaticon2-printer"></i>
                </a>
                <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-sm" data-toggle="modal" data-target="#kt_modal_tag_invoice" title="Tag Invoice" onclick="tagInvoiceAssemblyOperation(\''.$row['id'].'\')">
                    <i class="flaticon2-tag"></i>
                </a>
                <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-sm" data-toggle="modal" data-target="#kt_modal_d_assemblies_operation" title="Delete" onclick="removeAssemblyOperation(\''.$row['id'].'\')">
                    <i class="flaticon2-trash"></i>
                </a>';

	$output['data'][] = array(		
		'SN' => $count++,
        'id' => $row['id'],
        'composite'=>$row['composite'],
        'spares' => $sp,
        'operation'=>$row['operation'],
        'quantity'=>$row['quantity'],
		'log_user' => $row['log_user'],
        'log_date' => $row['log_date'],
        'Actions' => $actionBtn
	);
}

echo json_encode($output);

?>