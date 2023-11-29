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
th{
  font-weight: 500;
  border-top: 0px !important;
  color: #666;
  font-size: 14px;
}
</style>

<?php
  $campaigntypes = array(
    "banner"  => "AMP",
    "o2o"     => "O2O",
    "shopee"     => "AMP",
  );
?>

<main role="main">

  <div class="album pb-5 top30">
    <div class="container">
      <div class="row">
        <div class="col-12 col-md-3 mb-3">
          <?php echo $__env->make('frontend.menu_sidebar',array('saldo' => @$saldo, 'login' => @$login), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div class="col-12 col-md-9 mobile-nopadding">
          <div class="album-content">
            <h3 class="text-black mb-3">Dashboard</h3>
            <div class="row">
              <div class="col-6 col-md-3 mt-2">
                <div class="panel">
                  <div class="panel-header text-center" style="background:#5eb6f0;color:#fff;">
                    Unique Visitor
                  </div>
                  <div class="panel-content text-center">
                    <h3 class="text-black text-center"><?=number_format(@$statistic['visit']['total'],0,',','.')?></h3>
                    <small>visitor</small>
                  </div>
                </div>
              </div>

              <div class="col-6 col-md-3 mt-2">
                <div class="panel">
                  <div class="panel-header text-center" style="background:#c82360;color:#fff;">
                    Unique Read/Stay
                  </div>
                  <div class="panel-content text-center">
                    <h3 class="text-black text-center"><?=number_format(@$statistic['read']['percent'],2,',','.')?>%</h3>
                    <small>of visitor</small>
                  </div>
                </div>
              </div>

              <div class="col-6 col-md-3 mt-2">
                <div class="panel">
                  <div class="panel-header text-center" style="background:#fcb13b;color:#fff;">
                    Unique Action
                  </div>
                  <div class="panel-content text-center">
                    <h3 class="text-black text-center"><?=number_format(@$statistic['action']['percent'],2,',','.')?>%</h3>
                    <small>of visitor</small>
                  </div>
                </div>
              </div>

              <div class="col-6 col-md-3 mt-2">
                <div class="panel">
                  <div class="panel-header text-center" style="background:#961515;color:#fff;">
                    Unique Acquisition
                  </div>
                  <div class="panel-content text-center">
                    <h3 class="text-black text-center"><?=number_format(@$statistic['acquisition']['percent'],2,',','.')?>%</h3>
                    <small>of visitor</small>
                  </div>
                </div>
              </div>

              <div class="col-12 mt-3">
                <div class="panel">
                  <div class="panel-header">
                    Realtime Graphic Performance
                  </div>
                  <div class="panel-content" style="padding-left:15px;padding-right:15px;">
                    <canvas id="lineChart" height="120" class="view-web"></canvas>
                    <canvas id="lineChart2" height="320" class="view-mobile"></canvas>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-12 mt-3">
                <div class="panel">
                  <div class="panel-header">
                    Tracker Log
                  </div>
                  <div class="panel-content box-log" style="height:200px;overflow-y:scroll;overflow-x:hidden;">
                    <div class='table-responsive'>
                    <?php
                      if($logs!=""){
                        print '<table class="table table-log">';
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
                            <!--<td><?=$value->encrypted_code?></td>-->
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
                  </div>
                </div>
              </div>

              <div class="col-md-6 col-12 mt-3">
                <div class="panel">
                  <div class="panel-header">
                    10 Top Earnings
                  </div>
                  <div class="panel-content box-log" style="height:200px;overflow-y:scroll;overflow-x:hidden;">
                    <table class="table table-log">
                      <?php
                        if($earning['top10']){
                          foreach ($earning['top10'] as $key => $value) {
                            ?>
                            <tr>
                              <td><?=$value->campaign_title?></td>
                              <td><?=$campaigntypes[@$value->campaign_type]?></td>
                              <td class="text-right"><strong>Rp.<?=number_format($value->earning,0,',','.')?></strong></td>
                            </tr>
                            <?php
                          }
                        }
                      ?>
                    </table>
                  </div>
                </div>
              </div>

              <div class="col-12 mt-3">
                <div class="panel">
                  <div class="panel-header">
                    Running campaigns
                  </div>
                  <div class="panel-content pl-3 pr-3">
                    <div class="testimoni-prev">
                        <i class="fa fa-chevron-left" aria-hidden="true"></i>
                    </div>
                    <div class="testimoni-next">
                        <i class="fa fa-chevron-right" aria-hidden="true"></i>
                    </div>
                    <div class="owl-carousel" id="owl-carousel-testimoni">
                        <?php
                          if($campaigns){
                            foreach ($campaigns as $key => $value) {
                              ?>
                              <div class="item">
                                <div class="panel">
                                  <div class="panel-header text-dark-pink">
                                    Earn up to Rp. <?=number_format($value->budget,0,',','.')?>
                                  </div>
                                  <div class="panel-content" style="padding-left:15px;padding-right:15px;min-height:120px;">
                                    <span class="pull-right label label-pink-outline"><?=$campaigntypes[@$value->campaign_type]?></span>
                                    <?=$value->campaign_title?>
                                    <br>
                                    <div class="campaign-date"><i class="fa fa-calendar"></i> until <?=date("d",strtotime($value->end_date))?><sup><?=date("S",strtotime($value->end_date))?></sup> <?=date("M Y",strtotime($value->end_date))?></div>
                                    <small><a href="<?php echo e(url('campaign/detail/'.$value->campaign_link)); ?>" class="text-black">lihat campaign <i class="fa fa-angle-right"></i> </a></small>
                                  </div>
                                </div>
                              </div>
                              <?php
                            }
                          }
                        ?>

                    </div>
                  </div>
                </div>
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

    $("#formmain").submit(function(e){
      e.preventDefault();
      $("#updatealert").html("");
      $("#btnupdate").addClass("disabled").prop("disabled",true).html("Processing..");
      $.ajax({
        type:"POST",
        dataType: "json",
        url:"<?=url('user/ajax-update')?>",
        data: $(this).serialize()
      })
      .done(function(result){
        console.log(result);
        if(result.status=="success"){
          $("#updatealert").html(result.response);
        }else if(result.status == "error_validation"){
          var errors = result.response;
          for(var i in errors){
            $("#"+i).prop("title",errors[i][0]).prop("data-placement","right").tooltip().parent(".form-group").addClass("has-error");
          }
          $("#updatealert").html("<div class='alert alert-danger'>Tolong cek input! Ada kesalahan.</div>");
        }else{
          $("#updatealert").html(result.response);
        }
        $("#btnupdate").removeClass("disabled").prop("disabled",false).html("SIMPAN PERUBAHAN");
      })
      .fail(function(msg){
        console.log(msg);
        $("#btnupdate").attr("disabled",false).removeClass("disabled").html("SIMPAN PERUBAHAN");
      })
      .always(function(){

      });
    });


          var lineData = {
              labels:['5 days ago', '4 days ago', '3 days ago', '2 days ago', '1 days ago', 'under 12 hours'],
              datasets: [{
                    label: "Acquisition",
                    backgroundColor: "#961515",
                    borderColor: "#961515",
                    pointBackgroundColor: "#ffffff",
                    pointBorderColor: "#961515",
                    data: <?=json_encode(@$chart['acquisition'])?>
                },
                {
                    label: "Action",
                    backgroundColor: "#fcb13b",
                    borderColor: "#fcb13b",
                    pointBackgroundColor: "#ffffff",
                    pointBorderColor: "#fcb13b",
                    data: <?=json_encode(@$chart['action'])?>
                },
                {
                      label: "Stay/Read",
                      backgroundColor: "#c82360",
                      borderColor: "#c82360",
                      pointBackgroundColor: "#ffffff",
                      pointBorderColor: "#c82360",
                      data: <?=json_encode(@$chart['read'])?>
                  },
                {
                      label: "Visits",
                      backgroundColor: "#5eb6f0",
                      borderColor: "#5eb6f0",
                      pointBackgroundColor: "#ffffff",
                      pointBorderColor: "#5eb6f0",
                      data: <?=json_encode(@$chart['visit'])?>
                  }
              ]
          };

          var lineOptions = {
              responsive: true
          };


          var ctx = document.getElementById("lineChart").getContext("2d");
          new Chart(ctx, {type: 'bar', data: lineData, options:lineOptions});

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
<?php /**PATH /home2/zealsasi/new.zeals.asia/resources/views/frontend/dashboard.blade.php ENDPATH**/ ?>