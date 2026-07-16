<!-- begin:: Header Menu -->

<!-- Uncomment this to display the close button of the panel -->
<!-- <button class="kt-header-menu-wrapper-close" id="kt_header_menu_mobile_close_btn"><i class="la la-close"></i></button> -->

<div class="kt-header-menu-wrapper" id="kt_header_menu_wrapper">
	<div id="kt_header_menu" class="kt-header-menu kt-header-menu-mobile kt-header-menu--layout-default">
		<ul class="kt-menu__nav">

			<!-- Web View: Full Menu Display -->
			<li class="kt-menu__item kt-menu__item--submenu kt-menu__item--rel" data-ktmenu-submenu-toggle="click"
				aria-haspopup="true">
				<a data-toggle="modal" data-target="#kt_modal_product" class="kt-menu__link kt-menu__toggle">
					<span class="kt-menu__link-text">Products</span>
					<i class="kt-menu__ver-arrow la la-angle-right"></i>
				</a>
			</li>
			<li class="kt-menu__item kt-menu__item--submenu kt-menu__item--rel" data-ktmenu-submenu-toggle="click"
				aria-haspopup="true">
				<a data-toggle="modal" data-target="#kt_modal_client" class="kt-menu__link kt-menu__toggle">
					<span class="kt-menu__link-text">Client</span>
					<i class="kt-menu__ver-arrow la la-angle-right"></i>
				</a>
			</li>
			<li class="kt-menu__item kt-menu__item--submenu kt-menu__item--rel" data-ktmenu-submenu-toggle="click"
				aria-haspopup="true">
				<a data-toggle="modal" data-target="#kt_modal_supplier" class="kt-menu__link kt-menu__toggle">
					<span class="kt-menu__link-text">Supplier</span>
					<i class="kt-menu__ver-arrow la la-angle-right"></i>
				</a>
			</li>
			<li class="kt-menu__item kt-menu__item--rel" aria-haspopup="true">
				<a href="?page=sales" class="kt-menu__link">
					<span class="kt-menu__link-text">Sales Invoice</span>
					<i class="kt-menu__ver-arrow la la-angle-right"></i>
				</a>
			</li>
			<li class="kt-menu__item kt-menu__item--rel" aria-haspopup="true">
				<a href="?page=secondary_sales" class="kt-menu__link">
					<span class="kt-menu__link-text">Secondary Sales</span>
					<i class="kt-menu__ver-arrow la la-angle-right"></i>
				</a>
			</li>
			<li class="kt-menu__item kt-menu__item--submenu kt-menu__item--rel" data-ktmenu-submenu-toggle="click" aria-haspopup="true">
				<a data-toggle="modal" data-target="#kt_modal_product_history" class="kt-menu__link kt-menu__toggle">
					<span class="kt-menu__link-text">Product History</span>
					<i class="kt-menu__ver-arrow la la-angle-right"></i>
				</a>
			</li>
			<li class="kt-menu__item kt-menu__item--submenu kt-menu__item--rel" data-ktmenu-submenu-toggle="click" aria-haspopup="true">
				<a data-toggle="modal" data-target="#kt_modal_client_history" class="kt-menu__link kt-menu__toggle">
					<span class="kt-menu__link-text">Client History</span>
					<i class="kt-menu__ver-arrow la la-angle-right"></i>
				</a>
			</li>


			<!-- Plus Button for Mobile View -->

		</ul>
	</div>

	<!-- Custom CSS to style the button and remove the dropdown arrow -->
	<style>
		.kt-menu__link.kt-menu__toggle.dropdown-toggle::after {
			display: none;
			/* Removes the default dropdown arrow */
		}

		/* Hide full menu on mobile */
		@media (max-width: 991.98px) {
			.kt-header-menu .kt-menu__item:not(.d-lg-none) {
				display: none;
				/* Hide regular items on mobile */
			}
		}

		/* Ensure the plus button only shows on mobile */
		@media (min-width: 992px) {
			.d-lg-none {
				display: none;
			}
		}
	</style>
</div>

<!-- end:: Header Menu -->