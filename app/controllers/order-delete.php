<?php 

if(!Auth::logged_in())
{
    $_SESSION['error'] = 'Please login to view the admin section';
    redirect('login');
}

// Only allow admin and supervisor to delete orders
if(!Auth::access('admin') && !Auth::access('supervisor')){
    $_SESSION['error'] = "Only admin and supervisor can delete orders";
    require views_path('auth/denied');
    die;
}

$id = $_GET['id'] ?? null;
$order = new Orders();

// Fix: Pass conditions as an array
$row = $order->where(['id' => $id]);
$row = $row[0] ?? false;

if($_SERVER['REQUEST_METHOD'] == "POST" && $row)
{
    // Delete associated images
    if(!empty($row['image1']) && file_exists($row['image1']))
    {
        unlink($row['image1']);
    }
    if(!empty($row['image2']) && file_exists($row['image2']))
    {
        unlink($row['image2']);
    }

    $order->delete($id);
    $_SESSION['success'] = "Order deleted successfully";
    redirect('admin&tab=orders');
}

require views_path('admin/order-delete');
