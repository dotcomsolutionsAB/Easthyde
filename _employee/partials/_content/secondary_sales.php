<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <?php

        $username = $_SESSION['username'];
        $userlevel = $_SESSION['userlevel'];

        $sql_access = "SELECT * FROM users WHERE `username` = '$username'";
        $query_access = $db->query($sql_access);
        $row_access = $query_access->fetch_assoc();

        $menu_access = json_decode($row_access['access'], true);

        if($menu_access['secondary_sales']['create'] == '1' || $userlevel == "sadmin_df56fdg"){

            
    ?>
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
										<span>2</span> Enter Products
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
										<span>4</span> Dispatch Details
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
									<input type="text" name="edit_si_id" id="edit_si_id" style="display:none">
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
			                            <div class="col-sm-3">
			                                <div class="form-group">
			                                    <label>Sales Invoice Series</label>
												<div class="kt-input-icon">
				                                    <select class="form-control kt-select2 si_series-select2" name="si_series" id="si_series">
				                                    	<option value="SECONDARY">Secondary</option>

				                                    </select>
				                                </div>
			                                    <span class="form-text text-muted">Please select the invoice series..</span>
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
												<input type="text" class="form-control" id="shipping_name" name="shipping_name">
											</div>
										</div>
										<div class="col-lg-3">
											<label>Address Line 1:</label>
											<div class="kt-input-icon">
												<input type="text" class="form-control" id="shipping_add_1" name="shipping_add_1">
											</div>
										</div>
										<div class="col-lg-3">
											<label>Address Line 2:</label>
											<div class="kt-input-icon">
												<input type="text" class="form-control" id="shipping_add_2" name="shipping_add_2">
											</div>
										</div>
									</div>
									<div class="form-group row">
										<div class="col-lg-3">
											<label>City:</label>
											<div class="kt-input-icon">
												<input type="text" class="form-control" id="shipping_city" name="shipping_city">
											</div>
										</div>
										<div class="col-lg-3">
											<label>Pincode:</label>
											<div class="kt-input-icon">
												<input type="text" class="form-control" id="shipping_pincode" name="shipping_pincode">
											</div>
										</div>
										<div class="col-lg-3">
											<label>State:</label>
											<div class="kt-input-icon">
												<select type="text" class="form-control kt-select2 sales_add_3-select2" id="shipping_state" name="shipping_state">
												</select>
											</div>
										</div>
										<div class="col-lg-3">
											<label>Country:</label>
											<div class="kt-input-icon">
												<input type="text" class="form-control" id="shipping_country" name="shipping_country">
											</div>
										</div>
									</div>
			                            
								</div>
							</div>
						</div>
						<!--end: Form Wizard Step 1-->

						<!--begin: Form Wizard Step 2-->
						<div class="kt-wizard-v3__content" data-ktwizard-type="step-content">
							<div class="kt-heading kt-heading--md">Enter Products</div>
							<div class="kt-form__section kt-form__section--first">
								<div class="form-group row">
									<div class="col-lg-3">
										<label>Sales Order:</label>
										<div class="kt-input-icon">
											<select type="text" class="form-control kt-select2 si_sales_order-select2 kt-select2-multiple" id="si_sales_order" name="si_sales_order[]">
											</select>
										</div>
									</div>
									<div class="col-lg-3">
										<label>Quotation:</label>
										<div class="kt-input-icon">
											<select type="text" class="form-control kt-select2 si_quotation-select2 kt-select2-multiple" id="si_quotation" name="si_quotation[]">
											</select>
										</div>
									</div>
								</div>
								<div class="kt-wizard-v3__form">
									<div class="form-group row" style="border-bottom: 1px solid #eee; padding-bottom:1rem;">
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
									</div>
									<div class="form-group row">
										<div id="kt_repeater_si">
											<div class="form-group form-group-last row" id="kt_repeater_1">
												<div data-repeater-list="sales_invoice" id="sales_invoice_list" class="col-lg-12">
													<div data-repeater-item class="form-group row align-items-center" style="border-bottom: 1px dashed #5d78ff; padding-bottom:1rem; margin-bottom: 1rem;">
														<div class="col-md-1" style="margin-top:3px; text-align:center">
															<input type="text" class="form-control" name="si_sn" value="1" style="border: none;font-weight: 900;text-align: center;" readonly="">
														</div>
														<div class="col-md-3">
															<div class="kt-form__group--inline">
																<div class="kt-form__control">
																	<select class="form-control kt-select2 si_product_name-select2" name="si_product_name">
									                            	</select>
																</div>
															</div>
														</div>
														<div class="col-md-4">
															<input type="text" class="form-control" name="si_product_description" placeholder="Product Name">
														</div>	
														<div class="col-md-4">
															<textarea class="form-control kt_autosize_so" placeholder="Product Description" name="si_product_add_description" rows='1' style="height:40px;"></textarea>
														</div>
														<div class="col-md-1" style="margin-top:3px">
														</div>
														<div class="col-md-1" style="margin-top:3px">
															<div class="input-group">
																<input placeholder="Qty" name="si_qty" class="form-control si_qty" type="text">
															</div>
														</div>
														<div class="col-md-1" style="margin-top:3px">
															<div class="kt-form__group--inline">
																<div class="kt-form__control">
																	<select name="si_unit" class="form-control kt-select2 si_unit-select2"> 
									                                </select>
																</div>
															</div>
														</div>
														<div class="col-md-1" style="margin-top:3px">
															<div class="input-group">
									                            <input placeholder="Price" name="si_rate" class="form-control si_rate" type="text">
															</div>
														</div>
														<div class="col-md-1" style="margin-top:3px">
															<div class="input-group">
																<input placeholder="Discount" name="si_dsc" class="form-control si_dsc" type="text">
															</div>
														</div>

														<div class="col-md-1" style="margin-top:3px">
															<div class="input-group">
																<input placeholder="HSN" name="si_hsn" class="form-control si_hsn" type="text">
															</div>
														</div>
														<div class="col-md-1" style="margin-top:3px">
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
														<div class="col-md-1" style="margin-top:3px">
															<div class="input-group">
									                            <input type="text" class="form-control si_gross_pr" name="si_gross_pr" style="background-color: #eee" readonly>
															</div>
														</div>	
														<div class="col-md-1" style="margin-top:3px">
															<div class="input-group">
									                            <input type="text" class="form-control si_tax_pr" name="si_tax_pr" style="background-color: #eee"  readonly>
															</div>
														</div>
														<input type="text" name="si_cgst" style="display: none">
														<input type="text" name="si_sgst" style="display: none">
														<input type="text" name="si_igst" style="display: none">
														<div class="col-md-1" style="margin-top:3px">
															<div class="input-group">
									                            <input type="text" class="form-control si_total_pr" name="si_total_pr" style="background-color: #eee" readonly>
															</div>
														</div>
														<div class="col-md-1" style="margin-top:3px">
															<div class="kt-form__group--inline">
																<div class="kt-form__control">
																	<select name="si_display_make" class="form-control kt-select2 si_display_make-select2"> 
			                                                            <option></option>
			                                                            <option value="1">Show</option>
			                                                            <option value="0">Hide</option>
			                                                        </select>
																</div>
															</div>
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
			                                    <span class="form-text text-muted">Please enter the buyer's order #..</span>
			                                </div>
			                            </div>
			                            <div class="col-sm-3">
			                                <div class="form-group">
			                                    <label>Buyer's Order Date</label>
												<div class="kt-input-icon">
			                                    	<input name="buyer_order_date" placeholder="Buyer's Order Date" id="buyer_order_date" class="form-control date-picker" type="text">
			                                    </div>
			                                    <span class="form-text text-muted">Please enter the buyer's order date..</span>
			                                </div>
			                            </div>
		                            </div>
									<div class="form-group row">
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
			                                    	<textarea class="form-control kt_autosize_terms_delivery" placeholder="Terms of Delivery" name="terms_delivery" id="terms_delivery" rows='1' style="height:40px;"></textarea>
			                                    </div>
			                                    <span class="form-text text-muted">Please enter the terms of delivery..</span>
			                                </div>
			                            </div>
			                            <div class="col-sm-3">
			                                <div class="form-group">
			                                    <label>Other Reference(s)</label>
												<div class="kt-input-icon">
				                                    <input class="form-control" name="other_ref" id="other_ref">
				                                </div>
			                                    <span class="form-text text-muted">Please enter other reference(s)..</span>
			                                </div>
			                            </div>
			                        </div>
								</div>
							</div>
						</div>

						<!--end: Form Wizard Step 3-->

						<!--begin: Form Wizard Step 4-->
						<div class="kt-wizard-v3__content" data-ktwizard-type="step-content">
							<div class="kt-heading kt-heading--md">Dispatch Details</div>
							<div class="kt-form__section kt-form__section--first">
								<div class="kt-wizard-v3__form">
									<div class="form-group row">
										<div class="col-sm-3">
			                                <div class="form-group">
			                                    <label>Medium</label>
												<div class="kt-input-icon">
			                                    	<input name="despatch_medium" placeholder="Medium" id="despatch_medium" class="form-control" type="text">
			                                    </div>
			                                    <span class="form-text text-muted">Please enter the medium..</span>
			                                </div>
			                            </div>
			                            <div class="col-sm-3">
			                                <div class="form-group">
			                                    <label>Document #</label>
												<div class="kt-input-icon">
			                                    	<input name="despatch_doc_no" placeholder="Document #" id="despatch_doc_no" class="form-control" type="text">
			                                    </div>
			                                    <span class="form-text text-muted">Please enter the document #..</span>
			                                </div>
			                            </div>
			                            <div class="col-sm-3">
			                                <div class="form-group">
			                                    <label>Date</label>
												<div class="kt-input-icon">
			                                    	<input name="despatch_date" placeholder="Date" id="despatch_date" class="form-control date-picker" type="text">
			                                    </div>
			                                    <span class="form-text text-muted">Please enter the date..</span>
			                                </div>
			                            </div>
			                            <div class="col-sm-3">
			                                <div class="form-group">
			                                    <label>Destination</label>
												<div class="kt-input-icon">
			                                    	<input name="despatch_destination" placeholder="Destination" id="despatch_destination" class="form-control" type="text">
			                                    </div>
			                                    <span class="form-text text-muted">Please enter the destination..</span>
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
                                    <div class="kt-wizard-v3__review-item">
										<div class="kt-wizard-v3__review-title">
											Invoice Details
										</div>
										<div id="review_si_client" class="kt-wizard-v3__review-content"></div>
										<div id="review_sales_invoice_no" class="kt-wizard-v3__review-content"></div>
										<div id="review_sales_invoice_date" class="kt-wizard-v3__review-content"></div>
										<div id="review_si_series" class="kt-wizard-v3__review-content"></div>
										<div id="review_shipping_add_1" class="kt-wizard-v3__review-content"></div>
										<div id="review_shipping_add_2" class="kt-wizard-v3__review-content"></div>
										<div id="review_shipping_add_3" class="kt-wizard-v3__review-content"></div>
										<div id="review_shipping_state" class="kt-wizard-v3__review-content"></div>
										<div id="review_si_sales_order" class="kt-wizard-v3__review-content"></div>
										<div id="review_si_quotation" class="kt-wizard-v3__review-content"></div>
									</div>
									<div class="kt-wizard-v3__review-item">
										<div class="kt-wizard-v3__review-title">
											Buyer's Details
										</div>
										<div id="review_buyer_order_no" class="kt-wizard-v3__review-content"></div>
										<div id="review_buyer_order_date" class="kt-wizard-v3__review-content"></div>
										<div id="review_terms_payment" class="kt-wizard-v3__review-content"></div>
										<div id="review_terms_delivery" class="kt-wizard-v3__review-content"></div>
										<div id="review_other_ref" class="kt-wizard-v3__review-content"></div>
									</div>
									<div class="kt-wizard-v3__review-item">
										<div class="kt-wizard-v3__review-title">
											Dispatch Details
										</div>
										<div id="review_despatch_medium" class="kt-wizard-v3__review-content"></div>
										<div id="review_despatch_doc_no" class="kt-wizard-v3__review-content"></div>
										<div id="review_despatch_date" class="kt-wizard-v3__review-content"></div>
										<div id="review_despatch_destination" class="kt-wizard-v3__review-content"></div>
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
							<button id="sales_invoice_submit" class="btn btn-success btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u" data-ktwizard-type="action-submit">
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
							</div>
						</div>
					</div>
				</div>
				<!--end: Search Form -->
				<!--begin: Datatable -->
					<div class="kt-datatable" id="sales_secondary_datatable"></div>
				<!--end: Datatable -->
			</div>
		</div>
	</div>
	<!--end::Portlet-->
</div>
<!-- end:: Content -->

<script type="text/javascript">
	function myFunction()
    {
        var si_client = document.getElementById('si_client')
        var review_si_client = document.getElementById('review_si_client');
        review_si_client.innerHTML =document.write='<strong>Client: </strong>'+si_client.value;

        var sales_invoice_no = document.getElementById('sales_invoice_no')
        var review_sales_invoice_no = document.getElementById('review_sales_invoice_no');
        review_sales_invoice_no.innerHTML =document.write='<strong>Sales Invoice No: </strong>'+sales_invoice_no.value;

        var sales_invoice_date = document.getElementById('sales_invoice_date')
        var review_sales_invoice_date = document.getElementById('review_sales_invoice_date');
        review_sales_invoice_date.innerHTML =document.write='<strong>Sales Invoice Date: </strong>'+sales_invoice_date.value;

        var si_series = document.getElementById('si_series')
        var review_si_series = document.getElementById('review_si_series');
        review_si_series.innerHTML =document.write='<strong>Sales Invoice Series: </strong>'+si_series.value;

        var shipping_add_1 = document.getElementById('shipping_add_1')
        var review_shipping_add_1 = document.getElementById('review_shipping_add_1');
        review_shipping_add_1.innerHTML =document.write='<strong>Address Line 1: </strong>'+shipping_add_1.value;

        var shipping_add_2 = document.getElementById('shipping_add_2')
        var review_shipping_add_2 = document.getElementById('review_shipping_add_2');
        review_shipping_add_2.innerHTML =document.write='<strong>Address Line 2: </strong>'+shipping_add_2.value;

        var shipping_add_3 = document.getElementById('shipping_add_3')
        var review_shipping_add_3 = document.getElementById('review_shipping_add_3');
        review_shipping_add_3.innerHTML =document.write='<strong>Address Line 3: </strong>'+shipping_add_3.value;

        var shipping_state = document.getElementById('shipping_state')
        var review_shipping_state = document.getElementById('review_shipping_state');
        review_shipping_state.innerHTML =document.write='<strong>Shipping State: </strong>'+shipping_state.value;

        var si_sales_order = document.getElementById('si_sales_order')
        var review_si_sales_order = document.getElementById('review_si_sales_order');
        review_si_sales_order.innerHTML =document.write='<strong>Sales Order No: </strong>'+si_sales_order.value;

        var si_quotation = document.getElementById('si_quotation')
        var review_si_quotation = document.getElementById('review_si_quotation');
        review_si_quotation.innerHTML =document.write='<strong>Quotation No: </strong>'+si_quotation.value;

        var buyer_order_no = document.getElementById('buyer_order_no')
        var review_buyer_order_no = document.getElementById('review_buyer_order_no');
        review_buyer_order_no.innerHTML =document.write='<strong>Buyer Order No: </strong>'+buyer_order_no.value;

        var buyer_order_date = document.getElementById('buyer_order_date')
        var review_buyer_order_date = document.getElementById('review_buyer_order_date');
        review_buyer_order_date.innerHTML =document.write='<strong>Buyer Order Date: </strong>'+buyer_order_date.value;

        var terms_payment = document.getElementById('terms_payment')
        var review_terms_payment = document.getElementById('review_terms_payment');
        review_terms_payment.innerHTML =document.write='<strong>Terms of Payment: </strong>'+terms_payment.value;

        var terms_delivery = document.getElementById('terms_delivery')
        var review_terms_delivery = document.getElementById('review_terms_delivery');
        review_terms_delivery.innerHTML =document.write='<strong>Terms of Delivery: </strong>'+terms_delivery.value;

        var other_ref = document.getElementById('other_ref')
        var review_other_ref = document.getElementById('review_other_ref');
        review_other_ref.innerHTML =document.write='<strong>Other Reference No: </strong>'+other_ref.value;

        var despatch_medium = document.getElementById('despatch_medium')
        var review_despatch_medium = document.getElementById('review_despatch_medium');
        review_despatch_medium.innerHTML =document.write='<strong>Dispatch Medium: </strong>'+despatch_medium.value;

        var despatch_doc_no = document.getElementById('despatch_doc_no')
        var review_despatch_doc_no = document.getElementById('review_despatch_doc_no');
        review_despatch_doc_no.innerHTML =document.write='<strong>Dispatch Document No: </strong>'+despatch_doc_no.value;

        var despatch_date = document.getElementById('despatch_date')
        var review_despatch_date = document.getElementById('review_despatch_date');
        review_despatch_date.innerHTML =document.write='<strong>Dispatch Date: </strong>'+despatch_date.value;

        var despatch_destination = document.getElementById('despatch_destination')
        var review_despatch_destination = document.getElementById('review_despatch_destination');
        review_despatch_destination.innerHTML =document.write='<strong>Despatch Destination: </strong>'+despatch_destination.value;

    }
</script>