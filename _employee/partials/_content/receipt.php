<!-- begin:: Content --> 
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

	<!--Begin::Dashboard 1-->
	<div class="col-lg-12">
	    <?php

        $username = $_SESSION['username'];
        $userlevel = $_SESSION['userlevel'];

        $sql_access = "SELECT * FROM users WHERE `username` = '$username'";
        $query_access = $db->query($sql_access);
        $row_access = $query_access->fetch_assoc();

        $menu_access = json_decode($row_access['access'], true);

        if($menu_access['receipt']['create'] == '1' || $userlevel == "sadmin_df56fdg"){

            
    ?>
		<!--begin::Portlet-->
		<div class="kt-portlet" id="kt_portlet_add_py">
			<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title">
						Receipt
					</h3>
				</div>
				<div class="kt-portlet__head-toolbar">
					<div class="kt-portlet__head-group">
						<a href="#" data-ktportlet-tool="toggle" class="btn btn-sm btn-icon btn-clean btn-icon-md"><i class="la la-angle-down"></i></a>
					</div>
				</div>
			</div>
			<!--begin::Form-->
			<form class="kt-form" id="add_receipt">
				<div class="kt-portlet__body">
					<div class="kt-section kt-section--first">
						<div class="form-group row">
                            <input name="rc_id" id="rc_id" class="form-control" type="text" style="display:none;">
                            <input name="r_no" id="r_no" class="form-control" type="text" style="display:none;">

							<div class="col-sm-3">
                                <div class="form-group">
                                    <label>Client</label>
                                    <select class="form-control kt-select2 client-select2" name="rc_client" id="rc_client">
                                    	<option></option>
                                    </select>
                                    <span class="form-text text-muted">Please enter name of the client..</span>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Date</label>
                                    <input placeholder="Receipt Date" name="rc_date" id="rc_date" class="form-control date-picker" type="text" data-date-end-date="+3m" value="<?php echo date('d-m-Y', strtotime('today')); ?>">
                                    <span class="form-text text-muted">Please enter the receipt date..</span>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Credit To</label>
                                    <select name="rc_bank" id="rc_bank" class="form-control kt-select2 rc_bank-select2"> 
                                    </select>
                                    <span class="form-text text-muted">Please select the account to credit..</span>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Amount</label>
                                    <input placeholder="Amount" name="amount" id="amount" class="form-control" type="text">
                                    <span class="form-text text-muted">Please enter the total amount..</span>
                                </div>
                            </div>
                        </div>
                        <h5 class="kt-portlet__head-title" id="invoice_details_title" style="display: none;">
							Invoice Details
						</h5>
						<div class="form-group row" id="invoice_details" style="display: none;">
							<div id="kt_repeater_rc" style="width: 100%;">
								<div class="form-group form-group-last row" id="kt_repeater_1">
									<div data-repeater-list="receipt" id="receipt_list" class="col-lg-12">
										<div data-repeater-item class="form-group row align-items-center" style="border-bottom: 1px dashed #5d78ff; margin-bottom:0;">
											<input type="text" class="form-control" name="rc_invoice_id" style="display: none;" >
											<div class="col-md-1">
												<div class="kt-form__control">
													<input name="rc_details_sn" class="form-control" type="text" style="border: none;" readonly="">
												</div>
											</div>
											<div class="col-md-3">
												<div class="kt-form__control">
													<input name="rc_details_si" class="form-control" type="text" style="border: none;" readonly="">
												</div>
											</div>
											<div class="col-md-2">
												<div class="kt-form__control">
													<input name="rc_details_date" class="form-control" type="text" style="border: none;" readonly="">
												</div>
											</div>
											<div class="col-md-2">
												<div class="kt-form__control">
													<input name="rc_details_amount" class="form-control" type="text" style="border: none; text-align: right;" readonly="">
												</div>
											</div>
											<div class="col-md-2">
												<div class="kt-form__control">
													<input name="rc_amount" class="form-control rc_amount" type="text" style="text-align: right;"> 
												</div>
											</div>
											<input name="rc_due" class="form-control" type="text" style="text-align: right; display:none;"> 
											<div class="col-md-2" style="margin-top: 2em;">
												<label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
													<input type="checkbox" class="rc_completed" name="rc_completed">Completed
													<span></span>
												</label>
											</div>
										</div>
									</div>
								</div>
								<div class="row form-group">
									<div class="col-md-8"  style="margin-top: 2em;"> 
										<a id="rc_btn_advance" class="btn btn-bold btn-sm btn-label-brand">
											<i class="la la-plus"></i> Advance Amount
										</a>
									</div>
									<div class="col-md-2"  style="margin-top: 2em;"> 
										<input name="rc_advance_amount" id="rc_advance_amount" placeholder="Advance" class="form-control" type="text" style="display:none;">
									</div>
								</div>
								<div >
									<div class="col-lg-4" style="display: none;">
										<a href="javascript:;" data-repeater-create="receipt" id="rc_btn_add" class="btn btn-bold btn-sm btn-label-brand">
											<i class="la la-plus"></i> Add
										</a>
									</div>
								</div>
							</div>
						</div>
						<h5 class="kt-portlet__head-title" id="bank_details_title" style="display: none;">
							Bank Details
						</h5>
                        <div class="form-group row" id="bank_details" style="margin-top: 2em; display: none;">
                        	<div class="col-sm-3">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input name="rc_bank_name" id="rc_bank_name" class="form-control" type="text">
                                    <span class="form-text text-muted">Please enter the bank details details..</span>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Mode</label>
                                    <select name="rc_mode" id="rc_mode" class="form-control kt-select2 rc_mode-select2">
                                    	<option value="Cheque/DD">Cheque/DD</option> 
                                    	<option value="e-Fund Transfer">e-Fund Transfer</option>  
                                    </select>
                                    <span class="form-text text-muted">Please select the mode of reciept..</span>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Instrument No</label>
                                    <input name="rc_instrument" id="rc_instrument" class="form-control" type="text">
                                    <span class="form-text text-muted">Please enter the cheque details..</span>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Instrument Date</label>
                                    <input name="rc_ins_date" id="rc_ins_date" class="form-control date-picker" type="text" value= "<?php echo date('d-m-Y', strtotime('today')); ?>">
                                    <span class="form-text text-muted">Please enter the date..</span>
                                </div>
                            </div>
                        </div>
                        
					</div>
		            <div class="kt-portlet__foot">
						<div class="kt-form__actions" style="float:right;">
							<button id="dcs_add_receipt_submit" type="submit" class="btn btn-primary">Submit</button>
						</div>
					</div>
				</div>
			</form>
			<!--end::Form-->
		</div>
		<!--end::Portlet-->
		
		<?php
        }
        ?>

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
				<div class="kt-datatable" id="receipts_datatable"></div>
				<!--end: Datatable -->
				
			</div>
		</div>

		<!--end::Portlet-->
	</div>
	<!--End::Dashboard 1-->
</div>
<!-- end:: Content -->

<!--begin::Delete Receipt Modal-->
<div class="modal fade" id="kt_modal_d_receipt" tabindex="-1" role="dialog" aria-labelledby="deleteReceiptModal" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="deleteReceiptModal" >Delete Receipt</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				</button>
			</div>
			<div class="modal-body">
				<!--begin::Form-->
				<form class="kt-form kt-form--label-right">
					<div class="kt-portlet__body">
						Are you sure you want to delete this receipt ?
					</div>
				</form>

				<!--end::Form-->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button id="delete_receipt_submit" type="button" class="btn btn-primary">Delete</button>
			</div>
		</div>
	</div>
</div>
<!--end::Delete Receipt Modal