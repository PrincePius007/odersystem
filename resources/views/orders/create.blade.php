@extends('layouts.app')

@section('content')

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Order</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
           
            color: #333;
            padding: 40px;
            margin: 0;
            
    background-image: url("https://img.freepik.com/premium-vector/pastel-color-background-abstract-design_336924-5234.jpg");
    background-size: cover;           /* Cover full screen */
    background-repeat: no-repeat;     /* No tiling */
    background-position: center;      /* Center the image */
    background-attachment: fixed;   
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
            color: #121c2f;

        }

        form {
            max-width: 900px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        label {
            font-weight: 500;
            display: block;
            margin-top: 20px;
            margin-bottom: 5px;
        }

        input, select, textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 15px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        textarea[readonly] {
            background-color: #f1f3f5;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #e9ecef;
        }

        thead {
            background-color: #f8f9fa;
            font-weight: 600;
        }

        tr:hover {
            background-color: #f1f3f5;
        }

        .remove-row {
            background: #ffe3e3;
            border: none;
            padding: 6px 10px;
            border-radius: 4px;
            cursor: pointer;
            color: #c92a2a;
        }

        #add-row {
            margin-top: 15px;
            background-color: #d0ebff;
            color: #1c7ed6;
            border: none;
            padding: 8px 14px;
            font-weight: 500;
            font-size: 14px;
            border-radius: 6px;
            cursor: pointer;
        }

        #add-row:hover {
            background-color: #a5d8ff;
        }

        #grand-total {
            font-size: 18px;
            font-weight: 600;
            color: #2f9e44;
        }

        button[type="submit"] {
            margin-top: 30px;
            padding: 12px 20px;
            background-color: #4c6ef5;
            color: white;
            border: none;
            font-size: 16px;
            font-weight: 500;
            border-radius: 6px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #4263eb;
        }

        .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>

<h1>Create New Order</h1>

<form method="POST" action="{{ route('orders.store') }}">
    @csrf

    <label for="customer_id">Customer Name:</label>
    <select name="customer_id" id="customer_id" required>
        <option value="">-- Select Customer --</option>
        @foreach($customers as $customer)
            <option value="{{ $customer->id }}" data-address="{{ $customer->address }}">
                {{ $customer->name }}
            </option>
        @endforeach
    </select>

    <label for="customer_address">Address:</label>
    <textarea name="customer_address" id="customer_address" rows="3" readonly></textarea>

    <label>Order Date:</label>
    <input type="date" name="order_date" required>

    <h3 style="margin-top: 30px;">Products:</h3>
    <table>
        <thead>
        <tr>
            <th>SL No</th>
            <th>Product</th>
            <th>Image</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Total</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody id="product-rows">
        <tr>
            <td>1</td>
            <td>
                <select name="products[0][product_id]" class="product-select" required>
                    <option value="">-- Select --</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}"
                                data-price="{{ $product->price }}"
                                data-image="{{ asset('storage/' . $product->image) }}">
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </td>
            <td>
                <img src="" class="product-image">
            </td>
            <td><input type="number" name="products[0][quantity]" class="qty" value="1" min="1" required></td>
            <td><input type="text" name="products[0][price]" class="price" readonly></td>
            <td><input type="text" name="products[0][total]" class="total" readonly></td>
            <td><button type="button" class="remove-row">🗑️</button></td>
        </tr>
        </tbody>
    </table>

    <button type="button" id="add-row">➕ Add Product</button><br><br>

    <strong>Total Order Price: ₹<span id="grand-total">0.00</span></strong><br><br>

    <button type="submit">💾 Save Order</button>
</form>

<script>
    document.getElementById('customer_id').addEventListener('change', function () {
        const selected = this.options[this.selectedIndex];
        const address = selected.getAttribute('data-address');
        document.getElementById('customer_address').value = address || '';
    });

    let rowCount = 1;

    function updateTotals() {
        let total = 0;
        document.querySelectorAll('#product-rows tr').forEach((row) => {
            const qty = parseFloat(row.querySelector('.qty')?.value || 0);
            const price = parseFloat(row.querySelector('.price')?.value || 0);
            const lineTotal = qty * price;
            row.querySelector('.total').value = lineTotal.toFixed(2);
            total += lineTotal;
        });
        document.getElementById('grand-total').textContent = total.toFixed(2);
    }

    function addRow() {
        const table = document.getElementById('product-rows');
        const newRow = table.rows[0].cloneNode(true);
        newRow.querySelectorAll('input, select').forEach(el => {
            el.name = el.name.replace(/\[\d+\]/, `[${rowCount}]`);
            if (el.tagName === 'INPUT') el.value = (el.classList.contains('qty')) ? 1 : '';
            if (el.classList.contains('total') || el.classList.contains('price')) el.value = '';
        });
        newRow.querySelector('.product-image').src = '';
        newRow.querySelector('td:first-child').textContent = rowCount + 1;
        table.appendChild(newRow);
        rowCount++;
    }

    document.getElementById('add-row').addEventListener('click', addRow);

    document.addEventListener('change', function (e) {
        if (e.target.classList.contains('product-select')) {
            const selected = e.target.selectedOptions[0];
            const price = selected.dataset.price || 0;
            const image = selected.dataset.image || '';
            const row = e.target.closest('tr');
            row.querySelector('.price').value = price;
            row.querySelector('.product-image').src = image;
            updateTotals();
        }
        if (e.target.classList.contains('qty')) {
            updateTotals();
        }
    });

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-row')) {
            const row = e.target.closest('tr');
            const table = document.getElementById('product-rows');
            if (table.rows.length > 1) {
                row.remove();
                updateTotals();
            }
        }
    });
</script>

</body>
</html>
@endsection