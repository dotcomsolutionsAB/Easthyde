<?php
session_start();
include ("../connect.php");

$output = array('meta'=> array("page"=> 1, "pages"=> 1, "perpage"=> 1,"total"=> 1,"sort"=> "asc", "field"=> "SN"), 'data' => array());



$count=1;
$sql = "SELECT * FROM users";
$query = $db->query($sql);
if ($query) {
while($row = $query->fetch_assoc()){

    $actionBtn = '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-sm" data-toggle="modal" data-target="#kt_modal_e_user" onclick="editUser(\''.$row['id'].'\')" title="Edit details">
                            <i class="flaticon2-paper"></i>
                        </a>
                        <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-sm" data-toggle="modal" data-target="#kt_modal_d_user" title="Delete" onclick="removeUser(\''.$row['id'].'\')">
                            <i class="flaticon2-trash"></i>
                        </a>';
                        
    $name = '<a href="?page=access_control&id='.$row['id'].'" target="_blank">'.$row['name'].'</a>';

    $output['data'][] = array(      
        'SN' => $count++,
        'id' => $row['id'],
        'name' => $name,
        'username' => $row['username'],
        'password' => $row['password'],
        'mobile' => $row['mobile'],
        'email' => $row['email'],
        'userlevel' => $row['userlevel'],
        'allowed_fy' => isset($row['allowed_fy']) ? $row['allowed_fy'] : '',
        'Actions' => $actionBtn
    );
}
}

echo json_encode($output);

?>