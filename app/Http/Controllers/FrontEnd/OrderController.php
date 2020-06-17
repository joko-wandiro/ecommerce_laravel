<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use Auth;
use MenuBuilder;
use Route;
use App\Models\Categories;
use App\Models\Products;
use DB;
use Scaffolding;
use Form;
use Validator;
use App\Models\Orders;
use App\Models\OrderDetail;
use App\Models\OrderProducts;

class OrderController extends FrontEndController
{

    /**
     * Cart Page
     * 
     * @return Illuminate\View\View
     */
    public function index($id_order)
    {
        session()->flush();
        // Get order
        $columns = array(
            'orders.*',
            'customers.name',
            'customers.phone',
        );
        $Model = new Orders;
        $Model = $Model->join('customers', 'customers.id', '=', 'orders.id_customer', 'INNER')
                ->where('orders.id', $id_order);
        $order = $Model->select($columns)->get()->first();
        // Get products
        $columns = array(
            'order_products.*',
            'products.*',
        );
        $Model = new OrderProducts();
        $Model = $Model->join('products', 'products.id', '=', 'order_products.id_product', 'INNER')
                ->where('order_products.id_order', $id_order);
        $products = $Model->select($columns)->get();
//        dd($products);
        $parameters = $this->getParameters();
        $parameters['id_page'] = "order";
        $parameters['order'] = $order;
        $parameters['products'] = $products;
        return view('frontend.themes.ecommerce.order_success', $parameters)->render();
    }

}
