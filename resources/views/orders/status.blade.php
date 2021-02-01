@extends('layouts.app')
@section('content')
    <div class="row area-center">
        <div class="col-lg-12">
            <div class="container">
                <h2 class="text-center text-bold">Resumen de compra</h2>
            </div>
        </div>
    </div>
    <br>
    <div class="row fix-row">
        <div class="container">

            @if(isset($status))
                <div class="container">
                    <div class="row justify-content-md-center">

                        <div class="col col-lg-4 alert {{ $alert_type }}">
                            <h5>Estado de la compra</h5>
                            <h1>{{ $status }}</h1>
                        </div>

                        <div class="col col-lg-4">

                            <form action="{{route('order.pay', ['idorder' => $order->id_order])}}" method="POST">
                                @csrf @method("post")

                                <input type="hidden" name="id" value="{{ $order->id }}">
                                <input type="hidden" name="customer_name" value="{{ $order->customer_name }}">
                                <input type="hidden" name="customer_email" value="{{ $order->customer_email }}">
                                <input type="hidden" name="customer_mobile" value="{{ $order->customer_mobile }}">
                                <input type="hidden" name="product_price" value="{{ $order->product_price }}">

                                <div class="col-md-auto text-center">
                                    <h3>{{ $message }}</h3>

                                    @if(!Auth::check())
                                        @if($status == 'APROBADA')
                                            <i class="fas fa-check fa-2x"></i>
                                        @endif

                                        @if($status != 'APROBADA')
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-cash-register"></i> Realizar el pago
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            @else
                <div class="container">
                    <div class="row justify-content-md-center">
                        <div class="col-md-auto">
                            <a href="{{ route('products.list')}}" class="btn btn-light">
                                <i class="fa fa-fw fa-reply-all"></i>
                            </a>
                        </div>
                        </form>
                    </div>
                </div>
            @endif

            <br>
            <div class="row">
                <div class="col-lg-6 text-center card">
                    <div class="container py-3 mb-3">
                        <h3>Producto a comprar</h3>
                        <hr class="info-product">
                        <div class="row info-product ">
                            <div class="col">
                                <h4 class="font-weight-bold text-center">{{ $order->product_name }}</h4>
                            </div>
                        </div>

                        @if (! empty($order->product_info))
                            <div class="row info-product ">
                                <div class="col">
                                    <p>{{ $order->product_info }}</p>
                                </div>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-12">
                                <button type="button" class="btn btn-outline-primary btn-price-focus">
                                    Precio <strong> $ {{ $order->product_price }}</strong>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 text-center card">
                    <div class="container py-3 mb-3">
                        <h3>Sus datos</h3>
                        <hr class="info-product">
                        <div class="row info-product ">
                            <div class="col">
                                <h4 class="font-weight-bold text-center">{{ $order->customer_name }}</h4>
                            </div>
                        </div>

                        <div class="row info-product ">
                            <div class="col">
                                <h4 class="font-weight-bold text-center">{{ $order->customer_email }}</h4>
                            </div>
                        </div>

                        <div class="row info-product ">
                            <div class="col">
                                <h4 class="font-weight-bold text-center">{{ $order->customer_mobile }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-header">
                <div class="card-tools">
                    @if(Auth::check())
                        <a href="{{ route('home')}}" class="btn btn-block btn-outline-info btn-sm">
                            <i class="fa fa-fw fa-reply-all"></i> Volver al listado de productos
                        </a>
                    @else
                        <a href="{{ route('products.list')}}" class="btn btn-block btn-outline-info btn-sm">
                            <i class="fa fa-fw fa-reply-all"></i> Volver al listado de productos
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
