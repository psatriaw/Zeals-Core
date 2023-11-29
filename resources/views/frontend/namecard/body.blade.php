<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title><?=$title?></title>
    <meta property="og:title" content="<?=$title?>">
    <meta property="og:description" content="<?=$description?>">
    <meta property="og:keywords" content="<?=$keywords?>">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?=Request::url()?>">
    <meta property="og:title" content="<?=$title?>">
    <meta property="og:description" content="<?=$description?>">

    <?php if(@$data['slug']){ ?>
    <meta property="og:image" content="{{ url('templates/namecard/qr/'.$data['slug'].'.png') }}">
    <?php } ?>

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?=Request::url()?>">
    <meta property="twitter:title" content="<?=$title?>">
    <meta property="twitter:description" content="<?=$description?>">
    <?php if(@$image){ ?>
    <meta property="twitter:image" content="https://metatags.io/assets/meta-tags-16a33a6a8531e519cc0936fbba0ad904e52d35f34a46c97a2c9f6f7dd7d336f2.png">
    <?php } ?>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ url('templates/admin/img/favicon.png')}}">

    <!-- Bootstrap core CSS -->

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ url('templates/newzeals/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ url('templates/namecard/style.css') }}">
    <link href="{{ url('templates/admin/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
    <script src="{{ url('templates/newzeals/assets/js/vendor/jquery-1.12.4.min.js')}}"></script>
    <script src="{{ url('templates/admin/js/bootstrap.min.js') }}"></script>
    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }


      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>
    <!-- Custom styles for this template -->
    <link href="album.css" rel="stylesheet">
  </head>
  <body>
    <div class="container-fluid dashboard">
      <div class="row">
        <div class="col-xs-12 col-md-12">
          <?=$content?>
        </div>
      </div>
    </div>
  </body>
  </body>
</html>
