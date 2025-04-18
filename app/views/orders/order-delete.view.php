<?php require_once("../app/pages/includes/header.php") ?>

<div class="container-fluid border rounded p-4 m-2 col-lg-4">

    <?php if(!empty($row)):?>
        <form method="post">

            <h5 class="text-primary"><i class="fa fa-shopping-cart"></i> Delete Order</h5>

            <div class="alert alert-danger text-center">Are you sure you want to delete this order?</div>

            <div class="mb-3">
                <label class="form-label">Order Number</label>
                <input disabled value="<?=$row['order_number']?>" type="text" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Order Name</label>
                <input disabled value="<?=$row['order_name']?>" type="text" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Customer Name</label>
                <input disabled value="<?=$row['customer_name']?>" type="text" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Total Amount</label>
                <input disabled value="<?=$row['total_amount']?>" type="text" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Pick Up Time</label>
                <input disabled value="<?=date('Y-m-d H:i', strtotime($row['pick_up_time']))?>" type="text" class="form-control">
            </div>

            <?php if(!empty($row['image1']) || !empty($row['image2'])):?>
                <div class="mb-3">
                    <label class="form-label">Reference Images</label>
                    <div class="d-flex gap-2">
                        <?php if(!empty($row['image1'])):?>
                            <img src="<?=$row['image1']?>" style="max-width: 200px;">
                        <?php endif;?>
                        <?php if(!empty($row['image2'])):?>
                            <img src="<?=$row['image2']?>" style="max-width: 200px;">
                        <?php endif;?>
                    </div>
                </div>
            <?php endif;?>

            <div class="d-grid gap-2">
                <button class="btn btn-danger" type="submit">Delete Order</button>
            </div>
        </form>
    <?php else:?>
        <div class="alert alert-danger text-center">That order was not found</div>
    <?php endif;?>

    <div class="mt-3">
        <a href="index.php?pg=admin&tab=orders">
            <button type="button" class="btn btn-secondary">Back to Orders</button>
        </a>
    </div>

</div>

<?php require_once("../app/pages/includes/footer.php") ?>
