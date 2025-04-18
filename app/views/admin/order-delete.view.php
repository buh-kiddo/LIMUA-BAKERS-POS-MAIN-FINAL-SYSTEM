<?php require views_path('partials/header');?>

<div class="container-fluid p-4 shadow mx-auto" style="max-width: 1000px;">
    <div class="card border-danger">
        <div class="card-header bg-danger text-white">
            <h4>Delete Order</h4>
        </div>
        <div class="card-body">
            <?php if(!empty($row)):?>
                <div class="alert alert-danger">
                    <h5>Are you sure you want to delete this order?</h5>
                    <p><strong>Order Name:</strong> <?=$row['order_name']?></p>
                    <p><strong>Customer:</strong> <?=$row['phone_number']?></p>
                    <p><strong>Total Amount:</strong> KES <?=number_format($row['total_amount'], 2)?></p>
                </div>

                <form method="post">
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                    <a href="index.php?pg=admin&tab=orders" class="btn btn-secondary">Cancel</a>
                </form>
            <?php else:?>
                <div class="alert alert-danger">
                    <p>Order not found!</p>
                </div>
                <a href="index.php?pg=admin&tab=orders" class="btn btn-secondary">Back to Orders</a>
            <?php endif;?>
        </div>
    </div>
</div>

<?php require views_path('partials/footer');?>
