<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

use DateTime;

class OrderController extends Controller
{
    /**
     * Para mostrar el formulario de creación de orden segun el id del producto seleccionado
     *
     * @param $idproduct
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($idproduct)
    {
        // Busco el producto
        $product = Product::find($idproduct);

        if (! $product) {
            return redirect()->route('products.list')->with('message', 'El producto seleccionado no existe.');
        }

        return view('orders.create', ["product" => $product]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'   => 'required',
            'email'  => 'required',
            'mobile' => 'required'
        ]);

        $data = $request->all();

        $order = new Order();
        $order->customer_name   = $data['name'];
        $order->customer_email  = $data['email'];
        $order->customer_mobile = $data['mobile'];
        $order->status          = 'CREATED';
        $order->product_id      = $data['idproduct'];
        $result = $order->save();

        if (! $result) {
            return back()->withInput();
        }

        // Redirigo al cliente al resumen de la orden
        return redirect()->route('order.show', ["idorder" => $order->id])->with('message', 'Resumen de su orden.');
    }

    /**
     * Mostrar los datos de la orden
     *
     * @param $id
     */
    public function show($id)
    {
        $order = Order::select('orders.id as id_order', 'orders.customer_name', 'orders.customer_email',
                'orders.customer_mobile', 'orders.status', 'orders.created_at as orders_created_at',
                'orders.updated_at as orders_updated_at', 'products.id as id_product', 'products.product_name',
                'products.product_price', 'products.product_info', 'products.product_state',
                'products.created_at as products_created_at', 'products.updated_at as products_updated_at'
                )
            ->join('products', 'products.id', '=', 'orders.product_id')
            ->where('orders.id', $id)
            ->first();

        // Valido si no se encuentra el la orden
        if (! $order ) {
            return redirect()->route('products.list');
        }

        $status_message = "Orden creada, pendiente de pago.";
        $alert_type  = "alert-warning";
        $band = false;

        if ($order->status == 'FAILED') {
            $status_message = "Hubo un error al consultar el estado. por favor intente nuevamente o consultelo más tarde";
            $alert_type  = "alert-danger";
        }

        if ($order->status == 'PAYED' || $order->status === 'APPROVED') {
            $status_message = "En hora buena, su orden está pagada, gracias por su compra.";
            $alert_type  = "alert-success";
            $band = true;
        }

        if ($order->status == 'REJECTED') {
            $status_message = "Su orden está rechazada.";
            $alert_type  = "alert-danger";
        }

        $result = [
            "order"          => $order,
            "status_message" => $status_message,
            "alert_type"     => $alert_type,
            "status"         => $band,
        ];

        return view('orders.resume', $result);
    }

    /**
     * Metodo para procesar el pago
     *
     * @param Request $request es necesario el request para tomar datos del Pc del cliente
     * @param $idorder es el id de la orden guardada en la base de datos
     *
     */
    public function pay(Request $request, $idorder)
    {
        $order = Order::find($idorder);

        if (! $idorder || ! $order) {
            return redirect()->route('products.list');
        }

        $servicio = config('app.API_REDIRECTION');

        $date = new DateTime();
        $seed = date_format($date->setTimezone(new \DateTimeZone('America/Bogota')), 'c');

        // Inicio Proceso para generar el Nonce
        if (function_exists('random_bytes')) {
            $nonce = bin2hex(random_bytes(16));
        } elseif (function_exists('openssl_random_pseudo_bytes')) {
            $nonce = bin2hex(openssl_random_pseudo_bytes(16));
        } else {
            $nonce = mt_rand();
        }

        $secretKey = '024h1IlD';
        $trankey = base64_encode(sha1($nonce . $seed . $secretKey, true));

        $auth = array(
            'login'   => config('app.API_LOGIN'),
            'tranKey' => $trankey,
            'nonce'   => base64_encode($nonce),
            'seed'    => $seed,
        );

        $locale = "es_CO";

        // Tomo los datos del cliente digitados en la web
        $buyer = [
            'name'   => $request->input('customer_name'),
            'email'  => $request->input('customer_email'),
            'mobile' => $request->input('customer_mobile'),
        ];

        // Tomo los valores del producto a comprar
        $subtotal = (double)$request->input('product_price');
        $valueAddedTax = $subtotal * 0.19;
        $shipping = 2;
        $tip = 0;
        $total = $valueAddedTax + $shipping + $tip + $subtotal;

        $payment = [
            'reference'  => $idorder,
            'amount' => [
                'taxes' => [
                    [
                        'kind'   => 'valueAddedTax',
                        'amount' => $valueAddedTax
                    ]
                ],
                'details' => [
                    [
                        'kind' => 'shipping',
                        'amount' => $shipping
                    ],
                    [
                        "kind" => 'tip',
                        "amount" => $tip
                    ], [
                        "kind" => 'subtotal',
                        "amount" => $subtotal
                    ]
                ],
                'currency' => 'USD',
                'total' => $total
            ],
            "items" => [
                [
                    "sku"      => 17080,
                    "name"     => "Producto X1",
                    "category" => "varios",
                    "qty"      => 1,
                    "price"    => $subtotal,
                    "tax"      => $valueAddedTax
                ]
            ], "allowPartial" => false
        ];

        // Fecha de expiracion + 1 hora
        $expiration = $date->add(new \DateInterval('PT' . 1 . 'H'));
        $expiration = date_format($expiration->setTimezone(new \DateTimeZone('America/Bogota')), 'c');

        // Tomo la IP publica
        $ipAddress = trim(shell_exec("dig +short myip.opendns.com @resolver1.opendns.com"));

        // Tomo la url de la aplicacion para que sea el atributo cancelUrl
        $localUrl  = env('APP_URL') ? env('APP_URL') : 'http://127.0.0.1:8000';
        $returnUrl = $returnUrl = $localUrl . '/response/' . $idorder;;
        $cancelUrl = $localUrl . '/order/status/' . $idorder;

        $arguments = [
            'locale'  => $locale,
            'buyer'   => $buyer,
            'instrument' => [
                'card' => [ 'number' => '4007000000027']
            ],
            'payment' => $payment,
            "expiration" => $expiration,
            "ipAddress"  => $ipAddress,
            "userAgent"  => $request->header('User-Agent'),
            "returnUrl"  => $returnUrl,
            "cancelUrl"  => $cancelUrl,
            "skipResult" => false,
            "noBuyerFill" => false,
            "captureAddress" => false,
            "paymentMethod" => null,
            "fields" => [
                [
                    "keyword" => "Redeem Code",
                    "value" => 591813,
                    "displayOn" => "payment"
                ]
            ],
            'auth' => $auth,
        ];

        $json_data = json_encode($arguments);
        $ch = curl_init($servicio);

        //attach encoded JSON string to the POST fields
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);

        //set the content type to application/json
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

        //return response instead of outputting
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //execute the POST request
        $result = curl_exec($ch);

        //close cURL resource
        curl_close($ch);

        // decodificar la respuesta para obtener el link del pago
        $arrayResult = json_decode($result,true);

        // valido la respuesta del Web Checkout
        if ($arrayResult['status']['status'] === "FAILED") {
            return back()->withInput();
        }

        if (isset($arrayResult['status']['status'])) {
            if ($arrayResult['status']['status'] === "OK") {

                $order->request_id  = $arrayResult['requestId'];
                $order->process_url = $arrayResult['processUrl'];
                $order->save();

                // Redirigo al usuario a la URL retornada
                return redirect()->away($arrayResult['processUrl']);
            }
        }

        // Si hay error con el API
        return redirect()->route('order.pay.status', ["id" => $idorder]);
    }

    /**
     * Método para verificar el pago
     *
     * @param $idorder
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function responsePay($idorder)
    {
        $order = Order::find($idorder);
        $requestId = $order->request_id;

        $servicio = config('app.API_REDIRECTION') . $requestId;

        $date = new DateTime();
        $seed = date_format($date->setTimezone(new \DateTimeZone('America/Bogota')), 'c');

        // Inicio Proceso para generar el Nonce
        if (function_exists('random_bytes')) {
            $nonce = bin2hex(random_bytes(16));
        } elseif (function_exists('openssl_random_pseudo_bytes')) {
            $nonce = bin2hex(openssl_random_pseudo_bytes(16));
        } else {
            $nonce = mt_rand();
        }

        $secretKey = '024h1IlD';
        $trankey = base64_encode(sha1($nonce . $seed . $secretKey, true));

        $auth = array(
            "internalReference" => $requestId,
            'login'   => config('app.API_LOGIN'),
            'tranKey' => $trankey,
            'nonce'   => base64_encode($nonce),
            'seed'    => $seed,
        );

        $arguments = [
            'auth' => $auth,
        ];

        $json_data = json_encode($arguments);
        $ch = curl_init($servicio);

        //attach encoded JSON string to the POST fields
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);

        //set the content type to application/json
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

        //return response instead of outputting
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //execute the POST request
        $result = curl_exec($ch);

        //close cURL resource
        curl_close($ch);

        // decodificar la respuesta para obtener el link del pago
        $arrayResult = json_decode($result,true);

        // Actualizo el estado la orden segun la respuesta
        $order->status = $arrayResult['status']['status'];
        $order->save();

        return redirect()->route('order.pay.status',
            [
                "idorder" => $idorder,
                "checked" => true
            ]);
    }

    /**
     * Método para consultar estado de una transacción
     *
     * @param $id de la orden generada
     * @param null $checked
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function statusOrder($id, $checked = null)
    {
        $order = Order::select('orders.id as id_order', 'orders.customer_name', 'orders.customer_email',
            'orders.customer_mobile', 'orders.status', 'orders.request_id', 'orders.created_at as orders_created_at',
            'orders.updated_at as orders_updated_at', 'products.id as id_product', 'products.product_name',
            'products.product_price', 'products.product_info', 'products.product_state',
            'products.created_at as products_created_at', 'products.updated_at as products_updated_at'
        )
            ->join('products', 'products.id', '=', 'orders.product_id')
            ->where('orders.id', $id)
            ->first();

        $requestId = $order->request_id;

        if (! $requestId) {
            if (auth()->user()) {
                return redirect()->route('home')->with('message', 'Error al consultar el estado de la orden.');
            }

            return redirect()->route('products.list')->with('message', 'Error al consultar el estado de la orden.');
        }

        // Consulto si la peticion viene de consultar el estado de la transacción
        if (! $checked) {

            // consulto el estado
            $responsePay = $this->checkStatusPayed($requestId);
            $status_api = $responsePay['status']['status'];


            // ACtualizo el registro en la base de datos si y solo si no es estado APPROVED
            if ($order->status != 'APPROVED') {

                $order_update = Order::find($id);
                $order_update->status = $status_api;
                $order_update->save();
            }

            $status_message = "Orden creada, está pendiente de pago.";
            $alert_type  = "alert-warning";
            $status = "CREADA";

            if ($order->status == 'FAILED') {
                $status_message = "Por favor intente nuevamente o consultelo más tarde";
                $alert_type  = "alert-danger";
                $status = "FALLIDA";
            }

            if ($order->status == 'PAYED' || $order->status === 'APPROVED') {
                $status_message = "En hora buena, su orden está pagada, gracias por su compra.";
                $alert_type  = "alert-success";
                $status = "APROBADA";
            }

            if ($order->status == 'REJECTED') {
                $status_message = "Error, intente de nuevo realizar el pago.";
                $alert_type  = "alert-danger";
                $status = "RECHAZADA";
            }

            return view('orders.status')
                ->with(['order'   => $order])
                ->with(['status'  => $status])
                ->with(['message' => $status_message])
                ->with(['alert_type' => $alert_type]);
        }

        $status_message = "Orden creada, pendiente de pago.";
        $alert_type  = "alert-warning";
        $status = "CREADA";

        if ($order->status == 'FAILED') {
            $status_message = "Por favor intente nuevamente o consultelo más tarde";
            $alert_type  = "alert-danger";
            $status = "FALLIDA";
        }

        if ($order->status == 'PAYED' || $order->status === 'APPROVED') {
            $status_message = "En hora buena, gracias por su compra.";
            $alert_type  = "alert-success";
            $status = "APROBADA";
        }

        if ($order->status == 'REJECTED') {
            $status_message = "Reintentar el pago.";
            $alert_type  = "alert-danger";
            $status = "RECHAZADA";
        }

        return view('orders.status')
            ->with(['order'   => $order])
            ->with(['status'  => $status])
            ->with(['message' => $status_message])
            ->with(['alert_type' => $alert_type]);
    }

    public function checkStatusPayed($requestId)
    {
        $servicio = config('app.API_REDIRECTION') . $requestId;

        $date = new DateTime();
        $seed = date_format($date->setTimezone(new \DateTimeZone('America/Bogota')), 'c');

        // Inicio Proceso para generar el Nonce
        if (function_exists('random_bytes')) {
            $nonce = bin2hex(random_bytes(16));
        } elseif (function_exists('openssl_random_pseudo_bytes')) {
            $nonce = bin2hex(openssl_random_pseudo_bytes(16));
        } else {
            $nonce = mt_rand();
        }

        $secretKey = '024h1IlD';
        $trankey = base64_encode(sha1($nonce . $seed . $secretKey, true));

        $auth = array(
            "internalReference" => $requestId,
            'login'   => config('app.API_LOGIN'),
            'tranKey' => $trankey,
            'nonce'   => base64_encode($nonce),
            'seed'    => $seed,
        );

        $arguments = [
            'auth' => $auth,
        ];

        $json_data = json_encode($arguments);
        $ch = curl_init($servicio);

        //attach encoded JSON string to the POST fields
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);

        //set the content type to application/json
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

        //return response instead of outputting
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //execute the POST request
        $result = curl_exec($ch);

        //close cURL resource
        curl_close($ch);

        // decodificar la respuesta para obtener el link del pago
        $arrayResult = json_decode($result,true);

        return $arrayResult;
    }
}
