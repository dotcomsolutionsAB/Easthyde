
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

	<!--begin::Portlet-->
	<div class="kt-portlet kt-portlet--mobile">
		<div class="kt-portlet__body kt-portlet__body--fit">
			<div class="kt-portlet__body">
				<!--begin: Search Form -->
				<div class="kt-form kt-form--label-right kt-margin-t-20 kt-margin-b-10">
					<div class="row align-items-center">
						<div class="col-xl-12 order-2 order-xl-1">
							<div class="row align-items-center">				
								<div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
									<div class="kt-input-icon kt-input-icon--left">
										<input type="text" class="form-control" placeholder="Search..." id="generalSearch">
										<span class="kt-input-icon__icon kt-input-icon__icon--left">
											<span><i class="la la-search"></i></span>
										</span>
									</div>
								</div>
								<div class="col-md-2 kt-margin-b-20-tablet-and-mobile">
									<div class="kt-form__control">
										<select class="form-control bootstrap-select" id="kt_pr_product_vendor">
										</select>
									</div>
								</div>
								<div class="col-md-2 kt-margin-b-20-tablet-and-mobile">
									<div class="kt-form__control">
										<select class="form-control bootstrap-select" id="kt_pr_product_category">
										</select>
									</div>
								</div>
								<div class="col-md-2 kt-margin-b-20-tablet-and-mobile">
									<div class="kt-form__control">
										<select class="form-control bootstrap-select" id="kt_pr_product_sub_category">
										</select>
									</div>
								</div>
								<div class="col-md-2 kt-margin-b-20-tablet-and-mobile">
									<div class="kt-form__control">
										<select class="form-control bootstrap-select" id="kt_pr_product_archive">
											<option></option>
											<option value='0'>Unarchived</option>
											<option value='1'>Archived</option>
										</select>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--end: Search Form -->
			</div>
			<!--begin: Datatable -->
			<div class="kt-datatable" id="product_datatable"></div>

			<!--end: Datatable -->

			<!--begin::Edit Product Modal-->
			<form class="kt-form kt-form--label-right" id="edit_product">
				<div class="modal fade" id="kt_modal_e_product" tabindex="-1" role="dialog" aria-labelledby="editProductModal" aria-hidden="true">
					<div class="modal-dialog modal-xl" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="editProductModal" >Edit Product</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								</button>
							</div>
							<div class="modal-body">
								<!--begin::Form-->
									<input type="text" id="edit_product_id" name="edit_product_id" style="display:none">

									<div class="kt-portlet__body">
										<div class="form-group row">
											<div class="col-lg-6">
												<label class="">Product Name:</label>
												<div class="kt-input-icon">
													<select class="form-control kt-select2" id="edit_product_name" name="edit_product_name"></select>
												</div>
												<span class="form-text text-muted">Please enter name of the product..</span>
											</div>
											<div class="col-lg-3">
												<label class="">Group Name:</label>
												<div class="kt-input-icon">
													<select class="form-control kt-select2" id="edit_product_group_name" name="edit_product_group_name"></select>
												</div>
												<span class="form-text text-muted">Please enter group name of the product..</span>
											</div>
											<div class="col-lg-3">
												<label class="">Vendor Name:</label>
												<div class="kt-input-icon">
													<select class="form-control kt-select2" id="edit_product_vendor_name" name="edit_product_vendor_name"></select>
												</div>
												<span class="form-text text-muted">Please enter group name of the product..</span>
											</div>
										</div>
										<div class="form-group row">
											<div class="col-lg-6">
												<label>Description</label>
												<div class="kt-input-icon">
													<input type="text" class="form-control" placeholder="Product Description"  id="edit_product_description" name="edit_product_description">
												</div>
												<span class="form-text text-muted">Please enter description of the product..</span>
											</div>
											<div class="col-lg-3">
												<label>Alias</label>
												<div class="kt-input-icon">
													<input type="text"  class="form-control" placeholder="Product Alias" id="edit_product_alias" name="edit_product_alias">
												</div>
												<span class="form-text text-muted">Please enter alias name of the product..</span>
											</div>
											<div class="col-lg-3">
												<label>Reorder Point</label>
												<div class="kt-input-icon">
													<input type="text"  class="form-control" placeholder="Reorder Point" id="edit_product_moq" name="edit_product_moq">
												</div>
												<span class="form-text text-muted">Please enter the ROP..</span>
											</div>
										</div>
										<div class="form-group row">
											<div class="col-lg-3">
												<label class="">Product Category:</label>
												<div class="kt-input-icon">
													<select class="form-control kt-select2" id="edit_product_category" name="edit_product_category">
													</select>
												</div>
												<span class="form-text text-muted">Please select the appropriate category..</span>
											</div>
											<div class="col-lg-3">
												<label class="">Sub Category:</label>
												<div class="kt-input-icon">
													<select class="form-control kt-select2" id="edit_product_sub_category" name="edit_product_sub_category">
													</select>
												</div>
												<span class="form-text text-muted">Please select the appropriate sub category..</span>
											</div>
											<div class="col-lg-3">
												<label class="">Opening Stock</label>
												<div class="kt-input-icon">
													<input type="text" class="form-control" placeholder="Enter Opening STock"  id="edit_product_opening_stock" name="edit_product_opening_stock">
												</div>
												<span class="form-text text-muted">Please enter opening stock..</span>
											</div>
											<div class="col-lg-3">
												<label>Unit:</label>
												<div class="kt-input-icon">
													<select class="form-control kt-select2" id="edit_product_unit" name="edit_product_unit">
														<option></option>
														<option value='PCS'>PCS</option>
														<option value='NOS'>NOS</option>
														<option value='SETS'>SETS</option>
														<option value='MTR'>MTR</option>
													</select>
												</div>
												<span class="form-text text-muted">Please select the unit..</span>
											</div>
										</div>
										<div class="form-group row">
											<div class="col-lg-3">
												<label class="">Cost Price:</label>
												<div class="kt-input-icon">
													<input type="text" class="form-control" placeholder="Enter cost price"  id="edit_product_cost" name="edit_product_cost">
												</div>
												<span class="form-text text-muted">Please enter cost price..</span>
											</div>
											<div class="col-lg-3">
												<label class="">Sale Price:</label>
												<div class="kt-input-icon">
													<input type="text" class="form-control" placeholder="Enter sale price"  id="edit_product_rate" name="edit_product_rate">
												</div>
												<span class="form-text text-muted">Please enter sale price..</span>
											</div>
											<div class="col-lg-3">
												<label>Tax:</label>
												<div class="kt-input-icon">
													<select class="form-control kt-select2" id="edit_product_tax" name="edit_product_tax">
														<option></option>
														<option value='5'>5%</option>
														<option value='12'>12%</option>
														<option value='18'>18%</option>
														<option value='28'>28%</option>
													</select>
												</div>
												<span class="form-text text-muted">Please select tax category..</span>
											</div>
											<div class="col-lg-3">
												<label class="">HSN:</label>
												<div class="kt-input-icon">
													<input type="text" class="form-control" placeholder="Enter HSN code" id="edit_product_hsn" name="edit_product_hsn">
												</div>
												<span class="form-text text-muted">Please hsn code..</span>
											</div>
										</div>
										<div class="form-group row">
											<div class="col-lg-3">
												<label class="">PDF:</label>
												<div class="kt-input-icon">
													<input type="text" class="form-control" placeholder="Enter Product PDF" id="edit_product_pdf" name="edit_product_pdf">
												</div>
												<span class="form-text text-muted">Please enter Product PDF..</span>
											</div>
											<div class="col-lg-9">
												<label class="">Images:</label>
												<div class="kt-input-icon">
													<input type="text" class="form-control" placeholder="Enter Product Images" id="edit_product_images" name="edit_product_images">
												</div>
												<span class="form-text text-muted">Please enter Images..</span>
											</div>
										</div>
										<div class="form-group row">
											<div class="col-lg-4">
												<label>Updated Price</label>
												<div class="col-3">
													<span class="kt-switch">
														<label>
															<input type="checkbox" name="edit_product_update" id="edit_product_update">
															<span></span>
														</label>
													</span>
												</div>
											</div>
											<div class="col-lg-4">
												<label>Updated Cost Price</label>
												<div class="col-3">
													<span class="kt-switch">
														<label>
															<input type="checkbox" name="edit_cost_update" id="edit_cost_update">
															<span></span>
														</label>
													</span>
												</div>
											</div>
										</div>
									</div>
								

								<!--end::Form-->
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
								<button id="edit_product_submit" type="submit" class="btn btn-primary">Save</button>
							</div>
						</div>
					</div>
				</div>
			</form>
			<!--end::Edit Product Modal-->

			<!--begin::Delete Product Modal-->
			<div class="modal fade" id="kt_modal_d_product" tabindex="-1" role="dialog" aria-labelledby="deleteProductModal" aria-hidden="true">
				<div class="modal-dialog modal-xl" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="deleteClientModal" >Delete Product</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							</button>
						</div>
						<div class="modal-body">
							<!--begin::Form-->
							<form class="kt-form kt-form--label-right">
								<div class="kt-portlet__body">
									Are you sure you want to delete this product ?
								</div>
							</form>

							<!--end::Form-->
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							<button id="delete_product_submit" type="button" class="btn btn-primary">Delete</button>
						</div>
					</div>
				</div>
			</div>

			<!--end::Delete Product Modal-->
		</div>
	</div>

	<!--end::Portlet-->
</div>

<!-- end:: Content