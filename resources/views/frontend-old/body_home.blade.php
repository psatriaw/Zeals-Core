<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" >

      <link rel="shortcut icon" type="image/x-icon" href="{{ url('templates/frontend/assets/img/favicon.png')}}">

      <link rel="stylesheet" href="{{ url('templates/frontend/assets/css/fontawesome.min.css') }}">
      <link rel="stylesheet" href="{{ url('templates/frontend/assets/css/animate.min.css') }}">
      <link rel="stylesheet" href="{{ url('templates/frontend/assets/css/bootstrap.min.css') }}">
      <link rel="stylesheet" href="{{ url('templates/frontend/assets/css/slick.min.css') }}">
      <link rel="stylesheet" href="{{ url('templates/frontend/assets/css/slider.css') }}">
      <link rel="stylesheet" href="{{ url('templates/frontend/assets/css/style.css') }}">


      <script src="{{ url('templates/frontend/assets/js/vendor/modernizr-2.8.3-respond-1.4.2.min.js') }}"></script>
    </head>

    <body class="header-1">

      <div id="main-slider" class="dl-slider">
        <?php
          if($slides){
            foreach ($slides as $key => $value) {
              ?>
              <div class="single-slide">
                  <div class="bg-img kenburns-top-right" style="background-image: url(<?=$value->path?>);"></div>
                  <div class="slider-content-wrap d-flex align-items-center text-left">
                      <div class="container">
                          <div class="slider-content">

                          </div>
                      </div>
                  </div>
              </div><!--Slide-1-->
              <?php
            }
          }
        ?>
      </div>

		<!-- jQuery Lib -->
		<script src="{{ url('templates/frontend/assets/js/vendor/jquery-1.12.4.min.js')}}"></script>
    <script src="{{ url('templates/frontend/assets/js/vendor/popper.min.js')}}"></script>
		<script src="{{ url('templates/frontend/assets/js/vendor/bootstrap.min.js')}}"></script>
    <script src="{{ url('templates/frontend/assets/js/vendor/waypoints.min.js')}}"></script>
		<script src="{{ url('templates/frontend/assets/js/vendor/slick.min.js')}}"></script>
    <script src="{{ url('templates/frontend/assets/js/vendor/headroom.min.js')}}"></script>
    <script src="{{ url('templates/frontend/assets/js/vendor/jquery.smoothscroll.min.js')}}"></script>
    <script src="{{ url('templates/frontend/assets/js/vendor/jquery.magnific-popup.min.js')}}"></script>
		<script src="{{ url('templates/frontend/assets/js/vendor/jquery.ajaxchimp.min.js')}}"></script>
		<script src="{{ url('templates/frontend/assets/js/vendor/jquery.mb.YTPlayer.min.js')}}"></script>
		<script src="{{ url('templates/frontend/assets/js/vendor/odometer.min.js')}}"></script>
		<script src="{{ url('templates/frontend/assets/js/vendor/jquery.nice-select.min.js')}}"></script>
		<script src="{{ url('templates/frontend/assets/js/vendor/simpleParallax.min.js')}}"></script>
		<script src="{{ url('templates/frontend/assets/js/vendor/wow.min.js')}}"></script>
		<script src="{{ url('templates/frontend/assets/js/contact.js')}}"></script>
    <script src="{{ url('templates/frontend/assets/js/appointment.js')}}"></script>
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



       !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '426759538368923');
        fbq('track', 'PageView');
        </script>
        <noscript><img height="1" width="1" style="display:none"
        src="https://www.facebook.com/tr?id=426759538368923&ev=PageView&noscript=1"
        /></noscript>
        <!-- End Facebook Pixel Code -->
        
        <!-- End Facebook Pixel Code -->
    </body>
</html>
