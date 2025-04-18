<?php require views_path('partials/header');?>

<div class="container-fluid" style="color:#444">
    <div class="row">
        <!-- Left Navigation Panel -->
        <div class="col-12 col-sm-3 col-md-2 px-0">
            <div class="sticky-top" style="top: 1rem;">
                <div class="bg-white rounded shadow-sm">
                    <div class="p-3 border-bottom">
                        <h5 class="mb-0"><i class="fa fa-user-shield"></i> Admin Panel</h5>
                    </div>
                    <ul class="list-group list-group-flush">
                        <?php if(Auth::access('admin')): ?>
                            <a href="index.php?pg=admin&tab=dashboard" class="list-group-item list-group-item-action <?=!$tab || $tab == 'dashboard'?'active':''?>">
                                <i class="fa fa-th-large"></i> Dashboard
                            </a>
                            <a href="index.php?pg=admin&tab=users" class="list-group-item list-group-item-action <?=$tab=='users'?'active':''?>">
                                <i class="fa fa-users"></i> Users
                            </a>
                        <?php endif; ?>
                        
                        <?php if(Auth::access('admin') || Auth::access('supervisor')): ?>
                            <a href="index.php?pg=admin&tab=products" class="list-group-item list-group-item-action <?=$tab=='products'?'active':''?>">
                                <i class="fa fa-hamburger"></i> Products
                            </a>
                            <a href="index.php?pg=admin&tab=reports" class="list-group-item list-group-item-action <?=$tab=='reports'?'active':''?>">
                                <i class="fa fa-chart-bar"></i> Reports
                            </a>
                        <?php endif; ?>
                        
                        <?php if(Auth::access('admin') || Auth::access('supervisor') || Auth::access('cashier')): ?>
                            <a href="index.php?pg=admin&tab=sales" class="list-group-item list-group-item-action <?=$tab=='sales'?'active':''?>">
                                <i class="fa fa-money-bill-wave"></i> Sales
                            </a>
                            <a href="index.php?pg=admin&tab=orders" class="list-group-item list-group-item-action <?=$tab=='orders'?'active':''?>">
                                <i class="fa fa-shopping-cart"></i> Orders
                            </a>
                        <?php endif; ?>
                        
                        <a href="index.php?pg=logout" class="list-group-item list-group-item-action text-danger">
                            <i class="fa fa-sign-out-alt"></i> Logout
                        </a>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="col-12 col-sm-9 col-md-10">
            <div class="bg-white rounded shadow-sm p-4">
                <?php if($tab != 'dashboard'): ?>
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="mb-0"><?=strtoupper($tab)?></h4>
                        <?php if($tab == 'orders' && (Auth::access('admin') || Auth::access('supervisor') || Auth::access('cashier'))): ?>
                            <a href="index.php?pg=order-new" class="btn btn-primary btn-sm">
                                <i class="fa fa-plus"></i> Add New
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php  
                    switch ($tab) {
                        case 'products':
                            require views_path('admin/products');
                            break;

                        case 'users':
                            require views_path('admin/users');
                            break;

                        case 'sales':
                            require views_path('admin/sales');
                            break;

                        case 'orders':
                            require views_path('admin/orders');
                            break;

                        case 'reports':
                            require views_path('admin/reports');
                            break;
                        
                        default:
                            require views_path('admin/dashboard');
                            break;
                    }
                ?>
            </div>
        </div>
    </div>
</div>

<?php require views_path('partials/footer');?>