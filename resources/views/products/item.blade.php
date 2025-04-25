<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Items</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>


body {
    margin: 0;
    padding: 0;
    height: 100vh; /* Full viewport height */
    font-family: 'Helvetica Neue', sans-serif;

    background-image: url("https://img.freepik.com/free-vector/organic-white-backgroun_1199-324.jpg?semt=ais_hybrid&w=740");
    background-size: cover;           /* Cover full screen */
    background-repeat: no-repeat;     /* No tiling */
    background-position: center;      /* Center the image */
    background-attachment: fixed;     /* Optional: adds parallax feel */
}

        .card-img-top {
            height: 200px;
            object-fit: cover;
        }
        .card {
            height: 100%;
        }
        .card-body {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
    </style>
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
            <div class="col-md-4 mb-4 d-flex align-items-stretch">
                <div class="card w-100">
                    @if($product->image && file_exists(storage_path('app/public/' . $product->image)))
                        <img src="{{ asset('storage/' . $product->image) }}" alt="Product Image" class="card-img-top">
                    @else
                        <img src="https://via.placeholder.com/150" class="card-img-top" alt="No Image">
                    @endif

                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text">Price: ₹{{ $product->price }}</p>
                        </div>

                        <div class="mt-3">
                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning w-50 mb-2">Edit</a>

                            <form action="{{ route('products.destroy', $product->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-30">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <a href="{{ route('products.create') }}" class="btn btn-primary mt-4">Add New Product</a>
</div>

</body>
</html>
