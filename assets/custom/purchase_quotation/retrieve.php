<?php
// ini_set('display_errors', 1);
session_start();
require_once "../connect.php";

setlocale(LC_MONETARY, 'en_IN');

$dt_start = $_SESSION['start'] ?? '';
$dt_end = $_SESSION['end'] ?? '';

$pagination = $_REQUEST['pagination'] ?? [];
$query_array = $_REQUEST['query'] ?? [];
$sort_array = $_REQUEST['sort'] ?? [];

$query = $query_array['generalSearch'] ?? '';
$query=str_replace(" ","",$query);
$query=str_replace("-","",$query);
$query=str_replace(".","",$query);

$status = $query_array['status'] ?? '';
$user = $query_array['user'] ?? '';
$product = $query_array['product'] ?? '';

if ($status == "") {
    $status = '%';
}

if ($user == "") {
    $user = '%';
}

if ($product == "") {
    $product = '%';
}

// Fetch total records for pagination
$sql_1 = "SELECT COUNT(*) AS total FROM purchase_quotation 
          WHERE (REPLACE(REPLACE(`pq_no`, ' ', ''), '-', '') LIKE '%$query%' 
          OR REPLACE(REPLACE(REPLACE(`supplier_name`, '-', ''), ' ', ''), '.', '') LIKE '%$query%' 
          OR `total` LIKE '%$query%')  
          AND `status` LIKE '$status' 
          AND `pi_date` BETWEEN '$dt_start' AND '$dt_end' 
          AND `log_user` LIKE '$user' 
          AND `items` LIKE '%$product%' 
          ORDER BY `pi_date` DESC";
$query_1 = $db->query($sql_1);
$row_1 = ($query_1 && ($tmp = $query_1->fetch_assoc())) ? $tmp : ['total' => 0];

$perpage = (int)($pagination['perpage'] ?? 10);
$page = (int)($pagination['page'] ?? 1);
if ($perpage < 1) { $perpage = 10; }
if ($page < 1) { $page = 1; }
$start = ($page - 1) * $perpage;
$pages = ceil($row_1['total'] / $perpage);

$output = array('meta'=> array(
    "page" => $page, 
    "pages" => $pages, 
    "perpage" => $perpage,
    "total" => $row_1['total'],
    "sort" => 'asc', 
    "field" => 'SN'), 
    'data' => array()
);

$count = 1;

// Fetch actual records
$sql = "SELECT * FROM purchase_quotation 
        WHERE (REPLACE(REPLACE(`pq_no`, ' ', ''), '-', '') LIKE '%$query%' 
        OR REPLACE(REPLACE(REPLACE(`supplier_name`, '-', ''), ' ', ''), '.', '') LIKE '%$query%' 
        OR `total` LIKE '%$query%') 
        AND `status` LIKE '$status' 
        AND `pi_date` BETWEEN '$dt_start' AND '$dt_end' 
        AND `log_user` LIKE '$user' 
        AND `items` LIKE '%$product%' 
        ORDER BY `pi_date` DESC 
        LIMIT ".$start.','.$perpage;
$query = $db->query($sql);

if ($query) {
while ($row = $query->fetch_assoc()) {
    // Check status and create appropriate action button
    // if ($row['status'] == 0) {
    //     $option = '<li class="kt-nav__item"><a href="javascript:;" onclick="setStatus(\''.$row['pq_no'].'\', \'1\', \'purchase_quotation\')" title="Completed" class="kt-nav__link"><i class="kt-nav__link-icon flaticon-like"></i><span class="kt-nav__link-text">Completed</span></a></li>';
    // } else {
    //     $option = '<li class="kt-nav__item"><a href="javascript:;" onclick="setStatus(\''.$row['pq_no'].'\', \'0\', \'purchase_quotation\')" title="Pending" class="kt-nav__link"><i class="kt-nav__link-icon flaticon-like"></i><span class="kt-nav__link-text">Pending</span></a></li>';
    // }

    // Admin-specific actions
   // Check if a file exists and set the View File link or a "No file uploaded" message
   if (!empty($row['file_path'])) {
    // Use htmlspecialchars() to prevent XSS and ensure valid HTML
    $viewFileOption = '<li class="kt-nav__item">
        <a href="' . htmlspecialchars($row['file_path']) . '" target="_blank" title="View File" class="kt-nav__link">
            <i class="kt-nav__link-icon flaticon-eye"></i>
            <span class="kt-nav__link-text">View File</span>
        </a>
    </li>';
} else {
    $viewFileOption = '<li class="kt-nav__item">
        <a href="javascript:;" title="No file uploaded" class="kt-nav__link">
            <i class="kt-nav__link-icon flaticon-eye"></i>
            <span class="kt-nav__link-text">No file uploaded</span>
        </a>
    </li>';
}
// Actions for admin users
if (($_SESSION['userlevel'] ?? '') == 'sadmin_df56fdg') {
    $actionBtn = '<div class="dropdown">
        <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown">
            <i class="flaticon-more-1"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right">
            <ul class="kt-nav">
              
                <li class="kt-nav__item">
                    <a href="javascript:;" data-toggle="modal" data-target="#kt_modal_purchase_quotation_whatsapp" onclick="Wa_purchase_quotation(\''.$row['id'].'\')" title="Whatsapp Quotation" class="kt-nav__link">
                        <i class="kt-nav__link-icon flaticon-whatsapp"></i>
                        <span class="kt-nav__link-text">Whatsapp Quotation</span>
                    </a>
                </li>
                ' . $viewFileOption . '
               
                <li class="kt-nav__item">
                    <a href="javascript:;" onclick="editPurchaseQuotation(\''.$row['id'].'\')" title="Edit Purchase Quotation" class="kt-nav__link">
                        <i class="kt-nav__link-icon flaticon2-contract"></i>
                        <span class="kt-nav__link-text">Edit</span>
                    </a>
                </li>
                <li class="kt-nav__item">
                    <a href="javascript:;" data-toggle="modal" data-target="#delete_purchase_quotation" title="Delete" onclick="removePurchaseQuotation(\''.$row['id'].'\')" class="kt-nav__link">
                        <i class="kt-nav__link-icon flaticon2-trash"></i>
                        <span class="kt-nav__link-text">Delete</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>';
} else {
    // Actions for non-admin users
    $actionBtn = '<div class="dropdown">
        <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown">
            <i class="flaticon-more-1"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right">
            <ul class="kt-nav">
               
                <li class="kt-nav__item">
                    <a href="javascript:;" data-toggle="modal" data-target="#kt_modal_purchase_quotation_whatsapp" onclick="Wa_purchase_quotation(\''.$row['id'].'\')" title="Whatsapp Quotation" class="kt-nav__link">
                        <i class="kt-nav__link-icon flaticon-whatsapp"></i>
                        <span class="kt-nav__link-text">Whatsapp Quotation</span>
                    </a>
                </li>
                ' . $viewFileOption . '
                '.$option.'
                <li class="kt-nav__item">
                    <a href="javascript:;" onclick="editPurchaseQuotation(\''.$row['id'].'\')" title="Edit Purchase Quotation" class="kt-nav__link">
                        <i class="kt-nav__link-icon flaticon2-contract"></i>
                        <span class="kt-nav__link-text">Edit</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>';
}


    $total = $row['total'];
    $s_name = $row['supplier_name'];

    // Fetch supplier details
    $sql_temp = "SELECT * FROM suppliers WHERE name = '$s_name'";
    $query_temp = $db->query($sql_temp);
    $row_temp = ($query_temp && ($tmp = $query_temp->fetch_assoc())) ? $tmp : [];

    // Add the data to the output array
    $output['data'][] = array(        
            'RecordID' => $count++,
            'RecordID2' => $row['id'],
            'Name' => $row['supplier_name'],
            'Supplier_ID' => $row_temp['id'] ?? '',
            'Date' => !empty($row['pi_date']) ? date('d-m-Y',strtotime($row['pi_date'])) : '',
            'Number' => $row['pq_no'],
            'ID' => $row['id'],
            'Status' => $row['status'],
            'KT_Class' => $row_temp['kt-class'] ?? '',
            'Amount' => number_format((float)$total, 2),
            'User' => $row['log_user'],
            'Log_Date' => !empty($row['log_date']) ? date('d-m-Y', strtotime($row['log_date'])) : '',
            'Actions' => $actionBtn,
            'sql' => $sql
    );
}
}

echo json_encode($output);
?>
