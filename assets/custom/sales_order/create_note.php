<?php
    include ("../connect.php");
    include ("../php_replace_improper.php");

    session_start();

    $log_user = $_SESSION['username'] ?? '';
    $log_date = date('Y-m-d H:i:s',time());

    $validator = array("success"=>true, "messages"=>"There was some error saving the records");

    $so_no = $_REQUEST['an_so_no'] ?? '';
    $notes_enter = $_REQUEST['add_so_note'] ?? '';

    $sql = "SELECT * FROM sales_order WHERE `so_no` = '$so_no'";
    $query = $db->query($sql);
    $row = ($query && ($tmp = $query->fetch_assoc())) ? $tmp : null;
    if (!$row) {
        $validator['success'] = false;
        $validator['messages'] = "Record not found";
        echo json_encode($validator);
        exit;
    }

    $notes = json_decode($row['notes'] ?? '', true);
    if (!is_array($notes)) { $notes = ['notes'=>[], 'user'=>[], 'timestamp'=>[]]; }
    $notes['notes'][] = $notes_enter;
    $notes['user'][] = $log_user;
    $notes['timestamp'][] = $log_date;

    $note=json_encode($notes);

    $sql_update = "UPDATE sales_order SET `notes` = '$note' WHERE `so_no` = '$so_no'";
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
