<?php
if (!empty($data)) {
    extract($data);
}
?>

<div class="container-fluid p-4">
    <!-- Report Type Selection -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="get" class="row g-3 align-items-end">
                        <input type="hidden" name="pg" value="admin">
                        <input type="hidden" name="tab" value="reports">

                        <div class="col-md-3">
                            <label class="form-label">Report Type</label>
                            <select name="type" class="form-select" onchange="this.form.submit()">
                                <option value="product_sales" <?= $report_type == 'product_sales' ? 'selected' : '' ?>>Product Sales Report</option>
                                <option value="order_sales" <?= $report_type == 'order_sales' ? 'selected' : '' ?>>Order Sales Report</option>
                                <option value="inventory" <?= $report_type == 'inventory' ? 'selected' : '' ?>>Inventory Report</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date" class="form-control" value="<?= htmlspecialchars($start_date) ?>" max="<?= date('Y-m-d') ?>">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" class="form-control" value="<?= htmlspecialchars($end_date) ?>" max="<?= date('Y-m-d') ?>">
                        </div>

                        <div class="col-md-3 d-flex align-items-end">
                            <div class="d-flex gap-2 w-100">
                                <button type="submit" class="btn btn-primary flex-grow-1">Generate</button>
                                <a href="index.php?pg=admin&tab=export_pdf&type=<?=$report_type?>&start_date=<?=$start_date?>&end_date=<?=$end_date?>" class="btn btn-info flex-grow-1">
                                    <i class="fas fa-file-pdf me-1"></i> Export PDF
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Content -->
    <div class="row report-content">
        <div class="col-12">
            <?php if ($report_type == 'inventory'): ?>
                <!-- Inventory Report -->
                <div class="card shadow-sm" data-report="inventory">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Inventory Summary</h5>
                        <span class="text-muted">Generated on: <?= date('F j, Y g:i A') ?></span>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-12 col-md-6 col-lg-4 mb-3">
                                <div class="card bg-primary bg-gradient text-white h-100 summary-card">
                                    <div class="card-body">
                                        <h6 class="card-subtitle mb-2">Total Products</h6>
                                        <h3 class="card-title mb-0"><?= number_format($total_products) ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 mb-3">
                                <div class="card bg-success bg-gradient text-white h-100 summary-card">
                                    <div class="card-body">
                                        <h6 class="card-subtitle mb-2">Total Stock Value</h6>
                                        <h3 class="card-title mb-0">KES <?= number_format($total_stock_value, 2) ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 mb-3">
                                <div class="card bg-info bg-gradient text-white h-100 summary-card">
                                    <div class="card-body">
                                        <h6 class="card-subtitle mb-2">Total Revenue</h6>
                                        <h3 class="card-title mb-0">KES <?= number_format($total_revenue, 2) ?></h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Low Stock Alert -->
                        <?php if (!empty($low_stock_data)): ?>
                        <div class="alert alert-warning mb-4">
                            <h6 class="alert-heading mb-2">Low Stock Alert</h6>
                            <div class="table-responsive">
                                <table class="table table-sm table-warning mb-0">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Current Stock</th>
                                            <th>Units Needed</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($low_stock_data as $item): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($item['description']) ?></td>
                                            <td><?= number_format($item['current_stock']) ?></td>
                                            <td><?= number_format($item['units_needed']) ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Inventory Table -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Product</th>
                                        <th>Barcode</th>
                                        <th class="text-center">Current Stock</th>
                                        <th class="text-end">Unit Price</th>
                                        <th class="text-end">Stock Value</th>
                                        <th class="text-center">Total Sold</th>
                                        <th class="text-end">Total Revenue</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($products)): ?>
                                        <?php foreach ($products as $product): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($product['description']) ?></td>
                                                <td><?= $product['barcode'] ?></td>
                                                <td class="text-center">
                                                    <?php
                                                    $stock_class = '';
                                                    if ($product['current_stock'] <= 5) {
                                                        $stock_class = 'text-danger fw-bold';
                                                    } elseif ($product['current_stock'] <= 10) {
                                                        $stock_class = 'text-warning fw-bold';
                                                    }
                                                    ?>
                                                    <span class="<?= $stock_class ?>"><?= number_format($product['current_stock']) ?></span>
                                                </td>
                                                <td class="text-end">KES <?= number_format($product['unit_price'], 2) ?></td>
                                                <td class="text-end">KES <?= number_format($product['stock_value'], 2) ?></td>
                                                <td class="text-center"><?= number_format($product['total_sold']) ?></td>
                                                <td class="text-end">KES <?= number_format($product['total_revenue'], 2) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center">No inventory data available</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                                <?php if (!empty($products)): ?>
                                    <tfoot class="table-light">
                                        <tr>
                                            <td colspan="2"><strong>Total</strong></td>
                                            <td class="text-center"><strong><?= number_format(array_sum(array_column($products, 'current_stock'))) ?></strong></td>
                                            <td></td>
                                            <td class="text-end"><strong>KES <?= number_format($total_stock_value, 2) ?></strong></td>
                                            <td class="text-center"><strong><?= number_format(array_sum(array_column($products, 'total_sold'))) ?></strong></td>
                                            <td class="text-end"><strong>KES <?= number_format($total_revenue, 2) ?></strong></td>
                                        </tr>
                                    </tfoot>
                                <?php endif; ?>
                            </table>
                        </div>
                    </div>
                </div>

            <?php elseif ($report_type == 'product_sales'): ?>
                <!-- Sales Performance Summary -->
                <div class="card shadow-sm mb-4" data-report="product_sales">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Sales Performance</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <div class="card bg-primary bg-gradient text-white h-100 summary-card">
                                    <div class="card-body">
                                        <h6 class="card-subtitle mb-2">Today's Sales</h6>
                                        <h3 class="card-title mb-0">KES <?= number_format($today_sales, 2) ?></h3>
                                        <small><?= number_format($today_transactions) ?> transactions</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card bg-info bg-gradient text-white h-100 summary-card">
                                    <div class="card-body">
                                        <h6 class="card-subtitle mb-2">Last Sales</h6>
                                        <h3 class="card-title mb-0">KES <?= number_format($last_sales, 2) ?></h3>
                                        <div class="d-flex flex-column">
                                            <small><?= number_format($last_transactions) ?> transactions</small>
                                            <small class="opacity-75">on <?= date('M j', strtotime($last_sale_date)) ?></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card <?= $sales_growth >= 0 ? 'bg-success' : 'bg-danger' ?> bg-gradient text-white h-100 summary-card">
                                    <div class="card-body">
                                        <h6 class="card-subtitle mb-2">Sales Growth</h6>
                                        <h3 class="card-title mb-0"><?= number_format($sales_growth, 1) ?>%</h3>
                                        <small>vs Last Sales</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card bg-warning bg-gradient text-white h-100 summary-card">
                                    <div class="card-body">
                                        <h6 class="card-subtitle mb-2">Average Sale</h6>
                                        <h3 class="card-title mb-0">KES <?= number_format($average_sale, 2) ?></h3>
                                        <small class="text-muted">Average KES <?= number_format($average_sale, 2) ?>/day</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sales Trends -->
                <div class="card shadow-sm mb-4" data-report="product_sales">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Sales Trends</h5>
                        <div class="text-muted">
                            <small><?= date('F j, Y', strtotime($start_date)) ?> - <?= date('F j, Y', strtotime($end_date)) ?></small>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8 mb-4">
                                <div class="chart-container" style="position: relative; height:300px;">
                                    <canvas id="salesTrendChart"></canvas>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <div class="chart-container" style="position: relative; height:300px;">
                                    <canvas id="hourlyDistributionChart"></canvas>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Trend Metrics -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle border">
                                        <thead class="table-light">
                                            <tr class="text-uppercase" style="font-size: 0.85rem;">
                                                <th class="fw-bold border-end">
                                                    <i class="fas fa-calendar me-1 text-primary"></i>
                                                    Date
                                                </th>
                                                <th class="text-end fw-bold border-end">
                                                    <i class="fas fa-shopping-cart me-1 text-primary"></i>
                                                    Transactions
                                                </th>
                                                <th class="text-end fw-bold border-end">
                                                    <i class="fas fa-box me-1 text-primary"></i>
                                                    Items
                                                </th>
                                                <th class="text-end fw-bold border-end">
                                                    <i class="fas fa-money-bill me-1 text-success"></i>
                                                    Revenue
                                                </th>
                                                <th class="text-end fw-bold border-end">
                                                    <i class="fas fa-calculator me-1 text-info"></i>
                                                    Per Sale
                                                </th>
                                                <th class="text-end fw-bold">
                                                    <i class="fas fa-cubes me-1 text-warning"></i>
                                                    Products
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $prev_total = 0;
                                            foreach ($sales_trend as $trend): ?>
                                            <tr class="border-bottom">
                                                <td class="border-end">
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-3">
                                                            <span class="badge bg-light text-dark rounded-circle p-2">
                                                                <?= date('d', strtotime($trend['sale_date'])) ?>
                                                            </span>
                                                        </div>
                                                        <div>
                                                            <span class="fw-medium"><?= date('M Y', strtotime($trend['sale_date'])) ?></span>
                                                            <br>
                                                            <small class="text-muted"><?= date('l', strtotime($trend['sale_date'])) ?></small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-end border-end">
                                                    <span class="badge bg-primary rounded-pill">
                                                        <?= number_format($trend['transaction_count']) ?>
                                                    </span>
                                                </td>
                                                <td class="text-end border-end">
                                                    <div class="fw-medium"><?= number_format($trend['items_sold']) ?></div>
                                                    <small class="text-muted">units</small>
                                                </td>
                                                <td class="text-end border-end">
                                                    <div class="fw-medium text-success">KES <?= number_format($trend['daily_total'], 2) ?></div>
                                                    <?php 
                                                    $prev_day = isset($prev_total) ? $trend['daily_total'] - $prev_total : 0;
                                                    $prev_total = $trend['daily_total'];
                                                    if ($prev_day != 0):
                                                    ?>
                                                    <small class="<?= $prev_day > 0 ? 'text-success' : 'text-danger' ?>">
                                                        <i class="fas fa-<?= $prev_day > 0 ? 'arrow-up' : 'arrow-down' ?>"></i>
                                                        <?= number_format(abs($prev_day), 2) ?>
                                                    </small>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-end border-end">
                                                    <div class="fw-medium text-info">KES <?= number_format($trend['avg_transaction_value'], 2) ?></div>
                                                </td>
                                                <td class="text-end">
                                                    <span class="badge bg-warning text-dark rounded-pill">
                                                        <?= number_format($trend['unique_products']) ?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                        <tfoot class="table-light">
                                            <tr>
                                                <td class="fw-bold border-end">Period Summary</td>
                                                <td class="text-end fw-bold border-end"><?= number_format(array_sum(array_column($sales_trend, 'transaction_count'))) ?></td>
                                                <td class="text-end fw-bold border-end"><?= number_format(array_sum(array_column($sales_trend, 'items_sold'))) ?></td>
                                                <td class="text-end fw-bold text-success border-end">KES <?= number_format(array_sum(array_column($sales_trend, 'daily_total')), 2) ?></td>
                                                <td class="text-end fw-bold text-info border-end">KES <?= number_format(array_sum(array_column($sales_trend, 'daily_total')) / max(1, array_sum(array_column($sales_trend, 'transaction_count'))), 2) ?></td>
                                                <td class="text-end fw-bold text-warning"><?= number_format(max(array_column($sales_trend, 'unique_products'))) ?></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Sales Analysis -->
                <div class="card shadow-sm mb-4" data-report="product_sales">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Product Performance</h5>
                    </div>
                    <div class="card-body">
                        <!-- Summary Stats -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="border rounded p-3 text-center summary-card">
                                    <h6 class="text-muted mb-2">Total Revenue</h6>
                                    <h3>KES <?= number_format($totals['total_revenue'] ?? 0, 2) ?></h3>
                                    <small class="text-muted">Average KES <?= number_format($totals['daily_avg_revenue'] ?? 0, 2) ?>/day</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="border rounded p-3 text-center summary-card">
                                    <h6 class="text-muted mb-2">Sales Volume</h6>
                                    <h3><?= number_format($totals['total_transactions'] ?? 0) ?></h3>
                                    <small class="text-muted">Avg basket: KES <?= number_format($totals['avg_basket_size'] ?? 0, 2) ?></small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="border rounded p-3 text-center summary-card">
                                    <h6 class="text-muted mb-2">Items Sold</h6>
                                    <h3><?= number_format($totals['total_items_sold'] ?? 0) ?></h3>
                                    <small class="text-muted"><?= number_format($totals['unique_products'] ?? 0) ?> unique products</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="border rounded p-3 text-center summary-card">
                                    <h6 class="text-muted mb-2">Avg Per Product</h6>
                                    <h3><?= number_format($totals['avg_units_per_product'] ?? 0, 1) ?></h3>
                                    <small class="text-muted">units sold per product</small>
                                </div>
                            </div>
                        </div>

                        <!-- Product Sales Table -->
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Quantity Sold</th>
                                        <th>Revenue</th>
                                        <th>Current Stock</th>
                                        <th>Current Price</th>
                                        <th>Average Sale</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($top_products as $product): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($product['description']) ?></td>
                                        <td><?= number_format($product['total_quantity']) ?></td>
                                        <td>KES <?= number_format($product['total_revenue'], 2) ?></td>
                                        <td>
                                            <?php if ($product['current_stock'] < 10): ?>
                                            <span class="badge bg-danger"><?= number_format($product['current_stock']) ?></span>
                                            <?php else: ?>
                                            <?= number_format($product['current_stock']) ?>
                                            <?php endif; ?>
                                        </td>
                                        <td>KES <?= number_format($product['current_price'], 2) ?></td>
                                        <td>KES <?= number_format($product['total_revenue'] / $product['total_quantity'], 2) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <?php if (!empty($insights)): ?>
                <!-- Sales Insights -->
                <div class="row mt-4">
                    <!-- Top Products -->
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-white">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-star text-warning me-2"></i>
                                    Top Selling Products
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Product</th>
                                                <th class="text-end">Qty Sold</th>
                                                <th class="text-end">Revenue</th>
                                                <th class="text-end">Last Sold</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($insights['top_products'] as $product): ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <span class="fw-medium"><?= htmlspecialchars($product['description']) ?></span>
                                                        <small class="text-muted"><?= $product['barcode'] ?></small>
                                                    </div>
                                                </td>
                                                <td class="text-end">
                                                    <span class="badge bg-primary rounded-pill">
                                                        <?= number_format($product['total_qty']) ?>
                                                    </span>
                                                </td>
                                                <td class="text-end">
                                                    <div class="fw-medium text-success">
                                                        KES <?= number_format($product['total_revenue'], 2) ?>
                                                    </div>
                                                    <small class="text-muted">
                                                        <?= number_format($product['transaction_count']) ?> sales
                                                    </small>
                                                </td>
                                                <td class="text-end text-muted">
                                                    <?= date('M j, g:ia', strtotime($product['last_sold'])) ?>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sales by Time -->
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-white">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-clock text-info me-2"></i>
                                    Peak Sales Hours
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Time</th>
                                                <th class="text-end">Sales</th>
                                                <th class="text-end">Transactions</th>
                                                <th class="text-end">Avg Sale</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $max_sales = max(array_column($insights['hourly_sales'], 'total_sales'));
                                            foreach ($insights['hourly_sales'] as $hour): 
                                                $percentage = ($hour['total_sales'] / $max_sales) * 100;
                                            ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-3">
                                                            <?= date('ga', strtotime($hour['hour_of_day'] . ':00')) ?>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <div class="progress" style="height: 4px;">
                                                                <div class="progress-bar bg-success" style="width: <?= $percentage ?>%"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-end text-success">
                                                    KES <?= number_format($hour['total_sales'], 2) ?>
                                                </td>
                                                <td class="text-end">
                                                    <span class="badge bg-primary rounded-pill">
                                                        <?= number_format($hour['transaction_count']) ?>
                                                    </span>
                                                </td>
                                                <td class="text-end text-muted">
                                                    KES <?= number_format($hour['avg_sale'], 2) ?>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sales by Day -->
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-white">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-calendar-day text-primary me-2"></i>
                                    Sales by Day of Week
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Day</th>
                                                <th class="text-end">Sales</th>
                                                <th class="text-end">Transactions</th>
                                                <th class="text-end">Avg Sale</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $max_daily = max(array_column($insights['daily_sales'], 'total_sales'));
                                            foreach ($insights['daily_sales'] as $day): 
                                                $percentage = ($day['total_sales'] / $max_daily) * 100;
                                            ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-3 fw-medium" style="width: 100px;">
                                                            <?= $day['day_name'] ?>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <div class="progress" style="height: 4px;">
                                                                <div class="progress-bar bg-primary" style="width: <?= $percentage ?>%"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-end text-success">
                                                    KES <?= number_format($day['total_sales'], 2) ?>
                                                </td>
                                                <td class="text-end">
                                                    <span class="badge bg-primary rounded-pill">
                                                        <?= number_format($day['transaction_count']) ?>
                                                    </span>
                                                </td>
                                                <td class="text-end text-muted">
                                                    KES <?= number_format($day['avg_sale'], 2) ?>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Category Performance -->
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-white">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-tags text-warning me-2"></i>
                                    Category Performance
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Category</th>
                                                <th class="text-end">Sales</th>
                                                <th class="text-end">Qty</th>
                                                <th class="text-end">Avg Sale</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $max_category = max(array_column($insights['category_sales'], 'total_sales'));
                                            foreach ($insights['category_sales'] as $category): 
                                                $percentage = ($category['total_sales'] / $max_category) * 100;
                                            ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-3 fw-medium">
                                                            <?= htmlspecialchars($category['category']) ?>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <div class="progress" style="height: 4px;">
                                                                <div class="progress-bar bg-warning" style="width: <?= $percentage ?>%"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-end text-success">
                                                    KES <?= number_format($category['total_sales'], 2) ?>
                                                </td>
                                                <td class="text-end">
                                                    <span class="badge bg-warning text-dark rounded-pill">
                                                        <?= number_format($category['total_qty']) ?>
                                                    </span>
                                                </td>
                                                <td class="text-end text-muted">
                                                    KES <?= number_format($category['avg_sale'], 2) ?>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sales Velocity -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-line text-primary me-2"></i>
                            Recent Sales Velocity
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th class="text-end">Revenue</th>
                                        <th class="text-end">Transactions</th>
                                        <th class="text-end">Products</th>
                                        <th class="text-end">Avg Basket</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($insights['sales_velocity'] as $velocity): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="fw-medium"><?= date('D, M j', strtotime($velocity['sale_date'])) ?></span>
                                                <small class="text-muted"><?= date('Y', strtotime($velocity['sale_date'])) ?></small>
                                            </div>
                                        </td>
                                        <td class="text-end">
                                            <div class="fw-medium text-success">KES <?= number_format($velocity['revenue'], 2) ?></div>
                                        </td>
                                        <td class="text-end">
                                            <span class="badge bg-primary rounded-pill">
                                                <?= number_format($velocity['transactions']) ?>
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <span class="badge bg-info rounded-pill">
                                                <?= number_format($velocity['unique_products']) ?>
                                            </span>
                                        </td>
                                        <td class="text-end text-muted">
                                            KES <?= number_format($velocity['avg_basket'], 2) ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            <?php elseif ($report_type == 'order_sales'): ?>
                <!-- Order Summary -->
                <div class="card shadow-sm mb-4" data-report="order_sales">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <div class="card bg-primary bg-gradient text-white h-100 summary-card">
                                    <div class="card-body">
                                        <h6 class="card-subtitle mb-2">Total Orders</h6>
                                        <h3 class="card-title mb-0"><?= number_format(array_sum(array_column($order_status, 'order_count'))) ?></h3>
                                        <small>For selected period</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card bg-success bg-gradient text-white h-100 summary-card">
                                    <div class="card-body">
                                        <h6 class="card-subtitle mb-2">Total Revenue</h6>
                                        <h3 class="card-title mb-0">KES <?= number_format(array_sum(array_column($order_status, 'total_amount')), 2) ?></h3>
                                        <small>All orders</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card bg-info bg-gradient text-white h-100 summary-card">
                                    <div class="card-body">
                                        <h6 class="card-subtitle mb-2">Total Deposits</h6>
                                        <h3 class="card-title mb-0">KES <?= number_format(array_sum(array_column($order_status, 'total_deposits')), 2) ?></h3>
                                        <small>Received payments</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card bg-warning bg-gradient text-white h-100 summary-card">
                                    <div class="card-body">
                                        <h6 class="card-subtitle mb-2">Outstanding Balance</h6>
                                        <h3 class="card-title mb-0">KES <?= number_format(array_sum(array_column($order_status, 'total_balance')), 2) ?></h3>
                                        <small>Pending payments</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Trends -->
                <div class="card shadow-sm mb-4" data-report="order_sales">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Order Trends</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8 mb-4">
                                <canvas id="orderTrendChart"></canvas>
                            </div>
                            <div class="col-md-4 mb-4">
                                <canvas id="paymentStatusChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="card shadow-sm mb-4" data-report="order_sales">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Recent Orders</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Date</th>
                                        <th>Cashier</th>
                                        <th>Total</th>
                                        <th>Deposit</th>
                                        <th>Balance</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recent_orders as $order): ?>
                                    <tr>
                                        <td>#<?= $order['id'] ?></td>
                                        <td><?= date('M j, Y g:i A', strtotime($order['date_created'])) ?></td>
                                        <td><?= htmlspecialchars($order['cashier_name']) ?></td>
                                        <td>KES <?= number_format($order['total_amount'], 2) ?></td>
                                        <td>KES <?= number_format($order['deposit'], 2) ?></td>
                                        <td>KES <?= number_format($order['balance'], 2) ?></td>
                                        <td>
                                            <?php
                                            $statusClass = match($order['payment_status']) {
                                                'Fully Paid' => 'bg-success',
                                                'Partially Paid' => 'bg-warning',
                                                default => 'bg-danger'
                                            };
                                            ?>
                                            <span class="badge <?= $statusClass ?>"><?= $order['payment_status'] ?></span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Print Styles -->
<style media="print">
    @page {
        size: landscape;
        margin: 1.5cm;
    }
    
    /* Hide navigation and controls */
    nav,
    footer,
    .no-print,
    .form-control,
    select,
    button {
        display: none !important;
    }

    /* Basic styling */
    body {
        padding: 0 !important;
        margin: 0 !important;
    }

    .container-fluid {
        padding: 0 !important;
        margin: 0 !important;
    }

    /* Print header styling */
    .print-header {
        text-align: center;
        margin-bottom: 2cm;
    }

    .print-header h1 {
        font-size: 24pt;
        margin-bottom: 0.5cm;
        color: black;
    }

    .print-header p {
        font-size: 12pt;
        color: #666;
    }

    /* Hide non-relevant report sections */
    [data-report]:not([data-report-type="current"]) {
        display: none !important;
    }

    /* Card styling */
    .card {
        border: none !important;
        box-shadow: none !important;
        break-inside: avoid;
        margin-bottom: 1cm !important;
        page-break-inside: avoid;
        background: white !important;
    }

    .card-header {
        background: none !important;
        border-bottom: 2px solid #000 !important;
        padding: 0.5cm 0 !important;
    }

    /* Table styling */
    .table {
        width: 100% !important;
        border-collapse: collapse !important;
        margin-bottom: 1cm !important;
        color: black !important;
    }

    .table th {
        background-color: #f8f9fa !important;
        border-bottom: 2px solid #000 !important;
        color: black !important;
    }

    .table td, .table th {
        padding: 0.3cm !important;
        border: 1px solid #dee2e6 !important;
    }

    /* Summary cards */
    .summary-card {
        break-inside: avoid;
        padding: 0.5cm !important;
        margin-bottom: 0.5cm !important;
        border: 1px solid #000 !important;
        background: white !important;
    }

    /* Chart containers */
    canvas {
        max-height: 15cm !important;
        margin-bottom: 1cm !important;
    }

    /* Status indicators */
    .badge {
        border: 1px solid #000 !important;
        padding: 0.2cm 0.4cm !important;
        color: black !important;
        background: white !important;
    }

    /* Remove backgrounds and set text to black */
    .bg-primary, .bg-success, .bg-info, .bg-warning,
    .bg-primary *, .bg-success *, .bg-info *, .bg-warning * {
        background: white !important;
        color: black !important;
        border: 1px solid #000 !important;
    }

    /* Force black text */
    * {
        color: black !important;
    }
}
</style>

<!-- Print Script -->
<script>
function printReport() {
    // Get the current report type
    const reportType = document.querySelector('select[name="type"]').value;
    const reportTitle = document.querySelector('select[name="type"] option:checked').text;
    
    // Mark the current report sections
    document.querySelectorAll('[data-report]').forEach(section => {
        if (section.getAttribute('data-report') === reportType) {
            section.setAttribute('data-report-type', 'current');
        } else {
            section.removeAttribute('data-report-type');
        }
    });

    // Create and insert print header
    const header = document.createElement('div');
    header.className = 'print-header';
    header.innerHTML = `
        <h1>LIMUAA - ${reportTitle}</h1>
        <p>
            Period: ${document.querySelector('input[name="start_date"]').value} to ${document.querySelector('input[name="end_date"]').value}<br>
            Generated on: ${new Date().toLocaleString()}
        </p>
    `;
    document.querySelector('.report-content').insertBefore(header, document.querySelector('.report-content').firstChild);

    // Print the document
    window.print();

    // Cleanup after printing
    setTimeout(() => {
        header.remove();
        document.querySelectorAll('[data-report]').forEach(section => {
            section.removeAttribute('data-report-type');
        });
    }, 1000);
}

document.addEventListener('DOMContentLoaded', function() {
    <?php if ($report_type == 'product_sales'): ?>
        // Sales Trend Chart
        const salesTrendCtx = document.getElementById('salesTrendChart').getContext('2d');
        new Chart(salesTrendCtx, {
            type: 'line',
            data: {
                labels: <?= json_encode(array_column($sales_trend, 'date')) ?>,
                datasets: [{
                    label: 'Revenue',
                    data: <?= json_encode(array_column($sales_trend, 'total_sales')) ?>,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1,
                    yAxisID: 'y'
                }, {
                    label: 'Items Sold',
                    data: <?= json_encode(array_column($sales_trend, 'items_sold')) ?>,
                    borderColor: 'rgb(255, 99, 132)',
                    tension: 0.1,
                    yAxisID: 'y1'
                }]
            },
            options: {
                responsive: true,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                stacked: false,
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Revenue (KES)'
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        grid: {
                            drawOnChartArea: false,
                        },
                        title: {
                            display: true,
                            text: 'Items Sold'
                        }
                    }
                }
            }
        });

        // Hourly Distribution Chart
        const hourlyCtx = document.getElementById('hourlyDistributionChart').getContext('2d');
        new Chart(hourlyCtx, {
            type: 'bar',
            data: {
                labels: <?= json_encode(array_column($hourly_sales, 'hour_of_day')) ?>.map(hour => `${hour}:00`),
                datasets: [{
                    label: 'Sales',
                    data: <?= json_encode(array_column($hourly_sales, 'total_sales')) ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgb(75, 192, 192)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Sales Distribution by Hour'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Sales (KES)'
                        }
                    }
                }
            }
        });
    <?php endif; ?>

    <?php if ($report_type == 'order_sales'): ?>
        // Order Trend Chart
        const orderTrendCtx = document.getElementById('orderTrendChart').getContext('2d');
        new Chart(orderTrendCtx, {
            type: 'line',
            data: {
                labels: <?= json_encode(array_column($orders_trend, 'order_date')) ?>,
                datasets: [{
                    label: 'Daily Orders',
                    data: <?= json_encode(array_column($orders_trend, 'order_count')) ?>,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1,
                    yAxisID: 'y'
                }, {
                    label: 'Daily Revenue',
                    data: <?= json_encode(array_column($orders_trend, 'daily_total')) ?>,
                    borderColor: 'rgb(255, 99, 132)',
                    tension: 0.1,
                    yAxisID: 'y1'
                }]
            },
            options: {
                responsive: true,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                stacked: false,
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Number of Orders'
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        grid: {
                            drawOnChartArea: false,
                        },
                        title: {
                            display: true,
                            text: 'Revenue (KES)'
                        }
                    }
                }
            }
        });

        // Payment Status Chart
        const paymentStatusCtx = document.getElementById('paymentStatusChart').getContext('2d');
        new Chart(paymentStatusCtx, {
            type: 'doughnut',
            data: {
                labels: <?= json_encode(array_column($payment_status, 'payment_status')) ?>,
                datasets: [{
                    data: <?= json_encode(array_column($payment_status, 'order_count')) ?>,
                    backgroundColor: [
                        'rgb(75, 192, 192)',  // Fully Paid
                        'rgb(255, 205, 86)',  // Partially Paid
                        'rgb(255, 99, 132)'   // Unpaid
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Payment Status Distribution'
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    <?php endif; ?>
});
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>