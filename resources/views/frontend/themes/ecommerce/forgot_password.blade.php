@extends('frontend.themes.ecommerce.default')

@section('content')
<?php
if (Session::has('forgot_password_error_message')) {
    ?>
    <p class="alert alert-danger"><?php echo e(session('forgot_password_error_message')); ?></p>
    <?php
}
?>
<?php
if (Session::has('forgot_password_success_message')) {
    ?>
    <p class="alert alert-success"><?php echo e(session('forgot_password_success_message')); ?></p>
    <?php
}
?>
<?php
$url = action(config('app.frontend_namespace') . 'CustomerController@process_forgot_password');
echo Form::open(['method' => 'POST', 'url' => $url, 'id' => "form-forgot-password"]);
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
<input type="submit" name="submit" value="submit" class="btn">
<?php
echo Form::close();
?>
@endsection