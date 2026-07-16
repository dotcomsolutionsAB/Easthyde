<!-- begin:: Content --> 
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

	<!--Begin::Dashboard 1-->
	<div class="col-lg-12">
		<!--begin::Portlet-->
		<div class="kt-portlet" id="kt_portlet_add_q">
			<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title">
						Journal Entry
					</h3>
				</div>
				<div class="kt-portlet__head-toolbar">
					<div class="kt-portlet__head-group">
						<a href="#" data-ktportlet-tool="toggle" class="btn btn-sm btn-icon btn-clean btn-icon-md"><i class="la la-angle-down"></i></a>
					</div>
				</div>
			</div>
			<!--begin::Form-->
			<form class="kt-form" id="add_journal">
				<div class="kt-portlet__body">
					<div class="kt-section kt-section--first">
                        <input name="journal_edit_id" id="journal_edit_id" class="form-control" type="text" style="display:none;">
						<div class="form-group row">
							<div class="col-sm-3">
                                <div class="form-group">
                                    <label>Date</label>
                                    <input placeholder="Date" name="journal_date" id="journal_date" class="form-control date-picker" type="text" data-date-end-date="+3m" value="<?php echo date('d-m-Y', strtotime('today')); ?>">
                                </div>
                            </div>
						</div>
						
						<div class="form-group row" style="border-bottom: 1px solid #eee; padding-bottom:1rem;">
							<div class="col-md-1">
							</div>
							<div class="col-md-3">
								<div class="kt-form__label">
									<label>Master:</label>
								</div>
							</div>
							<div class="col-md-3">
								<div class="kt-form__label">
									<label>Particulars:</label>
								</div>
							</div>
							<div class="col-md-2">
								<div class="kt-form__label">
									<label>Debit:</label>
								</div>
							</div>
							<div class="col-md-2">
								<div class="kt-form__label">
									<label>Credit:</label>
								</div>
							</div>
						</div>
						<div class="form-group row">
							<div id="kt_repeater_journal" style="width:100%">
								<div id="kt_repeater_1">
									<div data-repeater-list="journal" id="journal_list" class="col-lg-12">
										<div data-repeater-item class="form-group row align-items-center" style="border-bottom: 1px dashed #5d78ff; padding-bottom:1rem; margin-bottom: 1rem;">
											<div class="col-md-1" style="margin-top:3px; text-align:center">
												<input type="text" class="form-control" name="journal_sn" value="1" style="border: none;font-weight: 900;text-align: center;" readonly="">
											</div>
											<input name="journal_type" class="form-control journal_type" type="text" style="display:none">
											<div class="col-md-3">
												<div class="kt-form__group--inline">
													<div class="kt-form__control">
														<select class="form-control kt-select2 journal_master-select2" name="journal_master">
                                                    	</select>
													</div>
												</div>
											</div>
											<div class="col-md-3">
												<div class="kt-form__group--inline">
													<div class="kt-form__control">
														<select class="form-control kt-select2 journal_particular-select2" name="journal_particular">
                                                    	</select>
													</div>
												</div>
											</div>
											<div class="col-md-2" style="margin-top:3px">
												<div class="input-group">
													<input placeholder="Amount" name="journal_debit" class="form-control journal_debit" type="text">
												</div>
											</div>
											<div class="col-md-2" style="margin-top:3px">
												<div class="input-group">
													<input placeholder="Amount" name="journal_credit" class="form-control journal_credit" type="text">
												</div>
											</div>
											<div class="col-md-1" style="margin-top:3px; text-align:center">
												<a href="javascript:;" data-repeater-delete="" class="btn-sm btn btn-label-danger btn-bold journal_delete">
													<i class="la la-trash-o"></i>
												</a>
											</div>
										</div>
									</div>
								</div>
								<div >
									<div class="col-lg-4">
										<a href="javascript:;" data-repeater-create="journal" id="journal_btn_add" class="btn btn-bold btn-sm btn-label-brand">
											<i class="la la-plus"></i> Add
										</a>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group row" style="margin-bottom: 2px;">
							<div class="col-md-7">
								
							</div>
							<div class="col-md-2">
								<div class="input-group">
						            <input type="text" class="form-control journal_debit_final" name="journal_debit_final" style="background-color: #eee; text-align:right;" readonly id="journal_debit_final">
								</div>
							</div>
							<div class="col-md-2">
								<div class="input-group">
						            <input type="text" class="form-control journal_credit_final" name="journal_credit_final" style="background-color: #eee; text-align:right;" readonly id="journal_credit_final">
								</div>
							</div>
						</div>
					</div>
		            <div class="kt-portlet__foot">
						<div class="kt-form__actions" style="float:right;">
							<button id="dcs_add_journal_submit" type="submit" class="btn btn-primary">Submit</button>
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
				<div class="kt-datatable" id="journal_datatable"></div>
				<!--end: Datatable -->
			</div>
		</div>

		<!--end::Portlet-->
	</div>
	<!--End::Dashboard 1-->
</div>
<!-- end:: Content -->

<!--begin::Delete Journal Modal-->
<div class="modal fade" id="kt_modal_d_journal" tabindex="-1" role="dialog" aria-labelledby="deleteJournalModal" aria-hidden="true">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="deleteJournalModal" >Delete Journal Entry</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				</button>
			</div>
			<div class="modal-body">
				<!--begin::Form-->
				<form class="kt-form kt-form--label-right">
					<div class="kt-portlet__body">
						Are you sure you want to delete this journal entry ?
					</div>
				</form>

				<!--end::Form-->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button id="delete_journal_submit" type="button" class="btn btn-primary">Delete</button>
			</div>
		</div>
	</div>
</div>
<!--end::Delete Journal Modal