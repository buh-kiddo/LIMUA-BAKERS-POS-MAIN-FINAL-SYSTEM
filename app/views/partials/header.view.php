<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?=esc(APP_NAME)?></title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" type="text/css" href="assets/css/all.min.css">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="assets/css/main.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Global styles */
        body {
            font-family: 'Space Grotesk', sans-serif;
            background: #f8fafc;
            min-height: 100vh;
            color: #1e293b;
        }

        /* Card styles */
        .card {
            background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.8) 100%);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.5);
            box-shadow: 0 8px 32px 0 rgba(31,38,135,0.1);
            border-radius: 20px;
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .card:hover {
            box-shadow: 0 12px 40px 0 rgba(31,38,135,0.15);
        }

        .card-header {
            background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
            color: white;
            border: none;
            padding: 1rem 1.5rem;
        }

        /* Table styles */
        .table {
            color: #1e293b;
            margin: 0;
        }

        .table th {
            color: #64748b;
            font-weight: 500;
            border-color: rgba(0,0,0,0.1);
        }

        .table td {
            border-color: rgba(0,0,0,0.1);
        }

        .table-hover tbody tr:hover {
            background-color: rgba(79, 70, 229, 0.05);
        }

        /* Form styles */
        .form-control {
            border-radius: 10px;
            border: 1px solid rgba(0,0,0,0.1);
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
            border-color: #4f46e5;
        }

        /* Button styles */
        .btn {
            border-radius: 10px;
            padding: 0.5rem 1.5rem;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
            border: none;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);
        }

        /* Navigation styles */
        .navbar {
            background: white !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .navbar-brand {
            font-weight: 700;
            color: #4f46e5 !important;
        }

        .nav-link {
            color: #64748b !important;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: #4f46e5 !important;
        }

        .nav-link.active {
            color: #4f46e5 !important;
            background: rgba(79, 70, 229, 0.1);
            border-radius: 10px;
        }

        /* Badge styles */
        .badge {
            padding: 0.5em 1em;
            border-radius: 10px;
            font-weight: 500;
        }

        /* Link styles */
        a {
            color: #4f46e5;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        a:hover {
            color: #3b82f6;
        }
    </style>
</head>
<body>
    <?php 
        $no_nav[] = "login";
        if(!in_array($controller, $no_nav)):
            require views_path('partials/nav');
        endif;
    ?>
    <div class="container-fluid" style="min-width: 350px;">
