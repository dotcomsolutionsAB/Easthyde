<!-- begin:: Content --> 
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

	<!--Begin::Dashboard 1-->
	<div class="col-lg-12">
		<!--begin::Portlet-->
		<div class="kt-portlet" id="kt_portlet_add_e">
			<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title">
						Material Receipt Note (MRN) / Material Return Note (MRTN)
					</h3>
				</div>
				<div class="kt-portlet__head-toolbar">
					<div class="kt-portlet__head-group">
						<a href="#" data-ktportlet-tool="toggle" class="btn btn-sm btn-icon btn-clean btn-icon-md"><i class="la la-angle-down"></i></a>
					</div>
				</div>
			</div>
			<!--begin::Form-->
			<form class="kt-form" id="add_materials_received">
				<div class="kt-portlet__body">
					<div class="kt-section kt-section--first">
						<input type="text" name="mr_edit_id" id="mr_edit_id" style="display:none">
						<div class="form-group row">
							<div class="col-sm-4">
                                <div class="form-group">
                                    <label>Supplier:</label>
									<div class="kt-input-icon">
                                    	<select class="form-control kt-select2 mr_supplier_name-select2" name="mr_supplier_name" id="mr_supplier_name">
	                                    	<option></option>
	                                    </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label>Date:</label>
									<div class="kt-input-icon">
                                    	<input type="text" id="mr_date" placeholder="Date" name="mr_date" class="form-control date-picker" data-date-end-date="+3m" value="<?php echo date('d-m-Y', strtotime('today')); ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label>Voucher Type:</label>
									<div class="kt-input-icon">
                                        <select class="form-control" name="mr_voucher_type" id="mr_voucher_type">
                                            <option value="MRN" selected>MRN</option>
                                            <option value="MRTN">MRTN</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
						<div class="form-group row">
							<div class="col-md-1">
							</div>
							<div class="col-md-4">
								<div class="kt-form__label">
									<label>Select Product:</label>
								</div>
							</div>
							<div class="col-md-4">
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
									<label>Unit:</label>
								</div>
							</div>
							<div class="col-md-1">
								<div class="kt-form__label">
									<label>Rate:</label>
								</div>
							</div>
						</div>
						<div class="form-group row">
							<div id="kt_repeater_materials_received" style="width: 100%;">
								<div class="form-group form-group-last row" id="kt_repeater_materials_received">
									<div data-repeater-list="materials_received" id="materials_received_list" class="col-lg-12">
										<div data-repeater-item class="form-group row align-items-center" style="border-bottom: 1px dashed #5d78ff; padding-bottom:1rem; margin-bottom: 1rem;">

											<div class="col-md-1">
												<input type="text" class="form-control" name="mr_sn" value="1" style="border: none;font-weight: 900;text-align: center;" readonly="">
											</div>
											<div class="col-md-4">
												<div class="kt-form__group--inline">
													<div class="kt-form__control">
														<select class="form-control kt-select2 mr_product_name-select2" name="mr_product_name">
                                                    	</select>
													</div>
												</div>
											</div>
											<div class="col-md-4">
												<div class="input-group">
													<input placeholder="Product Description" name="mr_desc" class="form-control mr_desc" readonly="" type="text">
												</div>
											</div>
											<div class="col-md-1">
												<div class="input-group">
													<input placeholder="Qty" name="mr_qty" class="form-control mr_qty" type="text">
												</div>
											</div>
											<div class="col-md-1">
												<div class="input-group">
													<input placeholder="Units" name="mr_unit" class="form-control mr_unit" type="text" readonly="">
												</div>
											</div>
											<div class="col-md-1">
												<div class="input-group">
													<input placeholder="Rate" name="mr_rate" class="form-control mr_rate" type="text">
												</div>
											</div>
											<div class="col-md-1" style="text-align:center">
												<a href="javascript:;" data-repeater-delete="" class="btn-sm btn btn-label-danger btn-bold mr_delete">
													<i class="la la-trash-o"></i>
												</a>
											</div>
										</div>
									</div>
								</div>
								<div >
									<div class="col-lg-4">
										<a href="javascript:;" data-repeater-create="materials_received" id="mr_btn_add" class="btn btn-bold btn-sm btn-label-brand">
											<i class="la la-plus"></i> Add
										</a>
									</div>
								</div>
							</div>
						</div>						
					</div>
		            <div class="kt-portlet__foot">
						<div class="kt-form__actions" style="float:right;">
							<button id="materials_received_submit" type="submit" class="btn btn-primary">Submit</button>
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
				<div class="kt-datatable" id="materials_received_datatable"></div>
				<!--end: Datatable -->

				<!--begin::Delete Assemblies Modal-->
				<div class="modal fade" id="kt_modal_d_materials_received" tabindex="-1" role="dialog" aria-labelledby="deleteMaterialsReceivedModal" aria-hidden="true">
					<div class="modal-dialog modal-xl" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="deleteMaterialsReceivedModal" >Delete MRN/MRTN Entry</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								</button>
							</div>
							<div class="modal-body">
								<!--begin::Form-->
								<form class="kt-form kt-form--label-right">
									<div class="kt-portlet__body">
										Are you sure you want to delete this entry ?
									</div>
								</form>
								<!--end::Form-->
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
								<button id="delete_materials_received_submit" type="button" class="btn btn-primary">Delete</button>
							</div>
						</div>
					</div>
				</div>
				<!--end::Delete Assemblies Modal-->
			</div>
		</div>
		<!--end::Portlet-->
	</div>
</div>