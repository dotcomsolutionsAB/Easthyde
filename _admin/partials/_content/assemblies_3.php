<!-- begin:: Content --> 
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

	<!--Begin::Dashboard 1-->
	<div class="col-lg-12">
		<!--begin::Portlet-->
		<div class="kt-portlet" id="kt_portlet_add_e">
			<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title">
						Assemblies
					</h3>
				</div>
				<div class="kt-portlet__head-toolbar">
					<div class="kt-portlet__head-group">
						<a href="#" data-ktportlet-tool="toggle" class="btn btn-sm btn-icon btn-clean btn-icon-md"><i class="la la-angle-down"></i></a>
					</div>
				</div>
			</div>
			<!--begin::Form-->
			<form class="kt-form" id="add_assembly">
				<div class="kt-portlet__body">
					<div class="kt-section kt-section--first">
						<div class="form-group row">
							<div class="col-sm-3">
                                <div class="form-group">
                                    <label>Composite Product:</label>
									<div class="kt-input-icon">
                                    	<select class="form-control kt-select2 composite_product-select2" name="composite_product" id="composite_product">
	                                    	<option></option>
	                                    </select>
                                    </div>
                                    <span class="form-text text-muted">Please enter name of the composite product..</span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Product Description:</label>
									<div class="kt-input-icon">
                                    	<input type="text" id="composite_product_description" placeholder="Product Description" name="composite_product_description" class="form-control" readonly="">
                                    </div>
                                    <span class="form-text text-muted">Product description...</span>
                                </div>
                            </div>
                        </div>
						<div class="form-group row">
							<div class="col-md-1">
							</div>
							<div class="col-md-3">
								<div class="kt-form__label">
									<label>Select Spare Product:</label>
								</div>
							</div>
							<div class="col-md-3">
								<div class="kt-form__label">
									<label>Quantity:</label>
								</div>
							</div>
						</div>
						<div class="form-group row">
							<div id="kt_repeater_assemblies" style="width: 100%;">
								<div class="form-group form-group-last row" id="kt_repeater_assemblies">
									<div data-repeater-list="assemblies" id="assemblies_list" class="col-lg-12">
										<div data-repeater-item class="form-group row align-items-center" style="border-bottom: 1px dashed #5d78ff; padding-bottom:1rem; margin-bottom: 1rem;">

											<div class="col-md-1">
												<input type="text" class="form-control" name="a_sn" value="1" style="border: none;font-weight: 900;text-align: center;" readonly="">
											</div>
											<div class="col-md-3">
												<div class="kt-form__group--inline">
													<div class="kt-form__control">
														<select class="form-control kt-select2 a_product_name-select2" name="a_product_name">
                                                    	</select>
													</div>
												</div>
											</div>
											<div class="col-md-4">
												<div class="input-group">
													<input placeholder="Product Description" name="a_desc" class="form-control a_desc" readonly="" type="text">
												</div>
											</div>
											<div class="col-md-1">
												<div class="input-group">
													<input placeholder="Qty" name="a_qty" class="form-control a_qty" type="text">
												</div>
											</div>
											<div class="col-md-1" style="text-align:center">
												<a href="javascript:;" data-repeater-delete="" class="btn-sm btn btn-label-danger btn-bold a_delete">
													<i class="la la-trash-o"></i>
												</a>
											</div>
										</div>
									</div>
								</div>
								<div >
									<div class="col-lg-4">
										<a href="javascript:;" data-repeater-create="assemblies" id="a_btn_add" class="btn btn-bold btn-sm btn-label-brand">
											<i class="la la-plus"></i> Add
										</a>
									</div>
								</div>
							</div>
						</div>						
					</div>
		            <div class="kt-portlet__foot">
						<div class="kt-form__actions" style="float:right;">
							<button id="assemblies_submit" type="submit" class="btn btn-primary">Submit</button>
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
				<div class="kt-datatable" id="assemblies_datatable"></div>
				<!--end: Datatable -->

				<!--begin::Delete Assemblies Modal-->
				<div class="modal fade" id="kt_modal_d_assemblies" tabindex="-1" role="dialog" aria-labelledby="deleteAssembliesModal" aria-hidden="true">
					<div class="modal-dialog modal-xl" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="deleteAssembliesModal" >Delete Assemblies</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								</button>
							</div>
							<div class="modal-body">
								<!--begin::Form-->
								<form class="kt-form kt-form--label-right">
									<div class="kt-portlet__body">
										Are you sure you want to delete this assembly ?
									</div>
								</form>
								<!--end::Form-->
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
								<button id="delete_assemblies_submit" type="button" class="btn btn-primary">Delete</button>
							</div>
						</div>
					</div>
				</div>
				<!--end::Delete Assemblies Modal-->

				<!--begin::Assemble Modal-->
				<form class="kt-form kt-form--label-right" id="assemblies_assemble">
					<div class="modal fade" id="kt_modal_a_assemblies" tabindex="-1" role="dialog" aria-labelledby="AssembleModal" aria-hidden="true">
						<div class="modal-dialog modal-sm" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="AssembleModal">Assemble Product</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									</button>
								</div>
								<div class="modal-body">
									<!--begin::Form-->
										<div class="kt-portlet__body">
											<input name="a_id" id="a_id" class="form-control" type="text" style="display:none">
											<input name="composite_assemble" id="composite_assemble" class="form-control" type="text" style="display:none">
											<div class="form-group row">
					                            <div class="col-sm-8">
					                                <div class="form-group">
					                                    <label>Quantity</label>
														<div class="kt-input-icon">
					                                    	<input name="assemble_qty" placeholder="Qty" id="assemble_qty" class="form-control" type="text" >
					                                    </div>
					                                    <span class="form-text text-muted">Please enter the qty to assemble..</span>
					                                </div>
					                            </div>
					                        </div>
										</div>
									<!--end::Form-->
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
									<button id="assemblies_assemble_submit" type="submit" class="btn btn-primary">Save</button>
								</div>
							</div>
						</div>
					</div>
				</form>
				<!--end::Assemble Modal-->

				<!--begin::Disassemble Modal-->
				<form class="kt-form kt-form--label-right" id="assemblies_disassemble">
					<div class="modal fade" id="kt_modal_dis_assemblies" tabindex="-1" role="dialog" aria-labelledby="DisassembleModal" aria-hidden="true">
						<div class="modal-dialog modal-sm" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="DisassembleModal">Disassemble Product</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									</button>
								</div>
								<div class="modal-body">
									<!--begin::Form-->
										<div class="kt-portlet__body">
											<input name="d_id" id="d_id" class="form-control" type="text" style="display:none">
											<input name="composite_disassemble" id="composite_disassemble" class="form-control" type="text" style="display:none">
											<div class="form-group row">
					                            <div class="col-sm-8">
					                                <div class="form-group">
					                                    <label>Quantity</label>
														<div class="kt-input-icon">
					                                    	<input name="disassemble_qty" placeholder="Qty" id="disassemble_qty" class="form-control" type="text" >
					                                    </div>
					                                    <span class="form-text text-muted">Please enter the qty to disassemble..</span>
					                                </div>
					                            </div>
					                        </div>
										</div>
									<!--end::Form-->
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
									<button id="assemblies_disassemble_submit" type="submit" class="btn btn-primary">Save</button>
								</div>
							</div>
						</div>
					</div>
				</form>
				<!--end::Disassemble Modal-->
			</div>
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
				<div class="kt-datatable" id="assemblies_operation_datatable"></div>
				<!--end: Datatable -->

				<!--begin::Delete Assemblies Modal-->
				<div class="modal fade" id="kt_modal_d_assemblies_operation" tabindex="-1" role="dialog" aria-labelledby="deleteAssembliesOperationModal" aria-hidden="true">
					<div class="modal-dialog modal-xl" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="deleteAssembliesOperationModal" >Delete Assemblies Operation</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								</button>
							</div>
							<div class="modal-body">
								<!--begin::Form-->
								<form class="kt-form kt-form--label-right">
									<div class="kt-portlet__body">
										Are you sure you want to delete this assembly operation?
									</div>
								</form>
								<!--end::Form-->
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
								<button id="delete_assemblies_operation_submit" type="button" class="btn btn-primary">Delete</button>
							</div>
						</div>
					</div>
				</div>
				<!--end::Delete Assemblies Modal-->
			</div>
		</div>
	</div>
</div>