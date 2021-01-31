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

        <!-- Fonts -->

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
                    <a class="nav-link" href="{{ route('login') }}">Login Admin</a>
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



        <div class="row">
            <div class="container">

                <div class="alert alert-primary" role="alert">
                    Por favor diligencie el siguiente formulario y verifique el producto a comprar.
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="card">
                            <h5 class="card-header">Formulario</h5>
                            <div class="card-body">
                                <h5 class="card-title">* Todos lo campos son obligatorios</h5>
                                <form>
                                    <div class="mb-3">
                                        <label for="nameInput" class="form-label">Nombres:</label>
                                        <input type="text" class="form-control" id="nameInput" aria-describedby="nameHelp">
                                        <div id="nameHelp" class="form-text"></div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="emailInput" class="form-label">Email:</label>
                                        <input type="email" class="form-control" id="emailInput" aria-describedby="emailHelp">
                                        <div id="emailHelp" class="form-text"></div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="nameMobile" class="form-label">Número de celular:</label>
                                        <input type="text" class="form-control" id="nameMobile" aria-describedby="nameMobile">
                                        <div id="nameMobile" class="form-text"></div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Proceder con el pago</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <h3>Producto a comprar:</h3>
                        <hr>
                        <div class="row">
                            <div class="col">
                                <h4>Nombre producto</h4>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <p>info: ---- isset o empty si esta vacio que no muestre ese DIV</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <h4>Precio</h4>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>

        <div class="row area-center">
            <div class="col-lg-12">
                <div class="container">
                    <div class="pagination text-center">

                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
