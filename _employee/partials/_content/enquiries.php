<!-- begin:: Content --> 
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

	<!--Begin::Dashboard 1-->
	<div class="col-lg-12">
		<!--begin::Portlet-->
		<div class="kt-portlet" id="kt_portlet_add_e">
			<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title">
						Enquiry
					</h3>
				</div>
				<div class="kt-portlet__head-toolbar">
					<div class="kt-portlet__head-group">
						<a href="#" data-ktportlet-tool="toggle" class="btn btn-sm btn-icon btn-clean btn-icon-md"><i class="la la-angle-down"></i></a>
					</div>
				</div>
			</div>
			<!--begin::Form-->
			<form class="kt-form" id="add_enquiry">
				<div class="kt-portlet__body">
					<div class="kt-section kt-section--first">
						<input type="text" id="e_id" name="e_id" style="display: none">
						<div class="form-group row">
							<div class="col-sm-4">
                                <div class="form-group">
                                    <label>Client</label>
									<div class="kt-input-icon">
                                    	<select class="form-control kt-select2 e_client-select2" name="e_client" id="e_client">
	                                    	<option></option>
	                                    </select>
                                    </div>
                                    <span class="form-text text-muted">Please enter name of the client..</span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Enquiry No#</label>
									<div class="kt-input-icon">
                                    	<input name="enquiry_no" placeholder="Enquiry No#" id="enquiry_no" class="form-control" type="text"  readonly>
                                    </div>
                                    <span class="form-text text-muted">Please enter the enquiry number#..</span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Enquiry Date</label>
									<div class="kt-input-icon">
                                    	<input placeholder="Enquiry Date" name="enquiry_date" id="enquiry_date" class="form-control date-picker" type="text" data-date-end-date="+3m" value="<?php echo date('d-m-Y', strtotime('today')); ?>">
                                    </div>
                                    <span class="form-text text-muted">Please enter the enquiry date..</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4">
	                            <div class="form-group">
	                                <label>Enquiry Mode</label>
									<div class="kt-input-icon">
		                                <select class="form-control kt-select2 enquiry-select2" name="enquiry_mode" id="enquiry_mode">
		                                	<option></option>
		                                	<option value="verbal">Verbal</option>
		                                	<option value="whatsapp">WhatsApp</option>
		                                	<option value="email">Email</option>
		                                	<option value="sms">SMS</option>
		                                	<option value="indiamart">IndiaMart</option>
		                                	<option value="forwarded">Forwarded by Co.</option>
		                                </select>
		                            </div>
	                                <span class="form-text text-muted">Please enter mode of enquiry..</span>
	                            </div>
	                        </div>
	                        <div class="col-sm-4">
	                            <div class="form-group">
	                                <label>Client Enquiry No</label>
									<div class="kt-input-icon">
		                                <input name="client_enquiry_no" placeholder="Client Enquiry No#" id="client_enquiry_no" class="form-control" type="text">
		                            </div>
	                                <span class="form-text text-muted">Please enter client's enquiry no..</span>
	                            </div>
	                        </div>
	                        <div class="col-sm-4">
	                            <div class="form-group">
	                                <label>Status</label>
									<div class="kt-input-icon">
		                                <select class="form-control kt-select2 enquiry-status-select2" name="enquiry_status" id="enquiry_status">
		                                	<option value="0">Pending</option>
		                                	<option value="1">Completed</option>
		                                	<option value="2">Rejected</option>
		                                </select>
		                            </div>
	                                <span class="form-text text-muted">Please set the status of enquiry..</span>
	                            </div>
	                        </div>
                        </div>
						<div class="form-group row">
							<div class="col-md-1">
							</div>
							<div class="col-md-2">
								<div class="kt-form__label">
									<label>SKU/Part No:</label>
								</div>
							</div>
							<div class="col-md-3">
								<div class="kt-form__label">
									<label>Product Name:</label>
								</div>
							</div>
							<div class="col-md-2">
								<div class="kt-form__label">
									<label>Product Description:</label>
								</div>
							</div>
							<div class="col-md-1">
								<div class="kt-form__label">
									<label>Quantity:</label>
								</div>
							</div>
							<div class="col-md-1">
								<div class="kt-form__label">
									<label>Stock in Hand:</label>
								</div>
							</div>
							<div class="col-md-1">
								<div class="kt-form__label">
									<label>Stock in Co.:</label>
								</div>
							</div>
						</div>
						<div class="form-group row">
							<div id="kt_repeater_enquiry" style="width: 100%;">
								<div class="form-group form-group-last row" id="kt_repeater_enquiry">
									<div data-repeater-list="enquiry" id="enquiry_list" class="col-lg-12">
										<div data-repeater-item class="form-group row align-items-center" style="border-bottom: 1px dashed #5d78ff; padding-bottom:1rem; margin-bottom: 1rem;">

											<div class="col-md-1">
												<input type="text" class="form-control" name="e_sn" value="1" style="border: none;font-weight: 900;text-align: center;" readonly="">
											</div>
											<div class="col-md-2">
												<div class="kt-form__group--inline">
													<div class="kt-form__control">
														<select class="form-control kt-select2 e_product_name-select2" name="e_product_name">
                                                    	</select>
													</div>
												</div>
											</div>
											<div class="col-md-3">
												<input type="text"  class="form-control" name="e_product_description" placeholder="Product Name">
											</div>
											<div class="col-md-2" >
												<textarea class="form-control" placeholder="Product Description" name="e_product_add_description" rows='1' style="height:40px;"></textarea>
											</div>
											<div class="col-md-1">
												<div class="input-group">
													<input placeholder="Qty" name="e_qty" class="form-control e_qty" type="text">
												</div>
											</div>
											<div class="col-md-1">
												<div class="input-group">
                                                    <input placeholder="Stock" name="e_current_stock" class="form-control e_current_stock" type="text">
												</div>
											</div>
											<div class="col-md-1">
												<div class="input-group">
                                                    <input placeholder="Stock" name="e_company_stock" class="form-control e_company_stock" type="text">
												</div>
											</div>
											<div class="col-md-1" style="text-align:center">
												<a href="javascript:;" data-repeater-delete="" class="btn-sm btn btn-label-danger btn-bold e_delete">
													<i class="la la-trash-o"></i>
												</a>
											</div>
										</div>
									</div>
								</div>
								<div >
									<div class="col-lg-4">
										<a href="javascript:;" data-repeater-create="enquiry" id="enq_btn_add" class="btn btn-bold btn-sm btn-label-brand">
											<i class="la la-plus"></i> Add
										</a>
									</div>
								</div>
							</div>
						</div>						
					</div>
		            <div class="kt-portlet__foot">
						<div class="kt-form__actions" style="float:right;">
							<button id="enquiry_submit" type="submit" class="btn btn-primary">Submit</button>
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
				<div class="kt-datatable" id="enquiry_datatable"></div>
				<!--end: Datatable -->


				<!--begin::Delete Enquiry Modal-->
				<div class="modal fade" id="kt_modal_d_enquiry" tabindex="-1" role="dialog" aria-labelledby="deleteEnquiryModal" aria-hidden="true">
					<div class="modal-dialog modal-xl" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="deleteEnquiryModal" >Delete Enquiry</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								</button>
							</div>
							<div class="modal-body">
								<!--begin::Form-->
								<form class="kt-form kt-form--label-right">
									<div class="kt-portlet__body">
										Are you sure you want to delete this enquiry ?
									</div>
								</form>

								<!--end::Form-->
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
								<button id="delete_enquiry_submit" type="button" class="btn btn-primary">Delete</button>
							</div>
						</div>
					</div>
				</div>
				<!--end::Delete Enquiry Modal-->
			</div>
		</div>
		<!--end::Portlet-->
	</div>
	<!--End::Dashboard 1-->
</div>
<!-- end:: Content -->