<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
	<!--Begin::Dashboard 1-->
	<div class="col-lg-12">
		<!--begin::Portlet-->
		<div class="kt-portlet" id="kt_portlet_add_pi">

			<!--begin::Form-->
			<form class="kt-form" id="add_purchase_quotation" enctype="multipart/form-data">
				<div class="kt-portlet__body">
					<div class="kt-section kt-section--first">
						<input type="text" name="edit_pi_id" id="edit_pi_id" style="display: none">
						<!--<input type="text" name="pi_state" id="pi_state" style="display: none">
						<input type="text" name="purchase_series" id="purchase_series" value="PRIMARY" style="display: none"> -->

						<div class="form-group row">
							<div class="col-sm-3">
								<div class="form-group">
									<label>Supplier</label>
									<div class="kt-input-icon">
										<select class="form-control kt-select2 supplier-select2" name="pi_supplier"
											id="pi_supplier">
											<option></option>
										</select>
									</div>
									<span class="form-text text-muted">Please enter name of the supplier..</span>
								</div>
							</div>
							<div class="col-sm-2">
								<div class="form-group">
									<label>Mobile No#</label>
									<div class="kt-input-icon">
										<input placeholder="Mobile No." name="mobile" id="mobile" class="form-control "
											type="text">
									</div>
									<span class="form-text text-muted">Please enter Mobile No...</span>
								</div>
							</div>
							<div class="col-sm-2">
								<div class="form-group">
									<label>Purchase Quotation#</label>
									<div class="kt-input-icon">
										<input name="purchase_quotation_no" placeholder="Purchase Quotation #"
											id="purchase_quotation_no" class="form-control" type="text">
									</div>
									<span class="form-text text-muted">Please enter the purchase Quotation#..</span>
								</div>
							</div>
							<div class="col-sm-2">
								<div class="form-group">
									<label>Purchase Quotation Date</label>
									<div class="kt-input-icon">
										<input placeholder="Purchase Invoice Date" name="purchase_invoice_date"
											id="purchase_invoice_date" class="form-control date-picker" type="text"
											data-date-end-date="+3m"
											value="<?php echo date('d-m-Y', strtotime('today')); ?>">
									</div>
									<span class="form-text text-muted">Please enter the purchase Quotation date..</span>
								</div>
							</div>

							<div class="col-sm-3">
								<div class="form-group">
									<label>Upload File</label>
									<input type="file" name="quotation_file" id="quotation_file" class="form-control">
									<span class="form-text text-muted">Please upload the quotation file..</span>
								</div>
							</div>


						</div>

						<div class="form-group row">
							<div id="kt_repeater_pi">
								<div class="form-group form-group-last row" id="kt_repeater_1">
									<div data-repeater-list="purchase_invoice" id="purchase_invoice_list"
										class="col-lg-12">
										<div data-repeater-item class="form-group row align-items-center"
											style="border-bottom: 1px dashed #5d78ff; padding-bottom:1rem; margin-bottom: 1rem;">
											<div class="col-md-1" style="margin-top:3px; text-align:center">
												<input type="text" class="form-control" name="pi_sn" value="1"
													style="border: none;font-weight: 900;text-align: center;"
													readonly="">
											</div>
											<div class="col-md-3">
												<div class="kt-form__group--inline">
													<div class="kt-form__control">
														<select class="form-control kt-select2 pi_product_name-select2"
															name="pi_product_name">
														</select>
													</div>
												</div>
											</div>
											<div class="col-md-4">
												<input type="text" class="form-control" name="pi_product_description"
													placeholder="Product Name">
											</div>
											<div class="col-md-4">
												<textarea class="form-control kt_autosize_so"
													placeholder="Product Description" name="pi_product_add_description"
													rows="1"></textarea>
											</div>
											<div class="col-md-1" style="margin-top:3px">
											</div>
											<div class="col-md-1" style="margin-top:3px">
												<div class="input-group">
													<input placeholder="Qty" name="pi_qty" class="form-control pi_qty"
														type="text">
												</div>
											</div>
											<div class="col-md-1" style="margin-top:3px">
												<div class="kt-form__group--inline">
													<div class="kt-form__control">
														<select name="pi_unit"
															class="form-control kt-select2 pi_unit-select2">
														</select>
													</div>
												</div>
											</div>
											<div class="col-md-1" style="margin-top:3px">
												<div class="input-group">
													<input placeholder="Price" name="pi_rate"
														class="form-control pi_rate" type="text">
												</div>
											</div>
											<div class="col-md-1" style="margin-top:3px">
												<div class="input-group">
													<input placeholder="Discount" name="pi_dsc"
														class="form-control pi_dsc" type="text">
												</div>
											</div>

											<div class="col-md-1" style="margin-top:3px">
												<div class="input-group">
													<input placeholder="HSN" name="pi_hsn" class="form-control pi_hsn"
														type="text">
												</div>
											</div>
											<div class="col-md-1" style="margin-top:3px">
												<div class="kt-form__group--inline">
													<div class="kt-form__control">
														<select name="pi_tax"
															class="form-control kt-select2 pi_tax-select2">
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
													<input type="text" class="form-control pi_gross_pr"
														name="pi_gross_pr" style="background-color: #eee" readonly>
												</div>
											</div>
											<div class="col-md-1" style="margin-top:3px">
												<div class="input-group">
													<input type="text" class="form-control pi_tax_pr" name="pi_tax_pr"
														style="background-color: #eee" readonly>
												</div>
											</div>

											<input type="text" name="pi_cgst" style="display: none">
											<input type="text" name="pi_sgst" style="display: none">
											<input type="text" name="pi_igst" style="display: none">
											<div class="col-md-1" style="margin-top:3px">
												<div class="input-group">
													<input type="text" class="form-control pi_total_pr"
														name="pi_total_pr" style="background-color: #eee" readonly>
												</div>
											</div>
											<div class="col-md-1" style="margin-top:3px">
												<div class="kt-form__group--inline">
													<div class="kt-form__control">
														<select name="pi_display_make"
															class="form-control kt-select2 pi_display_make-select2">
															<option></option>
															<option value="1">Show</option>
															<option value="0">Hide</option>
														</select>
													</div>
												</div>
											</div>
											<div class="col-md-1" style="margin-top:3px; text-align:center">
												<a href="javascript:;" data-repeater-delete=""
													class="btn-sm btn btn-label-danger btn-bold pi_delete">
													<i class="la la-trash-o"></i>
												</a>
											</div>
										</div>
									</div>
								</div>
								<div>
									<div class="col-lg-4">
										<a href="javascript:;" data-repeater-create="purchase_invoice" id="pi_btn_add"
											class="btn btn-bold btn-sm btn-label-brand">
											<i class="la la-plus"></i> Add
										</a>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group row" style="margin-bottom: 2px;">
							<div class="col-md-10">
							</div>
							<div class="col-md-2">
								<div class="input-group">
									<a href="javascript:;" id="pi_preview_btn"
										class="btn btn-bold btn-sm btn-label-success" style="width: 100%">
										Calculate
									</a>
								</div>
							</div>
						</div>
						<div class="form-group row" style="margin-bottom: 2px;">
							<div class="col-md-10">
								<div class="form-control" style="text-align:right; border: none;">Gross Total :</div>

							</div>
							<div class="col-md-2">
								<div class="input-group">
									<input type="text" class="form-control pi_gross_final" name="pi_gross_final"
										style="background-color: #eee; text-align:right;" readonly>
								</div>
							</div>
						</div>
						<div class="form-group row" style="margin-bottom: 2px;">
							<div class="col-md-10">
								<div class="form-control" style="text-align:right; border: none;">Packaging & Forwarding
									:</div>

							</div>
							<div class="col-md-2">
								<div class="input-group">
									<input type="text" class="form-control" name="pi_pf" style="text-align:right;"
										id="pi_pf">
								</div>
							</div>
						</div>
						<input type="text" name="pi_pf_cgst" id="pi_pf_cgst" style="display: none">
						<input type="text" name="pi_pf_sgst" id="pi_pf_sgst" style="display: none">
						<input type="text" name="pi_pf_igst" id="pi_pf_igst" style="display: none">
						<div class="form-group row" style="margin-bottom: 2px;">
							<div class="col-md-10">
								<div class="form-control" style="text-align:right; border: none;">Freight :</div>

							</div>
							<div class="col-md-2">
								<div class="input-group">
									<input type="text" class="form-control" name="pi_freight" style="text-align:right;"
										id="pi_freight">
								</div>
							</div>
						</div>
						<input type="text" name="pi_freight_cgst" id="pi_freight_cgst" style="display: none">
						<input type="text" name="pi_freight_sgst" id="pi_freight_sgst" style="display: none">
						<input type="text" name="pi_freight_igst" id="pi_freight_igst" style="display: none">
						<div class="form-group row" style="margin-bottom: 2px;">
							<div class="col-md-10">
								<div class="form-control" style="text-align:right; border: none;">Total Tax :</div>

							</div>
							<div class="col-md-2">
								<div class="input-group">
									<input type="text" class="form-control pi_tax_final" name="pi_tax_final"
										style="background-color: #eee; text-align:right;" readonly>
								</div>
							</div>
						</div>
						<div class="form-group row" style="margin-bottom: 2px;">
							<div class="col-md-10">
								<div class="form-control" style="text-align:right; border: none;">TCS :</div>

							</div>
							<div class="col-md-2">
								<div class="input-group">
									<input type="text" class="form-control" name="pi_tcs" style="text-align:right;"
										id="pi_tcs">
								</div>
							</div>
						</div>
						<div class="form-group row" style="margin-bottom: 2px;">
							<div class="col-md-10">
								<div class="form-control" style="text-align:right; border: none;">Round Off :</div>
							</div>
							<div class="col-md-2">
								<div class="input-group">
									<input type="text" class="form-control" name="pi_round" style=" text-align:right;"
										id="pi_round">
								</div>
							</div>
						</div>
						<div class="form-group row" style="margin-bottom: 2px;">
							<div class="col-md-10">
								<div class="form-control" style="text-align:right; border: none;">Grand Total :</div>

							</div>
							<div class="col-md-2">
								<div class="input-group">
									<input type="text" class="form-control pi_total_final" name="pi_total_final"
										style="background-color: #eee; text-align:right;" readonly>
								</div>
							</div>
						</div>
					</div>
					<div class="kt-portlet__foot">
						<div class="kt-form__actions" style="float:right;">
							<button id="purchase_quotation_submit" type="submit" class="btn btn-primary">Submit</button>
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
							<div class="col-xl-8 order-2 order-xl-1">
								<div class="row align-items-center">
									<div class="col-md-3 kt-margin-b-20-tablet-and-mobile">
										<div class="kt-input-icon kt-input-icon--left">
											<input type="text" class="form-control" placeholder="Search..."
												id="generalSearch">
											<span class="kt-input-icon__icon kt-input-icon__icon--left">
												<span><i class="la la-search"></i></span>
											</span>
										</div>
									</div>
									<div class="col-md-3 kt-margin-b-20-tablet-and-mobile">
										<div class="kt-form__control">
											<select class="form-control bootstrap-select"
												id="kt_purchase_invoice_product">
												<option></option>
											</select>
										</div>
									</div>
									<div class="col-md-3 kt-margin-b-20-tablet-and-mobile">
										<div class="kt-form__control">
											<select class="form-control bootstrap-select" id="kt_purchase_invoice_user">
												<option></option>
											</select>
										</div>
									</div>
									<div class="col-md-3 kt-margin-b-20-tablet-and-mobile">
										<div class="kt-form__control">
											<select class="form-control bootstrap-select"
												id="kt_purchase_invoice_status">
												<option></option>
												<option value="0">Pending</option>
												<option value="1">Completed</option>
												<option value="2">Partial</option>
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
				<div class="kt-datatable" id="purchase_quotation_datatable"></div>
				<!--end: Datatable -->

			</div>
		</div>

		<!--end::Portlet-->
	</div>
	<!--End::Dashboard 1-->
</div>
<!-- end:: Content -->

<!--begin::Delete Purchase Invoice Modal-->
<div class="modal fade" id="delete_purchase_quotation" tabindex="-1" role="dialog"
	aria-labelledby="deletePurchaseQuotationModal" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="deletePurchaseQuotationModal">Delete Purchase Quotation</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				</button>
			</div>
			<div class="modal-body">
				<!--begin::Form-->
				<form class="kt-form kt-form--label-right">
					<div class="kt-portlet__body">
						Are you sure you want to delete this purchase Quotation ?
					</div>
				</form>

				<!--end::Form-->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button id="delete_purchase_quotation_submit" type="button" class="btn btn-primary">Delete</button>
			</div>
		</div>
	</div>
</div>
<!--end::Delete Purchase Invoice Modal-->

<!--begin::Send whatsapp Modal-->
<form class="kt-form kt-form--label-right" id="send_purchase_invoice_whatsapp">
	<div class="modal fade" id="kt_modal_purchase_invoice_whatsapp" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Send Whatsapp</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					</button>
				</div>
				<div class="modal-body">
					<!--begin::Form-->
					<input name="pi_no_whatsapp" id="pi_no_whatsapp" style="display:none" class="form-control"
						type="text">

					<div class="kt-portlet__body">
						<div class="form-group row" style="margin-bottom: 0">
							<div class="col-sm-6">
								<div class="form-group">
									<label>Mobile No (Separted by comma)</label>
									<div class="kt-input-icon">
										<input name="pi_whatsapp_number" id="pi_whatsapp_number"
											placeholder="Enter Whatsapp Number(s)" class="form-control" type="text">
									</div>
								</div>
							</div>
						</div>
					</div>
					<!--end::Form-->
				</div>
				<div class="modal-footer">
					<button id="pi_whatsapp_submit" type="submit" class="btn btn-primary">Send</button>
				</div>
			</div>
		</div>
	</div>
</form>
<!--end::Send whatapp Modal-->
<script>
	window.onload = function() {
    set_purchase_quotation();
};

</script>