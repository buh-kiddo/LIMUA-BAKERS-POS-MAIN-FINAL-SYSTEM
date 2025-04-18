<?php 

if(!Auth::logged_in())
{
    message('please login to view the admin section');
    redirect('login');
}

// Only allow admin and supervisor to edit sales
if(!Auth::access('admin') && !Auth::access('supervisor')){
    Auth::setMessage("You don't have permission to edit sales");
    require views_path('auth/denied');
    die;
}

$id = $_GET['id'] ?? null;
$sale = new Sale();

if($id){
    
    $row = $sale->first(['id'=>$id]);
}

if($_SERVER['REQUEST_METHOD'] == "POST" && $row)
{

	$errors = $sale->validate($_POST,$row['id']);
	if(empty($errors)){
		
		$_POST['total'] = $_POST['qty'] * $_POST['amount'];
		
		$sale->update($row['id'],$_POST);

		redirect('admin&tab=sales');
	}


}

require views_path('admin/sale-edit');
