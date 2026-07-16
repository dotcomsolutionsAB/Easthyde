<?php
	include ("../connect.php");
    include ("../php_replace_improper.php");
	session_start();

	$id = $_REQUEST['member_id'];

    $sql_counter = "SELECT * FROM counter WHERE `key` = 'quotation'";
    $query_counter = $db->query($sql_counter);
    $row_counter = $query_counter -> fetch_assoc();
    $row_counter_arr = json_decode($row_counter['value'], true);

    $quotation_no = $row_counter_arr['prefix'][0].str_pad($row_counter_arr['number'][0],4,'0', STR_PAD_LEFT).$row_counter_arr['postfix'][0];
    $row_counter_arr['number'][0] = $row_counter_arr['number'][0] + 1;

    $log_user = $_SESSION['username'];
    $log_date = date('Y-m-d', strtotime("today"));

    $sql_temp = "SELECT * FROM quotation WHERE id='$id'";
    $query_temp = $db->query($sql_temp);
    $row_temp = $query_temp->fetch_assoc();

    $client=$row_temp['client'];
    $quotation_date=$row_temp['quotation_date'];
    $quotation_top=$row_temp['quotation_top'];
    $item=$row_temp['items'];
    $addon=$row_temp['addons'];
    $status=$row_temp['status'];
    $terms=$row_temp['terms'];
    
    $sql="INSERT INTO quotation (`client`,`quotation_no`,`quotation_date`,`quotation_top`,`items`,`addons`,`terms`,`status`,`log_user`,`log_date`) VALUES ('$client','$quotation_no', '$quotation_date','$quotation_top','$item','$addon','$terms','$status','$log_user','$log_date')";
     $query = $db->query($sql);

    if($query===true)
    {
        $counter_array = json_encode($row_counter_arr);
        $sql_counter = "UPDATE counter SET `value` = '$counter_array' WHERE `key` = 'quotation'";
        $query_counter = $db->query($sql_counter);

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