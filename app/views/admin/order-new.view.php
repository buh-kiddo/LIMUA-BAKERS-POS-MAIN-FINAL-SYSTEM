<?php require views_path('partials/header')?>

<div class="container-fluid p-4 shadow mx-auto" style="max-width: 1000px;">
    <h4>Add New Order</h4>

    <?php if(!empty($errors)):?>
        <div class="alert alert-danger">Please fix the errors below</div>
    <?php endif;?>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title mb-0"><i class="fa fa-plus"></i> New Order</h5>
        </div>
        <div class="card-body">
            <form method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6">
                        <!-- Order Details -->
                        <div class="mb-3">
                            <label>Order Name</label>
                            <input type="text" name="order_name" class="form-control <?=!empty($errors['order_name']) ? 'border-danger' : ''?>" value="<?=set_value('order_name')?>" autofocus>
                            <?php if(!empty($errors['order_name'])):?>
                                <small class="text-danger"><?=$errors['order_name']?></small>
                            <?php endif;?>
                        </div>

                        <div class="mb-3">
                            <label>Description</label>
                            <textarea name="description" class="form-control <?=!empty($errors['description']) ? 'border-danger' : ''?>"><?=set_value('description')?></textarea>
                            <?php if(!empty($errors['description'])):?>
                                <small class="text-danger"><?=$errors['description']?></small>
                            <?php endif;?>
                        </div>

                        <!-- Customer Information -->
                        <div class="mb-3">
                            <label>Customer Name</label>
                            <input type="text" name="customer_name" class="form-control" value="<?=set_value('customer_name')?>">
                        </div>

                        <div class="mb-3">
                            <label>Phone Number</label>
                            <input type="text" name="phone_number" class="form-control <?=!empty($errors['phone_number']) ? 'border-danger' : ''?>" value="<?=set_value('phone_number')?>">
                            <?php if(!empty($errors['phone_number'])):?>
                                <small class="text-danger"><?=$errors['phone_number']?></small>
                            <?php endif;?>
                        </div>

                        <!-- Financial Information -->
                        <div class="mb-3">
                            <label>Total Amount</label>
                            <input type="number" name="total_amount" class="form-control <?=!empty($errors['total_amount']) ? 'border-danger' : ''?>" step="0.01" value="<?=set_value('total_amount')?>">
                            <?php if(!empty($errors['total_amount'])):?>
                                <small class="text-danger"><?=$errors['total_amount']?></small>
                            <?php endif;?>
                        </div>

                        <div class="mb-3">
                            <label>Deposit Amount</label>
                            <input type="number" name="deposit" class="form-control" step="0.01" value="<?=set_value('deposit')?>">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Schedule Information -->
                        <div class="mb-3">
                            <label>Pickup Date & Time</label>
                            <input type="datetime-local" name="pickup_date" class="form-control <?=!empty($errors['pickup_date']) ? 'border-danger' : ''?>" value="<?=set_value('pickup_date')?>">
                            <?php if(!empty($errors['pickup_date'])):?>
                                <small class="text-danger"><?=$errors['pickup_date']?></small>
                            <?php endif;?>
                        </div>

                        <!-- Status -->
                        <div class="mb-3">
                            <label>Status</label>
                            <select name="status" class="form-select <?=!empty($errors['status']) ? 'border-danger' : ''?>">
                                <option value="pending" <?=set_value('status')=='pending' ? 'selected' : ''?>>Pending</option>
                                <option value="processing" <?=set_value('status')=='processing' ? 'selected' : ''?>>Processing</option>
                                <option value="completed" <?=set_value('status')=='completed' ? 'selected' : ''?>>Completed</option>
                                <option value="cancelled" <?=set_value('status')=='cancelled' ? 'selected' : ''?>>Cancelled</option>
                            </select>
                            <?php if(!empty($errors['status'])):?>
                                <small class="text-danger"><?=$errors['status']?></small>
                            <?php endif;?>
                        </div>

                        <!-- Images -->
                        <div class="mb-3">
                            <label>Image 1</label>
                            <input type="file" name="image1" class="form-control <?=!empty($errors['image1']) ? 'border-danger' : ''?>">
                            <?php if(!empty($errors['image1'])):?>
                                <small class="text-danger"><?=$errors['image1']?></small>
                            <?php endif;?>
                        </div>

                        <div class="mb-3">
                            <label>Image 2</label>
                            <input type="file" name="image2" class="form-control <?=!empty($errors['image2']) ? 'border-danger' : ''?>">
                            <?php if(!empty($errors['image2'])):?>
                                <small class="text-danger"><?=$errors['image2']?></small>
                            <?php endif;?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-primary">Save Order</button>
                <a href="index.php?pg=admin&tab=orders" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php require views_path('partials/footer')?>
