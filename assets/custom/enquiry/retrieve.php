<?php
session_start();
require_once "../connect.php";

$pagination = $_REQUEST['pagination'];  
$query_array = $_REQUEST['query'];  
$sort_array = $_REQUEST['sort'];  

$query = $query_array['generalSearch'];

$sql_1 = "SELECT COUNT(*) AS total FROM enquiry WHERE `enquiry_no` LIKE '%$query%' OR `client` LIKE '%$query%' ORDER BY id DESC";
$query_1 = $db->query($sql_1);
$row_1 = $query_1->fetch_assoc();

$perpage = $pagination['perpage'];
$start = ($pagination['page']-1)*$perpage;
$pages = $row_1['total'] / $perpage;

$output = array('meta'=> array("page"=> $pagination['page'], "pages"=> $pages, "perpage"=> $perpage,"total"=> $row_1['total'],"sort"=> 'asc', "field"=> 'SN'), 'data' => array());

$count=1;
$sql = "SELECT * FROM enquiry WHERE `enquiry_no` LIKE '%$query%' OR `client` LIKE '%$query%'  ORDER BY `id` DESC LIMIT ".$start.','.$perpage;
$query = $db->query($sql);
while($row = $query->fetch_assoc()){

    if($row['status']==0)
    {
        $option='<li class="kt-nav__item"><a href="javascript:;" onclick="setStatus(\''.$row['enquiry_no'].'\', \'1\', \'enquiry\')" title="Completed"class="kt-nav__link"><i class="kt-nav__link-icon flaticon-like"></i><span class="kt-nav__link-text">Completed</span></a>
        </li>
        <li class="kt-nav__item"><a href="javascript:;" onclick="setStatus(\''.$row['enquiry_no'].'\', \'2\', \'enquiry\')" title="Rejected"class="kt-nav__link"><i class="kt-nav__link-icon flaticon-circle"></i><span class="kt-nav__link-text">Rejected</span></a>
        </li>';
    }
    else 
    {
        $option='<li class="kt-nav__item"><a href="javascript:;" onclick="setStatus(\''.$row['enquiry_no'].'\', \'0\', \'enquiry\')" title="Pending"class="kt-nav__link"><i class="kt-nav__link-icon flaticon-like"></i><span class="kt-nav__link-text">Pending</span></a>
        </li>';
    }

    if($_SESSION['userlevel'] == 'sadmin_df56fdg'){
       $actionBtn = '<div class="dropdown"><a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown"><i class="flaticon-more-1"></i></a><div class="dropdown-menu dropdown-menu-right">
    <ul class="kt-nav">
        <li class="kt-nav__item"><a href="/assets/custom/enquiry_print.php?id='.$row['enquiry_no'].'&type=print" target="_blank" class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-printer"></i><span class="kt-nav__link-text">Print</span></a></li>
        <li class="kt-nav__item"><a href="/assets/custom/enquiry_print.php?id='.$row['enquiry_no'].'&type=download" target="_blank" class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-download"></i><span class="kt-nav__link-text">Download</span></a></li>
        <li class="kt-nav__item"><a href="javascript:;" onclick="editEnquiry(\''.$row['enquiry_no'].'\')" title="Edit Enquiry"class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-contract"></i><span class="kt-nav__link-text">Edit</span></a></li>
        <li class="kt-nav__item"><a href="javascript:;" data-toggle="modal" data-target="#kt_modal_d_enquiry" title="Delete" onclick="removeEnquiry(\''.$row['id'].'\')"class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-trash"></i><span class="kt-nav__link-text">Delete</span></a></li>
    </ul>
    </div></div>';
    }else{
        $actionBtn = '<div class="dropdown"><a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown"><i class="flaticon-more-1"></i></a><div class="dropdown-menu dropdown-menu-right">
    <ul class="kt-nav">
        <li class="kt-nav__item"><a href="/assets/custom/enquiry_print.php?id='.$row['enquiry_no'].'&type=print" target="_blank" class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-printer"></i><span class="kt-nav__link-text">Print</span></a></li>
        <li class="kt-nav__item"><a href="/assets/custom/enquiry_print.php?id='.$row['enquiry_no'].'&type=download" target="_blank" class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-download"></i><span class="kt-nav__link-text">Download</span></a></li>
        <li class="kt-nav__item"><a href="javascript:;" onclick="editEnquiry(\''.$row['enquiry_no'].'\')" title="Edit Enquiry"class="kt-nav__link"><i class="kt-nav__link-icon flaticon2-contract"></i><span class="kt-nav__link-text">Edit</span></a></li>
    </ul>
    </div></div>';
    }

	$output['data'][] = array(
		        'SN' => $count++,
                'RecordID' => $count,
                'Date' => date('d-m-Y', strtotime($row['enquiry_date'])),
                'Client' => $row['client'],
                'Enquiry_no'=>$row['enquiry_no'],
                'Mode'=>$row['mode'],
                'Status'=>$row['status'],
                'User'=>$row['log_user'],
                'Log_Date'=>$row['log_date'],
                'Actions' => $actionBtn
	);
}

echo json_encode($output);

?>