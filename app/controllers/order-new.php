<?php 

if(!Auth::logged_in()) {
    $_SESSION['error'] = 'Please login to create an order';
    redirect('login');
}

$errors = [];

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $order = new Orders();
    
    $_POST['date_created'] = date("Y-m-d H:i:s");
    $_POST['created_by'] = Auth::get('id');
    $_POST['status'] = 'pending';
    
    // Calculate balance
    $_POST['balance'] = ($_POST['total_amount'] ?? 0) - ($_POST['deposit'] ?? 0);

    if($order->validate($_POST)) {
        // Handle file uploads
        if(!empty($_FILES['image1']['name']))
        {
            $folder = "uploads/orders/";
            if(!file_exists($folder)) {
                mkdir($folder, 0777, true);
            }
            $allowed = ['image/jpeg','image/png','image/gif'];
            if(in_array($_FILES['image1']['type'], $allowed)) {
                $destination = $folder . time() . "_" . $_FILES['image1']['name'];
                move_uploaded_file($_FILES['image1']['tmp_name'], $destination);
                $_POST['image1'] = $destination;
            }
        }
        
        if(!empty($_FILES['image2']['name']))
        {
            $folder = "uploads/orders/";
            if(!file_exists($folder)) {
                mkdir($folder, 0777, true);
            }
            $allowed = ['image/jpeg','image/png','image/gif'];
            if(in_array($_FILES['image2']['type'], $allowed)) {
                $destination = $folder . time() . "_" . $_FILES['image2']['name'];
                move_uploaded_file($_FILES['image2']['tmp_name'], $destination);
                $_POST['image2'] = $destination;
            }
        }

        $order->insert($_POST);
        $_SESSION['success'] = "Order created successfully";
        redirect('admin&tab=orders');
    } else {
        $errors = $order->errors;
    }
}

require views_path('admin/order-new');
