
<!-- begin:: Header Menu -->

<!-- Uncomment this to display the close button of the panel
<button class="kt-header-menu-wrapper-close" id="kt_header_menu_mobile_close_btn"><i class="la la-close"></i></button>
-->
<div class="kt-header-menu-wrapper" id="kt_header_menu_wrapper">
	<div id="kt_header_menu" class="kt-header-menu kt-header-menu-mobile  kt-header-menu--layout-default ">
		<ul class="kt-menu__nav ">
			<?php

        $username = $_SESSION['username'];
        $userlevel = $_SESSION['userlevel'];

        $sql_access = "SELECT * FROM users WHERE `username` = '$username'";
        $query_access = $db->query($sql_access);
        $row_access = $query_access->fetch_assoc();

        $menu_access = json_decode($row_access['access'], true);

        if($menu_access['products']['create'] == '1' || $userlevel == "sadmin_df56fdg"){

            
    ?>
			<li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel" data-ktmenu-submenu-toggle="click" aria-haspopup="true"><a data-toggle="modal" data-target="#kt_modal_product"  class="kt-menu__link kt-menu__toggle"><span class="kt-menu__link-text">Products</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
			</li>
			
			<?php
        }
        if($menu_access['clients']['create'] == '1' || $userlevel == "sadmin_df56fdg"){
        ?>
			<li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel" data-ktmenu-submenu-toggle="click" aria-haspopup="true"><a data-toggle="modal" data-target="#kt_modal_client" class="kt-menu__link kt-menu__toggle"><span class="kt-menu__link-text">Client</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
			</li>
			<?php
        }
        if($menu_access['suppliers']['create'] == '1' || $userlevel == "sadmin_df56fdg"){
        ?>
			<li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel" data-ktmenu-submenu-toggle="click" aria-haspopup="true"><a data-toggle="modal" data-target="#kt_modal_supplier" class="kt-menu__link kt-menu__toggle"><span class="kt-menu__link-text">Supplier</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
			</li>
			<?php
        }
        
        ?>
			<li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel" data-ktmenu-submenu-toggle="click" aria-haspopup="true"><a data-toggle="modal" data-target="#kt_modal_calculator" class="kt-menu__link kt-menu__toggle"><span class="kt-menu__link-text">Calculator</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
			</li>
		</ul>
	</div>
</div>

<!-- end:: Header Menu -->