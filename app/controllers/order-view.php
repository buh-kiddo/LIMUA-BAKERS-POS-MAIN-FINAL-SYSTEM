<?php 

if(!Auth::logged_in())
{
    message('please login to view the admin section');
    redirect('login');
}

if(!Auth::access('supervisor') && !Auth::access('cashier')){
    Auth::setMessage("You dont have access to view orders");
    require views_path('auth/denied');
    die;
}

$id = $_GET['id'] ?? null;
$order = new Orders();

// Get order with user details
$query = "SELECT orders.*, users.username as creator_username 
          FROM orders 
          LEFT JOIN users ON orders.created_by = users.id 
          WHERE orders.id = ?";
$row = $order->query($query, [$id]);
$row = $row[0] ?? false;

require views_path('admin/order-view');
