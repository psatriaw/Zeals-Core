<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title><?=$detail->campaign_title?> | Zeals Asia - Integrated Digital Marketing Platform</title>
    <meta property="og:title" content="<?=$detail->campaign_title?>">
    <meta property="og:description" content="<?=$detail->campaign_description?>">
    <meta property="og:keywords" content="<?=$detail->campaign_description?>">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?=Request::url()?>">
    <meta property="og:title" content="<?=$detail->campaign_title?>">
    <meta property="og:description" content="<?=$detail->campaign_description?>">
    <?php $url = url($detail->photos); ?>
    <meta property="og:image" content="<?=$url?>">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?=Request::url()?>">
    <meta property="twitter:title" content="<?=$detail->campaign_title?>">
    <meta property="twitter:description" content="<?=$detail->campaign_description?>">
    <meta property="twitter:image" content="<?=$url?>">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ url('templates/admin/img/favicon.png')}}">

    <!-- Bootstrap core CSS -->

    <link rel="stylesheet" href="{{ url('templates/newzeals/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ url('templates/newzeals/assets/css/style.css') }}">
    <link href="{{ url('templates/admin/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
    <script src="{{ url('templates/newzeals/assets/js/vendor/jquery-1.12.4.min.js')}}"></script>
    <script src="{{ url('templates/admin/js/bootstrap.min.js') }}"></script>
    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }


      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>
    <!-- Custom styles for this template -->
    <link href="album.css" rel="stylesheet">
  </head>
  <body>
    <div class="container-fluid dashboard">
      <div class="row justify-content-center">
        <div class="col-xs-6 col-md-6">
          <?php
            $campaigntypes = array(
              "banner"  => "AMP",
              "o2o"     => "O2O",
              "shopee"  => "AMP"
            );
          ?>
          <main role="main" class="main-content">
                  <div class="mobile-nopadding">
                    <div class="album-content">
                      <div class="row">

                        <div class="col-12">
                          <div class="row">
                            <div class="col-12 col-md-8">
                              <h1 class="text-black"><?=$detail->campaign_title?></h1>
                            </div>
                            <div class="col-12 col-md-4 text-right">

                                <a href="{{ url('campaign/detail/'.$detail->campaign_link) }}" class="btn btn-primary btn-block btn-xl">Interested? Join Now!</a>
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
                              <p class="text-right mt-2"><i class="fa fa-calendar"></i> valid until <?=date("M d",strtotime($detail->end_date))?><sup><?=date("S",strtotime($detail->end_date))?></sup> <?=date("Y",strtotime($detail->end_date))?></p>
                            </div>
                            <div class="col-12 col-sm-12">
                              <?php
                                $banner = $detail->photos;
                              ?>
                              <img src="<?=url($banner)?>" class="img img-main mb-3">

                              <span class="pull-right label label-white"><?=$campaigntypes[@$detail->campaign_type]?></span>

                              <div class="panel">
                                <h3>Description</h3>
                                <p class="mt-3">
                                  <?=nl2br($detail->campaign_description)?>
                                </p>
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
    </div>
  </body>
    <script>
      function openMenu(){
        console.log("yes");
        $(".left-sidebar").addClass("open");
      }

      function closeMenu(){
        $(".left-sidebar").removeClass("open");
      }
    </script>
  </body>
</html>
