@extends('layouts.app')

@section('title', 'Môj profil - Domáce Dekorácie')

@section('content')
<!-- Profile Section -->
<section class="profile-page-container">
    <div class="container">
        <h1 class="profile-page-title">Môj profil</h1>

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
            <!-- Personal Information Form -->
            <div class="col-lg-6">
                <h2 class="profile-page-section-title">Moje údaje</h2>
                <form class="profile-page-form" action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    <div class="profile-page-form-group">
                        <input type="text" class="form-control profile-page-input @error('first_name') is-invalid @enderror" 
                               name="first_name" value="{{ old('first_name', $user->first_name) }}" placeholder="Meno" required />
                        @error('first_name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="profile-page-form-group">
                        <input type="text" class="form-control profile-page-input @error('last_name') is-invalid @enderror" 
                               name="last_name" value="{{ old('last_name', $user->last_name) }}" placeholder="Priezvisko" required />
                        @error('last_name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="profile-page-form-group">
                        <input type="email" class="form-control profile-page-input @error('email') is-invalid @enderror" 
                               name="email" value="{{ old('email', $user->email) }}" placeholder="E-mail" required />
                        @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="profile-page-form-group">
                        <input type="tel" class="form-control profile-page-input @error('phone') is-invalid @enderror" 
                               name="phone" value="{{ old('phone', $user->phone) }}" placeholder="Telefón" />
                        @error('phone')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn profile-page-save-btn">Uložiť</button>
                    </div>
                </form>
            </div>

            <!-- Addresses Section -->
            <div class="col-lg-6">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="profile-page-section-title mb-0">Moje adresy</h2>
                    <a href="{{ route('profile.address.create') }}" class="btn profile-page-add-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2Z"/>
                        </svg>
                        Pridať adresu
                    </a>
                </div>

                @if($addresses->count() > 0)
                    <div class="address-list">
                        @foreach($addresses as $address)
                            <div class="address-card {{ $address->is_default ? 'address-card-default' : '' }}">
                                @if($address->is_default)
                                    <div class="default-badge">Predvolená</div>
                                @endif
                                
                                <div class="address-card-body">
                                    <div class="address-details">
                                        <p class="address-line">{{ $address->address_line1 }}</p>
                                        @if($address->address_line2)
                                            <p class="address-line">{{ $address->address_line2 }}</p>
                                        @endif
                                        <p class="address-line">{{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}</p>
                                        <p class="address-line">{{ $address->country }}</p>
                                    </div>
                                    
                                    <div class="address-actions">
                                        <div class="btn-group">
                                            <a href="{{ route('profile.address.edit', $address->address_id) }}" class="btn btn-sm btn-outline-secondary">
                                                Upraviť
                                            </a>
                                            
                                            @if(!$address->is_default)
                                                <form action="{{ route('profile.address.default', $address->address_id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-sm btn-outline-primary">
                                                        Nastaviť ako predvolenú
                                                    </button>
                                                </form>
                                            
                                                <form action="{{ route('profile.address.delete', $address->address_id) }}" method="POST" class="d-inline"
                                                      onsubmit="return confirm('Naozaj chcete vymazať túto adresu?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        Vymazať
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-info">
                        Nemáte pridané žiadne adresy. Kliknite na tlačidlo "Pridať adresu" pre pridanie novej adresy.
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
    /* Profile page specific styles */
    .profile-page-container {
        padding: 20px 0 60px;
    }
    .profile-page-title {
        font-size: 28px;
        margin-bottom: 25px;
        border-bottom: 1px solid #ddd;
        padding-bottom: 15px;
    }
    .profile-page-section-title {
        font-size: 20px;
        margin-bottom: 20px;
        font-weight: 500;
    }
    .profile-page-form-group {
        margin-bottom: 20px;
    }
    .profile-page-input {
        height: 45px;
        border-radius: 0;
        border: 1px solid #ddd;
    }
    .profile-page-input:focus {
        box-shadow: none;
        border-color: #aaa;
    }
    .profile-page-save-btn, .profile-page-add-btn {
        background-color: #aaa;
        color: white;
        border: none;
        padding: 10px 30px;
        border-radius: 0;
        font-weight: 500;
        transition: background-color 0.3s;
    }
    .profile-page-save-btn:hover, .profile-page-add-btn:hover {
        background-color: #999;
        color: white;
    }
    .profile-page-add-btn {
        padding: 8px 15px;
    }
    .profile-page-add-btn svg {
        margin-right: 5px;
    }
    .profile-page-form {
        margin-bottom: 30px;
    }
    
    /* Address card styles */
    .address-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    .address-card {
        border: 1px solid #ddd;
        border-radius: 5px;
        position: relative;
        padding: 15px;
    }
    .address-card-default {
        border-color: #aaa;
        background-color: rgba(170, 170, 170, 0.05);
    }
    .default-badge {
        position: absolute;
        top: 0;
        right: 0;
        background-color: #aaa;
        color: white;
        font-size: 12px;
        padding: 3px 8px;
        border-bottom-left-radius: 5px;
    }
    .address-card-body {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    .address-details {
        flex-grow: 1;
    }
    .address-line {
        margin-bottom: 5px;
    }
    .address-actions {
        display: flex;
        justify-content: flex-end;
    }
    .address-actions .btn-group {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
    }
    
    /* Responsive styles */
    @media (max-width: 767px) {
        .profile-page-container {
            padding: 15px 0 40px;
        }
        .profile-page-title {
            font-size: 24px;
            margin-bottom: 20px;
        }
        .profile-page-section-title {
            font-size: 18px;
            margin-bottom: 15px;
        }
        .profile-page-save-btn {
            width: 100%;
        }
        .address-card-body {
            flex-direction: column;
        }
        .address-actions {
            margin-top: 10px;
            justify-content: flex-start;
        }
    }
</style>
@endsection