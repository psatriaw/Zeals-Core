<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $meta['title'] }}</title>
    <meta name="description" content="{{ $meta['description'] }}">
    <meta name="keywords" content="{{ $meta['keywords'] }}">

    <link href="{{ url('templates/admin/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ url('templates/admin/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ url('templates/admin/css/animate.css') }}" rel="stylesheet">
    <link href="{{ url('templates/admin/css/style.css') }}" rel="stylesheet">
    <link href="{{ url('templates/admin/css/custom.css') }}" rel="stylesheet">
    <link rel="shortcut icon" href="<?=url("templates/admin/img/favicon.png")?>">
    <script src="{{ url('templates/admin/js/jquery-3.1.1.min.js') }}"></script>
    <script src="{{ url('templates/admin/js/bootstrap.min.js') }}"></script>
</head>

<body class="dark-bg">


    <?php print $content; ?>

    <!-- Mainly scripts -->

</body>

</html>
