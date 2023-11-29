<!-- carousel -->
<!-- owl carousel -->

<main role="main" class="main-content">
  <?php
    $campaigntypes = array(
      "banner"  => "AMP",
      "o2o"     => "O2O",
      "shopee"  => "AMP",
      "event"   => "EVENT",
    );
  ?>
      <div class="row">
        <div class="col-12 mobile-nopadding">
            <div class="album-content">
                <?php
              $master_qr = Session::get('masterqr');
              if($master_qr!=""){
                ?>
                <div class="alert alert-success p-3">
                    <h1 style="color:#007d02;">Congrats <i class='fa fa-exclamation'></i> </h1>
                    <p>
                        Hi, Welcome to <a href="https://zeals.asia" target="_blank">Zeals</a>!<br>
                        Choose your campaign and earn your money now!<br><br>
                        Don't forget to update <a href="{{ url('profile') }}">your profile</a> to make your balance
                        withdrawable!
                    </p>
                </div>
                <?php
              }
            ?>
                <h1 class="text-black mb-3">Campaigns</h1>
                <div class="row">
                    <div class="col-sm-12 mb-3">
                        <!-- Slideshow container -->

                        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                            <ol class="carousel-indicators">
                                <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                                <li data-target="#carouselExampleIndicators" data-slide-to="1" class=""></li>
                                <li data-target="#carouselExampleIndicators" data-slide-to="2" class=""></li>
                            </ol>
                            <div class="carousel-inner">

                                <?php
                    if(sizeof($banners)){
                      foreach ($banners as $key => $value) {
                        $link = $value->link
                        ?>
                                <div class="carousel-item <?= $key == 1 ? 'active' : '' ?>"
                                    onclick="window.location = '<?= $link ?>';">
                                    <img class="d-block w-100" src="<?= $value->path ?>" alt="First slide">
                                    <div class="carousel-caption d-none d-md-block">
                                        <h5><?= $value->description ?></h5>
                                    </div>
                                </div>
                                <?php
                      }
                    }
                    ?>
                            </div>
                            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button"
                                data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button"
                                data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>

                        <br>
                        <h3>Running Campaign</h3>
                        <div class="">
                            <a class="btn mb-1 <?= '' == $selected_category ? 'btn-label-primary' : 'btn-label-white' ?>"
                                href="<?= url('campaign') ?>">All</a>
                            <?php
                   foreach ($categories as $key => $val) {
                   ?>
                     <a class="btn mb-1 <?=($val->id_sektor_industri==$selected_category)?"btn-label-primary":"btn-label-white"?>" href="<?= url('campaign/'.$val->id_sektor_industri) ?>"><?= $val->nama_sektor_industri ?></a>
                   <?php } ?>
                   <!-- <a href="{{ url('/profile/preferences') }}" class="pull-right btn btn-label-secondary"><i class="fa fa-cogs"></i> custom preferences</a> -->
                </div>

                <?php
                  $namacat = "";
                  foreach ($categories as $key => $val) {
                    if($val->id_sektor_industri==$selected_category){
                      $namacat = $val->nama_sektor_industri;
                      break;
                    }
                  }
                ?>

              </div>
            </div>

            <div class="mb-2">
              <form method="GET">
                <div class="input-group">
                  <input type="text" class="form-control" name="keyword" placeholder="cari campaign disini.." value="<?=@$_GET['keyword']?>">
                  <span class="input-group-addon" style="padding-top:10px;padding-right:15px;">
                    <i class="fa fa-search"></i>

                  </span>
                </div>
              </form>
            </div>

            <div class="row custom-mobile-row">
              <?php
                if(sizeof($campaigns)){
                  foreach ($campaigns as $key => $value) {

                    if(!is_file(url($value->photos))){
                      //$value->photos = 'https://www.match2one.com/wp-content/uploads/2020/10/banner_sizes.jpg';
                    }

                    ?>
                    <div class="col-6 col-sm-6 col-md-4 mb-3 item-campaign">
                      <a href="{{ url('campaign/detail/'.$value->campaign_link) }}">
                        <div class="item">
                          <div class="panel no-padding">
                            <div class="panel-content" style="padding-top:0px;border-bottom-right-radius: 8px;border-bottom-left-radius: 8px;background: #f7f7f7aa;">
                              <div class="item-campaign-img" style="overflow:hidden;margin-bottom:15px;">
                                <img src="<?=url($value->photos)?>" style="width: 100%;">
                              </div>
                              <div class="panel-custom-content">
                                <!-- <div class="up-to">IDR <?=number_format((@$value->budget>0)?@$value->budget:10000000)?></div> -->
                                <h4>
                                  <?=$value->campaign_title?>
                                </h4>
                                <div class="row">
                                  <div class="col-12">
                                    <!--<span class="label label-pink-outline"><?=$campaigntypes[@$value->campaign_type]?></span>
                                    -->
                                                    <p>
                                                        <i class="fa fa-calendar"></i> valid until
                                                        <?= date('M d', strtotime($value->end_date)) ?><sup><?= date('S', strtotime($value->end_date)) ?></sup>
                                                        <?= date('Y', strtotime($value->end_date)) ?>
                                                    </p>
                                                    <?php if(@$value->joined){ ?>
                                                    <!--<span class="label label-pink-outline">Joined</span>-->
                                                    <?php } ?>

                                                    <a href="{{ url('campaign/detail/' . $value->campaign_link) }}"
                                                        class="pull-right bullet-blue"><i class="fa fa-angle-right"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
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
                        <img src="{{ url('templates/newzeals/assets/img/no_data.png') }}">
                        <h3>No campaign found!</h3>
                        <p>Please check your preferences.</p><br>
                        <p>Or please try again to reload this page</p>
                    </div>
                    <?php
                }
              ?>

                </div>
            </div>
            <div class="row">

            </div>
        </div>
    </div>
</main>
<script src="{{ url('templates/admin/js/plugins/chartJs/Chart.min.js') }}"></script>
<script>
    $('.carousel').carousel();
</script>
