<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Items</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Whoops!</strong> Please fix the following errors:<br><br>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="container mt-4">
    <div class="row">
    @foreach($products as $product)
<div class="col-md-4 mb-4">
    <div class="card" style="width: 18rem;">

        @if($product->image && file_exists(storage_path('app/public/' . $product->image)))
        <img src="{{ asset('storage/' . $product->image) }}" alt="Product Image" class="card-img-top">
    @else
        <img src="https://via.placeholder.com/150" class="card-img-top" alt="No Image">
    @endif
    




        <div class="card-body">
            <h5 class="card-title">{{ $product->name }}</h5>
            <p class="card-text">Price: ₹{{ $product->price }}</p>

            <!-- Edit Button -->
            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning">Edit</a>

            <!-- Delete Button -->
            <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </div>
    </div>
</div>
@endforeach

    </div>

    <a href="{{ route('products.create') }}" class="btn btn-primary mt-4">Add New Product</a>
</div>

</body>
</html>