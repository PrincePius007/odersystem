<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\Customer;


class OrderController extends Controller
{
    //index
    public function index()
    {
        $orders = Order::with(['customer', 'orderItems.product'])->get();
    
        // Format total price for each order
        foreach ($orders as $order) {
            $order->formatted_total_price = numberFormat($order->total_price);
        }
    
        return view('orders.index', compact('orders'));
    }
    

    //create
    public function create(){
        
    $customers = Customer::all();   // 👈 Fetch all customers
    $products = Product::all();     // 👈 Fetch products (for order items)

    return view('orders.create', compact('customers', 'products'));
    }


    //store
    public function store(Request $request)
    {
        // ✅ Validate form data
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'order_date' => 'required|date',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);
    
        // ✅ Get the selected customer
        $customer = Customer::findOrFail($request->customer_id);
    
        // ✅ Create the order
        $order = Order::create([
            'customer_id' => $customer->id,
            'order_date' => $request->order_date,
            'total_price' => 0, // temporary value
        ]);
    
        $total = 0;
    
        // ✅ Loop through products and create order items
        foreach ($request->products as $productData) {
            $product = Product::findOrFail($productData['product_id']);
            $qty = $productData['quantity'];
            $lineTotal = $product->price * $qty;
    
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $qty,
                'price' => $product->price,
                'total' => $lineTotal,
            ]);
    
            $total += $lineTotal;
        }
    
        // ✅ Update order with final total
        $order->update(['total_price' => $total]);
    
        return redirect()->route('orders.index')->with('success', 'Order created successfully!');
    }
    


//show
public function show(Order $order)
{
    $order->load(['customer', 'items.product']);
    return view('orders.show', compact('order'));
}

//edit
public function edit(Order $order)
{
    $order->load('orderItems.product'); // preload product with image

    $customers = Customer::all();
    $products = Product::all();

    return view('orders.edit', compact('order', 'customers', 'products'));
}

//update
public function update(Request $request, Order $order)
{
    $request->validate([
        'customer_name' => 'required|string',
        'customer_address' => 'required|string',
        'order_date' => 'required|date',
        'products' => 'required|array|min:1',
        'products.*.product_id' => 'required|exists:products,id',
        'products.*.quantity' => 'required|integer|min:1',
    ]);

    // Update customer
    $order->customer->update([
        'name' => $request->customer_name,
        'address' => $request->customer_address,
    ]);

    // Update order
    $order->update([
        'order_date' => $request->order_date,
    ]);

    // Delete old items
    $order->items()->delete();

    $total = 0;

    // Add updated items
    foreach ($request->products as $productData) {
        $price = \App\Models\Product::find($productData['product_id'])->price;
        $qty = $productData['quantity'];
        $lineTotal = $price * $qty;

        \App\Models\OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $productData['product_id'],
            'quantity' => $qty,
            'price' => $price,
            'total' => $lineTotal,
        ]);

        $total += $lineTotal;
    }

    $order->update(['total_price' => $total]);

    return redirect()->route('orders.index')->with('success', 'Order updated successfully!');
}



//delete
public function destroy(Order $order)
{
    // Delete order items first (optional if using cascade delete)
    $order->items()->delete();

    // Delete customer only if you want (careful: might be shared)
    // $order->customer()->delete();

    // Delete the order
    $order->delete();

    return redirect()->route('orders.index')->with('success', 'Order deleted successfully!');
}



}