<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Order</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
            margin: 0;
            padding: 30px;
            color: #333;
        }

        h1 {
            text-align: center;
            font-weight: 600;
            color: #1f2937;
        }

        form {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            max-width: 1000px;
            margin: 30px auto;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        }

        label {
            display: block;
            margin-top: 20px;
            font-weight: 500;
        }

        input[type="text"],
        input[type="number"],
        input[type="date"],
        textarea,
        select {
            width: 100%;
            padding: 12px;
            margin-top: 6px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        input:focus,
        textarea:focus,
        select:focus {
            border-color: #6366f1;
            outline: none;
        }

        textarea {
            resize: vertical;
        }

        table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
            overflow-x: auto;
        }

        th, td {
            padding: 14px;
            text-align: center;
            border: 1px solid #e5e7eb;
        }

        th {
            background-color: #f3f4f6;
            font-weight: 600;
        }

        .remove-row {
            background-color: #ef4444;
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .remove-row:hover {
            background-color: #dc2626;
        }

        #add-row {
            margin-top: 20px;
            background-color: #3b82f6;
            color: white;
            padding: 10px 20px;
            font-weight: 500;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        #add-row:hover {
            background-color: #2563eb;
        }

        button[type="submit"] {
            background-color: #10b981;
            color: white;
            padding: 12px 24px;
            font-size: 16px;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 30px;
            transition: background-color 0.2s ease;
        }

        button[type="submit"]:hover {
            background-color: #059669;
        }

        strong {
            font-size: 18px;
            display: block;
            margin-top: 25px;
            text-align: right;
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
        }
    </style>
</head>
<body>

<h1>✏️ Edit Order #{{ $order->id }}</h1>

<form method="POST" action="{{ route('orders.update', $order->id) }}">
    @csrf
    @method('PUT')

    <label>Customer Name</label>
    <input type="text" name="customer_name" value="{{ $order->customer->name }}" required>

    <label>Address</label>
    <textarea name="customer_address" rows="3" required>{{ $order->customer->address }}</textarea>

    <label>Order Date</label>
    <input type="date" name="order_date" value="{{ $order->order_date }}" required>

    <h3 style="margin-top: 30px;">🛒 Products</h3>
    <table id="products-table">
        <thead>
            <tr>
                <th>SL No</th>
                <th>Product</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="product-rows">
            @foreach ($order->items as $index => $item)
            <tr>
                <td data-label="SL No">{{ $index + 1 }}</td>
                <td data-label="Product">
                    <select name="products[{{ $index }}][product_id]" class="product-select" required>
                        <option value="">-- Select --</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" data-price="{{ $product->price }}"
                                {{ $product->id == $item->product_id ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td data-label="Qty"><input type="number" name="products[{{ $index }}][quantity]" class="qty" value="{{ $item->quantity }}" min="1" required></td>
                <td data-label="Price"><input type="text" name="products[{{ $index }}][price]" class="price" value="{{ $item->price }}" readonly></td>
                <td data-label="Total"><input type="text" name="products[{{ $index }}][total]" class="total" value="{{ $item->total }}" readonly></td>
                <td data-label="Action"><button type="button" class="remove-row">🗑️</button></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <button type="button" id="add-row">➕ Add Product</button>

    <strong>Total Order Price: ₹<span id="grand-total">0.00</span></strong>

    <button type="submit">💾 Update Order</button>
</form>

<script>
    let rowCount = {{ count($order->items) }};
    const products = @json($products);

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
            if (el.tagName === 'INPUT') el.value = '';
            if (el.tagName === 'SELECT') el.selectedIndex = 0;
        });
        newRow.querySelector('td:first-child').textContent = rowCount + 1;
        table.appendChild(newRow);
        rowCount++;
        updateTotals();
    }

    document.getElementById('add-row').addEventListener('click', addRow);

    document.addEventListener('change', function (e) {
        if (e.target.classList.contains('product-select')) {
            const price = e.target.selectedOptions[0].dataset.price || 0;
            const row = e.target.closest('tr');
            row.querySelector('.price').value = price;
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

    window.onload = updateTotals;
</script>

</body>
</html>
