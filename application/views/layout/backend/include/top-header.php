<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php getMetaData(); ?>
    <title>Admin | <?= $title ?></title>
    <!-- Bootstrap -->
    <link rel="stylesheet" type="text/css" href="<?= assetUrl('plugins/bootstrap/css/bootstrap.min.css') ?>" media="screen">
    <!-- Font Awesome -->
    <link rel="stylesheet" type="text/css" href="<?= assetUrl('plugins/font-awesome/css/font-awesome.min.css') ?>" media="screen">
    <!-- NProgress -->
    <link rel="stylesheet" type="text/css" href="<?= assetUrl('plugins/nprogress/nprogress.css') ?>" media="screen">
    <!-- Custom Theme Style -->
    <link rel="stylesheet" type="text/css" href="<?= assetUrl('backend/css/custom.min.css') ?>" media="screen">
    <?php getStylesheet(); ?>
    <!-- Jquery -->
    <?= jquery() ?>
    <!-- toastr notification  -->
    <?= toastrJS() ?>
</head>