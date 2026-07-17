<?php
    include ("../connect.php");
    include ("../php_replace_improper.php");

    session_start();

    $array = $_REQUEST['assemblies'] ?? [];
    if (!is_array($array)) { $array = []; }
    $l = sizeof($array);

    $composite = replace_improper($_REQUEST['composite_product_2'] ?? '');
    $log_user = $_SESSION['username'] ?? '';
    $log_date = date('Y-m-d', strtotime("today"));
    $validator = array("success"=>false, "messages"=>"There was some error saving the records");

    $spares=array('product'=>array(),'quantity'=>array());

    for($i=0;$i<$l;$i++){
        $row = is_array($array[$i] ?? null) ? $array[$i] : [];
        if(($row['a_product_name'] ?? '') != '' && ($row['a_qty'] ?? '') != ''){

            $spares['product'][] = replace_improper($row['a_product_name'] ?? '');
            $spares['quantity'][] = replace_improper($row['a_qty'] ?? '');
        }
    }
    $spare=json_encode($spares);

    $sql = "INSERT INTO assembly (`composite`,`spares`,`log_user`,`log_date`) VALUES ('$composite','$spare','$log_user','$log_date')";
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
    echo json_encode($validator);
?>
