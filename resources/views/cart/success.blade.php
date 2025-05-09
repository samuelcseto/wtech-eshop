@extends('layouts.app')

@section('title', 'Úspešná Platba - Domáce Dekorácie')

@section('content')
<!-- Success Section -->
<section class="success-container">
    <div class="container">
        <div class="success-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
                <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z" />
            </svg>
        </div>

        <h1 class="success-title">Úspešná Platba</h1>

        <p class="success-message">Ďakujeme, že ste si vybrali náš obchod, svoju objednávku môžete vyhľadať v sekcii objednávok.</p>

        @if(isset($order))
        <div class="success-order-details">
            <h3>Detaily objednávky</h3>
            
            <div class="success-order-info">
                <div class="success-order-row">
                    <span>Číslo objednávky:</span>
                    <span>#{{ $order->order_id }}</span>
                </div>
                
                @if($order->shippingProvider)
                <div class="success-order-row">
                    <span>Spôsob dopravy:</span>
                    <span>{{ $order->shippingProvider->name }}</span>
                </div>
                @endif
                
                <div class="success-order-row">
                    <span>Spôsob platby:</span>
                    <span>
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
                    </span>
                </div>
                
                @if($order->status)
                <div class="success-order-row">
                    <span>Stav objednávky:</span>
                    <span>
                        @switch($order->status)
                            @case('new')
                                Nová
                                @break
                            @case('processing')
                                Spracováva sa
                                @break
                            @case('shipped')
                                Odoslaná
                                @break
                            @case('delivered')
                                Doručená
                                @break
                            @default
                                {{ $order->status }}
                        @endswitch
                    </span>
                </div>
                @endif
                
                <div class="success-order-row">
                    <span>Celková suma:</span>
                    <span>{{ number_format($order->total_amount, 2, ',', ' ') }} €</span>
                </div>
            </div>
        </div>
        @endif

        <p class="success-submessage">Potvrdenie o prijatí bolo odoslané na váš e-mail.</p>

        <p class="success-contact">V prípade akýchkoľvek otázok nás prosím kontaktujte.</p>

        <div class="success-buttons">
            <a href="{{ route('home') }}" class="success-button">Späť na domovskú stránku</a>
            @if(Auth::check())
                <a href="{{ route('orders.index') }}" class="success-button primary">Moje objednávky</a>
            @endif
        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
    .success-container {
        padding: 50px 0;
        text-align: center;
    }
    .success-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 30px;
        border-radius: 50%;
        background-color: #4caf50;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .success-icon svg {
        width: 40px;
        height: 40px;
        color: white;
    }
    .success-title {
        font-size: 36px;
        color: #4caf50;
        margin-bottom: 30px;
        font-weight: 500;
    }
    .success-message {
        font-size: 18px;
        color: #333;
        margin-bottom: 15px;
        max-width: 700px;
        margin-left: auto;
        margin-right: auto;
    }
    .success-submessage {
        font-size: 16px;
        color: #555;
        margin-bottom: 40px;
        max-width: 700px;
        margin-left: auto;
        margin-right: auto;
    }
    .success-contact {
        font-size: 16px;
        color: #666;
        margin-bottom: 40px;
    }
    .success-buttons {
        display: flex;
        gap: 20px;
        justify-content: center;
        margin-top: 40px;
    }
    .success-button {
        display: inline-block;
        padding: 12px 24px;
        background-color: #aaa;
        color: white;
        text-decoration: none;
        font-size: 16px;
        border-radius: 4px;
        transition: background-color 0.3s;
    }
    .success-button:hover {
        background-color: #888;
        color: white;
    }
    .success-button.primary {
        background-color: #4caf50;
    }
    .success-button.primary:hover {
        background-color: #3d8b40;
    }
    .success-order-details {
        margin: 30px auto;
        max-width: 500px;
        background-color: #f9f9f9;
        padding: 20px;
        border-radius: 8px;
        text-align: left;
    }
    .success-order-details h3 {
        font-size: 20px;
        color: #333;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
        text-align: center;
    }
    .success-order-info {
        padding: 10px 0;
    }
    .success-order-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #eee;
    }
    .success-order-row:last-child {
        border-bottom: none;
        font-weight: bold;
    }
</style>
@endsection