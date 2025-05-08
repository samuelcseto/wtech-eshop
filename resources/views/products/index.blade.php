@extends('layouts.app')

@section('title', isset($categoryName) ? $categoryName . ' - Domáce Dekorácie' : 'Katalóg produktov - Domáce Dekorácie')

@section('content')
<!-- Products Section -->
<section class="products-page-container">
    <div class="container">
        <h1 class="products-page-title">{{ isset($categoryName) ? $categoryName : 'Katalóg produktov' }}</h1>

        <!-- Mobile search - visible only on mobile -->
        <div class="products-page-mobile-search">
            <form class="d-flex" method="GET" action="{{ route('products.index') }}">
                <!-- Preserve existing filters -->
                @if(request('category'))
                    @if(is_array(request('category')))
                        @foreach(request('category') as $cat)
                            <input type="hidden" name="category[]" value="{{ $cat }}">
                        @endforeach
                    @else
                        <input type="hidden" name="category[]" value="{{ request('category') }}">
                    @endif
                @endif
                @if(request('in_stock'))
                    <input type="hidden" name="in_stock" value="{{ request('in_stock') }}">
                @endif
                @if(request('sort'))
                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                @endif
                <div class="input-group">
                    <input class="form-control" type="search" name="search" placeholder="Hľadať" aria-label="Hľadať" value="{{ request('search') }}" />
                    <button class="btn btn-outline-secondary" type="submit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>

        <!-- Mobile filters toggle button - visible only on mobile -->
        <button class="products-page-mobile-filters-btn" type="button" data-bs-toggle="collapse" data-bs-target="#filtersCollapse" aria-expanded="false" aria-controls="filtersCollapse">
            Filtre
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-funnel ms-2" viewBox="0 0 16 16">
                <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2zm1 .5v1.308l4.372 4.858A.5.5 0 0 1 7 8.5v5.306l2-.666V8.5a.5.5 0 0 1 .128-.334L13.5 3.308V2h-11z" />
            </svg>
        </button>

        <div class="row">
            <!-- Filters - Left Column -->
            <div class="col-lg-3">
                <div class="products-page-filter-section collapse d-lg-block" id="filtersCollapse">
                    <!-- Main filter form -->
                    <form id="filterForm" method="GET" action="{{ route('products.index') }}">
                        <!-- Keep search parameter if present -->
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif

                        <!-- Sort Dropdown -->
                        <div class="products-page-filter-card">
                            <div class="products-page-filter-header">
                                <span>Zoradiť podľa</span>
                            </div>
                            <div class="products-page-filter-body">
                                <select class="form-select products-page-sort-select" name="sort" aria-label="Zoradiť podľa" onchange="document.getElementById('filterForm').submit()">
                                    <option value="newest" {{ request('sort') == 'newest' || !request('sort') ? 'selected' : '' }}>Najnovšie</option>
                                    <option value="bestselling" {{ request('sort') == 'bestselling' ? 'selected' : '' }}>Najpredávanejší</option>
                                    <option value="price-asc" {{ request('sort') == 'price-asc' ? 'selected' : '' }}>Od najlacnejších</option>
                                    <option value="price-desc" {{ request('sort') == 'price-desc' ? 'selected' : '' }}>Od najdrahších</option>
                                </select>
                            </div>
                        </div>

                        <!-- Categories Filter -->
                        <div class="products-page-filter-card">
                            <div class="products-page-filter-header" data-bs-toggle="collapse" data-bs-target="#categoriesCollapse" aria-expanded="true">
                                <span>Kategórie</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-up" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M7.646 4.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1-.708.708L8 5.707l-5.646 5.647a.5.5 0 0 1-.708-.708l6-6z" />
                                </svg>
                            </div>
                            <div class="products-page-filter-body collapse show" id="categoriesCollapse">
                                @foreach($categories as $category)
                                    <div class="products-page-filter-item">
                                        <label class="{{ (is_array(request('category')) && in_array($category->category_id, request('category'))) || request('category') == $category->category_id ? 'selected-category' : '' }}">
                                            <input type="checkbox" 
                                                   name="category[]" 
                                                   value="{{ $category->category_id }}" 
                                                   class="category-checkbox"
                                                   {{ (is_array(request('category')) && in_array($category->category_id, request('category'))) || request('category') == $category->category_id ? 'checked' : '' }}>
                                            {{ $category->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Price Range Filter -->
                        <div class="products-page-filter-card">
                            <div class="products-page-filter-header" data-bs-toggle="collapse" data-bs-target="#priceCollapse" aria-expanded="true">
                                <span>Cenové rozpätie</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-up" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M7.646 4.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1-.708.708L8 5.707l-5.646 5.647a.5.5 0 0 1-.708-.708l6-6z" />
                                </svg>
                            </div>
                            <div class="products-page-filter-body collapse show" id="priceCollapse">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="price_from" class="form-label">Od:</label>
                                            <div class="input-group">
                                                <input type="number" 
                                                       id="price_from"
                                                       name="price_from" 
                                                       class="form-control" 
                                                       min="0" 
                                                       step="0.01"
                                                       value="{{ request('price_from') }}"
                                                       placeholder="0">
                                                <span class="input-group-text">€</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="price_to" class="form-label">Do:</label>
                                            <div class="input-group">
                                                <input type="number" 
                                                       id="price_to"
                                                       name="price_to" 
                                                       class="form-control" 
                                                       min="0" 
                                                       step="0.01"
                                                       value="{{ request('price_to') }}"
                                                       placeholder="Max">
                                                <span class="input-group-text">€</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Availability Filter -->
                        <div class="products-page-filter-card">
                            <div class="products-page-filter-header" data-bs-toggle="collapse" data-bs-target="#availabilityCollapse" aria-expanded="true">
                                <span>Dostupnosť</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-up" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M7.646 4.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1-.708.708L8 5.707l-5.646 5.647a.5.5 0 0 1-.708-.708l6-6z" />
                                </svg>
                            </div>
                            <div class="products-page-filter-body collapse show" id="availabilityCollapse">
                                <div class="products-page-filter-item">
                                    <label>
                                        <input type="checkbox" name="in_stock" value="1" 
                                            {{ request('in_stock') ? 'checked' : '' }}>
                                        Skladom
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Apply Filters Button -->
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary w-100">Použiť filtre</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Products Grid - Right Column -->
            <div class="col-lg-9">
                <div class="row">
                    @forelse($products as $product)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <a href="{{ route('products.show', $product->product_id) }}" class="text-decoration-none">
                                <div class="products-page-product-card">
                                    <div class="products-page-product-img">
                                        @if($product->primaryImage)
                                            <img src="{{ asset($product->primaryImage->image_url) }}" alt="{{ $product->name }}" />
                                        @else
                                            <img src="https://via.placeholder.com/300x200" alt="{{ $product->name }}" />
                                        @endif
                                    </div>
                                    <div class="products-page-product-info">
                                        <h5 class="products-page-product-title">{{ $product->name }}</h5>
                                        <p class="products-page-product-subtitle">
                                            @if($product->categories->isNotEmpty())
                                                {{ $product->categories->first()->name }}
                                            @else
                                                Dekorácia
                                            @endif
                                        </p>
                                        <p class="products-page-product-price">{{ number_format($product->price, 2, ',', ' ') }} €</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <h3>Žiadne produkty sa nenašli</h3>
                            <p>Skúste zmeniť filtre alebo vyhľadávacie kritériá</p>
                        </div>
                    @endforelse
                </div>
                
                <!-- Pagination with preserved query parameters -->
                @if($products->isNotEmpty())
                    <div class="row mt-4">
                        <div class="col-12">
                            {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
    /* Products page specific styles */
    .products-page-container {
        padding: 20px 0 40px;
    }
    .products-page-title {
        font-size: 28px;
        margin-bottom: 25px;
        text-align: center;
    }
    .products-page-filter-section {
        margin-bottom: 30px;
    }
    .products-page-filter-card {
        border: 1px solid #e0e0e0;
        border-radius: 0;
        margin-bottom: 10px;
    }
    .products-page-filter-header {
        background-color: #aaa;
        color: white;
        padding: 10px 15px;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .products-page-filter-header svg {
        transition: transform 0.3s ease;
    }
    .products-page-filter-header.collapsed svg {
        transform: rotate(180deg);
    }
    .color-swatch {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        display: inline-block;
    }
    .products-page-sort-select {
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 8px 10px;
        background-color: #fff;
        width: 100%;
        cursor: pointer;
        font-size: 14px;
        color: #333;
    }
    .products-page-filter-body {
        padding: 15px;
        background-color: #fff;
    }
    .products-page-filter-item {
        margin-bottom: 10px;
    }
    .products-page-filter-item:last-child {
        margin-bottom: 0;
    }
    .products-page-filter-item label {
        display: flex;
        align-items: center;
        cursor: pointer;
        font-weight: normal;
    }
    .products-page-filter-item input[type='checkbox'] {
        margin-right: 10px;
    }
    .products-page-product-card {
        margin-bottom: 10px;
        height: 100%;
        transition: transform 0.3s;
        background-color: #fff;
    }
    .products-page-product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    .products-page-product-img {
        width: 100%;
        height: 200px;
        background-color: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 10px;
        overflow: hidden;
    }
    .products-page-product-img img {
        max-width: 100%;
        max-height: 100%;
        object-fit: cover;
    }
    .products-page-product-info {
        padding: 10px;
    }
    .products-page-product-title {
        font-size: 16px;
        font-weight: 500;
        margin-bottom: 5px;
    }
    .products-page-product-subtitle {
        font-size: 12px;
        color: #666;
        margin-bottom: 10px;
    }
    .products-page-product-price {
        font-size: 18px;
        font-weight: bold;
        color: #333;
    }
    .products-page-mobile-filters-btn {
        display: none;
        margin-bottom: 20px;
        width: 100%;
        padding: 10px;
        background-color: #aaa;
        color: white;
        border: none;
    }
    .products-page-mobile-search {
        margin-bottom: 20px;
        display: none;
    }
    .products-page-view-options {
        margin-bottom: 20px;
    }

    /* Responsive styles */
    @media (max-width: 991px) {
        .products-page-filter-section.collapse:not(.show) {
            display: none;
        }
        .products-page-mobile-filters-btn {
            display: block;
        }
        .products-page-mobile-search {
            display: block;
        }
        .products-page-title {
            font-size: 24px;
            margin-bottom: 20px;
        }
    }

    @media (max-width: 767px) {
        .products-page-product-img {
            height: 180px;
        }
    }

    @media (max-width: 575px) {
        .products-page-container {
            padding: 15px 0 30px;
        }
        .products-page-title {
            font-size: 22px;
            margin-bottom: 15px;
        }
        .products-page-product-img {
            height: 150px;
        }
        .products-page-product-title {
            font-size: 14px;
        }
        .products-page-product-price {
            font-size: 16px;
        }
    }

    /* Selected category styling */
    .selected-category {
        background-color: #e0e0e0;
        border-radius: 4px;
        padding: 5px;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Get all filter headers
        const filterHeaders = document.querySelectorAll('.products-page-filter-header[data-bs-toggle="collapse"]');

        // Initialize collapse state
        filterHeaders.forEach(function (header) {
            const target = header.getAttribute('data-bs-target');
            if (target) {
                const collapseElement = document.querySelector(target);
                
                // Set initial state
                if (!collapseElement.classList.contains('show')) {
                    header.classList.add('collapsed');
                }

                // Add event listener
                header.addEventListener('click', function () {
                    // Toggle the collapsed class with a slight delay to match the collapse animation
                    setTimeout(function () {
                        if (collapseElement.classList.contains('show')) {
                            header.classList.remove('collapsed');
                        } else {
                            header.classList.add('collapsed');
                        }
                    }, 10);
                });
            }
        });
        
        // Make sure the "Apply Filters" button is visible and the auto-submit on checkbox is removed
        const categoryCheckboxes = document.querySelectorAll('.category-checkbox');
        categoryCheckboxes.forEach(function(checkbox) {
            // Remove any onclick or onchange events that might be causing automatic submission
            checkbox.removeAttribute('onchange');
            checkbox.removeAttribute('onclick');
        });
    });
</script>
@endsection