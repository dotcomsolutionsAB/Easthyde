<!-- begin:: Aside Menu -->

<?php
// You already have $db available (used below for `extra`).
// Fetch toggles
$sql   = "SELECT * FROM extra";
$query = $db->query($sql);
$row   = $query ? $query->fetch_assoc() : null;

$extra_toggle = $row['estimate_toggle'] ?? 0;

// ===== Access fetch (by session username) =====
session_start();
$user = $_SESSION['username'] ?? '';

$access = [];
if (!empty($user)) {
    // Fetch the access JSON for this user
    if ($stmt = $db->prepare("SELECT access FROM users WHERE username = ? LIMIT 1")) {
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $stmt->bind_result($accessJson);
        if ($stmt->fetch() && $accessJson) {
            $decoded = json_decode($accessJson, true);
            if (is_array($decoded)) $access = $decoded;
        }
        $stmt->close();
    }
}

// ===== Helpers =====
function canView(array $access, string $module): bool {
    return isset($access[$module]['view']) && $access[$module]['view'] === '1';
}
function anyView(array $access, array $modules): bool {
    foreach ($modules as $m) {
        if (canView($access, $m)) return true;
    }
    return false;
}
?>
<style>
    .submenu-arrow {
        font-size: 20px;
        color: #ffffff;
        margin-left: auto;
        font-weight: bold;
        transition: transform 0.3s ease;
    }
    .kt-menu__item--submenu:hover .submenu-arrow {
        transform: rotate(90deg);
    }
</style>

<div class="kt-aside-menu-wrapper kt-grid__item kt-grid__item--fluid" id="kt_aside_menu_wrapper">
    <div id="kt_aside_menu" class="kt-aside-menu" data-ktmenu-vertical="1" data-ktmenu-scroll="1" data-ktmenu-dropdown-timeout="500">
        <ul class="kt-menu__nav">

            <?php if (canView($access, 'quotation')): ?>
            <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true">
                <a href="?page=quotation" class="kt-menu__link kt-menu__toggle">
                    <span class="kt-menu__link-icon">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </span>
                    <span class="kt-menu__link-text" style="color: #ffffff; font-weight: semi-bold; font-size: 20px;">Quotations</span>
                </a>
            </li>
            <?php endif; ?>

            <?php
                // Sales parent shows only if any child is viewable
                $salesChildren = ['sales_order','proforma_invoice','sales_invoice','receipt','credit_note'];
                $showSales = anyView($access, $salesChildren);
            ?>
            <?php if ($showSales): ?>
            <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true">
                <a href="?page=sales" class="kt-menu__link kt-menu__toggle">
                    <span class="kt-menu__link-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </span>
                    <span class="kt-menu__link-text" style="color: #ffffff; font-weight: semi-bold; font-size: 20px;">Sales</span>
                    <i class="submenu-arrow fas fa-angle-right"></i>
                </a>
                <div class="kt-menu__submenu">
                    <ul class="kt-menu__subnav">
                        <li class="kt-menu__item kt-menu__item--parent" aria-haspopup="true">
                            <span class="kt-menu__link">
                                <span class="kt-menu__link-text" style="color: #ffffff; font-weight: semi-bold; font-size: 18px;">Sales</span>
                            </span>
                        </li>

                        <?php if (canView($access, 'sales_order')): ?>
                        <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true">
                            <a href="?page=sales_order" class="kt-menu__link kt-menu__toggle">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text" style="color: #ffffff; font-weight: semi-bold; font-size: 18px;">Sales Order</span>
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php if (canView($access, 'proforma_invoice')): ?>
                        <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true">
                            <a href="?page=proforma_invoice" class="kt-menu__link kt-menu__toggle">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text" style="color: #ffffff; font-weight: semi-bold; font-size: 18px;">Proforma Invoice</span>
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php if (canView($access, 'sales_invoice')): ?>
                        <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true">
                            <a href="?page=sales" class="kt-menu__link kt-menu__toggle">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text" style="color: #ffffff; font-weight: semi-bold; font-size: 18px;">Sales Invoice</span>
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php if (canView($access, 'receipt')): ?>
                        <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true">
                            <a href="?page=receipt" class="kt-menu__link kt-menu__toggle">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text" style="color: #ffffff; font-weight: semi-bold; font-size: 18px;">Receipt</span>
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php if (canView($access, 'credit_note')): ?>
                        <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true">
                            <a href="?page=credit_note" class="kt-menu__link kt-menu__toggle">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text" style="color: #ffffff; font-weight: semi-bold; font-size: 18px;">Credit Note</span>
                            </a>
                        </li>
                        <?php endif; ?>

                    </ul>
                </div>
            </li>
            <?php endif; ?>

            <?php
                // Purchase parent shows only if any child is viewable
                $purchaseChildren = ['purchase_quotation','purchase_order','purchase_invoice','payments','debit_note'];
                $showPurchase = anyView($access, $purchaseChildren);
            ?>
            <?php if ($showPurchase): ?>
            <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true">
                <a href="?page=purchase" class="kt-menu__link kt-menu__toggle">
                    <span class="kt-menu__link-icon">
                        <i class="fas fa-shopping-bag"></i>
                    </span>
                    <span class="kt-menu__link-text" style="color: #ffffff; font-weight: semi-bold; font-size: 20px;">Purchase</span>
                    <i class="submenu-arrow fas fa-angle-right"></i>
                </a>
                <div class="kt-menu__submenu">
                    <ul class="kt-menu__subnav">
                        <li class="kt-menu__item kt-menu__item--parent" aria-haspopup="true">
                            <span class="kt-menu__link">
                                <span class="kt-menu__link-text" style="color: #ffffff; font-weight: semi-bold; font-size: 18px;">Purchase</span>
                            </span>
                        </li>

                        <?php if (canView($access, 'purchase_quotation')): ?>
                        <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true">
                            <a href="?page=purchase_quotation" class="kt-menu__link kt-menu__toggle">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text" style="color: #ffffff; font-weight: semi-bold; font-size: 18px;">Purchase Quotation</span>
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php if (canView($access, 'purchase_order')): ?>
                        <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true">
                            <a href="?page=purchase_order" class="kt-menu__link kt-menu__toggle">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text" style="color: #ffffff; font-weight: semi-bold; font-size: 18px;">Purchase Order</span>
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php if (canView($access, 'purchase_invoice')): ?>
                        <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true">
                            <a href="?page=purchase" class="kt-menu__link kt-menu__toggle">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text" style="color: #ffffff; font-weight: semi-bold; font-size: 18px;">Purchase Invoice</span>
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php if (canView($access, 'payments')): ?>
                        <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true">
                            <a href="?page=payments" class="kt-menu__link kt-menu__toggle">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text" style="color: #ffffff; font-weight: semi-bold; font-size: 18px;">Payments</span>
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php if (canView($access, 'debit_note')): ?>
                        <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true">
                            <a href="?page=debit_note" class="kt-menu__link kt-menu__toggle">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text" style="color: #ffffff; font-weight: semi-bold; font-size: 18px;">Debit Note</span>
                            </a>
                        </li>
                        <?php endif; ?>

                    </ul>
                </div>
            </li>
            <?php endif; ?>

            <!-- Journal menu - Commented out -->
            <!-- (left as-is) -->

            <!-- Banks -->
            <?php if (canView($access, 'banks')): ?>
            <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true">
                <a href="?page=banks" class="kt-menu__link kt-menu__toggle">
                    <span class="kt-menu__link-icon">
                        <i class="fas fa-university"></i>
                    </span>
                    <span class="kt-menu__link-text" style="color: #ffffff; font-weight: semi-bold; font-size: 20px;">Banks</span>
                </a>
            </li>
            <?php endif; ?>

            <!-- Bank Ledger (only if you add 'bank_ledger' into access; otherwise it stays hidden) -->
            <?php if (canView($access, 'bank_ledger')): ?>
            <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true">
                <a href="?page=bank_ledger" class="kt-menu__link kt-menu__toggle">
                    <span class="kt-menu__link-icon">
                        <i class="fas fa-book"></i>
                    </span>
                    <span class="kt-menu__link-text" style="color: #ffffff; font-weight: semi-bold; font-size: 20px;">Bank Ledger</span>
                </a>
            </li>
            <?php endif; ?>

            <!-- Expense -->
            <?php if (canView($access, 'expense')): ?>
            <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true">
                <a href="?page=expense" class="kt-menu__link kt-menu__toggle">
                    <span class="kt-menu__link-icon">
                        <i class="fas fa-credit-card"></i>
                    </span>
                    <span class="kt-menu__link-text" style="color: #ffffff; font-weight: semi-bold; font-size: 20px;">Expense</span>
                </a>
            </li>
            <?php endif; ?>

            <!-- Product group parent only if any of its items is allowed -->
            <?php
                $productGroupChildren = ['products','ROP','assemblies']; // only 'products' exists in your access now
                $showProductGroup = anyView($access, $productGroupChildren);
            ?>
            <?php if ($showProductGroup): ?>
            <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true">
                <a href="?page=product" class="kt-menu__link kt-menu__toggle">
                    <span class="kt-menu__link-icon">
                        <i class="fas fa-box"></i>
                    </span>
                    <span class="kt-menu__link-text" style="color: #ffffff; font-weight: semi-bold; font-size: 20px;">Products</span>
                    <i class="submenu-arrow fas fa-angle-right"></i>
                </a>
                <div class="kt-menu__submenu">
                    <ul class="kt-menu__subnav">
                        <li class="kt-menu__item kt-menu__item--parent" aria-haspopup="true">
                            <span class="kt-menu__link">
                                <span class="kt-menu__link-text" style="color: #ffffff; font-weight: semi-bold; font-size: 18px;">Product</span>
                            </span>
                        </li>

                        <?php if (canView($access, 'products')): ?>
                        <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true">
                            <a href="?page=product" class="kt-menu__link kt-menu__toggle">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text" style="color: #ffffff; font-weight: semi-bold; font-size: 18px;">Product</span>
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php if (canView($access, 'ROP')): /* will be hidden unless you add to access */ ?>
                        <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true">
                            <a href="?page=ROP" class="kt-menu__link kt-menu__toggle">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text" style="color: #ffffff; font-weight: semi-bold; font-size: 18px;">ROP</span>
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php if (canView($access, 'assemblies')): /* will be hidden unless you add to access */ ?>
                        <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true">
                            <a href="?page=assemblies" class="kt-menu__link kt-menu__toggle">
                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                <span class="kt-menu__link-text" style="color: #ffffff; font-weight: semi-bold; font-size: 18px;">Assembly</span>
                            </a>
                        </li>
                        <?php endif; ?>

                    </ul>
                </div>
            </li>
            <?php endif; ?>

            <!-- Clients -->
            <?php if (canView($access, 'clients')): ?>
            <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true">
                <a href="?page=clients" class="kt-menu__link kt-menu__toggle">
                    <span class="kt-menu__link-icon">
                        <i class="fas fa-users"></i>
                    </span>
                    <span class="kt-menu__link-text" style="color: #ffffff; font-weight: semi-bold; font-size: 20px;">Clients</span>
                </a>
            </li>
            <?php endif; ?>

            <!-- Suppliers -->
            <?php if (canView($access, 'suppliers')): ?>
            <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true">
                <a href="?page=suppliers" class="kt-menu__link kt-menu__toggle">
                    <span class="kt-menu__link-icon">
                        <i class="fas fa-truck"></i>
                    </span>
                    <span class="kt-menu__link-text" style="color: #ffffff; font-weight: semi-bold; font-size: 20px;">Suppliers</span>
                </a>
            </li>
            <?php endif; ?>

            <!-- Users (skip unless you add 'users' into access) -->
            <?php if (canView($access, 'users')): ?>
            <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true">
                <a href="?page=users" class="kt-menu__link kt-menu__toggle">
                    <span class="kt-menu__link-icon">
                        <i class="fas fa-user-cog"></i>
                    </span>
                    <span class="kt-menu__link-text" style="color: #ffffff; font-weight: semi-bold; font-size: 20px;">Users</span>
                </a>
            </li>
            <?php endif; ?>

            <?php if ($extra_toggle == 1 || true): ?>
                <?php if (canView($access, 'secondary_sales')): ?>
                <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true">
                    <a href="?page=secondary_sales" class="kt-menu__link kt-menu__toggle">
                        <span class="kt-menu__link-icon">
                            <i class="fas fa-chart-line"></i>
                        </span>
                        <span class="kt-menu__link-text" style="color: #ffffff; font-weight: semi-bold; font-size: 20px;">Secondary Sales</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if (canView($access, 'secondary_purchase')): ?>
                <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true">
                    <a href="?page=secondary_purchase" class="kt-menu__link kt-menu__toggle">
                        <span class="kt-menu__link-icon">
                            <i class="fas fa-cart-arrow-down"></i>
                        </span>
                        <span class="kt-menu__link-text" style="color: #ffffff; font-weight: semi-bold; font-size: 18px;">Secondary Purchase</span>
                    </a>
                </li>
                <?php endif; ?>

                <!-- Secondary Sales Items menu - Commented out (left as-is) -->

                <?php if (canView($access, 'khumus')): ?>
                <li class="kt-menu__item kt-menu__item--submenu" aria-haspopup="true">
                    <a href="?page=khumus" class="kt-menu__link kt-menu__toggle">
                        <span class="kt-menu__link-icon">
                            <i class="fas fa-money-bill-alt"></i>
                        </span>
                        <span class="kt-menu__link-text" style="color: #ffffff; font-weight: semi-bold; font-size: 20px;">Khumus</span>
                    </a>
                </li>
                <?php endif; ?>
            <?php endif; ?>

        </ul>
    </div>
</div>

<!-- end:: Aside Menu -->
