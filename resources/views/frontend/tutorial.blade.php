<!-- carousel -->
<!-- owl carousel -->
<link rel="stylesheet" href="{{ url('templates/frontend/plugin/owlcarousel/assets/owl.carousel.min.css') }}">
<link rel="stylesheet" href="{{ url('templates/frontend/plugin/owlcarousel/assets/owl.theme.default.min.css') }} ">
<link type="text/css" rel="stylesheet" href="{{ url('templates/frontend/plugin/lightslider/css/lightslider.min.css') }}" />
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

<script src="{{ url('templates/frontend/plugin/jquery-easing/jquery.easing.min.js') }}"></script>
<script src="{{ url('templates/frontend/plugin/owlcarousel/owl.carousel.min.js') }}"></script>
<script src="{{ url('templates/frontend/plugin/lightslider/js/lightslider.min.js') }}"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<main role="main" class="main-content">
  <h1 class="text-black mb-3">Tutorials</h1>
  <div class="mobile-nopadding">
    <div class="album-content">
      <div id="accordion">
        <?php if($tutorials){ ?>
          <?php foreach ($tutorials as $key => $value) { ?>
            <div class="panel">
              <div class="media media-tutorial">
                <img class="mr-3" style="width: 150px;border-radius:5px;" src="http://i3.ytimg.com/vi/<?=$value->video_code?>/hqdefault.jpg" alt="Generic placeholder image">
                <div class="media-body">
                  <p class="mt-0"><i class="fa fa-calendar"></i> <?=date("d M Y H:i",$value->last_update)?></p>
                  <h3><?=$value->title?></h3>
                  <a href="<?=$value->url_video?>" target="_blank" class="btn btn-secondary">see video <i class="fa fa-angle-right"></i> </a>
                </div>
              </div>
            </div>
          <?php } ?>
        <?php } ?>
      </div>
    </div>
  </div>
</main>
<script src="{{ url('templates/admin/js/plugins/chartJs/Chart.min.js') }}"></script>
<script>
    function initializeTestimoniSlider(event){
      setTimeout(function(){
      var yay = $('#owl-carousel-testimoni').find('.active.center').find('.item');
      //  console.log(yay.data('testi'))
        $('#testi-text-user').text(yay.data('testi'))

      }, 100);
    }

    var owl = $('#owl-carousel-testimoni').owlCarousel({
      loop:true,
      margin:10,
      dots:true,
      startPosition:2,
      center:true,
      responsive:{
          0:{
              items:1
          },
          600:{
              items:2
          },
          1000:{
              items:3
          }
      },
      onInitialized : initializeTestimoniSlider
    })

    // Go to the next item
    $('.testimoni-prev').click(function() {
        owl.trigger('prev.owl.carousel');
    })
    // Go to the previous item
    $('.testimoni-next').click(function() {
        owl.trigger('next.owl.carousel', [300]);
    })

    owl.on('changed.owl.carousel', function(event) {
      setTimeout(function(){
      var yay = $('#owl-carousel-testimoni').find('.active.center').find('.item');
      //  console.log(yay.data('testi'))
        $('#testi-text-user').text(yay.data('testi'))

      }, 100);
    })
</script>
