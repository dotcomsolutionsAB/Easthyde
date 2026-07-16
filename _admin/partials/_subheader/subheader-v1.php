<?php
	$page = $_REQUEST['page'];
	$page_1 = str_replace('_',' ',$page);
?>

<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
	<div class="kt-container  kt-container--fluid ">
		<div class="kt-subheader__main">
			<h3 class="kt-subheader__title">
				<?php 
				if($page_1 == "index" || $page_1 =='')
					echo "Dashboard";
				else if($page == "secondary_sales")
					echo "Secondary Invoice";
				else if($page == "secondary_sales_items")
					echo "Estimate/Approvals Items";
				else if($page == "purchase")
					echo "Purchase Invoice";
				else
					echo ucwords($page_1);

				?> 
			</h3>
			<span class="kt-subheader__separator kt-subheader__separator--v"></span>

			<!-- Group actions for generic download -->
			<div class="kt-subheader__group kt-hidden" id="kt_subheader_group_actions">
				<div class="kt-subheader__desc">
					<span id="kt_subheader_group_selected_rows"></span> Selected:
				</div>
				<div class="btn-toolbar kt-margin-l-20">
					<div class="btn-toolbar kt-margin-l-20">
						<button class="btn btn-label-success btn-bold btn-sm btn-icon-h" id="kt_subheader_group_actions_product_excel">
							Download Excel
						</button>
						<button class="btn btn-label-success btn-bold btn-sm btn-icon-h" id="kt_subheader_group_actions_product_excel_all">
							Download  All Excel
						</button>
						<button class="btn btn-label-danger btn-bold btn-sm btn-icon-h" id="kt_subheader_group_actions_product_pdf">
							Download PDF
						</button>
					</div>
				</div>
			</div>

			<!-- Group actions for Purchase Order -->
			<div class="kt-subheader__group kt-hidden" id="kt_subheader_group_actions_purchase_order">
				<div class="kt-subheader__desc">
					<span id="kt_subheader_group_selected_rows_PO"></span> Selected:
				</div>
				<div class="btn-toolbar kt-margin-l-20">
					<div class="btn-toolbar kt-margin-l-20">
						<button class="btn btn-label-success btn-bold btn-sm btn-icon-h" id="kt_subheader_group_actions_bag_po">
							Add to Purchase Order
						</button>
					</div>
				</div>
			</div>

			<!-- Group actions for Payment Follow-Up -->
			<div class="kt-subheader__group kt-hidden" id="kt_subheader_group_actions_payment_followup">
				<div class="kt-subheader__desc">
					<span id="kt_subheader_group_selected_rows_payment_followup"></span> Selected:
				</div>
				<div class="btn-toolbar kt-margin-l-20">
					<div class="btn-toolbar kt-margin-l-20">
						<button class="btn btn-label-success btn-bold btn-sm btn-icon-h" id="kt_subheader_group_actions_payment_followup">
							Print
						</button>
					</div>
				</div>
			</div>

			<!-- Purchase Section - XML, Excel, PDF -->
			<div class="kt-subheader__group kt-hidden" id="kt_subheader_group_actions_purchase_xml">
				<div class="kt-subheader__desc">
					<span id="kt_subheader_group_selected_rows_purchase_xml"></span> Selected:
				</div>
				<div class="btn-toolbar kt-margin-l-20">
					<div class="btn-toolbar kt-margin-l-20">
						<!-- <button class="btn btn-label-danger btn-bold btn-sm btn-icon-h" id="kt_subheader_group_actions_purchase_xml_btn">
							Download XML Selected
						</button>
						<button class="btn btn-label-success btn-bold btn-sm btn-icon-h" id="kt_subheader_group_actions_purchase_all_xml">
							Download All XML
						</button> -->
						<button class="btn btn-label-success btn-bold btn-sm btn-icon-h" id="kt_subheader_group_actions_purchase_excel">
							Download Excel
						</button>
						<button class="btn btn-label-success btn-bold btn-sm btn-icon-h" id="kt_subheader_group_actions_purchase_all_excel">
							Download All Excel
						</button>

						<!-- <button class="btn btn-label-danger btn-bold btn-sm btn-icon-h" id="kt_subheader_group_actions_purchase_pdf">
							Download PDF
						</button> -->
					</div>
				</div>
			</div>

			<!-- Sales Section - XML, Excel, PDF -->
			<div class="kt-subheader__group kt-hidden" id="kt_subheader_group_actions_sales_xml">
				<div class="kt-subheader__desc">
					<span id="kt_subheader_group_selected_rows_sales_xml"></span> Selected:
				</div>
				<div class="btn-toolbar kt-margin-l-20">
					<div class="btn-toolbar kt-margin-l-20">
						<!-- <button class="btn btn-label-danger btn-bold btn-sm btn-icon-h" id="kt_subheader_group_actions_sales_xml_btn">
							Download XML Selected
						</button> -->
						<!-- <button class="btn btn-label-success btn-bold btn-sm btn-icon-h" id="kt_subheader_group_actions_sales_all_xml">
							Download All XML
						</button> -->
						<button class="btn btn-label-success btn-bold btn-sm btn-icon-h" id="kt_subheader_group_actions_sales_excel">
							Download Excel
						</button>
						<!-- <button class="btn btn-label-danger btn-bold btn-sm btn-icon-h" id="kt_subheader_group_actions_sales_pdf">
							Download PDF
						</button> -->
					</div>
				</div>
			</div>
			<!-- Sales Section - XML, Excel, PDF -->
			<div class="kt-subheader__group kt-hidden" id="kt_subheader_group_actions_secondary_sales_xml">
				<div class="kt-subheader__desc">
					<span id="kt_subheader_group_selected_rows_secondary_sales_xml"></span> Selected:
				</div>
				<div class="btn-toolbar kt-margin-l-20">
					<div class="btn-toolbar kt-margin-l-20">
						<!-- <button class="btn btn-label-danger btn-bold btn-sm btn-icon-h" id="kt_subheader_group_actions_sales_xml_btn">
							Download XML Selected
						</button> -->
						<!-- <button class="btn btn-label-success btn-bold btn-sm btn-icon-h" id="kt_subheader_group_actions_sales_all_xml">
							Download All XML
						</button> -->
						<button class="btn btn-label-success btn-bold btn-sm btn-icon-h" id="kt_subheader_group_actions_secondary_sales_excel">
							Download Excel
						</button>
						<!-- <button class="btn btn-label-danger btn-bold btn-sm btn-icon-h" id="kt_subheader_group_actions_sales_pdf">
							Download PDF
						</button> -->
					</div>
				</div>
			</div>

			<!-- Credit Note Section - XML, Excel, PDF -->
			<div class="kt-subheader__group kt-hidden" id="kt_subheader_group_actions_credit_note_xml">
				<div class="kt-subheader__desc">
					<span id="kt_subheader_group_selected_rows_credit_note_xml"></span> Selected:
				</div>
				<div class="btn-toolbar kt-margin-l-20">
					<div class="btn-toolbar kt-margin-l-20">
						<button class="btn btn-label-danger btn-bold btn-sm btn-icon-h" id="kt_subheader_group_actions_credit_note_xml_btn">
							Download XML Selected
						</button>
						<!-- <button class="btn btn-label-success btn-bold btn-sm btn-icon-h" id="kt_subheader_group_actions_credit_note_all_xml">
							Download All XML
						</button> -->
						<button class="btn btn-label-success btn-bold btn-sm btn-icon-h" id="kt_subheader_group_actions_credit_note_excel">
							Download Excel
						</button>
						<button class="btn btn-label-danger btn-bold btn-sm btn-icon-h" id="kt_subheader_group_actions_credit_note_pdf">
							Download PDF
						</button>
					</div>
				</div>
			</div>

			<!-- Debit Note Section - XML, Excel, PDF -->
			<div class="kt-subheader__group kt-hidden" id="kt_subheader_group_actions_debit_note_xml">
				<div class="kt-subheader__desc">
					<span id="kt_subheader_group_selected_rows_debit_note_xml"></span> Selected:
				</div>
				<div class="btn-toolbar kt-margin-l-20">
					<div class="btn-toolbar kt-margin-l-20">
						<!-- <button class="btn btn-label-danger btn-bold btn-sm btn-icon-h" id="kt_subheader_group_actions_debit_note_xml_btn">
							Download XML Selected
						</button> -->
						<!-- <button class="btn btn-label-success btn-bold btn-sm btn-icon-h" id="kt_subheader_group_actions_debit_note_all_xml">
							Download All XML
						</button> -->
						<button class="btn btn-label-success btn-bold btn-sm btn-icon-h" id="kt_subheader_group_actions_debit_note_excel">
							Download Excel
						</button>
						<!-- <button class="btn btn-label-danger btn-bold btn-sm btn-icon-h" id="kt_subheader_group_actions_debit_note_pdf">
							Download PDF
						</button> -->
					</div>
				</div>
			</div>

			<!-- Receipt Section - XML, Excel, PDF -->
			<div class="kt-subheader__group kt-hidden" id="kt_subheader_group_actions_receipt_xml">
				<div class="kt-subheader__desc">
					<span id="kt_subheader_group_selected_rows_receipt_xml"></span> Selected:
				</div>
				<div class="btn-toolbar kt-margin-l-20">
					<div class="btn-toolbar kt-margin-l-20">
						<!-- <button class="btn btn-label-danger btn-bold btn-sm btn-icon-h" id="kt_subheader_group_actions_receipt_xml_btn">
							Download XML Selected
						</button> -->
						<!-- <button class="btn btn-label-success btn-bold btn-sm btn-icon-h" id="kt_subheader_group_actions_receipt_all_xml">
							Download All XML
						</button> -->
						<button class="btn btn-label-success btn-bold btn-sm btn-icon-h" id="kt_subheader_group_actions_receipt_excel">
							Download Excel
						</button>
						<!-- <button class="btn btn-label-danger btn-bold btn-sm btn-icon-h" id="kt_subheader_group_actions_receipt_pdf">
							Download PDF
						</button> -->
					</div>
				</div>
			</div>

			<!-- Payment Section - XML, Excel, PDF -->
			<div class="kt-subheader__group kt-hidden" id="kt_subheader_group_actions_payment_xml">
				<div class="kt-subheader__desc">
					<span id="kt_subheader_group_selected_rows_payment_xml"></span> Selected:
				</div>
				<div class="btn-toolbar kt-margin-l-20">
					<div class="btn-toolbar kt-margin-l-20">
						<!-- <button class="btn btn-label-danger btn-bold btn-sm btn-icon-h" id="kt_subheader_group_actions_payment_xml_btn">
							Download XML Selected
						</button> -->
						<!-- <button class="btn btn-label-success btn-bold btn-sm btn-icon-h" id="kt_subheader_group_actions_payment_all_xml">
							Download All XML
						</button> -->
						<button class="btn btn-label-success btn-bold btn-sm btn-icon-h" id="kt_subheader_group_actions_payment_excel">
							Download Excel
						</button>
						<!-- <button class="btn btn-label-danger btn-bold btn-sm btn-icon-h" id="kt_subheader_group_actions_payment_pdf">
							Download PDF
						</button> -->
					</div>
				</div>
			</div>

		</div>
		<div class="kt-subheader__toolbar">
			<div class="kt-subheader__wrapper">
				<a href="#" class="btn kt-subheader__btn-daterange" id="kt_dashboard_daterangepicker" data-toggle="kt-tooltip" title="" data-placement="left" data-original-title="Select dashboard daterange">
                    <span class="kt-subheader__btn-daterange-title" id="kt_dashboard_daterangepicker_title">Today:</span>&nbsp;
                    <span class="kt-subheader__btn-daterange-date" id="kt_dashboard_daterangepicker_date">feb. 12</span>
                    <i class="flaticon2-calendar-1"></i>
                </a>
			</div>
		</div>
	</div>
</div>

<!-- end:: Subheader -->
