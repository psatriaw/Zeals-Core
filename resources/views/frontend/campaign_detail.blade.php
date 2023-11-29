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
    "shopee"  => "AMP",
    "event"   => "EVENT",
  );
?>
<div class="modal fade" id="alertModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Hai Zeals!!</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p class="text-center" style="font-size:16px;">
            Mudah kan kerja pakai platform kita??<br>
            Jangan sampai komisi kamu hilang ya Kawan!<br>
            Yuk perbarui profile kamu biar kamu bisa kembali login dan cairkan komisi kamu segera!
        </p>
      </div>
      <div class="modal-footer">
        <a href="<?=url('profile')?>" class="btn btn-secondary">Update Profile</a>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="interestPopup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body text-center">
        <h1>Interesting Huh 😊😊??</h1>
        <p class="text-center" style="font-size:16px;margin-top:30px;">
            Get your commission ASAP by joining this campaign!
        </p>
        <p>
            <a href="{{ url('campaign/joincampaign/'.$detail->id_campaign) }}" class="btn btn-full-secondary btn-xl">Join the Campaign</a>
        </p>
      </div>
    </div>
  </div>
</div>

<main role="main" class="main-content">
        <div class="mobile-nopadding">
          <div class="album-content">
            <div class="row">
              <div class="col-12 mb-3">
                <a href="{{ url('campaign') }}" class="btn btn-back btn-sm"><i class="fa fa-angle-left"></i> back</a>
              </div>

              <div class="col-12">
                <div class="row">
                  <div class="col-12 col-md-8">
                    <h1 class="text-black"><?=$detail->campaign_title?></h1>
                  </div>
                  <div class="col-12 col-md-4 text-right">
                    <?php if(@$detail->joined){ ?>
                      <a href="#" class="btn btn-primary  btn-block btn-xl">Joined</a>
                    <?php }else{  ?>
                      <a href="{{ url('campaign/joincampaign/'.$detail->id_campaign) }}" class="btn btn-full-secondary btn-block btn-xl">Join the Campaign</a>
                    <?php } ?>
                  </div>
                  <div class="col-12 col-md-8">
                    <div class="" id="preferences">
                      <?php
                        if($categories){
                          foreach ($categories as $key => $value) {
                            ?>
                            <a  href="{{ url('campaign/'.$value->id_sektor_industri) }}" class="yellow-box"><?=$value->nama_sektor_industri?></a>
                            <?php
                          }
                        }
                      ?>
                    </div>
                  </div>
                  <div class="col-12 col-md-4">
                    <p class="text-right mt-2 valid-until"><i class="fa fa-calendar"></i> valid until <?=date("M d",strtotime($detail->end_date))?><sup><?=date("S",strtotime($detail->end_date))?></sup> <?=date("Y",strtotime($detail->end_date))?></p>
                  </div>
                  <div class="col-12 col-sm-12">
                    <?php
                      $banner = $detail->photos;
                    ?>
                    <img src="<?=url($banner)?>" class="img img-main mb-3 mid-panel">

                    <span class="pull-right label label-white"><?=$campaigntypes[@$detail->campaign_type]?></span>

                    <div class="panel">
                      <h3>Description</h3>
                      <p class="mt-3">
                        <?=nl2br($detail->campaign_description)?>
                      </p>

                      <h3 class="mt-5">Commission</h3>
                      <div class="row">
                        <?php if(@$program['visit']){ ?>
                          <div class="col-sm-3 program-visit">
                            <span class="item-target"><strong>Unique Visitor</strong>You will earn <strong class='item-commission'>Rp.<?=number_format(@$program['visit']->commission,0,',','.')?></strong> <br>
                              <small class="text-small-grey">*) Make sure your audiens visiting the landing page of campaign, then you will be paid per unique visitor</small>
                            </span>
                          </div>
                        <?php } ?>

                        <?php if(@$program['read']){ ?>
                          <div class="col-sm-3 program-read">
                            <span class="item-target"><strong>Reader/Stay</strong>You will earn <strong class='item-commission'>Rp.<?=number_format(@$program['read']->commission,0,',','.')?></strong> <br>
                              <small class="text-small-grey">*) Make sure your unique visitor stay and read all the content at the end point, then you will be paid</small>
                            </span>
                          </div>
                        <?php } ?>

                        <?php if(@$program['commision']){ ?>
                          <div class="col-sm-3 program-click">
                            <span class="item-target"><strong>Unique Click</strong>You will earn <strong class='item-commission'>Rp.<?=number_format(@$program['action']->commission,0,',','.')?></strong> <br>
                              <small class="text-small-grey">*) Make sure your audiens do something like clicking at the landing page, then you will be paid</small>
                            </span>
                          </div>
                        <?php } ?>

                        <?php if(@$program['acquisition']){ ?>
                          <div class="col-sm-3 program-acquisition">
                            <span class="item-target"><strong>Sales</strong>You will earn
                              <strong class='item-commission'>
                                <?php if($program['acquisition']->type=='percent'){ ?>
                                  <?=number_format((@$program['acquisition']->commission),0,',','.')?>%
                                <?php }else{ ?>
                                  Rp.<?=number_format(@$program['acquisition']->commission,0,',','.')?>
                                <?php } ?>
                              </strong>
                              <br>
                              <small class="text-small-grey">*) Make sure your audiens buying, registering or do something in the campaign description</small>
                            </span>
                          </div>
                        <?php } ?>

                        <?php if(@$program['voucher']){ ?>
                          <div class="col-sm-3 program-click">
                            <span class="item-target"><strong>Redemption</strong>You will earn <strong class='item-commission'>Rp.<?=number_format(@$program['voucher']->commission,0,',','.')?></strong> <br>
                              <small class="text-small-grey">*) Make sure your audiens redeem the voucher from your unique link, then you will be paid more</small>
                            </span>
                          </div>
                        <?php } ?>

                        <?php if(@$program['cashback']){ ?>
                          <div class="col-sm-3 program-acquisition">
                            <span class="item-target"><strong>Sales/Transaction</strong>You will earn <strong class='item-commission'>
                              <?=number_format(@$program['cashback']->commission,0,',','.')?>%
                            </strong>
                            <br>
                            <small class="text-small-grey">*) per purchase transaction</small>
                            </span>
                          </div>
                        <?php } ?>
                      </div>

                      <h3 class="mt-5">Target/Audiens Location</h3>
                      <div class="">
                        <?php
                          if($domisili){
                            foreach ($domisili as $key => $value) {
                              ?>
                              <label class="label label-location"><?=$value->namakab?></label>
                              <?php
                            }
                          }else{
                              ?>
                              <label class="label label-location">Indonesia</label>
                              <?php
                          }
                        ?>
                      </div>

                      <h3 class="mt-5">Instructions</h3>
                      <p class="campaign-description">
                        <?=nl2br($detail->campaign_instruction)?>
                      </p>

                      <h3 class="mt-5">Dos & Don'ts</h3>
                      <p class="campaign-description">
                        <?=nl2br($detail->campaign_do_n_dont)?>
                      </p>
                    </div>


                  </div>

                </div>

                <?php if(!@$detail->joined){ ?>

                <?php }else{  ?>
                  <div class="panel">
                      <div class="unique-link" id="unique_link">
                        <div class="row">
                          <div class="col-12">
                            <p class="text-center">
                              You have joined the campaign, let's share your unique link and earn yours!
                            </p>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-xs-12 col-md-6 text-right text-strong" style="line-height:2.2;" id="uniquelink">
                              <?=url('link/'.$detail->joined)?>
                          </div>
                          <div class="col-xs-12 col-md-6">
                            <a href="#" onclick="copyTheText('<?=url('link/'.$detail->joined)?>')">
                              <div class="share-icon" style="background:#fff;border-color:#555;">
                                <i class="fa fa-solid fa-copy" style="color:#555;"></i>
                              </div>
                            </a>

                            <button onclick="showAlert('whatsapp://send?text=Hai semua, yuk coba simak informasi berikut! <?=url('link/'.$detail->joined)?>')" data-action="share/whatsapp/share" class="share-icon" style="background:#fff;border-color:#555;cursor:pointer;">
                              <i class="fab fa-whatsapp" style="color:#0ac73e;"></i>
                            </button>


                            <button onclick="showAlert('https://www.facebook.com/sharer/sharer.php?u=<?=urlencode(url('link/'.$detail->joined))?>&display=popup')" class="share-icon" style="background:#fff;border-color:#555;cursor:pointer;">
                              <i class="fab fa-facebook" style="color:#1877f2;"></i>
                            </button>

                            <button onclick="showAlert('https://twitter.com/share?url=<?=urlencode(url('link/'.$detail->joined))?>&text=Hai semua, yuk coba')" class="share-icon" style="background:#fff;border-color:#555;cursor:pointer;">
                              <i class="fab fa-twitter" style="color:#1da1f2;"></i>
                            </button>

                            <button onclick="showAlert('https://www.linkedin.com/shareArticle?mini=true&url=<?=urlencode(url('link/'.$detail->joined))?>&utm_source=&utm_medium=banner&utm_campaign=canonza2021_DRM160II&af_id=OWs5a2NsYXQ5clNSYlpFbE8xMjBaQT09')" class="share-icon" style="background:#fff;border-color:#555;cursor:pointer;">
                              <i class="fab fa-linkedin" style="color:#0a66c2;"></i>
                            </button>

                            <button onclick="showAlert('mailto:?subject=<?=$detail->campaign_title?>&body=<?=urlencode(url('link/'.$detail->joined))?>')" class="share-icon" style="background:#fff;border-color:#555;cursor:pointer;">
                              <i class="fa fa-envelope" style="color:#555;"></i>
                            </button>

                            <button onclick="showAlert('https://t.me/share/url?url=<?=urlencode(url('link/'.$detail->joined))?>&text=<?=$detail->campaign_title?>')" class="share-icon" style="background:#fff;border-color:#555;cursor:pointer;">
                              <i class="fab fa-telegram" style="color:#0088cc;"></i>
                            </button>
                          </div>
                        </div>
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
                      <div class="panel-header text-center" style="background:#5eb6f0;color:#fff;padding: 10px;border-radius: 10px;">
                        Earning Estimation
                      </div>
                      <div class="panel-content text-center mt-3">
                        <h2 class="text-black text-center">Rp. <?=number_format(@$earning['estimation'],0,',','.')?></h2>
                        <small>this month</small>
                      </div>
                    </div>
                  </div>

                  <div class="col-12 col-md-6  mt-3">
                    <div class="panel">
                      <div class="panel-header text-center" style="background:#5eb6f0;color:#fff;padding: 10px;border-radius: 10px;">
                        Total Earning
                      </div>
                      <div class="panel-content text-center mt-3">
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
                      <div class="panel-header text-center" style="background:#5eb6f0;color:#fff;padding:5px;border-radius:10px;">
                        Unique Visitor
                      </div>
                      <div class="panel-content text-center mt-2">
                        <h3 class="text-black text-center"><?=number_format(@$statistic['visit']['total'],0,',','.')?></h3>
                        <small>visitor</small>
                      </div>
                    </div>
                  </div>

                  <div class="col-6 col-md-3 mt-3">
                    <div class="panel">
                      <div class="panel-header text-center" style="background:#c82360;color:#fff;padding:5px;border-radius:10px;">
                        Unique Read/Stay
                      </div>
                      <div class="panel-content text-center mt-2">
                        <h3 class="text-black text-center"><?=number_format(@$statistic['read']['percent'],2,',','.')?>%</h3>
                        <small>of visitor</small>
                      </div>
                    </div>
                  </div>

                  <div class="col-6 col-md-3 mt-3">
                    <div class="panel">
                      <div class="panel-header text-center" style="background:#fcb13b;color:#fff;padding:5px;border-radius:10px;">
                        Unique Action
                      </div>
                      <div class="panel-content text-center mt-2">
                        <h3 class="text-black text-center"><?=number_format(@$statistic['action']['percent'],2,',','.')?>%</h3>
                        <small>of visitor</small>
                      </div>
                    </div>
                  </div>

                  <div class="col-6 col-md-3 mt-3">
                    <div class="panel">
                      <div class="panel-header text-center" style="background:#961515;color:#fff;padding:5px;border-radius:10px;">
                        Unique Acquisition
                      </div>
                      <div class="panel-content text-center mt-2">
                        <h3 class="text-black text-center"><?=number_format(@$statistic['acquisition']['percent'],2,',','.')?>%</h3>
                        <small>of visitor</small>
                      </div>
                    </div>
                  </div>


                  <div class="col-12 mt-3">
                    <div class="panel yellow-panel">
                      <div class="panel-header">
                        Realtime Graphic Performance
                      </div>
                      <div class="panel-content" style="padding-left:15px;padding-right:15px;">
                        <canvas id="lineChart" height="120"></canvas>
                      </div>
                    </div>
                  </div>

                  <div class="col-12 mt-3">
                    <div class="panel yellow-panel">
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
</main>
<script src="{{ url('templates/admin/js/plugins/chartJs/Chart.min.js') }}"></script>
<script>
  <?php if(!@$detail->joined){ ?>
  $(document).ready(function(){
    setTimeout(function(){
      $("#interestPopup").modal("show");
    },5000)
  });
  <?php } ?>

  function showAlert(action){
    console.log(action,"LOGS");
    <?php if(Session::get("masterqr")!=""){ ?>
      $("#alertModal").modal("show");
    <?php } ?>
    if(action!=undefined){
      window.open(action);
    }
  }

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


    function copyToClipboard(text) {
      var sampleTextarea = document.createElement("textarea");
      document.body.appendChild(sampleTextarea);
      sampleTextarea.value = text; //save main text in it
      sampleTextarea.select(); //select textarea contenrs
      document.execCommand("copy");
      document.body.removeChild(sampleTextarea);
      alert("Unique link copied");
   }

  function copyTheText(text){
      showAlert();
      copyToClipboard(text);
  }
</script>
