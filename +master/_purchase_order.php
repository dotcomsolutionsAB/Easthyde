<?php
	$sql_update = "UPDATE purchase_bag SET temp = '0' WHERE 1";
    $query_update = $db->query($sql_update);

?>
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

        if($menu_access['purchase_order']['create'] == '1' || $userlevel == "sadmin_df56fdg"){

            
    ?>
		<!--begin::Portlet-->
		<div class="kt-portlet" id="kt_portlet_add_po">
			
			<!--begin::Form-->
			<form class="kt-form" id="add_purchase_order">
				<div class="kt-portlet__body">
					<div class="kt-section kt-section--first">
						<input type="text" name="edit_po_id" id="edit_po_id" style="display: none">
						<input type="text" name="po_state" id="po_state" style="display: none">

						<div class="form-group row">
							<div class="col-sm-3">
                                <div class="form-group">
                                    <label>Supplier</label>
									<div class="kt-input-icon">
	                                    <select class="form-control kt-select2 supplier-select2" name="po_supplier" id="po_supplier">
	                                    	<option></option>
	                                    </select>
	                                </div>
                                    <span class="form-text text-muted">Please enter name of the supplier..</span>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Purchase Order#</label>
									<div class="kt-input-icon">
                                    	<input name="purchase" placeholder="Purchase Order #" id="purchase" class="form-control" type="text">
                                    </div>
                                    <span class="form-text text-muted">Please enter the purchase Order#..</span>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Purchase Order Date</label>
									<div class="kt-input-icon">
                                    	<input placeholder="Purchase Order Date" name="purchase_date" id="purchase_date" class="form-control date-picker" type="text" data-date-end-date="+3m" value="<?php echo date('d-m-Y', strtotime('today')); ?>">
                                    </div>
                                    <span class="form-text text-muted">Please enter the purchase order date..</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row col-lg-12">
							<label>Shipping Address</label>
						</div>
						<div class="form-group row">
							<div class="col-lg-6">
								<label>Name:</label>
								<div class="kt-input-icon">
									<input type="text" class="form-control" id="po_shipping_name" name="po_shipping_name" value="M.M. Enterprise">
								</div>
							</div>
							<div class="col-lg-3">
								<label>Address Line 1:</label>
								<div class="kt-input-icon">
									<input type="text" class="form-control" id="po_shipping_add_1" name="po_shipping_add_1" value="26, Strand Road">
								</div>
							</div>
							<div class="col-lg-3">
								<label>Address Line 2:</label>
								<div class="kt-input-icon">
									<input type="text" class="form-control" id="po_shipping_add_2" name="po_shipping_add_2" value="Ground Floor">
								</div>
							</div>
						</div>
						<div class="form-group row">
							<div class="col-lg-3">
								<label>City:</label>
								<div class="kt-input-icon">
									<input type="text" class="form-control" id="po_shipping_city" name="po_shipping_city" value="KOLKATA">
								</div>
							</div>
							<div class="col-lg-3">
								<label>Pincode:</label>
								<div class="kt-input-icon">
									<input type="text" class="form-control" id="po_shipping_pincode" name="po_shipping_pincode" value="700001">
								</div>
							</div>
							<div class="col-lg-3">
								<label>State:</label>
								<div class="kt-input-icon">
									<!-- <select type="text" class="form-control kt-select2 po_shipping_state-select2" id="po_shipping_state" name="po_shipping_state">
									</select> -->
									<input type="text" class="form-control" id="po_shipping_state" name="po_shipping_state" value="WEST BENGAL">
								</div>
							</div>
							<div class="col-lg-3">
								<label>Country:</label>
								<div class="kt-input-icon">
									<input type="text" class="form-control" id="po_shipping_country" name="po_shipping_country" value="INDIA">
								</div>
							</div>
						</div>
						<!-- <div class="form-group row" style="border-bottom: 1px solid #eee; padding-bottom:1rem;">
							<div class="col-md-1">
							</div>
							<div class="col-md-1">
								<div class="kt-form__label">
									<label>Quantity:</label>
								</div>
							</div>
							<div class="col-md-1">
								<div class="kt-form__label">
									<label>Unit:</label>
								</div>
							</div>
							<div class="col-md-1">
								<div class="kt-form__label">
									<label>Price:</label>
								</div>
							</div>
							<div class="col-md-1">
								<div class="kt-form__label">
									<label>Discount %:</label>
								</div>
							</div>
							<div class="col-md-1">
								<div class="kt-form__label">
									<label>HSN:</label>
								</div>
							</div>
							<div class="col-md-1">
								<div class="kt-form__label">
									<label>Tax:</label>
								</div>
							</div>
							<div class="col-md-1">
								<div class="kt-form__label">
									<label>Gross:</label>
								</div>
							</div>
							<div class="col-md-1">
								<div class="kt-form__label">
									<label>Tax:</label>
								</div>
							</div>
							<div class="col-md-1">
								<div class="kt-form__label">
									<label>Total:</label>
								</div>
							</div>
							<div class="col-md-1">
								<div class="kt-form__label">
									<label>Make:</label>
								</div>
							</div>
						</div> -->
						<div class="form-group row">
							<div id="kt_repeater_po">
								<div class="form-group form-group-last row" id="kt_repeater_1">
									<div data-repeater-list="purchase_order" id="purchase_order_list" class="col-lg-12">
										<div data-repeater-item class="form-group row align-items-center" style="border-bottom: 1px dashed #5d78ff; padding-bottom:1rem; margin-bottom: 1rem;">
											<div class="col-md-1" style="margin-top:3px; text-align:center">
												<input type="text" class="form-control" name="po_sn" value="1" style="border: none;font-weight: 900;text-align: center;" readonly="">
											</div>
											<div class="col-md-3">
												<div class="kt-form__group--inline">
													<div class="kt-form__control">
														<select class="form-control kt-select2 po_product_name-select2" name="po_product_name">
						                            	</select>
													</div>
												</div>
											</div>
											<div class="col-md-4" >
												<input type="text" class="form-control" name="po_product_description" placeholder="Product Name">
											</div>	
											<div class="col-md-4" >
												<textarea class="form-control" placeholder="Product Description" name="po_product_add_description" rows='1' style="height:40px;"></textarea>
											</div>	
											<div class="col-md-1" style="margin-top:3px">
											</div>
											<div class="col-md-1" style="margin-top:3px">
												<div class="input-group">
													<input placeholder="Qty" name="po_qty" class="form-control po_qty" type="text">
												</div>
											</div>
											<div class="col-md-1" style="margin-top:3px">
												<div class="kt-form__group--inline">
													<div class="kt-form__control">
														<select name="po_unit" class="form-control kt-select2 po_unit-select2"> 
						                                </select>
													</div>
												</div>
											</div>
											<div class="col-md-1" style="margin-top:3px">
												<div class="input-group">
						                            <input placeholder="Price" name="po_rate" class="form-control po_rate" type="text">
												</div>
											</div>
											<div class="col-md-1" style="margin-top:3px">
												<div class="input-group">
													<input placeholder="Discount" name="po_dsc" class="form-control po_dsc" type="text">
												</div>
											</div>

											<div class="col-md-1" style="margin-top:3px">
												<div class="input-group">
													<input placeholder="HSN" name="po_hsn" class="form-control po_hsn" type="text">
												</div>
											</div>
											<div class="col-md-1" style="margin-top:3px">
												<div class="kt-form__group--inline">
													<div class="kt-form__control">
														<select name="po_tax" class="form-control kt-select2 po_tax-select2"> 
						                                    <option></option>
															<option value="0">0</option>
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
						                            <input type="text" class="form-control po_gross_pr" name="po_gross_pr" style="background-color: #eee" readonly>
												</div>
											</div>	
											<div class="col-md-1" style="margin-top:3px">
												<div class="input-group">
						                            <input type="text" class="form-control po_tax_pr" name="po_tax_pr" style="background-color: #eee"  readonly>
												</div>
											</div>

											<input type="text" name="po_cgst" style="display: none">
											<input type="text" name="po_sgst" style="display: none">
											<input type="text" name="po_igst" style="display: none">
											<div class="col-md-1" style="margin-top:3px">
												<div class="input-group">
						                            <input type="text" class="form-control po_total_pr" name="po_total_pr" style="background-color: #eee" readonly>
												</div>
											</div>
											<div class="col-md-1" style="margin-top:3px">
												<div class="kt-form__group--inline">
													<div class="kt-form__control">
														<select name="po_display_make" class="form-control kt-select2 po_display_make-select2"> 
                                                            <option></option>
                                                            <option value="1">Show</option>
                                                            <option value="0">Hide</option>
                                                        </select>
													</div>
												</div>
											</div>
											<div class="col-md-1" style="margin-top:3px; text-align:center">
												<a href="javascript:;" data-repeater-delete="" class="btn-sm btn btn-label-danger btn-bold po_delete">
													<i class="la la-trash-o"></i>
												</a>
											</div>
										</div>
									</div>
								</div>
								<div >
									<div class="col-lg-4">
										<a href="javascript:;" data-repeater-create="purchase_order" id="po_btn_add" class="btn btn-bold btn-sm btn-label-brand">
											<i class="la la-plus"></i> Add
										</a>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group row" style="margin-bottom: 2px;">
							<div class="col-md-2">
                                <div class="form-group">
									<div class="kt-input-icon">
                                    	<input name="bulk_discount" placeholder="Bulk Discount" id="bulk_discount" class="form-control" type="text">
                                    </div>
                                </div>
                            </div>
							<div class="col-md-1">
								<button id="bulk_discount_btn" type="button" class="btn btn-success">Implement</button>
							</div>
							<div class="col-md-7">
								<div class="form-control" style="text-align:right; border: none;">Gross Total :</div>
								
							</div>
							<div class="col-md-2">
								<div class="input-group">
                                    <input type="text" class="form-control po_gross_final" name="po_gross_final" style="background-color: #eee; text-align:right;" readonly>
								</div>
							</div>	
						</div>
						<div class="form-group row" style="margin-bottom: 2px;">
							<div class="col-md-10">
								<div class="form-control" style="text-align:right; border: none;">Packaging & Forwarding :</div>
								
							</div>
							<div class="col-md-2">
								<div class="input-group">
						            <input type="text" class="form-control" name="po_pf" style="text-align:right;" id="po_pf">
								</div>
							</div>	
						</div>
						<input type="text" name="po_pf_cgst" id="po_pf_cgst" style="display: none">
						<input type="text" name="po_pf_sgst" id="po_pf_sgst" style="display: none">
						<input type="text" name="po_pf_igst" id="po_pf_igst" style="display: none">
						<div class="form-group row" style="margin-bottom: 2px;">
							<div class="col-md-10">
								<div class="form-control" style="text-align:right; border: none;">Freight :</div>
								
							</div>
							<div class="col-md-2">
								<div class="input-group">
						            <input type="text" class="form-control" name="po_freight" style="text-align:right;" id="po_freight">
								</div>
							</div>
						</div>
						<input type="text" name="po_freight_cgst" id="po_freight_cgst" style="display: none">
						<input type="text" name="po_freight_sgst" id="po_freight_sgst" style="display: none">
						<input type="text" name="po_freight_igst" id="po_freight_igst" style="display: none">
						<div class="form-group row" style="margin-bottom: 2px;">
							<div class="col-md-10">
								<div class="form-control" style="text-align:right; border: none;">Total Tax :</div>
								
							</div>
							<div class="col-md-2">
								<div class="input-group">
						            <input type="text" class="form-control po_tax_final" name="po_tax_final" style="background-color: #eee; text-align:right;" readonly>
								</div>
							</div>
						</div>
						<div class="form-group row" style="margin-bottom: 2px;">
							<div class="col-md-10">
								<div class="form-control" style="text-align:right; border: none;">Round Off :</div>
							</div>
							<div class="col-md-2">
								<div class="input-group">
						            <input type="text" class="form-control" name="po_round" style="text-align:right;" id="po_round">
								</div>
							</div>
						</div>
						<div class="form-group row" style="margin-bottom: 2px;">
							<div class="col-md-10">
								<div class="form-control" style="text-align:right; border: none;">Grand Total :</div>
								
							</div>
							<div class="col-md-2">
								<div class="input-group">
						            <input type="text" class="form-control po_total_final" name="po_total_final" style="background-color: #eee; text-align:right;" readonly>
								</div>
							</div>
						</div>
						<br/>
						<div class="form-group row">
							<div class="col-lg-4">
								<label>Mode/Terms of Payment:</label>
								<div class="kt-input-icon">
									<input type="text" class="form-control" id="po_mode" name="po_mode">
								</div>
							</div>
							<div class="col-lg-4">
								<label>Supplier's Ref No:</label>
								<div class="kt-input-icon">
									<input type="text" class="form-control" id="po_supplier_ref" name="po_supplier_ref">
								</div>
							</div>
							<div class="col-lg-4">
								<label>Other Reference(s):</label>
								<div class="kt-input-icon">
									<input type="text" class="form-control" id="po_other_ref" name="po_other_ref">
								</div>
							</div>
						</div>
						<div class="form-group row">
							<div class="col-lg-4">
								<label>Despatch Through:</label>
								<div class="kt-input-icon">
									<input type="text" class="form-control" id="po_despatch" name="po_despatch">
								</div>
							</div>
							<div class="col-lg-4">
								<label>Destination:</label>
								<div class="kt-input-icon">
									<input type="text" class="form-control" id="po_destination" name="po_destination">
								</div>
							</div>
							<div class="col-lg-4">
								<label>Terms of Delivery:</label>
								<div class="kt-input-icon">
									<input type="text" class="form-control" id="po_terms" name="po_terms">
								</div>
							</div>
						</div>
					</div>
		            <div class="kt-portlet__foot">
						<div class="kt-form__actions" style="float:right;">
							<button id="purchase_order_submit" type="submit" class="btn btn-primary">Submit</button>
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
											<select class="form-control bootstrap-select" id="kt_purchase_order_product">
												<option></option>
											</select>
										</div>
									</div>
									<div class="col-md-3 kt-margin-b-20-tablet-and-mobile">
										<div class="kt-form__control">
											<select class="form-control bootstrap-select" id="kt_purchase_order_user">
												<option></option>
											</select>
										</div>
									</div>
									<div class="col-md-3 kt-margin-b-20-tablet-and-mobile">
										<div class="kt-form__control">
											<select class="form-control bootstrap-select" id="kt_purchase_order_status">
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
				<div class="kt-datatable" id="purchase_order_datatable"></div>
				<!--end: Datatable -->
				
			</div>
		</div>
		<!--end::Portlet-->

		begin::Portlet
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
								</div>
							</div>
						</div>
					</div>
					<!--end: Search Form -->
				</div>

				<!--begin: Datatable -->
				<div class="kt-datatable" id="purchase_bag_datatable"></div>
				<!--end: Datatable -->
				
			</div>
		</div>
		<!--end::Portlet-->

	</div>
	<!--End::Dashboard 1-->
</div>
<!-- end:: Content -->

<!--begin::Delete Purchase Order Modal-->
<div class="modal fade" id="delete_purchase_order" tabindex="-1" role="dialog" aria-labelledby="deletePurchaseOrderModal" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="deletePurchaseOrderModal" >Delete Purchase Order</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				</button>
			</div>
			<div class="modal-body">
				<!--begin::Form-->
				<form class="kt-form kt-form--label-right">
					<div class="kt-portlet__body">
						Are you sure you want to delete this purchase order ?
					</div>
				</form>

				<!--end::Form-->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button id="delete_purchase_order_submit" type="button" class="btn btn-primary">Delete</button>
			</div>
		</div>
	</div>
</div>
<!--end::Delete Purchase Order Modal-->

<!--begin::Delete Purchase Bag Modal-->
<div class="modal fade" id="delete_item_purchase_bag" tabindex="-1" role="dialog" aria-labelledby="deletePurchaseBagModal" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="deletePurchaseBagModal" >Delete Item</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				</button>
			</div>
			<div class="modal-body">
				<!--begin::Form-->
				<form class="kt-form kt-form--label-right">
					<div class="kt-portlet__body">
						Are you sure you want to delete this item ?
					</div>
				</form>

				<!--end::Form-->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button id="delete_purchase_bag_submit" type="button" class="btn btn-primary">Delete</button>
			</div>
		</div>
	</div>
</div>
<!--end::Delete Purchase Bag Modal-->

<!--begin::Send Email Modal-->
<form class="kt-form kt-form--label-right" id="send_po_email">
	<div class="modal fade" id="kt_modal_po_email" tabindex="-1" role="dialog" aria-hidden="true">
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
							<input name="po_em_id" id="po_em_id" class="form-control" type="text" style="display:none">
							<div class="form-group row">
								<div class="col-sm-6">
	                                <div class="form-group">
	                                    <label>Send To</label>
										<div class="kt-input-icon">
	                                    	<input name="po_em_email" id="po_em_email" placeholder="Email Address" class="form-control" type="text">
	                                    </div>
	                                </div>
	                            </div>
	                            <div class="col-sm-6">
	                                <div class="form-group">
	                                    <label>Subject</label>
										<div class="kt-input-icon">
	                                    	<input name="po_em_subject" id="po_em_subject"  class="form-control" type="text">
	                                    </div>
	                                </div>
	                            </div>
	                            <div class="col-sm-6">
	                                <div class="form-group">
	                                    <label>CC</label>
										<div class="kt-input-icon">
	                                    	<input name="po_em_email_cc" id="po_em_email_cc" placeholder="Email Address"  class="form-control" type="text">
	                                    </div>
	                                </div>
	                            </div>
	                            <div class="col-sm-6">
	                                <div class="form-group">
	                                    <label>BCC</label>
										<div class="kt-input-icon">
	                                    	<input name="po_em_email_bcc" id="po_em_email_bcc" placeholder="Email Address" class="form-control" type="text">
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
							<div class="form-group row">
	                            <div class="col-sm-12">
	                                <div class="form-group">
										<div class="kt-input-icon">
	                                    	<input name="po_em_message" id="po_em_message" class="summernote">

	                                    </div>
	                                </div>
	                            </div>
	                        </div>
						</div>
					<!--end::Form-->
				</div>
				<div class="modal-footer">
					<button id="po_email_submit" type="submit" class="btn btn-primary">Send</button>
				</div>
			</div>
		</div>
	</div>
</form>
<!--end::Send Email Modal-->

<!--begin::Cancel Purchase Order Modal-->
<div class="modal fade" id="cancel_purchase_order" tabindex="-1" role="dialog" aria-labelledby="cancelPurchaseOrderModal" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="cancelPurchaseOrderModal" >Cancel Purchasse Order</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				</button>
			</div>
			<div class="modal-body">
				<!--begin::Form-->
				<form class="kt-form kt-form--label-right">
					<div class="kt-portlet__body">
						Are you sure you want to cancel this purchase order ?
					</div>
				</form>

				<!--end::Form-->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button id="cancel_purchase_order_submit" type="button" class="btn btn-primary">Delete</button>
			</div>
		</div>
	</div>
</div>
<!--end::Cancel Purchase Order Modal-->

<!--begin::Send whatsapp Modal-->
<form class="kt-form kt-form--label-right" id="send_purchase_order_whatsapp">
	<div class="modal fade" id="kt_modal_purchase_order_whatsapp" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Send Whatsapp</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					</button>
				</div>
				<div class="modal-body">
					<!--begin::Form-->
                    	<input name="po_no_whatsapp" id="po_no_whatsapp" style="display:none" class="form-control" type="text">

						<div class="kt-portlet__body">
							<div class="form-group row" style="margin-bottom: 0">
								<div class="col-sm-6">
	                                <div class="form-group">
	                                    <label>Mobile No (Separted by comma)</label>
										<div class="kt-input-icon">
	                                    	<input name="po_whatsapp_number" id="po_whatsapp_number" placeholder="Enter Whatsapp Number(s)" class="form-control" type="text">
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
						</div>
					<!--end::Form-->
				</div>
				<div class="modal-footer">
					<button id="po_whatsapp_submit" type="submit" class="btn btn-primary">Send</button>
				</div>
			</div>
		</div>
	</div>
</form>
<!--end::Send whatapp Modal-->