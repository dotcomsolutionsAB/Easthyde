<?php
    include ("../connect.php");
    include ("../php_replace_improper.php");

    session_start();

    $log_user = $_SESSION['username'] ?? '';
    $log_date = date('Y-m-d', strtotime("today"));

    $validator = array("success"=>false, "messages"=>"There was some error saving the records");

    //Edit Switch Variable
    $e_id = $_REQUEST['e_id'] ?? '';
 
    // Form Fields
    $client             = replace_improper_same($_REQUEST['e_client'] ?? '');
    $enquiry_no         = replace_improper_same($_REQUEST['enquiry_no'] ?? '');
    $client_enquiry_no  = replace_improper_same($_REQUEST['client_enquiry_no'] ?? '');
    $enquiry_date_raw   = $_REQUEST['enquiry_date'] ?? '';
    $enquiry_date       = ($enquiry_date_raw !== '') ? date('Y-m-d', strtotime((string)$enquiry_date_raw)) : '';
    $mode               = replace_improper_same($_REQUEST['enquiry_mode'] ?? '');
    $status             = $_REQUEST['enquiry_status'] ?? '';

    $array              = $_REQUEST['enquiry'] ?? [];
    if (!is_array($array)) { $array = []; }
    $l                  = sizeof($array);

    $items = array('product'=>array(),'quantity'=>array(),'desc'=>array(),'long_desc'=>array(),'stock'=>array(),'co_stock'=>array());

    for($i=0;$i<$l;$i++){
        $row = is_array($array[$i] ?? null) ? $array[$i] : [];
        if(($row['e_product_name'] ?? '') != '' && ($row['e_qty'] ?? '') != ''){

            $pr = $row['e_product_name'];
            $sql_temp = "SELECT * FROM product WHERE name = '$pr'";
            $query_temp = $db->query($sql_temp);
            $row_temp = ($query_temp && ($tmp = $query_temp->fetch_assoc())) ? $tmp : null;

            $items['product'][]     = replace_improper($row['e_product_name'] ?? '');
            $items['desc'][]        = replace_improper_same($row['e_product_description'] ?? '');
            $items['long_desc'][]   = replace_improper_textarea($row['e_product_add_description'] ?? '');
            $items['quantity'][]    = replace_improper_same($row['e_qty'] ?? '');
            $items['stock'][]       = replace_improper_same($row['e_current_stock'] ?? '');
            $items['co_stock'][]    = replace_improper_same($row['e_company_stock'] ?? '');

        }
    }

    $item=json_encode($items);

    if($e_id == '')
    {
        $sql_counter        = "SELECT * FROM counter WHERE `key` = 'enquiry'";
        $query_counter      = $db->query($sql_counter);
        if ($query_counter && $query_counter->num_rows > 0) {
        $row_counter        = $query_counter->fetch_assoc();
        $row_counter_arr    = json_decode($row_counter['value'] ?? '', true);

        if(is_array($row_counter_arr) && isset($row_counter_arr['prefix'][0], $row_counter_arr['number'][0], $row_counter_arr['postfix'][0])){
            $enquiry_no         = $row_counter_arr['prefix'][0].str_pad((string)$row_counter_arr['number'][0],4,'0', STR_PAD_LEFT).$row_counter_arr['postfix'][0];
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
        } else {
            $validator['success'] = false;
            $validator['messages'] = "Enquiry counter is not configured correctly.";
        }
        } else {
            $validator['success'] = false;
            $validator['messages'] = "Enquiry counter not found.";
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
