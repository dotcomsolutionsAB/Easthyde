<?php
session_start();
require_once "../connect.php";

$pagination = $_REQUEST['pagination'] ?? [];
$query_array = $_REQUEST['query'] ?? [];
$sort_array = $_REQUEST['sort'] ?? [];

$query = $query_array['generalSearch'] ?? '';
$query=str_replace(" ","",$query);
$query=str_replace("-","",$query);
$query=str_replace(".","",$query);


$sql_1 = "SELECT COUNT(*) AS total 
          FROM clients 
          WHERE (REPLACE(REPLACE(REPLACE(`name`, '.', ''), ' ', ''), '-', '') LIKE '%$query%' 
          OR JSON_UNQUOTE(JSON_EXTRACT(`contacts`, '$.mobile[0]')) LIKE '%$query%')";

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
$sql = "SELECT * FROM clients  WHERE (REPLACE(REPLACE(REPLACE(`name`, '.', ''), ' ', ''), '-', '') LIKE '%$query%' 
          OR JSON_UNQUOTE(JSON_EXTRACT(`contacts`, '$.mobile[0]')) LIKE '%$query%') ORDER BY `name` LIMIT ".$start.','.$perpage;
$query = $db->query($sql);
if ($query) {
while($row = $query->fetch_assoc()){
    
    $username = $_SESSION['username'] ?? '';
    $userlevel = $_SESSION['userlevel'] ?? '';

    $sql_access = "SELECT * FROM users WHERE `username` = '$username'";
    $query_access = $db->query($sql_access);
    $row_access = ($query_access && ($tmp = $query_access->fetch_assoc())) ? $tmp : [];

    $menu_access = json_decode($row_access['access'] ?? '', true);
    if (!is_array($menu_access)) {
        $menu_access = [];
    }
    
    $edit = '';
    $delete = '';

    if(($menu_access['clients']['edit'] ?? '') == '1' || $userlevel == "sadmin_df56fdg"){

            $edit = '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-sm" data-toggle="modal" data-target="#kt_modal_edit_client" onclick="editClient(\''.$row['id'].'\')" title="Edit details">
                        <i class="flaticon2-paper"></i>
                    </a>';
    }
    
    if(($menu_access['clients']['delete'] ?? '') == '1' || $userlevel == "sadmin_df56fdg"){

            $delete = '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-sm" data-toggle="modal" data-target="#kt_modal_d_client" title="Delete" onclick="removeClient(\''.$row['id'].'\')">
                        <i class="flaticon2-trash"></i>
                    </a>';
    }

    if(($_SESSION['userlevel'] ?? '') == 'sadmin_df56fdg'){
        $actionBtn = '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-sm" data-toggle="modal" data-target="#kt_modal_edit_client" onclick="editClient(\''.$row['id'].'\')" title="Edit details">
                        <i class="flaticon2-paper"></i>
                    </a>
                    <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-sm" data-toggle="modal" data-target="#kt_modal_d_client" title="Delete" onclick="removeClient(\''.$row['id'].'\')">
                        <i class="flaticon2-trash"></i>
                    </a>';
    }else{
        $actionBtn = $edit;
    }

    $contact_details = json_decode($row['contacts'] ?? '', true) ?: [];
    $address_details = json_decode($row['address'] ?? '', true) ?: [];
    $bank_details = json_decode($row['bank_details'] ?? '', true) ?: [];
	$output['data'][] = array(		
		'SN' => $count++,
        'Name' => $row['name'],
        'Id'=>$row['id'],
        'Category'=>$row['type'],
        'Add1' => $address_details['address_1'] ?? '',
        'Add2' => $address_details['address_2'] ?? '',
        'City' => $address_details['city'] ?? '',
        'Pincode' => $address_details['pincode'] ?? '',
        'State' => $row['state'],
        'Country' => $row['country'],
        'GSTIN' => $row['gstin'],
        'Contact_Name'=>$contact_details['name'][0] ?? '',
        'Designation'=>$contact_details['designation'][0] ?? '',
        'Mobile'=>$contact_details['mobile'][0] ?? '',
        'Phone'=>$contact_details['phone'][0] ?? '',
        'Email'=>$contact_details['email'][0] ?? '',
        'Bank_Client'=>$bank_details['name'] ?? '',
        'Bank_Name'=>$bank_details['bank_name'] ?? '',
        'Bank_Account'=>$bank_details['account'] ?? '',
        'Bank_IFSC'=>$bank_details['ifsc'] ?? '',
        'User' => $row['log_user'],
        'KT_Class'=>$row['kt-class'],
        'Date' => $row['log_date'],
        'Actions' => $actionBtn
	);
}
}

echo json_encode($output);

?>
