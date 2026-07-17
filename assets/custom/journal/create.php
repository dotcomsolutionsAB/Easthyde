<?php
    include ("../connect.php");
    include ("../php_replace_improper.php");

    session_start();

    $validator = array("success"=>false, "messages"=>"There was some error saving the records", "q_no"=>"");

    $id = $_REQUEST['journal_edit_id'] ?? '';
    $quotation_no = $_REQUEST['quotation_no'] ?? '';

    $log_user = $_SESSION['username'] ?? '';
    $log_date = date('Y-m-d', strtotime("today"));

    $journal_date_raw = $_REQUEST['journal_date'] ?? '';
    $date = ($journal_date_raw !== '') ? date('Y-m-d', strtotime((string)$journal_date_raw)) : '';
    
    $array = $_REQUEST['journal'] ?? [];
    if (!is_array($array)) { $array = []; }
    $l = sizeof($array);

    $items=array();

    for($i=0;$i<$l;$i++){
        $row = is_array($array[$i] ?? null) ? $array[$i] : [];
        if(($row['journal_master'] ?? '') != ''){

            $temp = array();

            $temp['master']      = replace_improper($row['journal_master'] ?? '');
            $temp['particular']  = replace_improper($row['journal_particular'] ?? '');
            $temp['debit']       = replace_improper_amount($row['journal_debit'] ?? '');
            $temp['credit']      = replace_improper_amount($row['journal_credit'] ?? '');

            $items[] = $temp;
        }
    }
    $item=json_encode($items);


    if($id == '')
    {

        $sql = "INSERT INTO journal (`date`,`items`,`log_user`,`log_date`) VALUES ('$date','$item','$log_user','$log_date')";
        $query = $db->query($sql);

        if($query===true)
        {

            $validator['success'] = true;
            $validator['messages'] = "Successfully Added";
            $validator['q_no'] = $quotation_no;
        }
        else
        {
            $validator['success'] = false;
            $validator['messages'] = "There was some error saving the records";

        }
    }
    else{
        $sql = "UPDATE journal SET `date` = '$date', `items`='$item', `log_user`='$log_user', `log_date`='$log_date' WHERE `id`='$id'";
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
