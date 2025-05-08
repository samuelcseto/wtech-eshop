@extends('layouts.app')

@section('title', 'Registrácia - Domáce Dekorácie')

@section('content')
<!-- Registration Section -->
<section class="register-page-container">
    <div class="register-page-image">
        <!-- Background image with blur effect -->
        <img style="width: 100%; height: 100%; filter: blur(5px); object-fit: cover" src="https://cf.bstatic.com/xdata/images/hotel/max1024x768/202903770.jpg?k=84ff167532e6b6a2c0b4df20278acd8c356d826061e346cb78c62827772e8999&o=&hp=1" alt="" />
    </div>
    <div class="register-page-form-container">
        <h1 class="register-page-title">Vytvoriť Konto</h1>

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="mb-3">
                <input type="text" class="form-control register-page-input @error('first_name') is-invalid @enderror" id="first_name" name="first_name" placeholder="Meno" required value="{{ old('first_name') }}" />
                @error('first_name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="mb-3">
                <input type="text" class="form-control register-page-input @error('last_name') is-invalid @enderror" id="last_name" name="last_name" placeholder="Priezvisko" required value="{{ old('last_name') }}" />
                @error('last_name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="mb-3">
                <input type="email" class="form-control register-page-input @error('email') is-invalid @enderror" id="email" name="email" placeholder="E-mail" required value="{{ old('email') }}" />
                @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="mb-3 register-page-password-wrapper">
                <input type="password" class="form-control register-page-input @error('password') is-invalid @enderror" id="password" name="password" placeholder="Heslo" required />
                <span class="register-page-password-toggle" id="passwordToggle">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-slash" viewBox="0 0 16 16">
                        <path
                            d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7.028 7.028 0 0 0-2.79.588l.77.771A5.944 5.944 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.134 13.134 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755-.165.165-.337.328-.517.486l.708.709z"
                        />
                        <path d="M11.297 9.176a3.5 3.5 0 0 0-4.474-4.474l.823.823a2.5 2.5 0 0 1 2.829 2.829l.822.822zm-2.943 1.299.822.822a3.5 3.5 0 0 1-4.474-4.474l.823.823a2.5 2.5 0 0 0 2.829 2.829z" />
                        <path
                            d="M3.35 5.47c-.18.16-.353.322-.518.487A13.134 13.134 0 0 0 1.172 8l.195.288c.335.48.83 1.12 1.465 1.755C4.121 11.332 5.881 12.5 8 12.5c.716 0 1.39-.133 2.02-.36l.77.772A7.029 7.029 0 0 1 8 13.5C3 13.5 0 8 0 8s.939-1.721 2.641-3.238l.708.709zm10.296 8.884-12-12 .708-.708 12 12-.708.708z"
                        />
                    </svg>
                </span>
                @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <button type="submit" class="btn register-page-button w-100">Vytvoriť</button>
        </form>

        <div class="register-page-login-link">
            <span>Už máte účet? <a href="{{ route('login') }}">Prihláste sa</a></span>
        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
    .register-page-container {
        display: flex;
        min-height: calc(100vh - 300px);
    }
    .register-page-image {
        flex: 1;
        background-color: #e6e6e6;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }
    .register-page-form-container {
        flex: 1;
        padding: 50px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .register-page-title {
        font-size: 32px;
        margin-bottom: 30px;
    }
    .register-page-input {
        height: 50px;
        margin-bottom: 20px;
    }
    .register-page-button {
        height: 50px;
        background-color: #aaaaaa;
        border: none;
        color: white;
        font-weight: 500;
    }
    .register-page-button:hover {
        background-color: #999999;
        color: white;
    }
    .register-page-login-link {
        text-align: center;
        margin-top: 20px;
    }
    .register-page-login-link a {
        color: #333;
        text-decoration: none;
        font-weight: 500;
    }
    .register-page-login-link a:hover {
        text-decoration: underline;
    }
    .register-page-password-wrapper {
        position: relative;
    }
    .register-page-password-toggle {
        position: absolute;
        right: 10px;
        top: 15px;
        cursor: pointer;
        color: #666;
    }
    
    /* Responsive styles */
    @media (max-width: 991px) {
        .register-page-container {
            flex-direction: column;
        }
        .register-page-image {
            min-height: 200px;
        }
        .register-page-form-container {
            padding: 30px 20px;
        }
        .register-page-title {
            font-size: 28px;
            text-align: center;
        }
    }

    @media (max-width: 575px) {
        .register-page-form-container {
            padding: 20px 15px;
        }
        .register-page-input {
            height: 45px;
            margin-bottom: 15px;
        }
        .register-page-title {
            font-size: 24px;
            margin-bottom: 20px;
        }
    }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const passwordToggle = document.getElementById('passwordToggle')
    const passwordInput = document.getElementById('password')

    passwordToggle.addEventListener('click', function () {
        // Toggle the password visibility
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text'
            passwordToggle.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                    <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                </svg>
            `
        } else {
            passwordInput.type = 'password'
            passwordToggle.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-slash" viewBox="0 0 16 16">
                    <path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7.028 7.028 0 0 0-2.79.588l.77.771A5.944 5.944 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.134 13.134 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755-.165.165-.337.328-.517.486l.708.709z"/>
                    <path d="M11.297 9.176a3.5 3.5 0 0 0-4.474-4.474l.823.823a2.5 2.5 0 0 1 2.829 2.829l.822.822zm-2.943 1.299.822.822a3.5 3.5 0 0 1-4.474-4.474l.823.823a2.5 2.5 0 0 0 2.829 2.829z"/>
                    <path d="M3.35 5.47c-.18.16-.353.322-.518.487A13.134 13.134 0 0 0 1.172 8l.195.288c.335.48.83 1.12 1.465 1.755C4.121 11.332 5.881 12.5 8 12.5c.716 0 1.39-.133 2.02-.36l.77.772A7.029 7.029 0 0 1 8 13.5C3 13.5 0 8 0 8s.939-1.721 2.641-3.238l.708.709zm10.296 8.884-12-12 .708-.708 12 12-.708.708z"/>
                </svg>
            `
        }
    })
})
</script>
@endsection