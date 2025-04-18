<?php 

if(!Auth::logged_in())
{
    message('please login to view the admin section');
    redirect('login');
}

$tab = $_GET['tab'] ?? 'dashboard';

// Allow cashiers to access only the orders and sales tabs
if(($tab == "orders" || $tab == "sales") && Auth::access('cashier')) {
    // Continue to orders/sales page
} else if(!Auth::access('supervisor')){
    Auth::setMessage("You dont have access to the admin page");
    require views_path('auth/denied');
    die;
}

if($tab == "products")
{
    $product = new Product();
    $products = $product->query("select * from products order by id desc");
    $data['products'] = $products;
    $data['tab'] = $tab;
    require views_path('admin/admin');
}
else if($tab == "sales")
{
    $section = $_GET['s'] ?? 'table';
    $startdate = $_GET['start'] ?? null;
    $enddate = $_GET['end'] ?? null;

    $sale = new Sale();
    
    $limit = $_GET['limit'] ?? 20;
    $limit = (int)$limit;
    $limit = $limit < 1 ? 10 : $limit;

    $pager = new Pager($limit);
    $offset = $pager->offset;

    $query = "select * from sales order by id desc limit $limit offset $offset";

    //get today's sales total
    $year = date("Y");
    $month = date("m");
    $day = date("d");

    $query_total = "SELECT sum(total) as total FROM sales WHERE day(date) = $day && month(date) = $month && year(date) = $year";

    //if both start and end are set
    if($startdate && $enddate)
    {
        $query = "select * from sales where date BETWEEN '$startdate' AND '$enddate' order by id desc limit $limit offset $offset";
        $query_total = "SELECT sum(total) as total FROM sales WHERE date BETWEEN '$startdate' AND '$enddate'";
    }
    //if only start date is set
    else if($startdate && !$enddate)
    {
        $styear = date("Y",strtotime($startdate));
        $stmonth = date("m",strtotime($startdate));
        $stday = date("d",strtotime($startdate));
        
        $query = "select * from sales where date = '$startdate' order by id desc limit $limit offset $offset";
        $query_total = "select sum(total) as total from sales where date = '$startdate' ";
    }
    
    $sales = $sale->query($query);
    $st = $sale->query($query_total);
    
    $sales_total = 0;
    if($st){
        $sales_total = $st[0]['total'] ?? 0;
    }

    $data = [
        'sales' => $sales,
        'sales_total' => $sales_total,
        'pager' => $pager,
        'section' => $section,
        'tab' => $tab,
    ];

    if($section == 'graph')
    {
        $db = new Database();
        
        //query todays records
        $today = date('Y-m-d');
        $query = "SELECT total,date FROM sales WHERE DATE(date) = '$today' ";
        $data['today_records'] = $db->query($query);

        //query this months records
        $thismonth = date('m');
        $thisyear = date('Y');
        $query = "SELECT total,date FROM sales WHERE month(date) = '$thismonth' && year(date) = '$thisyear'";
        $data['thismonth_records'] = $db->query($query);

        //query this years records
        $query = "SELECT total,date FROM sales WHERE year(date) = '$thisyear'";
        $data['thisyear_records'] = $db->query($query);
    }

    extract($data);
    require views_path('admin/admin');
}
else if($tab == "users")
{
    $limit = 10;
    $pager = new Pager($limit);
    $offset = $pager->offset;

    $user = new User();
    $users = $user->query("select * from users order by id desc limit $limit offset $offset");
    $data = [
        'users' => $users, 
        'pager' => $pager,
        'tab' => $tab,
    ];
    extract($data);
    require views_path('admin/admin');
}
else if($tab == "orders")
{
    $order = new Orders();
    
    // Calculate sales statistics
    $today = date('Y-m-d');
    $week_start = date('Y-m-d', strtotime('-7 days'));
    $month_start = date('Y-m-01');

    // Today's stats
    $today_orders = $order->query("SELECT COUNT(*) as count FROM orders WHERE DATE(date_created) = ?", [$today]);
    $today_sales = $order->query("SELECT SUM(total_amount) as total FROM orders WHERE DATE(date_created) = ?", [$today]);
    $data['today_orders'] = $today_orders[0]['count'] ?? 0;
    $data['today_sales'] = $today_sales[0]['total'] ?? 0;

    // Week's stats
    $week_orders = $order->query("SELECT COUNT(*) as count FROM orders WHERE DATE(date_created) >= ?", [$week_start]);
    $week_sales = $order->query("SELECT SUM(total_amount) as total FROM orders WHERE DATE(date_created) >= ?", [$week_start]);
    $data['week_orders'] = $week_orders[0]['count'] ?? 0;
    $data['week_sales'] = $week_sales[0]['total'] ?? 0;

    // Month's stats
    $month_orders = $order->query("SELECT COUNT(*) as count FROM orders WHERE DATE(date_created) >= ?", [$month_start]);
    $month_sales = $order->query("SELECT SUM(total_amount) as total FROM orders WHERE DATE(date_created) >= ?", [$month_start]);
    $data['month_orders'] = $month_orders[0]['count'] ?? 0;
    $data['month_sales'] = $month_sales[0]['total'] ?? 0;

    // Outstanding payments
    $outstanding = $order->query("SELECT COUNT(*) as count, SUM(balance) as total FROM orders WHERE balance > 0");
    $data['pending_payments'] = $outstanding[0]['count'] ?? 0;
    $data['total_outstanding'] = $outstanding[0]['total'] ?? 0;

    // Get all orders
    $orders = $order->findAll();
    $data['orders'] = $orders;
    $data['tab'] = $tab;
    require views_path('admin/admin');
}
else if($tab == "reports")
{
    $report_type = $_GET['type'] ?? 'product_sales';
    $start_date = $_GET['start_date'] ?? date('Y-m-01');
    $end_date = $_GET['end_date'] ?? date('Y-m-d');

    $db = new Database();
        
    // Calculate today's and yesterday's sales for profit comparison
    $today = date('Y-m-d');
    $yesterday = date('Y-m-d', strtotime('-1 day'));
        
    $today_sales_query = "SELECT 
        COALESCE(SUM(total), 0) as total_sales,
        COUNT(DISTINCT receipt_no) as transaction_count,
        CASE WHEN COUNT(DISTINCT receipt_no) > 0 
            THEN SUM(total)/COUNT(DISTINCT receipt_no) 
            ELSE 0 
        END as avg_sale
        FROM sales 
        WHERE DATE(date) = ?";
        
    // First try to get yesterday's sales, if none found get the last sales day
    $last_sales_query = "SELECT 
        DATE(date) as last_sale_date,
        COALESCE(SUM(total), 0) as total_sales,
        COUNT(DISTINCT receipt_no) as transaction_count,
        CASE WHEN COUNT(DISTINCT receipt_no) > 0 
            THEN SUM(total)/COUNT(DISTINCT receipt_no) 
            ELSE 0 
        END as avg_sale
        FROM sales 
        WHERE DATE(date) < ?
        GROUP BY DATE(date)
        ORDER BY date DESC
        LIMIT 1";
            
    $today_sales = $db->query($today_sales_query, [$today])[0];
    $last_sales = $db->query($last_sales_query, [$today])[0];

    // If no last sales found, initialize with zeros
    if (!$last_sales) {
        $last_sales = [
            'last_sale_date' => $yesterday,
            'total_sales' => 0,
            'transaction_count' => 0,
            'avg_sale' => 0
        ];
    }

    // Calculate average sale across both days
    $avg_sale_query = "SELECT 
        COALESCE(SUM(total), 0) as total_sales,
        COUNT(DISTINCT receipt_no) as transaction_count,
        CASE WHEN COUNT(DISTINCT receipt_no) > 0 
            THEN SUM(total)/COUNT(DISTINCT receipt_no) 
            ELSE 0 
        END as avg_sale
        FROM sales 
        WHERE DATE(date) <= ?";

    $avg_sale_data = $db->query($avg_sale_query, [$today])[0];
        
    $data = [
        'report_type' => $report_type,
        'start_date' => $start_date,
        'end_date' => $end_date,
        'today_sales' => $today_sales['total_sales'],
        'today_transactions' => $today_sales['transaction_count'],
        'last_sales' => $last_sales['total_sales'],
        'last_transactions' => $last_sales['transaction_count'],
        'last_sale_date' => $last_sales['last_sale_date'],
        'sales_growth' => $last_sales['total_sales'] > 0 ? 
            round((($today_sales['total_sales'] - $last_sales['total_sales']) / $last_sales['total_sales'] * 100), 1) : 
            ($today_sales['total_sales'] > 0 ? 100 : 0),
        'average_sale' => $avg_sale_data['avg_sale'],
        'total_transactions' => $avg_sale_data['transaction_count']
    ];

    if($report_type == 'inventory') {
        $inventory_query = "SELECT 
            p.barcode,
            p.description,
            p.qty as current_stock,
            p.amount as unit_price,
            p.qty * p.amount as stock_value,
            COALESCE(s.total_sold, 0) as total_sold,
            COALESCE(s.total_revenue, 0) as total_revenue
        FROM products p
        LEFT JOIN (
            SELECT 
                barcode,
                SUM(qty) as total_sold,
                SUM(total) as total_revenue
            FROM sales 
            WHERE DATE(date) BETWEEN :start_date AND :end_date
            GROUP BY barcode
        ) s ON p.barcode = s.barcode
        ORDER BY p.description";

        $products = $db->query($inventory_query, ['start_date' => $start_date, 'end_date' => $end_date]);

        $total_products = count($products);
        $total_stock_value = array_sum(array_column($products, 'stock_value'));
        $total_revenue = array_sum(array_column($products, 'total_revenue'));

        // Get low stock items (5 or fewer units)
        $low_stock_data = array_filter($products, function($p) {
            return $p['current_stock'] <= 5;
        });

        $data['products'] = $products;
        $data['total_products'] = $total_products;
        $data['total_stock_value'] = $total_stock_value;
        $data['total_revenue'] = $total_revenue;
        $data['low_stock_data'] = $low_stock_data;
    }
    else if($report_type == 'product_sales') {
        // Get sales trend data with more detailed metrics
        $trend_query = "SELECT 
            DATE(date) as sale_date,
            COUNT(DISTINCT receipt_no) as transaction_count,
            SUM(qty) as items_sold,
            SUM(total) as daily_total,
            COUNT(DISTINCT barcode) as unique_products,
            SUM(total)/COUNT(DISTINCT receipt_no) as avg_transaction_value
            FROM sales 
            WHERE date BETWEEN ? AND ?
            GROUP BY DATE(date)
            ORDER BY sale_date";
            
        $data['sales_trend'] = $db->query($trend_query, [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
            
        // Get top selling products
        $top_products_query = "SELECT 
            s.description,
            s.barcode,
            SUM(s.qty) as total_quantity,
            SUM(s.total) as total_revenue,
            COUNT(*) as sale_count,
            p.amount as current_price,
            p.qty as current_stock
            FROM sales s
            LEFT JOIN products p ON p.barcode = s.barcode
            WHERE s.date BETWEEN ? AND ?
            GROUP BY s.barcode, s.description
            ORDER BY total_quantity DESC";
            
        $data['top_products'] = $db->query($top_products_query, [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
            
        // Get hourly sales distribution
        $hourly_query = "SELECT 
            HOUR(date) as hour_of_day,
            COUNT(*) as transaction_count,
            SUM(total) as total_sales,
            SUM(qty) as items_sold
            FROM sales 
            WHERE date BETWEEN ? AND ?
            GROUP BY HOUR(date)
            ORDER BY hour_of_day";
            
        $data['hourly_sales'] = $db->query($hourly_query, [$start_date, $end_date]);

        // Calculate totals
        $totals_query = "SELECT 
            COUNT(DISTINCT receipt_no) as total_transactions,
            COUNT(DISTINCT barcode) as unique_products,
            COUNT(*) as total_items_sold,
            SUM(total) as total_revenue,
            AVG(total) as average_sale
            FROM sales 
            WHERE date BETWEEN ? AND ?";
            
        $totals = $db->query($totals_query, [$start_date . ' 00:00:00', $end_date . ' 23:59:59'])[0] ?? [];
        
        $data['totals'] = [
            'total_transactions' => $totals['total_transactions'] ?? 0,
            'total_items_sold' => $totals['total_items_sold'] ?? 0,
            'unique_products' => $totals['unique_products'] ?? 0,
            'total_revenue' => $totals['total_revenue'] ?? 0,
            'avg_basket_size' => $totals['total_transactions'] > 0 ? round($totals['total_revenue'] / $totals['total_transactions'], 2) : 0,
            'daily_avg_revenue' => $totals['total_revenue'],
            'avg_units_per_product' => $totals['unique_products'] > 0 ? round($totals['total_items_sold'] / $totals['unique_products'], 1) : 0
        ];
    }
    else if($report_type == 'order_sales') {
        // Get order trend data
        $trend_query = "SELECT 
            DATE(date_created) as order_date,
            COUNT(*) as order_count,
            SUM(total_amount) as daily_total,
            SUM(deposit) as daily_deposits,
            SUM(balance) as daily_balance
            FROM orders 
            WHERE date_created BETWEEN ? AND ?
            GROUP BY DATE(date_created)
            ORDER BY order_date";

        $data['orders_trend'] = $db->query($trend_query, [$start_date, $end_date]);
            
        // Get order status summary
        $status_query = "SELECT 
            status,
            COUNT(*) as order_count,
            SUM(total_amount) as total_amount,
            SUM(deposit) as total_deposits,
            SUM(balance) as total_balance,
            AVG(total_amount) as average_order
            FROM orders 
            WHERE date_created BETWEEN ? AND ?
            GROUP BY status";

        $data['order_status'] = $db->query($status_query, [$start_date, $end_date]);
            
        // Get payment status summary
        $payment_query = "SELECT 
            CASE 
                WHEN balance = 0 THEN 'Fully Paid'
                WHEN deposit > 0 THEN 'Partially Paid'
                ELSE 'Unpaid'
            END as payment_status,
            COUNT(*) as order_count,
            SUM(total_amount) as total_amount,
            SUM(deposit) as total_deposits,
            SUM(balance) as total_balance
            FROM orders 
            WHERE date_created BETWEEN ? AND ?
            GROUP BY 
                CASE 
                    WHEN balance = 0 THEN 'Fully Paid'
                    WHEN deposit > 0 THEN 'Partially Paid'
                    ELSE 'Unpaid'
                END";

        $data['payment_status'] = $db->query($payment_query, [$start_date, $end_date]);

        // Get recent orders
        $orders_query = "SELECT 
            o.*,
            u.username as cashier_name,
            CASE 
                WHEN o.balance = 0 THEN 'Fully Paid'
                WHEN o.deposit > 0 THEN 'Partially Paid'
                ELSE 'Unpaid'
            END as payment_status
            FROM orders o
            LEFT JOIN users u ON u.id = o.created_by
            WHERE o.date_created BETWEEN ? AND ?
            ORDER BY o.date_created DESC
            LIMIT 10";
            
        $data['recent_orders'] = $db->query($orders_query, [$start_date, $end_date]);
    }
        
    $data['tab'] = $tab;
    require views_path('admin/admin');
}
else if($tab == 'dashboard')
{
    $user = new User();
    $order = new Orders();
    $product = new Product();
    $sale = new Sale();

    // Get total counts
    $total_users = $user->getTotal();
    $total_products = $product->getTotal();
    $total_sales = $sale->query("SELECT COUNT(*) as total FROM sales")[0]['total'] ?? 0;
    
    // Get orders statistics
    $all_orders = $order->query("SELECT * FROM orders") ?? [];
    $total_orders = count($all_orders);
    
    // Calculate pending orders and total balance
    $pending_orders = 0;
    $total_balance = 0;
    
    foreach($all_orders as $ord) {
        if($ord['status'] !== 'picked' && $ord['status'] !== 'completed' && $ord['status'] !== 'cancelled') {
            $pending_orders++;
        }
        $total_balance += ($ord['total_amount'] - ($ord['deposit'] ?? 0));
    }
    
    // Get recent orders ordered by pickup date
    $recent_orders = $order->query("SELECT * FROM orders ORDER BY pickup_date DESC LIMIT 10");

    $data = [
        'total_users' => $total_users,
        'total_orders' => $total_orders,
        'total_products' => $total_products,
        'total_sales' => $total_sales,
        'pending_orders' => $pending_orders,
        'total_balance' => $total_balance,
        'recent_orders' => $recent_orders,
        'tab' => $tab,
    ];
    
    require views_path('admin/admin');
}
else if($tab == "orders")
{
    $order = new Orders();
    
    // Calculate sales statistics
    $today = date('Y-m-d');
    $week_start = date('Y-m-d', strtotime('-7 days'));
    $month_start = date('Y-m-01');
    
    // Today's sales
    $today_stats = $order->query("
        SELECT 
            COUNT(*) as order_count,
            COALESCE(SUM(total_amount), 0) as total_sales 
        FROM orders 
        WHERE DATE(date_created) = '$today'
    ")[0];
    
    // This week's sales
    $week_stats = $order->query("
        SELECT 
            COUNT(*) as order_count,
            COALESCE(SUM(total_amount), 0) as total_sales 
        FROM orders 
        WHERE DATE(date_created) BETWEEN '$week_start' AND '$today'
    ")[0];
    
    // This month's sales
    $month_stats = $order->query("
        SELECT 
            COUNT(*) as order_count,
            COALESCE(SUM(total_amount), 0) as total_sales 
        FROM orders 
        WHERE DATE(date_created) BETWEEN '$month_start' AND '$today'
    ")[0];
    
    // Outstanding payments
    $outstanding_stats = $order->query("
        SELECT 
            COUNT(*) as payment_count,
            COALESCE(SUM(balance), 0) as total_outstanding 
        FROM orders 
        WHERE balance > 0 AND status != 'cancelled'
    ")[0];
    
    // Get all orders with pagination
    $limit = 10;
    $pager = new Pager($limit);
    $offset = $pager->offset;
    
    $query = "select * from orders order by id desc limit $limit offset $offset";
    $orders = $order->query($query);

    $data = [
        'orders' => $orders,
        'pager' => $pager,
        'tab' => $tab,
        // Sales statistics
        'today_sales' => floatval($today_stats['total_sales']),
        'today_orders' => intval($today_stats['order_count']),
        'week_sales' => floatval($week_stats['total_sales']),
        'week_orders' => intval($week_stats['order_count']),
        'month_sales' => floatval($month_stats['total_sales']),
        'month_orders' => intval($month_stats['order_count']),
        'total_outstanding' => floatval($outstanding_stats['total_outstanding']),
        'pending_payments' => intval($outstanding_stats['payment_count']),
    ];
    
    extract($data);
    require views_path('admin/admin');
}
else if($tab == "reports")
{
    $report_type = $_GET['type'] ?? 'product_sales';
    $date_range = $_GET['date_range'] ?? 'month';
    $start_date = $_GET['start_date'] ?? date('Y-m-01'); // First day of current month
    $end_date = $_GET['end_date'] ?? date('Y-m-d');
    $category = $_GET['category'] ?? '';

    $db = new Database();
    
    // Set date range based on selection
    switch($date_range) {
        case 'today':
            $start_date = date('Y-m-d');
            $end_date = date('Y-m-d');
            break;
        case 'week':
            $start_date = date('Y-m-d', strtotime('-7 days'));
            $end_date = date('Y-m-d');
            break;
        case 'month':
            $start_date = date('Y-m-01');
            $end_date = date('Y-m-d');
            break;
        case 'year':
            $start_date = date('Y-01-01');
            $end_date = date('Y-m-d');
            break;
    }

    if($report_type == 'product_sales') {
        // Get sales data
        $query = "SELECT 
            p.id,
            p.description,
            p.barcode,
            p.qty as current_stock,
            p.amount,
            COUNT(DISTINCT s.receipt_no) as total_transactions,
            SUM(s.qty) as total_qty_sold,
            SUM(s.total) as total_revenue
            FROM products p
            LEFT JOIN sales s ON p.barcode = s.barcode 
                AND s.date BETWEEN :start_date AND :end_date
            GROUP BY p.id, p.description, p.barcode, p.qty, p.amount
            ORDER BY total_revenue DESC";

        $sales_data = $db->query($query, [
            'start_date' => $start_date,
            'end_date' => $end_date
        ]);

        // Get top selling products
        $top_products_query = "SELECT 
            s.barcode,
            s.description,
            COUNT(DISTINCT s.receipt_no) as transaction_count,
            SUM(s.qty) as total_qty,
            SUM(s.total) as total_revenue,
            ROUND(SUM(s.total) / COUNT(DISTINCT s.receipt_no), 2) as avg_transaction_value,
            MAX(s.date) as last_sold
        FROM sales s
        WHERE DATE(s.date) BETWEEN ? AND ?
        GROUP BY s.barcode, s.description
        ORDER BY total_revenue DESC
        LIMIT 5";

        // Get sales by hour of day
        $hourly_sales_query = "SELECT 
            HOUR(date) as hour_of_day,
            COUNT(DISTINCT receipt_no) as transaction_count,
            SUM(total) as total_sales,
            ROUND(SUM(total) / COUNT(DISTINCT receipt_no), 2) as avg_sale
        FROM sales 
        WHERE DATE(date) BETWEEN ? AND ?
        GROUP BY HOUR(date)
        ORDER BY total_sales DESC";

        // Get sales by day of week
        $daily_sales_query = "SELECT 
            DAYNAME(date) as day_name,
            DAYOFWEEK(date) as day_number,
            COUNT(DISTINCT receipt_no) as transaction_count,
            SUM(total) as total_sales,
            ROUND(SUM(total) / COUNT(DISTINCT receipt_no), 2) as avg_sale
        FROM sales 
        WHERE DATE(date) BETWEEN ? AND ?
        GROUP BY DAYNAME(date), DAYOFWEEK(date)
        ORDER BY DAYOFWEEK(date)";

        // Get sales trends by category
        $category_sales_query = "SELECT 
            COALESCE(c.name, 'Uncategorized') as category,
            COUNT(DISTINCT s.receipt_no) as transaction_count,
            SUM(s.qty) as total_qty,
            SUM(s.total) as total_sales,
            ROUND(SUM(s.total) / COUNT(DISTINCT s.receipt_no), 2) as avg_sale
        FROM sales s
        LEFT JOIN products p ON s.barcode = p.barcode
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE DATE(s.date) BETWEEN ? AND ?
        GROUP BY c.name
        ORDER BY total_sales DESC";

        // Get recent sales velocity
        $sales_velocity_query = "SELECT 
            DATE(date) as sale_date,
            COUNT(DISTINCT receipt_no) as transactions,
            SUM(total) as revenue,
            COUNT(DISTINCT barcode) as unique_products,
            ROUND(SUM(total) / COUNT(DISTINCT receipt_no), 2) as avg_basket
        FROM sales
        WHERE DATE(date) BETWEEN DATE_SUB(?, INTERVAL 7 DAY) AND ?
        GROUP BY DATE(date)
        ORDER BY sale_date DESC";

        $data['sales_data'] = $sales_data;
        $data['insights'] = [
            'top_products' => $db->query($top_products_query, [$start_date, $end_date]),
            'hourly_sales' => $db->query($hourly_sales_query, [$start_date, $end_date]),
            'daily_sales' => $db->query($daily_sales_query, [$start_date, $end_date]),
            'category_sales' => $db->query($category_sales_query, [$start_date, $end_date]),
            'sales_velocity' => $db->query($sales_velocity_query, [$end_date, $end_date])
        ];

        require views_path('admin/reports');
    }
    else if($report_type == 'daily_sales') {
        // Get daily sales data
        $daily_query = "SELECT 
            DATE(date) as sale_date,
            COUNT(*) as total_transactions,
            SUM(total) as total_amount
            FROM sales
            WHERE DATE(date) BETWEEN ? AND ?
            GROUP BY DATE(date)
            ORDER BY DATE(date) DESC";

        $daily_data = $db->query($daily_query, [$start_date, $end_date]);
        $data['daily_data'] = $daily_data;
    }
    else if($report_type == 'inventory') {
        // Get inventory data
        $inventory_query = "SELECT 
            p.description,
            p.qty,
            p.amount as unit_price,
            (p.qty * p.amount) as total_value
            FROM products p
            ORDER BY total_value DESC";

        $inventory_data = $db->query($inventory_query);
        $data['inventory_data'] = $inventory_data;

        // Get low stock items (less than 10)
        $low_stock_query = "SELECT 
            description,
            qty,
            amount as unit_price
            FROM products
            WHERE qty < 10
            ORDER BY qty ASC";

        $low_stock_data = $db->query($low_stock_query);
        $data['low_stock_data'] = $low_stock_data;
    }

    $data['report_type'] = $report_type;
    $data['date_range'] = $date_range;
    $data['start_date'] = $start_date;
    $data['end_date'] = $end_date;
    $data['tab'] = $tab;

    // Get top selling products
    $top_products_query = "SELECT 
        s.barcode,
        s.description,
        COUNT(DISTINCT s.receipt_no) as transaction_count,
        SUM(s.qty) as total_qty,
        SUM(s.total) as total_revenue,
        ROUND(SUM(s.total) / COUNT(DISTINCT s.receipt_no), 2) as avg_transaction_value,
        MAX(s.date) as last_sold
    FROM sales s
    WHERE DATE(s.date) BETWEEN ? AND ?
    GROUP BY s.barcode, s.description
    ORDER BY total_revenue DESC
    LIMIT 5";

    // Get sales by hour of day
    $hourly_sales_query = "SELECT 
        HOUR(date) as hour_of_day,
        COUNT(DISTINCT receipt_no) as transaction_count,
        SUM(total) as total_sales,
        ROUND(SUM(total) / COUNT(DISTINCT receipt_no), 2) as avg_sale
    FROM sales 
    WHERE DATE(date) BETWEEN ? AND ?
    GROUP BY HOUR(date)
    ORDER BY total_sales DESC";

    // Get sales by day of week
    $daily_sales_query = "SELECT 
        DAYNAME(date) as day_name,
        DAYOFWEEK(date) as day_number,
        COUNT(DISTINCT receipt_no) as transaction_count,
        SUM(total) as total_sales,
        ROUND(SUM(total) / COUNT(DISTINCT receipt_no), 2) as avg_sale
    FROM sales 
    WHERE DATE(date) BETWEEN ? AND ?
    GROUP BY DAYNAME(date), DAYOFWEEK(date)
    ORDER BY DAYOFWEEK(date)";

    // Get sales trends by category
    $category_sales_query = "SELECT 
        COALESCE(c.name, 'Uncategorized') as category,
        COUNT(DISTINCT s.receipt_no) as transaction_count,
        SUM(s.qty) as total_qty,
        SUM(s.total) as total_sales,
        ROUND(SUM(s.total) / COUNT(DISTINCT s.receipt_no), 2) as avg_sale
    FROM sales s
    LEFT JOIN products p ON s.barcode = p.barcode
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE DATE(s.date) BETWEEN ? AND ?
    GROUP BY c.name
    ORDER BY total_sales DESC";

    // Get recent sales velocity
    $sales_velocity_query = "SELECT 
        DATE(date) as sale_date,
        COUNT(DISTINCT receipt_no) as transactions,
        SUM(total) as revenue,
        COUNT(DISTINCT barcode) as unique_products,
        ROUND(SUM(total) / COUNT(DISTINCT receipt_no), 2) as avg_basket
    FROM sales
    WHERE DATE(date) BETWEEN DATE_SUB(?, INTERVAL 7 DAY) AND ?
    GROUP BY DATE(date)
    ORDER BY sale_date DESC";

    $data['insights'] = [
        'top_products' => $db->query($top_products_query, [$start_date, $end_date]),
        'hourly_sales' => $db->query($hourly_sales_query, [$start_date, $end_date]),
        'daily_sales' => $db->query($daily_sales_query, [$start_date, $end_date]),
        'category_sales' => $db->query($category_sales_query, [$start_date, $end_date]),
        'sales_velocity' => $db->query($sales_velocity_query, [$end_date, $end_date])
    ];

    require views_path('admin/admin');
}
else if($tab == 'order_sales')
{
    // Get order sales summary
    $summary_query = "SELECT 
        COUNT(*) as total_orders,
        SUM(total_amount) as total_revenue,
        SUM(CASE WHEN deposit >= total_amount THEN 1 ELSE 0 END) as paid_orders,
        SUM(CASE WHEN deposit > 0 AND deposit < total_amount THEN 1 ELSE 0 END) as partial_orders,
        SUM(CASE WHEN deposit = 0 THEN 1 ELSE 0 END) as unpaid_orders,
        SUM(total_amount - deposit) as total_balance,
        AVG(total_amount) as average_order
    FROM orders 
    WHERE date_created BETWEEN ? AND ?";

    $data['summary'] = $db->query($summary_query, [$start_date, $end_date])[0] ?? [];

    // Get daily order trends
    $trends_query = "SELECT 
        DATE(date_created) as order_date,
        COUNT(*) as daily_orders,
        SUM(total_amount) as daily_revenue,
        SUM(deposit) as daily_deposits,
        SUM(total_amount - deposit) as daily_balance
    FROM orders 
    WHERE date_created BETWEEN ? AND ?
    GROUP BY DATE(date_created)
    ORDER BY order_date";

    $data['order_trends'] = $db->query($trends_query, [$start_date, $end_date]);

    // Get recent orders with status
    $orders_query = "SELECT 
        o.*,
        (total_amount - deposit) as balance,
        CASE 
            WHEN deposit >= total_amount THEN 'Paid'
            WHEN deposit > 0 THEN 'Partial'
            ELSE 'Unpaid'
        END as payment_status
    FROM orders o
    WHERE date_created BETWEEN ? AND ?
    ORDER BY date_created DESC
    LIMIT 50";

    $data['orders'] = $db->query($orders_query, [$start_date, $end_date]);

    // Payment status distribution
    $status_query = "SELECT 
        CASE 
            WHEN deposit >= total_amount THEN 'Paid'
            WHEN deposit > 0 THEN 'Partial'
            ELSE 'Unpaid'
        END as status,
        COUNT(*) as count,
        SUM(total_amount) as total_amount
    FROM orders 
    WHERE date_created BETWEEN ? AND ?
    GROUP BY 
        CASE 
            WHEN deposit >= total_amount THEN 'Paid'
            WHEN deposit > 0 THEN 'Partial'
            ELSE 'Unpaid'
        END";

    $data['payment_status'] = $db->query($status_query, [$start_date, $end_date]);

    $data['tab'] = $tab;
    require views_path('admin/admin');
}
else if($tab == "export_pdf")
{
    $report_type = $_GET['type'] ?? 'product_sales';
    $start_date = $_GET['start_date'] ?? date('Y-m-d');
    $end_date = $_GET['end_date'] ?? date('Y-m-d');

    require_once '../app/libs/tcpdf/tcpdf.php';
    require_once '../app/libs/tcpdf/ReportPDF.php';

    // Create new PDF document
    $pdf = new ReportPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // Set document information
    $pdf->SetCreator('LIMUA BAKERS POS');
    $pdf->SetAuthor('LIMUA BAKERS');
    $pdf->SetTitle('Sales Report');

    // Set margins
    $pdf->SetMargins(15, 40, 15);
    $pdf->SetHeaderMargin(5);
    $pdf->SetFooterMargin(10);

    // Set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, 25);

    // Add a page
    $pdf->AddPage();

    // Set font
    $pdf->SetFont('helvetica', '', 11);

    $db = new Database();

    if($report_type == 'product_sales') {
        // Get top selling products
        $top_products_query = "SELECT 
            s.barcode,
            s.description,
            COUNT(DISTINCT s.receipt_no) as transaction_count,
            SUM(s.qty) as total_qty,
            SUM(s.total) as total_revenue,
            MAX(s.date) as last_sold
        FROM sales s
        WHERE DATE(s.date) BETWEEN ? AND ?
        GROUP BY s.barcode, s.description
        ORDER BY total_revenue DESC
        LIMIT 5";

        $top_products = $db->query($top_products_query, [$start_date, $end_date]);

        // Build HTML content
        $html = '<h2>Product Sales Report</h2>';
        $html .= '<p>Period: ' . date('M j, Y', strtotime($start_date)) . ' to ' . date('M j, Y', strtotime($end_date)) . '</p>';
        
        // Top Products Table
        $html .= '<h3>Top Selling Products</h3>';
        $html .= '<table border="1" cellpadding="5">
            <tr style="background-color: #f0f0f0;">
                <th>Product</th>
                <th>Quantity Sold</th>
                <th>Revenue</th>
                <th>Last Sold</th>
            </tr>';
        
        foreach($top_products as $product) {
            $html .= '<tr>
                <td>' . htmlspecialchars($product['description']) . '<br><small>' . $product['barcode'] . '</small></td>
                <td align="right">' . number_format($product['total_qty']) . '</td>
                <td align="right">KES ' . number_format($product['total_revenue'], 2) . '</td>
                <td>' . date('M j, g:ia', strtotime($product['last_sold'])) . '</td>
            </tr>';
        }
        
        $html .= '</table>';

        // Add other sections (hourly sales, daily sales, etc.) here...
    }
    else if($report_type == 'order_sales') {
        // Get order summary
        $order_query = "SELECT 
            o.id,
            o.order_name,
            o.description,
            o.customer_name,
            o.phone_number,
            o.total_amount,
            o.deposit,
            o.balance,
            o.pickup_date,
            o.status,
            o.date_created
        FROM orders o
        WHERE DATE(o.date_created) BETWEEN ? AND ?
        ORDER BY o.date_created DESC";

        $orders = $db->query($order_query, [$start_date, $end_date]);

        // Build HTML content
        $html = '<h2>Order Sales Report</h2>';
        $html .= '<p>Period: ' . date('M j, Y', strtotime($start_date)) . ' to ' . date('M j, Y', strtotime($end_date)) . '</p>';
        
        $html .= '<table border="1" cellpadding="5">
            <tr style="background-color: #f0f0f0;">
                <th>Order</th>
                <th>Customer</th>
                <th>Details</th>
                <th>Amount</th>
                <th>Status</th>
            </tr>';
        
        $total_amount = 0;
        $total_orders = 0;
        
        foreach($orders as $order) {
            $payment_status = $order['balance'] > 0 ? 'Partial' : 'Paid';
            
            $status_color = '';
            $status_text = ucfirst($order['status']);
            switch($order['status']) {
                case 'completed':
                case 'picked':
                    $status_color = '#22c55e'; // green
                    break;
                case 'in_progress':
                    $status_color = '#3b82f6'; // blue
                    break;
                default:
                    $status_color = '#f59e0b'; // amber
            }
            
            $html .= '<tr>
                <td>
                    <div class="fw-medium">' . htmlspecialchars($order['order_name']) . '</div>
                    <small class="text-muted">Due: ' . date('M j, g:ia', strtotime($order['pickup_date'])) . '</small>
                </td>
                <td>
                    <div>' . htmlspecialchars($order['customer_name']) . '</div>
                    <small class="text-muted">' . $order['phone_number'] . '</small>
                </td>
                <td>' . nl2br(htmlspecialchars($order['description'])) . '</td>
                <td align="right">
                    <div class="fw-medium">KES ' . number_format($order['total_amount'], 2) . '</div>
                    <small class="text-muted">' . $payment_status . 
                    ($order['balance'] > 0 ? ' (Balance: KES ' . number_format($order['balance'], 2) . ')' : '') . 
                    '</small>
                </td>
                <td><span style="color: ' . $status_color . ';">' . $status_text . '</span></td>
            </tr>';
            
            $total_amount += $order['total_amount'];
            $total_orders++;
        }
        
        $html .= '<tr style="background-color: #f0f0f0; font-weight: bold;">
            <td colspan="3">TOTAL (' . number_format($total_orders) . ' orders)</td>
            <td align="right">KES ' . number_format($total_amount, 2) . '</td>
            <td></td>
        </tr>';
        
        $html .= '</table>';
    }
    else if($report_type == 'inventory') {
        // Get inventory summary
        $inventory_query = "SELECT 
            p.barcode,
            p.description,
            p.qty as current_stock,
            p.amount as unit_price,
            COALESCE(s.total_sold, 0) as total_sold,
            COALESCE(s.total_revenue, 0) as total_revenue,
            COALESCE(s.last_sale, p.date) as last_movement
        FROM products p
        LEFT JOIN (
            SELECT 
                barcode,
                SUM(qty) as total_sold,
                SUM(total) as total_revenue,
                MAX(date) as last_sale
            FROM sales 
            WHERE DATE(date) BETWEEN ? AND ?
            GROUP BY barcode
        ) s ON p.barcode = s.barcode
        ORDER BY p.description";

        $products = $db->query($inventory_query, [$start_date, $end_date]);

        // Build HTML content
        $html = '<h2>Inventory Report</h2>';
        $html .= '<p>Period: ' . date('M j, Y', strtotime($start_date)) . ' to ' . date('M j, Y', strtotime($end_date)) . '</p>';
        
        $html .= '<table border="1" cellpadding="5">
            <tr style="background-color: #f0f0f0;">
                <th>Product</th>
                <th>Current Stock</th>
                <th>Unit Price</th>
                <th>Total Sold</th>
                <th>Revenue</th>
                <th>Last Movement</th>
            </tr>';
        
        $total_stock = 0;
        $total_revenue = 0;
        $total_items = 0;
        
        foreach($products as $product) {
            $stock_status = '';
            if($product['current_stock'] <= 5) {
                $stock_status = '<span style="color: #ef4444;">Low Stock</span>';
            } else if($product['current_stock'] <= 10) {
                $stock_status = '<span style="color: #f59e0b;">Medium Stock</span>';
            }
            
            $html .= '<tr>
                <td>
                    <div class="fw-medium">' . htmlspecialchars($product['description']) . '</div>
                    <small class="text-muted">' . $product['barcode'] . '</small>
                </td>
                <td align="right">
                    <div>' . number_format($product['current_stock']) . '</div>
                    ' . $stock_status . '
                </td>
                <td align="right">KES ' . number_format($product['unit_price'], 2) . '</td>
                <td align="right">' . number_format($product['total_sold']) . '</td>
                <td align="right">KES ' . number_format($product['total_revenue'], 2) . '</td>
                <td>' . date('M j, Y', strtotime($product['last_movement'])) . '</td>
            </tr>';
            
            $total_stock += $product['current_stock'];
            $total_revenue += $product['total_revenue'];
            $total_items++;
        }
        
        $html .= '<tr style="background-color: #f0f0f0; font-weight: bold;">
            <td>TOTAL (' . number_format($total_items) . ' items)</td>
            <td align="right">' . number_format($total_stock) . '</td>
            <td></td>
            <td align="right">' . number_format(array_sum(array_column($products, 'total_sold'))) . '</td>
            <td align="right">KES ' . number_format($total_revenue, 2) . '</td>
            <td></td>
        </tr>';
        
        $html .= '</table>';

        // Add stock level summary
        $low_stock = array_filter($products, function($p) { return $p['current_stock'] <= 5; });
        $medium_stock = array_filter($products, function($p) { return $p['current_stock'] > 5 && $p['current_stock'] <= 10; });
        
        $html .= '<div style="margin-top: 20px;">
            <h3>Stock Level Summary</h3>
            <p>
                <span style="color: #ef4444;">Low Stock (≤5): ' . count($low_stock) . ' items</span><br>
                <span style="color: #f59e0b;">Medium Stock (6-10): ' . count($medium_stock) . ' items</span><br>
                <span style="color: #22c55e;">Good Stock (>10): ' . ($total_items - count($low_stock) - count($medium_stock)) . ' items</span>
            </p>
        </div>';
    }

    // Output the HTML content
    $pdf->writeHTML($html, true, false, true, false, '');

    // Close and output PDF document
    $pdf->Output('sales_report.pdf', 'D');
}
else if($tab == "inventory") {
    $inventory_query = "SELECT 
        p.barcode,
        p.description,
        p.qty as current_stock,
        p.amount as unit_price,
        p.qty * p.amount as stock_value
    FROM products p
    ORDER BY p.description";

    $products = $db->query($inventory_query);

    // Calculate stock levels
    $low_stock = array_filter($products, function($p) { return $p['current_stock'] <= 5; });
    $medium_stock = array_filter($products, function($p) { return $p['current_stock'] > 5 && $p['current_stock'] <= 10; });
    $good_stock = array_filter($products, function($p) { return $p['current_stock'] > 10; });

    $html = '
    <style>
        .report-header { text-align: center; padding: 20px 0; }
        .report-title { font-size: 22px; color: #1a1a1a; margin-bottom: 5px; }
        .report-date { color: #666; font-size: 13px; }
        
        .stock-levels { 
            display: flex; 
            margin: 20px 0; 
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            overflow: hidden;
        }
        
        .stock-level-item { 
            flex: 1; 
            padding: 15px; 
            text-align: center; 
            background: #fff;
        }
        
        .stock-level-item:not(:last-child) { 
            border-right: 1px solid #e5e7eb; 
        }
        
        .level-count { 
            font-size: 28px; 
            font-weight: 600; 
            margin: 5px 0; 
        }
        
        .level-label { 
            font-size: 13px; 
            color: #666; 
        }
        
        .level-desc { 
            font-size: 12px; 
            color: #888; 
            margin-top: 3px; 
        }
        
        .critical { color: #dc2626; }
        .warning { color: #d97706; }
        .success { color: #059669; }
        
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px; 
            background: #fff;
            border: 1px solid #e5e7eb;
        }
        
        th { 
            background: #f8fafc; 
            padding: 12px 15px; 
            text-align: left; 
            font-size: 13px;
            color: #1a1a1a;
            border-bottom: 2px solid #e5e7eb;
        }
        
        td { 
            padding: 12px 15px; 
            border-bottom: 1px solid #e5e7eb;
            font-size: 13px;
        }
        
        .product-name { font-weight: 500; }
        .product-code { color: #666; font-size: 12px; margin-top: 2px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
    </style>
    
    <div class="report-header">
        <div class="report-title">Inventory Status Report</div>
        <div class="report-date">As of ' . date('F j, Y') . '</div>
    </div>

    <div class="stock-levels">
        <div class="stock-level-item">
            <div class="level-label">Critical Stock</div>
            <div class="level-count critical">' . count($low_stock) . '</div>
            <div class="level-desc">5 or fewer units</div>
        </div>
        <div class="stock-level-item">
            <div class="level-label">Moderate Stock</div>
            <div class="level-count warning">' . count($medium_stock) . '</div>
            <div class="level-desc">6 to 10 units</div>
        </div>
        <div class="stock-level-item">
            <div class="level-label">Optimal Stock</div>
            <div class="level-count success">' . count($good_stock) . '</div>
            <div class="level-desc">More than 10 units</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Product Details</th>
                <th class="text-center">Stock Level</th>
                <th class="text-right">Unit Price</th>
                <th class="text-right">Total Value</th>
            </tr>
        </thead>
        <tbody>';

    foreach($products as $product) {
        $status_class = $product['current_stock'] <= 5 ? 'critical' : 
                      ($product['current_stock'] <= 10 ? 'warning' : 'success');

        $html .= '<tr>
            <td>
                <div class="product-name">' . htmlspecialchars($product['description']) . '</div>
                <div class="product-code">' . $product['barcode'] . '</div>
            </td>
            <td class="text-center">
                <span class="' . $status_class . '">' . number_format($product['current_stock']) . ' units</span>
            </td>
            <td class="text-right">KES ' . number_format($product['unit_price'], 2) . '</td>
            <td class="text-right">KES ' . number_format($product['stock_value'], 2) . '</td>
        </tr>';
    }

    $total_value = array_sum(array_column($products, 'stock_value'));
    $total_items = array_sum(array_column($products, 'current_stock'));

    $html .= '
        </tbody>
        <tfoot>
            <tr>
                <td><strong>Total (' . count($products) . ' products)</strong></td>
                <td class="text-center"><strong>' . number_format($total_items) . ' units</strong></td>
                <td></td>
                <td class="text-right"><strong>KES ' . number_format($total_value, 2) . '</strong></td>
            </tr>
        </tfoot>
    </table>';
    }
    else
    {
        Auth::setMessage("Invalid tab");
        require views_path('auth/denied');
        die;
    }
