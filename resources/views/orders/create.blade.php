@extends('layouts.app')
@section('content')
    <div class="row area-center">
        <div class="col-lg-12">
            <div class="container">
                <h1 class="text-center">{{ config('app.name', 'EverStore') }}</h1>
            </div>
        </div>
    </div>
    <div class="row fix-row">
        <div class="container">

            <div class="alert alert-primary" role="alert">
                Por favor diligencie el siguiente formulario y verifique el producto a comprar.
            </div>

            <div class="card-header with-border">
                <div class="card-tools">
                    <a href="{{ route('products.list')}}" class="btn btn-block btn-outline-info btn-sm">
                        <i class="fa fa-fw fa-reply-all"></i> Volver al listado de productos
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <h5 class="card-header">Orden de producto</h5>
                        <div class="card-body">
                            <h5 class="card-title">* Todos lo campos son obligatorios</h5>
                            <form action="{{route('order.store')}}" method="POST">
                                @csrf @method("post")

                                <input type="hidden" id="idproduct" name="idproduct" value="{{ $product->id }}">
                                <input type="hidden" id="product_name" name="product_name" value="{{ $product->product_name }}">

                                <div class="mb-3">
                                    <label for="name" class="form-label">Nombres:</label>
                                    <input type="text" class="form-control" id="name" name="name" aria-describedby="nameHelp">
                                    <div id="nameHelp" class="form-text"></div>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email:</label>
                                    <input type="email" class="form-control" id="email"  name="email" aria-describedby="emailHelp">
                                    <div id="emailHelp" class="form-text"></div>
                                </div>
                                <div class="mb-3">
                                    <label for="mobile" class="form-label">Número de celular:</label>
                                    <input type="text" class="form-control" id="mobile" name="mobile" aria-describedby="mobileHelp">
                                    <div id="mobileHelp" class="form-text"></div>
                                </div>
                                <button type="submit" class="btn btn-success">Proceder con el pago</button>

                                <a href="{{ route('products.list')}}" class="btn btn-light">
                                    <i class="fa fa-fw fa-reply-all"></i> Cancelar
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <h3>Producto a comprar:</h3>
                    <hr class="info-product">
                    <div class="row info-product ">
                        <div class="col">
                            <h4 class="font-weight-bold text-center">{{ $product->product_name }}</h4>
                        </div>
                    </div>

                    @if (! empty($product->product_info))
                        <div class="row info-product ">
                            <div class="col">
                                <p>{{ $product->product_info }}</p>
                            </div>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-12">
                            <button type="button" class="btn btn-outline-primary btn-price-focus">
                                Precio <strong> $ {{ $product->product_price }}</strong>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
