@extends('frontend.themes.ecommerce.default')

@section('content')
<div id="content">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <?php
                $url_cart = action(config('app.frontend_namespace') . 'CartController@index');
                $url_delivery_info = action(config('app.frontend_namespace') . 'DeliveryInfoController@index');
                $url_payment = action(config('app.frontend_namespace') . 'PaymentController@index');
                ?>
                <ul id="order-steps" class="xrow">
                    <li class="com-4 active"><a href="<?php echo $url_cart; ?>">cart</a></li>
                    <li class="com-4 active"><a href="<?php echo $url_delivery_info; ?>">delivery info</a></li>
                    <li class="com-4"><a href="#">payment</a></li>
                </ul>
                <div id="delivery-info-detail">
                    <div class="row">
                        <div class="col-12">
                            <h1>Login</h1>
                            <?php
                            $errors = request()->session()->get('errors');
                            if ($errors) {
                                $errorMessages = $errors->getMessages();
                            }
                            $labelError = '<label class="error">%1$s</label>';
                            ?>
                            <?php
//                            $inputAttributes = array('class' => 'form-control');
//                            $errorMessages = $errors->getMessages();
//                            $labelError = '<label class="error">%1$s</label>';
                            if (Session::has('login_failure')) {
                                ?>
                                <p class="alert alert-danger"><?php echo e(session('login_failure')); ?></p>
                                <?php
                            }
                            ?>
                            <?php
                            $url = action(config('app.frontend_namespace') . 'DeliveryInfoController@login');
                            $url_forgot_password = action(config('app.frontend_namespace') . 'CustomerController@forgot_password');
                            echo Form::open(['method' => 'POST', 'url' => $url, 'id' => "form-delivery-info"]);
                            $login_email = "";
                            if (isset($login['login_email'])) {
                                $login_email = $login['login_email'];
                            }
                            ?>
                            <div class="form-group">
                                <input type="text" name="login_email" class="form-control" placeholder="Email" value="<?php echo $login_email; ?>" />
                            </div>
                            <?php
                            // Display errors
                            if (isset($errorMessages['login_email'])) {
                                foreach ($errorMessages['login_email'] as $errorMessage) {
                                    echo sprintf($labelError, e($errorMessage));
                                }
                            }
                            ?>
                            <?php
                            $login_password = "";
                            if (isset($login['login_password'])) {
                                $login_password = $login['login_password'];
                            }
                            ?>
                            <div class="form-group">
                                <input type="password" name="login_password" class="form-control" placeholder="Password" value="<?php echo $login_password; ?>" />
                            </div>
                            <?php
                            // Display errors
                            if (isset($errorMessages['login_password'])) {
                                foreach ($errorMessages['login_password'] as $errorMessage) {
                                    echo sprintf($labelError, e($errorMessage));
                                }
                            }
                            ?>
                            <input type="submit" name="submit" value="submit" class="btn">
                            <?php
                            echo Form::close();
                            ?>
                            <div><a href="<?php echo $url_forgot_password; ?>">Forgot Password</a></div>
                            <h1>Register</h1>
                            <?php #echo $customer_form; ?>
                            <?php
                            $errors = request()->session()->get('errors');
                            if ($errors) {
                                $errorMessages = $errors->getMessages();
                            }
                            ?>
                            <?php
//                            $inputAttributes = array('class' => 'form-control');
//                            $errorMessages = $errors->getMessages();
//                            $labelError = '<label class="error">%1$s</label>';
                            if (Session::has('register_message')) {
                                ?>
                                <p class="alert alert-danger"><?php echo e(session('register_message')); ?></p>
                                <?php
                            }
                            ?>                            
                            <?php
                            $url = action(config('app.frontend_namespace') . 'DeliveryInfoController@register');
                            echo Form::open(['method' => 'POST', 'url' => $url, 'id' => "form-delivery-info"]);
//                            $name = "";
//                            if (isset($delivery_info['name'])) {
//                                $name = $delivery_info['name'];
//                            }
                            ?>
                            <div class="form-group">
                                <input type="text" name="name" class="form-control" placeholder="Full Name" value="<?php echo old('name'); ?>" />
                            </div>
                            <?php
                            $labelError = '<label class="error">%1$s</label>';
                            // Display errors
                            if (isset($errorMessages['name'])) {
                                foreach ($errorMessages['name'] as $errorMessage) {
                                    echo sprintf($labelError, e($errorMessage));
                                }
                            }
                            ?>
                            <?php
                            $email = "";
                            if (isset($delivery_info['email'])) {
                                $email = $delivery_info['email'];
                            }
                            ?>
                            <div class="form-group">
                                <input type="text" name="email" class="form-control" placeholder="Email" value="<?php echo old('email'); ?>" />
                            </div>
                            <?php
                            $labelError = '<label class="error">%1$s</label>';
                            // Display errors
                            if (isset($errorMessages['email'])) {
                                foreach ($errorMessages['email'] as $errorMessage) {
                                    echo sprintf($labelError, e($errorMessage));
                                }
                            }
                            ?>                            
                            <?php
                            $password = "";
                            if (isset($delivery_info['password'])) {
                                $password = $delivery_info['password'];
                            }
                            ?>
                            <div class="form-group">
                                <input type="password" name="password" class="form-control" placeholder="Password" value="" />
                            </div>
                            <?php
                            $labelError = '<label class="error">%1$s</label>';
                            // Display errors
                            if (isset($errorMessages['password'])) {
                                foreach ($errorMessages['password'] as $errorMessage) {
                                    echo sprintf($labelError, e($errorMessage));
                                }
                            }
                            ?>
                            <?php
                            $password_confirm = "";
                            if (isset($delivery_info['password_confirm'])) {
                                $password_confirm = $delivery_info['password_confirm'];
                            }
                            ?>
                            <div class="form-group">
                                <input type="password" name="password_confirm" class="form-control" placeholder="Password Confirm" value="" />
                            </div>
                            <?php
                            $labelError = '<label class="error">%1$s</label>';
                            // Display errors
                            if (isset($errorMessages['password_confirm'])) {
                                foreach ($errorMessages['password_confirm'] as $errorMessage) {
                                    echo sprintf($labelError, e($errorMessage));
                                }
                            }
                            ?>                            
                            <?php
                            $phone = "";
                            if (isset($delivery_info['phone'])) {
                                $phone = $delivery_info['phone'];
                            }
                            ?>
                            <div class="form-group">
                                <input type="text" name="phone" class="form-control" placeholder="Phone" value="<?php echo old('phone'); ?>" />
                            </div>
                            <?php
                            $labelError = '<label class="error">%1$s</label>';
                            // Display errors
                            if (isset($errorMessages['phone'])) {
                                foreach ($errorMessages['phone'] as $errorMessage) {
                                    echo sprintf($labelError, e($errorMessage));
                                }
                            }
                            ?>
                            <?php
                            $address = "";
                            if (isset($delivery_info['address'])) {
                                $address = $delivery_info['address'];
                            }
                            ?>
                            <div class="form-group">
                                <textarea name="address" cols="2" class="form-control" placeholder="Address"><?php echo old('address'); ?></textarea>
                            </div>
                            <?php
                            $labelError = '<label class="error">%1$s</label>';
                            // Display errors
                            if (isset($errorMessages['address'])) {
                                foreach ($errorMessages['address'] as $errorMessage) {
                                    echo sprintf($labelError, e($errorMessage));
                                }
                            }
                            ?>
                            <input type="submit" name="submit" value="submit" class="btn">
                            <?php
                            echo Form::close();
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>