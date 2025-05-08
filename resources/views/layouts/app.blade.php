<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Domáce Dekorácie - Elegantné Bývanie')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Custom CSS -->
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
    <!-- Additional styles -->
    @yield('styles')
</head>
<body>
    @include('partials.header')

    <main>
        @yield('content')
    </main>

    @include('partials.footer')

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <!-- Custom Scripts -->
    <script src="{{ asset('js/script.js') }}"></script>
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

            // Cart functionality
            function setupCartEventListeners() {
                // Setup quantity change buttons in cart preview
                const quantityBtns = document.querySelectorAll('.quantity-btn');
                quantityBtns.forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        const itemContainer = this.closest('.cart-preview-item');
                        const itemId = itemContainer.dataset.itemId;
                        const inputElement = this.parentElement.querySelector('.quantity-input');
                        let currentQuantity = parseInt(inputElement.value);
                        
                        // Increase or decrease quantity
                        if (this.textContent === '−') {
                            if (currentQuantity > 1) {
                                currentQuantity -= 1;
                            }
                        } else {
                            currentQuantity += 1;
                        }
                        
                        // Update the displayed quantity immediately for better UX
                        inputElement.value = currentQuantity;
                        
                        // Make AJAX call to update the cart
                        updateCartItem(itemId, currentQuantity);
                    });
                });
                
                // Setup remove item buttons
                const removeButtons = document.querySelectorAll('.item-remove');
                removeButtons.forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        const itemContainer = this.closest('.cart-preview-item');
                        const itemId = itemContainer.dataset.itemId;
                        
                        // Remove the item from the DOM immediately for better UX
                        itemContainer.style.opacity = '0.5';
                        
                        // Make AJAX call to remove from cart
                        removeCartItem(itemId);
                    });
                });
            }

            // Function to update cart item quantity via AJAX
            function updateCartItem(itemId, quantity) {
                const formData = new FormData();
                formData.append('quantity', quantity);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                
                fetch(`/cart/update/${itemId}`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateCartDisplay(data.cart_count, data.cart_total);
                    }
                })
                .catch(error => console.error('Error updating cart:', error));
            }
            
            // Function to remove cart item via AJAX
            function removeCartItem(itemId) {
                const formData = new FormData();
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                
                fetch(`/cart/remove/${itemId}`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove item from display
                        const itemElement = document.querySelector(`.cart-preview-item[data-item-id="${itemId}"]`);
                        if (itemElement) {
                            itemElement.remove();
                        }
                        
                        updateCartDisplay(data.cart_count, data.cart_total);
                        
                        // Show empty cart message if no items left
                        if (data.cart_count === 0) {
                            const cartItems = document.querySelectorAll('.cart-preview-item');
                            if (cartItems.length === 0) {
                                const cartPreview = document.getElementById('cartPreview');
                                cartPreview.innerHTML = `
                                    <div class="cart-empty">
                                        <p>Váš košík je prázdny</p>
                                    </div>
                                `;
                            }
                        }
                    }
                })
                .catch(error => console.error('Error removing item:', error));
            }
            
            // Update cart display elements
            function updateCartDisplay(count, total) {
                // Update cart count in header
                const cartCountElement = document.querySelector('.cart-count');
                if (cartCountElement) {
                    cartCountElement.textContent = count;
                }
                
                // Update cart total in preview
                const cartTotalElement = document.querySelector('.cart-total span:last-child');
                if (cartTotalElement && typeof total === 'number') {
                    cartTotalElement.textContent = total.toLocaleString('sk-SK', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }).replace('.', ',') + ' €';
                }
            }
            
            // Initialize cart functionality
            setupCartEventListeners();
        });
    </script>
    <!-- Additional scripts -->
    @yield('scripts')
</body>
</html>
