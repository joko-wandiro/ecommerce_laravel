@extends('frontend.themes.ecommerce.default')

@section('content')
<?php
if (Session::has('change_password_success_message')) {
    ?>
    <p class="alert alert-success"><?php echo e(session('change_password_success_message')); ?></p>
    <?php
}
?>
<?php
$url = action(config('app.frontend_namespace') . 'CustomerController@process_change_password', array('token' => $token));
echo Form::open(['method' => 'POST', 'url' => $url, 'id' => "form-forgot-password"]);
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
<input type="submit" name="submit" value="submit" class="btn">
<?php
echo Form::close();
?>
@endsection