<!-- carousel -->
<!-- owl carousel -->
<link rel="stylesheet" href="<?php echo e(url('templates/frontend/plugin/owlcarousel/assets/owl.carousel.min.css')); ?>">
<link rel="stylesheet" href="<?php echo e(url('templates/frontend/plugin/owlcarousel/assets/owl.theme.default.min.css')); ?> ">
<link type="text/css" rel="stylesheet" href="<?php echo e(url('templates/frontend/plugin/lightslider/css/lightslider.min.css')); ?>" />
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

<script src="<?php echo e(url('templates/frontend/plugin/jquery-easing/jquery.easing.min.js')); ?>"></script>
<script src="<?php echo e(url('templates/frontend/plugin/owlcarousel/owl.carousel.min.js')); ?>"></script>
<script src="<?php echo e(url('templates/frontend/plugin/lightslider/js/lightslider.min.js')); ?>"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<style>

</style>
<main role="main">
  <?php
    $campaigntypes = array(
      "banner"  => "AMP",
      "o2o"     => "O2O",
      "shopee"  => "AMP"
    );
  ?>
  <div class="album pb-5 top30">
    <div class="container">
      <div class="row">
        <div class="col-12 col-md-3 mb-3">
          <?php echo $__env->make('frontend.menu_sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div class="col-12 col-md-9 mobile-nopadding">
          <div class="album-content">
            <h3 class="text-black mb-3">Campaigns</h3>
            <div class="row">
              <?php
                if(sizeof($campaigns)){
                  foreach ($campaigns as $key => $value) {
                    ?>
                    <div class="col-12 col-sm-6 col-md-6 mb-3">
                      <div class="item">
                        <div class="panel">
                          <div class="panel-header text-dark-pink text-center">
                            Earn up to Rp. <?=number_format($value->budget,0,",",".")?>
                          </div>
                          <div class="panel-content" style="padding-left:15px;padding-right:15px;min-height:120px;">
                            <span class="pull-right label label-pink-outline"><?=$campaigntypes[@$value->campaign_type]?></span>
                            <?php if(@$value->joined){ ?>
                              <span class="pull-right label label-pink-outline">Joined</span>
                            <?php } ?>
                            <?=$value->campaign_title?>
                            <br>
                            <div class="campaign-date"><i class="fa fa-calendar"></i> until <?=date("M d",strtotime($value->end_date))?><sup><?=date("S",strtotime($value->end_date))?></sup> <?=date("Y",strtotime($value->end_date))?></div>
                            <small><a href="<?php echo e(url('campaign/detail/'.$value->campaign_link)); ?>" class="text-black">see campaign <i class="fa fa-angle-right"></i> </a></small>
                          </div>
                        </div>
                      </div>
                    </div>
                    <?php
                  }
                  ?>
                  <div class="col-12 text-center mt-5">
                    <a href="" class="btn btn-secondary">Load more</a>
                  </div>
                  <?php
                }else{
                  ?>
                  <div class="col-12 col-sm-12 col-md-12 mb-3 text-center not-found">
                    <img src="<?php echo e(url('templates/frontend/assets/img/no_data.png')); ?>">
                    <h3>No campaign found!</h3>
                    <p>Please check your preferences.</p><br>
                    <p>Or please try again to reload this page</p>
                  </div>
                  <?php
                }
              ?>

            </div>
            <div class="row">

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
<script src="<?php echo e(url('templates/admin/js/plugins/chartJs/Chart.min.js')); ?>"></script>
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
<?php /**PATH /home/zealsasi/new.zeals.asia/resources/views/frontend/campaign_list.blade.php ENDPATH**/ ?>