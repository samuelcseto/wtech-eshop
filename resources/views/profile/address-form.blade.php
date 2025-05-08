@extends('layouts.app')

@section('title', (isset($address) ? 'Upraviť' : 'Pridať') . ' adresu - Domáce Dekorácie')

@section('content')
<!-- Address Form Section -->
<section class="profile-page-container">
    <div class="container">
        <h1 class="profile-page-title">
            {{ isset($address) ? 'Upraviť adresu' : 'Pridať novú adresu' }}
        </h1>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <form class="profile-page-form" action="{{ isset($address) 
                    ? route('profile.address.update', $address->address_id) 
                    : route('profile.address.store') }}" 
                    method="POST">
                    @csrf
                    @if(isset($address))
                        @method('PUT')
                    @endif

                    <div class="row">
                        <div class="col-md-12">
                            <div class="profile-page-form-group">
                                <label for="address_line1" class="form-label">Ulica a číslo *</label>
                                <input type="text" 
                                       class="form-control profile-page-input @error('address_line1') is-invalid @enderror" 
                                       id="address_line1"
                                       name="address_line1" 
                                       value="{{ old('address_line1', isset($address) ? $address->address_line1 : '') }}" 
                                       placeholder="Hlavná 123" required />
                                @error('address_line1')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="profile-page-form-group">
                                <label for="address_line2" class="form-label">Doplnková adresa</label>
                                <input type="text" 
                                       class="form-control profile-page-input @error('address_line2') is-invalid @enderror" 
                                       id="address_line2"
                                       name="address_line2" 
                                       value="{{ old('address_line2', isset($address) ? $address->address_line2 : '') }}" 
                                       placeholder="Byt 4B, poschodie 2" />
                                @error('address_line2')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="profile-page-form-group">
                                <label for="city" class="form-label">Mesto *</label>
                                <input type="text" 
                                       class="form-control profile-page-input @error('city') is-invalid @enderror" 
                                       id="city"
                                       name="city" 
                                       value="{{ old('city', isset($address) ? $address->city : '') }}" 
                                       placeholder="Bratislava" required />
                                @error('city')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="profile-page-form-group">
                                <label for="state" class="form-label">Kraj *</label>
                                <input type="text" 
                                       class="form-control profile-page-input @error('state') is-invalid @enderror" 
                                       id="state"
                                       name="state" 
                                       value="{{ old('state', isset($address) ? $address->state : '') }}" 
                                       placeholder="Bratislavský kraj" required />
                                @error('state')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="profile-page-form-group">
                                <label for="postal_code" class="form-label">PSČ *</label>
                                <input type="text" 
                                       class="form-control profile-page-input @error('postal_code') is-invalid @enderror" 
                                       id="postal_code"
                                       name="postal_code" 
                                       value="{{ old('postal_code', isset($address) ? $address->postal_code : '') }}" 
                                       placeholder="81102" required />
                                @error('postal_code')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="profile-page-form-group">
                                <label for="country" class="form-label">Krajina *</label>
                                <select class="form-control profile-page-input @error('country') is-invalid @enderror" 
                                       id="country"
                                       name="country" required>
                                    <option value="">Vyberte krajinu</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->name }}" {{ old('country', isset($address) ? $address->country : '') == $country->name ? 'selected' : '' }}>
                                            {{ $country->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('country')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-check mb-4">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="is_default" 
                                       name="is_default" 
                                       value="1" 
                                       {{ old('is_default', isset($address) && $address->is_default ? 'checked' : '') }}>
                                <label class="form-check-label" for="is_default">
                                    Nastaviť ako predvolenú adresu
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">Zrušiť</a>
                        <button type="submit" class="btn profile-page-save-btn">
                            {{ isset($address) ? 'Aktualizovať adresu' : 'Pridať adresu' }}
                        </button>
                    </div>
                </form>
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
    .profile-page-save-btn {
        background-color: #aaa;
        color: white;
        border: none;
        padding: 10px 30px;
        border-radius: 0;
        font-weight: 500;
        transition: background-color 0.3s;
    }
    .profile-page-save-btn:hover {
        background-color: #999;
        color: white;
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
        .profile-page-save-btn {
            width: 100%;
        }
    }
</style>
@endsection