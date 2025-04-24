<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { height: 100vh; }
        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #343a40;
        }
        .sidebar a {
            color: white;
            padding: 15px;
            display: block;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
    </style>
</head>
<body>
<div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar">
        <h4 class="text-white text-center py-3">oderSystem</h4>
        <a href="{{ route('orders.index') }}">Home</a>
        <a href="{{ route('orders.create') }}">Add Order</a>
        <a href="{{ route('products.create') }}">🛒 Add Product</a> <!-- New link -->
        <!-- Add more links if needed -->
    </div>

    <!-- Main Content -->
    <div class="flex-grow-1 p-4">
        @yield('content')
    </div>
</div>
</body>
</html>