<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

	<!--Begin::App-->
	<div class="kt-grid kt-grid--desktop kt-grid--ver kt-grid--ver-desktop kt-app">

		<!--Begin:: App Aside Mobile Toggle-->
		<button class="kt-app__aside-close" id="kt_user_profile_aside_close">
			<i class="la la-close"></i>
		</button>

		<!--End:: App Aside Mobile Toggle-->

		<!--Begin:: App Aside-->
		<div class="kt-grid__item kt-app__toggle kt-app__aside" id="kt_user_profile_aside" style="width: 400px;">

			<!--begin:: Widgets/Applications/User/Profile1-->
			<div class="kt-portlet kt-portlet--height-fluid-">
				<div class="kt-portlet__head  kt-portlet__head--noborder">
					<div class="kt-portlet__head-label">
						<h3 class="kt-portlet__head-title">
						</h3>
					</div>
				</div>
				<div class="kt-portlet__body kt-portlet__body--fit-y">

					<!--begin::Widget -->
					<div class="kt-widget kt-widget--user-profile-1">
						<div class="kt-widget__head">
							<div class="kt-widget__media">
								<?php 
								$src = '../assets/images/'.$_SESSION['username'].'.jpg';
								if(@getimagesize($src)){?>
									<img src="<?php echo $src; ?>" alt="image">
								<?php } else { ?>
									<span class="kt-badge kt-badge--info kt-badge--xl kt-badge--rounded" style="width: 90px; height:90px; font-size: 53px;">
										<?php 
											if($row_head['name'] != '' && $row_head['name'] != null)
												echo substr($row_head['name'],0,1);
											else
												echo 'D';
										?>
									</span>
								<?php } ?>
								</span>
							</div>
							<div class="kt-widget__content">
								<div class="kt-widget__section">
									<a href="#" class="kt-widget__username">
										<?php echo $row_head['name']; ?>
										<!-- <i class="flaticon2-correct kt-font-success"></i> -->
									</a>
									<span class="kt-widget__subtitle">
									Easthyde
									</span>
								</div>
							</div>
						</div>
						<div class="kt-widget__body">
							<div class="kt-widget__content">
								<div class="kt-widget__info">
									<span class="kt-widget__label">Email:</span>
									<a href="#" class="kt-widget__data"><?php echo $row_head['email']; ?></a>
								</div>
								<div class="kt-widget__info">
									<span class="kt-widget__label">Phone:</span>
									<a href="#" class="kt-widget__data"><?php echo $row_head['mobile']; ?></a>
								</div>
							</div>
							<div class="kt-widget__items">
								<a href="?page=profile" class="kt-widget__item">
									<span class="kt-widget__section">
										<span class="kt-widget__icon">
											<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
												<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
													<polygon points="0 0 24 0 24 24 0 24" />
													<path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
													<path d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z" fill="#000000" fill-rule="nonzero" />
												</g>
											</svg> </span>
										<span class="kt-widget__desc">
											Personal Information
										</span>
									</span>
								</a>
								<a href="?page=change_password" class="kt-widget__item kt-widget__item--active">
									<span class="kt-widget__section">
										<span class="kt-widget__icon">
											<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
												<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
													<rect x="0" y="0" width="24" height="24" />
													<path d="M4,4 L11.6314229,2.5691082 C11.8750185,2.52343403 12.1249815,2.52343403 12.3685771,2.5691082 L20,4 L20,13.2830094 C20,16.2173861 18.4883464,18.9447835 16,20.5 L12.5299989,22.6687507 C12.2057287,22.8714196 11.7942713,22.8714196 11.4700011,22.6687507 L8,20.5 C5.51165358,18.9447835 4,16.2173861 4,13.2830094 L4,4 Z" fill="#000000" opacity="0.3" />
													<path d="M12,11 C10.8954305,11 10,10.1045695 10,9 C10,7.8954305 10.8954305,7 12,7 C13.1045695,7 14,7.8954305 14,9 C14,10.1045695 13.1045695,11 12,11 Z" fill="#000000" opacity="0.3" />
													<path d="M7.00036205,16.4995035 C7.21569918,13.5165724 9.36772908,12 11.9907452,12 C14.6506758,12 16.8360465,13.4332455 16.9988413,16.5 C17.0053266,16.6221713 16.9988413,17 16.5815,17 C14.5228466,17 11.463736,17 7.4041679,17 C7.26484009,17 6.98863236,16.6619875 7.00036205,16.4995035 Z" fill="#000000" opacity="0.3" />
												</g>
											</svg> </span>
										<span class="kt-widget__desc">
											Change Password
										</span>
									</span>
								</a>
							</div>
						</div>
					</div>

					<!--end::Widget -->
				</div>
			</div>

			<!--end:: Widgets/Applications/User/Profile1-->
		</div>

		<!--End:: App Aside-->

		<!--Begin:: App Content-->
		<div class="kt-grid__item kt-grid__item--fluid kt-app__content">
			<div class="row">
				<div class="col-xl-12">
					<div class="kt-portlet kt-portlet--height-fluid">
						<div class="kt-portlet__head">
							<div class="kt-portlet__head-label">
								<h3 class="kt-portlet__head-title">Change Password<small>change or reset your account password</small></h3>
							</div>
							<div class="kt-portlet__head-toolbar kt-hidden">
								<div class="kt-portlet__head-toolbar">
									
								</div>
							</div>
						</div>
						<form class="kt-form kt-form--label-right" id="change_password">
							<div class="kt-portlet__body">
								<div class="kt-section kt-section--first">
									<div class="kt-section__body">
										<div class="alert alert-solid-danger alert-bold fade show kt-margin-t-20 kt-margin-b-40" role="alert">
											<div class="alert-icon"><i class="fa fa-exclamation-triangle"></i></div>
											<div class="alert-text">Kindly note down the new password before updating!</div>
											<div class="alert-close">
												<button type="button" class="close" data-dismiss="alert" aria-label="Close">
													<span aria-hidden="true"><i class="la la-close"></i></span>
												</button>
											</div>
										</div>
										<div class="form-group row">
											<label class="col-xl-3 col-lg-3 col-form-label">New Password</label>
											<div class="col-lg-9 col-xl-6">
												<input type="password" class="form-control" value="" placeholder="New password" id="new_pass" name="new_pass">
											</div>
										</div>
										<div class="form-group form-group-last row">
											<label class="col-xl-3 col-lg-3 col-form-label">Verify Password</label>
											<div class="col-lg-9 col-xl-6">
												<input type="password" class="form-control" value="" placeholder="Verify password" id="recheck_pass" name="recheck_pass">
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="kt-portlet__foot">
								<div class="kt-form__actions">
									<div class="row">
										<div class="col-lg-3 col-xl-3">
										</div>
										<div class="col-lg-9 col-xl-9">
											<button type="submit" id="edit_password_submit" class="btn btn-brand btn-bold">Change Password</button>&nbsp;
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>

		<!--End:: App Content-->
	</div>

	<!--End::App-->
</div>

<!-- end:: Content -->