<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use Auth;
use MenuBuilder;
use Route;
use App\Models\Categories;
use App\Models\Products;
use App\Models\Customers;
use DB;
use Scaffolding;
use Form;
use Validator;

class DeliveryInfoController extends FrontEndController
{

    /**
     * Cart Page
     * 
     * @return Illuminate\View\View
     */
    public function index()
    {
        $parameters = $this->getParameters();
        $parameters['id_page'] = "delivery-info";
        return view('frontend.themes.ecommerce.delivery_info', $parameters);
    }

    /**
     * Register
     * 
     * @return Illuminate\View\View
     */
    public function login()
    {
//        dd(session('cart'), session('id_customer'));
//        dd(request('product'));
//        $delivery_info = request()->all();
//        dd($delivery_info);
        $rules = array(
            'email' => 'required|email',
            'password' => 'required',
        );
        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        // Authenticate
        $Model = new \App\Models\Customers;
        $record = $Model->where('email', '=', request('email'))->first();
        if (password_verify(request('password'), $record['password'])) {
            // Set session parameters
            $parameters = array(
                'id' => $record['id'],
                'name' => $record['name'],
                'email' => $record['email'],
                'phone' => $record['phone'],
                'address' => $record['address'],
            );
            session($parameters);
            // Redirect to customer dashboard
            return redirect(action(config('app.frontend_namespace') . 'CustomerController@index'));
        } else {
            return back()->with('login_failure', trans('form.login.failure'));
        }
//        $parameters = array(
//            'delivery_info' => array(
//                'name' => request('name'),
//                'phone' => request('phone'),
//                'address' => request('address'),
//            ),
//        );        
//        session($parameters);
//        return redirect(action(config('app.frontend_namespace') . 'PaymentController@index'));
    }

    /**
     * Register
     * 
     * @return Illuminate\View\View
     */
    public function register()
    {
        $rules = array(
            'name' => 'max:255|string|required',
            'email' => 'max:255|string|email|required',
            'password' => 'max:255|string|required',
            'password_confirm' => 'max:255|string|required|same:password',
            'phone' => 'max:255|string|required',
            'address' => 'required',
        );
        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        // Authenticate
        $Model = new \App\Models\Customers;
        $record = $Model->where('email', '=', request('email'))->first();
        if ($record) {
            return back()->with('register_message', 'Email sudah terdaftar.')->withInput();
        }
        // Insert record
        $Customers = new Customers;
        $parameters = request()->all();
        $parameters['password'] = password_hash($parameters['password'], PASSWORD_DEFAULT);
        $result = $Customers->create($parameters);
        return back()->with('register_message', 'Email sudah dikirim. Silakan dikonfirmasi.');
    }

    /**
     * Add comment response
     * 
     * @return Illuminate\View\View
     */
    public function insertModifyResponse($Response)
    {
        return back()->with('alert_success_customers', trans('main.alert.success.customers'));
    }

    /**
     * Modify properties of columns
     * 
     * @param  array $columns
     * 
     * @return  array
     */
    public function modifyColumnsProperties($columns)
    {
        $action = request()->action;
        $columns['password_confirm'] = array(
            'attributes' => array(
                'class' => 'form-control dk-character',
                'placeholder' => 'Password',
            ),
            'name' => 'password_confirm',
            'label' => trans('dkscaffolding.column.password_confirm'),
            'dataType' => 'VARCHAR',
            'length' => '255',
            'range' => NULL,
            'type' => 'password',
            'require' => true,
        );
        if ($action == "edit") {
            $columns['password']['require'] = false;
            $columns['password_confirm']['require'] = false;
        }
        return $columns;
    }

    /**
     * Modify form layout
     * 
     * @param  array $layout
     * 
     * @return  array
     */
    public function modifyLayout($layout)
    {
        $password_confirm = array(
            array(
                array(
                    'attributes' => array(
                        'class' => 'col-sm-12',
                    ),
                    'name' => 'password_confirm',
                ),
            )
        );
        array_splice($layout, 3, 0, $password_confirm);
        return $layout;
    }

    /**
     * Modify javascript validation rules
     * 
     * @param  array $rules
     * 
     * @return  array
     */
    public function modifyValidationRulesJS($rules)
    {
        $action = request()->action;
        if ($action == "edit") {
            unset($rules['password']['required']);
        }
        $rules['password_confirm']['equalTo'] = ":input[name=\"password\"]";
        return $rules;
    }

    /**
     * Modify validation
     * 
     * @param  array $rules
     * 
     * @return  array
     */
    public function modifyValidation($rules)
    {
        $httpVerb = request()->getMethod();
        $rules['password_confirm'] = 'max:255|string|required|same:password';
        if ($httpVerb == "PUT" || $httpVerb == "PATCH") {
            $rules['password'] = 'max:255|string|nullable';
            $rules['password_confirm'] = 'max:255|string|nullable|same:password';
        }
        return $rules;
    }

    /**
     * Set image column
     * 
     * @param array $parameters
     * 
     * @return array
     */
    public function setPassword($parameters)
    {
        $httpVerb = request()->getMethod();
        if (($httpVerb == "PUT" || $httpVerb == "PATCH") && !$parameters['password']) {
            unset($parameters['password']);
        }
        if (isset($parameters['password'])) {
            $parameters['password'] = password_hash($parameters['password'], PASSWORD_DEFAULT);
        }
        return $parameters;
    }

}
