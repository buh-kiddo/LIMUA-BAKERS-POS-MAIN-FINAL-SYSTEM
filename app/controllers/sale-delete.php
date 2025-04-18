<?php 

$errors = [];

if(!Auth::logged_in())
{
    message('please login to view the admin section');
    redirect('login');
}

// Only allow admin and supervisor to delete sales
if(!Auth::access('admin') && !Auth::access('supervisor')){
    Auth::setMessage("You don't have permission to delete sales");
    require views_path('auth/denied');
    die;
}

$id = $_GET['id'] ?? null;
$sale = new Sale();

$row = $sale->first(['id'=>$id]);

if($_SERVER['REQUEST_METHOD'] == "POST" && $row)
{
	
	$sale->delete($row['id']);
  
	redirect('admin&tab=sales');
 

}


require views_path('sales/sale-delete');
