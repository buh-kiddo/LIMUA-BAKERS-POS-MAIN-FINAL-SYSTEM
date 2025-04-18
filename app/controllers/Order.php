<?php

/**
 * Order Controller
 */
class Order
{
    use Controller;

    public function index()
    {
        if(!Auth::logged_in())
        {
            $this->redirect('login');
        }

        $order = new Orders();
        $data = $order->findAll();

        $this->view('admin/orders', [
            'orders' => $data
        ]);
    }

    public function new()
    {
        if(!Auth::logged_in())
        {
            $this->redirect('login');
        }

        $errors = [];
        
        if($_SERVER['REQUEST_METHOD'] == "POST") {
            $order = new Orders();
            
            $_POST['order_number'] = $this->generateOrderNumber();
            
            if($order->validate($_POST)) {
                
                // Handle image uploads
                $folder = "uploads/orders/";
                if(!file_exists($folder)) {
                    mkdir($folder, 0777, true);
                }

                $image1 = $this->handleImageUpload('image1', $folder);
                $image2 = $this->handleImageUpload('image2', $folder);

                if(!empty($image1)) {
                    $_POST['image1'] = $image1;
                }
                if(!empty($image2)) {
                    $_POST['image2'] = $image2;
                }

                $_POST['date_created'] = date("Y-m-d H:i:s");
                $_POST['created_by'] = Auth::user('id');
                $_POST['status'] = 'pending'; // Set default status
                
                $order->insert($_POST);
                message("Order created successfully");
                $this->redirect('admin&tab=orders');
            } else {
                $errors = $order->errors;
            }
        }

        $this->view('admin/order-new', [
            'errors' => $errors
        ]);
    }

    public function edit($id = null)
    {
        if(!Auth::logged_in())
        {
            $this->redirect('login');
        }

        $order = new Orders();
        $errors = [];

        if($_SERVER['REQUEST_METHOD'] == "POST" && $id) {
            
            if($order->validate($_POST)) {
                
                // Handle image uploads
                $folder = "uploads/orders/";
                if(!file_exists($folder)) {
                    mkdir($folder, 0777, true);
                }

                $image1 = $this->handleImageUpload('image1', $folder);
                $image2 = $this->handleImageUpload('image2', $folder);

                if(!empty($image1)) {
                    $_POST['image1'] = $image1;
                }
                if(!empty($image2)) {
                    $_POST['image2'] = $image2;
                }

                $_POST['date_updated'] = date("Y-m-d H:i:s");
                $_POST['updated_by'] = Auth::user('id');
                
                $order->update($id, $_POST);
                message("Order updated successfully");
                $this->redirect('admin&tab=orders');
            } else {
                $errors = $order->errors;
            }
        }

        $data = $order->where('id', $id);
        
        $this->view('admin/order-edit', [
            'row' => $data[0] ?? false,
            'errors' => $errors
        ]);
    }

    public function delete($id = null)
    {
        if(!Auth::logged_in())
        {
            $this->redirect('login');
        }

        $order = new Orders();
        
        if($_SERVER['REQUEST_METHOD'] == "POST") {
            
            $order->delete($id);
            message("Order deleted successfully");
            $this->redirect('admin&tab=orders');
        }

        $data = $order->where('id', $id);
        
        $this->view('admin/order-delete', [
            'row' => $data[0] ?? false
        ]);
    }

    private function generateOrderNumber()
    {
        return 'ORD-' . date('Ymd') . '-' . rand(1000, 9999);
    }

    private function handleImageUpload($file_key, $folder)
    {
        if(!empty($_FILES[$file_key]['name'])) {
            $filename = $folder . time() . $_FILES[$file_key]['name'];
            move_uploaded_file($_FILES[$file_key]['tmp_name'], $filename);
            return $filename;
        }
        return '';
    }
}
