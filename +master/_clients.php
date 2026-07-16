<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

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
						</div>
						<div class="col-xl-4 order-1 order-xl-2 kt-align-right">
							<a href="#" class="btn btn-default kt-hidden">
								<i class="la la-cart-plus"></i> New Order
							</a>
							<div class="kt-separator kt-separator--border-dashed kt-separator--space-lg d-xl-none"></div>
						</div>
					</div>
				</div>
				<!--end: Search Form -->
			</div>
			<!--begin: Datatable -->
			<div class="kt-datatable" id="dcs_clients_datatable"></div>

			<!--end: Datatable -->

			<!--begin::Client Edit Modal-->
			<form class="kt-form kt-form--label-right" id="dcs_edit_client">
				<div class="modal fade" id="kt_modal_edit_client" tabindex="-1" role="dialog" aria-labelledby="editClientModal" aria-hidden="true">
					<div class="modal-dialog modal-xl" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="editClientModal">Edit Client</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								</button>
							</div>
							<div class="modal-body">
								<!--begin::Form-->
									<div class="kt-portlet__body">
										<div class="form-group row">
											<div class="col-lg-3">
												<label>Client Name:</label>
												<div class="kt-input-icon">
													<input type="text" class="form-control" placeholder="Enter Client Name" id="edit_client_name" name="edit_client_name">
												</div>
												<span class="form-text text-muted">Please enter name of the client..</span>
											</div>
											<div class="col-lg-3">
												<label>Printed Name:</label>
												<div class="kt-input-icon">
													<input type="text" class="form-control" placeholder="Enter Print Name" id="edit_client_print_name" name="edit_client_print_name">
												</div>
												<span class="form-text text-muted">Please enter name to be printed..</span>
											</div>
											<div class="col-lg-2">
												<label class="">Client Category:</label><br/>
												<div class="kt-input-icon">
													<select class="form-control kt-select2" id="edit_client_category" name="edit_client_category">
														<option></option>
													</select>
												</div>
												<span class="form-text text-muted">Please select the appropriate category..</span>
											</div>
											<div class="col-lg-2">
												<label class="">Vendor Code:</label><br/>
												<div class="kt-input-icon">
												<input type="text" class="form-control" placeholder="Enter Vendor Code:" id="edit_vendor_code" name="edit_vendor_code">
														
												</div>
												<span class="form-text text-muted">Please enter Vendor Code:</span>
											</div>
											<div class="col-lg-2">
												<label class="">Vendor Discount:</label><br/>
												<div class="kt-input-icon">
												<input type="text" class="form-control" placeholder="Enter Vendor Discount:" id="edit_vendor_discount" name="edit_vendor_discount">
														
												</div>
												<span class="form-text text-muted">Please enter Vendor Discount:</span>
											</div>
										</div>
										<div class="form-group row">
											<div class="col-lg-3">
												<label>Address Line 1:</label>
												<input type="text" class="form-control" placeholder="Enter Address Line 1"  id="edit_client_add_1" name="edit_client_add_1">
											</div>
											<div class="col-lg-3">
												<label>Address Line 2:</label>
												<div class="kt-input-icon">
													<input type="text" class="form-control" placeholder="Enter Address Line 2"  id="edit_client_add_2" name="edit_client_add_2">
												</div>
											</div>
											<div class="col-lg-3">
												<label>City:</label>
												<div class="kt-input-icon">
													<input type="text" class="form-control" placeholder="Enter City"  id="edit_client_city" name="edit_client_city">
												</div>
											</div>
											<div class="col-lg-3">
												<label>Pincode:</label>
												<div class="kt-input-icon">
													<input type="text" class="form-control" placeholder="Enter Pincode"  id="edit_client_pincode" name="edit_client_pincode">
												</div>
											</div>
										</div>
										<div class="form-group row">
											<div class="col-lg-3">
												<label>GSTIN:</label>
												<div class="kt-input-icon">
													<input type="text" class="form-control" placeholder="Enter GSTIN"  id="edit_client_gstin" name="edit_client_gstin" maxlength="15">
												</div>
											</div>
											<div class="col-lg-3">
												<label>GSTIN Type:</label>
												<div class="kt-input-icon">
													<select class="form-control kt-select2 edit_client_gstin_type-select2" id="edit_client_gstin_type" name="edit_client_gstin_type">
														<option value="Registered" selected="">Registered(Regular)</option>
														<option value="Unregistered">Un Registered</option>c
														<option value="Consumer">Consumer</option>
														<option value="Composite">Composite</option>
													</select>
												</div>
											</div>
											<div class="col-lg-3">
												<label>State:</label>
												<div class="kt-input-icon">
													<select class="form-control kt-select2 client_state-select2" id="edit_client_state" name="edit_client_state">
													</select>
												</div>
											</div>
											<div class="col-lg-3">
												<label>Country:</label>
												<div class="kt-input-icon">
													<input type="text" class="form-control" placeholder="Enter Country"  id="edit_client_country" name="edit_client_country">
												</div>
											</div>
										</div>
										<div class="form-group row">
											<div class="col-lg-3">
												<label>Credit (in days):</label>
												<div class="kt-input-icon">
													<input type="text" class="form-control"  id="edit_client_credit" name="edit_client_credit" maxlength="15">
												</div>
											</div>
											<div class="col-lg-3">
												<label>Opening Balance:</label>
												<div class="kt-input-icon">
													<input type="text" class="form-control" id="edit_client_opening" name="edit_client_opening">
												</div>
											</div>
										</div>
										<div class="form-group row">
											<div class="col-md-12">
												<label>Contact Persons:</label>
											</div>
											<div id="kt_repeater_client_edit" class="col-lg-12">
												<div class="form-group form-group-last row" id="kt_repeater_1">
													<div data-repeater-list="edit_client" class="col-lg-12">
														<div data-repeater-item class="form-group row align-items-center">
															<div class="col-md-3">
																	<input placeholder="Name" name="edit_client_person" id="edit_client_person" class="form-control" type="text">
															</div>
															<div class="col-md-3">
																	<input placeholder="Designation" name="edit_client_designation" id="edit_client_designation" class="form-control" type="text">
															</div>
															<div class="col-md-2">
																	<input placeholder="Mobile" name="edit_client_mobile" id="edit_client_mobile" class="form-control" type="text">
															</div>
															<div class="col-md-3">
																	<input placeholder="Email" name="edit_client_email" id="edit_client_email" class="form-control" type="text">
															</div>
															<div class="col-md-1">
																<a href="javascript:;" data-repeater-delete="" class="btn-sm btn btn-label-danger btn-bold">
																	<i class="la la-trash-o"></i>
																</a>
															</div>
														</div>
													</div>
												</div>
												<div class="form-group form-group-last row">
													<div class="col-lg-4">
														<a href="javascript:;" data-repeater-create="" class="btn btn-bold btn-sm btn-label-brand" id="edit_client_btn">
															<i class="la la-plus"></i> Add
														</a>
													</div>
												</div>
											</div>
										</div>
										
										<div class="form-group row col-lg-12">
											<div class="col-md-12">
												<label>Bank Details:</label>
											</div>
											<div class="col-md-3">
												<label>Name:</label>
												<div class="kt-input-icon">
													<input placeholder="Enter Account Name" name="edit_bank_client" id="edit_bank_client" class="form-control" type="text">
												</div>
											</div>
											<div class="col-md-3">
												<label>Bank:</label>
												<div class="kt-input-icon">
													<input placeholder="Enter Bank Name" name="edit_bank_name" id="edit_bank_name" class="form-control" type="text">
												</div>
											</div>
											<div class="col-md-3">
												<label>Account Number:</label>
												<div class="kt-input-icon">
													<input placeholder="Enter Account Number" name="edit_bank_account" id="edit_bank_account" class="form-control" type="text">
												</div>
											</div>
											<div class="col-md-3">
												<label>IFSC Code:</label>
												<div class="kt-input-icon">
													<input placeholder="Enter IFSC Code" name="edit_bank_ifsc" id="edit_bank_ifsc" class="form-control" type="text">
												</div>
											</div>
										</div>
									</div>
								<!--end::Form-->
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
								<button id="dcs_edit_client_submit" type="submit" class="btn btn-primary">Save</button>
							</div>
						</div>
					</div>
				</div>
			</form>
			<!--end::Client Modal-->

			<!--begin::Delete Client Modal-->
			<div class="modal fade" id="kt_modal_d_client" tabindex="-1" role="dialog" aria-labelledby="deleteClientModal" aria-hidden="true">
				<div class="modal-dialog modal-xl" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="deleteClientModal" >Delete Client</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							</button>
						</div>
						<div class="modal-body">
							<!--begin::Form-->
							<form class="kt-form kt-form--label-right">
								<div class="kt-portlet__body">
									Are you sure you want to delete this client ?
								</div>
							</form>

							<!--end::Form-->
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							<button id="dcs_delete_client_submit" type="button" class="btn btn-primary">Delete</button>
						</div>
					</div>
				</div>
			</div>
			<!--end::Delete Client Modal-->
		</div>
	</div>

	<!--end::Portlet-->
</div>

<!-- end:: Content -->