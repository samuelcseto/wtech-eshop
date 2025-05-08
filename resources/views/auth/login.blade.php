@extends('layouts.app')

@section('title', 'Prihlásenie - Domáce Dekorácie')

@section('content')
<!-- Login Section -->
<section class="login-page-container">
    <div class="login-page-image">
        <!-- Background image with blur effect -->
        <img style="width: 100%; height: 100%; filter: blur(5px)" src="https://s41585.pcdn.co/wp-content/themes/nice-north-america/assets/img/module-smart-home-montage-1-alt.jpg" alt="" />
    </div>
    <div class="login-page-form-container">
        <h1 class="login-page-title">Prihlásenie</h1>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <input type="email" class="form-control login-page-input @error('email') is-invalid @enderror" id="email" name="email" placeholder="E-mail" required value="{{ old('email') }}" />
                @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="mb-3">
                <input type="password" class="form-control login-page-input @error('password') is-invalid @enderror" id="password" name="password" placeholder="Heslo" required />
                @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember">Zapamätať si ma</label>
            </div>
            <button type="submit" class="btn login-page-button w-100">Prihlásiť sa</button>
        </form>

        <div class="login-page-register-link">
            <span>Nemáte účet? <a href="{{ route('register') }}">Vytvorte si</a></span>
        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
    .login-page-container {
        display: flex;
        min-height: calc(100vh - 300px);
    }
    .login-page-image {
        flex: 1;
        background-color: #e6e6e6;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }
    .login-page-form-container {
        flex: 1;
        padding: 50px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .login-page-title {
        font-size: 32px;
        margin-bottom: 30px;
    }
    .login-page-input {
        height: 50px;
        margin-bottom: 20px;
    }
    .login-page-button {
        height: 50px;
        background-color: #aaaaaa;
        border: none;
        color: white;
        font-weight: 500;
    }
    .login-page-button:hover {
        background-color: #999999;
        color: white;
    }
    .login-page-register-link {
        text-align: center;
        margin-top: 20px;
    }
    .login-page-register-link a {
        color: #333;
        text-decoration: none;
        font-weight: 500;
    }
    .login-page-register-link a:hover {
        text-decoration: underline;
    }

    /* Responsive styles */
    @media (max-width: 991px) {
        .login-page-container {
            flex-direction: column;
        }
        .login-page-image {
            min-height: 200px;
        }
        .login-page-form-container {
            padding: 30px 20px;
        }
        .login-page-title {
            font-size: 28px;
            text-align: center;
        }
    }

    @media (max-width: 575px) {
        .login-page-form-container {
            padding: 20px 15px;
        }
        .login-page-input {
            height: 45px;
            margin-bottom: 15px;
        }
        .login-page-title {
            font-size: 24px;
            margin-bottom: 20px;
        }
    }
</style>
@endsection