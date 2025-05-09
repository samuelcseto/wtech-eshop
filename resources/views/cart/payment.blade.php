@extends('layouts.app')

@section('title', 'Košík - Platba - Domáce Dekorácie')

@section('content')
<!-- Payment Section -->
<section class="payment-view-container">
    <div class="container">
        <h1 class="payment-title">Platba</h1>

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
            <!-- Left Column - Payment Form -->
            <div class="col-lg-8">
                <!-- Back to Contact & Shipping -->
                <a href="{{ route('cart.checkout') }}" class="back-link">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left me-1" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z" />
                    </svg>
                    <span>Kontakt a doprava</span>
                </a>

                <form id="paymentForm" method="POST" action="{{ route('cart.processPayment') }}">
                    @csrf
                    <!-- Billing Address Section -->
                    <div class="form-section">
                        <h3 class="section-title">Fakturačná adresa</h3>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="sameAddress" name="same_address" checked />
                            <label class="form-check-label" for="sameAddress"> Rovnaká ako dodacia adresa </label>
                        </div>

                        <div class="billing-form" id="billingAddressForm" style="display: none">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="billingFirstName" name="billing_first_name" placeholder="Meno" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="billingLastName" name="billing_last_name" placeholder="Priezvisko" />
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <input type="text" class="form-control" id="billingAddress" name="billing_address" placeholder="Adresa" />
                            </div>

                            <div class="form-group">
                                <input type="text" class="form-control" id="billingApartment" name="billing_apartment" placeholder="Byt (voliteľné)" />
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="billingCity" name="billing_city" placeholder="Mesto" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="billingPostalCode" name="billing_postal_code" placeholder="PSČ" />
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <select class="form-control" id="billingCountry" name="billing_country">
                                    <option value="" disabled>Vyberte krajinu</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->code }}">{{ $country->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method Section -->
                    <div class="form-section">
                        <h3 class="section-title">Platba</h3>

                        <div class="form-group mb-4">
                            <label for="paymentMethod" class="form-label">Spôsob platby</label>
                            <select class="form-control" id="paymentMethod" name="payment_method" required>
                                <option value="CARD" selected>Platba kartou</option>
                                <option value="COD">Dobierka</option>
                                <option value="WIRE">Bankový prevod</option>
                            </select>
                        </div>

                        <div id="cardPaymentForm">
                            <div class="credit-card-logos">
                                <img src="https://1000logos.net/wp-content/uploads/2021/11/VISA-logo.png" alt="Visa" class="credit-card-logo" />
                                <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" alt="Mastercard" class="credit-card-logo" />
                            </div>

                            <div class="credit-card-form">
                                <div class="form-group">
                                    <input type="text" class="form-control" id="cardNumber" name="card_number" placeholder="Číslo karty" />
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control card-expiry" id="expiryMonth" name="expiry_month" placeholder="Mesiac" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control card-expiry" id="expiryYear" name="expiry_year" placeholder="Rok" />
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group card-security-code">
                                    <input type="password" class="form-control" id="securityCode" name="security_code" placeholder="CVC Kód" />
                                </div>
                            </div>
                        </div>

                        <div id="codPaymentInfo" style="display: none">
                            <div class="alert alert-info">
                                <p>Pri zvolení dobierky zaplatíte za tovar pri jeho prevzatí. K cene objednávky bude pripočítaný poplatok za dobierku vo výške 1,50 €.</p>
                            </div>
                        </div>

                        <div id="transferPaymentInfo" style="display: none">
                            <div class="alert alert-info">
                                <p>Pri bankovom prevode prosím použite nasledujúce údaje:</p>
                                <p><strong>IBAN:</strong> SK12 3456 7890 1234 5678 9012</p>
                                <p><strong>Variabilný symbol:</strong> 12345678</p>
                                <p><strong>Poznámka pre príjemcu:</strong> Vaše meno a priezvisko</p>
                            </div>
                        </div>

                        <button type="submit" class="payment-btn">Dokončiť objednávku</button>
                    </div>
                </form>
            </div>

            <!-- Right Column - Order Summary -->
            <div class="col-lg-4">
                <div class="cart-summary">
                    <h3 class="section-title">Súhrn objednávky</h3>

                    <!-- Cart items summary -->
                    @if($cart->items->count() > 0)
                        <div class="checkout-cart-items-summary mb-4">
                            @foreach($cart->items as $item)
                                <div class="d-flex justify-content-between mb-2 small">
                                    <div>
                                        <strong>{{ $item->product->name }}</strong> × {{ $item->quantity }}
                                    </div>
                                    <div>{{ number_format($item->price * $item->quantity, 2, ',', ' ') }} €</div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="cart-summary-row">
                        <span>Medzisúčet:</span>
                        <span>{{ number_format($subtotal, 2, ',', ' ') }} €</span>
                    </div>

                    <div class="cart-summary-row">
                        <span>Doprava:</span>
                        <span>{{ number_format($shipping, 2, ',', ' ') }} €</span>
                    </div>
                    
                    @if($cart->shippingProvider)
                    <div class="cart-summary-row">
                        <span>Spôsob dopravy:</span>
                        <span>{{ $cart->shippingProvider->name }}</span>
                    </div>
                    @endif

                    <div class="cart-summary-row total">
                        <span>Celkom:</span>
                        <span>{{ number_format($total, 2, ',', ' ') }} €</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
    .payment-view-container .payment-container {
        padding: 30px 0;
    }
    .payment-view-container .payment-title {
        font-size: 28px;
        font-weight: 500;
        margin-bottom: 20px;
    }
    .payment-view-container .form-control {
        padding: 10px 12px;
        border-radius: 0;
        border: 1px solid #ccc;
        margin-bottom: 15px;
    }
    .payment-view-container .cart-summary {
        background-color: #f9f9f9;
        padding: 20px;
        height: 100%;
    }
    .payment-view-container .cart-summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
    }
    .payment-view-container .cart-summary-row.total {
        font-size: 18px;
        font-weight: 600;
        margin-top: 15px;
        border-top: 1px solid #ddd;
        padding-top: 15px;
    }
    .payment-view-container .payment-btn {
        display: block;
        width: 100%;
        padding: 12px;
        background-color: #4CAF50;
        color: white;
        text-align: center;
        text-decoration: none;
        font-size: 16px;
        margin-top: 20px;
        border: none;
        cursor: pointer;
        transition: background-color 0.3s;
    }
    .payment-view-container .payment-btn:hover {
        background-color: #45a049;
        color: white;
    }
    .payment-view-container .section-title {
        font-size: 18px;
        font-weight: 500;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #ddd;
    }
    .payment-view-container .back-link {
        display: inline-block;
        margin-bottom: 15px;
        color: #555;
        text-decoration: none;
    }
    .payment-view-container .back-link:hover {
        text-decoration: underline;
    }
    .payment-view-container .form-section {
        margin-bottom: 30px;
    }
    .payment-view-container .credit-card-logos {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
    }
    .payment-view-container .credit-card-logo {
        height: 30px;
    }
    .payment-view-container .card-security-code {
        max-width: 120px;
    }
    .payment-view-container .card-expiry {
        max-width: 100%;
    }
    .payment-view-container .back-link svg {
        vertical-align: -2px;
    }
    .payment-view-container .optional-label {
        color: #6c757d;
        font-size: 0.85em;
        margin-left: 5px;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Billing address toggle
        const sameAddressCheckbox = document.getElementById('sameAddress');
        const billingAddressForm = document.getElementById('billingAddressForm');

        sameAddressCheckbox.addEventListener('change', function () {
            billingAddressForm.style.display = this.checked ? 'none' : 'block';
        });

        // Payment method toggle
        const paymentMethodSelect = document.getElementById('paymentMethod');
        const cardPaymentForm = document.getElementById('cardPaymentForm');
        const codPaymentInfo = document.getElementById('codPaymentInfo');
        const transferPaymentInfo = document.getElementById('transferPaymentInfo');
        
        // For COD fee calculation
        const codFee = 1.50;
        let originalTotal = parseFloat('{{ $total }}'.replace(',', '.'));
        const formattedOriginalTotal = originalTotal.toFixed(2).replace('.', ',');
        const totalElement = document.querySelector('.cart-summary-row.total span:last-child');

        // Add COD fee row element to the summary
        const summaryDiv = document.querySelector('.cart-summary');
        const totalRow = document.querySelector('.cart-summary-row.total');
        const codFeeRow = document.createElement('div');
        codFeeRow.className = 'cart-summary-row cod-fee';
        codFeeRow.innerHTML = '<span>Poplatok za dobierku:</span><span>0,00 €</span>';
        codFeeRow.style.display = 'none';
        summaryDiv.insertBefore(codFeeRow, totalRow);

        paymentMethodSelect.addEventListener('change', function () {
            // Hide all payment forms first
            cardPaymentForm.style.display = 'none';
            codPaymentInfo.style.display = 'none';
            transferPaymentInfo.style.display = 'none';

            // Show the selected payment form
            if (this.value === 'CARD') {
                cardPaymentForm.style.display = 'block';
                
                // Mark card fields as required
                document.getElementById('cardNumber').setAttribute('required', '');
                document.getElementById('expiryMonth').setAttribute('required', '');
                document.getElementById('expiryYear').setAttribute('required', '');
                document.getElementById('securityCode').setAttribute('required', '');
                
                // Reset total to original value
                totalElement.textContent = formattedOriginalTotal + ' €';
                codFeeRow.style.display = 'none';
            } else {
                // Remove required attributes when not using card payment
                document.getElementById('cardNumber').removeAttribute('required');
                document.getElementById('expiryMonth').removeAttribute('required');
                document.getElementById('expiryYear').removeAttribute('required');
                document.getElementById('securityCode').removeAttribute('required');
                
                if (this.value === 'COD') {
                    codPaymentInfo.style.display = 'block';
                    
                    // Add COD fee to total
                    const newTotal = (originalTotal + codFee).toFixed(2).replace('.', ',');
                    totalElement.textContent = newTotal + ' €';
                    
                    // Show COD fee row
                    codFeeRow.style.display = 'flex';
                    codFeeRow.querySelector('span:last-child').textContent = codFee.toFixed(2).replace('.', ',') + ' €';
                } else if (this.value === 'WIRE') {
                    transferPaymentInfo.style.display = 'block';
                    
                    // Reset total to original value
                    totalElement.textContent = formattedOriginalTotal + ' €';
                    codFeeRow.style.display = 'none';
                }
            }
        });

        // Form validation for credit card fields
        document.getElementById('cardNumber').addEventListener('input', function() {
            this.value = this.value.replace(/[^\d]/g, '').substring(0, 16);
        });

        document.getElementById('expiryMonth').addEventListener('input', function() {
            this.value = this.value.replace(/[^\d]/g, '').substring(0, 2);
            if (this.value > 12) this.value = '12';
        });

        document.getElementById('expiryYear').addEventListener('input', function() {
            this.value = this.value.replace(/[^\d]/g, '').substring(0, 2);
        });

        document.getElementById('securityCode').addEventListener('input', function() {
            this.value = this.value.replace(/[^\d]/g, '').substring(0, 3);
        });
    });
</script>
@endsection