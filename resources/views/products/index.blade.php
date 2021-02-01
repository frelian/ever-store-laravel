<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Productos | EverStore</title>

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('css/styles.css') }}" rel="stylesheet">

    </head>
    <body>
        <nav class="nav nav-pills justify-content-end ml-auto">
            @if (Route::has('login'))
                @auth
                    <a class="nav-link" href="{{ url('/home') }}">Home</a>

                    @if (Route::has('register'))
                        <a class="nav-link" href="{{ route('register') }}">Register</a>
                    @endif
                @else
                    <a class="nav-link font-size-20x" href="{{ route('login') }}">Login Admin</a>
                @endauth
            @endif

        </nav>

        <div class="row area-center">
            <div class="col-lg-12">
                <div class="container">
                    <h1 class="text-center">{{ config('app.name', 'EverStore') }}</h1>
                </div>
            </div>
        </div>

        <div class="row fix-row">
            <div class="container">
                <div class="list-group">

                    <li class="list-group-item active">
                        <h4>Listado de productos</h4>
                    </li>
                    @foreach($products as $product)
                        <a href="{{ route('order.create', ['idproduct' => $product->id]) }}" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">{{ $product->product_name }}</h5>
                                <span class="badge badge-primary badge-pill">$ {{ $product->product_price }}</span>
                            </div>
                            <p class="mb-1">{{ $product->product_info }}</p>
                        </a>
                    @endforeach

                </div>
            </div>
        </div>

        <div class="row area-center">
            <div class="col-lg-12">
                <div class="container">
                    <div class="pagination text-center">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
