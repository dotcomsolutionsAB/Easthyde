<!-- begin:: Content --> 
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

	<!--Begin::Dashboard 1-->
	<div class="col-lg-12">
		<!--begin::Portlet-->
		<div class="kt-portlet" id="kt_portlet_add_buyer">
			<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title">
						Banks
					</h3>
				</div>
				<div class="kt-portlet__head-toolbar">
					<div class="kt-portlet__head-group">
						<a href="#" data-ktportlet-tool="toggle" class="btn btn-sm btn-icon btn-clean btn-icon-md"><i class="la la-angle-down"></i></a>
					</div>
				</div>
			</div>
			<!--begin::Form-->
			<form class="kt-form" id="add_bank">
				<div class="kt-portlet__body">
					<div class="kt-section kt-section--first">
						<input type="text" name="edit_bank_id" id="edit_bank_id" style="display: none">
						<div class="form-group row">
							<div class="col-sm-3">
                                <div class="form-group">
                                    <label>Account Name:</label>
									<div class="kt-input-icon">
                                    	<input type="text" id="account_name" placeholder="Account Name" name="account_name" class="form-control">
                                    </div>
                                    <span class="form-text text-muted">Please enter bank account name..</span>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Bank Name:</label>
									<div class="kt-input-icon">
                                    	<input type="text" id="bank_name" placeholder="Bank Name" name="bank_name" class="form-control">
                                    </div>
                                    <span class="form-text text-muted">Please enter bank name..</span>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Account Number:</label>
									<div class="kt-input-icon">
                                    	<input type="text" id="account_number" placeholder="Account Number" name="account_number" class="form-control">
                                    </div>
                                    <span class="form-text text-muted">Please enter account no..</span>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>IFSC:</label>
									<div class="kt-input-icon">
                                    	<input type="text" id="ifsc" placeholder="IFSC" name="ifsc" class="form-control">
                                    </div>
                                    <span class="form-text text-muted">Please enter IFSC..</span>
                                </div>
                            </div>
							</div>
							<div class="form-group row">
							<div class="col-sm-3">
                                <div class="form-group">
                                    <label>Opening Balance:</label>
									<div class="kt-input-icon">
                                    	<input type="text" id="opening_balance" placeholder="Opening Balance" name="opening_balance" class="form-control">
                                    </div>
                                    <span class="form-text text-muted">Please enter Opening Balance.</span>
                                </div>
                            </div>
							<div class="col-sm-3">
                                <div class="form-group">
                                    <label>Opening Balance Date:</label>
									<div class="kt-input-icon">
                                    	<input type="date" id="date" placeholder="Opening Balance Date:" name="date" class="form-control">
                                    </div>
                                    <span class="form-text text-muted">Please enter Opening Balance Date.</span>
                                </div>
                            </div>
                        </div>
					</div>
		            <div class="kt-portlet__foot">
						<div class="kt-form__actions" style="float:right;">
							<button id="bank_submit" type="submit" class="btn btn-primary">Submit</button>
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
				<div class="kt-datatable" id="bank_datatable"></div>
				<!--end: Datatable -->

				<!--begin::Delete Bank Modal-->
				<div class="modal fade" id="kt_modal_d_bank" tabindex="-1" role="dialog" aria-labelledby="deleteBankModal" aria-hidden="true">
					<div class="modal-dialog modal-xl" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="deleteBankModal" >Delete Bank</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								</button>
							</div>
							<div class="modal-body">
								<!--begin::Form-->
								<form class="kt-form kt-form--label-right">
									<div class="kt-portlet__body">
										Are you sure you want to delete this bank ?
									</div>
								</form>
								<!--end::Form-->
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
								<button id="delete_bank_submit" type="button" class="btn btn-primary">Delete</button>
							</div>
						</div>
					</div>
				</div>
				<!--end::Delete Bank Modal-->
			</div>
		</div>
		<!--end::Portlet-->
	</div>
</div>