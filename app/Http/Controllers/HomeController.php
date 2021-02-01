<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {

        $search = $request->get('search');

        if($search){
            $orders = Order::select('orders.id as id_order', 'orders.customer_name', 'orders.customer_email',
                    'orders.customer_mobile', 'orders.status', 'orders.request_id', 'orders.created_at as orders_created_at',
                    'orders.updated_at as orders_updated_at', 'products.id as id_product', 'products.product_name',
                    'products.product_price', 'products.product_info', 'products.product_state',
                    'products.created_at as products_created_at', 'products.updated_at as products_updated_at'
                )
                ->join('products', 'products.id', '=', 'orders.product_id')
                ->where('orders.customer_name', 'like', "%" . $search . "%")
                ->paginate(15);

            $total = $orders->total();

            return view('home', [
                    "orders" => $orders,
                    "search" => $search,
                    "total"  => $total
                ]
            );
        }

        $orders = Order::select('orders.id as id_order', 'orders.customer_name', 'orders.customer_email',
                'orders.customer_mobile', 'orders.status', 'orders.request_id', 'orders.created_at as orders_created_at',
                'orders.updated_at as orders_updated_at', 'products.id as id_product', 'products.product_name',
                'products.product_price', 'products.product_info', 'products.product_state',
                'products.created_at as products_created_at', 'products.updated_at as products_updated_at'
            )
            ->join('products', 'products.id', '=', 'orders.product_id')
            ->paginate(15);

        $total = $orders->total();

        return view('home', [
                "orders" => $orders,
                "search" => $search,
                "total"  => $total
            ]
        );
    }
}
