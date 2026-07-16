<?php
session_start();
require_once "../connect.php";
setlocale(LC_MONETARY, 'en_IN');

$pagination = $_REQUEST['pagination'] ?? [];
$query_array = $_REQUEST['query'] ?? [];
$sort_array = $_REQUEST['sort'] ?? [];

$query = $query_array['generalSearch'] ?? '';

$sql_1 = "SELECT COUNT(*) AS total FROM proforma WHERE `client_name` LIKE '%$query%' ORDER BY `id` DESC";
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
$sql = "SELECT * FROM proforma WHERE `client_name` LIKE '%$query%' ORDER BY `id` DESC LIMIT ".$start.','.$perpage;
$query = $db->query($sql);
if ($query) {
while($row = $query->fetch_assoc()){

    $actionBtn = '<div class="dropdown"><a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown"><i class="flaticon-more-1"></i></a><div class="dropdown-menu dropdown-menu-right">
    <ul class="kt-nav">
        <li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#kt_modal_proforma_whatsapp" onclick="Wa_proforma_invoice(\''.$row['id'].'\')" title="Send Whatsapp"class="kt-nav__link"><i class="kt-nav__link-icon flaticon-whatsapp"></i><span class="kt-nav__link-text">Send Whatsapp</span></a></li>
        <li class="kt-nav__item"><a target="_blank" href="/assets/custom/proforma_invoice_print.php?id='.$row['so_no'].'&type=print" class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-printer"></i><span class="kt-nav__link-text">Print</span></a></li>
        <li class="kt-nav__item"><a target="_blank" href="/assets/custom/proforma_invoice_print.php?id='.$row['so_no'].'&type=download" class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-download"></i><span class="kt-nav__link-text">Download</span></a></li>
    </ul>
    </div></div>';
    
    $total = $row['total'];

    $c_name = $row['client_name'];

    $sql_temp = "SELECT * FROM clients WHERE name = '$c_name'";
    $query_temp = $db->query($sql_temp);
    $row_temp = ($query_temp && ($tmp = $query_temp->fetch_assoc())) ? $tmp : [];

    $item_details = json_decode($row['items'] ?? '', true);
    if (!is_array($item_details)) {
        $item_details = [];
    }

	$output['data'][] = array(		
        	'RecordID' => $count++,
            'Name' => $row['client_name'],
            'Date' => !empty($row['so_date']) ? date('d-m-Y',strtotime($row['so_date'])) : '',
            'Number'=>$row['so_no'],
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
