<?php

$documents = array("enquiry", "quotation", "sales_order", "proforma", "sales_invoice", "e-commerce", "receipt", "purchase_order", "payment", "secondary");
$print_name = array("Enquiry", "Quotation", "Sales Order", "Proforma Invoice", "Sales Invoice", "E-Commerce", "Receipt", "Purchase Order", "Payment", "Secondary");

$len = sizeof($documents);

$sql = "SELECT * FROM extra";
$query = $db->query($sql);
$row = $query->fetch_assoc();

$extra_toggle = $row['estimate_toggle'];

if($extra_toggle == 1)
{
	$extra_toggle = 'checked';
}
else
{
	$extra_toggle = '';
}

?>

<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

	<!--Begin::Dashboard 1-->
	<div class="row">
	   	<div class="col-lg-6">	
	   		<!--begin::Portlet-->
	   		<div class="kt-portlet kt-portlet--mobile">
	   			<form class="kt-form kt-form--label-right" id="form_default_make">
					<div class="kt-portlet__head">
						<div class="kt-portlet__head-label">
							<h3 class="kt-portlet__head-title">
								Default Make <small>Category Wise...</small>
							</h3>
						</div>
					</div>
					<div class="kt-portlet__body">
						<div class="row" style="margin-top:5px;">
							<div class="col-sm-7">
								<select class="form-control kt-select2 settings_group-select2" name="settings_group" id="settings_group">
                                	<option></option>
                                </select>
							</div>
							<div class="col-sm-2">
								<label class="kt-switch">
                                    <input type="checkbox" name="settings_make" id="settings_make">
                                    <span></span>
                                </label>
							</div>
							<div class="col-sm-3">
								<button type="Submit" class="btn btn-success" id="update_settings_group">Update</button>
							</div>
						</div>
					</div>
				</form>
			</div>	
			<!--end::Portlet-->
	   		<!--begin::Portlet-->
	   		<div class="kt-portlet kt-portlet--mobile">
	   			<form class="kt-form kt-form--label-right" id="serial_numbering">
					<div class="kt-portlet__head">
						<div class="kt-portlet__head-label">
							<h3 class="kt-portlet__head-title">
								Settings <small>Serial Numbering...</small>
							</h3>
						</div>
					</div>
					<div class="kt-portlet__body">
						<div class="row" >
							<div class="col-sm-3"></div>
							<div class="col-sm-3"><center>Prefix</center></div>
							<div class="col-sm-3"><center>Number</center></div>
							<div class="col-sm-3"><center>Postfix</center></div>
						</div>
						<?php
							for($i=0;$i<$len;$i++){
								$key = $documents[$i];
								$sql_key_counter = "SELECT * FROM counter WHERE `key` = '$key'";
								$query_key_counter = $db->query($sql_key_counter);
								$row_key_counter = $query_key_counter->fetch_assoc();

								$value = json_decode($row_key_counter['value'], true);
								$prefix = $value['prefix'][0];
								$postfix = $value['postfix'][0];
								$number = $value['number'][0];

								$id_prefix = $key.'_prefix';
								$id_number = $key.'_number';
								$id_postfix = $key.'_postfix';
						?>
						<div class="row" style="margin-top:5px;">
							<div class="col-sm-3"><?php echo $print_name[$i]; ?></div>
							<div class="col-sm-3">
								<input class="form-control" type="text" value="<?php echo $prefix; ?>" id="<?php echo $id_prefix; ?>" name="<?php echo $id_prefix; ?>">
							</div>
							<div class="col-sm-3">
								<input class="form-control" type="text" value="<?php echo $number; ?>" id="<?php echo $id_number; ?>" name="<?php echo $id_number; ?>">
							</div>
							<div class="col-sm-3">
								<input class="form-control" type="text" value="<?php echo $postfix; ?>" id="<?php echo $id_postfix; ?>" name="<?php echo $id_postfix; ?>">
							</div>
						</div>
					<?php } ?>
					</div>
					<div class="kt-portlet__foot">
						<div class="kt-form__actions">
							<div class="row">
								<div class="col-lg-9 col-xl-9">
								</div>
								<div class="col-lg-3 col-xl-3" style="float:right;">
									<button type="Submit" class="btn btn-success" id="save_serial">Save Changes</button>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>	
			<!--end::Portlet-->
		</div>
		<div class="col-lg-6">	
			<!--begin::Portlet-->
	   		<div class="kt-portlet kt-portlet--mobile">
				<div class="kt-portlet__head">
					<div class="kt-portlet__head-label">
						<h3 class="kt-portlet__head-title">
							Add New Products <small>Products with Duplicate SKU shall be skipped...</small>
						</h3>
					</div>
				</div>
				<div class="kt-portlet__body">
					<div class="form-group form-group-last row">
						<div class="dropzone dropzone-default dropzone-brand" id="kt_dropzone_2">
							<div class="dropzone-msg dz-message needsclick">
								<h3 class="dropzone-msg-title">Drop files here or click to upload.</h3>
								<span class="dropzone-msg-desc">Files are only uploaded here. To actually <strong>import</strong> the file, kindly click on import button below after successful upload. </span>
							</div>
						</div>
					</div>
					
				</div>
				<div class="kt-portlet__foot">
						<button type="button" class="btn btn-brand" id="add_products">Add Bulk Products</button>
						<a href="../assets/media/dcs_product_template.xlsx" target="_blank" ><button type="button" class="btn btn-success" id="add_products">Download Template</button></a>
				</div>
			</div>	
			<!--end::Portlet-->
	   		<!--begin::Portlet-->
	   		<div class="kt-portlet kt-portlet--mobile">
				<div class="kt-portlet__head">
					<div class="kt-portlet__head-label">
						<h3 class="kt-portlet__head-title">
							Update Existing Products <small>Products with matching SKU shall only be updated...</small>
						</h3>
					</div>
				</div>
				<div class="kt-portlet__body">
					<div class="form-group form-group-last row">
						<div class="dropzone dropzone-default dropzone-success" id="kt_dropzone_1">
							<div class="dropzone-msg dz-message needsclick">
								<h3 class="dropzone-msg-title">Drop files here or click to upload.</h3>
								<span class="dropzone-msg-desc">Files are only uploaded here. To actually <strong>import</strong> the file, kindly click on import button below after successful upload.</span>
							</div>
						</div>
					</div>
					
				</div>
				<div class="kt-portlet__foot">
						<button type="button" class="btn btn-brand" id="update_products">Update</button>
				</div>
			</div>	
			<!--end::Portlet-->
		</div>
		<div class="col-lg-6">	
	   		<!--begin::Portlet-->
	   		<div class="kt-portlet kt-portlet--mobile">
	   			<form class="kt-form kt-form--label-right" id="form_extras_toggle">
					<div class="kt-portlet__head">
						<div class="kt-portlet__head-label">
							<h3 class="kt-portlet__head-title">
								Extras Toggle
							</h3>
						</div>
					</div>
					<div class="kt-portlet__body">
						<div class="row" style="margin-top:5px;">
							<div class="col-sm-3">
								<div class="form-group">
									<label class="kt-switch">
	                                    <input type="checkbox" <?php echo $extra_toggle; ?> name="exras_toggle" id="exras_toggle">
	                                    <span></span>
	                                </label>
	                            </div>
							</div>
							<div class="col-sm-3">
								<button type="Submit" class="btn btn-success" id="extras_toggle_submit">Update</button>
							</div>
						</div>
					</div>
				</form>
			</div>	
			<!--end::Portlet-->
		</div>
		<div class="col-lg-6">	
	   		<!--begin::Portlet-->
	   		<div class="kt-portlet kt-portlet--mobile">
	   			<form class="kt-form kt-form--label-right" id="form_product_xml">
					<div class="kt-portlet__head">
						<div class="kt-portlet__head-label">
							<h3 class="kt-portlet__head-title">
								Product Masters
							</h3>
						</div>
					</div>
					<div class="kt-portlet__body">
						<div class="form-group row">
							<div class="col-sm-6">
                                <div class="form-group">
                                    <label>From</label>
                                    <div class="kt-input-icon">
                                        <input name="product_xml_from" placeholder="From" id="product_xml_from" class="form-control date-picker" type="text"  required="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>To</label>
                                    <div class="kt-input-icon">
                                        <input name="product_xml_to" placeholder="To" id="product_xml_to" class="form-control date-picker" type="text" required="">
                                    </div>
                                </div>
                            </div>
                        </div>
						<div class="form-group row">
							<button type="Submit" class="btn btn-success" id="product_xml_submit">Generate XML</button>
						</div>
					</div>
				</form>
			</div>	
			<!--end::Portlet-->

			<!--begin::Portlet-->
	   		<div class="kt-portlet kt-portlet--mobile">
	   			<form class="kt-form kt-form--label-right" id="form_masters_xml">
					<div class="kt-portlet__head">
						<div class="kt-portlet__head-label">
							<h3 class="kt-portlet__head-title">
								Account Masters
							</h3>
						</div>
					</div>
					<div class="kt-portlet__body">
						<div class="form-group row">
							<div class="col-sm-6">
                                <div class="form-group">
                                    <label>From</label>
                                    <div class="kt-input-icon">
                                        <input name="masters_xml_from" placeholder="From" id="masters_xml_from" class="form-control date-picker" type="text"  required="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>To</label>
                                    <div class="kt-input-icon">
                                        <input name="masters_xml_to" placeholder="To" id="masters_xml_to" class="form-control date-picker" type="text" required="">
                                    </div>
                                </div>
                            </div>
                        </div>
						<div class="form-group row">
							<button type="Submit" class="btn btn-success" id="masters_xml_submit">Generate XML</button>
						</div>
					</div>
				</form>
			</div>	
			<!--end::Portlet-->

		</div>
	</div>
</div>

<!-- end:: Content -->

