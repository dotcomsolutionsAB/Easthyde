<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
	<div class="kt-portlet" id="kt_portlet_add_q">
		<div class="kt-portlet__head">
			<div class="kt-portlet__head-label">
				<h3 class="kt-portlet__head-title">
					Sales Invoice
				</h3>
			</div>
			<div class="kt-portlet__head-toolbar">
				<div class="kt-portlet__head-group">
					<a href="#" data-ktportlet-tool="toggle" class="btn btn-sm btn-icon btn-clean btn-icon-md"><i class="la la-angle-down"></i></a>
				</div>
			</div>
		</div>
		<div class="kt-portlet__body kt-portlet__body--fit">
			<div class="kt-grid kt-wizard-v3 kt-wizard-v3--white" id="kt_wizard_sales" data-ktwizard-state="step-first">
				<div class="kt-grid__item">

					<!--begin: Form Wizard Nav -->
					<div class="kt-wizard-v3__nav">

						<div class="kt-wizard-v3__nav-items">
							<div class="kt-wizard-v3__nav-item" data-ktwizard-type="step" data-ktwizard-state="current">
								<div class="kt-wizard-v3__nav-body">
									<div class="kt-wizard-v3__nav-label">
										<span>1</span> Invoice Details
									</div>
									<div class="kt-wizard-v3__nav-bar"></div>
								</div>
							</div>
							<div class="kt-wizard-v3__nav-item" data-ktwizard-type="step">
								<div class="kt-wizard-v3__nav-body">
									<div class="kt-wizard-v3__nav-label">
										<span>2</span> Dispatch Details
									</div>
									<div class="kt-wizard-v3__nav-bar"></div>
								</div>
							</div>
							<div class="kt-wizard-v3__nav-item" data-ktwizard-type="step">
								<div class="kt-wizard-v3__nav-body">
									<div class="kt-wizard-v3__nav-label">
										<span>3</span> Buyer's Details
									</div>
									<div class="kt-wizard-v3__nav-bar"></div>
								</div>
							</div>
							<div class="kt-wizard-v3__nav-item" data-ktwizard-type="step">
								<div class="kt-wizard-v3__nav-body">
									<div class="kt-wizard-v3__nav-label">
										<span>4</span> Enter Products
									</div>
									<div class="kt-wizard-v3__nav-bar"></div>
								</div>
							</div>
							<div class="kt-wizard-v3__nav-item" data-ktwizard-type="step">
								<div class="kt-wizard-v3__nav-body">
									<div class="kt-wizard-v3__nav-label">
										<span>5</span> Review and Submit
									</div>
									<div class="kt-wizard-v3__nav-bar"></div>
								</div>
							</div>
						</div>
					</div>

					<!--end: Form Wizard Nav -->
				</div>
				<div class="kt-grid__item kt-grid__item--fluid kt-wizard-v3__wrapper">

					<!--begin: Form Wizard Form-->
					<form class="kt-form" style="width:95% !important" id="add_sales_invoice">

						<!--begin: Form Wizard Step 1-->
						<div class="kt-wizard-v3__content" data-ktwizard-type="step-content" data-ktwizard-state="current">
							<div class="kt-heading kt-heading--md">Invoice Details</div>
							<div class="kt-form__section kt-form__section--first">
								<div class="kt-wizard-v3__form">
									<div class="form-group row">
										<div class="col-sm-3">
			                                <div class="form-group">
			                                    <label>Client</label>
												<div class="kt-input-icon">
				                                    <select class="form-control kt-select2 client-select2" name="si_client" id="si_client">
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
				                                    <select class="form-control kt-select2 kt-select2-multiple si_sales_order-select2" name="si_sales_order[]" id="si_sales_order">
				                                    </select>
				                                </div>
			                                    <span class="form-text text-muted">Please select the sales order..</span>
			                                </div>
			                            </div>
			                            <div class="col-sm-3">
			                                <div class="form-group">
			                                    <label>Quotation #</label>
												<div class="kt-input-icon">
				                                    <select class="form-control kt-select2 kt-select2-multiple si_quotation-select2" name="si_quotation[]" id="si_quotation">
				                                    </select>
				                                </div>
			                                    <span class="form-text text-muted">Please select the quotation..</span>
			                                </div>
			                            </div>
			                            <div class="col-sm-3">
			                                <div class="form-group">
			                                    <label>Other Reference(s)</label>
												<div class="kt-input-icon">
				                                    <input class="form-control" name="si_quotation[]" id="si_quotation">
				                                </div>
			                                    <span class="form-text text-muted">Please enter other reference(s)..</span>
			                                </div>
			                            </div>
			                        </div>
									<div class="form-group row">
										<div class="col-sm-3">
			                                <div class="form-group">
			                                    <label>Sales Invoice Series</label>
												<div class="kt-input-icon">
				                                    <select class="form-control kt-select2 si_series-select2" name="si_series" id="si_series">
				                                    	<option></option>
				                                    	<option>Primary</option>
				                                    	<option>Secondary</option>

				                                    </select>
				                                </div>
			                                    <span class="form-text text-muted">Please select the invoice series..</span>
			                                </div>
			                            </div>
			                            <div class="col-sm-3">
			                                <div class="form-group">
			                                    <label>Sales Invoice#</label>
												<div class="kt-input-icon">
			                                    	<input name="sales_invoice_no" placeholder="Sales Invoice #" id="sales_invoice_no" class="form-control" type="text">
			                                    </div>
			                                    <span class="form-text text-muted">Please enter the sales Invoice#..</span>
			                                </div>
			                            </div>
			                            <div class="col-sm-3">
			                                <div class="form-group">
			                                    <label>Sales Invoice Date</label>
												<div class="kt-input-icon">
			                                    	<input placeholder="Sales Invoice Date" name="sales_invoice_date" id="sales_invoice_date" class="form-control date-picker" type="text" data-date-end-date="+3m" value="<?php echo date('d-m-Y', strtotime('today')); ?>">
			                                    </div>
			                                    <span class="form-text text-muted">Please enter the sales invoice date..</span>
			                                </div>
			                            </div>
			                        </div>
								</div>
							</div>
						</div>
						<!--end: Form Wizard Step 1-->

						<!--begin: Form Wizard Step 2-->
						<div class="kt-wizard-v3__content" data-ktwizard-type="step-content">
							<div class="kt-heading kt-heading--md">Dispatch Details</div>
							<div class="kt-form__section kt-form__section--first">
								<div class="kt-wizard-v3__form">
									<div class="form-group row">
			                            <div class="col-sm-3">
			                                <div class="form-group">
			                                    <label>Dispatch Document #</label>
												<div class="kt-input-icon">
			                                    	<input name="dispatch_doc_no" placeholder="Dispatch Document #" id="dispatch_doc_no" class="form-control" type="text">
			                                    </div>
			                                    <span class="form-text text-muted">Please enter the dispatch document #..</span>
			                                </div>
			                            </div>
			                            <div class="col-sm-3">
			                                <div class="form-group">
			                                    <label>Dispatch Date</label>
												<div class="kt-input-icon">
			                                    	<input name="dispatch_date" placeholder="Dispatch Date" id="dispatch_date" class="form-control date-picker" data-date-end-date="+3m" type="text">
			                                    </div>
			                                    <span class="form-text text-muted">Please enter the dispatch date..</span>
			                                </div>
			                            </div>
			                            <div class="col-sm-3">
			                                <div class="form-group">
			                                    <label>Dispatched Through</label>
												<div class="kt-input-icon">
			                                    	<input name="dispatch_medium" placeholder="Dispatch Date" id="dispatch_medium" class="form-control" type="text">
			                                    </div>
			                                    <span class="form-text text-muted">Please enter the dispatch medium..</span>
			                                </div>
			                            </div>
			                            <div class="col-sm-3">
			                                <div class="form-group">
			                                    <label>Dispatch Destination</label>
												<div class="kt-input-icon">
			                                    	<input name="dispatch_destination" placeholder="Destination" id="dispatch_destination" class="form-control" type="text">
			                                    </div>
			                                    <span class="form-text text-muted">Please enter the dispatch destination..</span>
			                                </div>
			                            </div>
			                        </div>
								</div>
							</div>
						</div>

						<!--end: Form Wizard Step 2-->

						<!--begin: Form Wizard Step 3-->
						<div class="kt-wizard-v3__content" data-ktwizard-type="step-content">
							<div class="kt-heading kt-heading--md">Buyer's Details</div>
							<div class="kt-form__section kt-form__section--first">
								<div class="kt-wizard-v3__form">
									<div class="form-group row">
			                            <div class="col-sm-3">
			                                <div class="form-group">
			                                    <label>Buyer's Order#</label>
												<div class="kt-input-icon">
			                                    	<input name="buyer_order_no" placeholder="Buyer's order #" id="buyer_order_no" class="form-control" type="text">
			                                    </div>
			                                    <span class="form-text text-muted">Please enter the dispatch document #..</span>
			                                </div>
			                            </div>
			                            <div class="col-sm-3">
			                                <div class="form-group">
			                                    <label>Buyer's Order Date</label>
												<div class="kt-input-icon">
			                                    	<input name="buyer_order_date" placeholder="Buyer's Order Date" id="buyer_order_date" class="form-control date-picker" data-date-end-date="+3m" type="text">
			                                    </div>
			                                    <span class="form-text text-muted">Please enter the buyer's order date..</span>
			                                </div>
			                            </div>
			                            <div class="col-sm-3">
			                                <div class="form-group">
			                                    <label>Mode/Terms of Payment</label>
												<div class="kt-input-icon">
			                                    	<input name="terms_payment" placeholder="Mode/Terms of Payment" id="terms_payment" class="form-control" type="text">
			                                    </div>
			                                    <span class="form-text text-muted">Please enter the mode/terms of payment..</span>
			                                </div>
			                            </div>
			                            <div class="col-sm-3">
			                                <div class="form-group">
			                                    <label>Terms of Delivery</label>
												<div class="kt-input-icon">
			                                    	<input name="terms_delivery" placeholder="Terms of Delivery" id="terms_delivery" class="form-control" type="text">
			                                    </div>
			                                    <span class="form-text text-muted">Please enter the terms of delivery..</span>
			                                </div>
			                            </div>
			                        </div>
									<div class="form-group row col-lg-12">
										<label>Shipping Address</label>
									</div>
									<div class="form-group row">
										<div class="col-lg-4">
											<label>Address Line 1:</label>
											<div class="kt-input-icon">
												<input type="text" class="form-control" placeholder="Enter Address Line 1"  id="shipping_add_1" name="shipping_add_1">
											</div>
										</div>
										<div class="col-lg-4">
											<label>Address Line 2:</label>
											<div class="kt-input-icon">
												<input type="text" class="form-control" placeholder="Enter Address Line 2"  id="shipping_add_2" name="shipping_add_2">
											</div>
										</div>
										<div class="col-lg-4">
											<label>Address Line 3:</label>
											<div class="kt-input-icon">
												<select type="text" class="form-control kt-select2 sales_add_3-select2" placeholder="Enter Address Line 3"  id="shipping_add_3" name="shipping_add_3">
												</select>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<!--end: Form Wizard Step 3-->

						<!--begin: Form Wizard Step 4-->
						<div class="kt-wizard-v3__content" data-ktwizard-type="step-content">
							<div class="kt-heading kt-heading--md">Enter Products</div>
							<div class="kt-form__section kt-form__section--first">
								<div class="kt-wizard-v3__form">
									<div class="form-group row" style="border-bottom: 1px solid #eee; padding-bottom:1rem;">
										<div class="col-md-3">
											<div class="kt-form__label">
												<label>SKU/Part No:</label>
											</div>
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
									</div>
									<div class="form-group row">
										<div id="kt_repeater_si">
											<div class="form-group form-group-last row" id="kt_repeater_1">
												<div data-repeater-list="sales_invoice" id="sales_invoice_list" class="col-lg-12">
													<div data-repeater-item class="form-group row align-items-center" style="border-bottom: 1px dashed #5d78ff; padding-bottom:1rem; margin-bottom: 1rem;">
														<div class="col-md-3">
															<div class="kt-form__group--inline">
																<div class="kt-form__control">
																	<select class="form-control kt-select2 si_product_name-select2" name="si_product_name">
									                            	</select>
																</div>
															</div>
														</div>
														<div class="col-md-1">
															<div class="input-group">
																<input placeholder="Qty" name="si_qty" class="form-control si_qty" type="text">
															</div>
														</div>
														<div class="col-md-1">
															<div class="kt-form__group--inline">
																<div class="kt-form__control">
																	<select name="si_unit" class="form-control kt-select2 si_unit-select2"> 
									                                </select>
																</div>
															</div>
														</div>
														<div class="col-md-1">
															<div class="input-group">
									                            <input placeholder="Price" name="si_rate" class="form-control si_rate" type="text">
															</div>
														</div>
														<div class="col-md-1">
															<div class="input-group">
																<input placeholder="Discount" name="si_dsc" class="form-control si_dsc" type="text">
															</div>
														</div>

														<div class="col-md-1">
															<div class="input-group">
																<input placeholder="HSN" name="si_hsn" class="form-control si_hsn" type="text">
															</div>
														</div>
														<div class="col-md-1">
															<div class="kt-form__group--inline">
																<div class="kt-form__control">
																	<select name="si_tax" class="form-control kt-select2 si_tax-select2"> 
									                                    <option></option>
									                                    <option value="5">5</option>
									                                    <option value="12">12</option>
									                                    <option value="18">18</option>
									                                    <option value="28">28</option>
									                                </select>
																</div>
															</div>
														</div>
														<div class="col-md-1">
															<div class="input-group">
									                            <input type="text" class="form-control si_gross_pr" name="si_gross_pr" style="background-color: #eee" readonly>
															</div>
														</div>	
														<div class="col-md-1">
															<div class="input-group">
									                            <input type="text" class="form-control si_tax_pr" name="si_tax_pr" style="background-color: #eee"  readonly>
															</div>
														</div>
														<div class="col-md-1">
															<div class="input-group">
									                            <input type="text" class="form-control si_total_pr" name="si_total_pr" style="background-color: #eee" readonly>
															</div>
														</div>
														<div class="col-md-5" style="margin-top:3px">
															<input type="text" class="form-control" name="si_product_description" placeholder="Product Name">
														</div>	
														<div class="col-md-5" style="margin-top:3px">
															<textarea class="form-control kt_autosize_so" placeholder="Product Description" name="si_product_add_description" rows="1"></textarea>
														</div>	
														<div class="col-md-1" style="margin-top:3px; text-align:center">
															<input type="text" class="form-control" name="si_sn" value="1" style="border: none;font-weight: 900;text-align: center;" readonly="">
														</div>
														<div class="col-md-1" style="margin-top:3px; text-align:center">
															<a href="javascript:;" data-repeater-delete="" class="btn-sm btn btn-label-danger btn-bold si_delete">
																<i class="la la-trash-o"></i>
															</a>
														</div>
													</div>
												</div>
											</div>
											<div >
												<div class="col-lg-4">
													<a href="javascript:;" data-repeater-create="sales_invoice" id="si_btn_add" class="btn btn-bold btn-sm btn-label-brand">
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
			                                    <input type="text" class="form-control si_gross_final" name="si_gross_final" style="background-color: #eee; text-align:right;" readonly>
											</div>
										</div>	
									</div>
									<div class="form-group row" style="margin-bottom: 2px;">
										<div class="col-md-10">
											<div class="form-control" style="text-align:right; border: none;">Freight :</div>
											
										</div>
										<div class="col-md-2">
											<div class="input-group">
									            <input type="text" class="form-control" name="si_freight" style="text-align:right;" id="si_freight">
											</div>
										</div>
									</div>
									<div class="form-group row" style="margin-bottom: 2px;">
										<div class="col-md-10">
											<div class="form-control" style="text-align:right; border: none;">Packaging & Forwarding :</div>
											
										</div>
										<div class="col-md-2">
											<div class="input-group">
									            <input type="text" class="form-control" name="si_pf" style="text-align:right;" id="si_pf">
											</div>
										</div>	
									</div>
									<div class="form-group row" style="margin-bottom: 2px;">
										<div class="col-md-10">
											<div class="form-control" style="text-align:right; border: none;">Total Tax :</div>
											
										</div>
										<div class="col-md-2">
											<div class="input-group">
									            <input type="text" class="form-control si_tax_final" name="si_tax_final" style="background-color: #eee; text-align:right;" readonly>
											</div>
										</div>
									</div>
									<div class="form-group row" style="margin-bottom: 2px;">
										<div class="col-md-10">
											<div class="form-control" style="text-align:right; border: none;">Overall Discount (Rs.) :</div>
											
										</div>
										<div class="col-md-2">
											<div class="input-group">
									            <input type="text" class="form-control" name="si_tot_discount" style="text-align:right;" id="si_tot_discount">
											</div>
										</div>
									</div>
									<div class="form-group row" style="margin-bottom: 2px;">
										<div class="col-md-10">
											<div class="form-control" style="text-align:right; border: none;">Round Off :</div>
										</div>
										<div class="col-md-2">
											<div class="input-group">
									            <input type="text" class="form-control" name="si_round" style="background-color: #eee; text-align:right;" id="si_round" readonly="">
											</div>
										</div>
									</div>
									<div class="form-group row" style="margin-bottom: 2px;">
										<div class="col-md-10">
											<div class="form-control" style="text-align:right; border: none;">Grand Total :</div>
											
										</div>
										<div class="col-md-2">
											<div class="input-group">
									            <input type="text" class="form-control si_total_final" name="si_total_final" style="background-color: #eee; text-align:right;" readonly>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<!--end: Form Wizard Step 4-->

						<!--begin: Form Wizard Step 5-->
                        <div class="kt-wizard-v1__content" data-ktwizard-type="step-content">
                            <div class="kt-heading kt-heading--md">Review your Details and Submit</div>
                            <div class="kt-form__section kt-form__section--first">
                                <div class="kt-wizard-v1__review">
                                    <div class="kt-wizard-v1__review-item">
                                        <div class="kt-wizard-v1__review-title">
                                            Current Address
                                        </div>
                                        <div class="kt-wizard-v1__review-content">
                                            Address Line 1<br />
                                            Address Line 2<br />
                                            Melbourne 3000, VIC, Australia
                                        </div>
                                    </div>
                                    <div class="kt-wizard-v1__review-item">
                                        <div class="kt-wizard-v1__review-title">
                                            Delivery Details
                                        </div>
                                        <div class="kt-wizard-v1__review-content">
                                            Package: Complete Workstation (Monitor, Computer, Keyboard & Mouse)<br />
                                            Weight: 25kg<br />
                                            Dimensions: 110cm (w) x 90cm (h) x 150cm (L)
                                        </div>
                                    </div>
                                    <div class="kt-wizard-v1__review-item">
                                        <div class="kt-wizard-v1__review-title">
                                            Delivery Service Type
                                        </div>
                                        <div class="kt-wizard-v1__review-content">
                                            Overnight Delivery with Regular Packaging<br />
                                            Preferred Morning (8:00AM - 11:00AM) Delivery
                                        </div>
                                    </div>
                                    <div class="kt-wizard-v1__review-item">
                                        <div class="kt-wizard-v1__review-title">
                                            Delivery Address
                                        </div>
                                        <div class="kt-wizard-v1__review-content">
                                            Address Line 1<br />
                                            Address Line 2<br />
                                            Preston 3072, VIC, Australia
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--end: Form Wizard Step 5-->

						<!--begin: Form Actions -->
						<div class="kt-form__actions">
							<button class="btn btn-secondary btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u" data-ktwizard-type="action-prev">
								Previous
							</button>
							<button id="quotation_submit" class="btn btn-success btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u" data-ktwizard-type="action-submit">
								Submit
							</button>
							<button class="btn btn-brand btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u" data-ktwizard-type="action-next" onclick="myFunction()">
								Next Step
							</button>
						</div>

						<!--end: Form Actions -->
					</form>

					<!--end: Form Wizard Form-->
				</div>
			</div>
		</div>
	</div>
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
							</div>
						</div>
					</div>
				</div>
				<!--end: Search Form -->
				<!--begin: Datatable -->
					<div class="kt-datatable" id="sales_invoice_datatable"></div>
				<!--end: Datatable -->
			</div>
		</div>
	</div>
	<!--end::Portlet-->
</div>
<!-- end:: Content -->

<!--begin::Edit Sales Invoice Modal-->
<form class="kt-form kt-form--label-right" id="edit_sales_invoice">
	<div class="modal fade" id="kt_modal_e_sales_invoice" tabindex="-1" role="dialog" aria-labelledby="editSalesInvoiceModal" aria-hidden="true">
		<div class="modal-dialog modal-xl" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="editSalesInvoiceModal" >Edit Sales Invoice</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					</button>
				</div>
				<input name="edit_si_id" id="edit_si_id" class="form-control" type="text" style="display:none">
				<div class="modal-body">
					<!--begin::Form-->
						<div class="kt-portlet__body">
							<div class="form-group row">
								<div class="col-sm-3">
	                                <div class="form-group">
	                                    <label>Client</label>
										<div class="kt-input-icon">
		                                    <select class="form-control kt-select2 client-select2" name="edit_si_client" id="edit_si_client">
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
		                                    <select class="form-control kt-select2 edit_si_sales_order-select2" name="edit_si_sales_order" id="edit_si_sales_order">
		                                    	<option></option>
		                                    </select>
		                                </div>
	                                    <span class="form-text text-muted">Please select the sales order..</span>
	                                </div>
	                            </div>
	                            <div class="col-sm-3">
	                                <div class="form-group">
	                                    <label>Sales Invoice#</label>
										<div class="kt-input-icon">
	                                    	<input name="edit_sales_inv" id="edit_sales_inv" class="form-control" type="text" style="text-transform:uppercase">
	                                    </div>
	                                    <span class="form-text text-muted">Please enter the sales Order#..</span>
	                                </div>
	                            </div>
	                            <div class="col-sm-3">
	                                <div class="form-group">
	                                    <label>Sales Invoice Date</label>
										<div class="kt-input-icon">
	                                    	<input name="edit_sales_inv_date" id="edit_sales_inv_date" class="form-control date-picker" type="text" data-date-end-date="+3m">
	                                    </div>
	                                    <span class="form-text text-muted">Please enter the sales order date..</span>
	                                </div>
	                            </div>
	                        </div>
	                        <div class="form-group row col-lg-12">
								<label>Shipping Address</label>
							</div>
							<div class="form-group row">
								<div class="col-lg-4">
									<label>Address Line 1:</label>
									<div class="kt-input-icon">
										<input type="text" class="form-control" placeholder="Enter Address Line 1"  id="edit_shipping_add_1" name="edit_shipping_add_1">
									</div>
								</div>
								<div class="col-lg-4">
									<label>Address Line 2:</label>
									<div class="kt-input-icon">
										<input type="text" class="form-control" placeholder="Enter Address Line 2"  id="edit_shipping_add_2" name="edit_shipping_add_2">
									</div>
								</div>
								<div class="col-lg-4">
									<label>Address Line 3:</label>
									<div class="kt-input-icon">
										<select type="text" class="form-control kt-select2 edit_sales_add_3-select2" placeholder="Enter Address Line 3"  id="edit_shipping_add_3" name="edit_shipping_add_3">
										</select>
									</div>
								</div>
							</div>
							<div class="form-group row">
	                        	<div class="col-md-3">
									<br/>
									<div class="input-group">
										<div class="input-group-prepend"><span class="input-group-text">Freight</span></div>
	                                    <input type="text" class="form-control" name="edit_si_freight" id="edit_si_freight">
									</div>
								</div>
								<div class="col-md-3">
									<br/>
									<div class="input-group">
										<div class="input-group-prepend"><span class="input-group-text">P&F</span></div>
	                                    <input type="text" class="form-control" name="edit_si_pf" id="edit_si_pf">
									</div>
								</div>	
								<div class="col-md-3">
									<br/>
									<div class="input-group">
										<div class="input-group-prepend"><span class="input-group-text">Discount</span></div>
	                                    <input type="text" class="form-control" name="edit_si_tot_discount" id="edit_si_tot_discount">
									</div>
								</div>
								<div class="col-md-3">
									<br/>
									<div class="input-group">
										<div class="input-group-prepend"><span class="input-group-text">Round Off</span></div>
	                                    <input type="text" class="form-control" name="edit_si_round" id="edit_si_round">
									</div>
								</div>
							</div>
							<div class="form-group row">
								<div class="col-sm-3">
	                                <div class="form-group">
		                                <label>Invoice Status</label>
										<div class="kt-input-icon">
			                                <select class="form-control kt-select2 si_status-select2" name="sales_invoice_status" id="sales_invoice_status">
			                                	<option value="0">Pending</option>
			                                	<option value="1">Completed</option>
			                                	<option value="2">Rejected</option>
			                                </select>
			                            </div>
		                                <span class="form-text text-muted">Please enter status of the sales invoice..</span>
		                            </div>
	                            </div>
							</div>
						</div>
					<!--end::Form-->
				</div>
				<div class="modal-footer">
					<button id="edit_sales_invoice_submit" type="submit" class="btn btn-primary">Submit</button>
				</div>
			</div>
		</div>
	</div>
</form>
<!--end::Edit Sales Invoice Modal-->

<!--begin::Delete Sales Invoice Modal-->
<div class="modal fade" id="delete_sales_invoice" tabindex="-1" role="dialog" aria-labelledby="deleteSalesInvoiceModal" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="deleteSalesInvoiceModal" >Delete Sales Invoice</h5>
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
				<button id="delete_sales_invoice_submit" type="button" class="btn btn-primary">Delete</button>
			</div>
		</div>
	</div>
</div>
<!--end::Delete Sales Invoice Modal-->

<!--begin::Add Item Sales Invoice Modal-->
<form class="kt-form kt-form--label-right" id="add_item_sales_invoice">
	<div class="modal fade" id="kt_modal_ai_sales_invoice" tabindex="-1" role="dialog" aria-labelledby="addItemSalesInvoiceModal" aria-hidden="true">
		<div class="modal-dialog modal-xl" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="addItemSalesInvoiceModal">Add Item</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					</button>
				</div>
				<input name="ai_si_id" id="ai_si_id" class="form-control" type="text" style="display:none">
				<div class="modal-body">
					<!--begin::Form-->
						<div class="kt-portlet__body">
							<div class="form-group row">
								<div class="col-sm-4">
	                                <div class="form-group">
	                                    <label>Product</label>
	                                    <select class="form-control kt-select2 ai-product-select2" name="ai_si_product" id="ai_si_product">
	                                    	<option></option>
	                                    </select>
	                                    <span class="form-text text-muted">Please select name of the product..</span>
	                                </div>
	                            </div>
	                            <div class="col-sm-8">
	                                <div class="form-group">
	                                    <label>Description</label>
	                                    <input name="ai_si_description" placeholder="Product Description" id="ai_si_description" class="form-control" type="text">
	                                    <span class="form-text text-muted">Please enter the Description..</span>
	                                </div>
	                            </div>
	                        </div>
	                        <div class="form-group row">
	                            <div class="col-sm-3">
	                                <div class="form-group">
	                                    <label>Quantity</label>
	                                    <input name="ai_si_quantity" placeholder="Qty" id="ai_si_quantity" class="form-control" type="text">
	                                    <span class="form-text text-muted">Please enter the Quantity..</span>
	                                </div>
	                            </div>
	                            <div class="col-sm-3">
									<div class="form-group">
	                                    <label>Unit</label>
											<select name="ai_si_unit" id="ai_si_unit" class="form-control kt-select2 ai_si_unit-select2"> 
			                                </select>
			                                <option></option>
	                                    <span class="form-text text-muted">Please enter the Unit..</span>
									</div>
								</div>
	                            <div class="col-sm-3">
	                                <div class="form-group">
	                                    <label>Price</label>
	                                    <div class="input-group">
											<div class="input-group-prepend"><span class="input-group-text">Rs.</span></div>
                                            <input placeholder="Price" name="ai_si_price" id="ai_si_price" class="form-control" type="text">
										</div>
	                                    <span class="form-text text-muted">Please enter the price..</span>
	                                </div>
	                            </div>
	                            <div class="col-sm-3">
	                                <div class="form-group">
	                                    <label>Discount</label>
	                                    <div class="input-group">
											<input placeholder="Discount" name="ai_si_dsc" id="ai_si_dsc" class="form-control" type="text">
											<div class="input-group-prepend"><span class="input-group-text">%</span></div>
										</div>
	                                    <span class="form-text text-muted">Please enter the discount %..</span>
	                                </div>
	                            </div>
	                        </div>
	                        <div class="form-group row">
	                            <div class="col-sm-2">
	                                <div class="form-group">
	                                    <label>HSN</label>
	                                    <input name="ai_si_hsn" id="ai_si_hsn" placeholder="HSN" class="form-control" type="text">
	                                    <span class="form-text text-muted">Please enter the HSN Code..</span>
	                                </div>
	                            </div>
	                            <div class="col-sm-2">
	                                <div class="form-group">
	                                    <label>Tax</label>
	                                    <select name="ai_si_tax" id="ai_si_tax" class="form-control kt-select2 ai_si_tax-select2"> 
                                            <option></option>
                                            <option value="5">5</option>
                                            <option value="12">12</option>
                                            <option value="18">18</option>
                                            <option value="28">28</option>
                                        </select>
	                                    <span class="form-text text-muted">Please select the tax rate..</span>
	                                </div>
	                            </div>
	                            <div class="col-sm-8">
	                                <div class="form-group">
	                                    <label>Additional Description</label>
										<textarea class="form-control kt_autosize_ai_si_ai" placeholder="Additional Product Description" id="ai_si_product_add_description" name="ai_si_product_add_description" rows="1"></textarea>
	                                    <span class="form-text text-muted">Please enter additional description..</span>
									</div>
								</div>
	                        </div>
						</div>
					<!--end::Form-->
				</div>
				<div class="modal-footer">
					<button id="ai_sales_invoice_submit" type="submit" class="btn btn-primary">Submit</button>
				</div>
			</div>
		</div>
	</div>
</form>
<!--end::Add Item Sales Invoice Modal-->

<!--begin::Edit Item Sales Invoice Modal-->
<form class="kt-form kt-form--label-right" id="edit_item_sales_invoice">
	<div class="modal fade" id="kt_modal_ei_sales_invoice" tabindex="-1" role="dialog" aria-labelledby="editItemSalesInvoiceModal" aria-hidden="true">
		<div class="modal-dialog modal-xl" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="editItemSalesInvoiceModal">Edit Item</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					</button>
				</div>
				<input name="ei_si_id" id="ei_si_id" class="form-control" type="text" style="display:none">
				<input name="ei_si_index" id="ei_si_index" class="form-control" type="text" style="display:none">
				<div class="modal-body">
					<!--begin::Form-->
						<div class="kt-portlet__body">
							<div class="form-group row">
								<div class="col-sm-4">
	                                <div class="form-group">
	                                    <label>Product</label>
	                                    <select class="form-control kt-select2 ei-product-select2" name="ei_si_product" id="ei_si_product">
	                                    	<option></option>
	                                    </select>
	                                    <span class="form-text text-muted">Please select name of the product..</span>
	                                </div>
	                            </div>
	                            <div class="col-sm-8">
	                                <div class="form-group">
	                                    <label>Description</label>
	                                    <input name="ei_si_description" id="ei_si_description" class="form-control" type="text" style="text-transform:uppercase">
	                                    <span class="form-text text-muted">Please enter the Description..</span>
	                                </div>
	                            </div>
	                        </div>
	                        <div class="form-group row">
	                            <div class="col-sm-3">
	                                <div class="form-group">
	                                    <label>Quantity</label>
	                                    <input name="ei_si_quantity" id="ei_si_quantity" class="form-control" type="text">
	                                    <span class="form-text text-muted">Please enter the Quantity..</span>
	                                </div>
	                            </div>
	                            <div class="col-sm-3">
									<div class="form-group">
	                                    <label>Unit</label>
											<select name="ei_si_unit" id="ei_si_unit" class="form-control kt-select2 ei_si_unit-select2"> 
			                                </select>
			                                <option></option>
	                                    <span class="form-text text-muted">Please enter the Unit..</span>
									</div>
								</div>
	                            <div class="col-sm-3">
	                                <div class="form-group">
	                                    <label>Price</label>
	                                    <div class="input-group">
											<div class="input-group-prepend"><span class="input-group-text">Rs.</span></div>
                                            <input placeholder="Price" name="ei_si_price" id="ei_si_price" class="form-control" type="text">
										</div>
	                                    <span class="form-text text-muted">Please enter the price..</span>
	                                </div>
	                            </div>
	                            <div class="col-sm-3">
	                                <div class="form-group">
	                                    <label>Discount</label>
	                                    <div class="input-group">
											<input placeholder="Discount" name="ei_si_dsc" id="ei_si_dsc" class="form-control" type="text">
											<div class="input-group-prepend"><span class="input-group-text">%</span></div>
										</div>
	                                    <span class="form-text text-muted">Please enter the discount %..</span>
	                                </div>
	                            </div>
	                        </div>
	                        <div class="form-group row">
	                            <div class="col-sm-2">
	                                <div class="form-group">
	                                    <label>HSN</label>
	                                    <input name="ei_si_hsn" id="ei_si_hsn" class="form-control" type="text">
	                                    <span class="form-text text-muted">Please enter the HSN Code..</span>
	                                </div>
	                            </div>
	                            <div class="col-sm-2">
	                                <div class="form-group">
	                                    <label>Tax</label>
	                                    <select name="ei_si_tax" id="ei_si_tax" class="form-control kt-select2 ei_si_tax-select2"> 
                                            <option></option>
                                            <option value="5">5</option>
                                            <option value="12">12</option>
                                            <option value="18">18</option>
                                            <option value="28">28</option>
                                        </select>
	                                    <span class="form-text text-muted">Please select the tax rate..</span>
	                                </div>
	                            </div>
	                            <div class="col-sm-8">
	                                <div class="form-group">
	                                    <label>Additional Description</label>
										<textarea class="form-control kt_autosize_si_ei" placeholder="Additional Product Description" id="ei_si_product_add_description" name="ei_si_product_add_description" rows="1"></textarea>
	                                    <span class="form-text text-muted">Please enter additional description..</span>
									</div>
								</div>

	                        </div>
						</div>
					<!--end::Form-->
				</div>
				<div class="modal-footer">
					<button id="ei_sales_invoice_submit" type="submit" class="btn btn-primary">Submit</button>
				</div>
			</div>
		</div>
	</div>
</form>
<!--end::Edit Item Sales Invoice Modal-->

<!--begin::Delete Item Sales Invoice Modal-->
<div class="modal fade" id="delete_item_sales_invoice" tabindex="-1" role="dialog" aria-labelledby="deleteItemSalesInvoiceModal" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="deleteSalesInvoiceModal" >Delete Item</h5>
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
				<button id="delete_item_sales_invoice_submit" type="button" class="btn btn-primary">Delete</button>
			</div>
		</div>
	</div>
</div>
<!--end::Delete Item Sales Invoice Modal-->