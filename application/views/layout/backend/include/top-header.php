<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>Admin | <?= $title ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
    <link href="<?= assetUrl('plugins/font-awesome/css/font-awesome.min.css') ?>" rel="stylesheet" type="text/css"/>
    <link href="<?= assetUrl('plugins/simple-line-icons/simple-line-icons.min.css') ?>" rel="stylesheet" type="text/css"/>
    <link href="<?= assetUrl('plugins/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet" type="text/css"/>
    <link href="<?= assetUrl('plugins/bootstrap-switch/css/bootstrap-switch.min.css') ?>" rel="stylesheet" type="text/css"/>
    <link href="<?= assetUrl('backend/css/components.min.css') ?>" rel="stylesheet" type="text/css"/>
    <link href="<?= assetUrl('backend/css/plugins.min.css') ?>" rel="stylesheet" type="text/css"/>
    <link href="<?= assetUrl('backend/css/layout.min.css') ?>" rel="stylesheet" type="text/css"/>
    <link href="<?= assetUrl('backend/css/darkblue.min.css') ?>" rel="stylesheet" type="text/css"/>
    <link href="<?= assetUrl('backend/css/custom.css') ?>" rel="stylesheet" type="text/css"/>
    <link rel="shortcut icon" href="favicon.ico"/>
    <!-- Jquery -->
    <?= jquery() ?>
    <!-- toastr notification  -->
    <?= toastrJS() ?>
</head>