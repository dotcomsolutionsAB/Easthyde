<title>Access Control</title>

<style>

	.table_sub_header {
		background: rgba(241, 199, 141, 0.2) !important; 
		/*color: #8B440C;*/
		border-top: 2px solid #8B440C!important;
		border-bottom: 2px solid #8B440C!important;
	}

	.table_header {
		/*background: #8B440C; */
		color: white; 
		position:sticky; 
		top:120px; 
		z-index: 99;
	}

	.kt-checkbox > input:checked ~ span, .kt-checkbox > span{
		border: 1px solid #8B440C!important;
	}

	.kt-checkbox > span::after {
		border-top-color: rgb(139, 68, 12);
		border-top-style: solid;
		border-top-width: medium;
		border-right-color: rgb(139, 68, 12);
		border-right-style: solid;
		border-right-width: medium;
		border-bottom-color: rgb(139, 68, 12);
		border-bottom-style: solid;
		border-bottom-width: medium;
		border-left-color: rgb(139, 68, 12);
		border-left-style: solid;
		border-left-width: medium;
		border-image-outset: 0;
		border-image-repeat: stretch;
		border-image-slice: 100%;
		border-image-source: none;
		border-image-width: 1;
	}

	th{
		/*background-color: #456B33;*/
	}

</style>

<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

	<?php
		$id = $_REQUEST['id'];
		$sql = "SELECT * FROM users WHERE id = '$id'";
		$query = $db->query($sql);
		$row = $query->fetch_assoc();

		$access = json_decode($row['access'], true);
		$modules = array("Quotation","Sales Order","Proforma","Sales Invoice","Receipt","Credit Note","Purchase Quotation","Purchase Order","Purchase Invoice","Payments","Debit Note","Banks","Expense","Products","Clients","Suppliers","Secondary Sales","Secondary Purchase","Khumus");
		$modules_id = array("quotation","sales_order","proforma_invoice","sales_invoice","receipt","credit_note","purchase_quotation","purchase_order","purchase_invoice","payments","debit_note","banks","expense","products","clients","suppliers","secondary_sales","secondary_purchase","khumus");

		$len = sizeof($modules);
	?>

	<h3>User : <?php echo $row['name']; ?></h3>

	<form class="kt-form kt-form--label-right" id="access_control" autocomplete="off">
		<input id="user_id" name="user_id" style="display: none" value="<?php echo $_REQUEST['id']; ?>"/>
		<table class="table table-bordered" style="background: white;">
			<tr>
				<th style="text-align: left;" >Modules</th>
				<th style="text-align: center;"  width="20%">Create</th>
				<th style="text-align: center;"  width="20%">View</th>
				<th style="text-align: center;"  width="20%">Edit</th>
				<th style="text-align: center;"  width="20%">Delete</th>
			</tr>
			<?php for($i=0;$i<$len;$i++){ ?>
			<tr>
				<th style="text-align: left;"><?php echo $modules[$i]; ?></th>
				<td style="text-align: center;">
					<?php $tmp=$modules_id[$i].'_create'; ?>
					<label class="kt-checkbox">
						<?php 
							$checked = "";
							$module = $modules_id[$i];
							if($access[$module]['create'] == '1')
								$checked = "checked"; 
						?>
						<input type="checkbox" id="<?php echo $tmp; ?>" name="<?php echo $tmp; ?>" <?php echo $checked; ?>>
						<span></span>
					</label>
				</td>
				<td style="text-align: center;">
					<?php $tmp=$modules_id[$i].'_view'; ?>
					<label class="kt-checkbox">
						<?php 
							$checked = "";
							$module = $modules_id[$i];
							if($access[$module]['view'] == '1')
								$checked = "checked"; 
						?>
						<input type="checkbox" id="<?php echo $tmp; ?>" name="<?php echo $tmp; ?>" <?php echo $checked; ?>>
						<span></span>
					</label>
				</td>
				<td style="text-align: center;">
					<?php $tmp=$modules_id[$i].'_edit'; ?>
					<label class="kt-checkbox">
						<?php 
							$checked = "";
							$module = $modules_id[$i];
							if($access[$module]['edit'] == '1')
								$checked = "checked"; 
						?>
						<input type="checkbox" id="<?php echo $tmp; ?>" name="<?php echo $tmp; ?>" <?php echo $checked; ?>>
						<span></span>
					</label>
				</td>
				<td style="text-align: center;">
					<?php $tmp=$modules_id[$i].'_delete'; ?>
					<label class="kt-checkbox">
						<?php 
							$checked = "";
							$module = $modules_id[$i];
							if($access[$module]['delete'] == '1')
								$checked = "checked"; 
						?>
						<input type="checkbox" id="<?php echo $tmp; ?>" name="<?php echo $tmp; ?>" <?php echo $checked; ?>>
						<span></span>
					</label>
				</td>
			</tr>
			<?php } ?>
			

		</table>
		<button id="update_access_control" type="submit" class="btn btn-primary" style="float: right; background: #456B33; border: 1px solid #456B33;">Update</button>
	</form>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){

    $("#access_control").on("submit", function(e){
        e.preventDefault(); // prevent normal form submit

        $.ajax({
            url: "../../../assets/custom/settings/access_control.php",
            type: "POST",
            data: $(this).serialize(),
            dataType: "json", // expect JSON back
            success: function(response){
                if (response.success) {
                    alert(response.messages);   // ✅ success message
                    location.reload();          // 🔄 reload page
                } else {
                    alert("Error: " + response.messages); // ❌ error message
                }
            },
            error: function(xhr, status, error){
                alert("AJAX Error: " + error);
                console.error(error);
            }
        });

    });

});
</script>
