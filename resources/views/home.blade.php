@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-center">Listado de ventas</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form action="" method="get">
                        <div class="form-group row">

                            <div class="col-lg-6 pull-right">
                                <label for="search" class="font-weight-bold">Buscar por comprador:</label>
                                <div class="input-group">

                                    <input type="text" class="form-control" value="{{ $search }}" name="search" placeholder="Buscar comprador" id="search"/>
                                    <div class="input-group-btn">
                                        <button class="btn btn-default" type="submit">
                                            <i class="fas fa-search text-primary fa-lg"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Email</th>
                                <th scope="col">Celular</th>
                                <th scope="col">Producto</th>
                                <th scope="col" class="text-center">Request ID</th>
                                <th scope="col">Estado</th>
                                <th scope="col">Fecha creación</th>
                                <th scope="col" class="text-center">Ver</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($orders as $order )
                                <tr class="item-row{{ $order->id_order }}">
                                    <th scope="row">{{ $order->id_order }}</th>
                                    <td>{{ $order->customer_name }}</td>
                                    <td>{{ $order->customer_email }}</td>
                                    <td>{{ $order->customer_mobile }}</td>

                                    <td>{{ $order->product_name }}</td>
                                    <td class="text-center">{{ $order->request_id }}</td>
                                    <td >{{ $order->status }}</td>
                                    <td >{{ $order->orders_created_at }}</td>

                                    <td class="text-center">
                                        <a href="{{route('order.pay.status', ['idorder' => $order->id_order])}}" class="table-link">
                                            <span class="fa-stack">
                                                <i class="fa fa-square fa-stack-2x"></i>
                                                <i class="fas fa-eye fa-stack-1x fa-inverse"></i>
                                            </span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination">
                        {{ $orders->links() }}
                    </div>
                </div>

                <div class="card-footer text-muted">
                    {{ $total }} registros consultados
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
