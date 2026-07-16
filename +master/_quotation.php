<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    
    <?php

        $username = $_SESSION['username'];
        $userlevel = $_SESSION['userlevel'];

        $sql_access = "SELECT * FROM users WHERE `username` = '$username'";
        $query_access = $db->query($sql_access);
        $row_access = $query_access->fetch_assoc();

        $menu_access = json_decode($row_access['access'], true);

        if($menu_access['quotation']['create'] == '1' || $userlevel == "sadmin_df56fdg"){

            
    ?>
    
	<div class="kt-portlet" id="kt_portlet_add_q">
		
		<div class="kt-portlet__body kt-portlet__body--fit">
			<div class="kt-grid kt-wizard-v3 kt-wizard-v3--white" id="kt_wizard_v3" data-ktwizard-state="step-first">
				<div class="kt-grid__item">

					<!--begin: Form Wizard Nav -->
					<div class="kt-wizard-v3__nav">

						<div class="kt-wizard-v3__nav-items">
							<div class="kt-wizard-v3__nav-item" data-ktwizard-type="step" data-ktwizard-state="current">
								<div class="kt-wizard-v3__nav-body">
									<div class="kt-wizard-v3__nav-label">
										<span>1</span> Quotation Details
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
										<span>3</span> Terms & Conditions
									</div>
									<div class="kt-wizard-v3__nav-bar"></div>
								</div>
							</div>
							<div class="kt-wizard-v3__nav-item" data-ktwizard-type="step">
								<div class="kt-wizard-v3__nav-body">
									<div class="kt-wizard-v3__nav-label">
										<span>4</span> Review and Submit
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
					<form class="kt-form" style="width:95% !important" id="add_quotation">

						<!--begin: Form Wizard Step 1-->
						<div class="kt-wizard-v3__content" data-ktwizard-type="step-content" data-ktwizard-state="current">
							
							<div class="kt-form__section kt-form__section--first">
								<div class="kt-wizard-v3__form">
									<input type="text" id="q_id" name="q_id" style="display: none">
									<input type="text" id="q_state" name="q_state" style="display: none">

									<div class="form-group row">
										<div class="col-sm-3">
			                                <div class="form-group">
			                                    <label>Client</label>
												<div class="kt-input-icon">
				                                    <select class="form-control kt-select2 q_client-select2" name="q_client" id="q_client">
				                                    	<option></option>
				                                    </select>
				                                </div>
			                                    <span class="form-text text-muted">Please enter name of the client..</span>
			                                </div>
			                            </div>
										<div class="col-sm-3">
			                                <div class="form-group">
			                                    <label>Quotation No#</label>
												<div class="kt-input-icon">
			                                    	<input name="quotation_no" placeholder="Quotation No#" id="quotation_no" class="form-control" type="text" value="<?php echo $quotation_number; ?>" readonly>
			                                    </div>
			                                    <span class="form-text text-muted">Please enter the quotation number#..</span>
			                                </div>
			                            </div>
			                            <div class="col-sm-3">
										
			                                <div class="form-group">
			                                    <label>Mobile No</label>
												<div class="kt-input-icon">
			                                    	<input placeholder="Mobile No" class="form-control" name="mobile" id="mobile" type="text">
			                                    </div>
			                                </div>
			                           
			                            </div>
			                            <div class="col-sm-3">
			                                <div class="form-group">
			                                    <label>Quotation Date</label>
												<div class="kt-input-icon">
			                                    	<input placeholder="Quotation Date" name="quotation_date" id="quotation_date" class="form-control date-picker" type="text" data-date-end-date="+3m" value="<?php echo date('d-m-Y', strtotime('today')); ?>">
			                                    </div>
			                                    <span class="form-text text-muted">Please enter the quotation date..</span>
			                                </div>
			                            </div>
			                        </div>

			                        <div class="form-group row">
										<div class="col-sm-4">
			                                <div class="form-group">
			                                    <label>Address 1</label>
												<div class="kt-input-icon">
			                                    	<input placeholder="Address 1" class="form-control" name="address_1" id="address_1" type="text">
			                                    </div>
			                                </div>
			                            </div>
			                            <div class="col-sm-4">
			                                <div class="form-group">
			                                    <label>Address 2</label>
												<div class="kt-input-icon">
			                                    	<input placeholder="Address 2" class="form-control" name="address_2" id="address_2" type="text">
			                                    </div>
			                                </div>
			                            </div>
			                            <div class="col-sm-4">
			                                <div class="form-group">
			                                    <label>Country</label>
												<div class="kt-input-icon">
			                                    	<input placeholder="Country" class="form-control" name="country" id="country" type="text">
			                                    </div>
			                                </div>
			                            </div>
			                        </div>

			                        <div class="form-group row">
			                            <div class="col-sm-4">
			                                <div class="form-group">
			                                    <label>State</label>
												<div class="kt-input-icon">
			                                    	<input placeholder="State" class="form-control" name="state" id="state" type="text">
			                                    </div>
			                                </div>
			                            </div>
			                            <div class="col-sm-4">
			                                <div class="form-group">
			                                    <label>City</label>
												<div class="kt-input-icon">
			                                    	<input placeholder="City" class="form-control" name="city" id="city" type="text">
			                                    </div>
			                                </div>
			                            </div>
			                            <div class="col-sm-4">
			                                <div class="form-group">
			                                    <label>Pincode</label>
												<div class="kt-input-icon">
			                                    	<input placeholder="Pincode" class="form-control" name="pincode" id="pincode" type="text">
			                                    </div>
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
								<div class="kt-wizard-v3__form">
									<div class="form-group row">
										<!-- <div class="col-sm-3">
			                                <div class="form-group">
			                                    <label>Enquiry No</label>
												<div class="kt-input-icon">
			                                    	<select class="form-control kt-select2 kt-select2-multiple q_enquiry_no-select2" name="q_enquiry_no[]" id="q_enquiry_no">
				                                    </select>
			                                    </div>
			                                    <span class="form-text text-muted">Please enter enquiry number..</span>
			                                </div>
			                            </div> -->
			                            <div class="col-sm-3">
			                                <div class="form-group">
			                                	<label>Enquiry No</label>
												<div class="kt-input-icon">
			                                    	<input name="q_cl_enquiry_no" id="q_cl_enquiry_no" class="form-control" type="text">
			                                    </div>
			                                    <span class="form-text text-muted">Please enter enquiry number..</span>
			                                </div>
			                            </div>
			                            <div class="col-sm-3">
			                                <div class="form-group">
			                            		<label>Enquiry Date</label>
												<div class="kt-input-icon">
			                                    	<input name="q_enquiry_date" id="q_enquiry_date" class="form-control" type="date">
			                                    </div>
			                                    <span class="form-text text-muted">Please enter enquiry date..</span>
			                                </div>
			                            </div>
			                            <div class="col-sm-3">
			                                <div class="form-group">
			                                	<label>Contact Person</label>
												<div class="kt-input-icon">
			                                    	<input name="q_contact_person" id="q_contact_person" class="form-control" type="text">
			                                    </div>
			                                    <span class="form-text text-muted">Please enter Contact Person..</span>
			                                </div>
			                            </div>
			                            <div class="col-sm-3">
			                                <div class="form-group">
			                                	<label>Contact No</label>
												<div class="kt-input-icon">
			                                    	<input name="q_contact_no" id="q_contact_no" class="form-control" type="text">
			                                    </div>
			                                    <span class="form-text text-muted">Please enter contact number..</span>
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
										<div id="kt_repeater_q">
											<div id="kt_repeater_1">
												<div data-repeater-list="quotation" id="quotation_list" class="col-lg-12">
													<div data-repeater-item class="form-group row align-items-center" style="border-bottom: 1px dashed #5d78ff; padding-bottom:1rem; margin-bottom: 1rem;">
														<div class="col-md-1" style="margin-top:3px; text-align:center">
															<input type="text" class="form-control" name="q_sn" value="1" style="border: none;font-weight: 900;text-align: center;" readonly="">
														</div>
														<div class="col-md-3">
															<div class="kt-form__group--inline">
																<div class="kt-form__control">
																	<select class="form-control kt-select2 q_product_name-select2" name="q_product_name">
			                                                    	</select>
																</div>
															</div>
															<span class="product_stock" name="product_stock"></span>
														</div>
														<div class="col-md-4" >
															<input type="text" class="form-control" name="q_product_description" placeholder="Product Name">
														</div>
														<div class="col-md-4" >
															<textarea class="form-control" placeholder="Product Description" name="q_product_add_description" rows='1'></textarea>
														</div>
														<div class="col-md-1" style="margin-top:3px">
														</div>
														<div class="col-md-1" style="margin-top:3px">
															<div class="input-group">
																<input placeholder="Qty" name="q_qty" class="form-control q_qty" type="text">
															</div>
														</div>
														<div class="col-md-1" style="margin-top:3px">
															<div class="kt-form__group--inline">
																<div class="kt-form__control">
																	<select name="q_unit" class="form-control kt-select2 q_unit-select2"> 
			                                                        </select>
																</div>
															</div>
														</div>
														<div class="col-md-1" style="margin-top:3px">
															<div class="input-group">
			                                                    <input placeholder="Price" name="q_rate" class="form-control q_rate" type="text">
															</div>
														</div>
														<div class="col-md-1" style="margin-top:3px">
															<div class="input-group">
																<input placeholder="Discount" name="q_dsc" class="form-control q_dsc" type="text">
															</div>
														</div>

														<div class="col-md-1" style="margin-top:3px">
															<div class="input-group">
																<input placeholder="HSN" name="q_hsn" class="form-control q_hsn" type="text">
															</div>
														</div>
														<div class="col-md-1" style="margin-top:3px">
															<div class="kt-form__group--inline">
																<div class="kt-form__control">
																	<select name="q_tax" class="form-control kt-select2 q_tax-select2"> 
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
			                                                    <input type="text" class="form-control q_gross_pr" name="q_gross_pr" style="background-color: #eee" readonly>
															</div>
														</div>	
														<div class="col-md-1" style="margin-top:3px">
															<div class="input-group">
			                                                    <input type="text" class="form-control q_tax_pr" name="q_tax_pr" style="background-color: #eee"  readonly>
															</div>
														</div>
														<input type="text" name="q_cgst" style="display: none">
														<input type="text" name="q_sgst" style="display: none">
														<input type="text" name="q_igst" style="display: none">
														<div class="col-md-1" style="margin-top:3px">
															<div class="input-group">
			                                                    <input type="text" class="form-control q_total_pr" name="q_total_pr" style="background-color: #eee" readonly>
															</div>
														</div>
														<div class="col-md-1" style="margin-top:3px">
															<div class="kt-form__group--inline">
																<div class="kt-form__control">
																	<select name="q_display_make" class="form-control kt-select2 q_display_make-select2"> 
			                                                            <option></option>
			                                                            <option value="1">Show</option>
			                                                            <option value="0">Hide</option>
			                                                        </select>
																</div>
															</div>
														</div>
														<div class="col-md-1" style="margin-top:3px; text-align:center">
															<a href="javascript:;" data-repeater-delete="" class="btn-sm btn btn-label-danger btn-bold q_delete">
																<i class="la la-trash-o"></i>
															</a>
														</div>
													</div>
												</div>
											</div>
											<div >
												<div class="col-lg-4">
													<a href="javascript:;" data-repeater-create="quotation" id="qtn_btn_add" class="btn btn-bold btn-sm btn-label-brand">
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
			                                    <input type="text" class="form-control q_gross_final" name="q_gross_final" style="background-color: #eee; text-align:right;" readonly>
											</div>
										</div>	
									</div>
									<div class="form-group row" style="margin-bottom: 2px;">
										<div class="col-md-10">
											<div class="form-control" style="text-align:right; border: none;">Packaging & Forwarding :</div>
											
										</div>
										<div class="col-md-2">
											<div class="input-group">
			                                    <input type="text" class="form-control" name="q_pf" style="text-align:right;" id="q_pf">
											</div>
										</div>	
									</div>
									<input type="text" name="q_pf_cgst" id="q_pf_cgst" style="display: none">
									<input type="text" name="q_pf_sgst" id="q_pf_sgst" style="display: none">
									<input type="text" name="q_pf_igst" id="q_pf_igst" style="display: none">
									<div class="form-group row" style="margin-bottom: 2px;">
										<div class="col-md-10">
											<div class="form-control" style="text-align:right; border: none;">Freight :</div>
											
										</div>
										<div class="col-md-2">
											<div class="input-group">
			                                    <input type="text" class="form-control" name="q_freight" style="text-align:right;" id="q_freight">
											</div>
										</div>
									</div>
									<input type="text" name="q_freight_cgst" id="q_freight_cgst" style="display: none">
									<input type="text" name="q_freight_sgst" id="q_freight_sgst" style="display: none">
									<input type="text" name="q_freight_igst" id="q_freight_igst" style="display: none">
									<div class="form-group row" style="margin-bottom: 2px;">
										<div class="col-md-10">
											<div class="form-control" style="text-align:right; border: none;">Total Tax :</div>
											
										</div>
										<div class="col-md-2">
											<div class="input-group">
			                                    <input type="text" class="form-control q_tax_final" name="q_tax_final" style="background-color: #eee; text-align:right;" readonly>
											</div>
										</div>
									</div>
									<div class="form-group row" style="margin-bottom: 2px;">
										<div class="col-md-10">
											<div class="form-control" style="text-align:right; border: none;">Round Off :</div>
										</div>
										<div class="col-md-2">
											<div class="input-group">
			                                    <input type="text" class="form-control" name="q_round" style="background-color: #eee;text-align:right;" id="q_round" readonly="">
											</div>
										</div>
									</div>
									<div class="form-group row" style="margin-bottom: 2px;">
										<div class="col-md-10">
											<div class="form-control" style="text-align:right; border: none;">Grand Total :</div>
											
										</div>
										<div class="col-md-2">
											<div class="input-group">
			                                    <input type="text" class="form-control q_total_final" name="q_total_final" style="background-color: #eee; text-align:right;" readonly>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<!--end: Form Wizard Step 2-->

						<!--begin: Form Wizard Step 3-->
						<div class="kt-wizard-v3__content" data-ktwizard-type="step-content">
							<div class="kt-heading kt-heading--md">Terms & Conditions</div>
							<div class="kt-form__section kt-form__section--first">
								<div class="kt-wizard-v3__form">
									<div class="form-group row">
										<div class="col-sm-3">
			                                <div class="form-group">
			                                    <label>Prices</label>
												<div class="kt-input-icon">
			                                    	<input value="As Stated Above, Ex-Godown Kol" placeholder="Prices" class="form-control" name="prices" id="prices" type="text">
			                                    </div>
			                                </div>
			                            </div>
			                            <div class="col-sm-3">
			                                <div class="form-group">
			                                    <label>P & F</label>
												<div class="kt-input-icon">
			                                    	<input placeholder="P & F" value="Nil -If Hand Delivered" class="form-control" name="pf" id="pf" type="text">
			                                    </div>
			                                </div>
			                            </div>
			                            <div class="col-sm-3">
			                                <div class="form-group">
			                                    <label>Freight</label>
												<div class="kt-input-icon">
			                                    	<input placeholder="Freight" value="Nil -If Hand Delivered" name="freight" id="freight" class="form-control" type="text">
			                                    </div>
			                                </div>
			                            </div>
			                            <div class="col-sm-3">
			                                <div class="form-group">
			                                    <label>Delivery</label>
												<div class="kt-input-icon">
			                                    	<input placeholder="Delivery" value="Min 3-4 Weeks"  name="delivery" id="delivery" class="form-control" type="text">
			                                    </div>
			                                </div>
			                            </div>
										<div class="col-sm-3">
			                                <div class="form-group">
			                                    <label>Payment</label>
												<div class="kt-input-icon">
			                                    	<input placeholder="Payment" value="50% on Order Rest before Delivery"  class="form-control" name="payment" id="payment" type="text">
			                                    </div>
			                                </div>
			                            </div>
			                            <div class="col-sm-3">
			                                <div class="form-group">
			                                    <label>Validity</label>
												<div class="kt-input-icon">
			                                    	<input placeholder="Validity"  value="7 Days" class="form-control" name="validity" id="validity" type="text">
			                                    </div>
			                                </div>
			                            </div>
			                            <div class="col-sm-3">
			                                <div class="form-group">
			                                    <label>Remarks</label>
												<div class="kt-input-icon">
			                                    	<input placeholder="Remarks"  value="Check Suitability Before Order" name="remarks" id="remarks" class="form-control" type="text">
			                                    </div>
			                                </div>
			                            </div>
			                            <div class="col-sm-3">
                                    <div class="form-group " >
                                        <label>Upload Attachment:</label>
                                        <div class="dropzone dropzone-multi" id="kt_dropzone_quotation" >
                                            <div class="dropzone-panel">
                                                <a class="dropzone-select btn btn-label-brand btn-bold btn-sm">Attach file</a>
                                                <a class="dropzone-upload btn btn-label-brand btn-bold btn-sm">Upload</a>
                                                <a class="dropzone-remove-all btn btn-label-brand btn-bold btn-sm" id="remove_file_whatsapp">Remove</a>
                                            </div>
                                            <div class="dropzone-items">
                                                <div class="dropzone-item" style="display:none">
                                                    <div class="dropzone-file">
                                                        <div class="dropzone-filename" title="some_image_file_name.jpg"><span data-dz-name>some_image_file_name.jpg</span> <strong>(<span  data-dz-size>340kb</span>)</strong></div>
                                                        <div class="dropzone-error" data-dz-errormessage></div>
                                                    </div>
                                                    <div class="dropzone-progress">
                                                        <div class="progress">
                                                            <div class="progress-bar kt-bg-brand" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0" data-dz-uploadprogress></div>
                                                        </div>
                                                    </div>
                                                    <div class="dropzone-toolbar">
                                                        <span class="dropzone-start"><i class="flaticon2-arrow"></i></span>
                                                        <span class="dropzone-cancel" data-dz-remove style="display: none;"><i class="flaticon2-cross"></i></span>
                                                        <span class="dropzone-delete" data-dz-remove><i class="flaticon2-cross"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="form-text text-muted" id="quotation_display"></span>
                                    </div>
                                </div>
			                        </div>
								</div>
							</div>
						</div>

						<!--end: Form Wizard Step 3-->

						<!--begin: Form Wizard Step 4-->
						<div class="kt-wizard-v3__content" data-ktwizard-type="step-content">
							<div class="kt-heading kt-heading--md">Review your Details and Submit</div>
							<div class="kt-form__section kt-form__section--first">
								<div class="kt-wizard-v3__review">
									<div class="kt-wizard-v3__review-item">
										<div class="kt-wizard-v3__review-title">
											Quotation Details
										</div>
										<div id="client" class="kt-wizard-v3__review-content"></div>
										<div id="no" class="kt-wizard-v3__review-content"></div>
										<div id="date" class="kt-wizard-v3__review-content"></div>
									</div>
									<div class="kt-wizard-v3__review-item">
										<div class="kt-wizard-v3__review-title">
											Delivery Details
										</div>
										<div id="enquiry" class="kt-wizard-v3__review-content"></div>
									</div>
									<div class="kt-wizard-v3__review-item">
										<div class="kt-wizard-v3__review-title">
											Terms & Conditions
										</div>
										<div id="pricesdiv" class="kt-wizard-v3__review-content"></div>
										<div id="pfdiv" class="kt-wizard-v3__review-content"></div>
										<div id="freightdiv" class="kt-wizard-v3__review-content"></div>
										<div id="deliverydiv" class="kt-wizard-v3__review-content"></div>
										<div id="paymentdiv" class="kt-wizard-v3__review-content"></div>
										<div id="validitydiv" class="kt-wizard-v3__review-content"></div>
										<div id="remarksdiv" class="kt-wizard-v3__review-content"></div>
									</div>
								</div>
							</div>
						</div>

						<!--end: Form Wizard Step 4-->
					<div>
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
						</div>
						<!--end: Form Actions -->
					</form>

					<!--end: Form Wizard Form-->
				</div>
			</div>
		</div>
	</div>
	<!--begin::Portlet-->
	<?php

        }
            
    ?>
	
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
										<select class="form-control bootstrap-select" id="kt_quotation_product">
											<option></option>
										</select>
									</div>
								</div>
								<div class="col-md-3 kt-margin-b-20-tablet-and-mobile">
									<div class="kt-form__control">
										<select class="form-control bootstrap-select" id="kt_quotation_user">
											<option></option>
										</select>
									</div>
								</div>
								<div class="col-md-3 kt-margin-b-20-tablet-and-mobile">
									<div class="kt-form__control">
										<select class="form-control bootstrap-select" id="kt_quotation_status">
											<option></option>
											<option value="0">Pending</option>
											<option value="1">Completed</option>
											<option value="2">Rejected</option>
										</select>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--end: Search Form -->
				<!--begin: Datatable -->
					<div class="kt-datatable" id="quotation_datatable"></div>
				<!--end: Datatable -->
			</div>
		</div>
	</div>
	<!--end::Portlet-->
    </div>
<!-- end:: Content -->

<!--begin::Toggle HSN Table Modal-->
<div class="modal fade" id="toggle_quotation_hsn" tabindex="-1" role="dialog" aria-labelledby="toggleHSNModal" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="toggleHSNModal" >HSN Toggle Table</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				</button>
			</div>
			<div class="modal-body">
				<!--begin::Form-->
				<div class="kt-portlet__body" id="toggle_text">
					Are you sure you want to show / hide the HSN description table ?
				</div>

				<!--end::Form-->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button id="toggle_quotation_hsn_submit" type="button" class="btn btn-primary">Save</button>
			</div>
		</div>
	</div>
</div>
<!--end::Toggle HSN Table Modal-->

<!--begin::Toggle Totals Table Modal-->
<div class="modal fade" id="toggle_quotation_totals" tabindex="-1" role="dialog" aria-labelledby="toggleHSNModal" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="toggleHSNModal" >Totals Toggle Table</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				</button>
			</div>
			<div class="modal-body">
				<!--begin::Form-->
				<div class="kt-portlet__body" id="toggle_text">
					Are you sure you want to show / hide the Totals ?
				</div>

				<!--end::Form-->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button id="toggle_quotation_totals_submit" type="button" class="btn btn-primary">Save</button>
			</div>
		</div>
	</div>
</div>
<!--end::Toggle Totals Table Modal-->

<!--begin::Delete Quotation Modal-->
<div class="modal fade" id="kt_modal_d_quotation" tabindex="-1" role="dialog" aria-labelledby="deleteQuotationModal" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="deleteQuotationModal" >Delete Quotation</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				</button>
			</div>
			<div class="modal-body">
				<!--begin::Form-->
				<form class="kt-form kt-form--label-right">
					<div class="kt-portlet__body">
						Are you sure you want to delete this quotation ?
					</div>
				</form>

				<!--end::Form-->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button id="delete_quotation_submit" type="button" class="btn btn-primary">Delete</button>
			</div>
		</div>
	</div>
</div>
<!--end::Delete Quotation Modal-->

<!--begin::Cancel Quotation Modal-->
<div class="modal fade" id="cancel_quotation" tabindex="-1" role="dialog" aria-labelledby="cancelQuotationModal" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="cancelQuotationModal" >Cancel Quotation</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				</button>
			</div>
			<div class="modal-body">
				<!--begin::Form-->
				<form class="kt-form kt-form--label-right">
					<div class="kt-portlet__body">
						Are you sure you want to cancel this quotation ?
					</div>
				</form>

				<!--end::Form-->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button id="cancel_quotation_submit" type="button" class="btn btn-primary">Delete</button>
			</div>
		</div>
	</div>
</div>
<!--end::Cancel Quotation Modal-->

<!--begin::Duplicate Quotation Modal-->
<div class="modal fade" id="kt_modal_duplicate_quotation" tabindex="-1" role="dialog" aria-labelledby="duplicateQuotationModal" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="duplicateQuotationModal" >Duplicate Quotation</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				</button>
			</div>
			<div class="modal-body">
				<!--begin::Form-->
				<form class="kt-form kt-form--label-right">
					<div class="kt-portlet__body">
						Are you sure you want to duplicate this quotation ?
					</div>
				</form>

				<!--end::Form-->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button id="duplicate_quotation_submit" type="Submit" class="btn btn-primary">Duplicate</button>
			</div>
		</div>
	</div>
</div>
<!--end::Duplicate Quotation Modal-->

<!--begin::Add Notes Modal-->
<form class="kt-form kt-form--label-right" id="add_qnote">
	<div class="modal fade" id="kt_modal_a_qnote" tabindex="-1" role="dialog" aria-labelledby="AddQnoteModal" aria-hidden="true">
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
							<input name="an_q_no" id="an_q_no" class="form-control" type="text" style="display:none">
							<div class="form-group row">
	                            <div class="col-sm-12">
	                                <div class="form-group">
										<div class="kt-input-icon">
	                                    	<textarea name="add_qnote" placeholder="Note..." id="add_qnote" class="form-control" type="text" ></textarea>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
						</div>
					<!--end::Form-->
				</div>
				<div class="modal-footer">
					<button id="a_qnote_submit" type="submit" class="btn btn-primary">Save</button>
				</div>
			</div>
		</div>
	</div>
</form>
<!--end::Add Notes Modal-->

<!--begin::Send whatsapp Modal-->
<form class="kt-form kt-form--label-right" id="send_quotation_whatsapp">
	<div class="modal fade" id="kt_modal_quotation_whatsapp" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Send Whatsapp</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					</button>
				</div>
				<div class="modal-body">
					<!--begin::Form-->
                    	<input name="q_no_whatsapp" id="q_no_whatsapp" style="display:none" class="form-control" type="text">

						<div class="kt-portlet__body">
							<div class="form-group row" style="margin-bottom: 0">
								<div class="col-sm-6">
	                                <div class="form-group">
	                                    <label>Mobile No (Separted by comma)</label>
										<div class="kt-input-icon">
	                                    	<input name="q_whatsapp_number" id="q_whatsapp_number" placeholder="Enter Whatsapp Number(s)" class="form-control" type="text">
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
						</div>
					<!--end::Form-->
				</div>
				<div class="modal-footer">
					<button id="q_whatsapp_submit" type="submit" class="btn btn-primary">Send</button>
				</div>
			</div>
		</div>
	</div>
</form>
<!--end::Send whatapp Modal-->

<!--begin::Send Email Modal-->
<form class="kt-form kt-form--label-right" id="send_q_email">
	<div class="modal fade" id="kt_modal_q_email" tabindex="-1" role="dialog" aria-hidden="true">
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
							<input name="q_em_id" id="q_em_id" class="form-control" type="text" style="display:none">
							<div class="form-group row">
								<div class="col-sm-6">
	                                <div class="form-group">
	                                    <label>Send To</label>
										<div class="kt-input-icon">
	                                    	<input name="q_em_email" id="q_em_email" placeholder="Email Address" class="form-control" type="text">
	                                    </div>
	                                </div>
	                            </div>
	                            <div class="col-sm-6">
	                                <div class="form-group">
	                                    <label>Subject</label>
										<div class="kt-input-icon">
	                                    	<input name="q_em_subject" id="q_em_subject"  class="form-control" type="text">
	                                    </div>
	                                </div>
	                            </div>
	                            <div class="col-sm-6">
	                                <div class="form-group">
	                                    <label>CC</label>
										<div class="kt-input-icon">
	                                    	<input name="q_em_email_cc" id="q_em_email_cc" placeholder="Email Address"  class="form-control" type="text">
	                                    </div>
	                                </div>
	                            </div>
	                            <div class="col-sm-6">
	                                <div class="form-group">
	                                    <label>BCC</label>
										<div class="kt-input-icon">
	                                    	<input name="q_em_email_bcc" id="q_em_email_bcc" placeholder="Email Address" class="form-control" type="text">
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
							<div class="form-group row">
	                            <div class="col-sm-12">
	                                <div class="form-group">
										<div class="kt-input-icon">
	                                    	<input name="q_em_message" id="q_em_message" class="summernote">

	                                    </div>
	                                </div>
	                            </div>
	                        </div>
						</div>
					<!--end::Form-->
				</div>
				<div class="modal-footer">
					<button id="q_email_submit" type="submit" class="btn btn-primary">Send</button>
				</div>
			</div>
		</div>
	</div>
</form>
<!--end::Send Email Modal-->

<script type="text/javascript">
	function myFunction()
    {
        var client = document.getElementById('q_client')
        var clientdiv = document.getElementById('client');
        clientdiv.innerHTML =document.write='<strong>Client: </strong>'+client.value;

        var no = document.getElementById('quotation_no')
        var nodiv = document.getElementById('no');
        nodiv.innerHTML =document.write='<strong>Quotation No: </strong>'+no.value;

        var date = document.getElementById('quotation_date')
        var datediv = document.getElementById('date');
        datediv.innerHTML =document.write='<strong>Quotation Date: </strong>'+date.value;

        var enquiry = document.getElementById('q_enquiry_no')
        var enquirydiv = document.getElementById('enquiry');
        enquirydiv.innerHTML =document.write='<strong>Enquiry No: </strong>'+enquiry.value;

        var prices = document.getElementById('prices')
        var pricesdiv = document.getElementById('pricesdiv');
        pricesdiv.innerHTML =document.write='<strong>Prices: </strong>'+prices.value;

        var pf = document.getElementById('pf')
        var pfdiv = document.getElementById('pfdiv');
        pfdiv.innerHTML =document.write='<strong>P & F: </strong>'+pf.value;

        var freight = document.getElementById('freight')
        var freightdiv = document.getElementById('freightdiv');
        freightdiv.innerHTML =document.write='<strong>Freight: </strong>'+freight.value;

        var delivery = document.getElementById('delivery')
        var deliverydiv = document.getElementById('deliverydiv');
        deliverydiv.innerHTML =document.write='<strong>Delivery: </strong>'+delivery.value;

        var payment = document.getElementById('payment')
        var paymentdiv = document.getElementById('paymentdiv');
        paymentdiv.innerHTML =document.write='<strong>Payment: </strong>'+payment.value;

        var validity = document.getElementById('validity')
        var validitydiv = document.getElementById('validitydiv');
        validitydiv.innerHTML =document.write='<strong>Validity: </strong>'+validity.value;

        var remarks = document.getElementById('remarks')
        var remarksdiv = document.getElementById('remarksdiv');
        remarksdiv.innerHTML =document.write='<strong>Remarks: </strong>'+remarks.value;

    }
</script>
