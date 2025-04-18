<?php
if(!empty($data)) {
    extract($data);
}
?>

<div class="card">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0"><i class="fa fa-hamburger"></i> Orders</h4>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="collapse" data-bs-target="#salesSummary">
                <i class="fa fa-chart-bar"></i> Sales Summary
            </button>
            <?php if(Auth::access('admin') || Auth::access('supervisor') || Auth::access('cashier')): ?>
                <a href="index.php?pg=order-new" class="btn btn-sm btn-light">
                    <i class="fa fa-plus"></i> Add Order
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Sales Summary Section -->
    <div class="collapse" id="salesSummary">
        <div class="card-body border-bottom">
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="card-title text-muted">Today's Sales</h6>
                            <h4 class="mb-0">KES <?=number_format($today_sales, 2)?></h4>
                            <small class="text-muted"><?=$today_orders?> orders</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="card-title text-muted">This Week's Sales</h6>
                            <h4 class="mb-0">KES <?=number_format($week_sales, 2)?></h4>
                            <small class="text-muted"><?=$week_orders?> orders</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="card-title text-muted">This Month's Sales</h6>
                            <h4 class="mb-0">KES <?=number_format($month_sales, 2)?></h4>
                            <small class="text-muted"><?=$month_orders?> orders</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="card-title text-muted">Total Outstanding</h6>
                            <h4 class="mb-0">KES <?=number_format($total_outstanding, 2)?></h4>
                            <small class="text-muted"><?=$pending_payments?> pending payments</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <!-- Table Header -->
                <thead class="table-light">
                    <tr>
                        <th style="width: 8%">Order ID</th>
                        <th style="width: 12%">Order Name</th>
                        <th style="width: 12%">Customer Name</th>
                        <th style="width: 10%">Customer Phone No</th>
                        <th style="width: 10%">Total Amount</th>
                        <th style="width: 10%">Deposit</th>
                        <th style="width: 10%">Balance</th>
                        <th style="width: 2%">Status</th>
                        <th style="width: 8%">Created By</th>
                        <th style="width: 14%">Pickup Date</th>
                        <th style="width: 4%">Actions</th>
                    </tr>
                </thead>

                <!-- Table Body -->
                <tbody>
                    <?php if(!empty($orders)):?>
                        <?php foreach ($orders as $order):?>
                            <tr>
                                <!-- Order ID -->
                                <td>
                                    <a href="index.php?pg=order-view&id=<?=$order['id']?>" class="text-primary">
                                        #<?=str_pad($order['id'], 5, '0', STR_PAD_LEFT)?>
                                    </a>
                                </td>
                                
                                <!-- Order Details -->
                                <td><?=$order['order_name']?></td>
                                <td><?=!empty($order['customer_name']) ? $order['customer_name'] : 'Not provided'?></td>
                                <td><?=$order['phone_number']?></td>
                                
                                <!-- Financial Information -->
                                <td>KES <?=number_format($order['total_amount'], 2)?></td>
                                <td>KES <?=number_format($order['deposit'] ?? 0, 2)?></td>
                                <td>KES <?=number_format($order['balance'] ?? ($order['total_amount'] - ($order['deposit'] ?? 0)), 2)?></td>
                                
                                <!-- Status Badge -->
                                <td>
                                    <span class="badge bg-<?php
                                        switch($order['status'] ?? 'pending') {
                                            case 'completed': echo 'success'; break;
                                            case 'in_progress': echo 'primary'; break;
                                            case 'picked': echo 'info'; break;
                                            case 'cancelled': echo 'danger'; break;
                                            default: echo 'warning';
                                        }
                                    ?>">
                                        <?=ucfirst($order['status'] ?? 'Pending')?>
                                    </span>
                                </td>
                                
                                <!-- Created By -->
                                <td><?=$order['created_by']?></td>
                                
                                <!-- Pickup Date -->
                                <td><?=!empty($order['pickup_date']) ? get_date($order['pickup_date']) : 'Not Set'?></td>
                                
                                <!-- Action Buttons -->
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <?php if($order['status'] == 'picked'): ?>
                                            <?php if(Auth::access('admin')): ?>
                                                <a href="index.php?pg=order-edit&id=<?=$order['id']?>" class="btn btn-primary btn-sm">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <?php if(Auth::access('admin') || Auth::access('supervisor') || Auth::access('cashier')): ?>
                                                <a href="index.php?pg=order-edit&id=<?=$order['id']?>" class="btn btn-primary btn-sm">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        
                                        <?php if(Auth::access('admin') || Auth::access('supervisor')): ?>
                                            <a href="index.php?pg=order-delete&id=<?=$order['id']?>" class="btn btn-danger btn-sm">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        <?php endif; ?>
                                        
                                        <a href="index.php?pg=order-view&id=<?=$order['id']?>" class="btn btn-info btn-sm">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach;?>
                    <?php else:?>
                        <!-- Empty State -->
                        <tr>
                            <td colspan="11" class="text-center py-5">
                                <i class="fa fa-shopping-cart fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No orders found</p>
                            </td>
                        </tr>
                    <?php endif;?>
                </tbody>
            </table>
        </div>
    </div>
</div>