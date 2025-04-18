<?php require views_path('partials/print_header');?>

<style>
    @media print {
        body {
            padding: 0;
            margin: 0;
            font-size: 12px;
        }
        .no-print {
            display: none;
        }
        .container-fluid {
            padding: 0 !important;
            margin: 0 !important;
            box-shadow: none !important;
            width: 100% !important;
            max-width: none !important;
        }
        .card {
            border: none !important;
            margin: 0 !important;
            padding: 15px !important;
        }
        .card-body {
            padding: 0 !important;
        }
        h1 { font-size: 24px !important; }
        h2 { font-size: 18px !important; }
        h4 { font-size: 14px !important; }
        p { margin: 2px 0 !important; }
        .receipt-section { margin-bottom: 15px !important; }
        .info-table th, .info-table td { padding: 4px 8px !important; }
        .amount-table th, .amount-table td { padding: 4px 8px !important; }
        .receipt-header { margin-bottom: 15px !important; padding-bottom: 10px !important; }
        .receipt-footer { margin-top: 15px !important; padding-top: 10px !important; }
        .mb-4 { margin-bottom: 10px !important; }
        .mb-3 { margin-bottom: 8px !important; }
        .mt-3 { margin-top: 8px !important; }
    }

    .receipt-header {
        text-align: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #ddd;
    }

    .receipt-header h1 {
        color: #2c3e50;
        margin-bottom: 5px;
        font-size: 24px;
    }

    .receipt-header p {
        color: #7f8c8d;
        margin: 0;
        font-size: 12px;
        line-height: 1.3;
    }

    .receipt-body {
        margin-bottom: 20px;
    }

    .receipt-section {
        margin-bottom: 20px;
    }

    .receipt-section h4 {
        color: #2c3e50;
        border-bottom: 1px solid #eee;
        padding-bottom: 8px;
        margin-bottom: 10px;
        font-size: 14px;
    }

    .info-table {
        width: 100%;
        margin-bottom: 15px;
        font-size: 12px;
    }

    .info-table th {
        color: #34495e;
        font-weight: 600;
        padding: 4px 8px;
        width: 35%;
        vertical-align: top;
    }

    .info-table td {
        color: #555;
        padding: 4px 8px;
    }

    .amount-table {
        width: 100%;
        margin-top: 10px;
        font-size: 12px;
    }

    .amount-table th {
        background-color: #f8f9fa;
        padding: 6px 10px;
        font-weight: 600;
    }

    .amount-table td {
        padding: 6px 10px;
        border-top: 1px solid #eee;
    }

    .amount-table .total-row {
        font-weight: bold;
        font-size: 13px;
        background-color: #f8f9fa;
    }

    .receipt-footer {
        text-align: center;
        margin-top: 20px;
        padding-top: 15px;
        border-top: 2px solid #ddd;
    }

    .receipt-footer p {
        color: #7f8c8d;
        margin: 2px 0;
        font-size: 11px;
        line-height: 1.3;
    }

    .status-badge {
        font-size: 12px;
        padding: 4px 12px;
        border-radius: 12px;
    }

    .order-images {
        margin-top: 10px;
    }

    .order-images img {
        max-width: 100%;
        height: auto;
        max-height: 150px;
        border-radius: 4px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    /* Compact layout for print */
    @page {
        margin: 10mm;
        size: A4;
    }
</style>

<div class="container-fluid shadow mx-auto">
    <?php if(!empty($row)):?>
        <div class="card">
            <div class="card-body">
                <!-- Receipt Header -->
                <div class="receipt-header">
                    <h1>LIMUA BAKERS</h1>
                    <p>Professional Cake & Pastry Services</p>
                    <p>You Crave, We Deliver</p>
                    <p>Phone: +254 769 094 030</p>
                    <p>Email: info@limuabakers.com</p>
                    <p>Location: Wote, Makueni</p>
                </div>

                <!-- Receipt Title -->
                <div class="text-center mb-3">
                    <h2 class="mb-2">Order Receipt #<?=str_pad($row['id'], 5, '0', STR_PAD_LEFT)?></h2>
                    <?php if($row['status'] == 'completed' || $row['status'] == 'picked'): ?>
                        <span class="badge bg-success status-badge">FINAL RECEIPT</span>
                    <?php else: ?>
                        <span class="badge bg-warning status-badge">PENDING ORDER</span>
                    <?php endif; ?>
                </div>

                <div class="receipt-body">
                    <div class="row">
                        <div class="col-6">
                            <!-- Customer Information -->
                            <div class="receipt-section">
                                <h4>Customer Information</h4>
                                <table class="info-table">
                                    <tr>
                                        <th>Customer Name:</th>
                                        <td><?=!empty($row['customer_name']) ? $row['customer_name'] : 'Not provided'?></td>
                                    </tr>
                                    <tr>
                                        <th>Phone Number:</th>
                                        <td><?=$row['phone_number']?></td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Order Details -->
                            <div class="receipt-section">
                                <h4>Order Details</h4>
                                <table class="info-table">
                                    <tr>
                                        <th>Order Name:</th>
                                        <td><?=$row['order_name']?></td>
                                    </tr>
                                    <?php if(!empty($row['description'])): ?>
                                    <tr>
                                        <th>Description:</th>
                                        <td><?=nl2br($row['description'])?></td>
                                    </tr>
                                    <?php endif; ?>
                                    <tr>
                                        <th>Pickup Date:</th>
                                        <td><?=!empty($row['pickup_date']) ? get_date($row['pickup_date']) : 'Not Set'?></td>
                                    </tr>
                                    <tr>
                                        <th>Status:</th>
                                        <td>
                                            <span class="badge bg-<?php
                                                switch($row['status']) {
                                                    case 'completed': echo 'success'; break;
                                                    case 'in_progress': echo 'primary'; break;
                                                    case 'picked': echo 'info'; break;
                                                    case 'cancelled': echo 'danger'; break;
                                                    default: echo 'warning';
                                                }
                                            ?>">
                                                <?=ucfirst($row['status'])?>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Created By:</th>
                                        <td><?=$row['creator_username'] ?? 'Unknown'?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="col-6">
                            <!-- Payment Information -->
                            <div class="receipt-section">
                                <h4>Payment Details</h4>
                                <table class="amount-table">
                                    <tr>
                                        <td>Total Amount</td>
                                        <td class="text-end">KES <?=number_format($row['total_amount'], 2)?></td>
                                    </tr>
                                    <tr>
                                        <td>Deposit Paid</td>
                                        <td class="text-end">KES <?=number_format($row['deposit'], 2)?></td>
                                    </tr>
                                    <tr class="total-row">
                                        <td>Balance Due</td>
                                        <td class="text-end">KES <?=number_format($row['balance'], 2)?></td>
                                    </tr>
                                </table>
                            </div>

                            <?php if(!empty($row['image1']) || !empty($row['image2'])): ?>
                            <!-- Order Images -->
                            <div class="receipt-section">
                                <h4>Order Images</h4>
                                <div class="row order-images">
                                    <?php if(!empty($row['image1'])): ?>
                                        <div class="col-6">
                                            <img src="<?=$row['image1']?>" class="img-fluid" alt="Order Image 1">
                                        </div>
                                    <?php endif; ?>
                                    <?php if(!empty($row['image2'])): ?>
                                        <div class="col-6">
                                            <img src="<?=$row['image2']?>" class="img-fluid" alt="Order Image 2">
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Receipt Footer -->
                <div class="receipt-footer">
                    <p><strong>Created By:</strong> <?=$row['creator_username'] ?? 'Unknown'?> | <strong>Date:</strong> <?=get_date($row['date_created'])?></p>
                    <p class="mt-2">Thank you for choosing Limua Bakers!</p>
                    <p>For any queries, please contact us at +254 769 094 030</p>
                    <p>Terms and Conditions Apply</p>
                </div>
            </div>
        </div>

        <!-- Print Button - Hidden when printing -->
        <div class="text-center mt-4 no-print">
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fa fa-print"></i> Print Receipt
            </button>
            <a href="index.php?pg=admin&tab=orders" class="btn btn-secondary">
                <i class="fa fa-chevron-left"></i> Back to Orders
            </a>
        </div>
    <?php else:?>
        <div class="alert alert-danger">
            <p>Order not found!</p>
        </div>
        <a href="index.php?pg=admin&tab=orders" class="btn btn-secondary">Back to Orders</a>
    <?php endif;?>
</div>

<script>
    // Auto-print when the page loads
    window.onload = function() {
        window.print();
    }
</script>

<?php require views_path('partials/print_footer');?>
