<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
<!doctype html>
<html lang="<?php echo $lang; ?>">
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
        <link rel="icon" href="data:image/x-icon;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAAAbwAAAG8B8aLcQwAAABl0RVh0U29mdHdhcmUAd3d3Lmlua3NjYXBlLm9yZ5vuPBoAAAMXSURBVDiNXZNfbFN1FMe/59ff7V230lK3lVZsZrN1UMtQFuJoR+bQZIkkogZfyEgYEGKiBB5ICMYHfCCorybCkww1y4xoMHGySEA3pCFEnHRD0oEbjDE6OrbSP2vX9t57fJA2W8/z53vOyfd7DjEzKsv36WC9UlC6GdwDAET8tcLoixzvileyVGqwbWibfLjQsB3AXjC9TGnPlCn2is+rZLQPmr6/F6q92SiFMWKAzqwuPrqATtYAQF4JtvhhMvZ9wmLXwqrkzEDQzWG/28F5+wx09UmSWZ/OOc1Fw2TT5qk+9mvV8dGxwGk2NvQRU69kwefAtACwtcG9aDnhH0uq7kj6puWe/mXWWn0/26Q9rj6USdQdSxfCfVpy/BcrmXOrXJ1LbXWh3JsSQAAAovW+v38O7MgdMP9UsxGR6ssKY9wbZiFGWLhCJFd32kff6bB91bo0d0QdzKwx5zoAQJbMKJAqJhdfCH0UOQhvfuyi3TZk1prJAmiIFRLxb2Z+v9Y/M9QFAHlVREo6aX+pMJSKmtctd7YmLdX3hydfY4Cbf7wEAnkG5/4aXs7E9KrYqcWmcentyXSCYdTMiluzou7awO351nJEABGoLFKEzL/t3DLSn3Vm/nwabwHgFs9I4W1i58HQ88HB/RuzO7f6EgWpRktCkY9Et6jFxKW2E9lD3reCqqnOCUAAgMyzaUIlvbE8ldihen2Ow69/tv7F5NSd/skgwEvra11fPDZAjhVHlHNMyPa5Nxo3Kwv/HDCp036wFyBbCbhvb2gGL1UeX0qZdk0rUy0SugxIALhRfC5wd7agXTaCStG+K1zIBVOgKgaXDeBsfCKVvhoNJy+cb035d3uwxh1YEeP/WN6iPD3bbjUGhrV1HY9EbNPd3+Kv4tSd93yeyQe2Pbe+a69cRwqD/IZAj4WMzSt6mTNr9YY/1n48+iEAwIMH/5bT4OITAJ/r4LNyfGdvFMAxnCNTCq4uZtpXhFhhFgAwkQbgBxCf2TF/4+K3p4/qwLJvXF5t5/fUJkDdDOxVRrtB4F5rfrHv+sl35yvZ/wAaFUyH5FFaDAAAAABJRU5ErkJggg==">
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,700italic">
        <link rel="stylesheet" href="<?php echo base_url($frameworks_dir . '/bootstrap/css/bootstrap.min.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url($frameworks_dir . '/font-awesome/css/font-awesome.min.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url($frameworks_dir . '/ionicons/css/ionicons.min.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url($frameworks_dir . '/adminlte/css/adminlte.min.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url($frameworks_dir . '/adminlte/css/skins/skin-blue.min.css'); ?>">
<?php if ($mobile === FALSE && $admin_prefs['transition_page'] == TRUE): ?>
        <link rel="stylesheet" href="<?php echo base_url($plugins_dir . '/animsition/animsition.min.css'); ?>">
<?php endif; ?>
        <link rel="stylesheet" href="<?php echo base_url($frameworks_dir . '/domprojects/css/dp.min.css'); ?>">
<?php if ($mobile === FALSE): ?>
        <!--[if lt IE 9]>
            <script src="<?php echo base_url($plugins_dir . '/html5shiv/html5shiv.min.js'); ?>"></script>
            <script src="<?php echo base_url($plugins_dir . '/respond/respond.min.js'); ?>"></script>
        <![endif]-->
<?php endif; ?>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    </head>
    <body class="hold-transition skin-blue fixed sidebar-mini">
<?php if ($mobile === FALSE && $admin_prefs['transition_page'] == TRUE): ?>
        <div class="wrapper animsition">
<?php else: ?>
        <div class="wrapper">
<?php endif; ?>
