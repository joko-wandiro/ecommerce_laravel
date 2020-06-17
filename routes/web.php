<?php

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */
// Route Authentication
Route::group(['prefix' => 'backend', 'namespace' => 'BackEnd'], function () {
    // Auth Routes
    Route::get('login', array('as' => 'backend.prelogin',
        'uses' => 'AuthController@displayLoginForm'));
    Route::post('login', array('as' => 'backend.login',
        'uses' => 'AuthController@login'));
    Route::get('logout', array('as' => 'backend.logout',
        'uses' => 'AuthController@logout'));
    Route::any('/', array('as' => 'dashboard', 'uses' => 'DashboardController@index'));
    Route::any('categories', array('as' => 'categories.index', 'uses' => 'CategoriesController@index'));
    Route::any('products', array('as' => 'products.index', 'uses' => 'ProductsController@index'));
    Route::get('pos', array('as' => 'orders.create', 'uses' => 'PosController@create'));
    Route::post('pos', array('as' => 'orders.store', 'uses' => 'PosController@store'));
    Route::get('pos/{id_order}', array('as' => 'orders.show', 'uses' => 'PosController@show'));
    Route::get('pos/{id_order}/edit', array('as' => 'orders.edit', 'uses' => 'PosController@edit'));
    Route::put('pos/{id_order}', array('as' => 'orders.update', 'uses' => 'PosController@update'));
    Route::any('orders', array('as' => 'orders.index', 'uses' => 'OrdersController@index'));
    Route::get('settings', array('as' => 'settings.edit', 'uses' => 'SettingsController@edit'));
    Route::put('settings', array('as' => 'settings.update', 'uses' => 'SettingsController@update'));
    Route::get('reports/daily/{year}/{month}', array('as' => 'orders.show', 'uses' => 'ReportsController@daily'));
    Route::get('reports/monthly/{year}', array('as' => 'orders.show', 'uses' => 'ReportsController@monthly'));
    Route::any('users', array('as' => 'users.index', 'uses' => 'UsersController@index'));
    Route::any('customers', array('as' => 'customers.index', 'uses' => 'CustomersController@index'));
});
Route::group(['namespace' => 'FrontEnd'], function () {
    Route::get('/', array('as' => 'homepage', 'uses' => 'HomePageController@index'));
    Route::get('category/{category}', array('as' => 'category', 'uses' => 'CategoryController@index'));
    Route::get('product/{product}', array('as' => 'product', 'uses' => 'ProductController@index'));
    Route::post('product/add', array('as' => 'product', 'uses' => 'ProductController@add'));
    Route::get('cart', array('as' => 'cart.index', 'uses' => 'CartController@index'));
    Route::post('cart/add', array('as' => 'cart.add', 'uses' => 'CartController@add'));
    Route::get('delivery-info', array('as' => 'deliveryinfo.index', 'uses' => 'DeliveryInfoController@index'));
    Route::get('register', array('as' => 'account.register', 'uses' => 'CustomerController@register'));
    Route::post('register', array('as' => 'account.register', 'uses' => 'CustomerController@do_register'));
    Route::get('confirm/{token}', array('as' => 'account.confirm', 'uses' => 'CustomerController@confirm'));
    Route::get('login', array('as' => 'account.login', 'uses' => 'CustomerController@login'));
    Route::post('login', array('as' => 'account.login', 'uses' => 'CustomerController@do_login'));
    Route::get('forgot-password', array('as' => 'account.forgot_password', 'uses' => 'CustomerController@forgot_password'));
    Route::post('forgot-password', array('as' => 'account.process_forgot_password', 'uses' => 'CustomerController@process_forgot_password'));
    Route::get('change-password/{token}', array('as' => 'account.change_password', 'uses' => 'CustomerController@change_password'));
    Route::post('change-password/{token}', array('as' => 'account.change_password', 'uses' => 'CustomerController@process_change_password'));
    Route::get('account', array('as' => 'account.dashboard', 'uses' => 'CustomerController@index'));
    Route::get('account/order', array('as' => 'account.order', 'uses' => 'CustomerController@order'));
    Route::get('account/profile', array('as' => 'account.profile', 'uses' => 'CustomerController@profile'));
    Route::put('account/profile', array('as' => 'account.profile', 'uses' => 'CustomerController@profile'));
    Route::get('account/logout', array('as' => 'account.logout', 'uses' => 'CustomerController@logout'));
    Route::get('payment', array('as' => 'payment.index', 'uses' => 'PaymentController@index'));
    Route::post('payment/save', array('as' => 'payment.save', 'uses' => 'PaymentController@save'));
    Route::get('order/{id_order}', array('as' => 'order.index', 'uses' => 'OrderController@index'));
});
Route::group(['prefix' => 'api', 'namespace' => 'Api'], function () {
    Route::get('categories', array('as' => 'categories.index', 'uses' => 'CategoriesController@index'));
    Route::get('category/{category}', array('as' => 'products.index', 'uses' => 'ProductsController@products_category'));
    Route::get('product/{product}', array('as' => 'products.view', 'uses' => 'ProductsController@product'));
    Route::post('login', array('as' => 'account.login', 'uses' => 'CustomerController@do_login'));
    Route::post('register', array('as' => 'account.register', 'uses' => 'CustomerController@do_register'));
    Route::post('forgot-password', array('as' => 'account.forgot_password', 'uses' => 'CustomerController@do_forgot_password'));
    Route::get('account/order', array('as' => 'account.order', 'uses' => 'CustomerController@order'))->middleware('api.auth');
    Route::put('account/profile', array('as' => 'account.profile', 'uses' => 'CustomerController@profile'))->middleware('api.auth');
    Route::post('cart/add', array('as' => 'cart.add', 'uses' => 'CartController@add'));
    Route::post('payment/save', array('as' => 'payment.save', 'uses' => 'PaymentController@save'));
    Route::get('order/{id_order}', array('as' => 'order.index', 'uses' => 'OrderController@index'));
});
