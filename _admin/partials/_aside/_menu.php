<!-- begin:: Aside Menu -->

<?php

$sql = "SELECT * FROM extra";
$query = $db->query($sql);
$row = $query->fetch_assoc();

$extra_toggle = $row['estimate_toggle'];

?>
<style>
    .submenu-arrow {
        font-size: 20px;
        /* Increase the size of the arrow */
        color: #ffffff;
        /* Arrow color */
        margin-left: auto;
        /* Align arrow to the right */
        font-weight: bold;
        /* Make the arrow bold */
        transition: transform 0.3s ease;
        /* Smooth transition for rotation */
    }

    .kt-menu__item--submenu:hover .submenu-arrow {
        transform: rotate(90deg);
        /* Rotate arrow when hovering over the submenu */
    }
</style>
<div class="kt-aside-menu-wrapper kt-grid__item kt-grid__item--fluid" id="kt_aside_menu_wrapper">
    <div id="kt_aside_menu" class="kt-aside-menu" data-ktmenu-vertical="1" data-ktmenu-scroll="1"
        data-ktmenu-dropdown-timeout="500">
        <ul class="kt-menu__nav">

            <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true">
                <a href="?page=quotation" class="kt-menu__link kt-menu__toggle">
                    <span class="kt-menu__link-icon">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </span>
                    <span class="kt-menu__link-text"
                        style="color: #ffffff; font-weight: semi-bold; font-size: 20px;">Quotations</span>
                </a>
            </li>

            <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true">
                <a href="?page=sales" class="kt-menu__link kt-menu__toggle">
                    <span class="kt-menu__link-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </span>
                    <span class="kt-menu__link-text"
                        style="color: #ffffff; font-weight: semi-bold; font-size: 20px;">Sales</span>
                    <i class="submenu-arrow fas fa-angle-right"></i>
                </a>
                <div class="kt-menu__submenu">
                    <ul class="kt-menu__subnav">
                        <li class="kt-menu__item kt-menu__item--parent" aria-haspopup="true">
                            <span class="kt-menu__link">
                                <span class="kt-menu__link-text"
                                    style="color: #ffffff; font-weight: semi-bold; font-size: 18px;">Sales</span>
                            </span>
                        </li>
                        <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true">
                            <a href="?page=sales_order" class="kt-menu__link kt-menu__toggle">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text"
                                    style="color: #ffffff; font-weight: semi-bold; font-size: 18px;">Sales Order</span>
                            </a>
                        </li>
                        <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true">
                            <a href="?page=proforma_invoice" class="kt-menu__link kt-menu__toggle">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text"
                                    style="color: #ffffff; font-weight: semi-bold; font-size: 18px;">Proforma
                                    Invoice</span>
                            </a>
                        </li>
                        <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true">
                            <a href="?page=sales" class="kt-menu__link kt-menu__toggle">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text"
                                    style="color: #ffffff; font-weight: semi-bold; font-size: 18px;">Sales
                                    Invoice</span>
                            </a>
                        </li>
                        <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true">
                            <a href="?page=receipt" class="kt-menu__link kt-menu__toggle">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text"
                                    style="color: #ffffff; font-weight: semi-bold; font-size: 18px;">Receipt</span>
                            </a>
                        </li>
                        <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true">
                            <a href="?page=credit_note" class="kt-menu__link kt-menu__toggle">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text"
                                    style="color: #ffffff; font-weight: semi-bold; font-size: 18px;">Credit Note</span>
                            </a>
                        </li>

                    </ul>
                </div>
            </li>

            <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true">
                <a href="?page=purchase" class="kt-menu__link kt-menu__toggle">
                    <span class="kt-menu__link-icon">
                        <i class="fas fa-shopping-bag"></i>
                    </span>
                    <span class="kt-menu__link-text"
                        style="color: #ffffff; font-weight: semi-bold; font-size: 20px;">Purchase</span>
                    <i class="submenu-arrow fas fa-angle-right"></i>
                </a>
                <div class="kt-menu__submenu">
                    <ul class="kt-menu__subnav">
                        <li class="kt-menu__item kt-menu__item--parent" aria-haspopup="true">
                            <span class="kt-menu__link">
                                <span class="kt-menu__link-text"
                                    style="color: #ffffff; font-weight: semi-bold; font-size: 18px;">Purchase</span>
                            </span>
                        </li>
                        <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true">
                            <a href="?page=purchase_quotation" class="kt-menu__link kt-menu__toggle">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text"
                                    style="color: #ffffff; font-weight: semi-bold; font-size: 18px;">Purchase
                                    Quotation</span>
                            </a>
                        </li>
                        <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true">
                            <a href="?page=purchase_order" class="kt-menu__link kt-menu__toggle">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text"
                                    style="color: #ffffff; font-weight: semi-bold; font-size: 18px;">Purchase
                                    Order</span>
                            </a>
                        </li>
                        <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true">
                            <a href="?page=purchase" class="kt-menu__link kt-menu__toggle">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text"
                                    style="color: #ffffff; font-weight: semi-bold; font-size: 18px;">Purchase
                                    Invoice</span>
                            </a>
                        </li>
                        <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true">
                            <a href="?page=payments" class="kt-menu__link kt-menu__toggle">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text"
                                    style="color: #ffffff; font-weight: semi-bold; font-size: 18px;">Payments</span>
                            </a>
                        </li>
                        <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true">
                            <a href="?page=debit_note" class="kt-menu__link kt-menu__toggle">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text"
                                    style="color: #ffffff; font-weight: semi-bold; font-size: 18px;">Debit Note</span>
                            </a>
                        </li>

                    </ul>
                </div>
            </li>

            <!-- Journal menu - Commented out -->
            <!-- <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true">
                <a href="?page=journal" class="kt-menu__link kt-menu__toggle">
                    <span class="kt-menu__link-icon">
                        <i class="fas fa-book"></i>
                    </span>
                    <span class="kt-menu__link-text" style="color: #ffffff; font-weight: semi-bold; font-size: 20px;">Journal</span>
                </a>
            </li> -->

            <!-- <li class="kt-menu__section">
                <h4 class="kt-menu__section-text">Management</h4>
                <i class="kt-menu__section-icon"></i>
            </li> -->

            <!-- Banks menu - Commented out -->
            <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true">
                <a href="?page=banks" class="kt-menu__link kt-menu__toggle">
                    <span class="kt-menu__link-icon">
                        <i class="fas fa-university"></i>
                    </span>
                    <span class="kt-menu__link-text"
                        style="color: #ffffff; font-weight: semi-bold; font-size: 20px;">Banks</span>
                </a>
            </li>
            <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true">
                <a href="?page=bank_ledger" class="kt-menu__link kt-menu__toggle">
                    <span class="kt-menu__link-icon">
                        <i class="fas fa-book"></i>
                    </span>
                    <span class="kt-menu__link-text"
                        style="color: #ffffff; font-weight: semi-bold; font-size: 20px;">Bank Ledger</span>
                </a>
            </li>
            <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true">
                <a href="?page=expense" class="kt-menu__link kt-menu__toggle">
                    <span class="kt-menu__link-icon">
                        <i class="fas fa-credit-card"></i>
                    </span>
                    <span class="kt-menu__link-text"
                        style="color: #ffffff; font-weight: semi-bold; font-size: 20px;">Expense</span>
                </a>
            </li>

            <!-- Contra Entry menu - Commented out -->
            <!-- <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true">
                <a href="?page=contra_entry" class="kt-menu__link kt-menu__toggle">
                    <span class="kt-menu__link-icon">
                        <i class="fas fa-exchange-alt"></i>
                    </span>
                    <span class="kt-menu__link-text" style="color: #ffffff; font-weight: semi-bold; font-size: 20px;">Contra Entry</span>
                </a>
            </li> -->

            <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true">
                <a href="?page=product" class="kt-menu__link kt-menu__toggle">
                    <span class="kt-menu__link-icon">
                        <i class="fas fa-box"></i>
                    </span>
                    <span class="kt-menu__link-text"
                        style="color: #ffffff; font-weight: semi-bold; font-size: 20px;">Products</span>
                    <i class="submenu-arrow fas fa-angle-right"></i>
                </a>
                <div class="kt-menu__submenu">
                    <ul class="kt-menu__subnav">
                        <li class="kt-menu__item kt-menu__item--parent" aria-haspopup="true">
                            <span class="kt-menu__link">
                                <span class="kt-menu__link-text"
                                    style="color: #ffffff; font-weight: semi-bold; font-size: 18px;">Product</span>
                            </span>
                        </li>
                        <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true">
                            <a href="?page=product" class="kt-menu__link kt-menu__toggle">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text"
                                    style="color: #ffffff; font-weight: semi-bold; font-size: 18px;">Product</span>
                            </a>
                        </li>
                        <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true">
                            <a href="?page=ROP" class="kt-menu__link kt-menu__toggle">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text"
                                    style="color: #ffffff; font-weight: semi-bold; font-size: 18px;">ROP</span>
                            </a>
                        </li>
                        <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true">
                            <a href="?page=assemblies" class="kt-menu__link kt-menu__toggle">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text"
                                    style="color: #ffffff; font-weight: semi-bold; font-size: 18px;">Assembly</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true">
                <a href="?page=clients" class="kt-menu__link kt-menu__toggle">
                    <span class="kt-menu__link-icon">
                        <i class="fas fa-users"></i>
                    </span>
                    <span class="kt-menu__link-text"
                        style="color: #ffffff; font-weight: semi-bold; font-size: 20px;">Clients</span>
                </a>
            </li>

            <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true">
                <a href="?page=suppliers" class="kt-menu__link kt-menu__toggle">
                    <span class="kt-menu__link-icon">
                        <i class="fas fa-truck"></i>
                    </span>
                    <span class="kt-menu__link-text"
                        style="color: #ffffff; font-weight: semi-bold; font-size: 20px;">Suppliers</span>
                </a>
            </li>

            <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true">
                <a href="?page=materials_received" class="kt-menu__link kt-menu__toggle">
                    <span class="kt-menu__link-icon">
                        <i class="fas fa-clipboard-check"></i>
                    </span>
                    <span class="kt-menu__link-text"
                        style="color: #ffffff; font-weight: semi-bold; font-size: 20px;">Material Received</span>
                    <i class="submenu-arrow fas fa-angle-right"></i>
                </a>
                <div class="kt-menu__submenu">
                    <ul class="kt-menu__subnav">
                        <li class="kt-menu__item kt-menu__item--parent" aria-haspopup="true">
                            <span class="kt-menu__link">
                                <span class="kt-menu__link-text"
                                    style="color: #ffffff; font-weight: semi-bold; font-size: 18px;">Material Received</span>
                            </span>
                        </li>
                        <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true">
                            <a href="?page=materials_received" class="kt-menu__link kt-menu__toggle">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text"
                                    style="color: #ffffff; font-weight: semi-bold; font-size: 18px;">MRN / MRTN Entries</span>
                            </a>
                        </li>
                        <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true">
                            <a href="?page=suppliers" class="kt-menu__link kt-menu__toggle">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text"
                                    style="color: #ffffff; font-weight: semi-bold; font-size: 18px;">Seller Dashboard Links</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true">
                <a href="?page=users" class="kt-menu__link kt-menu__toggle">
                    <span class="kt-menu__link-icon">
                        <i class="fas fa-user-cog"></i>
                    </span>
                    <span class="kt-menu__link-text"
                        style="color: #ffffff; font-weight: semi-bold; font-size: 20px;">Users</span>
                </a>
            </li>
            <?php
            if ($extra_toggle == 1 || true) {
                ?>
                <!-- <li class="kt-menu__section">
                <h4 class="kt-menu__section-text">Extras</h4>
                <i class="kt-menu__section-icon fas fa-plus"></i>
            </li> -->
                <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true">
                    <a href="?page=secondary_sales" class="kt-menu__link kt-menu__toggle">
                        <span class="kt-menu__link-icon">
                            <i class="fas fa-chart-line"></i>
                        </span>
                        <span class="kt-menu__link-text"
                            style="color: #ffffff; font-weight: semi-bold; font-size: 20px;">Secondary Sales</span>
                    </a>
                </li>
                <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true">
                    <a href="?page=secondary_purchase" class="kt-menu__link kt-menu__toggle">
                        <span class="kt-menu__link-icon">
                            <i class="fas fa-cart-arrow-down"></i>
                        </span>
                        <span class="kt-menu__link-text"
                            style="color: #ffffff; font-weight: semi-bold; font-size: 18px;">Secondary Purchase</span>
                    </a>
                </li>
                
                <!-- Secondary Sales Items menu - Commented out -->
                <!-- <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true">
                <a href="?page=secondary_sales_items" class="kt-menu__link kt-menu__toggle">
                    <span class="kt-menu__link-icon">
                        <i class="fas fa-star"></i>
                    </span>
                    <span class="kt-menu__link-text" style="color: #ffffff; font-weight: semi-bold; font-size: 20px;">Estimate/Approvals Items</span>
                </a>
            </li> -->

            <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true">
                <a href="?page=khumus" class="kt-menu__link kt-menu__toggle">
                    <span class="kt-menu__link-icon">
                        <i class="fas fa-money-bill-alt"></i>
                    </span>
                    <span class="kt-menu__link-text"
                        style="color: #ffffff; font-weight: semi-bold; font-size: 20px;">Khumus</span>
                </a>
            </li>
                <?php
            }
            ?>
        </ul>
    </div>
</div>

<!-- end:: Aside Menu -->