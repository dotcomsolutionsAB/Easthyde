<?php
	include ("../connect.php");
	session_start();
	
	$modules_id = array("quotation","sales_order","proforma_invoice","sales_invoice","receipt","credit_note","purchase_quotation","purchase_order","purchase_invoice","payments","debit_note","banks","expense","products","clients","suppliers","secondary_sales","secondary_purchase","khumus");
	$len = sizeof($modules);

	$user_id = $_REQUEST['user_id'];

	$sql = "SELECT * FROM users WHERE id = '$user_id'";
	$query = $db->query($sql);
	while($row = $query->fetch_assoc()){

		$id = $row['id'];

		$access = array();

		$modules_id = array("quotation","sales_order","proforma_invoice","sales_invoice","receipt","credit_note","purchase_quotation","purchase_order","purchase_invoice","payments","debit_note","banks","expense","products","clients","suppliers","secondary_sales","secondary_purchase","khumus");

		$functions = array("create","view","edit","delete");

		$len = sizeof($modules_id);
		$f_len = sizeof($functions);

		for($i=0;$i<$len;$i++)
		{
			for($j=0;$j<$f_len;$j++)
			{
				$temp = $modules_id[$i].'_'.$functions[$j];
				$value = $_REQUEST[$temp];

				if($value == 'on')
					$value = '1';
				else
					$value = '0';

				$module = $modules_id[$i];
				$function = $functions[$j];
				$access[$module][$function] = $value;
			}
		}

		$access = json_encode($access);


		$sql_update = "UPDATE users SET `access`='$access' WHERE `id` = '$id'";
		$query_update = $db->query($sql_update);
	}

	$validator = array("success"=>false, "messages"=>$sql );

	if($query_update===true)
	{
		$validator['success'] = true;
		$validator['messages'] = "Successfully Updated";
	}
	else
	{
		$validator['success'] = false;
		$validator['messages'] = $sql;
	}

	echo json_encode($validator);
	
?>