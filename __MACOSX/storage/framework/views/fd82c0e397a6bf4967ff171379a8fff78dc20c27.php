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
    "shopee"  => "AMP"
  );
?>
<main role="main">
  <div class="album pb-5 top30">
    <div class="container">
      <div class="row">
        <div class="col-12 col-md-3 mb-3">
          <?php echo $__env->make('frontend.menu_sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div class="col-12 col-md-9 mobile-nopadding">
          <div class="album-content">
            <div class="row">
              <div class="col-12 mb-3">
                <a href="<?php echo e(url('campaign')); ?>" class="btn btn-back btn-sm"><i class="fa fa-angle-left"></i> back</a>
              </div>

              <div class="col-12">
                <h2 class="text-black">Campaign Description</h2>
                <div class="row">
                  <div class="col-12 col-sm-6">
                    <h3 class="text-black">Banner</h3>
                    <?php
                      $banner = $detail->photos;
                    ?>
                    <img src="<?=url($banner)?>" class="img img-thumbnail mb-3">

                    <span class="pull-right label label-pink-outline"><?=$campaigntypes[@$detail->campaign_type]?></span>
                    <?php if(@$detail->joined){ ?>
                    <span class="pull-right label label-pink-outline">Joined</span>
                    <?php } ?>

                    <div class="campaign-dates"><i class="fa fa-calendar"></i> until <?=date("d",strtotime($detail->end_date))?><sup><?=date("S",strtotime($detail->end_date))?></sup> <?=date("M Y",strtotime($detail->end_date))?></div>
                    <h3 class="text-black mt-3">Interest Categories</h3>
                    <div class="" id="preferences">
                      <?php
                        if($categories){
                          foreach ($categories as $key => $value) {
                            ?>
                            <label class="label label-pink"><?=$value->nama_sektor_industri?></label>
                            <?php
                          }
                        }
                      ?>
                    </div>
                    <h3 class="text-black mt-3">Location</h3>
                    <div class="">
                      <?php
                        if($domisili){
                          foreach ($domisili as $key => $value) {
                            ?>
                            <label class="label label-pink"><?=$value->namakab?></label>
                            <?php
                          }
                        }else{
                            ?>
                            <label class="label label-pink">Indonesia</label>
                            <?php
                        }
                      ?>
                    </div>
                  </div>

                  <div class="col-12 col-sm-6">
                    <div class="row">
                      <div class="col-12">
                        <h3 class="text-black">Description</h3>
                        <p class="campaign-description">
                          <?=$detail->campaign_description?>
                        </p>
                      </div>
                      <div class="col-12">
                        <h3 class="text-black">Instruction</h3>
                        <p class="campaign-description">
                          <?=nl2br($detail->campaign_instruction)?>
                          <br>
                          <br>
                          Share your unique link with your followers/friends until they visit the url. Make sure your followers/friends do activities according to the target in the campaign. The more
                          activities, the more you will be paid according to the commission below.
                        </p>
                      </div>
                      <div class="col-12">
                        <h3 class="text-black">Do & Don't</h3>
                        <p class="campaign-description">
                          <?=$detail->campaign_do_n_dont?>
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row justify-content-md-center mt-5">
                  <div class="col-sm-8">
                    <h3 class="text-black mt-3">Commission</h3>
                    <p class="campaign-description">
                      <?php if(@$program['visit']){ ?>
                        <span class="item-target"><strong>Unique Visitor</strong>, You will earn <strong>Rp.<?=number_format(@$program['visit']->commission,0,',','.')?></strong> per unique visitor</span>
                      <?php } ?>

                      <?php if(@$program['visit']){ ?>
                        <span class="item-target"><strong>Reader/Stay</strong>, You will earn <strong>Rp.<?=number_format(@$program['read']->commission,0,',','.')?></strong> per unique reader</span>
                      <?php } ?>

                      <?php if(@$program['visit']){ ?>
                        <span class="item-target"><strong>Unique Click</strong>, You will earn <strong>Rp.<?=number_format(@$program['action']->commission,0,',','.')?></strong> per targetted click/action</span>
                      <?php } ?>

                      <?php if(@$program['visit']){ ?>
                        <span class="item-target"><strong>Acquisition</strong>, You will earn <strong>Rp.<?=number_format(@$program['acquisition']->commission,0,',','.')?></strong> per data acquisition</span>
                      <?php } ?>

                      <?php if(@$program['voucher']){ ?>
                        <span class="item-target"><strong>Redemption</strong>, You will earn <strong>Rp.<?=number_format(@$program['voucher']->commission,0,',','.')?></strong> per redemption</span>
                      <?php } ?>

                      <?php if(@$program['cashback']){ ?>
                        <span class="item-target"><strong>Sales/Transaction</strong>, You will earn <strong><?=number_format(@$program['cashback']->commission,0,',','.')?>% </strong> of each purchase transaction.</span>
                      <?php } ?>
                    </p>
                  </div>
                </div>
                <?php if(!@$detail->joined){ ?>
                <div class="row justify-content-md-center mt-5">
                    <div class="col-12 col-md-6">
                      <div class="alert alert-info text-small">
                        You have to click "join the campaign" button to get a unique link
                      </div>
                      <a href="<?php echo e(url('campaign/joincampaign/'.$detail->id_campaign)); ?>" class="btn btn-secondary btn-block btn-xl">Join the Campaign!</a>
                    </div>
                </div>
                <?php }else{  ?>
                <div class="row justify-content-md-center mt-5">
                    <div class="col-12 col-md-8 text-center">
                      <div class="alert alert-info">
                        You have been join the campaign, lets share your unique link and get your earn!
                      </div>
                      <div class="unique-link" id="unique_link">
                        <?=url('link/'.$detail->joined)?>
                      </div>

                      <a href="whatsapp://send?text=Hai semua, yuk coba simak informasi berikut! <?=url('link/'.$detail->joined)?>" data-action="share/whatsapp/share" target="_blank"
                          class="text-decoration-none">
                        <div class="share-icon">
                          <i class="fa fa-whatsapp"></i>
                        </div>
                      </a>

                      <a href="https://www.facebook.com/sharer/sharer.php?u=<?=urlencode(url('link/'.$detail->joined))?>&display=popup" target="_blank"class="text-decoration-none">
                        <div class="share-icon">
                          <i class="fa fa-facebook"></i>
                        </div>
                      </a>

                      <a href="https://twitter.com/share?url=<?=urlencode(url('link/'.$detail->joined))?>&text=Hai semua, yuk coba" target="_blank" class="text-decoration-none">
                        <div class="share-icon">
                          <i class="fa fa-twitter"></i>
                        </div>
                      </a>

                      <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?=urlencode(url('link/'.$detail->joined))?>&utm_source=&utm_medium=banner&utm_campaign=canonza2021_DRM160II&af_id=OWs5a2NsYXQ5clNSYlpFbE8xMjBaQT09" target="_blank" class="text-decoration-none">
                        <div class="share-icon">
                          <i class="fa fa-linkedin"></i>
                        </div>
                      </a>

                      <a href="mailto:?subject=<?=$detail->campaign_title?>&body=<?=urlencode(url('link/'.$detail->joined))?>" class="text-decoration-none">
                        <div class="share-icon">
                          <i class="fa fa-envelope"></i>
                        </div>
                      </a>

                      <a href="https://t.me/share/url?url=<?=urlencode(url('link/'.$detail->joined))?>&text=<?=$detail->campaign_title?>" class="text-decoration-none" target="_blank">
                        <div class="share-icon">
                          <i class="fa fa-telegram"></i>
                        </div>
                      </a>
                    </div>


                </div>
                <?php } ?>
              </div>

              <div class="col-12 mt-5">

                <h2 class="text-black">Earning</h2>
                <p class="campaign-description">
                  Earning estimation will be transferred as your balance every month.
                </p>

                <?php if(@$detail->campaign_type=="shopee"){ ?>
                  <div class="alert alert-info">
                    Oops! We are doing something great for you, please comeback later to see it.
                  </div>
                <?php }else{ ?>
                <div class="row">
                  <div class="col-12 col-md-6  mt-3">
                    <div class="panel">
                      <div class="panel-header text-center" style="background:#5eb6f0;color:#fff;">
                        Earning Estimation
                      </div>
                      <div class="panel-content text-center">
                        <h2 class="text-black text-center">Rp. <?=number_format(@$earning['estimation'],0,',','.')?></h2>
                        <small>this month</small>
                      </div>
                    </div>
                  </div>

                  <div class="col-12 col-md-6  mt-3">
                    <div class="panel">
                      <div class="panel-header text-center" style="background:#5eb6f0;color:#fff;">
                        Total Earning
                      </div>
                      <div class="panel-content text-center">
                        <h2 class="text-black text-center">Rp. <?=number_format(@$earning['total'],0,',','.')?></h2>
                        <small>exclude earning estimation</small>
                      </div>
                    </div>
                  </div>
                </div>
                <?php } ?>



                <h2 class="text-black mt-5">Statistic</h2>
                <p class="campaign-description">You will get link activity information here.</p>
                <?php if(@$detail->campaign_type=="shopee"){ ?>
                  <div class="alert alert-info">
                    Oops! We are doing something great for you, please comeback later to see it.
                  </div>
                <?php }else{ ?>

                <div class="row">
                  <div class="col-6 col-md-3 mt-3">
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

                  <div class="col-6 col-md-3 mt-3">
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

                  <div class="col-6 col-md-3 mt-3">
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

                  <div class="col-6 col-md-3 mt-3">
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
                        <canvas id="lineChart" height="120"></canvas>
                      </div>
                    </div>
                  </div>

                  <div class="col-12 mt-3">
                    <div class="panel">
                      <div class="panel-header">
                        Last 15 Logs
                      </div>
                      <div class="panel-content box-log" style="max-height:400px;overflow-y:scroll;overflow-x:hidden;">
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

                </div>

                <?php } ?>
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
  var lineData = {
      labels:['5 minutes ago', '4 minutes ago', '3 minutes ago', '2 minutes ago', '1 minutes ago', 'under 30 seconds'],
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
</script>
<?php /**PATH /home/zealsasi/new.zeals.asia/resources/views/frontend/campaign_detail.blade.php ENDPATH**/ ?>