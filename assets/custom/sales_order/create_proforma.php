<?php
    include ("../connect.php");
    include ("../php_replace_improper.php");

    session_start();

    $validator = array("success"=>true, "messages"=>"There was some error saving the records", "so"=>"");

    $so_id = $_REQUEST['member_id'] ?? '';
    $log_user = $_SESSION['username'] ?? '';
    $log_date = date('Y-m-d', strtotime("today"));

    $sql_counter = "SELECT * FROM counter WHERE `key` = 'proforma'";
    $query_counter = $db->query($sql_counter);
    if (!$query_counter || $query_counter->num_rows === 0) {
        $validator['success'] = false;
        $validator['messages'] = "Counter not found";
        echo json_encode($validator);
        exit;
    }
    $row_counter = $query_counter->fetch_assoc();
    $row_counter_arr = json_decode($row_counter['value'] ?? '', true);
    if (!is_array($row_counter_arr) || !isset($row_counter_arr['prefix'][0], $row_counter_arr['number'][0], $row_counter_arr['postfix'][0])) {
        $validator['success'] = false;
        $validator['messages'] = "Counter not found";
        echo json_encode($validator);
        exit;
    }

    $order_no = $row_counter_arr['prefix'][0].str_pad($row_counter_arr['number'][0],3,'0', STR_PAD_LEFT).$row_counter_arr['postfix'][0];
    $row_counter_arr['number'][0] = $row_counter_arr['number'][0] + 1;

    $sql_fetch = "SELECT * FROM sales_order WHERE so_no = '$so_id'";
    $query_fetch = $db->query($sql_fetch);
    $row_fetch = ($query_fetch && ($tmp = $query_fetch->fetch_assoc())) ? $tmp : null;
    if (!$row_fetch) {
        $validator['success'] = false;
        $validator['messages'] = "Record not found";
        echo json_encode($validator);
        exit;
    }

    $client         = $row_fetch['client_name'];
    $order_no       = $order_no;
    $order_date     = $row_fetch['so_date'];
    $quotation      = $row_fetch['q_no'];
    $client_so_no   = $row_fetch['client_so_no'];
    $item           = $row_fetch['items'];
    $addon          = $row_fetch['addons'];
    $tax            = $row_fetch['tax'];
    $total          = $row_fetch['total'];

    $sql = "INSERT INTO proforma (`client_name`,`so_no`,`so_date`,`q_no`,`client_so_no`,`items`,`addons`,`tax`,`total`,`log_user`,`log_date`) VALUES ('$client','$order_no', '$order_date','$quotation','$client_so_no','$item','$addon','$tax','$total','$log_user','$log_date')";
    $query = $db->query($sql);

    if($query===true)
    {
        $counter_array = json_encode($row_counter_arr);
        $sql_counter = "UPDATE counter SET `value` = '$counter_array' WHERE `key` = 'proforma'";
        $query_counter = $db->query($sql_counter);

        $validator['success'] = true;
        $validator['messages'] = "Successfully Added";
        $validator['so'] = $order_no;
    }
    else
    {
        $validator['success'] = false;
        $validator['messages'] = "There was some error saving the records";

    }


    echo json_encode($validator);
?>
