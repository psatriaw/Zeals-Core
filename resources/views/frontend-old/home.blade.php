
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, max-scale=1">
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

    <link rel="shortcut icon" type="image/x-icon" href="{{ url('templates/frontend/assets/img/favicon.png')}}">

    <!-- Bootstrap core CSS -->

    <link rel="stylesheet" href="{{ url('templates/frontend/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ url('templates/frontend/assets/css/slick.min.css') }}">
    <link rel="stylesheet" href="{{ url('templates/frontend/assets/css/slider.css') }}">
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
  <body>
    <header>
      <div class="navbar navbar shadow-sm">
        <div class="container d-flex justify-content-between mobile-nopadding">
          <a href="{{ url('') }}" class="navbar-brand d-flex align-items-center">
            <img src="{{ url('templates/frontend/assets/img/logo_white.png')}}" alt="Logo" class="main-logo">
          </a>
          <div class="head-menu text-right">
            <ul class="nav">
              <?php if(@$login->id_user==""){ ?>
              <li class="nav-item">
                <a class="nav-link <?=(Request::segment(1)=="home")?"active":""?>" href="#">HOME</a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?=(Request::segment(1)=="shop")?"active":""?>" href="{{ url('shop') }}">SHOP</a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?=(Request::segment(1)=="confirmation")?"active":""?>" href="{{ url('confirmation') }}">CONFIRMATION</a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?=(Request::segment(1)=="signin")?"active":""?>" href="{{ url('signin') }}" tabindex="-1" aria-disabled="true">LOGIN</a>
              </li>
              <?php }else{?>
                <li class="nav-item">
                  <a class="nav-link <?=(Request::segment(1)=="home")?"active":""?>" href="#">HOME</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link <?=(Request::segment(1)=="shop")?"active":""?>" href="{{ url('shop') }}">SHOP</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link <?=(Request::segment(1)=="confirmation")?"active":""?>" href="{{ url('confirmation') }}">CONFIRMATION</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link <?=(Request::segment(1)=="signin")?"active":""?>" href="{{ url('signout') }}" tabindex="-1" aria-disabled="true">KELUAR</a>
                </li>
              <?php } ?>
              <li class="nav-item relative">
                <a class="nav-link" href="{{ url('login') }}" tabindex="-1" aria-disabled="true"><span class='badge badge-cart'>1</span> <i class='fa fa-shopping-cart'></i></a>
              </li>
            </ul>
            <ul class="nav nav-nd">
              <?php if(@$login->id_user!=""){ ?>
                <li class="nav-item">
                  <a class="nav-link <?=(Request::segment(1)=="profile")?"active":""?>" href="{{ url('profile') }}">PROFILE</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link <?=(Request::segment(1)=="history")?"active":""?>" href="{{ url('history') }}">HISTORY</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link <?=(Request::segment(1)=="address")?"active":""?>" href="{{ url('address') }}">ADDRESS</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link <?=(Request::segment(1)=="complain")?"active":""?>" href="{{ url('complain') }}">COMPLAIN</a>
                </li>
              <?php } ?>
            </ul>
          </div>
          <button id="head-menu-button" class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
            <span class="fa fa-list"></span>
          </button>
        </div>
      </div>
    </header>

    <?=$content?>

    <script src="{{ url('templates/frontend/assets/js/vendor/slick.min.js')}}"></script>
	  <!--
    <script>
    /* ======= Main Slider ======= */
    $('#main-slider').on('init', function(e, slick) {
        var $firstAnimatingElements = $('div.single-slide:first-child').find('[data-animation]');
        doAnimations($firstAnimatingElements);
    });
    $('#main-slider').on('beforeChange', function(e, slick, currentSlide, nextSlide) {
              var $animatingElements = $('div.single-slide[data-slick-index="' + nextSlide + '"]').find('[data-animation]');
              doAnimations($animatingElements);
    });
    $('#main-slider').slick({
       autoplay: true,
       autoplaySpeed: 10000,
       dots: true,
       fade: true,
       prevArrow: '<div class="slick-prev"><i class="fa fa-chevron-left"></i></div>',
            nextArrow: '<div class="slick-next"><i class="fa fa-chevron-right"></i></div>'
    });
    function doAnimations(elements) {
        var animationEndEvents = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
        elements.each(function() {
            var $this = $(this);
            var $animationDelay = $this.data('delay');
            var $animationType = 'animated ' + $this.data('animation');
            $this.css({
                'animation-delay': $animationDelay,
                '-webkit-animation-delay': $animationDelay
            });
            $this.addClass($animationType).one(animationEndEvents, function() {
                $this.removeClass($animationType);
            });
        });
    }
    </script>
	-->
    <div style="position:fixed;right: 10px;bottom: 0px;">
      <a href="https://api.whatsapp.com/send?phone=<?=$official_phone_number?>&text=Hai">
          <img src="https://www.freepngimg.com/thumb/whatsapp/77239-instant-messaging-logo-whatsapp-message-android-thumb.png" style="width: 60px;height: 60px;">
      </a>
    </div>
  </body>
</html>
