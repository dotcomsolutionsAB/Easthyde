<title>Assembly</title>
<!-- begin:: Content --> 
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

	<!--Begin::Dashboard 1-->
	<div class="col-lg-12">
		<div class="kt-portlet" id="kt_portlet_add_pi">
			<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title">
						Assemble / Dissemble
					</h3>
				</div>
				<div class="kt-portlet__head-toolbar">
					<ul class="nav nav-pills nav-pills-sm nav-pills-label nav-pills-bold" role="tablist">
	                    <li class="nav-item">
	                        <button style="background: transparent;color:blue;" id="load_assembly_items" type="button" class="btn btn-primary">Load Items for Composite Product</button>
	                    </li>
	                </ul>
					<div class="kt-portlet__head-group">
						<a href="#" data-ktportlet-tool="toggle" class="btn btn-sm btn-icon btn-clean btn-icon-md"><i class="la la-angle-down"></i></a>
					</div>
				</div>
			</div>
			<!--begin::Form-->
			<form class="kt-form" id="add_assembly_operation">
				<div class="kt-portlet__body">
					<div class="kt-section kt-section--first">
						<input type="text" name="edit_as_id" id="edit_as_id" style="display: none">
						<div class="form-group row">
							<div class="col-sm-3">
                                <div class="form-group">
                                    <label>Date</label>
									<div class="kt-input-icon">
                                    	<input placeholder="Credit Note Date" name="assembly_date" id="assembly_date" class="form-control date-picker" type="text" data-date-end-date="+3m" value="<?php echo date('d-m-Y', strtotime('today')); ?>">
                                    </div>
                                    <span class="form-text text-muted">Please enter the date..</span>
                                </div>
                            </div>
                        </div>
						<div class="form-group row">
							<div class="col-sm-4">
		                        <div class="form-group">
		                            <label>Composite Product</label>
									<div class="kt-input-icon">
		                                <select class="form-control kt-select2 composite_product-select2" name="composite_product" id="composite_product">
	                                    	<option></option>
	                                    </select>
		                            </div>
		                        </div>
		                    </div>
		                    <div class="col-sm-2">
		                    	<label>Quantity</label>
		                    	<input placeholder="Quantity" name="composite_qty" id="composite_qty" class="form-control composite_qty" type="text" required="">
		                    	<span class="form-text text-muted">Please enter total quantity of composite product required..</span>
		                    </div>
		                    <div class="col-sm-2">
								<label>Type</label>
		                    	<select class="form-control kt-select2 as_type-select2" name="as_type" id="as_type">
		                    		<option></option>
                                	<option value="Assembled">Assemble</option>
                                	<option value="Disassembled">Disassemble</option>
                                </select>
		                    </div>
		                    <div class="col-sm-2">
		                    	<center>
			                    	<label style="color: #d9534f ">Current Stock</label>
			                    	<input name="current_composite_stock" id="current_composite_stock" class="form-control" type="text"  style="background: transparent; border: none; text-align: center; font-weight: bold; " readonly="">
		                    	</center>
		                    </div>
		                    <div class="col-sm-2">
		                    	<center>
			                    	<label style="color: #5cb85c">Result</label>
			                    	<input name="result_composite_stock" id="result_composite_stock" class="form-control" type="text"  style="background: transparent; border: none; text-align: center;   font-weight: bold;" readonly="">
		                    	</center>
		                    </div>
		                    <!-- <div class="col-sm-2">
		                    	<label style="color: transparent;"> A</label>
		                    	<button id="load_assembly_items" type="button" class="btn">Load Items for Composite Product</button>
		                    </div> -->
                        </div>
						<div class="form-group row" style="border-bottom: 1px solid #eee; padding-bottom:1rem;">
							<!-- <div class="col-md-1">
							</div> -->
							<div class="col-md-4">
								<div class="kt-form__label">
									<label>Product:</label>
								</div>
							</div>
							<div class="col-md-2">
								<div class="kt-form__label">
									<label>Qty:</label>
									<span class="form-text text-muted">Please enter quantity required for a single composite product..</span>
								</div>
							</div>
							<div class="col-md-2">
								<center>
									<div class="kt-form__label">
										<label style="color: #d9534f ">Current Stock:</label>
									</div>
								</center>
							</div>
							<div class="col-md-2">
								<center>
									<div class="kt-form__label">
										<label style="color: #5cb85c ">Result:</label>
									</div>
								</center>
							</div>
							<div class="col-md-1"></div>
						</div>
						<div class="form-group row" >
							<div id="kt_repeater_as" style="width:100%">
								<div class="form-group form-group-last row" id="kt_repeater_assembly">
									<div data-repeater-list="assembly" id="assembly_list" class="col-lg-12">
										<div data-repeater-item class="form-group row align-items-center" style="border-bottom: 1px dashed #5d78ff; padding-bottom:1rem; margin-bottom: 1rem;">
											<!-- <div class="col-md-1" style="margin-top:3px; text-align:center">
												<input type="text" class="form-control" name="t_sn" value="1" style="border: none;font-weight: 900;text-align: center;" readonly="">
											</div> -->
											<div class="col-sm-4">
												<div class="kt-form__group--inline">
													<div class="kt-form__control">
														<select class="form-control kt-select2 as_product_name-select2" name="as_product_name">
						                            	</select>
													</div>
												</div>
											</div>
											<div class="col-sm-2" style="margin-top:3px">
												<div class="input-group">
													<input placeholder="Qty" name="as_qty" class="form-control as_qty" type="text" required="">

												</div>
											</div>
											<div class="col-sm-2">
												<div class="input-group">
													<input name="current_spare_stock" class="form-control current_spare_stock" type="text"  style="background: transparent; border: none; text-align: center; font-weight: bold; " readonly="">
												</div>
											</div>
											<div class="col-sm-2">
												<div class="input-group">
													<input name="result_spare_stock" class="form-control result_spare_stock"  type="text"  style="background: transparent; border: none; text-align: center; font-weight: bold; " readonly="">
												</div>
											</div>

											<div class="col-sm-1" style="margin-top:3px; text-align:center">
												<a href="javascript:;" data-repeater-delete="" class="btn-sm btn btn-label-danger btn-bold as_delete">
													<i class="la la-trash-o"></i>
												</a>
											</div>
										</div>
									</div>
								</div>
								<div >
									<div class="col-lg-4">
										<a href="javascript:;" data-repeater-create="assembly" id="as_btn_add" class="btn btn-bold btn-sm btn-label-brand">
											<i class="la la-plus"></i> Add
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
		            <div class="kt-portlet__foot" >
						<div class="kt-form__actions" style="float:right;">
							<button id="assembly_submit" type="submit" class="btn btn-primary">Submit</button>
						</div>
					</div>
				</div>
			</form>
			<!--end::Form-->
		</div>

		<!--begin::Portlet-->
		<div class="kt-portlet kt-portlet--mobile">
			<div class="kt-portlet__body kt-portlet__body--fit">
				<div class="kt-portlet__head">
					<div class="kt-portlet__head-label">
						<h3 class="kt-portlet__head-title">
							Assembly Operations
						</h3>
					</div>
					<div class="kt-portlet__head-toolbar">
						<div class="kt-portlet__head-group">
							<a href="#" data-ktportlet-tool="toggle" class="btn btn-sm btn-icon btn-clean btn-icon-md"><i class="la la-angle-down"></i></a>
						</div>
					</div>
				</div>
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

		<!--begin::Portlet-->
		<div class="kt-portlet" id="kt_portlet_add_e">
			<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title">
						Save Assembly Combination
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
                                    	<select class="form-control kt-select2 composite_product_2-select2" name="composite_product_2" id="composite_product_2">
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
                                    	<input type="text" id="composite_product_2_description" placeholder="Product Description" name="composite_product_2_description" class="form-control">
                                    </div>
                                    <span class="form-text text-muted">Product description...</span>
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
														<select class="form-control kt-select2 a_product_name-select2" name="a_product_name" required="">
                                                    	</select>
													</div>
												</div>
											</div>
											<div class="col-md-4">
												<div class="input-group">
													<input placeholder="Product Description" name="a_desc" class="form-control a_desc" type="text">
												</div>
											</div>
											<div class="col-md-1">
												<div class="input-group">
													<input placeholder="Qty" name="a_qty" class="form-control a_qty" type="text" required="">
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
				<div class="kt-portlet__head">
					<div class="kt-portlet__head-label">
						<h3 class="kt-portlet__head-title">
							Assembly Combinations
						</h3>
					</div>
					<div class="kt-portlet__head-toolbar">
						<div class="kt-portlet__head-group">
							<a href="#" data-ktportlet-tool="toggle" class="btn btn-sm btn-icon btn-clean btn-icon-md"><i class="la la-angle-down"></i></a>
						</div>
					</div>
				</div>
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
						<div class="modal-dialog modal-xl" role="document">
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
					                            <div class="col-sm-6">
					                                <div class="form-group">
					                                    <label>Quantity</label>
														<div class="kt-input-icon">
					                                    	<input name="assemble_qty" placeholder="Qty" id="assemble_qty" class="form-control" type="text" required="">
					                                    </div>
					                                </div>
					                            </div>
					                            <div class="col-sm-6">
					                                <div class="form-group">
					                                    <label>Place</label>
														<div class="kt-input-icon">
					                                    	<select name="assemble_place" id="assemble_place" class="form-control kt-select2 assemble_place" required=""> 
							                                    <option></option>
							                                </select>
					                                    </div>
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
						<div class="modal-dialog modal-xl" role="document">
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
					                            <div class="col-sm-6">
					                                <div class="form-group">
					                                    <label>Quantity</label>
														<div class="kt-input-icon">
					                                    	<input name="disassemble_qty" placeholder="Qty" id="disassemble_qty" class="form-control" type="text" required="">
					                                    </div>
					                                </div>
					                            </div>

					                            <div class="col-sm-6">
					                                <div class="form-group">
					                                    <label>Place</label>
														<div class="kt-input-icon">
					                                    	<select name="disassemble_place" id="disassemble_place" class="form-control kt-select2 disassemble_place" required=""> 
							                                    <option></option>
							                                </select>
					                                    </div>
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

		<!--begin::Client Edit Modal-->
			<form class="kt-form kt-form--label-right" id="dcs_tag_invoice">
				<div class="modal fade" id="kt_modal_tag_invoice" tabindex="-1" role="dialog" aria-labelledby="editClientModal" aria-hidden="true">
					<div class="modal-dialog modal-sm" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="editClientModal">Tag Invoice</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								</button>
							</div>
							<div class="modal-body">
								<!--begin::Form-->
									<div class="kt-portlet__body">
										<input type="text" class="form-control" id="assemby_tag_id" name="assemby_tag_id" style="display:none">
										<div class="form-group row">
											<div class="col-lg-12">
												<label>Sales Invoice No:</label>
												<div class="kt-input-icon">
													<input type="text" class="form-control" placeholder="Sales Invoice No" id="assemby_invoice" name="assemby_invoice">
												</div>
											</div>
										</div>
									</div>
								<!--end::Form-->
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
								<button id="tag_invoice_submit" type="submit" class="btn btn-primary">Save</button>
							</div>
						</div>
					</div>
				</div>
			</form>
			<!--end::Client Modal-->

		
	</div>
</div>