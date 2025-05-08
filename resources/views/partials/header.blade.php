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
                    <a class="nav-link" href="#">Katalóg</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Výpredaj</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Služby</a>
                </li>
            </ul>
            <form class="d-flex ms-auto">
                <div class="input-group">
                    <input class="form-control" type="search" placeholder="Hľadať" aria-label="Hľadať" />
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
                            <a href="#" class="d-flex align-items-center text-decoration-none">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-person me-3" viewBox="0 0 16 16">
                                    <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4Zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10Z" />
                                </svg>
                                <span>Môj profil</span>
                            </a>
                        </div>
                        <div class="user-menu-item">
                            <a href="#" class="d-flex align-items-center text-decoration-none">
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
                    <a class="nav-link" href="#">
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
                                <div class="cart-preview-item">
                                    <div class="d-flex">
                                        <div class="item-image">
                                            <img src="{{ $item->product->primaryImage?->image_url ? asset($item->product->primaryImage->image_url) : 'https://via.placeholder.com/100' }}" alt="{{ $item->product->name }}" />
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $item->product->name }}</h6>
                                            <small class="text-muted">{{ $item->variant_details ?? '' }}</small>
                                        </div>
                                        <span class="item-remove">×</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <div class="quantity-control">
                                            <button class="quantity-btn">−</button>
                                            <input type="text" class="quantity-input" value="{{ $item->quantity }}" readonly />
                                            <button class="quantity-btn">+</button>
                                        </div>
                                        <div class="fw-bold">{{ number_format($item->price, 2, ',', ' ') }} €</div>
                                    </div>
                                </div>
                            @endforeach
                            <div class="cart-total">
                                <span>Medzisúčet:</span>
                                <span>{{ number_format($cart_total, 2, ',', ' ') }} €</span>
                            </div>
                            <a href="#" class="cart-checkout">Pokračovať</a>
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
</style>
