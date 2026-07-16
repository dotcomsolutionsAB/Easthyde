<?php
	include ("../connect.php");
    include ("../php_replace_improper.php");
	
	session_start();

	$log_user = $_SESSION['username'];
	$log_date = date('Y-m-d', strtotime("today"));

	$id = $_REQUEST['edit_product_id'];	;	
	$name = replace_improper($_REQUEST['edit_product_name']);
	$description = replace_improper_same($_REQUEST['edit_product_description']);
	$aliases = replace_improper_same($_REQUEST['edit_product_alias']);
	$moq = replace_improper_same($_REQUEST['edit_product_moq']);
	$group_name = replace_improper($_REQUEST['edit_product_group_name']);	
	$vendor_name = replace_improper($_REQUEST['edit_product_vendor_name']);	
	$category = replace_improper($_REQUEST['edit_product_category']);
	$sub_category = replace_improper($_REQUEST['edit_product_sub_category']);
	$unit = replace_improper($_REQUEST['edit_product_unit']);
	$rate = $_REQUEST['edit_product_rate'];
	$cost = $_REQUEST['edit_product_cost'];
	$tax = $_REQUEST['edit_product_tax'];
	$hsn = $_REQUEST['edit_product_hsn'];
	$opening_stock = $_REQUEST['edit_product_opening_stock'];
	$pdf = $_REQUEST['edit_product_pdf'];
	$images = $_REQUEST['edit_product_images'];


	$updated_price = 0;

	if (isset($_REQUEST['edit_product_update'])) {
	    $updated_price = 1;
	}

	$updated_cost = 0;

	if (isset($_REQUEST['edit_cost_update'])) {
	    $updated_cost = 1;
	}

	$sql = "SELECT * FROM product WHERE id = '$id'";
    $query = $db->query($sql);
    $row = $query->fetch_assoc();

    $orig_name = $row['name'];

    $opening_stock_current = json_decode($row['new_opening_stock'],true);
    $len = sizeof($opening_stock_current['year']);

    $sql_year = "SELECT * FROM year WHERE current = '1'";
    $query_year = $db->query($sql_year);
    $row_year = $query_year->fetch_assoc();

    $year = $row_year['year'];

    for($i=0;$i<$len;$i++)
    {
    	if($opening_stock_current['year'][$i] == $year)
    	{
    		$opening_stock_current['stock'][$i] = $opening_stock;
    	}
    }

    $new_opening_stock = json_encode($opening_stock_current);


	if($moq=='')
	{
		$moq = 0;
	}

	$validator = array("success"=>true, "messages"=>"There was some error saving the records");

	if($updated_price == 1 && $updated_cost == 1) {
		$sql = "UPDATE product SET `name`='$name',`group`='$group_name',`vendor`='$vendor_name',`description`='$description',`aliases`='$aliases',`category`='$category',`sub_category`='$sub_category',`unit`='$unit',`rate`='$rate',`cost`='$cost',`tax`='$tax',`hsn`='$hsn',`opening_stock`='',`moq`='$moq',`pdf`='$pdf',`images`='$images',`updated_price`='$updated_price',`updated_price_date`='$log_date',`updated_cost`='$updated_cost',`updated_cost_date`='$log_date',`log_date`='$log_date' WHERE `id` = '$id'";
	$query = $db->query($sql);
	} else if($updated_price == 1) {
		$sql = "UPDATE product SET `name`='$name',`group`='$group_name',`vendor`='$vendor_name',`description`='$description',`aliases`='$aliases',`category`='$category',`sub_category`='$sub_category',`unit`='$unit',`rate`='$rate',`cost`='$cost',`tax`='$tax',`hsn`='$hsn',`opening_stock`='',`moq`='$moq',`pdf`='$pdf',`images`='$images',`updated_price`='$updated_price',`updated_price_date`='$log_date',`log_date`='$log_date' WHERE `id` = '$id'";
	$query = $db->query($sql);
	} else if($updated_cost == 1) {
		$sql = "UPDATE product SET `name`='$name',`group`='$group_name',`vendor`='$vendor_name',`description`='$description',`aliases`='$aliases',`category`='$category',`sub_category`='$sub_category',`unit`='$unit',`rate`='$rate',`cost`='$cost',`tax`='$tax',`hsn`='$hsn',`opening_stock`='',`moq`='$moq',`pdf`='$pdf',`images`='$images',`updated_cost`='$updated_cost',`updated_cost_date`='$log_date',`log_date`='$log_date' WHERE `id` = '$id'";
	$query = $db->query($sql);
	} else {
		$sql = "UPDATE product SET `name`='$name',`group`='$group_name',`vendor`='$vendor_name',`description`='$description',`aliases`='$aliases',`category`='$category',`sub_category`='$sub_category',`unit`='$unit',`rate`='$rate',`cost`='$cost',`tax`='$tax',`hsn`='$hsn',`new_opening_stock`='$new_opening_stock',`moq`='$moq',`pdf`='$pdf',`images`='$images',`updated_price`='$updated_price',`log_date`='$log_date' WHERE `id` = '$id'";
		$query = $db->query($sql);
	}


	if($query===true)
	{

		if($orig_name != $name){

			$sql = "INSERT INTO `product_logs`(`old_name`, `new_name`, `log_user`, `log_date`) VALUES ('$orig_name','$name','$log_user','$log_date')";
			$query = $db->query($sql);

			$sql = "UPDATE assembly SET `composite` = '$name' WHERE `composite` LIKE '$orig_name'";
	    	$query = $db->query($sql);

	    	$sql = "UPDATE assembly_operation SET `composite` = '$name' WHERE `composite` LIKE '$orig_name'";
	    	$query = $db->query($sql);

			$product_name = '\"'.$name.'\"';
			$orig_name = '\"'.$orig_name.'\"';

	    	$sql = "UPDATE quotation SET `items` = REPLACE(`items`, '$orig_name', '$product_name') WHERE `items` LIKE '%$orig_name%'";
	    	$query = $db->query($sql);	
	    	$sql = "UPDATE sales_order SET `items` = REPLACE(`items`, '$orig_name', '$product_name') WHERE `items` LIKE '%$orig_name%'";
	    	$query = $db->query($sql);	
	    	$sql = "UPDATE purchase_order SET `items` = REPLACE(`items`, '$orig_name', '$product_name') WHERE `items` LIKE '%$orig_name%'";
	    	$query = $db->query($sql);	
	    	$sql = "UPDATE sales_invoice SET `items` = REPLACE(`items`, '$orig_name', '$product_name') WHERE `items` LIKE '%$orig_name%'";
	    	$query = $db->query($sql);	
	    	$sql = "UPDATE purchase_invoice SET `items` = REPLACE(`items`, '$orig_name', '$product_name') WHERE `items` LIKE '%$orig_name%'";
	    	$query = $db->query($sql);	
	    	$sql = "UPDATE assembly SET `spares` = REPLACE(`items`, '$orig_name', '$product_name') WHERE `spares` LIKE '%$orig_name%'";
	    	$query = $db->query($sql);

	    	$sql = "UPDATE assembly_operation SET `items` = REPLACE(`items`, '$orig_name', '$product_name') WHERE `items` LIKE '%$orig_name%'";
	    	$query = $db->query($sql);

		}
		
		$validator['success'] = true;
		$validator['messages'] = "Successfully Updated";
	}
	else
	{
		$validator['success'] = false;
		$validator['messages'] = "There was some error updating the records";
	}

	echo json_encode($validator);
	
?>