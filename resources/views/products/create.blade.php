<div style="background-image: url('https://img.freepik.com/free-vector/organic-white-backgroun_1199-324.jpg?semt=ais_hybrid&w=740'); background-size: cover; background-position: center; height: 100%;">
@extends('layouts.app')

@section('content')
   
        <h2>Add New Product</h2>
    
        @if ($errors->any())
        <style> </style>
        <div class="alert alert-danger">
            <strong>Whoops!</strong> Please fix the following errors:<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
       <div>
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
        
                <!-- Product Name Input -->
                <div class="mb-3">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>
        
                <!-- Product Price Input -->
                <div class="mb-3">
                    <label for="price">Price</label>
                    <input type="number" name="price" id="price" class="form-control" required>
                </div>
        
                <!-- Product Image Input -->
                <div class="mb-3">
                    <label for="image">Image (optional)</label>
                    <input type="file" name="image" id="image" class="form-control">
                    
                </div>
        
                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary">Add Product</button>
                
            </form><br>
            <a href="{{route('products.item')}}"> <button type="submit" class="btn btn-primary">view product</button><a>
       </div>
        
   </div>
@endsection
