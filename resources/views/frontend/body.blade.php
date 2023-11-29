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
    <meta name="theme-color" content="#5DB6F2" />
    <!-- Bootstrap core CSS -->

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ url('templates/newzeals/assets/css/style.css') }}">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.2.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
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
      <?php if($login){ ?>
      <div class="row" id="head-mobile-menu">
        <div class="col-sm-12">
          <a href="">
            <img src="<?=url("templates/frontend/assets/img/logo.svg")?>" class="mini-logo">
          </a>
          <button type="button" class="btn-menu" onclick="openMenu()">
            <i class="fa fa-bars"></i>
          </button>
        </div>
      </div>
      <?php } ?>

      <div class="row">
        <?php if($login){ ?>
        <div class="col-xs-12 col-md-2 left-sidebar">
          <a href="{{ url('') }}" class="no-view-mobile">
            <img src="<?=url("templates/frontend/assets/img/logo.svg")?>" class="logo">
          </a>
          @include('frontend.menu_sidebar',array('saldo' => @$saldo, 'login' => @$login))
        </div>
        <div class="col-xs-12 col-md-7 pl-4">
          <?=$content?>
        </div>
        <div class="col-xs-12 col-md-3">
          <div class="right-sidebar">
            @include('frontend.menu_sidebar_right',array('saldo' => @$saldo, 'login' => @$login,'joined_campaign' => @$joined_campaign, 'might_like' => @$might_like))
          </div>
        </div>
      <?php }else{ ?>
        <div class="col-xs-12 col-md-12">
          <?=$content?>
        </div>
      <?php }?>
      </div>
    </div>
  </body>
  <!--
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
      <div class="">
    </header>
  -->


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
        console.log("yes");
        $(".left-sidebar").addClass("open");
      }

      function closeMenu(){
        $(".left-sidebar").removeClass("open");
      }
    </script>

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-BC5T8BZFNK"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-BC5T8BZFNK');
    </script>

    @include('frontend.tutorial_video',['login' => $login])


  </body>
  
</html>

<!-- Meta Pixel Code -->
<script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', '943272616812588');
  fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
  src="https://www.facebook.com/tr?id=943272616812588&ev=PageView&noscript=1"
/></noscript>
<!-- End Meta Pixel Code -->
