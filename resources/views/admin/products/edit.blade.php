@extends('layouts.app')

@section('content')
<div class="container my-4">
    <div class="row mb-4">
        <div class="col">
            <h1>Edit Product</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.products.update', ['product' => $product->product_id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="name" class="form-label">Product Name *</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $product->name) }}" required>
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Description *</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" required>{{ old('description', $product->description) }}</textarea>
                    @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="price" class="form-label">Price (€) *</label>
                        <div class="input-group">
                            <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" step="0.01" min="0" value="{{ old('price', $product->price) }}" required>
                            <span class="input-group-text">€</span>
                            @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="stock_quantity" class="form-label">Stock *</label>
                        <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror" id="stock_quantity" name="stock_quantity" min="0" value="{{ old('stock_quantity', $product->stock_quantity) }}" required>
                        @error('stock_quantity')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="categories" class="form-label">Categories</label>
                    <div class="card card-body">
                        <div class="row">
                            @foreach($categories as $category)
                            <div class="col-md-4 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="categories[]" value="{{ $category->category_id }}" id="category{{ $category->category_id }}" 
                                        {{ in_array($category->category_id, old('categories', $product->categories->pluck('category_id')->toArray())) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="category{{ $category->category_id }}">
                                        {{ $category->name }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @error('categories')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label class="form-label">Current Images</label>
                    <div class="row g-3">
                        @forelse($product->images as $image)
                        <div class="col-md-3 col-6">
                            <div class="card h-100">
                                <img src="{{ asset($image->image_url) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 150px; object-fit: cover;">
                                <div class="card-body p-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="primary_image" id="image{{ $image->image_id }}" value="{{ $image->image_id }}" {{ $image->is_primary ? 'checked' : '' }}>
                                        <label class="form-check-label" for="image{{ $image->image_id }}">
                                            Primary Image
                                        </label>
                                    </div>
                                    <button type="button" class="btn btn-outline-danger btn-sm w-100 mt-2" 
                                            onclick="deleteImage({{ $product->product_id }}, {{ $image->image_id }})">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col">
                            <div class="alert alert-info">
                                No images available for this product.
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Add New Images</label>
                    <div class="input-group">
                        <input type="file" class="form-control @error('images.*') is-invalid @enderror" name="images[]" multiple accept="image/*">
                        @error('images.*')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-text">You can upload multiple images.</div>
                </div>
                
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Back to List</a>
                    <button type="submit" class="btn btn-primary">Update Product</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function deleteImage(productId, imageId) {
        if (confirm('Are you sure you want to delete this image?')) {
            // Create a form element to properly send the DELETE request
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/products/${productId}/images/${imageId}`;
            
            // Add CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            // Add method spoofing for DELETE
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            form.appendChild(methodField);
            
            // Append to body, submit, and remove
            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        }
    }
</script>
@endsection