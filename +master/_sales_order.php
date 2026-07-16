<!-- begin:: Content --> 
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

	<!--Begin::Dashboard 1-->
	<div class="col-lg-12">
	    
	    <?php

            $username = $_SESSION['username'];
            $userlevel = $_SESSION['userlevel'];
    
            $sql_access = "SELECT * FROM users WHERE `username` = '$username'";
            $query_access = $db->query($sql_access);
            $row_access = $query_access->fetch_assoc();
    
            $menu_access = json_decode($row_access['access'], true);
    
            if($menu_access['sales_order']['create'] == '1' || $userlevel == "sadmin_df56fdg"){
    
                
        ?>
	    
		<!--begin::Portlet-->
		<div class="kt-portlet" id="kt_portlet_add_so">
			
			<!--begin::Form-->
			<form class="kt-form" id="add_sales_order">
				<div class="kt-portlet__body">
					<div class="kt-section kt-section--first">
						<input type="text" name="edit_so_id" id="edit_so_id" style="display: none">
						<input type="text" name="so_state" id="so_state" style="display: none">

						<div class="form-group row">
							<div class="col-sm-3">
                                <div class="form-group">
                                    <label>Client</label>
									<div class="kt-input-icon">
	                                    <select class="form-control kt-select2 client-select2" name="so_client" id="so_client">
	                                    	<option></option>
	                                    </select>
	                                </div>
                                    <span class="form-text text-muted">Please enter name of the client..</span>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Quotation</label>
									<div class="kt-input-icon">
	                                    <select class="form-control kt-select2 kt-select2-multiple so_quotation-select2" name="so_quotation[]" id="so_quotation">
	                                    </select>
	                                </div>
                                    <span class="form-text text-muted">Please select the quotation..</span>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Sales Order #</label>
									<div class="kt-input-icon">
                                    	<input name="sales" placeholder="Sales Order #" id="sales" class="form-control" type="text" readonly>
                                    </div>
                                    <span class="form-text text-muted">Please enter the sales order#..</span>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Sales Order Date</label>
									<div class="kt-input-icon">
                                    	<input placeholder="Sales Order Date" name="sales_date" id="sales_date" class="form-control date-picker" type="text" data-date-end-date="+3m" value="<?php echo date('d-m-Y', strtotime('today')); ?>">
                                    </div>
                                    <span class="form-text text-muted">Please enter the sales order date..</span>
                                </div>
                            </div>
                        </div>
						<div class="form-group row">
						<div class="col-sm-3">
                                <div class="form-group">
                                    <label>Mobile No#</label>
									<div class="kt-input-icon">
                                    	<input placeholder="Client Mobile no. #" name="mobile" id="mobile" class="form-control" type="text">
                                    </div>
                                    <span class="form-text text-muted">Please enter the Client's Mobile No. #..</span>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Client Sales Order #</label>
									<div class="kt-input-icon">
                                    	<input placeholder="Client Sales Order #" name="client_so_no" id="client_so_no" class="form-control" type="text">
                                    </div>
                                    <span class="form-text text-muted">Please enter the client's sales order #..</span>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Material Status</label>
									<div class="kt-input-icon">
	                                    <select class="form-control kt-select2 so_collected-select2" name="so_collected" id="so_collected">
	                                    	<option value='0'>Order Received</option>
	                                    	<option value='1'>Material Given</option>
	                                    </select>
	                                </div>
                                    <span class="form-text text-muted">Please select the material status..</span>
                                </div>
                            </div>
                        </div>
						
						<div class="form-group row">
							<div id="kt_repeater_so">
								<div class="form-group form-group-last row" id="kt_repeater_1">
									<div data-repeater-list="sales_order" id="sales_order_list" class="col-lg-12">
										<div data-repeater-item class="form-group row align-items-center" style="border-bottom: 1px dashed #5d78ff; padding-bottom:1rem; margin-bottom: 1rem;">
											<div class="col-md-1" style="margin-top:3px; text-align:center">
												<input type="text" class="form-control" name="so_sn" value="1" style="border: none;font-weight: 900;text-align: center;" readonly="">
											</div>
											<div class="col-md-3">
												<div class="kt-form__group--inline">
													<div class="kt-form__control">
														<select class="form-control kt-select2 so_product_name-select2" name="so_product_name">
						                            	</select>
													</div>
												</div>
												<span class="product_stock" name="product_stock"></span>
											</div>
											<div class="col-md-4" >
												<input type="text" class="form-control" name="so_product_description" placeholder="Product Name">
											</div>	
											<div class="col-md-4">
												<textarea class="form-control" placeholder="Product Description" name="so_product_add_description" rows='1'></textarea>
											</div>
											<div class="col-md-1" style="margin-top:3px">
											</div>
											<div class="col-md-1" style="margin-top:3px">
												<div class="input-group">
													<input placeholder="Qty" name="so_qty" class="form-control so_qty" type="text">
												</div>
											</div>
											<div class="col-md-1" style="margin-top:3px">
												<div class="kt-form__group--inline">
													<div class="kt-form__control">
														<select name="so_unit" class="form-control kt-select2 so_unit-select2"> 
						                                </select>
													</div>
												</div>
											</div>
											<div class="col-md-1" style="margin-top:3px">
												<div class="input-group">
						                            <input placeholder="Price" name="so_rate" class="form-control so_rate" type="text">
												</div>
											</div>
											<div class="col-md-1" style="margin-top:3px">
												<div class="input-group">
													<input placeholder="Discount" name="so_dsc" class="form-control so_dsc" type="text">
												</div>
											</div>
											<div class="col-md-1" style="margin-top:3px">
												<div class="input-group">
													<input placeholder="HSN" name="so_hsn" class="form-control so_hsn" type="text">
												</div>
											</div>
											<div class="col-md-1" style="margin-top:3px">
												<div class="kt-form__group--inline">
													<div class="kt-form__control">
														<select name="so_tax" class="form-control kt-select2 so_tax-select2"> 
						                                    <option></option>
						                                    <option value="5">5</option>
						                                    <option value="12">12</option>
						                                    <option value="18">18</option>
						                                    <option value="28">28</option>
						                                </select>
													</div>
												</div>
											</div>
											<div class="col-md-1" style="margin-top:3px">
												<div class="input-group">
						                            <input type="text" class="form-control so_gross_pr" name="so_gross_pr" style="background-color: #eee" readonly>
												</div>
											</div>	
											<div class="col-md-1" style="margin-top:3px">
												<div class="input-group">
						                            <input type="text" class="form-control so_tax_pr" name="so_tax_pr" style="background-color: #eee"  readonly>
												</div>
											</div>
											<input type="text" name="so_cgst" style="display: none">
											<input type="text" name="so_sgst" style="display: none">
											<input type="text" name="so_igst" style="display: none">
											<div class="col-md-1" style="margin-top:3px">
												<div class="input-group">
						                            <input type="text" class="form-control so_total_pr" name="so_total_pr" style="background-color: #eee" readonly>
												</div>
											</div>
											<div class="col-md-1" style="margin-top:3px">
												<div class="kt-form__group--inline">
													<div class="kt-form__control">
														<select name="so_display_make" class="form-control kt-select2 so_display_make-select2"> 
                                                            <option></option>
                                                            <option value="1">Show</option>
                                                            <option value="0">Hide</option>
                                                        </select>
													</div>
												</div>
											</div>
											<div class="col-md-1" style="margin-top:3px; text-align:center">
												<a href="javascript:;" data-repeater-delete="" class="btn-sm btn btn-label-danger btn-bold so_delete">
													<i class="la la-trash-o"></i>
												</a>
											</div>
										</div>
									</div>
								</div>
								<div >
								
									<div class="col-lg-4">
										<a href="javascript:;" data-repeater-create="sales_order" id="so_btn_add" class="btn btn-bold btn-sm btn-label-brand">
											<i class="la la-plus"></i> Add
										</a>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group row" style="margin-bottom: 2px;">
							<div class="col-md-10">
								<div class="form-control" style="text-align:right; border: none;">Gross Total :</div>
								
							</div>
							<div class="col-md-2">
								<div class="input-group">
                                    <input type="text" class="form-control so_gross_final" name="so_gross_final" style="background-color: #eee; text-align:right;" readonly>
								</div>
							</div>	
						</div>
						<div class="form-group row" style="margin-bottom: 2px;">
							<div class="col-md-10">
								<div class="form-control" style="text-align:right; border: none;">Packaging & Forwarding :</div>
								
							</div>
							<div class="col-md-2">
								<div class="input-group">
						            <input type="text" class="form-control" name="so_pf" style="text-align:right;" id="so_pf">
								</div>
							</div>	
						</div>
						<input type="text" name="so_pf_cgst" id="so_pf_cgst" style="display: none">
						<input type="text" name="so_pf_sgst" id="so_pf_sgst" style="display: none">
						<input type="text" name="so_pf_igst" id="so_pf_igst" style="display: none">
						<div class="form-group row" style="margin-bottom: 2px;">
							<div class="col-md-10">
								<div class="form-control" style="text-align:right; border: none;">Freight :</div>
								
							</div>
							<div class="col-md-2">
								<div class="input-group">
						            <input type="text" class="form-control" name="so_freight" style="text-align:right;" id="so_freight">
								</div>
							</div>
						</div>
						<input type="text" name="so_freight_cgst" id="so_freight_cgst" style="display: none">
						<input type="text" name="so_freight_sgst" id="so_freight_sgst" style="display: none">
						<input type="text" name="so_freight_igst" id="so_freight_igst" style="display: none">
						<div class="form-group row" style="margin-bottom: 2px;">
							<div class="col-md-10">
								<div class="form-control" style="text-align:right; border: none;">Total Tax :</div>
								
							</div>
							<div class="col-md-2">
								<div class="input-group">
						            <input type="text" class="form-control so_tax_final" name="so_tax_final" style="background-color: #eee; text-align:right;" readonly>
								</div>
							</div>
						</div>
						<div class="form-group row" style="margin-bottom: 2px;">
							<div class="col-md-10">
								<div class="form-control" style="text-align:right; border: none;">Round Off :</div>
							</div>
							<div class="col-md-2">
								<div class="input-group">
						            <input type="text" class="form-control" name="so_round" style="text-align:right;" id="so_round">
								</div>
							</div>
						</div>
						<div class="form-group row" style="margin-bottom: 2px;">
							<div class="col-md-10">
								<div class="form-control" style="text-align:right; border: none;">Grand Total :</div>
								
							</div>
							<div class="col-md-2">
								<div class="input-group">
						            <input type="text" class="form-control so_total_final" name="so_total_final" style="background-color: #eee; text-align:right;" readonly>
								</div>
							</div>
						</div>
					</div>
		            <div class="kt-portlet__foot">
						<div class="kt-form__actions" style="float:right;">
							<button id="sales_order_submit" type="submit" class="btn btn-primary">Submit</button>
						</div>
					</div>
				</div>
			</form>
			<!--end::Form-->
		</div>
		<!--end::Portlet-->
		
		<?php
		
            }
		
		?>

		<!--begin::Portlet-->
		<div class="kt-portlet kt-portlet--mobile">
			<div class="kt-portlet__body kt-portlet__body--fit">
				<div class="kt-portlet__body">
					<!--begin: Search Form -->
					<div class="kt-form kt-form--label-right kt-margin-t-20 kt-margin-b-10">
						<div class="row align-items-center">
							<div class="col-xl-8 order-2 order-xl-1">
								<div class="row align-items-center">				
									<div class="col-md-3 kt-margin-b-20-tablet-and-mobile">
										<div class="kt-input-icon kt-input-icon--left">
											<input type="text" class="form-control" placeholder="Search..." id="generalSearch">
											<span class="kt-input-icon__icon kt-input-icon__icon--left">
												<span><i class="la la-search"></i></span>
											</span>
										</div>
									</div>
									<div class="col-md-3 kt-margin-b-20-tablet-and-mobile">
										<div class="kt-form__control">
											<select class="form-control bootstrap-select" id="kt_sales_order_product">
												<option></option>
											</select>
										</div>
									</div>
									<div class="col-md-3 kt-margin-b-20-tablet-and-mobile">
										<div class="kt-form__control">
											<select class="form-control bootstrap-select" id="kt_sales_order_user">
												<option></option>
											</select>
										</div>
									</div>
									<div class="col-md-3 kt-margin-b-20-tablet-and-mobile">
										<div class="kt-form__control">
											<select class="form-control bootstrap-select" id="kt_sales_order_status">
												<option></option>
												<option value="0">Pending</option>
												<option value="1">Completed</option>
											</select>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!--end: Search Form -->
				</div>

				<!--begin: Datatable -->
				<div class="kt-datatable" id="sales_order_datatable"></div>
				<!--end: Datatable -->
				
			</div>
		</div>

	</div>
	<!--End::Dashboard 1-->
</div>
<!-- end:: Content -->

<!--begin::Create Proforma Modal-->
<div class="modal fade" id="create_proforma" tabindex="-1" role="dialog" aria-labelledby="createProformaModal" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="createProformaModal" >Create Proforma Invoice</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				</button>
			</div>
			<div class="modal-body">
				<!--begin::Form-->
				<form class="kt-form kt-form--label-right">
					<div class="kt-portlet__body">
						Are you sure you want to create proforma invoice for this order ?
					</div>
				</form>

				<!--end::Form-->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button id="create_proforma_submit" type="button" class="btn btn-primary">Create</button>
			</div>
		</div>
	</div>
</div>
<!--end::Create Proforma Modal-->

<!--begin::Delete Sales Order Modal-->
<div class="modal fade" id="delete_sales_order" tabindex="-1" role="dialog" aria-labelledby="deleteSalesOrderModal" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="deleteSalesOrderModal" >Delete Sales Order</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				</button>
			</div>
			<div class="modal-body">
				<!--begin::Form-->
				<form class="kt-form kt-form--label-right">
					<div class="kt-portlet__body">
						Are you sure you want to delete this sales order ?
					</div>
				</form>

				<!--end::Form-->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button id="delete_sales_order_submit" type="button" class="btn btn-primary">Delete</button>
			</div>
		</div>
	</div>
</div>
<!--end::Delete Sales Order Modal-->

<form class="kt-form kt-form--label-right" id="add_purchase_bag">
	<div class="modal fade" id="kt_modal_add_purchase_bag" tabindex="-1" role="dialog" aria-labelledby="addPurchaseBagModal" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="addPurchaseBagModal" >Add to Purchase Bag</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					</button>
				</div>
				<div class="modal-body">
					<div class="kt-portlet__body">
						<div class="form-group row">
	                        <div class="col-sm-6">
	                        	<div class="form-group">
	                                <label>Product Name</label>
	                                <input name="pb_product" id="pb_product" class="form-control" type="text">
	                            </div>											
	                        </div>
	                        <div class="col-sm-6">
	                            <div class="form-group">
			                            <label>Quantity</label>
	                                <input name="pb_quantity" id="pb_quantity" class="form-control" type="text">
	                                <span class="form-text text-muted">Please enter the the quantity required .</span>
	                            </div>
	                        </div>
	                    </div>
                    </div>
				</div>
				<div class="modal-footer">
					<button id="add_pb_submit" type="submit" class="btn btn-primary">Submit </button>
				</div>
			</div>
		</div>
	</div>
</form>

<!--begin::Send Email Modal-->
<form class="kt-form kt-form--label-right" id="send_so_email">
	<div class="modal fade" id="kt_modal_so_email" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="AssembleModal">Send Email</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					</button>
				</div>
				<div class="modal-body">
					<!--begin::Form-->
						<div class="kt-portlet__body">
							<input name="so_em_id" id="so_em_id" class="form-control" type="text" style="display:none">
							<div class="form-group row">
								<div class="col-sm-6">
	                                <div class="form-group">
	                                    <label>Send To</label>
										<div class="kt-input-icon">
	                                    	<input name="so_em_email" id="so_em_email" placeholder="Email Address" class="form-control" type="text">
	                                    </div>
	                                </div>
	                            </div>
	                            <div class="col-sm-6">
	                                <div class="form-group">
	                                    <label>Subject</label>
										<div class="kt-input-icon">
	                                    	<input name="so_em_subject" id="so_em_subject"  class="form-control" type="text">
	                                    </div>
	                                </div>
	                            </div>
	                            <div class="col-sm-6">
	                                <div class="form-group">
	                                    <label>CC</label>
										<div class="kt-input-icon">
	                                    	<input name="so_em_email_cc" id="so_em_email_cc" placeholder="Email Address"  class="form-control" type="text">
	                                    </div>
	                                </div>
	                            </div>
	                            <div class="col-sm-6">
	                                <div class="form-group">
	                                    <label>BCC</label>
										<div class="kt-input-icon">
	                                    	<input name="so_em_email_bcc" id="so_em_email_bcc" placeholder="Email Address" class="form-control" type="text">
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
							<div class="form-group row">
	                            <div class="col-sm-12">
	                                <div class="form-group">
										<div class="kt-input-icon">
	                                    	<input name="so_em_message" id="so_em_message" class="summernote">

	                                    </div>
	                                </div>
	                            </div>
	                        </div>
						</div>
					<!--end::Form-->
				</div>
				<div class="modal-footer">
					<button id="so_email_submit" type="submit" class="btn btn-primary">Send</button>
				</div>
			</div>
		</div>
	</div>
</form>
<!--end::Send Email Modal-->

<!--begin::Cancel Sales Order Modal-->
<div class="modal fade" id="cancel_sales_order" tabindex="-1" role="dialog" aria-labelledby="cancelSalesOrderModal" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="cancelSalesOrderModal" >Cancel Sales Order</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				</button>
			</div>
			<div class="modal-body">
				<!--begin::Form-->
				<form class="kt-form kt-form--label-right">
					<div class="kt-portlet__body">
						Are you sure you want to cancel this sales order ?
					</div>
				</form>

				<!--end::Form-->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button id="cancel_sales_order_submit" type="button" class="btn btn-primary">Delete</button>
			</div>
		</div>
	</div>
</div>
<!--end::Cancel Sales Order Modal-->

<!--begin::Send whatsapp Modal-->
<form class="kt-form kt-form--label-right" id="send_sales_order_whatsapp">
	<div class="modal fade" id="kt_modal_sales_order_whatsapp" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Send Whatsapp</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					</button>
				</div>
				<div class="modal-body">
					<!--begin::Form-->
                    	<input name="so_no_whatsapp" id="so_no_whatsapp" style="display:none" class="form-control" type="text">

						<div class="kt-portlet__body">
							<div class="form-group row" style="margin-bottom: 0">
								<div class="col-sm-6">
	                                <div class="form-group">
	                                    <label>Mobile No (Separted by comma)</label>
										<div class="kt-input-icon">
	                                    	<input name="so_whatsapp_number" id="so_whatsapp_number" placeholder="Enter Whatsapp Number(s)" class="form-control" type="text">
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
						</div>
					<!--end::Form-->
				</div>
				<div class="modal-footer">
					<button id="so_whatsapp_submit" type="submit" class="btn btn-primary">Send</button>
				</div>
			</div>
		</div>
	</div>
</form>
<!--end::Send whatapp Modal-->

<!--begin::Send whatsapp Modal-->
<form class="kt-form kt-form--label-right" id="send_proforma_whatsapp">
	<div class="modal fade" id="kt_modal_proforma_whatsapp" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Send Whatsapp</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					</button>
				</div>
				<div class="modal-body">
					<!--begin::Form-->
                    	<input name="pr_no_whatsapp" id="pr_no_whatsapp" style="display:none" class="form-control" type="text">

						<div class="kt-portlet__body">
							<div class="form-group row" style="margin-bottom: 0">
								<div class="col-sm-6">
	                                <div class="form-group">
	                                    <label>Mobile No (Separted by comma)</label>
										<div class="kt-input-icon">
	                                    	<input name="pr_whatsapp_number" id="pr_whatsapp_number" placeholder="Enter Whatsapp Number(s)" class="form-control" type="text">
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
						</div>
					<!--end::Form-->
				</div>
				<div class="modal-footer">
					<button id="pr_whatsapp_submit" type="submit" class="btn btn-primary">Send</button>
				</div>
			</div>
		</div>
	</div>
</form>
<!--end::Send whatapp Modal-->

<!--begin::Add Notes Modal-->
<form class="kt-form kt-form--label-right" id="add_so_note">
	<div class="modal fade" id="kt_modal_so_note" tabindex="-1" role="dialog" aria-labelledby="AddQnoteModal" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="AssembleModal">Add a Note</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					</button>
				</div>
				<div class="modal-body">
					<!--begin::Form-->
						<div class="kt-portlet__body">
							<input name="an_so_no" id="an_so_no" class="form-control" type="text" style="display:none">
							<div class="form-group row">
	                            <div class="col-sm-12">
	                                <div class="form-group">
										<div class="kt-input-icon">
	                                    	<textarea name="add_so_note" placeholder="Note..." id="add_so_note" class="form-control" type="text" ></textarea>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
						</div>
					<!--end::Form-->
				</div>
				<div class="modal-footer">
					<button id="so_note_submit" type="submit" class="btn btn-primary">Save</button>
				</div>
			</div>
		</div>
	</div>
</form>
<!--end::Add Notes Modal-->


