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
          @include('frontend.menu_sidebar')
        </div>
        <div class="col-12 col-md-9 mobile-nopadding">
          <div class="album-content">
            <h3 class="text-black mb-3">Campaigns</h3>
            <div class="row">
              <div class="col-sm-12">
                <!-- Slideshow container -->
                <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                  <div class="carousel-inner">
                    <?php
                    if(sizeof($banners)){
                      foreach ($banners as $key => $value) {
                        ?>
                        <a href="{{ url($value->link) }}">
                          <div class="carousel-item active">
                            <img class="d-block w-100" src="<?=$value->path?>" alt="First slide">
                            <div class="carousel-caption d-none d-md-block">
                              <h5><?=$value->description?></h5>
                            </div>
                          </div>
                        </a>
                        <?php
                      }
                    }
                    ?>

                  </div>
                  <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                  </a>
                  <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                  </a>
                </div>

                <br>
                <!--
                <div class="">
                  <a class="btn mb-1 <?=(""==$selected_category)?"btn-info":"btn-outline-secondary"?>" href="<?= url('campaign') ?>">All</a>
                  <?php
                   foreach ($categories as $key => $val) {
                   ?>
                     <a class="btn mb-1 <?=($val->id_sektor_industri==$selected_category)?"btn-info":"btn-outline-secondary"?>" href="<?= url('campaign/'.$val->id_sektor_industri) ?>"><?= $val->nama_sektor_industri ?></a>
                   <?php } ?>
                   <a href="{{ url('/profile/preferences') }}" class="pull-right btn btn-secondary"><i class="fa fa-cogs"></i> edit your preferences</a>
                </div>
                -->
                <?php
                  $namacat = "";
                  foreach ($categories as $key => $val) {
                    if($val->id_sektor_industri==$selected_category){
                      $namacat = $val->nama_sektor_industri;
                      break;
                    }
                  }
                ?>
                <div class="dropdown">
                    <button class="btn btn-secondary btn-block dropdown-toggle text-justify" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <?=($selected_category=="")?"Categories":$namacat?>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <?php
                            foreach ($categories as $key => $val) {
                              ?><a class="dropdown-item" href="<?= url('campaign/'.$val->id_sektor_industri) ?>"><?= $val->nama_sektor_industri ?></a><?php
                            }
                      ?>
                    </div>
                  </div>
              </div>

            </div>
            <div class="row mt-3">
              <?php
                if(sizeof($campaigns)){
                  foreach ($campaigns as $key => $value) {

                    ?>
                    <div class="col-12 col-sm-6 col-md-6 mb-3">
                      <div class="item">
                        <div class="panel">
                          <div class="panel-content" style="min-height:150px;padding-top:0px;border-bottom-right-radius: 8px;border-bottom-left-radius: 8px;background: #f7f7f7aa;padding-bottom: 20px;">
                            <div class="" style="height:150px;overflow:hidden;margin-bottom:15px;">
                              <img src="<?=url($value->photos)?>" style="max-width: 100%;">
                            </div>
                            <div style="padding-left:15px;padding-right:15px;">
                              <h4 style="font-size:18px;">
                                <?=$value->campaign_title?>
                              </h4>
                              <div class="row">
                                <div class="col-4">
                                  <span class="label label-pink-outline"><?=$campaigntypes[@$value->campaign_type]?></span>
                                  <?php if(@$value->joined){ ?>
                                    <span class="label label-pink-outline">Joined</span>
                                  <?php } ?>
                                </div>
                                <div class="col-8">
                                  <div class="text-right text-small"><i class="fa fa-calendar"></i> valid until <?=date("M d",strtotime($value->end_date))?><sup><?=date("S",strtotime($value->end_date))?></sup> <?=date("Y",strtotime($value->end_date))?></div>
                                </div>
                              </div>
                            </div>
                            <div style="padding-left:15px;padding-right:15px;margin-top:10px;">
                              <small><a href="{{ url('campaign/detail/'.$value->campaign_link) }}" class="text-black btn btn-primary btn-sm text-white" style="height:32px !important;">open campaign <i class="fa fa-angle-right"></i> </a></small>
                            </div>
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
                    <img src="{{ url('templates/frontend/assets/img/no_data.png') }}">
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
<script src="{{ url('templates/admin/js/plugins/chartJs/Chart.min.js') }}"></script>
