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

    <link rel="stylesheet" href="{{ url('templates/newzeals/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ url('templates/newzeals/assets/css/style.css') }}">
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

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-BC5T8BZFNK"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-BC5T8BZFNK');
    </script>
  </body>
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
</html>
