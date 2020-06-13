@extends('frontend.themes.ecommerce.default')

@section('content')
<h1>Register</h1>
<?php
$errors = request()->session()->get('errors');
if ($errors) {
    $errorMessages = $errors->getMessages();
}
?>
<?php
$labelError = '<label class="error">%1$s</label>';
if (Session::has('register_message')) {
    ?>
    <p class="alert alert-danger"><?php echo e(session('register_message')); ?></p>
    <?php
}
?>
<?php
$url = action(config('app.frontend_namespace') . 'CustomerController@do_register');
echo Form::open(['method' => 'POST', 'url' => $url, 'id' => "form-register"]);
?>
<div class="form-group">
    <input type="text" name="name" class="form-control" placeholder="Full Name" value="<?php echo old('name'); ?>" />
</div>
<?php
// Display errors
if (isset($errorMessages['name'])) {
    foreach ($errorMessages['name'] as $errorMessage) {
        echo sprintf($labelError, e($errorMessage));
    }
}
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
<div class="form-group">
    <input type="password" name="password_confirm" class="form-control" placeholder="Password Confirm" value="" />
</div>
<?php
// Display errors
if (isset($errorMessages['password_confirm'])) {
    foreach ($errorMessages['password_confirm'] as $errorMessage) {
        echo sprintf($labelError, e($errorMessage));
    }
}
?>                            
<div class="form-group">
    <input type="text" name="phone" class="form-control" placeholder="Phone" value="<?php echo old('phone'); ?>" />
</div>
<?php
// Display errors
if (isset($errorMessages['phone'])) {
    foreach ($errorMessages['phone'] as $errorMessage) {
        echo sprintf($labelError, e($errorMessage));
    }
}
?>
<div class="form-group">
    <textarea name="address" cols="2" class="form-control" placeholder="Address"><?php echo old('address'); ?></textarea>
</div>
<?php
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
@endsection