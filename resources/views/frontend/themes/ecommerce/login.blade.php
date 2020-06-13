@extends('frontend.themes.ecommerce.default')

@section('content')
<h1>Login</h1>
<?php
$errors = request()->session()->get('errors');
if ($errors) {
    $errorMessages = $errors->getMessages();
}
$labelError = '<label class="error">%1$s</label>';
?>
<?php
if (Session::has('login_failure')) {
    ?>
    <p class="alert alert-danger"><?php echo e(session('login_failure')); ?></p>
    <?php
}
?>
<?php
$url = action(config('app.frontend_namespace') . 'CustomerController@do_login');
$url_forgot_password = action(config('app.frontend_namespace') . 'CustomerController@forgot_password');
$url_register= action(config('app.frontend_namespace') . 'CustomerController@register');
echo Form::open(['method' => 'POST', 'url' => $url, 'id' => "form-delivery-info"]);
?>
<div class="form-group">
    <input type="text" name="email" class="form-control" placeholder="Email" value="<?php echo old('email'); ?>" />
</div>
<?php
// Display errors
if (isset($errorMessages['email'])) {
    foreach ($errorMessages['email'] as $errorMessage) {
        echo sprintf($labelError, e($errorMessage));
    }
}
?>
<div class="form-group">
    <input type="password" name="password" class="form-control" placeholder="Password" value="" />
</div>
<?php
// Display errors
if (isset($errorMessages['password'])) {
    foreach ($errorMessages['password'] as $errorMessage) {
        echo sprintf($labelError, e($errorMessage));
    }
}
?>
<input type="submit" name="submit" value="submit" class="btn">
<?php
echo Form::close();
?>
<div><a href="<?php echo $url_forgot_password; ?>">Forgot Password</a></div>
<div><a href="<?php echo $url_register; ?>">Register</a></div>
@endsection