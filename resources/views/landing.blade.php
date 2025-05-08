@extends('layouts.app')

@section('title', 'Domov')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="placeholder-img" style="width: 100%; height: 350px">
                    <img src="{{ asset('images/banners/landing-banner.jpg') }}" alt="Hlavný banner" style="width: 100%; height: 100%; object-fit: cover" />
                </div>
                <h2 class="mt-4">Elegantné dizajnové riešenia pre domácnosť</h2>
                <p>Premeňte svoj životný priestor s našou starostlivo vybranou kolekciou nábytku a dekorácií</p>
                <button class="btn btn-outline-dark">Objavte viac</button>
            </div>
        </div>
    </section>

    <!-- Classics Section -->
    <section class="container mt-5">
        <h3>Klasický Štýl</h3>
        <div class="row mt-3">
            @foreach ($products as $product)
                <div class="col-md-4">
                    <a href="{{ route('products.show', $product) }}" class="text-decoration-none">
                        <div class="product-card">
                            <div class="placeholder-img" style="height: 250px">
                                <img 
                                    src="{{ 
                                        $product->primaryImage?->image_url
                                        ? asset($product->primaryImage->image_url)
                                        : 'https://via.placeholder.com/300x250' 
                                    }}"
                                    alt="{{ $product->name }}"
                                />
                            </div>
                            <div class="product-info">
                                <h6>{{ $product->name }}</h6>
                                <p class="text-muted">{{ Str::limit($product->description, 30) }}</p>
                                <p class="fw-bold">{{ number_format($product->price, 2, ',', ' ') }} €</p>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Categories Section -->
    <section class="category-section">
        <div class="container">
            <h3>Kategórie</h3>
            <div class="row mt-3">
                @foreach ($categories as $index => $category)
                    <div class="col-md-6 col-lg-{{ $index % 2 == 0 ? '4' : '8' }}">
                        <a href="{{ route('categories.show', $category) }}" class="text-decoration-none">
                            <div class="category-card">
                                <div class="placeholder-img" style="height: 300px">
                                    <img 
                                        src="{{ 
                                            $category->image_url
                                            ? asset($category->image_url)
                                            : 'https://via.placeholder.com/300x300' 
                                        }}"
                                        alt="{{ $category->name }}"
                                    />
                                </div>
                                <div class="category-label">
                                    <span>{{ $category->name }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Secondary Banner -->
    <section class="hero-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <h4 class="mt-4">Kvalitné remeselné spracovanie pre každý domov</h4>
                    <p>Objavte naše nadčasové dizajny, ktoré spájajú funkčnosť s estetickým vzhľadom</p>
                    <button class="btn btn-outline-dark">Zobraziť viac</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Styles Section -->
    <section class="style-section">
        <div class="container">
            <h3>Podľa štýlov</h3>
            <div class="row mt-3">
                @foreach (['Klasický', 'Moderný', 'Rustikálny', 'Industriálny'] as $style)
                    <div class="col-md-3">
                        <a href="#styl-{{ Str::slug($style) }}" class="text-decoration-none">
                            <div class="style-card">
                                <div class="placeholder-img" style="height: 200px">
                                    <img src="https://via.placeholder.com/300x200" alt="{{ $style }} štýl" />
                                </div>
                                <div class="style-info">
                                    <h6>{{ $style }}</h6>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection

@section('styles')
<style>
    .hero-section {
        text-align: center;
        padding: 3rem 0;
    }
    
    .product-card {
        border: 1px solid #eee;
        border-radius: 5px;
        overflow: hidden;
        transition: transform 0.3s, box-shadow 0.3s;
        margin-bottom: 1.5rem;
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    
    .product-info {
        padding: 1rem;
    }
    
    .category-section, .style-section {
        padding: 3rem 0;
        background-color: #f8f9fa;
    }
    
    .category-card, .style-card {
        position: relative;
        border-radius: 5px;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    
    .category-label {
        position: absolute;
        bottom: 0;
        width: 100%;
        background-color: rgba(255, 255, 255, 0.8);
        padding: 1rem;
        text-align: center;
    }
    
    .placeholder-img {
        width: 100%;
        position: relative;
        overflow: hidden;
    }
    
    .placeholder-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .style-info {
        padding: 1rem;
        text-align: center;
        background-color: #fff;
    }
    
    /* User menu and cart preview styles */
    .user-container, .cart-container {
        position: relative;
    }
    
    .user-menu, .cart-preview {
        position: absolute;
        right: 0;
        top: 100%;
        width: 250px;
        background: white;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        display: none;
        z-index: 1000;
    }
    
    .user-menu-item {
        padding: 10px 15px;
        border-bottom: 1px solid #eee;
    }
    
    .user-menu-item:last-child {
        border-bottom: none;
    }
    
    .cart-icon {
        position: relative;
    }
    
    .cart-count {
        position: absolute;
        top: -8px;
        right: -8px;
        background-color: #dc3545;
        color: white;
        border-radius: 50%;
        width: 18px;
        height: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
    }
    
    .cart-preview-item {
        padding: 10px 15px;
        border-bottom: 1px solid #eee;
    }
    
    .item-image {
        width: 50px;
        height: 50px;
        margin-right: 10px;
        overflow: hidden;
    }
    
    .item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .item-remove {
        margin-left: auto;
        cursor: pointer;
        font-weight: bold;
        color: #999;
        padding: 0 5px;
    }
    
    .quantity-control {
        display: flex;
        align-items: center;
    }
    
    .quantity-btn {
        border: 1px solid #ddd;
        background: #f8f8f8;
        width: 25px;
        height: 25px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-weight: bold;
        user-select: none;
    }
    
    .quantity-input {
        width: 30px;
        text-align: center;
        border: 1px solid #ddd;
        height: 25px;
    }
    
    .cart-total {
        display: flex;
        justify-content: space-between;
        padding: 10px 15px;
        font-weight: bold;
    }
    
    .cart-checkout {
        display: block;
        text-align: center;
        padding: 12px;
        background-color: #212529;
        color: white;
        text-decoration: none;
    }
    
    .cart-checkout:hover {
        background-color: #343a40;
        color: white;
    }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // User dropdown toggle
    const userContainer = document.getElementById('userContainer');
    const userMenu = document.getElementById('userMenu');
    
    if (userContainer && userMenu) {
        userContainer.addEventListener('click', function(e) {
            e.stopPropagation();
            userMenu.style.display = userMenu.style.display === 'block' ? 'none' : 'block';
        });
    }
    
    // Cart preview toggle
    const cartContainer = document.getElementById('cartContainer');
    const cartPreview = document.getElementById('cartPreview');
    
    if (cartContainer && cartPreview) {
        cartContainer.addEventListener('mouseover', function() {
            cartPreview.style.display = 'block';
        });
        
        cartContainer.addEventListener('mouseleave', function() {
            cartPreview.style.display = 'none';
        });
    }
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (userMenu) userMenu.style.display = 'none';
    });
});
</script>
@endsection
