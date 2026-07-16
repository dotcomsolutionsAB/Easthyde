<?php

$start = $_SESSION['start'];
$end = $_SESSION['end']; 

$start_year = date('Y', strtotime($start));
$end_year = date('Y', strtotime($end));

$year = $start_year.'-'.substr($end, 2,2);

$product_name = $_REQUEST['pr'];
$name = $_REQUEST['pr'];
$_SESSION['pd_product_name'] = $product_name;

// $sql_year = "SELECT * FROM year WHERE current = '1'";
// $query_year = $db->query($sql_year);
// $row_year = $query_year->fetch_assoc();

// $year = $row_year['year'];
// $start = $row_year['start'];
// $end = $row_year['end'];

// echo $year;
$sql_pr = "SELECT * FROM product WHERE name = '$product_name'";
$query_pr = $db->query($sql_pr);
$row_pr = $query_pr->fetch_assoc();

$image_arr = explode(",",$row_pr['images']);
$image_len = sizeof($image_arr);
$image = $image_arr[0];
$pdf = $row_pr['pdf'];


$id = $row_pr['id'];
$group = $row_pr['group'];
$group = str_replace(" ","_",$group);

$new_opening_stock = json_decode($row_pr['new_opening_stock'],true);
$len = sizeof($new_opening_stock['year']);
// echo $new_opening_stock['year'][1];

for($i=0;$i<$len;$i++)
{
    if($new_opening_stock['year'][$i] == $year)
    {
        $opening_stock = $new_opening_stock['stock'][$i];
    }
}
// $opening_stock = $row_pr['opening_stock'];
$stock = $opening_stock;

$outward_stock=0;
$sql_tmp = "SELECT * FROM sales_invoice WHERE items LIKE '%$name%' AND `si_date` BETWEEN '$start' AND '$end'";
$query_tmp = $db->query($sql_tmp);
while($row_tmp = $query_tmp->fetch_assoc()){
    $items = json_decode($row_tmp['items'], true);
    $len = sizeof($items['product']);
    for($i=0;$i<$len;$i++){
        if($items['product'][$i] == $name)
        {
            // $outward_stock += $items['quantity'][$i];
            if($row_tmp['series'] == 'SECONDARY' ){
                $outward_stock += $items['effective_quantity'][$i];
            }else{
                $outward_stock += $items['quantity'][$i];
            }
        }
    }
}

$inward_stock=0;
$sql_tmp = "SELECT * FROM purchase_invoice WHERE items LIKE '%$name%' AND `pi_date` BETWEEN '$start' AND '$end'";
$query_tmp = $db->query($sql_tmp);
while($row_tmp = $query_tmp->fetch_assoc()){
    $items = json_decode($row_tmp['items'], true);
    $len = sizeof($items['product']);
    for($i=0;$i<$len;$i++){
        if($items['product'][$i] == $name)
        {
            $inward_stock += $items['quantity'][$i];
        }
    }
}

// Sales
$sql_tmp = "SELECT * FROM sales_invoice WHERE items LIKE '%$name%' AND `si_date` BETWEEN '$start' AND '$end'";
$query_tmp = $db->query($sql_tmp);
while($row_tmp = $query_tmp->fetch_assoc()){
    $items = json_decode($row_tmp['items'], true);
    $len = sizeof($items['product']);
    for($i=0;$i<$len;$i++){
        if($items['product'][$i] == $name)
        {
            if($row_tmp['series'] == 'SECONDARY' ){
                $stock -= $items['effective_quantity'][$i];
            }else{
                $stock -= $items['quantity'][$i];
            }
        }
    }
}

// Sales
$sql_tmp = "SELECT * FROM sales_order WHERE items LIKE '%$name%' AND collected = '1' AND `status` = '0' AND `so_date` BETWEEN '$start' AND '$end'";
$query_tmp = $db->query($sql_tmp);
while($row_tmp = $query_tmp->fetch_assoc()){
    $items = json_decode($row_tmp['items'], true);
    $len = sizeof($items['product']);
    for($i=0;$i<$len;$i++){
        if($items['product'][$i] == $name)
        {
            $stock -= $items['quantity'][$i];
            $outward_stock += $items['quantity'][$i];
        }
    }
}



// Purchase
$sql_tmp = "SELECT * FROM purchase_invoice WHERE items LIKE '%$name%' AND `pi_date` BETWEEN '$start' AND '$end'";
$query_tmp = $db->query($sql_tmp);
while($row_tmp = $query_tmp->fetch_assoc()){
    $items = json_decode($row_tmp['items'], true);
    $len = sizeof($items['product']);
    for($i=0;$i<$len;$i++){
        if($items['product'][$i] == $name)
        {
            $stock += $items['quantity'][$i];
        }
    }
}

$pr_search="\"".$name."\"";

// Assemblies
    $sql_tmp = "SELECT * FROM assembly_operation WHERE composite = '$name' AND `operation` = 'Assembled' AND `log_date` BETWEEN '$start' AND '$end'";
    $query_tmp = $db->query($sql_tmp);
    while($row_tmp = $query_tmp->fetch_assoc()){
        $stock += $row_tmp['quantity'];
    }

    $sql_tmp = "SELECT * FROM assembly_operation WHERE items LIKE '%$pr_search%' AND `operation` = 'Assembled' AND `log_date` BETWEEN '$start' AND '$end'";
    $query_tmp = $db->query($sql_tmp);
    while($row_tmp = $query_tmp->fetch_assoc()){
        $items = json_decode($row_tmp['items'], true);
        $len = sizeof($items['product']);
        for($i=0;$i<$len;$i++){
            if($items['product'][$i] == $name)
            {
                $qty = $row_tmp['quantity'] * $items['quantity'][$i];
                $stock -= $qty;
            }
        }
    }

    // Disassemble
    $sql_tmp = "SELECT * FROM assembly_operation WHERE composite = '$name' AND `operation` = 'Disassembled' AND `log_date` BETWEEN '$start' AND '$end'";
    $query_tmp = $db->query($sql_tmp);
    while($row_tmp = $query_tmp->fetch_assoc()){
        $stock -= $row_tmp['quantity'];
    }

    $sql_tmp = "SELECT * FROM assembly_operation WHERE items LIKE '%$pr_search%' AND `operation` = 'Disassembled' AND `log_date` BETWEEN '$start' AND '$end'";
    $query_tmp = $db->query($sql_tmp);
    while($row_tmp = $query_tmp->fetch_assoc()){
        $items = json_decode($row_tmp['items'], true);
        $len = sizeof($items['product']);
        for($i=0;$i<$len;$i++){
            if($items['product'][$i] == $name)
            {
                $qty = $row_tmp['quantity'] * $items['quantity'][$i];
                $stock += $qty;
            }
        }
    }

setlocale(LC_MONETARY, 'en_IN');

?>

<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

        <div class="kt-grid kt-grid--desktop kt-grid--ver kt-grid--ver-desktop kt-app">
            <div class="kt-grid__item kt-app__toggle kt-app__aside" style="width:35%">
                <div class="kt-portlet kt-portlet--height-fluid-" >
                    <div class="kt-portlet__head kt-portlet__head--noborder">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">

                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <!-- <a href="#" class="btn btn-clean btn-sm btn-icon btn-icon-md" data-toggle="dropdown">
                                <i class="flaticon-more-1"></i>
                            </a> -->
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        <!--begin::Widget -->
                        <div class="kt-widget kt-widget--user-profile-2">
                            <div class="kt-widget__head" style="margin-bottom: 20px;">
                                <div class="kt-widget__media">
                                    <?php 
                                    $src = '../assets/vendor/file-manager/files/'.strtoupper($group).'/'.$image;
                                    if(@getimagesize($src)){?>
                                        <img src="<?php echo $src; ?>" alt="image" style="width: 90px; height:90px;">
                                    <?php } else { ?>
                                        <span class="kt-badge kt-badge--info kt-badge--xl kt-badge--rounded" style="width: 90px; height:90px; font-size: 53px;">
                                            <?php 
                                                echo 'A';
                                            ?>
                                        </span>
                                    <?php } ?>
                                    </span>
                                </div>
                                <div class="kt-widget__info">
                                    <div class="kt-widget__section">
                                        <a href="#" class="kt-widget__username">
                                            <?php echo $product_name; ?>
                                            <!-- <i class="flaticon2-correct kt-font-success"></i> -->
                                        </a>
                                        <span class="kt-widget__desc">
                                            <?php echo $row_pr['description']; ?><br/>
                                            HSN Code : <?php echo $row_pr['hsn']; ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="kt-widget__body">
                                <div class="kt-widget__content">
                                    <div class="kt-widget__stats kt-margin-r-20">
                                        <div class="kt-widget__icon">
                                            <i class="flaticon-business"></i>
                                        </div>
                                        <div class="kt-widget__details">
                                            <span class="kt-widget__title">Purchase</span>
                                            <span class="kt-widget__value"><span>Rs. </span><?php echo money_format('%!i', $row_pr['cost']); ?></span>
                                        </div>
                                    </div>

                                    <div class="kt-widget__stats">
                                        <div class="kt-widget__icon">
                                            <i class="flaticon-pie-chart"></i>
                                        </div>
                                        <div class="kt-widget__details">
                                            <span class="kt-widget__title">Sales</span>
                                            <span class="kt-widget__value"><span>Rs. </span><?php echo money_format('%!i', $row_pr['rate']); ?></span>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="kt-widget__content">
                                    <a style="margin-right:5px;" href="javascript:;" data-toggle="modal" data-target="#kt_modal_product_details_whatsapp" onclick="Wa_product_details(<?php echo $id; ?>)" class="btn btn-sm btn-icon btn-success btn-icon-lg"><i class="flaticon-whatsapp"></i></a>
                                    <?php
                                        $flag = 0;

                                        $mydir = "../assets/vendor/file-manager/pdf/".$group."/";
                                        $myfiles = glob($mydir."/*");
                                        $sub_child_len = sizeof($myfiles);

                                        for($k=0;$k<$sub_child_len;$k++){
                                            
                                            $fileName = $myfiles[$k];
                                            $ext = pathinfo($fileName, PATHINFO_EXTENSION);
                                            $fileName_without_ext = basename($fileName);
                                            if($fileName_without_ext == $pdf)
                                            {
                                                $flag = 1;
                                            }
                                        }

                                        if($flag == 1 )
                                        {

                                            $mydir = "../assets/vendor/file-manager/pdf/".$group."/";
                                            $mydir =  $mydir.$pdf;

                                            ?>

                                            <a style="margin-right:5px;" href="<?php echo $mydir; ?>" target="_blank" class="btn btn-sm btn-icon btn-danger btn-icon-md"><i class="fa fa-file-pdf"></i></a>
                                            
                                            
                                    <?php } ?>

                                    <!-- <a style="margin-right:5px;" href="#" class="btn btn-sm btn-icon btn-brand btn-icon-md"><i class="flaticon2-send"></i></a>   -->
                                    <!-- <a style="margin-right:5px;" href="/assets/custom/client_ledger_print.php?id=<?php echo $id;?>" class="btn btn-sm btn-icon btn-danger btn-icon-md"><i class="fa fa-file-pdf"></i></a> -->
                                </div>



                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="kt-portlet kt-iconbox kt-iconbox--primary kt-iconbox--animate-slower">
                            <div class="kt-portlet__body" style="padding: 1px;">
                                <div class="kt-iconbox__body">
                                    <div class="kt-iconbox__icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"></rect>
                                                <path d="M2.56066017,10.6819805 L4.68198052,8.56066017 C5.26776695,7.97487373 6.21751442,7.97487373 6.80330086,8.56066017 L8.9246212,10.6819805 C9.51040764,11.267767 9.51040764,12.2175144 8.9246212,12.8033009 L6.80330086,14.9246212 C6.21751442,15.5104076 5.26776695,15.5104076 4.68198052,14.9246212 L2.56066017,12.8033009 C1.97487373,12.2175144 1.97487373,11.267767 2.56066017,10.6819805 Z M14.5606602,10.6819805 L16.6819805,8.56066017 C17.267767,7.97487373 18.2175144,7.97487373 18.8033009,8.56066017 L20.9246212,10.6819805 C21.5104076,11.267767 21.5104076,12.2175144 20.9246212,12.8033009 L18.8033009,14.9246212 C18.2175144,15.5104076 17.267767,15.5104076 16.6819805,14.9246212 L14.5606602,12.8033009 C13.9748737,12.2175144 13.9748737,11.267767 14.5606602,10.6819805 Z" fill="#000000" opacity="0.3"></path>
                                                <path d="M8.56066017,16.6819805 L10.6819805,14.5606602 C11.267767,13.9748737 12.2175144,13.9748737 12.8033009,14.5606602 L14.9246212,16.6819805 C15.5104076,17.267767 15.5104076,18.2175144 14.9246212,18.8033009 L12.8033009,20.9246212 C12.2175144,21.5104076 11.267767,21.5104076 10.6819805,20.9246212 L8.56066017,18.8033009 C7.97487373,18.2175144 7.97487373,17.267767 8.56066017,16.6819805 Z M8.56066017,4.68198052 L10.6819805,2.56066017 C11.267767,1.97487373 12.2175144,1.97487373 12.8033009,2.56066017 L14.9246212,4.68198052 C15.5104076,5.26776695 15.5104076,6.21751442 14.9246212,6.80330086 L12.8033009,8.9246212 C12.2175144,9.51040764 11.267767,9.51040764 10.6819805,8.9246212 L8.56066017,6.80330086 C7.97487373,6.21751442 7.97487373,5.26776695 8.56066017,4.68198052 Z" fill="#000000"></path>
                                            </g>
                                        </svg>                  
                                    </div>
                                    <div class="kt-iconbox__desc">
                                        <h3 class="kt-iconbox__title">
                                            <a class="kt-link" href="#"><?php echo $opening_stock; ?> <?php echo $row_pr['unit']; ?></a>
                                        </h3>
                                        <div class="kt-iconbox__content">
                                            Opening Stock
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="kt-portlet kt-iconbox kt-iconbox--warning kt-iconbox--animate-slower">
                            <div class="kt-portlet__body" style="padding: 1px;">
                                <div class="kt-iconbox__body">
                                    <div class="kt-iconbox__icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"></rect>
                                                <path d="M2.56066017,10.6819805 L4.68198052,8.56066017 C5.26776695,7.97487373 6.21751442,7.97487373 6.80330086,8.56066017 L8.9246212,10.6819805 C9.51040764,11.267767 9.51040764,12.2175144 8.9246212,12.8033009 L6.80330086,14.9246212 C6.21751442,15.5104076 5.26776695,15.5104076 4.68198052,14.9246212 L2.56066017,12.8033009 C1.97487373,12.2175144 1.97487373,11.267767 2.56066017,10.6819805 Z M14.5606602,10.6819805 L16.6819805,8.56066017 C17.267767,7.97487373 18.2175144,7.97487373 18.8033009,8.56066017 L20.9246212,10.6819805 C21.5104076,11.267767 21.5104076,12.2175144 20.9246212,12.8033009 L18.8033009,14.9246212 C18.2175144,15.5104076 17.267767,15.5104076 16.6819805,14.9246212 L14.5606602,12.8033009 C13.9748737,12.2175144 13.9748737,11.267767 14.5606602,10.6819805 Z" fill="#000000" opacity="0.3"></path>
                                                <path d="M8.56066017,16.6819805 L10.6819805,14.5606602 C11.267767,13.9748737 12.2175144,13.9748737 12.8033009,14.5606602 L14.9246212,16.6819805 C15.5104076,17.267767 15.5104076,18.2175144 14.9246212,18.8033009 L12.8033009,20.9246212 C12.2175144,21.5104076 11.267767,21.5104076 10.6819805,20.9246212 L8.56066017,18.8033009 C7.97487373,18.2175144 7.97487373,17.267767 8.56066017,16.6819805 Z M8.56066017,4.68198052 L10.6819805,2.56066017 C11.267767,1.97487373 12.2175144,1.97487373 12.8033009,2.56066017 L14.9246212,4.68198052 C15.5104076,5.26776695 15.5104076,6.21751442 14.9246212,6.80330086 L12.8033009,8.9246212 C12.2175144,9.51040764 11.267767,9.51040764 10.6819805,8.9246212 L8.56066017,6.80330086 C7.97487373,6.21751442 7.97487373,5.26776695 8.56066017,4.68198052 Z" fill="#000000"></path>
                                            </g>
                                        </svg>                  
                                    </div>
                                    <div class="kt-iconbox__desc">
                                        <h3 class="kt-iconbox__title">
                                            <a class="kt-link" href="#"><?php echo $inward_stock; ?> <?php echo $row_pr['unit']; ?></a>
                                        </h3>
                                        <div class="kt-iconbox__content">
                                            Inward Stock
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="kt-portlet kt-iconbox kt-iconbox--danger kt-iconbox--animate-slower">
                            <div class="kt-portlet__body" style="padding: 1px;">
                                <div class="kt-iconbox__body">
                                    <div class="kt-iconbox__icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"></rect>
                                                <path d="M2.56066017,10.6819805 L4.68198052,8.56066017 C5.26776695,7.97487373 6.21751442,7.97487373 6.80330086,8.56066017 L8.9246212,10.6819805 C9.51040764,11.267767 9.51040764,12.2175144 8.9246212,12.8033009 L6.80330086,14.9246212 C6.21751442,15.5104076 5.26776695,15.5104076 4.68198052,14.9246212 L2.56066017,12.8033009 C1.97487373,12.2175144 1.97487373,11.267767 2.56066017,10.6819805 Z M14.5606602,10.6819805 L16.6819805,8.56066017 C17.267767,7.97487373 18.2175144,7.97487373 18.8033009,8.56066017 L20.9246212,10.6819805 C21.5104076,11.267767 21.5104076,12.2175144 20.9246212,12.8033009 L18.8033009,14.9246212 C18.2175144,15.5104076 17.267767,15.5104076 16.6819805,14.9246212 L14.5606602,12.8033009 C13.9748737,12.2175144 13.9748737,11.267767 14.5606602,10.6819805 Z" fill="#000000" opacity="0.3"></path>
                                                <path d="M8.56066017,16.6819805 L10.6819805,14.5606602 C11.267767,13.9748737 12.2175144,13.9748737 12.8033009,14.5606602 L14.9246212,16.6819805 C15.5104076,17.267767 15.5104076,18.2175144 14.9246212,18.8033009 L12.8033009,20.9246212 C12.2175144,21.5104076 11.267767,21.5104076 10.6819805,20.9246212 L8.56066017,18.8033009 C7.97487373,18.2175144 7.97487373,17.267767 8.56066017,16.6819805 Z M8.56066017,4.68198052 L10.6819805,2.56066017 C11.267767,1.97487373 12.2175144,1.97487373 12.8033009,2.56066017 L14.9246212,4.68198052 C15.5104076,5.26776695 15.5104076,6.21751442 14.9246212,6.80330086 L12.8033009,8.9246212 C12.2175144,9.51040764 11.267767,9.51040764 10.6819805,8.9246212 L8.56066017,6.80330086 C7.97487373,6.21751442 7.97487373,5.26776695 8.56066017,4.68198052 Z" fill="#000000"></path>
                                            </g>
                                        </svg>                  
                                    </div>
                                    <div class="kt-iconbox__desc">
                                        <h3 class="kt-iconbox__title">
                                            <a class="kt-link" href="#"><?php echo $outward_stock; ?> <?php echo $row_pr['unit']; ?></a>
                                        </h3>
                                        <div class="kt-iconbox__content">
                                            Outward Stock
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="kt-portlet kt-iconbox kt-iconbox--success kt-iconbox--animate-slower">
                            <div class="kt-portlet__body" style="padding: 1px;">
                                <div class="kt-iconbox__body">
                                    <div class="kt-iconbox__icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"></rect>
                                                <path d="M2.56066017,10.6819805 L4.68198052,8.56066017 C5.26776695,7.97487373 6.21751442,7.97487373 6.80330086,8.56066017 L8.9246212,10.6819805 C9.51040764,11.267767 9.51040764,12.2175144 8.9246212,12.8033009 L6.80330086,14.9246212 C6.21751442,15.5104076 5.26776695,15.5104076 4.68198052,14.9246212 L2.56066017,12.8033009 C1.97487373,12.2175144 1.97487373,11.267767 2.56066017,10.6819805 Z M14.5606602,10.6819805 L16.6819805,8.56066017 C17.267767,7.97487373 18.2175144,7.97487373 18.8033009,8.56066017 L20.9246212,10.6819805 C21.5104076,11.267767 21.5104076,12.2175144 20.9246212,12.8033009 L18.8033009,14.9246212 C18.2175144,15.5104076 17.267767,15.5104076 16.6819805,14.9246212 L14.5606602,12.8033009 C13.9748737,12.2175144 13.9748737,11.267767 14.5606602,10.6819805 Z" fill="#000000" opacity="0.3"></path>
                                                <path d="M8.56066017,16.6819805 L10.6819805,14.5606602 C11.267767,13.9748737 12.2175144,13.9748737 12.8033009,14.5606602 L14.9246212,16.6819805 C15.5104076,17.267767 15.5104076,18.2175144 14.9246212,18.8033009 L12.8033009,20.9246212 C12.2175144,21.5104076 11.267767,21.5104076 10.6819805,20.9246212 L8.56066017,18.8033009 C7.97487373,18.2175144 7.97487373,17.267767 8.56066017,16.6819805 Z M8.56066017,4.68198052 L10.6819805,2.56066017 C11.267767,1.97487373 12.2175144,1.97487373 12.8033009,2.56066017 L14.9246212,4.68198052 C15.5104076,5.26776695 15.5104076,6.21751442 14.9246212,6.80330086 L12.8033009,8.9246212 C12.2175144,9.51040764 11.267767,9.51040764 10.6819805,8.9246212 L8.56066017,6.80330086 C7.97487373,6.21751442 7.97487373,5.26776695 8.56066017,4.68198052 Z" fill="#000000"></path>
                                            </g>
                                        </svg>                  
                                    </div>
                                    <div class="kt-iconbox__desc">
                                        <h3 class="kt-iconbox__title">
                                            <a class="kt-link" href="#"><?php echo $stock; ?> <?php echo $row_pr['unit']; ?></a>
                                        </h3>
                                        <div class="kt-iconbox__content">
                                            Stock in Hand
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="kt-grid__item kt-app__toggle kt-app__content" style="width:65%">
                <div class="kt-portlet kt-portlet--mobile">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Product Timeline
                            </h3>
                            <div class="col-md-offset-6 col-md-6">
                            <div class="kt-input-icon kt-input-icon--left">
                                <input type="text" class="form-control" placeholder="Search..." id="search_pd_timeline">
                                <span class="kt-input-icon__icon kt-input-icon__icon--left">
                                    <span><i class="la la-search"></i></span>
                                </span>
                            </div>
                        </div>
                        </div>

                    </div>
                    <div class="kt-portlet__body">
                        <div class="kt-datatable" id="pd_timeline"></div>
                    </div>
                </div>
                <div class="kt-portlet kt-portlet--mobile">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Purchase Details
                            </h3>
                            <div class="col-md-offset-6 col-md-6">
                            <div class="kt-input-icon kt-input-icon--left">
                                <input type="text" class="form-control" placeholder="Search..." id="search_pd_purchase">
                                <span class="kt-input-icon__icon kt-input-icon__icon--left">
                                    <span><i class="la la-search"></i></span>
                                </span>
                            </div>
                        </div>
                        </div>

                    </div>
                    <div class="kt-portlet__body">
                        <div class="kt-datatable" id="pd_purchase"></div>
                    </div>
                </div>
                <div class="kt-portlet kt-portlet--mobile">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Sales Details
                            </h3>
                            <div class="col-md-offset-6 col-md-6">
                            <div class="kt-input-icon kt-input-icon--left">
                                <input type="text" class="form-control" placeholder="Search..." id="search_pd_sales">
                                <span class="kt-input-icon__icon kt-input-icon__icon--left">
                                    <span><i class="la la-search"></i></span>
                                </span>
                            </div>
                        </div>
                        </div>

                    </div>
                    <div class="kt-portlet__body">
                        <div class="kt-datatable" id="pd_sales"></div>
                    </div>
                </div>
                <div class="kt-portlet kt-portlet--mobile">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Quotations
                            </h3>
                            <div class="col-md-offset-6 col-md-6">
                            <div class="kt-input-icon kt-input-icon--left">
                                <input type="text" class="form-control" placeholder="Search..." id="search_pd_quotation">
                                <span class="kt-input-icon__icon kt-input-icon__icon--left">
                                    <span><i class="la la-search"></i></span>
                                </span>
                            </div>
                        </div>
                        </div>

                    </div>
                    <div class="kt-portlet__body">
                        <div class="kt-datatable" id="pd_quotation"></div>
                    </div>
                </div>
                <div class="kt-portlet kt-portlet--mobile">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Enquiries
                            </h3>
                            <div class="col-md-offset-6 col-md-6">
                            <div class="kt-input-icon kt-input-icon--left">
                                <input type="text" class="form-control" placeholder="Search..." id="search_pd_enquiries">
                                <span class="kt-input-icon__icon kt-input-icon__icon--left">
                                    <span><i class="la la-search"></i></span>
                                </span>
                            </div>
                        </div>
                        </div>

                    </div>
                    <div class="kt-portlet__body">
                        <div class="kt-datatable" id="pd_enquiry"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--begin::Send whatsapp Modal-->
<form class="kt-form kt-form--label-right" id="send_product_details_whatsapp">
    <div class="modal fade" id="kt_modal_product_details_whatsapp" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Send Whatsapp</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                        <div class="kt-portlet__body">
                            <input name="pd_whatsapp_id" id="pd_whatsapp_id" class="form-control" style="display: none" type="text">
                            <div class="form-group row" style="margin-bottom: 0">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Mobile No (Separted by comma)</label>
                                        <div class="kt-input-icon">
                                            <input name="pd_whatsapp_number" id="pd_whatsapp_number" placeholder="Enter Whatsapp Number(s)" class="form-control" type="text">
                                        </div>
                                    </div>
                                </div>
                            
                            
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12" >
                                    <div class="form-group">
                                        <label>Message</label>
                                        <textarea class="form-control" placeholder="Whatsapp Message" name="pd_whatsapp_message" id="pd_whatsapp_message" rows='15'></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                            $mydir = "../assets/vendor/file-manager/pdf/".$group."/";
                                
                            if($pdf!='')
                            {
                                $mydir = "../assets/vendor/file-manager/pdf/".$group."/";
                                $mydir = $mydir.$pdf;
                                
                                $test = str_replace('..', 'https://crm.ammarindustrial.in', $mydir);

                        ?>

                        <div class="form-group row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Technical PDF</label>
                                    <div class="kt-input-icon">
                                        <select class="form-control kt-select2" id="technical_pdf" name="technical_pdf">
                                            <option></option>
                                            <option value="<?php echo $test; ?>">Yes</option>
                                            <option value='0'>No</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php 
                            }
                        ?>
                        <select multiple="multiple" class="image-picker show-html" name="images[]" id="images">
                            <?php

                                for($k=0;$k<$image_len;$k++)
                                {

                                    $mydir = "../assets/vendor/file-manager/files/".$group."/";
                                    $mydir = $mydir.$image_arr[$k];
                                    
                                    $test = str_replace('..', 'https://crm.ammarindustrial.in', $mydir);
                            ?>
                                <option data-img-src="<?php echo $mydir; ?>" value="<?php echo $test; ?>"></option>
                            <?php 
                                }
                            ?>
                        </select>
                        <div class="form-group row">
                            <div class="col-sm-1">
                            </div>
                            <div class="col-sm-10">
                                <div class="form-group form-group-last row">
                                    <div class="col-sm-5">
                                        <label class="col-form-label">Upload Files:</label>
                                    </div>
                                    <div class="col-sm-5">
                                        <div class="dropzone dropzone-multi" id="kt_dropzone_pd_whatsapp">
                                                <div class="dropzone-panel">
                                                    <a class="dropzone-select btn btn-label-brand btn-bold btn-sm">Attach file</a>
                                                    <a class="dropzone-upload btn btn-label-brand btn-bold btn-sm">Upload</a>
                                                    <a class="dropzone-remove-all btn btn-label-brand btn-bold btn-sm" id="remove_file_whatsapp">Remove</a>
                                                </div>
                                                <div class="dropzone-items">
                                                    <div class="dropzone-item" style="display:none">
                                                        <div class="dropzone-file">
                                                            <div class="dropzone-filename" title="some_image_file_name.jpg"><span data-dz-name>some_image_file_name.jpg</span> <strong>(<span  data-dz-size>340kb</span>)</strong></div>
                                                            <div class="dropzone-error" data-dz-errormessage></div>
                                                        </div>
                                                        <div class="dropzone-progress">
                                                            <div class="progress">
                                                                <div class="progress-bar kt-bg-brand" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0" data-dz-uploadprogress></div>
                                                            </div>
                                                        </div>
                                                        <div class="dropzone-toolbar">
                                                            <span class="dropzone-start"><i class="flaticon2-arrow"></i></span>
                                                            <span class="dropzone-cancel" data-dz-remove style="display: none;"><i class="flaticon2-cross"></i></span>
                                                            <span class="dropzone-delete" data-dz-remove><i class="flaticon2-cross"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>
                                        </br>
                                        <p class="form-text text-muted">Max file size is 2MB</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-1">
                            </div>
                        </div>
                    <!--end::Form-->
                </div>
                <div class="modal-footer">
                    <button id="pd_whatsapp_submit" type="submit" class="btn btn-primary">Send</button>
                </div>
            </div>
        </div>
    </div>
</form>
<!--end::Send whatapp Modal-->