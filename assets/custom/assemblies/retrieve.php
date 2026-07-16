<?php
session_start();
require_once "../connect.php";

$pagination = $_REQUEST['pagination'];  
$query_array = $_REQUEST['query'];  
$sort_array = $_REQUEST['sort'];  

$query = $query_array['generalSearch'];

$sql_1 = "SELECT COUNT(*) AS total FROM assembly WHERE `composite` LIKE '%$query%'";
$query_1 = $db->query($sql_1);
$row_1 = $query_1->fetch_assoc();

$perpage = $pagination['perpage'];
$start = ($pagination['page']-1)*$perpage;
$pages = $row_1['total'] / $perpage;

$output = array('meta'=> array("page"=> $pagination['page'], "pages"=> $pages, "perpage"=> $perpage,"total"=> $row_1['total'],"sort"=> 'asc', "field"=> 'SN'), 'data' => array());

$count=1;
$sql = "SELECT * FROM assembly WHERE `composite` LIKE '%$query%' ORDER BY `composite` LIMIT ".$start.','.$perpage;
$query = $db->query($sql);

while($row = $query->fetch_assoc()){
    $spares = json_decode($row['spares'], true);
    $len = sizeof($spares['product']);

    $sp = '';
    for($i=0;$i<$len;$i++){
        $sp .= $spares['product'][$i].' ('.$spares['quantity'][$i].'), </br>';
    }

    $sp = rtrim($sp, ', ');
    $actionBtn = '
            <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-sm" data-toggle="modal" data-target="#kt_modal_d_assemblies" title="Delete" onclick="removeAssemblies(\''.$row['id'].'\')">
                <i class="flaticon2-trash"></i>
            </a>
            ';

	$output['data'][] = array(		
		'SN' => $count++,
        'Composite' => $row['composite'],
        'Spares' => $sp,
        'Log_user'=>$row['log_user'],
        'Log_date'=>$row['log_date'],
        'Actions' => $actionBtn
	);
}

echo json_encode($output);

?>