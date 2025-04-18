<?php require views_path('partials/header');?>

<div class="container-fluid p-4 shadow mx-auto" style="max-width: 1000px;">
    <?php if(!empty($row)):?>
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">Order Details #<?=str_pad($row['id'], 5, '0', STR_PAD_LEFT)?></h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-hover">
                                    <tbody>
                                        <tr>
                                            <th style="width: 35%">Order Name:</th>
                                            <td><?=$row['order_name']?></td>
                                        </tr>
                                        <tr>
                                            <th>Description:</th>
                                            <td><?=!empty($row['description']) ? nl2br($row['description']) : 'No description'?></td>
                                        </tr>
                                        <tr>
                                            <th>Customer Name:</th>
                                            <td><?=!empty($row['customer_name']) ? $row['customer_name'] : 'Not provided'?></td>
                                        </tr>
                                        <tr>
                                            <th>Customer Phone:</th>
                                            <td><?=$row['phone_number']?></td>
                                        </tr>
                                        <tr>
                                            <th>Total Amount:</th>
                                            <td>KES <?=number_format($row['total_amount'], 2)?></td>
                                        </tr>
                                        <tr>
                                            <th>Deposit Paid:</th>
                                            <td>KES <?=number_format($row['deposit'], 2)?></td>
                                        </tr>
                                        <tr>
                                            <th>Balance:</th>
                                            <td>KES <?=number_format($row['balance'], 2)?></td>
                                        </tr>
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
                                        <?php if(!empty($row['updated_by'])): ?>
                                        <tr>
                                            <th>Updated By:</th>
                                            <td><?=$row['updated_by']?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <tr>
                                            <th>Created Date:</th>
                                            <td><?=get_date($row['date_created'])?></td>
                                        </tr>
                                        <?php if(!empty($row['date_updated'])): ?>
                                        <tr>
                                            <th>Last Updated:</th>
                                            <td><?=get_date($row['date_updated'])?></td>
                                        </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <?php if(!empty($row['image1']) || !empty($row['image2']) || !empty($row['images'])): ?>
                                    <h5 class="mb-3">Order Images</h5>
                                    <div class="row">
                                        <?php if(!empty($row['image1'])): ?>
                                            <div class="col-md-6 mb-3">
                                                <img src="<?=$row['image1']?>" class="img-fluid img-thumbnail" alt="Order Image 1">
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if(!empty($row['image2'])): ?>
                                            <div class="col-md-6 mb-3">
                                                <img src="<?=$row['image2']?>" class="img-fluid img-thumbnail" alt="Order Image 2">
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php 
                                        if(!empty($row['images'])):
                                            $additional_images = json_decode($row['images'], true);
                                            if(is_array($additional_images)):
                                                foreach($additional_images as $image): 
                                        ?>
                                            <div class="col-md-6 mb-3">
                                                <img src="<?=$image?>" class="img-fluid img-thumbnail" alt="Additional Order Image">
                                            </div>
                                        <?php 
                                                endforeach;
                                            endif;
                                        endif; 
                                        ?>
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted">No images uploaded for this order</p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="mt-4">
                            <a href="index.php?pg=order-edit&id=<?=$row['id']?>" class="btn btn-info text-white">
                                <i class="fa fa-edit"></i> Edit Order
                            </a>
                            <a href="index.php?pg=order-print&id=<?=$row['id']?>&type=<?=($row['status'] == 'picked' || $row['status'] == 'completed') ? 'final' : 'pending'?>" class="btn btn-secondary" target="_blank">
                                <i class="fa fa-print"></i> Print Receipt
                            </a>
                            <a href="index.php?pg=order-delete&id=<?=$row['id']?>" class="btn btn-danger">
                                <i class="fa fa-trash"></i> Delete Order
                            </a>
                            <a href="index.php?pg=admin&tab=orders" class="btn btn-primary">
                                <i class="fa fa-arrow-left"></i> Back to Orders
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php else:?>
        <div class="alert alert-danger">
            <p>Order not found!</p>
        </div>
        <a href="index.php?pg=admin&tab=orders" class="btn btn-secondary">Back to Orders</a>
    <?php endif;?>
</div>

<?php require views_path('partials/footer');?>
