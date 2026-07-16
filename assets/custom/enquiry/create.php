<?php
    include ("../connect.php");
    include ("../php_replace_improper.php");

    session_start();

    $log_user = $_SESSION['username'];
    $log_date = date('Y-m-d', strtotime("today"));

    $validator = array("success"=>true, "messages"=>"There was some error saving the records");

    //Edit Switch Variable
    $e_id = $_REQUEST['e_id'];
 
    // Form Fields
    $client             = replace_improper_same($_REQUEST['e_client']);
    $enquiry_no         = replace_improper_same($_REQUEST['enquiry_no']);
    $client_enquiry_no  = replace_improper_same($_REQUEST['client_enquiry_no']);
    $enquiry_date       = date('Y-m-d', strtotime($_REQUEST['enquiry_date']));
    $mode               = replace_improper_same($_REQUEST['enquiry_mode']);
    $status             = $_REQUEST['enquiry_status'];

    $array              = $_REQUEST['enquiry'];
    $l                  = sizeof($array);

    $items = array('product'=>array(),'quantity'=>array(),'desc'=>array(),'long_desc'=>array(),'stock'=>array(),'co_stock'=>array());

    for($i=0;$i<$l;$i++){
        if($array[$i]['e_product_name'] != '' && $array[$i]['e_qty'] != ''){

            $pr = $array[$i]['e_product_name'];
            $sql_temp = "SELECT * FROM product WHERE name = '$pr'";
            $query_temp = $db->query($sql_temp);
            $row_temp = $query_temp->fetch_assoc();

            $items['product'][]     = replace_improper($array[$i]['e_product_name']);
            $items['desc'][]        = replace_improper_same($array[$i]['e_product_description']);
            $items['long_desc'][]   = replace_improper_textarea($array[$i]['e_product_add_description']);
            $items['quantity'][]    = replace_improper_same($array[$i]['e_qty']);
            $items['stock'][]       = replace_improper_same($array[$i]['e_current_stock']);
            $items['co_stock'][]    = replace_improper_same($array[$i]['e_company_stock']);

        }
    }

    $item=json_encode($items);

    if($e_id == '')
    {
        $sql_counter        = "SELECT * FROM counter WHERE `key` = 'enquiry'";
        $query_counter      = $db->query($sql_counter);
        $row_counter        = $query_counter -> fetch_assoc();
        $row_counter_arr    = json_decode($row_counter['value'], true);

        $enquiry_no         = $row_counter_arr['prefix'][0].str_pad($row_counter_arr['number'][0],4,'0', STR_PAD_LEFT).$row_counter_arr['postfix'][0];
        $row_counter_arr['number'][0] = $row_counter_arr['number'][0] + 1;

        $sql = "INSERT INTO enquiry (`client`,`enquiry_no`,`cl_enquiry_no`,`enquiry_date`,`mode`,`items`,`status`,`log_user`,`log_date`) VALUES ('$client','$enquiry_no','$client_enquiry_no', '$enquiry_date','$mode','$item','$status','$log_user','$log_date')";
        $query = $db->query($sql);

        if($query===true)
        {
            $counter_array = json_encode($row_counter_arr);
            $sql_counter = "UPDATE counter SET `value` = '$counter_array' WHERE `key` = 'enquiry'";
            $query_counter = $db->query($sql_counter);

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
        $sql = "UPDATE enquiry SET `client` = '$client', `enquiry_no`='$enquiry_no',`cl_enquiry_no`='$client_enquiry_no', `enquiry_date`='$enquiry_date',`mode`='$mode',`items`='$item',`status`='$status',`log_user`='$log_user',`log_date`='$log_date' WHERE `id`='$e_id'";
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