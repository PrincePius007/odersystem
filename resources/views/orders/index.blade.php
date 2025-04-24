@extends('layouts.app')

@section('content')

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Orders</title>
    <style>
        body {
            margin: 0;
            padding: 40px;
            font-family: 'Helvetica Neue', sans-serif;
            background-color: #f9f9fb;
            color: #333;
        }

        h1 {
            text-align: center;
            font-weight: 600;
            margin-bottom: 30px;
            color: #2c3e50;
        }

        .add-btn {
            display: block;
            margin: 0 auto 30px auto;
            padding: 10px 20px;
            background-color: #4c6ef5;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 15px;
            font-weight: 500;
            text-align: center;
            width: max-content;
            transition: background-color 0.3s ease;
        }

        .add-btn:hover {
            background-color: #3b5bdb;
        }

        table {
            width: 90%;
            margin: 0 auto;
            border-collapse: collapse;
            background-color: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 6px 24px rgba(0, 0, 0, 0.05);
        }

        th, td {
            padding: 14px 18px;
            text-align: center;
            border-bottom: 1px solid #f0f0f0;
        }

        thead {
            background-color: #f1f3f5;
        }

        th {
            font-size: 14px;
            color: #495057;
        }

        td {
            font-size: 14px;
            color: #343a40;
        }

        tbody tr:hover {
            background-color: #f8f9fa;
        }

        .actions a,
        .actions button {
            margin: 0 4px;
            font-size: 13px;
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-weight: 500;
        }

        .actions a.view {
            background-color: #e7f5ff;
            color: #1c7ed6;
        }

        .actions a.edit {
            background-color: #fff3bf;
            color: #f08c00;
        }

        .actions button.delete {
            background-color: #ffe3e3;
            color: #fa5252;
        }

        .actions a:hover,
        .actions button:hover {
            opacity: 0.85;
        }

        form {
            display: inline;
        }

        .no-orders {
            text-align: center;
            padding: 20px;
            color: #888;
        }

        .product-img {
            width: 50px;
            height: 50px;
            border-radius: 6px;
            object-fit: cover;
        }
    </style>
</head>
<body>

<h1>Orders</h1>

<a href="{{ route('orders.create') }}" class="add-btn">➕ Add New Order</a>

<table>
    <thead>
        <tr>
            <th>Sl. No</th>
            <th>Order No</th>
            <th>Date</th>
            <th>Customer</th>
            <th>Total Price</th>
            <th>Product Image</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($orders as $index => $order)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>#{{ $order->id }}</td>
                <td>{{ $order->order_date }}</td>
                <td>{{ $order->customer->name }}</td>
                <td>₹{{ numberFormat($order->total_price) }}</td>
                <td>
                    @php
                    $firstOrderItem = optional($order->orderItems)->first();
                    $imagePath = optional($firstOrderItem?->product)->image;
                    @endphp
                    @if($imagePath)
                        <img src="{{ asset('storage/' . $imagePath) }}" alt="Product Image" class="product-img">
                    @else
                        N/A
                    @endif
                </td>
                <td class="actions">
                    <a href="{{ route('orders.show', $order) }}" class="view">View</a>
                    <a href="{{ route('orders.edit', $order) }}" class="edit">Edit</a>
                    <form action="{{ route('orders.destroy', $order) }}" method="POST" onsubmit="return confirm('Delete this order?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="no-orders">No orders found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

</body>
</html>

@endsection
