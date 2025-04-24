<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
{
    // Show the form to create a new product
    public function create()
    {
        return view('products.create');
    }

    // Show all product items
    public function item()
    {
        $products = Product::all();
        return view('products.item', compact('products'));
    }

    // Handle the submission of the new product form
    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            // 'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:50000', // validate image file
        ]);

        // Initialize the imagePath to null in case no image is uploaded
        $imagePath = null;

        // Check if the 'image' file is present in the request
        if ($request->hasFile('image')) {
            // Store the image in the 'public/products' directory and get the storage path
            $imagePath = $request->file('image')->store('products', 'public');
        }

        // Create a new product instance and assign the data
        $product = new Product();
        $product->name = $request->name;
        $product->price = $request->price;
        $product->image = $imagePath; // Save the image path in the database
        $product->save(); // Save the product to the database

        // Redirect the user back to the 'item' page with a success message
        return redirect()->route('products.item')->with('success', 'Product added successfully!');
    }

    // Show products again (not strictly needed if using item())
    public function showItem()
    {
        $products = Product::all();
        return view('products.item', compact('products'));
    }

    // Edit form
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('products.edit', compact('product'));
    }

    // Handle update
    public function update(Request $request, $id)
    {
        // Find the product by ID
        $product = Product::findOrFail($id);
        
        // Validate the incoming request
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5000', // validate image file
        ]);
        
        // Update the product details
        $product->name = $request->input('name');
        $product->price = $request->input('price');
        
        // Check if a new image has been uploaded
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($product->image && file_exists(storage_path('app/public/products/' . $product->image))) {
                unlink(storage_path('app/public/products/' . $product->image));
            }
    
            // Store the new image
            $imageName = time() . '.' . $request->image->extension();
            $request->image->storeAs('products', $imageName, 'public');
            
            // Save the relative image path in the database
            $product->image = 'products/' . $imageName;
        }
    
        // Save the updated product details
        $product->save();
        
        // Redirect back to the product items page with a success message
        return redirect()->route('products.item')->with('success', 'Product updated successfully!');
    }
    
    

    // Delete product and image
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Delete image from folder
        if ($product->image && file_exists(storage_path('app/public/products/' . $product->image))) {
            unlink(storage_path('app/public/products/' . $product->image));
        }

        // Delete the product record from the database
        $product->delete();

        // Redirect back to product items page
        return redirect()->route('products.item')->with('success', 'Product deleted successfully!');
    }

    public function testImageResize()
    {
    $img = Image::make(public_path('example.jpg'))->resize(300, 300);
    $img->save(public_path('resized.jpg'));

    return 'Image resized and saved!';
}

}