<?php 

if(!Auth::logged_in()) {
    $_SESSION['error'] = 'Please login to view the admin section';
    redirect('login');
}

if(!Auth::access('admin') && !Auth::access('supervisor') && !Auth::access('cashier')){
    $_SESSION['error'] = "You don't have access to edit orders";
    require views_path('auth/denied');
    die;
}

$id = $_GET['id'] ?? null;
$order = new Orders();
$row = $order->where(['id' => $id]);
$row = $row[0] ?? false;

// Check if order exists
if($row) {
    // If order status is picked, only admin can edit
    if($row['status'] == 'picked' && !Auth::access('admin')) {
        $_SESSION['error'] = "Only admin can edit orders that have been picked up";
        redirect('admin&tab=orders');
    }
} else {
    $_SESSION['error'] = "Order not found";
    redirect('admin&tab=orders');
}

$errors = [];

if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($id)) {
    // Recheck permissions before saving (in case status changed during edit)
    $current_order = $order->first(['id' => $id]);
    if($current_order && $current_order['status'] == 'picked' && !Auth::access('admin')) {
        $_SESSION['error'] = "Only admin can edit orders that have been picked up";
        redirect('admin&tab=orders');
    }
    
    // Calculate balance
    if(isset($_POST['total_amount'])) {
        $_POST['balance'] = $_POST['total_amount'] - ($_POST['deposit'] ?? 0);
    }

    if($order->validate($_POST)) {
        $folder = "uploads/orders/";
        if(!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }

        // Handle image uploads
        if(!empty($_FILES['image1']['name'])) {
            $_POST['image1'] = $folder . time() . $_FILES['image1']['name'];
            move_uploaded_file($_FILES['image1']['tmp_name'], $_POST['image1']);

            // Delete old image if exists
            if(!empty($row['image1']) && file_exists($row['image1'])) {
                unlink($row['image1']);
            }
        }

        if(!empty($_FILES['image2']['name'])) {
            $_POST['image2'] = $folder . time() . $_FILES['image2']['name'];
            move_uploaded_file($_FILES['image2']['tmp_name'], $_POST['image2']);

            // Delete old image if exists
            if(!empty($row['image2']) && file_exists($row['image2'])) {
                unlink($row['image2']);
            }
        }

        $_POST['updated_by'] = Auth::get('id');
        $_POST['date_updated'] = date("Y-m-d H:i:s");

        $order->update($id, $_POST);
        $_SESSION['success'] = "Order updated successfully";
        redirect('admin&tab=orders');
    } else {
        $errors = $order->errors;
    }
}

require views_path('admin/order-edit');
