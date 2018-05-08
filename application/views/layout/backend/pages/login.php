<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Grab-A-Gram || Admin </title>
    <!-- Bootstrap -->
    <link href="<?= assetUrl('plugins/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?= assetUrl('plugins/font-awesome/css/font-awesome.min.css') ?>" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="<?= assetUrl('admin/css/custom.min.css') ?>" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="<?= assetUrl('common/css/style.css') ?>" rel="stylesheet">
</head>
<body class="login">
<div>
    <div class="login_wrapper">
        <div class="animate form login_form">
            <section class="login_content">
                <img src="<?= assetUrl('images/logo.png') ?>">
                <?= htmlFlash() ?>
                <?= form_open('auth/login', ['class' => 'login-form']) ?>
                <div class="form-group">
                    <input class="form-control" placeholder="Email" type="text" name="user_name">
                </div>
                <div class="form-group">
                    <input class="form-control" placeholder="Password" type="password" name="password">
                </div>
                <!--<div class="forgot-username"> Forgot Username or Password? <a href="<? /*= base_url('admin/forgot') */ ?>"> Click here</a></div>-->
                <button class="btn send-btn" type="submit" name="submit">LOGIN</button>
                <?= form_close() ?>
            </section>
        </div>
    </div>
</div>
</body>
</html>