@extends('layouts.app')

@section('title', 'Domov')

@section('content')
    <section class="bg-secondary text-white text-start py-5 px-4"
            style="background-image: url('{{ asset('images/banners/landing-banner.jpg') }}'); background-size: cover; background-position: center;">
        <div class="container">
            <h1>Lorem ipsum dolor</h1>
            <p>Lorem ipsum dolor</p>
            <a href="#" class="btn btn-light">Pozrieť si</a>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <h2 class="mb-4">Classics</h2>
            <div class="row g-4">
                @foreach ($products as $product)
                    <div class="col-md-4">
                        <div class="card text-center h-100">
                        <img 
                            src="{{ 
                                    $product->primaryImage?->image_url
                                    ? asset($product->primaryImage->image_url)
                                    : 'https://via.placeholder.com/300x180' 
                                }}"
                                class="card-img-top"
                                alt="product"
                                
                            >
                            <div class="card-body">
                                <h5 class="card-title">{{ $product->name }}</h5>
                                <p class="card-text">{{ $product->description }}</p>
                                <strong class="text-dark">{{ $product->price }}€</strong>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="bg-light py-5">
        <div class="container">
            <h2 class="mb-4">Podľa kategórie</h2>
            <div class="row g-4">
                @foreach ($categories as $category)
                    <div class="col-md-3">
                        <div class="card text-center">
                            <img 
                                src="{{ 
                                    $category->image_url
                                    ? asset($category->image_url)
                                    : 'https://via.placeholder.com/300x180' 
                                }}"
                                class="card-img-top" 
                                alt="{{$category->name}}"
                            >
                            <div class="card-body">
                                <p class="card-text">{{ $category->name }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <h2 class="mb-4">Podľa štýlu</h2>
            <div class="row g-4">
                @foreach (['Klasický', 'Moderný', 'Rustikálny', 'Industriálny'] as $style)
                    <div class="col-md-3">
                        <div class="card text-center">
                            <img src="https://via.placeholder.com/300x180" class="card-img-top" alt="style">
                            <div class="card-body">
                                <p class="card-text">{{ $style }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
