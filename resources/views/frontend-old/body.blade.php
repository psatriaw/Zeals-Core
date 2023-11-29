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
    <?php if(@$image){ ?>
    <meta property="og:image" content="https://metatags.io/assets/meta-tags-16a33a6a8531e519cc0936fbba0ad904e52d35f34a46c97a2c9f6f7dd7d336f2.png">
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

    <link rel="stylesheet" href="{{ url('templates/frontend/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ url('templates/frontend/assets/css/style.css') }}">
    <link href="{{ url('templates/admin/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
    <script src="{{ url('templates/frontend/assets/js/vendor/jquery-1.12.4.min.js')}}"></script>
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
  <body class="<?=(@$login)?'':'mobile-nologin'?>">
    <div class="head-bg"></div>
    <header>
      <?php if(@$login){ ?>
      <div class="navbar navbar shadow-sm nav-top">
        <div class="container d-flex justify-content-between mobile-nopadding">
          <div class="head-menu view-mobile" style="width:100%;">
            <div class="row">
              <div class="col-6 col-sm-7 ellipsis ">
                <span class="view-mobile">
                  Hi, <?=$login->first_name?>
                </span>
              </div>
              <div class="col-6 col-sm-5 text-right">
                <button type="button" class="btn btn-menu" onclick="openMenu()">
                  <i class="fa fa-list"></i>
                </button>
              </div>
            </div>
          </div>
          <button id="head-menu-button" class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
            <span class="fa fa-list"></span>
          </button>
        </div>
      </div>
      <?php } ?>
    </header>

    <?=$content?>

    <?php if(Request::segment(1)!="" && @$login->id_user!=""){ ?>
    <footer class="text-white">
      <div class="container">
        <div class="row footer-area">
          <div class="col-12 col-md-8">copyright - zeals.asia &copy; 2021</div>
          <div class="col-md-4 col-12 text-right text-black">
            <!--<i class="fa fa-instagram"></i> @
            -->
          </div>
        </div>
      </div>
    </footer>
    <?php } ?>

    <script>
      function openMenu(){
        $("#sidebarmenu").addClass("open");
      }

      function closeMenu(){
        $("#sidebarmenu").removeClass("open");
      }
    </script>
  </body>
</html>
