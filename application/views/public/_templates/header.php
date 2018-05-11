<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
<!doctype html>
<html lang="<?php echo $lang; ?>" ng-app="INVENTARIO" ng-class="{'full-page-map': isFullPageMap}">
<head>
    <meta charset="<?php echo $charset; ?>">
    <title><?php echo $title; ?></title>
    <?php if ($mobile === FALSE): ?>
        <!--[if IE 8]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <![endif]-->
    <?php else: ?>
        <meta name="HandheldFriendly" content="true">
    <?php endif; ?>
    <?php if ($mobile == TRUE && $mobile_ie == TRUE): ?>
        <meta http-equiv="cleartype" content="on">
    <?php endif; ?>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="google" content="notranslate">
    <meta name="robots" content="noindex, nofollow">
    <?php if ($mobile == TRUE && $ios == TRUE): ?>
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta name="apple-mobile-web-app-title" content="<?php echo $title; ?>">
    <?php endif; ?>
    <?php if ($mobile == TRUE && $android == TRUE): ?>
        <meta name="mobile-web-app-capable" content="yes">
    <?php endif; ?>

    <link rel="icon" href="data:image/x-icon;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAAqElEQVRYR+2WYQ6AIAiF8W7cq7oXd6v5I2eYAw2nbfivYq+vtwcUgB1EPPNbRBR4Tby2qivErYRvaEnPAdyB5AAi7gCwvSUeAA4iis/TkcKl1csBHu3HQXg7KgBUegVA7UW9AJKeA6znQKULoDcDkt46bahdHtZ1Por/54B2xmuz0uwA3wFfd0Y3gDTjhzvgANMdkGb8yAyY/ro1d4H2y7R1DuAOTHfgAn2CtjCe07uwAAAAAElFTkSuQmCC">

    <?php if ($mobile === FALSE): ?>
        <!--[if lt IE 9]>
        <script src="<?php echo base_url($plugins_dir . '/html5shiv/html5shiv.min.js'); ?>"></script>
        <script src="<?php echo base_url($plugins_dir . '/respond/respond.min.js'); ?>"></script>
        <![endif]-->
    <?php endif; ?>

    <!--    Style for frontend template  -->
    <link href="<?php echo base_url($frameworks_dir . '/frontend/css/styles.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url($frameworks_dir . '/frontend/css/vendors.min.css'); ?>" rel="stylesheet">


</head>
<body ng-controller="MainController" scroll-spy id="top" ng-class="{{ngclass}}">