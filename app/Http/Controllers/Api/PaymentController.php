<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Scaffolding;
use Form;
use Html;
use DB;
use Validator;
use Illuminate\Http\JsonResponse;
use App\Models\Categories;
use App\Models\Products;
use App\Models\Orders;
use App\Models\OrderDetail;
use App\Models\OrderProducts;

class PaymentController extends ApiController
{

    protected $table = 'products';

    /**
     * Process quantity of products
     * 
     * @return Illuminate\View\View
     */
    public function save()
    {
        $jwt_parameters = jwt_parameters();
        // Authenticate
        if (!$jwt_parameters->id) {
            $error_messages = $validator->errors()->getMessages();
            $response = array(
                'content' => 'Silakan login terlebih dahulu.',
                'type' => 'error',
            );
            $JsonResponse = new JsonResponse($response, 200);
            $JsonResponse->send();
            exit;
        }
        $result = DB::transaction(function ($db) use ($jwt_parameters) {
                    $cart = request('product');
                    // Get product ids
                    $ids_product = array();
                    foreach (request('product') as $id_product => $product) {
                        $ids_product[] = $id_product;
                    }
                    // Get product
                    $columns = array(
                        'products.*',
                        'categories.name AS category_name'
                    );
                    $Model = new Products;
                    $Model = $Model->join('categories', 'categories.id', '=', 'products.id_category', 'INNER')
                            ->whereIn('products.id', $ids_product);
                    $products = $Model->select($columns)->get();
                    $total = 0;
                    foreach ($products as $product) {
                        $id_product = $product['id'];
                        $qty = $cart[$id_product]['qty'];
                        $subtotal = $product['price'] * $qty;
                        $total += $subtotal;
                    }
                    $delivery_fee = 10000;
                    $total_delivery = $total + $delivery_fee;
                    $ppn = $total_delivery * 10 / 100;
                    $grand_total = $total_delivery + $ppn;
                    // Insert order
                    $parameters = array(
                        'id_customer' => $jwt_parameters->id,
                        'subtotal' => $total,
                        'delivery_fee' => $total_delivery,
                        'ppn' => $ppn,
                        'total' => $grand_total,
                        'shipping_address' => request('shipping_address'),
                    );
                    $Orders = new Orders;
                    $Orders = $Orders->create($parameters);
                    // Insert order products
                    foreach ($cart as $id_product => $product) {
                        $parameters = array(
                            'id_order' => $Orders->id,
                            'id_product' => $product['id'],
                            'qty' => $product['qty'],
                        );
                        $OrderProducts = new OrderProducts;
                        $OrderProducts = $OrderProducts->create($parameters);
                    }
                    return $Orders;
                });
        // Get order
        $columns = array(
            'orders.*',
            'customers.name',
            'customers.phone',
        );
        $Model = new Orders;
        $Model = $Model->join('customers', 'customers.id', '=', 'orders.id_customer', 'INNER')
                ->where('orders.id', $result->id);
        $order = $Model->select($columns)->get()->first();
        // Get products
        $columns = array(
            'order_products.*',
            'products.*',
        );
        $Model = new OrderProducts();
        $Model = $Model->join('products', 'products.id', '=', 'order_products.id_product', 'INNER')
                ->where('order_products.id_order', $result->id);
        $products = $Model->select($columns)->get();
        $parameters = $this->getParameters();
        $parameters['order'] = $order;
        $parameters['products'] = $products;
        $response = array(
            'content' => array(
                'order' => $order,
                'products' => $products,
            ),
            'type' => 'success',
        );
        $JsonResponse = new JsonResponse($response, 200);
        $JsonResponse->send();
        exit;
//        return redirect(action(config('app.frontend_namespace') . 'OrderController@index', array('id_order' => $result->id)));
    }

}
