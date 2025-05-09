<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            <img src="{{ asset('images/logo.png') }}" alt="Logo Domáce Dekorácie" />
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('products.index') }}">Katalóg</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Výpredaj</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Služby</a>
                </li>
            </ul>
            <form class="d-flex ms-auto" method="GET" action="{{ route('products.index') }}">
                <div class="input-group">
                    <input class="form-control" type="search" name="search" placeholder="Hľadať" aria-label="Hľadať" value="{{ request('search') }}" />
                    <button class="btn btn-outline-secondary" type="submit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                        </svg>
                    </button>
                </div>
            </form>
            <ul class="navbar-nav ms-3">
                <li class="nav-item user-container" id="userContainer">
                    <a class="nav-link d-flex align-items-center" href="{{ auth()->check() ? '#' : route('login') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                            <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4Zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10Z" />
                        </svg>
                        @auth
                            <span class="ms-2">{{ auth()->user()->first_name }}</span>
                        @endauth
                    </a>

                    @if (auth()->check())
                    <!-- User Dropdown Menu -->
                    <div class="user-menu" id="userMenu">
                        <div class="user-menu-header px-3 py-2 border-bottom">
                            <span class="fw-bold">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</span>
                            <small class="d-block text-muted">{{ auth()->user()->email }}</small>
                        </div>
                        <div class="user-menu-item">
                            <a href="{{ route('profile.show') }}" class="d-flex align-items-center text-decoration-none">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-person me-3" viewBox="0 0 16 16">
                                    <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4Zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10Z" />
                                </svg>
                                <span>Môj profil</span>
                            </a>
                        </div>
                        @if(auth()->user()->isAdmin())
                        <div class="user-menu-item">
                            <a href="{{ route('admin.products.index') }}" class="d-flex align-items-center text-decoration-none">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-shield-lock me-3" viewBox="0 0 16 16">
                                    <path d="M5.338 1.59a61.44 61.44 0 0 0-2.837.856.481.481 0 0 0-.328.39c-.554 4.157.726 7.19 2.253 9.188a10.725 10.725 0 0 0 2.287 2.233c.346.244.652.42.893.533.12.057.218.095.293.118a.55.55 0 0 0 .101.025.615.615 0 0 0 .1-.025c.076-.023.174-.061.294-.118.24-.113.547-.29.893-.533a10.726 10.726 0 0 0 2.287-2.233c1.527-1.997 2.807-5.031 2.253-9.188a.48.48 0 0 0-.328-.39c-.651-.213-1.75-.56-2.837-.855C9.552 1.29 8.531 1.067 8 1.067c-.53 0-1.552.223-2.662.524zM5.072.56C6.157.265 7.31 0 8 0s1.843.265 2.928.56c1.11.3 2.229.655 2.887.87a1.54 1.54 0 0 1 1.044 1.262c.596 4.477-.787 7.795-2.465 9.99a11.775 11.775 0 0 1-2.517 2.453 7.159 7.159 0 0 1-1.048.625c-.28.132-.581.24-.829.24s-.548-.108-.829-.24a7.158 7.158 0 0 1-1.048-.625 11.777 11.777 0 0 1-2.517-2.453C1.928 10.487.545 7.169 1.141 2.692A1.54 1.54 0 0 1 2.185 1.43 62.456 62.456 0 0 1 5.072.56z"/>
                                    <path d="M9.5 6.5a1.5 1.5 0 0 1-1 1.415l.385 1.99a.5.5 0 0 1-.491.595h-.788a.5.5 0 0 1-.49-.595l.384-1.99a1.5 1.5 0 1 1 2-1.415z"/>
                                </svg>
                                <span>Admin</span>
                            </a>
                        </div>
                        @endif
                        <div class="user-menu-item">
                            <a href="{{ route('orders.index') }}" class="d-flex align-items-center text-decoration-none">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-list me-3" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z" />
                                </svg>
                                <span>Objednávky</span>
                            </a>
                        </div>
                        <div class="user-menu-item">
                            <a href="{{ route('logout') }}" class="d-flex align-items-center text-decoration-none"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-box-arrow-right me-3" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z" />
                                    <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z" />
                                </svg>
                                <span>Odhlásiť sa</span>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </div>
                    @else
                    <!-- Guest dropdown menu -->
                    <div class="user-menu d-none" id="userMenu">
                        <div class="user-menu-item">
                            <a href="{{ route('login') }}" class="d-flex align-items-center text-decoration-none">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-box-arrow-in-right me-3" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M6 3.5a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-2a.5.5 0 0 0-1 0v2A1.5 1.5 0 0 0 6.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-8A1.5 1.5 0 0 0 5 3.5v2a.5.5 0 0 0 1 0v-2z"/>
                                    <path fill-rule="evenodd" d="M11.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H1.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
                                </svg>
                                <span>Prihlásiť sa</span>
                            </a>
                        </div>
                        <div class="user-menu-item">
                            <a href="{{ route('register') }}" class="d-flex align-items-center text-decoration-none">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-person-plus me-3" viewBox="0 0 16 16">
                                    <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H1s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C9.516 10.68 8.289 10 6 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                                    <path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5z"/>
                                </svg>
                                <span>Registrovať sa</span>
                            </a>
                        </div>
                    </div>
                    @endif
                </li>
                <li class="nav-item cart-container" id="cartContainer">
                    <a class="nav-link" href="{{ route('cart.show') }}">
                        <div class="cart-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16">
                                <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                            </svg>
                            <span class="cart-count">{{ isset($cart_count) ? $cart_count : '0' }}</span>
                        </div>
                    </a>
                    <!-- Shopping Cart Preview -->
                    <div class="cart-preview" id="cartPreview">
                        @if(isset($cart_items) && count($cart_items) > 0)
                            @foreach($cart_items as $item)
                                <div class="cart-preview-item" data-item-id="{{ $item->cart_item_id }}">
                                    <div class="d-flex">
                                        <div class="item-image">
                                            <img src="{{ $item->product->primaryImage?->image_url ? asset($item->product->primaryImage->image_url) : 'https://via.placeholder.com/100' }}" alt="{{ $item->product->name }}" />
                                        </div>
                                        <div class="flex-grow-1 me-2">
                                            <h6 class="mb-0">{{ $item->product->name }}</h6>
                                            <small class="text-muted">{{ $item->variant_details ?? '' }}</small>
                                            <p class="mb-0 small item-description">{{ \Illuminate\Support\Str::limit($item->product->description, 60) }}</p>
                                        </div>
                                        <span class="item-remove">×</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <div class="quantity-control">
                                            <button class="quantity-btn">−</button>
                                            <input type="text" class="quantity-input" value="{{ $item->quantity }}" readonly />
                                            <button class="quantity-btn">+</button>
                                        </div>
                                        <div class="price-details text-end">
                                            <div class="small text-muted">{{ number_format($item->price, 2, ',', ' ') }} € / ks</div>
                                            <div class="fw-bold">{{ number_format($item->price * $item->quantity, 2, ',', ' ') }} €</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <div class="cart-total mt-2 d-flex justify-content-between border-top pt-2">
                                <span>Medzisúčet:</span>
                                <span>{{ number_format($cart_total, 2, ',', ' ') }} €</span>
                            </div>
                            <a href="{{ route('cart.show') }}" class="cart-checkout">Pokračovať</a>
                        @else
                            <div class="cart-empty">
                                <p>Váš košík je prázdny</p>
                            </div>
                        @endif
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
.user-menu-header {
    background-color: #f8f9fa;
}

/* Cart preview styles */
.cart-preview {
    width: 350px;
    max-height: 500px;
    overflow-y: auto;
}

.cart-preview-item {
    padding: 12px;
    border-bottom: 1px solid #eee;
}

.item-image img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    margin-right: 12px;
}

.item-description {
    color: #666;
    font-size: 0.8rem;
    margin-top: 4px;
}

.item-remove {
    cursor: pointer;
    font-size: 1.2rem;
    color: #999;
    padding: 0 8px;
}

.item-remove:hover {
    color: #dc3545;
}

.quantity-control {
    display: flex;
    align-items: center;
}

.quantity-btn {
    width: 24px;
    height: 24px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f0f0f0;
    border: 1px solid #ddd;
    font-weight: bold;
}

.quantity-input {
    width: 32px;
    text-align: center;
    border: 1px solid #ddd;
    margin: 0 4px;
}

.price-details {
    min-width: 80px;
}

.cart-total {
    font-weight: bold;
    margin-top: 8px;
}

.cart-checkout {
    display: block;
    width: 100%;
    padding: 10px 0;
    background-color: #4CAF50;
    color: white;
    text-align: center;
    text-decoration: none;
    border-radius: 4px;
    margin-top: 10px;
    font-weight: bold;
}

.cart-checkout:hover {
    background-color: #45a049;
    color: white;
}
</style>
