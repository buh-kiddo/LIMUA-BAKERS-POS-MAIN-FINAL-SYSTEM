<?php
    $order = new Orders();
    $todaysOrders = $order->getTodaysOrders();
    $stats = $order->getOrderStats();
?>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Today's Orders</h5>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Orders</h5>
                        <h3><?=$stats['total_orders'] ?? 0?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Amount</h5>
                        <h3>KES <?=number_format($stats['total_amount'] ?? 0, 2)?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Deposits</h5>
                        <h3>KES <?=number_format($stats['total_deposits'] ?? 0, 2)?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Balance</h5>
                        <h3>KES <?=number_format($stats['total_balance'] ?? 0, 2)?></h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Order</th>
                        <th>Amount</th>
                        <th>Pick Up</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($todaysOrders)):?>
                        <?php foreach($todaysOrders as $order):?>
                            <tr>
                                <td><?=esc($order['order_number'])?></td>
                                <td><?=esc($order['customer_name'])?></td>
                                <td><?=esc($order['order_name'])?></td>
                                <td>KES <?=number_format($order['total_amount'], 2)?></td>
                                <td><?=date('H:i', strtotime($order['pick_up_time']))?></td>
                                <td>
                                    <?php 
                                        $now = time();
                                        $pickup = strtotime($order['pick_up_time']);
                                        if($pickup < $now):
                                    ?>
                                        <span class="badge bg-danger">Overdue</span>
                                    <?php elseif($pickup - $now < 3600): // Less than 1 hour?>
                                        <span class="badge bg-warning">Due Soon</span>
                                    <?php else:?>
                                        <span class="badge bg-success">Scheduled</span>
                                    <?php endif;?>
                                </td>
                            </tr>
                        <?php endforeach;?>
                    <?php else:?>
                        <tr>
                            <td colspan="6" class="text-center">No orders scheduled for today</td>
                        </tr>
                    <?php endif;?>
                </tbody>
            </table>
        </div>
    </div>
</div>
