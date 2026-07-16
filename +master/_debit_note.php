<!-- begin:: Content --> 
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

	<!--Begin::Dashboard 1-->
	<div class="col-lg-12">
		<!--begin::Portlet-->
		<div class="kt-portlet" id="kt_portlet_add_dn">
			<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title">
						Debit Note
					</h3>
				</div>
				<div class="kt-portlet__head-toolbar">
					<div class="kt-portlet__head-group">
						<a href="#" data-ktportlet-tool="toggle" class="btn btn-sm btn-icon btn-clean btn-icon-md"><i class="la la-angle-down"></i></a>
					</div>
				</div>
			</div>
			<!--begin::Form-->
			<form class="kt-form" id="add_debit_note">
				<div class="kt-portlet__body">
					<div class="kt-section kt-section--first">
						<input type="text" name="edit_dn_id" id="edit_dn_id" style="display: none">
						<input type="text" name="dn_state" id="dn_state" style="display: none">

						<div class="form-group row">
							<div class="col-sm-3">
                                <div class="form-group">
                                    <label>Supplier</label>
									<div class="kt-input-icon">
	                                    <select class="form-control kt-select2 supplier-select2" name="dn_supplier" id="dn_supplier">
	                                    	<option></option>
	                                    </select>
	                                </div>
                                    <span class="form-text text-muted">Please enter name of the supplier..</span>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Purchase Invoice #</label>
									<div class="kt-input-icon">
                                    	<input name="dn_pi_no" placeholder="Purchase Invoice #" id="dn_pi_no" class="form-control" type="text">
                                    </div>
                                    <span class="form-text text-muted">Please enter the purchase invoice #..</span>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Purchase Invoice Date</label>
									<div class="kt-input-icon">
                                    	<input placeholder="Purchase Invoice Date" name="dn_pi_date" id="dn_pi_date" class="form-control date-picker" type="text" data-date-end-date="+3m" value="<?php echo date('d-m-Y', strtotime('today')); ?>">
                                    </div>
                                    <span class="form-text text-muted">Please enter the purchase invoice date..</span>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Debit Note #</label>
									<div class="kt-input-icon">
                                    	<input name="dn_dn_no" placeholder="Debit Note #" id="dn_dn_no" class="form-control" type="text">
                                    </div>
                                    <span class="form-text text-muted">Please enter the debit note#..</span>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Debit Note Date</label>
									<div class="kt-input-icon">
                                    	<input placeholder="Debit Note Date" name="dn_date" id="dn_date" class="form-control date-picker" type="text" data-date-end-date="+3m" value="<?php echo date('d-m-Y', strtotime('today')); ?>">
                                    </div>
                                    <span class="form-text text-muted">Please enter the debit note date..</span>
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
							<div id="kt_repeater_dn">
								<div class="form-group form-group-last row" id="kt_repeater_1">
									<div data-repeater-list="debit_note" id="debit_note_list" class="col-lg-12">
										<div data-repeater-item class="form-group row align-items-center" style="border-bottom: 1px dashed #5d78ff; padding-bottom:1rem; margin-bottom: 1rem;">
											<div class="col-md-1" style="margin-top:3px; text-align:center">
												<input type="text" class="form-control" name="dn_sn" value="1" style="border: none;font-weight: 900;text-align: center;" readonly="">
											</div>
											<div class="col-md-3">
												<div class="kt-form__group--inline">
													<div class="kt-form__control">
														<select class="form-control kt-select2 dn_product_name-select2" name="dn_product_name">
						                            	</select>
													</div>
												</div>
											</div>
											<div class="col-md-4" >
												<input type="text" class="form-control" name="dn_product_description" placeholder="Product Name">
											</div>	
											<div class="col-md-4">
												<textarea class="form-control" placeholder="Product Description" name="dn_product_add_description" rows='1'></textarea>
											</div>
											<div class="col-md-1" style="margin-top:3px">
											</div>
											<div class="col-md-1" style="margin-top:3px">
												<div class="input-group">
													<input placeholder="Qty" name="dn_qty" class="form-control dn_qty" type="text">
												</div>
											</div>
											<div class="col-md-1" style="margin-top:3px">
												<div class="kt-form__group--inline">
													<div class="kt-form__control">
														<select name="dn_unit" class="form-control kt-select2 dn_unit-select2"> 
						                                </select>
													</div>
												</div>
											</div>
											<div class="col-md-1" style="margin-top:3px">
												<div class="input-group">
						                            <input placeholder="Price" name="dn_rate" class="form-control dn_rate" type="text">
												</div>
											</div>
											<div class="col-md-1" style="margin-top:3px">
												<div class="input-group">
													<input placeholder="Discount" name="dn_dsc" class="form-control dn_dsc" type="text">
												</div>
											</div>
											<div class="col-md-1" style="margin-top:3px">
												<div class="input-group">
													<input placeholder="HSN" name="dn_hsn" class="form-control dn_hsn" type="text">
												</div>
											</div>
											<div class="col-md-1" style="margin-top:3px">
												<div class="kt-form__group--inline">
													<div class="kt-form__control">
														<select name="dn_tax" class="form-control kt-select2 dn_tax-select2"> 
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
						                            <input type="text" class="form-control dn_gross_pr" name="dn_gross_pr" style="background-color: #eee" readonly>
												</div>
											</div>	
											<div class="col-md-1" style="margin-top:3px">
												<div class="input-group">
						                            <input type="text" class="form-control dn_tax_pr" name="dn_tax_pr" style="background-color: #eee"  readonly>
												</div>
											</div>
											<input type="text" name="dn_cgst" style="display: none">
											<input type="text" name="dn_sgst" style="display: none">
											<input type="text" name="dn_igst" style="display: none">
											<div class="col-md-1" style="margin-top:3px">
												<div class="input-group">
						                            <input type="text" class="form-control dn_total_pr" name="dn_total_pr" style="background-color: #eee" readonly>
												</div>
											</div>
											<div class="col-md-1" style="margin-top:3px">
												<div class="kt-form__group--inline">
													<div class="kt-form__control">
														<select name="dn_display_make" class="form-control kt-select2 dn_display_make-select2"> 
                                                            <option></option>
                                                            <option value="1">Show</option>
                                                            <option value="0">Hide</option>
                                                        </select>
													</div>
												</div>
											</div>
											<div class="col-md-1" style="margin-top:3px; text-align:center">
												<a href="javascript:;" data-repeater-delete="" class="btn-sm btn btn-label-danger btn-bold dn_delete">
													<i class="la la-trash-o"></i>
												</a>
											</div>
										</div>
									</div>
								</div>
								<div >
									<div class="col-lg-4">
										<a href="javascript:;" data-repeater-create="debit_note" id="dn_btn_add" class="btn btn-bold btn-sm btn-label-brand">
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
                                    <input type="text" class="form-control dn_gross_final" name="dn_gross_final" style="background-color: #eee; text-align:right;" readonly>
								</div>
							</div>	
						</div>
						<div class="form-group row" style="margin-bottom: 2px;">
							<div class="col-md-10">
								<div class="form-control" style="text-align:right; border: none;">Packaging & Forwarding :</div>
								
							</div>
							<div class="col-md-2">
								<div class="input-group">
						            <input type="text" class="form-control" name="dn_pf" style="text-align:right;" id="dn_pf">
								</div>
							</div>	
						</div>
						<div class="form-group row" style="margin-bottom: 2px;">
							<div class="col-md-10">
								<div class="form-control" style="text-align:right; border: none;">Freight :</div>
								
							</div>
							<div class="col-md-2">
								<div class="input-group">
						            <input type="text" class="form-control" name="dn_freight" style="text-align:right;" id="dn_freight">
								</div>
							</div>
						</div>
						<div class="form-group row" style="margin-bottom: 2px;">
							<div class="col-md-10">
								<div class="form-control" style="text-align:right; border: none;">Total Tax :</div>
								
							</div>
							<div class="col-md-2">
								<div class="input-group">
						            <input type="text" class="form-control dn_tax_final" name="dn_tax_final" style="background-color: #eee; text-align:right;" readonly>
								</div>
							</div>
						</div>
						<div class="form-group row" style="margin-bottom: 2px;">
							<div class="col-md-10">
								<div class="form-control" style="text-align:right; border: none;">Round Off :</div>
							</div>
							<div class="col-md-2">
								<div class="input-group">
						            <input type="text" class="form-control" name="dn_round" style=" text-align:right;" id="dn_round" >
								</div>
							</div>
						</div>
						<div class="form-group row" style="margin-bottom: 2px;">
							<div class="col-md-10">
								<div class="form-control" style="text-align:right; border: none;">Grand Total :</div>
								
							</div>
							<div class="col-md-2">
								<div class="input-group">
						            <input type="text" class="form-control dn_total_final" name="dn_total_final" style="background-color: #eee; text-align:right;" readonly>
								</div>
							</div>
						</div>
					</div>
		            <div class="kt-portlet__foot">
						<div class="kt-form__actions" style="float:right;">
							<button id="debit_note_submit" type="submit" class="btn btn-primary">Submit</button>
						</div>
					</div>
				</div>
			</form>
			<!--end::Form-->
		</div>
		<!--end::Portlet-->

		<!--begin::Portlet-->
		<div class="kt-portlet kt-portlet--mobile">
			<div class="kt-portlet__body kt-portlet__body--fit">
				<div class="kt-portlet__body">
					<!--begin: Search Form -->
					<div class="kt-form kt-form--label-right kt-margin-t-20 kt-margin-b-10">
						<div class="row align-items-center">
							<div class="col-xl-12 order-2 order-xl-1">
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
											<select class="form-control bootstrap-select" id="kt_debit_note_product">
												<option></option>
											</select>
										</div>
									</div>
									<div class="col-md-3 kt-margin-b-20-tablet-and-mobile">
										<div class="kt-form__control">
											<select class="form-control bootstrap-select" id="kt_debit_note_user">
												<option></option>
											</select>
										</div>
									</div>
									<div class="col-md-3 kt-margin-b-20-tablet-and-mobile">
										<div class="kt-form__control">
											<select class="form-control bootstrap-select" id="kt_debit_note_status">
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
				<div class="kt-datatable" id="debit_note_datatable"></div>
				<!--end: Datatable -->
				
			</div>
		</div>

	</div>
	<!--End::Dashboard 1-->
</div>
<!-- end:: Content -->

<!--begin::Delete Debit Note Modal-->
<div class="modal fade" id="delete_debit_note" tabindex="-1" role="dialog" aria-labelledby="deleteDebitNoteModal" aria-hidden="true">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="deleteDebitNoteModal" >Delete Debit Note</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				</button>
			</div>
			<div class="modal-body">
				<!--begin::Form-->
				<form class="kt-form kt-form--label-right">
					<div class="kt-portlet__body">
						Are you sure you want to delete this debit note ?
					</div>
				</form>

				<!--end::Form-->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button id="delete_debit_note_submit" type="button" class="btn btn-primary">Delete</button>
			</div>
		</div>
	</div>
</div>
<!--end::Delete Debit Note Modal-->












