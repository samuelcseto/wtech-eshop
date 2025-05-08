@extends('layouts.app')

@section('title', $product->name . ' - Domáce Dekorácie')

@section('content')
<!-- Product Detail Section -->
<section class="product-detail-container">
    <div class="container">
        <div class="row">
            <!-- Product Gallery - Left Column -->
            <div class="col-md-6">
                <div class="product-gallery">
                    <div class="row">
                        <div class="col-2">
                            <!-- Thumbnails -->
                            <div class="product-thumbnails">
                                @if($product->images->count() > 0)
                                    @foreach($product->images as $index => $image)
                                        <div class="product-thumbnail {{ $index == 0 ? 'active' : '' }}">
                                            <img src="{{ asset($image->image_url) }}" alt="{{ $product->name }} - Image {{ $index + 1 }}" />
                                        </div>
                                    @endforeach
                                @else
                                    <div class="product-thumbnail active">
                                        <img src="https://via.placeholder.com/300x200" alt="{{ $product->name }}" />
                                    </div>
                                @endif
                            </div>
                            <!-- Thumbnail navigation arrows -->
                            @if($product->images->count() > 3)
                            <div class="product-thumbnail-arrows">
                                <div class="thumbnail-arrow up">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-up" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M7.646 4.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1-.708.708L8 5.707l-5.646 5.647a.5.5 0 0 1-.708-.708l6-6z" />
                                    </svg>
                                </div>
                                <div class="thumbnail-arrow down">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-down" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" />
                                    </svg>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="col-10">
                            <!-- Main Image -->
                            <div class="product-main-image">
                                @if($product->primaryImage)
                                    <img src="{{ asset($product->primaryImage->image_url) }}" alt="{{ $product->name }}" id="mainProductImage" />
                                @else
                                    <img src="https://via.placeholder.com/300x200" alt="{{ $product->name }}" id="mainProductImage" />
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Info - Right Column -->
            <div class="col-md-6">
                <h1 class="product-title">{{ $product->name }}</h1>
                <p class="product-description">{{ $product->description }}</p>

                <!-- Product Specifications -->
                <div class="product-specs">
                    <div class="product-spec-row">
                        <div class="product-spec-label">Cena</div>
                        <div class="product-spec-value">
                            <span>{{ number_format($product->price, 2, ',', ' ') }} €</span>
                        </div>
                    </div>
                    @if($product->categories->isNotEmpty())
                    <div class="product-spec-row">
                        <div class="product-spec-label">Kategória</div>
                        <div class="product-spec-value">
                            <span>{{ $product->categories->first()->name }}</span>
                        </div>
                    </div>
                    @endif
                    <div class="product-spec-row">
                        <div class="product-spec-label">Dostupnosť</div>
                        <div class="product-spec-value">
                            <span>{{ $product->stock_quantity > 0 ? 'Skladom (' . $product->stock_quantity . ' ks)' : 'Na objednávku' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Add to Cart Button -->
                <form action="{{ route('cart.add', $product->product_id) }}" method="POST">
                    @csrf
                    <div class="product-quantity-container">
                        <label for="quantity">Množstvo:</label>
                        <div class="product-quantity-control">
                            <button type="button" class="product-quantity-btn" id="decreaseQuantity">−</button>
                            <input type="number" id="quantity" name="quantity" value="1" min="1" max="{{ $product->stock_quantity }}" class="product-quantity-input" required />
                            <button type="button" class="product-quantity-btn" id="increaseQuantity">+</button>
                        </div>
                    </div>
                    <button type="submit" class="product-cart-button" {{ $product->stock_quantity <= 0 ? 'disabled' : '' }}>
                        {{ $product->stock_quantity > 0 ? 'Pridať do košíka' : 'Nedostupné' }}
                    </button>
                </form>

                <!-- Product Information Sections -->
                <div class="product-info-section">
                    <div class="product-info-header" data-bs-toggle="collapse" data-bs-target="#detailsCollapse" aria-expanded="true">
                        <span>Podrobnosti o produkte</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-dash" viewBox="0 0 16 16">
                            <path d="M4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8z" />
                        </svg>
                    </div>
                    <div class="product-info-body collapse show" id="detailsCollapse">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                </div>

                {{-- <div class="product-info-section">
                    <div class="product-info-header" data-bs-toggle="collapse" data-bs-target="#shippingCollapse">
                        <span>Doprava a vrátenie</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                        </svg>
                    </div>
                    <div class="product-info-body collapse" id="shippingCollapse">
                        <p><strong>Preprava:</strong></p>
                        <ul class="product-info-list">
                            <li class="product-info-list-item">Je na Slovensku a v Českej republike zadarmo</li>
                        </ul>
                        <p><strong>Vrátenie:</strong></p>
                        <ul class="product-info-list">
                            <li class="product-info-list-item">Na vrátenie alebo výmenu sú oprávnené nepoužívané položky do 14 dní od nákupu. Položky z konečného predaja nie sú oprávnené na vrátenie alebo výmenu.</li>
                        </ul>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
    /* Product detail page specific styles */
    .product-detail-container {
        padding: 40px 0;
    }
    .product-gallery {
        position: relative;
        margin-bottom: 30px;
    }
    .product-thumbnails {
        display: flex;
        flex-direction: column;
        gap: 10px;
        max-height: 400px;
        overflow-y: auto;
    }
    .product-thumbnail {
        width: 100px;
        height: 80px;
        background-color: #e6e6e6;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .product-thumbnail img {
        max-width: 100%;
        max-height: 100%;
    }
    .product-thumbnail.active {
        border: 2px solid #333;
    }
    .product-main-image {
        height: 400px;
        background-color: #e6e6e6;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
    }
    .product-main-image img {
        max-width: 100%;
        max-height: 100%;
    }
    .product-thumbnail-arrows {
        position: absolute;
        left: 35px;
        width: 30px;
        z-index: 1;
    }
    .thumbnail-arrow {
        width: 30px;
        height: 30px;
        background-color: rgba(255, 255, 255, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }
    .thumbnail-arrow.up {
        top: 0;
    }
    .thumbnail-arrow.down {
        bottom: 0;
    }
    .product-title {
        font-size: 32px;
        margin-bottom: 15px;
        font-weight: 500;
    }
    .product-description {
        font-size: 16px;
        color: #555;
        margin-bottom: 30px;
    }
    .product-specs {
        margin-bottom: 30px;
    }
    .product-spec-row {
        display: flex;
        border: 1px solid #ddd;
        margin-bottom: 10px;
    }
    .product-spec-label {
        width: 200px;
        padding: 12px 15px;
        background-color: #f9f9f9;
        font-weight: 500;
    }
    .product-spec-value {
        flex-grow: 1;
        padding: 12px 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .product-cart-button {
        display: block;
        width: 100%;
        padding: 15px;
        font-size: 18px;
        background-color: #888;
        color: white;
        border: none;
        text-align: center;
        margin-bottom: 30px;
        transition: background-color 0.3s;
    }
    .product-cart-button:hover {
        background-color: #666;
    }
    .product-info-section {
        border: 1px solid #ddd;
        margin-bottom: 15px;
    }
    .product-info-header {
        padding: 15px;
        background-color: #fff;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 18px;
        font-weight: 500;
    }
    .product-info-body {
        padding: 20px;
        border-top: 1px solid #ddd;
    }
    .product-info-list {
        margin-bottom: 0;
        padding-left: 20px;
    }
    .product-info-list-item {
        margin-bottom: 5px;
    }
    .product-quantity-container {
        margin-bottom: 20px;
    }
    .product-quantity-control {
        display: flex;
        align-items: center;
    }
    .product-quantity-btn {
        background-color: #ddd;
        border: none;
        padding: 10px;
        cursor: pointer;
    }
    .product-quantity-input {
        width: 60px;
        text-align: center;
        border: 1px solid #ddd;
        margin: 0 5px;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Handle thumbnail clicks
        const thumbnails = document.querySelectorAll('.product-thumbnail');
        const mainImage = document.getElementById('mainProductImage');
        
        thumbnails.forEach(function (thumbnail) {
            thumbnail.addEventListener('click', function () {
                // Remove active class from all thumbnails
                thumbnails.forEach((t) => t.classList.remove('active'));

                // Add active class to clicked thumbnail
                this.classList.add('active');

                // Update the main image
                if (mainImage) {
                    mainImage.src = this.querySelector('img').src;
                }
            });
        });

        // Handle collapse icons
        const infoHeaders = document.querySelectorAll('.product-info-header');
        infoHeaders.forEach(function (header) {
            const target = header.getAttribute('data-bs-target');
            if (target) {
                const collapseElement = document.querySelector(target);
                const icon = header.querySelector('svg');
                
                header.addEventListener('click', function () {
                    // Wait for collapse animation
                    setTimeout(function () {
                        if (collapseElement.classList.contains('show')) {
                            // Change to minus icon
                            icon.innerHTML = '<path d="M4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8z" />';
                        } else {
                            // Change to plus icon
                            icon.innerHTML = '<path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />';
                        }
                    }, 10);
                });
            }
        });

        // Handle quantity buttons
        const decreaseQuantityBtn = document.getElementById('decreaseQuantity');
        const increaseQuantityBtn = document.getElementById('increaseQuantity');
        const quantityInput = document.getElementById('quantity');

        decreaseQuantityBtn.addEventListener('click', function () {
            let currentValue = parseInt(quantityInput.value);
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
            }
        });

        increaseQuantityBtn.addEventListener('click', function () {
            let currentValue = parseInt(quantityInput.value);
            if (currentValue < parseInt(quantityInput.max)) {
                quantityInput.value = currentValue + 1;
            }
        });
    });
</script>
@endsection