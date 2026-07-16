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
    
            if($menu_access['proforma_invoice']['create'] == '1' || $userlevel == "sadmin_df56fdg"){
    
                
        ?>
		<!--begin::Portlet-->
		<div class="kt-portlet" id="kt_portlet_add_pr">
			
			<!--begin::Form-->
			<form class="kt-form" id="add_proforma_invoice">
				<div class="kt-portlet__body">
					<div class="kt-section kt-section--first">
						<input type="text" name="edit_pr_id" id="edit_pr_id" style="display: none">
						<input type="text" name="pr_state" id="pr_state" style="display: none">
						<!-- <input type="text" name="client_so_no" id="client_so_no" style="display: none"> -->
						<div class="form-group row">
							<div class="col-sm-3">
                                <div class="form-group">
                                    <label>Client</label>
									<div class="kt-input-icon">
	                                    <select class="form-control kt-select2 client-select2" name="pr_client" id="pr_client">
	                                    	<option></option>
	                                    </select>
	                                </div>
                                    <span class="form-text text-muted">Please enter name of the client..</span>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Sales Order#</label>
									<div class="kt-input-icon">
	                                    <select class="form-control kt-select2 kt-select2-multiple pr_sales_order-select2" name="pr_sales_order[]" id="pr_sales_order">
	                                    </select>
	                                </div>
                                    <span class="form-text text-muted">Please select the sales order#..</span>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Proforma Invoice #</label>
									<div class="kt-input-icon">
                                    	<input name="pr_no" placeholder="Proforma Invoice #" id="pr_no" class="form-control" type="text" readonly>
                                    </div>
                                    <span class="form-text text-muted">Please enter the proforma invice #..</span>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Proforma Invoice Date</label>
									<div class="kt-input-icon">
                                    	<input placeholder="Proforma Invoice Date" name="pr_date" id="pr_date" class="form-control date-picker" type="text" data-date-end-date="+3m" value="<?php echo date('d-m-Y', strtotime('today')); ?>">
                                    </div>
                                    <span class="form-text text-muted">Please enter the proforma invoice date..</span>
                                </div>
                            </div>
                        </div>
						<div class="form-group row">
						<div class="col-sm-3">
                                <div class="form-group">
                                    <label>Mobile No#</label>
									<div class="kt-input-icon">
                                    	<input name="mobile" placeholder="Mobile No #" id="mobile" class="form-control" type="text" >
                                    </div>
                                    <span class="form-text text-muted">Please enter the clients Mobile No#..</span>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Client SO #</label>
									<div class="kt-input-icon">
                                    	<input name="client_so_no" placeholder="Client SO #" id="client_so_no" class="form-control" type="text" >
                                    </div>
                                    <span class="form-text text-muted">Please enter the client sales order#..</span>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Address 1</label>
									<div class="kt-input-icon">
                                    	<input placeholder="Address 1" class="form-control" name="address_1" id="address_1" type="text">
                                    </div>
                                    <span class="form-text text-muted">Please enter the address line 1..</span>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Address 2</label>
									<div class="kt-input-icon">
                                    	<input placeholder="Address 2" class="form-control" name="address_2" id="address_2" type="text">
                                    </div>
                                    <span class="form-text text-muted">Please enter the address line 2..</span>
                                </div>
                            </div>
                           
                        </div>
						<div class="form-group row">
						<div class="col-sm-3">
                                <div class="form-group">
                                    <label>Country</label>
									<div class="kt-input-icon">
                                    	<input placeholder="Country" class="form-control" name="country" id="country" type="text">
                                    </div>
                                    <span class="form-text text-muted">Please enter the country..</span>
                                </div>
                            </div>
							<div class="col-sm-3">
                                <div class="form-group">
                                    <label>State</label>
									<div class="kt-input-icon">
                                    	<input placeholder="State" class="form-control" name="state" id="state" type="text">
                                    </div>
                                    <span class="form-text text-muted">Please enter the state..</span>
                                </div>
                            </div>
							<div class="col-sm-3">
                                <div class="form-group">
                                    <label>City</label>
									<div class="kt-input-icon">
                                    	<input placeholder="City" class="form-control" name="city" id="city" type="text">
                                    </div>
                                    <span class="form-text text-muted">Please enter the city..</span>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Pincode</label>
									<div class="kt-input-icon">
                                    	<input placeholder="Pincode" class="form-control" name="pincode" id="pincode" type="text">
                                    </div>
                                    <span class="form-text text-muted">Please enter the pincode..</span>
                                </div>
                            </div>
						</div>
						
						<div class="form-group row">
							<div id="kt_repeater_pr">
								<div class="form-group form-group-last row" id="kt_repeater_1">
									<div data-repeater-list="proforma_invoice" id="proforma_invoice_list" class="col-lg-12">
										<div data-repeater-item class="form-group row align-items-center" style="border-bottom: 1px dashed #5d78ff; padding-bottom:1rem; margin-bottom: 1rem;">
											<div class="col-md-1" style="margin-top:3px; text-align:center">
												<input type="text" class="form-control" name="pr_sn" value="1" style="border: none;font-weight: 900;text-align: center;" readonly="">
											</div>
											<div class="col-md-5">
												<div class="kt-form__group--inline">
													<div class="kt-form__control">
														<select class="form-control kt-select2 pr_product_name-select2" name="pr_product_name">
						                            	</select>
													</div>
												</div>
												<span class="product_stock" name="product_stock"></span>
											</div>
											<div class="col-md-6" >
												<input type="textarea" class="form-control" name="pr_product_description" placeholder="Product Decription">
											</div>	
											
											<div class="col-md-1" style="margin-top:3px">
											</div>
											<div class="col-md-1" style="margin-top:3px">
												<div class="input-group">
													<input placeholder="Qty" name="pr_qty" class="form-control pr_qty" type="text">
												</div>
											</div>
											<div class="col-md-1" style="margin-top:3px">
												<div class="kt-form__group--inline">
													<div class="kt-form__control">
														<select name="pr_unit" class="form-control kt-select2 pr_unit-select2"> 
						                                </select>
													</div>
												</div>
											</div>
											<div class="col-md-1" style="margin-top:3px">
												<div class="input-group">
						                            <input placeholder="Price" name="pr_rate" class="form-control pr_rate" type="text">
												</div>
											</div>
											<div class="col-md-1" style="margin-top:3px">
												<div class="input-group">
													<input placeholder="Discount" name="pr_dsc" class="form-control pr_dsc" type="text">
												</div>
											</div>
											<div class="col-md-1" style="margin-top:3px">
												<div class="input-group">
													<input placeholder="HSN" name="pr_hsn" class="form-control pr_hsn" type="text">
												</div>
											</div>
											<div class="col-md-1" style="margin-top:3px">
												<div class="kt-form__group--inline">
													<div class="kt-form__control">
														<select name="pr_tax" class="form-control kt-select2 pr_tax-select2"> 
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
						                            <input type="text" class="form-control pr_gross_pr" name="pr_gross_pr" style="background-color: #eee" readonly>
												</div>
											</div>	
											<div class="col-md-1" style="margin-top:3px">
												<div class="input-group">
						                            <input type="text" class="form-control pr_tax_pr" name="pr_tax_pr" style="background-color: #eee"  readonly>
												</div>
											</div>
											<input type="text" name="pr_cgst" style="display: none">
											<input type="text" name="pr_sgst" style="display: none">
											<input type="text" name="pr_igst" style="display: none">
											<div class="col-md-1" style="margin-top:3px">
												<div class="input-group">
						                            <input type="text" class="form-control pr_total_pr" name="pr_total_pr" style="background-color: #eee" readonly>
												</div>
											</div>
											<div class="col-md-1" style="margin-top:3px">
												<div class="kt-form__group--inline">
													<div class="kt-form__control">
														<select name="pr_display_make" class="form-control kt-select2 pr_display_make-select2"> 
                                                            <option></option>
                                                            <option value="1">Show</option>
                                                            <option value="0">Hide</option>
                                                        </select>
													</div>
												</div>
											</div>
											<div class="col-md-1" style="margin-top:3px; text-align:center">
												<a href="javascript:;" data-repeater-delete="" class="btn-sm btn btn-label-danger btn-bold pr_delete">
													<i class="la la-trash-o"></i>
												</a>
											</div>
										</div>
									</div>
								</div>
								<div >
									<div class="col-lg-4">
										<a href="javascript:;" data-repeater-create="proforma_invoice" id="pr_btn_add" class="btn btn-bold btn-sm btn-label-brand">
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
                                    <input type="text" class="form-control pr_gross_final" name="pr_gross_final" style="background-color: #eee; text-align:right;" readonly>
								</div>
							</div>	
						</div>
						<div class="form-group row" style="margin-bottom: 2px;">
							<div class="col-md-10">
								<div class="form-control" style="text-align:right; border: none;">Packaging & Forwarding :</div>
								
							</div>
							<div class="col-md-2">
								<div class="input-group">
						            <input type="text" class="form-control" name="pr_pf" style="text-align:right;" id="pr_pf">
								</div>
							</div>	
						</div>
						<input type="text" name="pr_pf_cgst" id="pr_pf_cgst" style="display: none">
						<input type="text" name="pr_pf_sgst" id="pr_pf_sgst" style="display: none">
						<input type="text" name="pr_pf_igst" id="pr_pf_igst" style="display: none">
						<div class="form-group row" style="margin-bottom: 2px;">
							<div class="col-md-10">
								<div class="form-control" style="text-align:right; border: none;">Freight :</div>
								
							</div>
							<div class="col-md-2">
								<div class="input-group">
						            <input type="text" class="form-control" name="pr_freight" style="text-align:right;" id="pr_freight">
								</div>
							</div>
						</div>
						<input type="text" name="pr_freight_cgst" id="pr_freight_cgst" style="display: none">
						<input type="text" name="pr_freight_sgst" id="pr_freight_sgst" style="display: none">
						<input type="text" name="pr_freight_igst" id="pr_freight_igst" style="display: none">
						<div class="form-group row" style="margin-bottom: 2px;">
							<div class="col-md-10">
								<div class="form-control" style="text-align:right; border: none;">Total Tax :</div>
								
							</div>
							<div class="col-md-2">
								<div class="input-group">
						            <input type="text" class="form-control pr_tax_final" name="pr_tax_final" style="background-color: #eee; text-align:right;" readonly>
								</div>
							</div>
						</div>
						<div class="form-group row" style="margin-bottom: 2px;">
							<div class="col-md-10">
								<div class="form-control" style="text-align:right; border: none;">Round Off :</div>
							</div>
							<div class="col-md-2">
								<div class="input-group">
						            <input type="text" class="form-control" name="pr_round" style="text-align:right;" id="pr_round">
								</div>
							</div>
						</div>
						<div class="form-group row" style="margin-bottom: 2px;">
							<div class="col-md-10">
								<div class="form-control" style="text-align:right; border: none;">Grand Total :</div>
								
							</div>
							<div class="col-md-2">
								<div class="input-group">
						            <input type="text" class="form-control pr_total_final" name="pr_total_final" style="background-color: #eee; text-align:right;" readonly>
								</div>
							</div>
						</div>
					</div>
		            <div class="kt-portlet__foot">
						<div class="kt-form__actions" style="float:right;">
							<button id="proforma_invoice_submit" type="submit" class="btn btn-primary">Submit</button>
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
									<div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
										<div class="kt-input-icon kt-input-icon--left">
											<input type="text" class="form-control" placeholder="Search..." id="generalSearch">
											<span class="kt-input-icon__icon kt-input-icon__icon--left">
												<span><i class="la la-search"></i></span>
											</span>
										</div>
									</div>
									<div class="col-md-3 kt-margin-b-20-tablet-and-mobile">
										<div class="kt-form__control">
											<select class="form-control bootstrap-select" id="kt_proforma_invoice_product">
												<option></option>
											</select>
										</div>
									</div>
									<div class="col-md-3 kt-margin-b-20-tablet-and-mobile">
										<div class="kt-form__control">
											<select class="form-control bootstrap-select" id="kt_proforma_invoice_user">
												<option></option>
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
				<div class="kt-datatable" id="proforma_invoice_datatable"></div>
				<!--end: Datatable -->
				
			</div>
		</div>

		<!--end::Portlet-->
	</div>
	<!--End::Dashboard 1-->
</div>
<!-- end:: Content -->

<!--begin::Delete Proforma Invoice Modal-->
<div class="modal fade" id="delete_proforma_invoice" tabindex="-1" role="dialog" aria-labelledby="deleteProformaInvoiceModal" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="deleteProformaInvoiceModal" >Delete Proforma Invoice</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				</button>
			</div>
			<div class="modal-body">
				<!--begin::Form-->
				<form class="kt-form kt-form--label-right">
					<div class="kt-portlet__body">
						Are you sure you want to delete this proforma ?
					</div>
				</form>

				<!--end::Form-->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button id="delete_proforma_invoice_submit" type="button" class="btn btn-primary">Delete</button>
			</div>
		</div>
	</div>
</div>
<!--end::Delete Proforma Invoice Modal-->

<!--begin::Send Email Modal-->
<form class="kt-form kt-form--label-right" id="send_pr_email">
	<div class="modal fade" id="kt_modal_pr_email" tabindex="-1" role="dialog" aria-hidden="true">
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
							<input name="pr_em_id" id="pr_em_id" class="form-control" type="text" style="display:none">
							<div class="form-group row">
								<div class="col-sm-6">
	                                <div class="form-group">
	                                    <label>Send To</label>
										<div class="kt-input-icon">
	                                    	<input name="pr_em_email" id="pr_em_email" placeholder="Email Address" class="form-control" type="text">
	                                    </div>
	                                </div>
	                            </div>
	                            <div class="col-sm-6">
	                                <div class="form-group">
	                                    <label>Subject</label>
										<div class="kt-input-icon">
	                                    	<input name="pr_em_subject" id="pr_em_subject"  class="form-control" type="text">
	                                    </div>
	                                </div>
	                            </div>
	                            <div class="col-sm-6">
	                                <div class="form-group">
	                                    <label>CC</label>
										<div class="kt-input-icon">
	                                    	<input name="pr_em_email_cc" id="pr_em_email_cc" placeholder="Email Address"  class="form-control" type="text">
	                                    </div>
	                                </div>
	                            </div>
	                            <div class="col-sm-6">
	                                <div class="form-group">
	                                    <label>BCC</label>
										<div class="kt-input-icon">
	                                    	<input name="pr_em_email_bcc" id="pr_em_email_bcc" placeholder="Email Address" class="form-control" type="text">
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
							<div class="form-group row">
	                            <div class="col-sm-12">
	                                <div class="form-group">
										<div class="kt-input-icon">
	                                    	<input name="pr_em_message" id="pr_em_message" class="summernote">

	                                    </div>
	                                </div>
	                            </div>
	                        </div>
						</div>
					<!--end::Form-->
				</div>
				<div class="modal-footer">
					<button id="pr_email_submit" type="submit" class="btn btn-primary">Send</button>
				</div>
			</div>
		</div>
	</div>
</form>
<!--end::Send Email Modal-->


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
<form class="kt-form kt-form--label-right" id="add_pr_note">
	<div class="modal fade" id="kt_modal_pr_note" tabindex="-1" role="dialog" aria-labelledby="AddQnoteModal" aria-hidden="true">
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
							<input name="an_pr_no" id="an_pr_no" class="form-control" type="text" style="display:none">
							<div class="form-group row">
	                            <div class="col-sm-12">
	                                <div class="form-group">
										<div class="kt-input-icon">
	                                    	<textarea name="add_pr_note" placeholder="Note..." id="add_pr_note" class="form-control" type="text" ></textarea>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
						</div>
					<!--end::Form-->
				</div>
				<div class="modal-footer">
					<button id="pr_note_submit" type="submit" class="btn btn-primary">Save</button>
				</div>
			</div>
		</div>
	</div>
</form>
<!--end::Add Notes Modal-->
