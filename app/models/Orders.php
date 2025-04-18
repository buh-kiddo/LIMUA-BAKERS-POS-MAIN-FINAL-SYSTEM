<?php

/**
 * Orders Model
 */
class Orders extends Model
{
    protected $table = 'orders';
    public $errors = [];
    protected $allowed_columns = [
        'id',
        'order_name',
        'description',
        'customer_name',
        'phone_number',
        'total_amount',
        'deposit',
        'balance',
        'image1',
        'image2',
        'status',
        'date_created',
        'pickup_date',
        'created_by'
    ];

    public function validate($data)
    {
        $this->errors = [];

        // Required fields
        if(empty($data['order_name'])) {
            $this->errors['order_name'] = "Order name is required";
        }

        if(empty($data['phone_number'])) {
            $this->errors['phone_number'] = "Customer phone number is required";
        } else 
        if(!preg_match("/^[0-9]{10}$/", $data['phone_number'])) {
            $this->errors['phone_number'] = "Please enter a valid 10-digit phone number";
        }

        if(empty($data['total_amount'])) {
            $this->errors['total_amount'] = "Total amount is required";
        } else 
        if(!is_numeric($data['total_amount']) || $data['total_amount'] < 0) {
            $this->errors['total_amount'] = "Please enter a valid amount";
        }

        // Optional fields validation
        if(!empty($data['deposit'])) {
            if(!is_numeric($data['deposit']) || $data['deposit'] < 0) {
                $this->errors['deposit'] = "Please enter a valid deposit amount";
            } else if($data['deposit'] > $data['total_amount']) {
                $this->errors['deposit'] = "Deposit cannot be greater than total amount";
            }
        }

        if(!empty($data['pickup_date'])) {
            $pickup_timestamp = strtotime($data['pickup_date']);
            if($pickup_timestamp === false) {
                $this->errors['pickup_date'] = "Please enter a valid pickup date";
            }
        }

        return empty($this->errors);
    }

    public function getTotal()
    {
        $query = "SELECT COUNT(*) as total FROM $this->table";
        $result = $this->query($query);

        if ($result) {
            return $result[0]['total'];
        }
        return 0;
    }

    public function getTodaysOrders()
    {
        $today = date('Y-m-d');
        $query = "SELECT o.*, COALESCE(u.username, o.created_by) as created_by 
                 FROM $this->table o 
                 LEFT JOIN users u ON u.id = o.created_by 
                 WHERE DATE(o.date_created) = ? 
                 ORDER BY o.date_created DESC";
        return $this->query($query, [$today]);
    }

    public function getPendingOrders()
    {
        $query = "SELECT o.*, COALESCE(u.username, o.created_by) as created_by 
                 FROM $this->table o 
                 LEFT JOIN users u ON u.id = o.created_by 
                 WHERE o.status = 'pending' 
                 ORDER BY o.date_created DESC";
        return $this->query($query);
    }

    public function getOrderStats()
    {
        $query = "SELECT 
            SUM(total_amount) as total_amount,
            SUM(deposit) as total_deposits,
            SUM(balance) as total_balance
            FROM $this->table";
        
        $result = $this->query($query);
        
        if ($result) {
            return [
                'total_amount' => $result[0]['total_amount'] ?? 0,
                'total_deposits' => $result[0]['deposit'] ?? 0,
                'total_balance' => $result[0]['balance'] ?? 0
            ];
        }
        
        return [
            'total_amount' => 0,
            'total_deposits' => 0,
            'total_balance' => 0
        ];
    }

    public function findAll()
    {
        $query = "SELECT o.*, COALESCE(u.username, o.created_by) as created_by 
                 FROM $this->table o 
                 LEFT JOIN users u ON u.id = o.created_by 
                 ORDER BY o.date_created DESC";
        $result = $this->query($query);
        return is_array($result) ? $result : [];
    }

    public function insert($data)
    {
        if(empty($data['created_by'])) {
            $data['created_by'] = Auth::get('id'); // Store user ID
        }
        return parent::insert($data);
    }

    public function update($id, $data)
    {
        if(empty($data['updated_by'])) {
            $data['updated_by'] = Auth::get('id'); // Store user ID
        }
        return parent::update($id, $data);
    }
}
