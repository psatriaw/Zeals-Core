<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e($meta['title']); ?></title>
    <meta name="description" content="<?php echo e($meta['description']); ?>">
    <meta name="keywords" content="<?php echo e($meta['keywords']); ?>">

    <link href="<?php echo e(url('templates/admin/css/bootstrap.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(url('templates/admin/font-awesome/css/font-awesome.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(url('templates/admin/css/animate.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(url('templates/admin/css/style.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(url('templates/admin/css/custom.css')); ?>" rel="stylesheet">
    <link rel="shortcut icon" href="<?=url("templates/admin/img/favicon.png")?>">
    <script src="<?php echo e(url('templates/admin/js/jquery-3.1.1.min.js')); ?>"></script>
    <script src="<?php echo e(url('templates/admin/js/bootstrap.min.js')); ?>"></script>
</head>

<body class="dark-bg">


    <?php print $content; ?>

    <!-- Mainly scripts -->

</body>

</html>
<?php /**PATH /home/zealsasi/new.zeals.asia/resources/views/backend/body.blade.php ENDPATH**/ ?>