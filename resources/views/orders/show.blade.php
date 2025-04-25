@extends('layouts.app')

@section('content')

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Details</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #fdfbfb, #ebedee);
            padding: 40px;
            margin: 0;
            color: #333;
        }

        h1 {
            font-size: 28px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 20px;
            text-align: center;
        }

        p {
            font-size: 16px;
            margin: 8px 0;
        }

        strong {
            color: #374151;
        }

        .order-details {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
            padding: 30px;
        }

        table {
            width: 100%;
            margin-top: 25px;
            border-collapse: collapse;
        }

        th, td {
            padding: 14px;
            text-align: center;
            border-bottom: 1px solid #e5e7eb;
        }

        th {
            background-color: #f3f4f6;
            font-weight: 600;
            font-size: 15px;
        }

        td {
            font-size: 14px;
        }

        h3 {
            margin-top: 30px;
            font-size: 20px;
            font-weight: 600;
        }

        .total {
            font-size: 20px;
            font-weight: bold;
            color: #10b981;
            margin-top: 30px;
            text-align: right;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            font-weight: 500;
            color: #3b82f6;
            transition: color 0.2s ease;
        }

        a:hover {
            color: #2563eb;
        }

        @media (max-width: 768px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }

            thead {
                display: none;
            }

            tr {
                margin-bottom: 15px;
                border: 1px solid #e5e7eb;
                border-radius: 10px;
                padding: 10px;
            }

            td {
                text-align: left;
                padding-left: 50%;
                position: relative;
            }

            td::before {
                content: attr(data-label);
                position: absolute;
                left: 10px;
                top: 10px;
                font-weight: 600;
                color: #6b7280;
            }

            .total {
                text-align: left;
            }
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

<div class="order-details">
    <h1>📄 Order #{{ $order->id }}</h1>

    <p><strong>👤 Customer:</strong> {{ $order->customer->name }}</p>
    <p><strong>🏠 Address:</strong> {{ $order->customer->address }}</p>
    <p><strong>📅 Order Date:</strong> {{ $order->order_date }}</p>

    <h3>🛍️ Items</h3>

    <table>
        <thead>
            <tr>
                <th>Sl. No</th>
                <th>Product</th>
                <th>Qty</th>
                <th>image</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $index => $item)
                <tr>
                    <td data-label="Sl. No">{{ $index + 1 }}</td>
                    <td data-label="Product">{{ $item->product->name }}</td>
                    <td data-label="Qty">{{ $item->quantity }}</td>
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
                    <td data-label="Price">₹{{ number_format($item->price, 2) }}</td>
                    <td data-label="Total">₹{{ number_format($item->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">Total: ₹{{ number_format($order->total_price, 2) }}</div>

    <a href="{{ route('orders.index') }}">← Back to Orders</a>
</div>

</body>
</html>
@endsection