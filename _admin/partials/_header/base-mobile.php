<!-- begin:: Header Mobile -->
<div id="kt_header_mobile" class="kt-header-mobile kt-header-mobile--fixed">
    <div class="kt-header-mobile__logo">
        <a href="index.php">
            <img alt="Logo" src="../assets/media/company-logos/Picture1.png" width="100px" />
        </a>
    </div>
    <div class="kt-header-mobile__toolbar" style="display: flex; align-items: center;">
        <!-- Mobile Menu Toggle Button -->
        <button class="kt-header-mobile__toggler kt-header-mobile__toggler--left" id="kt_aside_mobile_toggler">
            <span></span>
        </button>

        <!-- Circular Plus Button for Mobile -->
        <li class="kt-menu__item kt-menu__item--submenu kt-menu__item--rel dropdown d-lg-none" aria-haspopup="true" style="list-style-type: none; margin-left: 15px;">
            <a class="kt-menu__link kt-menu__toggle dropdown-toggle" data-toggle="dropdown" aria-expanded="false"
                style="display: flex; align-items: center; justify-content: center; width: 35px; height: 35px; border-radius: 50%; background-color: #007bff; color: white; border: 2px solid #0056b3;">
                <i class="la la-plus" style="font-size: 24px; line-height: 1;"></i>
            </a>

            <!-- Dropdown Menu for Mobile -->
            <div class="dropdown-menu">
                <a class="dropdown-item" data-toggle="modal" data-target="#kt_modal_product">
                    Products
                </a>
                <a class="dropdown-item" data-toggle="modal" data-target="#kt_modal_client">
                    Client
                </a>
                <a class="dropdown-item" data-toggle="modal" data-target="#kt_modal_supplier">
                    Supplier
                </a>
                <a class="dropdown-item" href="?page=sales">
                    Sales Invoice
                </a>
                <a class="dropdown-item"  href="?page=secondary_sales">
                    Secondary Sales
                </a>
                <a class="dropdown-item" data-toggle="modal" data-target="#kt_modal_product_history">
                    Product History
                </a>
                <a class="dropdown-item" data-toggle="modal" data-target="#kt_modal_Client_history">
                    Client History
                </a>
            </div>
        </li>

        <!-- Topbar Toggler for Mobile -->
        <button class="kt-header-mobile__topbar-toggler" id="kt_header_mobile_topbar_toggler" style="margin-left: 15px;">
            <i class="flaticon-more"></i>
        </button>
    </div>
</div>
<!-- end:: Header Mobile -->
