	<!-- begin:: Content --> 
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

	<!--Begin::Dashboard 1-->
	<div class="col-lg-12">
		<!--begin::Portlet-->
		<div class="kt-portlet" id="kt_portlet_add_contra">
			<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title">
						Contra Entry
					</h3>
				</div>
				<div class="kt-portlet__head-toolbar">
					<div class="kt-portlet__head-group">
						<a href="#" data-ktportlet-tool="toggle" class="btn btn-sm btn-icon btn-clean btn-icon-md"><i class="la la-angle-down"></i></a>
					</div>
				</div>
			</div>
			<!--begin::Form-->
			<form class="kt-form" id="add_contra">
				<div class="kt-portlet__body">
					<div class="kt-section kt-section--first">
                        <input name="contra_edit_id" id="contra_edit_id" class="form-control" type="text" style="display:none;">
						<div class="form-group row">
							<div class="col-sm-3">
                                <div class="form-group">
                                    <label>Date</label>
                                    <input placeholder="Date" name="contra_date" id="contra_date" class="form-control date-picker" type="text" data-date-end-date="+3m" value="<?php echo date('d-m-Y', strtotime('today')); ?>">
                                </div>
                            </div>
							<div class="col-sm-3">
                                <div class="form-group">
                                    <label>Transfer From</label>
                                    <select class="form-control kt-select2 contra_bank_1-select2" name="contra_bank_1" id="contra_bank_1">
                                    	<option></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Transfer To</label>
                                    <select class="form-control kt-select2 contra_bank_2-select2" name="contra_bank_2" id="contra_bank_2">
                                    	<option></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Amount</label>
                                    <input placeholder="Amount" name="contra_amount" id="contra_amount" class="form-control" type="text">
                                </div>
                            </div>
                        </div>
					</div>
		            <div class="kt-portlet__foot">
						<div class="kt-form__actions" style="float:right;">
							<button id="dcs_add_contra_submit" type="submit" class="btn btn-primary">Submit</button>
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
							<div class="col-xl-8 invoice-2 invoice-xl-1">
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
				<div class="kt-datatable" id="contra_datatable"></div>
				<!--end: Datatable -->
			</div>
		</div>

		<!--end::Portlet-->
	</div>
	<!--End::Dashboard 1-->
</div>
<!-- end:: Content -->

<!--begin::Delete Contra Modal-->
<div class="modal fade" id="kt_modal_d_contra" tabindex="-1" role="dialog" aria-labelledby="deleteContraModal" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="deleteContraModal" >Delete Contra Entry</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				</button>
			</div>
			<div class="modal-body">
				<!--begin::Form-->
				<form class="kt-form kt-form--label-right">
					<div class="kt-portlet__body">
						Are you sure you want to delete this contra entry ?
					</div>
				</form>

				<!--end::Form-->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button id="delete_contra_submit" type="button" class="btn btn-primary">Delete</button>
			</div>
		</div>
	</div>
</div>
<!--end::Delete Contra Modal