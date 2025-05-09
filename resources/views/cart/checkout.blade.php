@extends('layouts.app')

@section('title', 'Košík - Kontakt a doprava - Domáce Dekorácie')

@section('content')
<!-- Cart Section -->
<section class="checkout-page-container">
    <div class="container">
        <h1 class="cart-title">Kontakt a doprava</h1>

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
            <!-- Left Column - Checkout Form -->
            <div class="col-lg-8">
                <!-- Back to Shopping link with proper icon -->
                <a href="{{ route('cart.show') }}" class="checkout-back-link">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left me-1" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z" />
                    </svg>
                    <span>Nákupný košík</span>
                </a>

                <form id="checkoutForm" class="checkout-form">
                    <!-- Contact Information Section -->
                    <div class="checkout-form-section">
                        <h3 class="checkout-section-title">Kontaktné informácie</h3>
                        
                        @if(!Auth::check())
                            <div class="checkout-account-options">
                                <span>Máte účet?</span>
                                <a href="{{ route('login') }}">Prihlásiť sa</a>
                            </div>
                        @endif

                        <!-- Contact Fields -->
                        <div class="checkout-form-group">
                            <input type="email" class="checkout-form-control" id="email" placeholder="Email" 
                                value="{{ $cart->email ?? (Auth::check() ? Auth::user()->email : '') }}" required />
                            <div class="invalid-feedback">Zadajte platný email.</div>
                        </div>
                        <div class="checkout-form-group">
                            <input type="tel" class="checkout-form-control" id="phone" placeholder="Telefónne číslo" 
                                value="{{ $cart->phone ?? (Auth::check() && Auth::user()->phone ? Auth::user()->phone : '') }}" required />
                            <div class="invalid-feedback">Zadajte telefónne číslo.</div>
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="newsletter" {{ $cart->newsletter ? 'checked' : '' }} />
                            <label class="form-check-label" for="newsletter"> Zasielajte mi e-mail s novinkami a ponukami <span class="checkout-optional-label">(voliteľné)</span> </label>
                        </div>
                    </div>

                    <!-- Shipping Section -->
                    <div class="checkout-form-section">
                        <h3 class="checkout-section-title">Adresa pre doručenie</h3>

                        @if(Auth::check() && Auth::user()->addresses->count() > 0)
                            <div class="checkout-form-group">
                                <label for="saved-addresses">Uložené adresy</label>
                                <select class="checkout-form-control" id="saved-addresses" onchange="fillAddressForm(this.value)">
                                    <option value="">Vyberte adresu...</option>
                                    @foreach(Auth::user()->addresses as $address)
                                        <option value="{{ $address->address_id }}" 
                                            data-first-name="{{ Auth::user()->first_name }}"
                                            data-last-name="{{ Auth::user()->last_name }}"
                                            data-address="{{ $address->address_line1 }}"
                                            data-address2="{{ $address->address_line2 }}"
                                            data-city="{{ $address->city }}"
                                            data-postal="{{ $address->postal_code }}"
                                            data-country="{{ $address->country }}"
                                            {{ $address->is_default ? 'selected' : '' }}>
                                            {{ $address->address_line1 }}, {{ $address->city }}, {{ $address->country }} {{ $address->is_default ? '(predvolená)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="checkout-form-group">
                                    <input type="text" class="checkout-form-control" id="firstName" placeholder="Meno" 
                                        value="{{ $cart->first_name ?? (Auth::check() ? Auth::user()->first_name : '') }}" required />
                                    <div class="invalid-feedback">Zadajte meno.</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="checkout-form-group">
                                    <input type="text" class="checkout-form-control" id="lastName" placeholder="Priezvisko" 
                                        value="{{ $cart->last_name ?? (Auth::check() ? Auth::user()->last_name : '') }}" required />
                                    <div class="invalid-feedback">Zadajte priezvisko.</div>
                                </div>
                            </div>
                        </div>

                        <div class="checkout-form-group">
                            <input type="text" class="checkout-form-control" id="address" placeholder="Adresa" 
                                value="{{ $cart->address_line1 ?? (Auth::check() && Auth::user()->defaultAddress() ? Auth::user()->defaultAddress()->address_line1 : '') }}" required />
                            <div class="invalid-feedback">Zadajte adresu pre doručenie.</div>
                        </div>

                        <div class="checkout-form-group">
                            <input type="text" class="checkout-form-control" id="address2" placeholder="Byt, poschodie, atď. (voliteľné)" 
                                value="{{ $cart->address_line2 ?? (Auth::check() && Auth::user()->defaultAddress() && Auth::user()->defaultAddress()->address_line2 ? Auth::user()->defaultAddress()->address_line2 : '') }}" />
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="checkout-form-group">
                                    <input type="text" class="checkout-form-control" id="city" placeholder="Mesto" 
                                        value="{{ $cart->city ?? (Auth::check() && Auth::user()->defaultAddress() ? Auth::user()->defaultAddress()->city : '') }}" required />
                                    <div class="invalid-feedback">Zadajte mesto.</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="checkout-form-group">
                                    <input type="text" class="checkout-form-control" id="postal" placeholder="PSČ" 
                                        value="{{ $cart->postal_code ?? (Auth::check() && Auth::user()->defaultAddress() ? Auth::user()->defaultAddress()->postal_code : '') }}" required />
                                    <div class="invalid-feedback">Zadajte PSČ.</div>
                                </div>
                            </div>
                        </div>

                        <div class="checkout-form-group">
                            <select class="checkout-form-control" id="country" required>
                                <option value="" disabled {{ (!$cart->country && (!Auth::check() || !Auth::user()->defaultAddress())) ? 'selected' : '' }}>Vyberte krajinu</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->code }}" 
                                        {{ ($cart->country == $country->code) || (Auth::check() && Auth::user()->defaultAddress() && Auth::user()->defaultAddress()->country == $country->name) ? 'selected' : '' }}>
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">Vyberte krajinu.</div>
                        </div>
                    </div>

                    <!-- Shipping Methods Section -->
                    <div class="checkout-form-section" id="shipping-methods-section" style="display: none;">
                        <h3 class="checkout-section-title">Spôsob doručenia</h3>

                        <div class="checkout-shipping-options" id="shipping-options-container">
                            @if(count($countries) > 0)
                                @foreach($countries as $country)
                                    <div class="country-shipping-methods" data-country="{{ $country->code }}" style="display: none;">
                                        @foreach($country->shippingProviders as $provider)
                                            <div class="checkout-shipping-option">
                                                <div class="form-check">
                                                    <input class="form-check-input shipping-method-radio" 
                                                        type="radio" 
                                                        name="shippingMethod" 
                                                        id="shipping-provider-{{ $provider->provider_id }}" 
                                                        value="{{ $provider->provider_id }}"
                                                        data-price="{{ $provider->price }}"
                                                        {{ ($cart->shipping_provider_id == $provider->provider_id) || 
                                                           ($loop->first && !$cart->shipping_provider_id) ? 'checked' : '' }} />
                                                    <label class="form-check-label d-flex justify-content-between w-100" 
                                                        for="shipping-provider-{{ $provider->provider_id }}">
                                                        <span>{{ $provider->name }}</span>
                                                        <span>{{ number_format($provider->price, 2, ',', ' ') }} €</span>
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            @else
                                <div class="alert alert-warning">
                                    Momentálne nie sú k dispozícii žiadne spôsoby dopravy. Prosím, kontaktujte nás.
                                </div>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            <!-- Cart Summary - Right Column -->
            <div class="col-lg-4">
                <div class="checkout-cart-summary">
                    <div class="checkout-cart-summary-header mb-3">
                        <h3 class="checkout-section-title">Zhrnutie objednávky</h3>
                    </div>
                    
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
                    
                    <!-- Cart totals -->
                    <div class="checkout-cart-summary-row">
                        <div>Medzisúčet:</div>
                        <div>{{ number_format($subtotal, 2, ',', ' ') }} €</div>
                    </div>
                    <div class="checkout-cart-summary-row">
                        <div>Doprava:</div>
                        <div>{{ number_format($shipping, 2, ',', ' ') }} €</div>
                    </div>
                    <div class="checkout-cart-summary-row checkout-total">
                        <div>Celkom:</div>
                        <div>{{ number_format($total, 2, ',', ' ') }} €</div>
                    </div>
                    <button id="proceedToPaymentBtn" class="checkout-proceed-btn">Pokračovať na platbu</button>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
    .checkout-page-container {
        padding: 30px 0;
    }
    .cart-title {
        font-size: 28px;
        font-weight: 500;
        margin-bottom: 20px;
    }
    .checkout-form-control {
        padding: 10px 12px;
        border-radius: 0;
        border: 1px solid #ccc;
        margin-bottom: 15px;
        width: 100%;
    }
    .checkout-form-control.is-invalid {
        border-color: #dc3545;
    }
    .invalid-feedback {
        display: none;
        width: 100%;
        margin-top: -12px;
        margin-bottom: 10px;
        font-size: 80%;
        color: #dc3545;
    }
    .checkout-form-control.is-invalid + .invalid-feedback {
        display: block;
    }
    .checkout-optional-label {
        color: #6c757d;
        font-size: 0.85em;
        margin-left: 5px;
    }
    .checkout-back-link svg {
        vertical-align: -2px;
    }
    .checkout-cart-summary {
        background-color: #f9f9f9;
        padding: 20px;
        height: 100%;
    }
    .checkout-cart-summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
    }
    .checkout-cart-summary-row.checkout-total {
        font-size: 18px;
        font-weight: 600;
        margin-top: 15px;
        border-top: 1px solid #ddd;
        padding-top: 15px;
    }
    .checkout-proceed-btn {
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
        border-radius: 4px;
    }
    .checkout-proceed-btn:hover {
        background-color: #45a049;
        color: white;
    }
    .checkout-proceed-btn.disabled {
        background-color: #cccccc;
        cursor: not-allowed;
    }
    .checkout-section-title {
        font-size: 18px;
        font-weight: 500;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #ddd;
    }
    .checkout-shipping-option {
        margin-bottom: 12px;
    }
    .checkout-back-link {
        display: inline-block;
        margin-bottom: 15px;
        color: #555;
        text-decoration: none;
    }
    .checkout-back-link:hover {
        text-decoration: underline;
    }
    .checkout-form-section {
        margin-bottom: 30px;
    }
    .checkout-account-options {
        margin-bottom: 15px;
    }
    .checkout-account-options a {
        margin-left: 8px;
        color: #555;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Pre-select default address if available
        var savedAddressSelect = document.getElementById('saved-addresses');
        if (savedAddressSelect) {
            // If there's a default address (will have the selected attribute)
            // Fill the form with its data
            for (var i = 0; i < savedAddressSelect.options.length; i++) {
                if (savedAddressSelect.options[i].selected && savedAddressSelect.options[i].value !== '') {
                    fillAddressForm(savedAddressSelect.options[i].value);
                    break;
                }
            }
        }
        
        // Set up country change listener to update shipping options
        var countrySelect = document.getElementById('country');
        if (countrySelect) {
            countrySelect.addEventListener('change', updateShippingOptions);
            // Initialize with the current country selection
            updateShippingOptions();
            
            // After updating shipping options, if there's a previously selected shipping method, select it
            var savedShippingMethodId = {{ $cart->shipping_provider_id ?? 'null' }};
            if (savedShippingMethodId) {
                setTimeout(function() {
                    var savedShippingInput = document.querySelector('input[name="shippingMethod"][value="' + savedShippingMethodId + '"]');
                    if (savedShippingInput) {
                        savedShippingInput.checked = true;
                        updateShippingCost();
                    }
                }, 100); // Small timeout to ensure shipping options are loaded
            }
        }
        
        // Set up event listeners for shipping method selection
        document.querySelectorAll('.shipping-method-radio').forEach(function(radio) {
            radio.addEventListener('click', updateShippingCost);
        });

        // Initial update of shipping cost based on the selected method
        updateShippingCost();

        // Add validation and navigation to payment page
        const proceedToPaymentBtn = document.getElementById('proceedToPaymentBtn');
        proceedToPaymentBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (validateCheckoutForm()) {
                // Store form data in session
                const formData = new FormData();
                formData.append('email', document.getElementById('email').value);
                formData.append('phone', document.getElementById('phone').value);
                formData.append('firstName', document.getElementById('firstName').value);
                formData.append('lastName', document.getElementById('lastName').value);
                formData.append('address', document.getElementById('address').value);
                formData.append('address2', document.getElementById('address2').value);
                formData.append('city', document.getElementById('city').value);
                formData.append('postal', document.getElementById('postal').value);
                formData.append('country', document.getElementById('country').value);
                
                const selectedShippingMethod = document.querySelector('input[name="shippingMethod"]:checked');
                if (selectedShippingMethod) {
                    formData.append('shippingMethod', selectedShippingMethod.value);
                }
                
                formData.append('_token', '{{ csrf_token() }}');

                // Send form data to server using fetch
                fetch('{{ route('cart.storeCheckout') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Navigate to payment page
                        window.location.href = '{{ route('cart.payment') }}';
                    } else {
                        // Show error message
                        alert(data.message || 'Vyskytla sa chyba. Skontrolujte formulár a skúste znova.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Vyskytla sa chyba pri spracovaní požiadavky.');
                });
            }
        });
    });

    function validateCheckoutForm() {
        let isValid = true;
        const requiredFields = [
            'email', 'phone', 'firstName', 'lastName', 
            'address', 'city', 'postal', 'country'
        ];
        
        // Reset validation
        requiredFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            field.classList.remove('is-invalid');
        });
        
        // Check each required field
        requiredFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            }
        });
        
        // Validate email format
        const emailField = document.getElementById('email');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(emailField.value.trim())) {
            emailField.classList.add('is-invalid');
            isValid = false;
        }
        
        // Validate shipping method selection
        const shippingMethodsSection = document.getElementById('shipping-methods-section');
        if (shippingMethodsSection.style.display !== 'none') {
            const selectedShippingMethod = document.querySelector('input[name="shippingMethod"]:checked');
            if (!selectedShippingMethod) {
                isValid = false;
                alert('Prosím, vyberte spôsob dopravy.');
            }
        }
        
        return isValid;
    }

    function updateShippingOptions() {
        var countrySelect = document.getElementById('country');
        var selectedCountry = countrySelect.value;
        var shippingMethodsSection = document.getElementById('shipping-methods-section');
        
        // Hide the shipping methods section by default
        if (shippingMethodsSection) {
            shippingMethodsSection.style.display = 'none';
        }
        
        // If no country is selected or the placeholder option is selected, return early
        if (!selectedCountry || selectedCountry === '') {
            return;
        }
        
        // Hide all country shipping methods
        var countryMethods = document.querySelectorAll('.country-shipping-methods');
        countryMethods.forEach(function(methodsGroup) {
            methodsGroup.style.display = 'none';
        });
        
        // Show only the methods for the selected country
        var relevantMethods = document.querySelector('.country-shipping-methods[data-country="' + selectedCountry + '"]');
        if (relevantMethods) {
            relevantMethods.style.display = 'block';
            
            // Select the first shipping option for this country
            var firstInput = relevantMethods.querySelector('input[type="radio"]');
            if (firstInput) {
                firstInput.checked = true;
                // Update shipping cost based on selected method
                updateShippingCost();
            }
            
            // Only show the shipping methods section if a valid country with shipping options is selected
            if (shippingMethodsSection) {
                shippingMethodsSection.style.display = 'block';
            }
        }
    }
    
    function updateShippingCost() {
        console.log("Updating shipping cost...");
        
        // Get the selected shipping method's price
        var selectedMethod = document.querySelector('input[name="shippingMethod"]:checked');
        if (!selectedMethod) {
            console.log("No shipping method selected");
            return;
        }
        
        var shippingCost = parseFloat(selectedMethod.getAttribute('data-price'));
        console.log("Selected shipping cost:", shippingCost);
        
        // Find all checkout-cart-summary-row elements
        var summaryRows = document.querySelectorAll('.checkout-cart-summary-row');
        if (summaryRows.length < 2) {
            console.log("Could not find summary rows");
            return;
        }
        
        // Update shipping cost row (second row)
        var shippingRow = summaryRows[1];
        var shippingPriceElement = shippingRow.querySelector('div:last-child');
        if (shippingPriceElement) {
            shippingPriceElement.textContent = shippingCost.toFixed(2).replace('.', ',') + ' €';
            console.log("Updated shipping price display:", shippingPriceElement.textContent);
        } else {
            console.log("Could not find shipping price element");
        }
        
        // Get subtotal from first row
        var subtotalRow = summaryRows[0];
        var subtotalElement = subtotalRow.querySelector('div:last-child');
        if (!subtotalElement) {
            console.log("Could not find subtotal element");
            return;
        }
        
        var subtotalText = subtotalElement.textContent;
        var subtotal = parseFloat(subtotalText.replace(' €', '').replace(',', '.').replace(/\s/g, ''));
        console.log("Parsed subtotal:", subtotal);
        
        // Calculate and update total (in the checkout-total row)
        var total = subtotal + shippingCost;
        var totalRow = document.querySelector('.checkout-cart-summary-row.checkout-total');
        if (!totalRow) {
            console.log("Could not find total row");
            return;
        }
        
        var totalElement = totalRow.querySelector('div:last-child');
        if (totalElement) {
            totalElement.textContent = total.toFixed(2).replace('.', ',') + ' €';
            console.log("Updated total:", totalElement.textContent);
        } else {
            console.log("Could not find total element");
        }
    }

    function fillAddressForm(addressId) {
        if (!addressId) return;
        
        var option = document.querySelector('option[value="' + addressId + '"]');
        if (!option) return;
        
        // Fill form fields with address data
        document.getElementById('firstName').value = option.getAttribute('data-first-name');
        document.getElementById('lastName').value = option.getAttribute('data-last-name');
        document.getElementById('address').value = option.getAttribute('data-address');
        document.getElementById('address2').value = option.getAttribute('data-address2') || '';
        document.getElementById('city').value = option.getAttribute('data-city');
        document.getElementById('postal').value = option.getAttribute('data-postal');
        
        // Set country value
        var countrySelect = document.getElementById('country');
        var countryCode = option.getAttribute('data-country');
        
        // Find the option with the matching country name or code and select it
        for (var i = 0; i < countrySelect.options.length; i++) {
            if (countrySelect.options[i].text === countryCode || 
                countrySelect.options[i].value === countryCode) {
                countrySelect.selectedIndex = i;
                // Update shipping options based on the selected country
                updateShippingOptions();
                break;
            }
        }
    }
</script>
@endsection