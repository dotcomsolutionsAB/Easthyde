<?php

	$so_no = $_REQUEST['so_no'];

?>

<!-- begin:: Content -->
<div class="kt-container kt-container--fluid  kt-grid__item kt-grid__item--fluid">
	<div class="row">
		<div class="col-lg-6">
			<!--Begin::Portlet-->
			<div class="kt-portlet">
				
				<div class="kt-portlet__body">
					<div class="kt-notes">
						<div class="kt-notes__items">
							<?php 
								$sql_notes = "SELECT * FROM sales_order WHERE so_no = '$so_no'";
								$query_notes = $db->query($sql_notes);
								$row_notes = $query_notes->fetch_assoc();

								$notes_arr = json_decode($row_notes['notes'], true);
								$len = sizeof($notes_arr['notes']);

								for($i=$len-1;$i>=0;$i--){
							?>
							<div class="kt-notes__item">
								<div class="kt-notes__media">
									<span class="kt-notes__icon">
										<i class="flaticon2-shield kt-font-brand"></i>
									</span>
								</div>
								<div class="kt-notes__content">
									<div class="kt-notes__section">
										<div class="kt-notes__info">
											<a href="#" class="kt-notes__title">
												<?php echo $notes_arr['user'][$i]; ?>
											</a>
											<span class="kt-notes__desc">
												<?php echo date('h:m a d F, Y',strtotime($notes_arr['timestamp'][$i])); ?>
											</span>
										</div>
										<div class="kt-notes__dropdown">
											<a href="javascript:;" data-toggle="modal" data-target="#kt_modal_d_so_note" title="Delete" onclick="<?php echo 'removeNoteSalesOrder(\''.$row_notes['so_no'].'\',\''.$i.'\')'; ?>" class="btn btn-sm btn-icon-md btn-icon" data-toggle="dropdown">
												<i class="flaticon-more-1 kt-font-brand"></i>
											</a>
											<div class="dropdown-menu dropdown-menu-right">
												<ul class="kt-nav">
													<li class="kt-nav__item">
														<a href="#" class="kt-nav__link">
															<i class="kt-nav__link-icon flaticon2-trash"></i>
															<span class="kt-nav__link-text">Delete</span>
														</a>
													</li>
												</ul>
											</div>
										</div>
									</div>
									<span class="kt-notes__body">
										<?php echo $notes_arr['notes'][$i]; ?>
									</span>
								</div>
							</div>
							<?php }?>
						</div>
					</div>
				</div>
			</div>

			<!--End::Portlet-->
		</div>
	</div>
</div>
<!-- end:: Content -->

<!--begin::Delete Note Modal-->
<div class="modal fade" id="kt_modal_d_so_note" tabindex="-1" role="dialog" aria-labelledby="deleteQnoteModal" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="deleteQnoteModal" >Delete Note</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				</button>
			</div>
			<div class="modal-body">
				<!--begin::Form-->
				<form class="kt-form kt-form--label-right">
					<div class="kt-portlet__body">
						Are you sure you want to delete this note ?
					</div>
				</form>

				<!--end::Form-->
			</div>
			<div class="modal-footer">
				<button id="delete_sales_order_note_submit" type="button" class="btn btn-primary">Delete</button>
			</div>
		</div>
	</div>
</div>
<!--end::Delete Note Modal-->