<?php

if(!Auth::logged_in()) {
    message('Please login to view the admin section');
    redirect('login');
}

$id = $_GET['id'] ?? null;
$type = $_GET['type'] ?? 'pending';

if($id) {
    $order = new Orders();

    // Get order with creator username
    $query = "SELECT orders.*, users.username as creator_username 
              FROM orders 
              LEFT JOIN users ON orders.created_by = users.id 
              WHERE orders.id = ?";
    $row = $order->query($query, [$id]);
    $row = $row[0] ?? false;

    if($row) {
        $data = [
            'row' => $row,
            'receipt_type' => $type,
        ];
        
        extract($data);
        require views_path('admin/order-print');
    } else {
        message("Order not found");
        redirect('admin&tab=orders');
    }
} else {
    message("Order ID is required");
    redirect('admin&tab=orders');
}
