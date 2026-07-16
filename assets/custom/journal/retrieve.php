<?php
session_start();
require_once "../connect.php";

$pagination = $_REQUEST['pagination'];  
$query_array = $_REQUEST['query'];  
$sort_array = $_REQUEST['sort'];  

$query = $query_array['generalSearch'];

$sql_1 = "SELECT COUNT(*) AS total FROM journal";
$query_1 = $db->query($sql_1);
$row_1 = $query_1->fetch_assoc();

$perpage = $pagination['perpage'];
$start = ($pagination['page']-1)*$perpage;
$pages = $row_1['total'] / $perpage;

$output = array('meta'=> array("page"=> $pagination['page'], "pages"=> $pages, "perpage"=> $perpage,"total"=> $row_1['total'],"sort"=> 'asc', "field"=> 'SN'), 'data' => array());

$count=1;
$sql = "SELECT * FROM journal LIMIT ".$start.','.$perpage;
$query = $db->query($sql);
while($row = $query->fetch_assoc()){

    if($_SESSION['userlevel'] == 'sadmin_df56fdg'){
        $actionBtn = '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-sm" onclick="editJournal(\''.$row['id'].'\')" title="Edit">
                        <i class="flaticon2-paper"></i>
                    </a>
                    <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-sm" data-toggle="modal" data-target="#kt_modal_d_journal" title="Delete" onclick="removeJournal(\''.$row['id'].'\')">
                        <i class="flaticon2-trash"></i>
                    </a>';
    }else{
        $actionBtn = '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-sm" onclick="editJournal(\''.$row['id'].'\')" title="Edit">
                        <i class="flaticon2-paper"></i>
                    </a>';
    }

    $entry = '';

    $items = json_decode($row['items'],true);
    $len = sizeof($items);

    for($i=0;$i<$len;$i++)
    {
        if($items[$i]['debit'] != '' && $items[$i]['debit'] != '0')
        {
            $entry .= $items[$i]['master'].' '.$items[$i]['particulars'].' <strong>Debit: '.$items[$i]['debit'].'</strong><br/>';
        }
        else if($items[$i]['credit'] != '' && $items[$i]['credit'] != '0')
        {
            $entry .= $items[$i]['master'].' '.$items[$i]['particulars'].' <strong>Credit: '.$items[$i]['credit'].'</strong><br/>';
        }
    }

    $output['data'][] = array(      
        'SN' => $count++,
        'Id'=>$row['id'],
        'date' => date('d-m-Y',strtotime($row['date'])),
        'entry' => $entry,
        'Log_user' => $row['log_user'],
        'Log_date' => $row['log_date'],
        'Actions' => $actionBtn
    );
}

echo json_encode($output);

?>