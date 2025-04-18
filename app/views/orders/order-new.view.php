<?php require_once("../app/pages/includes/header.php") ?>

<div class="container-fluid border rounded p-4 m-2 col-lg-4">

    <form method="post" enctype="multipart/form-data">

        <h5 class="text-primary"><i class="fa fa-shopping-cart"></i> New Order</h5>

        <div class="mb-3">
            <label for="orderNameInput" class="form-label">Order Name</label>
            <input name="order_name" type="text" class="form-control <?=!empty($errors['order_name']) ? 'border-danger':''?>" id="orderNameInput" placeholder="Order Name">
            <?php if(!empty($errors['order_name'])):?>
                <small class="text-danger"><?=$errors['order_name']?></small>
            <?php endif;?>
        </div>

        <div class="mb-3">
            <label for="customerNameInput" class="form-label">Customer Name</label>
            <input name="customer_name" type="text" class="form-control <?=!empty($errors['customer_name']) ? 'border-danger':''?>" id="customerNameInput" placeholder="Customer Name">
            <?php if(!empty($errors['customer_name'])):?>
                <small class="text-danger"><?=$errors['customer_name']?></small>
            <?php endif;?>
        </div>

        <div class="mb-3">
            <label for="descriptionInput" class="form-label">Order Description</label>
            <textarea name="order_description" class="form-control <?=!empty($errors['order_description']) ? 'border-danger':''?>" id="descriptionInput" rows="3" placeholder="Order Details"></textarea>
            <?php if(!empty($errors['order_description'])):?>
                <small class="text-danger"><?=$errors['order_description']?></small>
            <?php endif;?>
        </div>

        <div class="mb-3">
            <label for="totalAmountInput" class="form-label">Total Amount</label>
            <input name="total_amount" type="number" step="0.01" class="form-control <?=!empty($errors['total_amount']) ? 'border-danger':''?>" id="totalAmountInput" placeholder="0.00">
            <?php if(!empty($errors['total_amount'])):?>
                <small class="text-danger"><?=$errors['total_amount']?></small>
            <?php endif;?>
        </div>

        <div class="mb-3">
            <label for="depositInput" class="form-label">Deposit Amount</label>
            <input name="deposit_paid" type="number" step="0.01" class="form-control <?=!empty($errors['deposit_paid']) ? 'border-danger':''?>" id="depositInput" placeholder="0.00">
            <?php if(!empty($errors['deposit_paid'])):?>
                <small class="text-danger"><?=$errors['deposit_paid']?></small>
            <?php endif;?>
        </div>

        <div class="mb-3">
            <label for="pickupTimeInput" class="form-label">Pick Up Time</label>
            <input name="pick_up_time" type="datetime-local" class="form-control <?=!empty($errors['pick_up_time']) ? 'border-danger':''?>" id="pickupTimeInput">
            <?php if(!empty($errors['pick_up_time'])):?>
                <small class="text-danger"><?=$errors['pick_up_time']?></small>
            <?php endif;?>
        </div>

        <div class="mb-3">
            <label for="image1" class="form-label">Reference Image 1</label>
            <input name="image1" type="file" class="form-control <?=!empty($errors['image1']) ? 'border-danger':''?>" id="image1">
            <?php if(!empty($errors['image1'])):?>
                <small class="text-danger"><?=$errors['image1']?></small>
            <?php endif;?>
        </div>

        <div class="mb-3">
            <label for="image2" class="form-label">Reference Image 2</label>
            <input name="image2" type="file" class="form-control <?=!empty($errors['image2']) ? 'border-danger':''?>" id="image2">
            <?php if(!empty($errors['image2'])):?>
                <small class="text-danger"><?=$errors['image2']?></small>
            <?php endif;?>
        </div>

        <div class="d-grid gap-2">
            <button class="btn btn-primary" type="submit">Save Order</button>
        </div>
    </form>

    <div class="mt-3">
        <a href="index.php?pg=admin&tab=orders">
            <button type="button" class="btn btn-secondary">Back to Orders</button>
        </a>
    </div>
</div>

<?php require_once("../app/pages/includes/footer.php") ?>
