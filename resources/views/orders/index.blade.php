@extends('layouts.app')

@section('title', 'Moje objednávky - Domáce Dekorácie')

@section('styles')
<style>
    .orders-page-container {
        padding: 20px 0 60px;
    }
    .orders-page-title {
        font-size: 28px;
        margin-bottom: 25px;
        border-bottom: 1px solid #ddd;
        padding-bottom: 15px;
    }
    .orders-page-order-box {
        background-color: #e6e6e6;
        padding: 20px;
        margin-bottom: 20px;
        cursor: pointer;
        transition: background-color 0.2s;
    }
    .orders-page-order-box:hover {
        background-color: #ddd;
    }
    .orders-page-order-box.active {
        background-color: #d9d9d9;
        border-left: 4px solid #aaa;
    }
    .orders-page-order-number {
        font-size: 18px;
        font-weight: 500;
        margin-bottom: 15px;
    }
    .orders-page-order-details {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .orders-page-order-detail-row {
        display: flex;
        justify-content: space-between;
    }
    .orders-page-order-detail-row > div {
        flex: 1;
    }
    .orders-page-detail-title {
        font-size: 22px;
        font-weight: 500;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #ddd;
    }
    .orders-page-detail-section {
        margin-bottom: 30px;
    }
    .orders-page-product-item {
        display: flex;
        align-items: center;
        padding: 15px 0;
        border-bottom: 1px solid #eee;
    }
    .orders-page-product-image {
        width: 80px;
        height: 80px;
        background-color: #f5f5f5;
        margin-right: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .orders-page-product-image img {
        max-width: 100%;
        max-height: 100%;
    }
    .orders-page-product-details {
        flex: 1;
    }
    .orders-page-product-name {
        font-weight: 500;
        margin-bottom: 5px;
    }
    .orders-page-product-meta {
        color: #666;
        font-size: 14px;
        margin-bottom: 5px;
    }
    .orders-page-product-price {
        text-align: right;
        font-weight: 500;
    }
    .orders-page-summary {
        background-color: #f9f9f9;
        padding: 15px;
        margin-top: 20px;
    }
    .orders-page-summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }
    .orders-page-summary-row.total {
        font-weight: 700;
        font-size: 18px;
        padding-top: 10px;
        border-top: 1px solid #ddd;
    }

    @media (max-width: 991px) {
        .orders-page-container {
            padding: 15px 0 40px;
        }
        .orders-page-title {
            font-size: 24px;
            margin-bottom: 20px;
        }
        .orders-page-order-detail-row {
            flex-direction: column;
            gap: 5px;
        }
        .orders-page-detail-title {
            font-size: 20px;
        }
    }

    @media (max-width: 767px) {
        .orders-page-detail-section {
            margin-top: 30px;
        }
        .orders-page-product-item {
            flex-direction: column;
            align-items: flex-start;
        }
        .orders-page-product-image {
            margin-bottom: 10px;
        }
        .orders-page-product-price {
            text-align: left;
            margin-top: 10px;
        }
    }
</style>
@endsection

@section('content')
<section class="orders-page-container">
    <div class="container">
        <h1 class="orders-page-title">Moje objednávky</h1>

        @if($orders->isEmpty())
            <div class="alert alert-info">
                Zatiaľ nemáte žiadne objednávky.
            </div>
        @else
            <div class="row">
                <!-- Orders List - Left Column -->
                <div class="col-lg-5">
                    @foreach($orders as $key => $order)
                        <div class="orders-page-order-box {{ $key === 0 ? 'active' : '' }}" data-order="{{ $order->order_id }}">
                            <div class="orders-page-order-number">Objednávka č. {{ $order->order_id }}</div>
                            <div class="orders-page-order-details">
                                <div class="orders-page-order-detail-row">
                                    <div>Dátum: {{ $order->created_at->format('d. F Y') }}</div>
                                    <div>Položiek: {{ $order->items->count() }}</div>
                                </div>
                                <div class="orders-page-order-detail-row">
                                    <div>Status: {{ $order->status }}</div>
                                    <div>Celkom: {{ number_format($order->total_amount, 2, ',', ' ') }} €</div>
                                </div>
                                <div>
                                    Doručenie: 
                                    @if($order->shippingProvider)
                                        {{ $order->shippingProvider->name }}
                                        @if($order->status === 'potvrdené' || $order->status === 'odoslané')
                                            (očakávané: {{ $order->created_at->addDays(5)->format('d.m.Y') }})
                                        @endif
                                    @else
                                        Neuvedené
                                    @endif
                                </div>
                                <div>
                                    Platba: 
                                    @switch($order->payment_method)
                                        @case('CARD')
                                            Platba kartou
                                            @break
                                        @case('COD')
                                            Dobierka (+1,50 €)
                                            @break
                                        @case('WIRE')
                                            Bankový prevod
                                            @break
                                        @default
                                            {{ $order->payment_method }}
                                    @endswitch
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Order Details - Right Column -->
                <div class="col-lg-7" id="orderDetailsContainer">
                    @if($orders->isNotEmpty())
                        <div class="orders-page-detail-section">
                            <h2 class="orders-page-detail-title">Objednávka č. {{ $orders->first()->order_id }}</h2>

                            <!-- Order items -->
                            <div class="orders-page-order-items">
                                @foreach($orders->first()->items as $item)
                                    <div class="orders-page-product-item">
                                        <div class="orders-page-product-image">
                                            @if($item->product->images->isNotEmpty())
                                                <img src="{{ asset($item->product->images->first()->image_url) }}" alt="{{ $item->product->name }}">
                                            @else
                                                <div class="no-image">Bez obrázku</div>
                                            @endif
                                        </div>
                                        <div class="orders-page-product-details">
                                            <div class="orders-page-product-name">{{ $item->product->name }}</div>
                                            <div class="orders-page-product-meta">
                                                Vlastnosti: {{ $item->product->description }}
                                            </div>
                                            <div class="orders-page-product-meta">Množstvo: {{ $item->quantity }} ks</div>
                                        </div>
                                        <div class="orders-page-product-price">{{ number_format($item->price, 2, ',', ' ') }} €</div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Order summary -->
                            <div class="orders-page-summary">
                                <div class="orders-page-summary-row">
                                    <div>Medzisúčet:</div>
                                    <div>{{ number_format($orders->first()->total_amount - $orders->first()->shipping_cost, 2, ',', ' ') }} €</div>
                                </div>
                                <div class="orders-page-summary-row">
                                    <div>Doprava:</div>
                                    <div>{{ number_format($orders->first()->shipping_cost, 2, ',', ' ') }} €</div>
                                </div>
                                @if($orders->first()->payment_method === 'COD')
                                    <div class="orders-page-summary-row">
                                        <div>Dobierka:</div>
                                        <div>1,50 €</div>
                                    </div>
                                @endif
                                <div class="orders-page-summary-row total">
                                    <div>Celkom:</div>
                                    <div>{{ number_format($orders->first()->total_amount, 2, ',', ' ') }} €</div>
                                </div>
                            </div>
                            
                            <!-- Shipping and payment information -->
                            <div class="orders-page-detail-section">
                                <h3>Informácie o doručení a platbe</h3>
                                <div>
                                    <p><strong>Spôsob dopravy:</strong> 
                                        @if($orders->first()->shippingProvider)
                                            {{ $orders->first()->shippingProvider->name }}
                                        @else
                                            Neuvedené
                                        @endif
                                    </p>
                                    <p><strong>Spôsob platby:</strong> 
                                        @switch($orders->first()->payment_method)
                                            @case('CARD')
                                                Platba kartou
                                                @break
                                            @case('COD')
                                                Dobierka
                                                @break
                                            @case('WIRE')
                                                Bankový prevod
                                                @break
                                            @default
                                                {{ $orders->first()->payment_method }}
                                        @endswitch
                                    </p>
                                    <p><strong>Adresa doručenia:</strong> {{ $orders->first()->address_line1 }}{{ $orders->first()->address_line2 ? ', '.$orders->first()->address_line2 : '' }}, {{ $orders->first()->postal_code }} {{ $orders->first()->city }}, {{ $orders->first()->country }}</p>
                                    <p><strong>Kontakt:</strong> {{ $orders->first()->first_name }} {{ $orders->first()->last_name }}, {{ $orders->first()->phone }}, {{ $orders->first()->email }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</section>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Get all order boxes
        const orderBoxes = document.querySelectorAll('.orders-page-order-box');
        const detailContainer = document.getElementById('orderDetailsContainer');

        // Add click event listener to each order box
        orderBoxes.forEach(function (box) {
            box.addEventListener('click', function () {
                // Remove active class from all boxes
                orderBoxes.forEach(function (b) {
                    b.classList.remove('active');
                });

                // Add active class to clicked box
                box.classList.add('active');

                // Get order ID
                const orderId = box.getAttribute('data-order');
                
                // Fetch order details using AJAX
                fetch(`/orders/${orderId}`)
                    .then(response => response.text())
                    .then(html => {
                        detailContainer.innerHTML = html;
                    })
                    .catch(error => {
                        console.error('Error fetching order details:', error);
                    });
            });
        });
    });
</script>
@endsection