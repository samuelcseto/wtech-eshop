@extends('layouts.app')

@section('title', 'Nákupný košík - Domáce Dekorácie')

@section('content')
<!-- Cart Section -->
<section class="cart-page-container">
    <div class="container">
        <h1 class="cart-page-title">Nákupný košík</h1>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="row">
            <!-- Cart Items - Left Column -->
            <div class="col-lg-8">
                <h2 class="cart-page-section-title">Zoznam</h2>

                @if($cart->items->count() > 0)
                    @foreach($cart->items as $item)
                        <div class="cart-page-item">
                            <div class="cart-page-item-image">
                                @if($item->product->primaryImage)
                                    <img src="{{ asset($item->product->primaryImage->image_url) }}" alt="{{ $item->product->name }}" style="width: 100%; height: 100%; object-fit: cover;" />
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="white" viewBox="0 0 16 16">
                                        <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z" />
                                        <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-12zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1h12z" />
                                    </svg>
                                @endif
                            </div>
                            <div class="cart-page-item-details">
                                <h3 class="cart-page-item-title">{{ $item->product->name }}</h3>
                                
                                <div class="cart-page-item-description">
                                    {{ \Illuminate\Support\Str::limit($item->product->description, 150) }}
                                </div>
                                
                                <div class="cart-page-item-attributes">
                                    @if($item->attributes)
                                        @foreach($item->attributes as $key => $value)
                                            <div class="cart-page-item-attribute">{{ $key }}: {{ $value }}</div>
                                        @endforeach
                                    @endif
                                </div>
                                
                                <div class="cart-page-item-actions">
                                    <form action="{{ route('cart.update', $item->cart_item_id) }}" method="POST" class="cart-quantity-form">
                                        @csrf
                                        <div class="cart-page-quantity-control">
                                            <button type="button" class="cart-page-quantity-btn decrease-btn">−</button>
                                            <input type="text" name="quantity" class="cart-page-quantity-input" value="{{ $item->quantity }}" readonly />
                                            <button type="button" class="cart-page-quantity-btn increase-btn">+</button>
                                        </div>
                                    </form>
                                    <div class="cart-page-item-price-container">
                                        <div class="cart-page-item-unit-price">
                                            <span class="price-label">Jednotková cena:</span>
                                            <span>{{ number_format($item->price, 2, ',', ' ') }} €</span>
                                        </div>
                                        <div class="cart-page-item-total-price">
                                            <span class="price-label">Celkom:</span>
                                            <span>{{ number_format($item->price * $item->quantity, 2, ',', ' ') }} €</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <form action="{{ route('cart.remove', $item->cart_item_id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="cart-page-item-remove" onclick="return confirm('Naozaj chcete odstrániť túto položku z košíka?')">×</button>
                            </form>
                        </div>
                    @endforeach
                @else
                    <div class="alert alert-info">
                        Váš košík je prázdny. <a href="{{ route('products.index') }}">Prejsť do katalógu</a>
                    </div>
                @endif
            </div>

            <!-- Cart Summary - Right Column -->
            <div class="col-lg-4">
                <div class="cart-page-summary">
                    <div class="cart-page-summary-row">
                        <div>Medzisúčet:</div>
                        <div>{{ number_format($subtotal, 2, ',', ' ') }} €</div>
                    </div>
                    <div class="cart-page-summary-row">
                        <div>Doprava:</div>
                        <div>{{ number_format($shipping, 2, ',', ' ') }} €</div>
                    </div>
                    <div class="cart-page-summary-row total">
                        <div>Celkom:</div>
                        <div>{{ number_format($total, 2, ',', ' ') }} €</div>
                    </div>

                    @if($cart->items->count() > 0)
                        <a href="{{ route('cart.checkout') }}" class="cart-page-continue-btn">Pokračovať</a>
                        
                        <form action="{{ route('cart.clear') }}" method="POST" class="mt-3">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger w-100" onclick="return confirm('Naozaj chcete vyprázdniť celý košík?')">
                                Vyprázdniť košík
                            </button>
                        </form>
                    @else
                        <a href="{{ route('products.index') }}" class="cart-page-continue-btn">Prejsť do katalógu</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
    .cart-page-container {
        padding: 30px 0 60px;
    }
    .cart-page-title {
        font-size: 32px;
        margin-bottom: 15px;
    }
    .cart-page-section-title {
        font-size: 20px;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid #ddd;
    }
    .cart-page-item {
        display: flex;
        padding: 20px 0;
        border-bottom: 1px solid #eee;
        position: relative;
    }
    .cart-page-item-image {
        width: 180px;
        height: 180px;
        background-color: #e6e6e6;
        margin-right: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .cart-page-item-details {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .cart-page-item-title {
        font-size: 18px;
        font-weight: 500;
        margin-bottom: 10px;
    }
    .cart-page-item-description {
        color: #666;
        margin-bottom: 15px;
        line-height: 1.4;
        font-size: 0.9rem;
    }
    .cart-page-item-attributes {
        margin-bottom: 15px;
        color: #555;
    }
    .cart-page-item-attribute {
        margin-bottom: 5px;
    }
    .cart-page-item-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .cart-page-quantity-control {
        display: flex;
        align-items: center;
        border: 1px solid #ddd;
        width: fit-content;
    }
    .cart-page-quantity-btn {
        width: 32px;
        height: 32px;
        background-color: #f2f2f2;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }
    .cart-page-quantity-input {
        width: 50px;
        height: 32px;
        text-align: center;
        border: none;
        border-left: 1px solid #ddd;
        border-right: 1px solid #ddd;
    }
    .cart-page-quantity-input:focus {
        outline: none;
    }
    .cart-page-item-price-container {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
    }
    .cart-page-item-unit-price {
        font-size: 14px;
        color: #666;
        margin-bottom: 5px;
    }
    .cart-page-item-total-price {
        font-size: 18px;
        font-weight: 500;
    }
    .price-label {
        margin-right: 5px;
        font-weight: normal;
    }
    .cart-page-item-remove {
        position: absolute;
        top: 20px;
        right: 0;
        cursor: pointer;
        font-size: 18px;
        color: #777;
        background: none;
        border: none;
        padding: 0;
    }
    .cart-page-summary {
        background-color: #f9f9f9;
        padding: 20px;
        margin-top: 20px;
    }
    .cart-page-summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
        font-size: 18px;
    }
    .cart-page-summary-row.total {
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #ddd;
        font-weight: 700;
        font-size: 20px;
    }
    .cart-page-continue-btn {
        display: block;
        background-color: #4CAF50;
        color: white;
        text-align: center;
        padding: 12px;
        margin-top: 20px;
        text-decoration: none;
        font-weight: 500;
        transition: background-color 0.3s;
        border-radius: 4px;
    }
    .cart-page-continue-btn:hover {
        background-color: #45a049;
        color: white;
    }

    @media (max-width: 767px) {
        .cart-page-container {
            padding: 15px 0 40px;
        }
        .cart-page-title {
            font-size: 28px;
        }
        .cart-page-item {
            flex-direction: column;
            padding-bottom: 30px;
        }
        .cart-page-item-image {
            width: 100%;
            height: 200px;
            margin-right: 0;
            margin-bottom: 15px;
        }
        .cart-page-item-actions {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
        .cart-page-item-price-container {
            align-items: flex-start;
            margin-top: 10px;
        }
        .cart-page-item-remove {
            top: 10px;
            right: 10px;
        }
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get all quantity controls
        const decreaseBtns = document.querySelectorAll('.decrease-btn');
        const increaseBtns = document.querySelectorAll('.increase-btn');
        
        // Add event listeners to decrease buttons
        decreaseBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                const form = this.closest('.cart-quantity-form');
                const input = form.querySelector('.cart-page-quantity-input');
                let value = parseInt(input.value);
                
                if (value > 1) {
                    value--;
                    input.value = value;
                    form.submit();
                }
            });
        });
        
        // Add event listeners to increase buttons
        increaseBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                const form = this.closest('.cart-quantity-form');
                const input = form.querySelector('.cart-page-quantity-input');
                let value = parseInt(input.value);
                
                value++;
                input.value = value;
                form.submit();
            });
        });
    });
</script>
@endsection