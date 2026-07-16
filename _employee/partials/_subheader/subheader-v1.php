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
					echo "Estimate/Approvals";
				else if($page == "secondary_sales_items")
					echo "Estimate/Approvals Items";
				else
					echo ucwords($page_1);

				?> </h3>
				<span class="kt-subheader__separator kt-subheader__separator--v"></span>
				<div class="kt-subheader__group kt-hidden" id="kt_subheader_group_actions">
					<div class="kt-subheader__desc"><span id="kt_subheader_group_selected_rows"></span> Selected:</div>
					<div class="btn-toolbar kt-margin-l-20">
						<div class="btn-toolbar kt-margin-l-20">
							<button class="btn btn-label-success btn-bold btn-sm btn-icon-h" id="kt_subheader_group_actions_product_excel">
								Download Excel
							</button>
						</div>
					</div>
				</div>

				<div class="kt-subheader__group kt-hidden" id="kt_subheader_group_actions_purchase_order">
					<div class="kt-subheader__desc"><span id="kt_subheader_group_selected_rows_PO"></span> Selected:</div>
					<div class="btn-toolbar kt-margin-l-20">
						<div class="btn-toolbar kt-margin-l-20">
							<button class="btn btn-label-success btn-bold btn-sm btn-icon-h" id="kt_subheader_group_actions_bag_po">
								Add to Purchase Order
							</button>
							<button class="btn btn-label-danger btn-bold btn-sm btn-icon-h" id="kt_subheader_group_actions_bag_delete">
								Delete
							</button>
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