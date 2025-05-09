<div class="orders-page-detail-section">
    <h2 class="orders-page-detail-title">Objednávka č. {{ $order->order_id }}</h2>

    <!-- Order items -->
    <div class="orders-page-order-items">
        @foreach($order->items as $item)
            <div class="orders-page-product-item">
                <div class="orders-page-product-image">
                    @if($item->product->images->isNotEmpty())
                        <img src="{{ asset($item->product->images->first()->image_path) }}" alt="{{ $item->product->name }}">
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
            <div>{{ number_format($order->total_amount - $order->shipping_cost - ($order->payment_method === 'COD' ? 1.50 : 0), 2, ',', ' ') }} €</div>
        </div>
        <div class="orders-page-summary-row">
            <div>Doprava:</div>
            <div>{{ number_format($order->shipping_cost, 2, ',', ' ') }} €</div>
        </div>
        @if($order->payment_method === 'COD')
        <div class="orders-page-summary-row">
            <div>Poplatok za dobierku:</div>
            <div>1,50 €</div>
        </div>
        @endif
        <div class="orders-page-summary-row total">
            <div>Celkom:</div>
            <div>{{ number_format($order->total_amount, 2, ',', ' ') }} €</div>
        </div>
    </div>
    
    <!-- Shipping and payment information -->
    <div class="orders-page-detail-section">
        <h3>Informácie o doručení a platbe</h3>
        <div>
            <p><strong>Spôsob dopravy:</strong> 
                @if($order->shippingProvider)
                    {{ $order->shippingProvider->name }}
                @else
                    Neuvedené
                @endif
            </p>
            <p><strong>Spôsob platby:</strong> 
                @switch($order->payment_method)
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
                        {{ $order->payment_method }}
                @endswitch
            </p>
            <p><strong>Adresa doručenia:</strong> {{ $order->address_line1 }}{{ $order->address_line2 ? ', '.$order->address_line2 : '' }}, {{ $order->postal_code }} {{ $order->city }}, {{ $order->country }}</p>
            <p><strong>Kontakt:</strong> {{ $order->first_name }} {{ $order->last_name }}, {{ $order->phone }}, {{ $order->email }}</p>
        </div>
    </div>
</div>