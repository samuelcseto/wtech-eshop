@extends('layouts.app')

@section('content')
<div class="container my-4">
    <div class="row mb-4">
        <div class="col">
            <h1>Product Details</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Details</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 mb-4">
            @if($product->images->isNotEmpty())
                <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @foreach($product->images as $index => $image)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <img src="{{ asset($image->image_url) }}" class="d-block w-100" alt="{{ $product->name }}" style="height: 400px; object-fit: contain;">
                            </div>
                        @endforeach
                    </div>
                    @if($product->images->count() > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    @endif
                </div>
                
                @if($product->images->count() > 1)
                    <div class="row mt-2">
                        @foreach($product->images as $index => $image)
                            <div class="col-3 mb-2">
                                <img src="{{ asset($image->image_url) }}" class="img-thumbnail {{ $image->is_primary ? 'border-primary' : '' }}" alt="{{ $product->name }}" 
                                     style="width: 100%; height: 80px; object-fit: cover; cursor: pointer;" 
                                     onclick="document.querySelectorAll('#productCarousel .carousel-item')[{{ $index }}].classList.add('active'); 
                                              document.querySelectorAll('#productCarousel .carousel-item:not(:nth-child({{ $index + 1 }}))').forEach(item => item.classList.remove('active'));">
                                @if($image->is_primary)
                                    <div class="text-center">
                                        <span class="badge bg-primary mt-1">Primary</span>
                                    </div>
                                @endif
                                <form action="{{ route('admin.products.deleteImage', ['product' => $product->product_id, 'image' => $image->image_id]) }}" method="POST" class="mt-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm w-100" onclick="return confirm('Are you sure you want to delete this image?');">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endif
            @else
                <div class="card h-100 d-flex align-items-center justify-content-center bg-light">
                    <div class="text-center p-5">
                        <i class="bi bi-image" style="font-size: 5rem;"></i>
                        <p class="mt-3">No images available</p>
                    </div>
                </div>
            @endif
        </div>
        
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Product Information</h5>
                    <div>
                        <a href="{{ route('admin.products.edit', ['product' => $product->product_id]) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <form action="{{ route('admin.products.destroy', ['product' => $product->product_id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this product?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <h2>{{ $product->name }}</h2>
                    
                    <div class="mb-3">
                        <h5 class="text-primary">€{{ number_format($product->price, 2, ',', ' ') }}</h5>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-muted">Stock:</h6>
                        <p>{{ $product->stock_quantity }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-muted">Categories:</h6>
                        @if($product->categories->isNotEmpty())
                            <div>
                                @foreach($product->categories as $category)
                                    <span class="badge bg-secondary me-1">{{ $category->name }}</span>
                                @endforeach
                            </div>
                        @else
                            <p>No categories assigned</p>
                        @endif
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-muted">Description:</h6>
                        <div class="overflow-auto" style="max-height: 200px;">
                            <p>{{ $product->description }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <h6 class="text-muted">Product ID:</h6>
                        <p class="mb-0">{{ $product->product_id }}</p>
                    </div>
                </div>
                <div class="card-footer text-muted">
                    <div class="row">
                        <div class="col-6">
                            Created: {{ $product->created_at->format('d.m.Y H:i') }}
                        </div>
                        <div class="col-6 text-end">
                            Updated: {{ $product->updated_at->format('d.m.Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="d-flex justify-content-between mt-4">
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
        <a href="{{ route('products.show', ['product' => $product->product_id]) }}" class="btn btn-info" target="_blank">
            <i class="bi bi-eye"></i> View on Site
        </a>
    </div>
</div>
@endsection