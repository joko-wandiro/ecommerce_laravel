@extends('frontend.themes.ecommerce.default')

@section('content')
<div id="content">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <?php
                $url_dashboard = action(config('app.frontend_namespace') . 'CustomerController@index');
                $url_order_history = action(config('app.frontend_namespace') . 'CustomerController@order');
                $url_profile = action(config('app.frontend_namespace') . 'CustomerController@profile');
                $url_logout = action(config('app.frontend_namespace') . 'CustomerController@logout');
                ?>
                <ul id="order-steps" class="xrow">
                    <li class="com-3"><a href="<?php echo $url_dashboard; ?>">Dashboard</a></li>
                    <li class="com-3 active"><a href="<?php echo $url_order_history; ?>">Order History</a></li>
                    <li class="com-3"><a href="<?php echo $url_profile; ?>">Profile</a></li>
                    <li class="com-3"><a href="<?php echo $url_logout; ?>">Logout</a></li>
                </ul>
                <div id="welcome">
                    <div class="row">
                        <div class="col-12">
                            <?php echo $scaffolding; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection