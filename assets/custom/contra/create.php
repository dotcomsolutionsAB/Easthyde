<?php
    include ("../connect.php");
    include ("../php_replace_improper.php");

    session_start();

    $log_user = $_SESSION['username'] ?? '';
    $log_date = date('Y-m-d', strtotime("today"));

    $validator = array("success"=>true, "messages"=>"There was some error saving the records");

    //Edit Switch Variable
    $id = $_REQUEST['contra_edit_id'] ?? '';
 
    // Form Fields
    $contra_date_raw    = $_REQUEST['contra_date'] ?? '';
    $date               = ($contra_date_raw !== '') ? date('Y-m-d', strtotime((string)$contra_date_raw)) : '';
    $transfer_from      = replace_improper_same($_REQUEST['contra_bank_1'] ?? '');
    $transfer_to        = replace_improper_same($_REQUEST['contra_bank_2'] ?? '');
    $amount             = replace_improper_same($_REQUEST['contra_amount'] ?? '');

    if($id == '')
    {

        $sql = "INSERT INTO contra_entry (`date`,`transfer_from`,`transfer_to`,`amount`,`log_user`,`log_date`) VALUES ('$date','$transfer_from','$transfer_to', '$amount','$log_user','$log_date')";
        $query = $db->query($sql);

        if($query===true)
        {
            
            $validator['success'] = true;
            $validator['messages'] = "Successfully Added";
        }
        else
        {
            $validator['success'] = false;
            $validator['messages'] = "There was some error saving the records";

        }
    }
    else
    {
        $sql = "UPDATE contra_entry SET `date` = '$date', `transfer_from`='$transfer_from',`transfer_to`='$transfer_to', `amount`='$amount',`log_user`='$log_user',`log_date`='$log_date' WHERE `id`='$id'";
        $query = $db->query($sql);

        if($query===true)
        {
            $validator['success'] = true;
            $validator['messages'] = "Successfully Updated";
        }
        else
        {
            $validator['success'] = false;
            $validator['messages'] = "There was some error saving the records";

        }
    }
    echo json_encode($validator);
?>
