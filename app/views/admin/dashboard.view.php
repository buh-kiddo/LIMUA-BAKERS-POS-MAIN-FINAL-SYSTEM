<style>
.stat-card {
    background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.8) 100%);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.5);
    box-shadow: 0 8px 32px 0 rgba(31,38,135,0.1);
    border-radius: 20px;
    padding: 1.5rem;
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 40px 0 rgba(31,38,135,0.15);
}

.stat-icon {
    font-size: 40px;
    background: linear-gradient(45deg, #4f46e5 0%, #3b82f6 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-bottom: 1rem;
}

.stat-title {
    color: #64748b;
    font-size: 1rem;
    margin-bottom: 0.5rem;
}

.stat-value {
    color: #1e293b;
    font-size: 2rem;
    font-weight: bold;
    margin: 0;
}

.orders-table {
    background: #ffffff;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 8px 32px 0 rgba(31,38,135,0.1);
}

.orders-table .card-header {
    background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
    color: white;
    padding: 1rem 1.5rem;
    border: none;
}

.orders-table .table {
    color: #1e293b;
    margin: 0;
}

.orders-table .table th {
    border-color: rgba(0,0,0,0.1);
    color: #64748b;
    font-weight: 500;
}

.orders-table .table td {
    border-color: rgba(0,0,0,0.1);
}

.badge {
    padding: 0.5em 1em;
    border-radius: 10px;
    font-weight: 500;
}

body {
    background: #f8fafc;
    min-height: 100vh;
}

.table-hover tbody tr:hover {
    background-color: rgba(79, 70, 229, 0.05);
}
</style>

<div class="row justify-content-center g-4">
    <div class="col-md-3">
        <div class="stat-card">
            <i class="fa fa-user stat-icon"></i>
            <h4 class="stat-title">Total Users</h4>
            <h1 class="stat-value"><?=$total_users?></h1>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <i class="fa fa-hamburger stat-icon"></i>
            <h4 class="stat-title">Total Products</h4>
            <h1 class="stat-value"><?=$total_products?></h1>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <i class="fa fa-money-bill-wave stat-icon"></i>
            <h4 class="stat-title">Total Sales</h4>
            <h1 class="stat-value"><?=$total_sales?></h1>
        </div>
    </div>
</div>

<div class="row justify-content-center g-4 mt-2">
    <div class="col-md-3">
        <div class="stat-card">
            <i class="fa fa-shopping-cart stat-icon"></i>
            <h4 class="stat-title">Total Orders</h4>
            <h1 class="stat-value"><?=$total_orders?></h1>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <i class="fa fa-clock stat-icon"></i>
            <h4 class="stat-title">Pending Orders</h4>
            <h1 class="stat-value"><?=$pending_orders?></h1>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <i class="fa fa-dollar-sign stat-icon"></i>
            <h4 class="stat-title">Orders Balance</h4>
            <h1 class="stat-value">KES <?=number_format($total_balance, 2)?></h1>
        </div>
    </div>
</div>

<div class="row justify-content-center mt-4">
    <div class="col-md-10">
        <div class="card orders-table">
            <div class="card-header">
                <h4 class="mb-0">Recent Orders</h4>
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th style="width: 15%">Order ID</th>
                            <th style="width: 30%">Name</th>
                            <th style="width: 20%">Amount</th>
                            <th style="width: 15%">Status</th>
                            <th style="width: 20%">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($recent_orders)):?>
                            <?php foreach($recent_orders as $order):?>
                                <tr>
                                    <td style="width: 15%">
                                        <a href="index.php?pg=order-view&id=<?=$order['id']?>" class="text-primary">
                                            #<?=str_pad($order['id'], 5, '0', STR_PAD_LEFT)?>
                                        </a>
                                    </td>
                                    <td style="width: 30%"><?=$order['order_name']?></td>
                                    <td style="width: 20%">KES <?=number_format($order['total_amount'], 2)?></td>
                                    <td style="width: 15%">
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
                                    <td style="width: 20%"><?=get_date($order['date_created'])?></td>
                                </tr>
                            <?php endforeach;?>
                        <?php else:?>
                            <tr>
                                <td colspan="5" class="text-center">No recent orders</td>
                            </tr>
                        <?php endif;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>