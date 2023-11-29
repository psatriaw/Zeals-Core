<link rel="stylesheet" href="{{ url('templates/frontend/assets/css/slick.min.css') }}">
<link rel="stylesheet" href="{{ url('templates/frontend/assets/css/slider.css') }}">
<link rel="stylesheet" href="{{ url('templates/frontend/assets/css/style.css') }}">
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
			break;
      }
    }
  ?>
</div>
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
