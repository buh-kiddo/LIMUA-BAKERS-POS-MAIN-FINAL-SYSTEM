<?php require views_path('partials/header');?>

<div class="container-fluid p-4 shadow mx-auto" style="max-width: 1000px;">
    <h4>Edit Order</h4>

    <?php if(!empty($errors)):?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Please fix the errors below</strong>
            <?php foreach($errors as $error): ?>
                <div><?=$error?></div>
            <?php endforeach; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif;?>

    <form method="post" enctype="multipart/form-data">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0"><i class="fa fa-edit"></i> Edit Order #<?=str_pad($row['id'], 5, '0', STR_PAD_LEFT)?></h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <!-- Order Details -->
                        <div class="mb-3">
                            <label for="order_name">Order Name</label>
                            <input name="order_name" type="text" class="form-control <?=!empty($errors['order_name']) ? 'border-danger' : ''?>" id="order_name" value="<?=set_value('order_name', $row['order_name'] ?? '')?>" autofocus>
                            <?php if(!empty($errors['order_name'])):?>
                                <small class="text-danger"><?=$errors['order_name']?></small>
                            <?php endif;?>
                        </div>

                        <div class="mb-3">
                            <label for="description">Description</label>
                            <textarea name="description" class="form-control" id="description" rows="4"><?=set_value('description', $row['description'] ?? '')?></textarea>
                        </div>

                        <!-- Customer Information -->
                        <div class="mb-3">
                            <label for="customer_name">Customer Name</label>
                            <input name="customer_name" type="text" class="form-control" id="customer_name" value="<?=set_value('customer_name', $row['customer_name'] ?? '')?>">
                        </div>

                        <div class="mb-3">
                            <label for="phone_number">Customer Phone</label>
                            <input name="phone_number" type="text" class="form-control <?=!empty($errors['phone_number']) ? 'border-danger' : ''?>" id="phone_number" value="<?=set_value('phone_number', $row['phone_number'] ?? '')?>">
                            <?php if(!empty($errors['phone_number'])):?>
                                <small class="text-danger"><?=$errors['phone_number']?></small>
                            <?php endif;?>
                        </div>

                        <!-- Financial Information -->
                        <div class="mb-3">
                            <label for="total_amount">Total Amount</label>
                            <input name="total_amount" type="number" class="form-control <?=!empty($errors['total_amount']) ? 'border-danger' : ''?>" id="total_amount" step="0.01" value="<?=set_value('total_amount', $row['total_amount'] ?? '')?>">
                            <?php if(!empty($errors['total_amount'])):?>
                                <small class="text-danger"><?=$errors['total_amount']?></small>
                            <?php endif;?>
                        </div>

                        <div class="mb-3">
                            <label for="deposit">Deposit Amount</label>
                            <input name="deposit" type="number" class="form-control <?=!empty($errors['deposit']) ? 'border-danger' : ''?>" id="deposit" step="0.01" value="<?=set_value('deposit', $row['deposit'] ?? '')?>">
                            <?php if(!empty($errors['deposit'])):?>
                                <small class="text-danger"><?=$errors['deposit']?></small>
                            <?php endif;?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Schedule Information -->
                        <div class="mb-3">
                            <label for="pickup_date">Pickup Date & Time</label>
                            <input name="pickup_date" type="datetime-local" class="form-control <?=!empty($errors['pickup_date']) ? 'border-danger' : ''?>" id="pickup_date" value="<?=set_value('pickup_date', date('Y-m-d\TH:i', strtotime($row['pickup_date'] ?? '')))?>">
                            <?php if(!empty($errors['pickup_date'])):?>
                                <small class="text-danger"><?=$errors['pickup_date']?></small>
                            <?php endif;?>
                        </div>

                        <!-- Status -->
                        <div class="mb-3">
                            <label for="status">Status</label>
                            <select name="status" class="form-select <?=!empty($errors['status']) ? 'border-danger' : ''?>" id="status">
                                <option value="pending" <?=$row['status'] == 'pending' ? 'selected' : ''?>>Pending</option>
                                <option value="in_progress" <?=$row['status'] == 'in_progress' ? 'selected' : ''?>>In Progress</option>
                                <option value="completed" <?=$row['status'] == 'completed' ? 'selected' : ''?>>Completed</option>
                                <option value="picked" <?=$row['status'] == 'picked' ? 'selected' : ''?>>Picked</option>
                                <option value="cancelled" <?=$row['status'] == 'cancelled' ? 'selected' : ''?>>Cancelled</option>
                            </select>
                            <?php if(!empty($errors['status'])):?>
                                <small class="text-danger"><?=$errors['status']?></small>
                            <?php endif;?>
                        </div>

                        <!-- Images -->
                        <div class="mb-3">
                            <label for="image1">Image 1</label>
                            <input type="file" name="image1" class="form-control" id="image1">
                            <?php if(!empty($row['image1'])):?>
                                <img src="<?=$row['image1']?>" class="mt-2" style="max-width: 100%; max-height: 150px;">
                            <?php endif;?>
                        </div>

                        <div class="mb-3">
                            <label for="image2">Image 2</label>
                            <input type="file" name="image2" class="form-control" id="image2">
                            <?php if(!empty($row['image2'])):?>
                                <img src="<?=$row['image2']?>" class="mt-2" style="max-width: 100%; max-height: 150px;">
                            <?php endif;?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Update Order</button>
                <a href="index.php?pg=admin&tab=orders" class="btn btn-secondary">Cancel</a>
            </div>
        </div>
    </form>
</div>

<?php require views_path('partials/footer');?>
