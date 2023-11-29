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

<main role="main">
  <div class="album pb-5 top30">
    <div class="container">
      <div class="row">
        <div class="col-12 col-md-3 mb-3">
          <?php echo $__env->make('frontend.menu_sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div class="col-12 col-md-9 mobile-nopadding">
          <div class="album-content">
            <h3 class="text-black mb-3">Logs</h3>
            <div class="row justify-content-md-center">
              <div class="col-12 col-md-4">
                <div class="panel">
                  <div class="panel-header text-center" style="background:#e5e5e5;color:#666;">
                    Total Log
                  </div>
                  <div class="panel-content text-center">
                    <h3 class="text-black text-center"><?=number_format($total_log,0,',','.')?> logs</h3>
                  </div>
                </div>
              </div>
            </div>

            <div class='table-responsive mt-3'>
            <?php
              if($logs!=""){
                print '<table class="table table-log table-bordered">';
                print '<tr>';
                print '<th>Time</th>';
                print '<th>Type</th>';
                print '<th>IP</th>';
                //print '<th>Encrypted Identifier</th>';
                print '<th>Commission</th>';
                print '<th>Device info</th>';
                print '</tr>';
                foreach ($logs as $key => $value) {
                  ?>
                  <tr>
                    <td><?=date("d/m/Y H:i A",$value->time_created)?></td>
                    <td><?=$value->type_conversion?></td>
                    <td><?=$value->ip?></td>
                    <!--
                    <td><?=$value->encrypted_code?></td>
                    -->
                    <td class='text-right'>Rp.<?=number_format($value->commission,0,',','.')?></td>
                    <td><?=$value->browser?></td>
                  </tr>
                  <?php
                }
                print "</table>";
              }else{
                ?>
                <p>No data found!</p>
                <?php
              }
            ?>
            </div>

            <div class="row">
              <div class="col-12 text-center">
                <a href="" class="btn btn-secondary">Reload</a>
              </div>
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
<?php /**PATH /home2/zealsasi/new.zeals.asia/resources/views/frontend/logs.blade.php ENDPATH**/ ?>