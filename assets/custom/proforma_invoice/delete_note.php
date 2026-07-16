<?php
    include ("../connect.php");
    include ("../php_replace_improper.php");

    session_start();

    $validator = array("success"=>true, "messages"=>"There was some error saving the records");

    $pr_no = $_REQUEST['member_id'];
    $id = $_REQUEST['index'];

    $sql = "SELECT * FROM proforma WHERE `pr_no` = '$pr_no'";
    $query = $db->query($sql);
    $row = $query->fetch_assoc();

    $new_notes = array("notes"=>array(),"user"=>array(),"timestamp"=>array());

    $notes = json_decode($row['notes'], true);
    $len = sizeof($notes['notes']);

    for($i=0;$i<$len;$i++){
        if($i != $id){
            $new_notes['notes'][] = $notes['notes'][$i];
            $new_notes['user'][] = $notes['user'][$i];
            $new_notes['timestamp'][] = $notes['timestamp'][$i];
        }
    }

    $note=json_encode($new_notes);

    $sql_update = "UPDATE proforma SET `notes` = '$note' WHERE `pr_no` = '$pr_no'";
    $query_update = $db->query($sql_update);

    if($query_update === true)
    {
        $validator['success'] = true;
        $validator['messages'] = "Successfully Added";
    }
    else
    {
        $validator['success'] = false;
        $validator['messages'] = "There was some error saving the records";

    }

    echo json_encode($validator);
?>