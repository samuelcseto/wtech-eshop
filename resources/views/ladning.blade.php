@extends('layouts.app')

@section('title', 'Domov')

@section('content')
    <section class="bg-secondary text-white text-start py-5 px-4">
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
                @foreach ([
                    ['title' => 'Stolička klasická', 'price' => '39 €'],
                    ['title' => 'Svetlo klasické', 'price' => '109 €'],
                    ['title' => 'Taniere klasické', 'price' => '7,99 €'],
                ] as $product)
                    <div class="col-md-4">
                        <div class="card text-center h-100">
                            <img src="https://via.placeholder.com/300x180" class="card-img-top" alt="product">
                            <div class="card-body">
                                <h5 class="card-title">{{ $product['title'] }}</h5>
                                <p class="card-text">Najlepšie materiály</p>
                                <strong class="text-dark">{{ $product['price'] }}</strong>
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
                @foreach (['Spálňa', 'Kuchyňa', 'Kancelária', 'Detská'] as $category)
                    <div class="col-md-3">
                        <div class="card text-center">
                            <img src="https://via.placeholder.com/300x180" class="card-img-top" alt="category">
                            <div class="card-body">
                                <p class="card-text">{{ $category }}</p>
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
