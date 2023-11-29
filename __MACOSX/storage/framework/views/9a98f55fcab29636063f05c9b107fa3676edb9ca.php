<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, max-scale=1">
    <title><?=$title?></title>
    <meta property="og:title" content="<?=$title?>">
    <meta property="og:description" content="<?=$description?>">
    <meta property="og:keywords" content="<?=$keywords?>">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?=Request::url()?>">
    <meta property="og:title" content="<?=$title?>">
    <meta property="og:description" content="<?=$description?>">
    <?php if(@$image){ ?>
    <meta property="og:image" content="https://metatags.io/assets/meta-tags-16a33a6a8531e519cc0936fbba0ad904e52d35f34a46c97a2c9f6f7dd7d336f2.png">
    <?php } ?>
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?=Request::url()?>">
    <meta property="twitter:title" content="<?=$title?>">
    <meta property="twitter:description" content="<?=$description?>">
    <?php if(@$image){ ?>
    <meta property="twitter:image" content="https://metatags.io/assets/meta-tags-16a33a6a8531e519cc0936fbba0ad904e52d35f34a46c97a2c9f6f7dd7d336f2.png">
    <?php } ?>

    <link rel="shortcut icon" type="image/x-icon" href="<?php echo e(url('templates/admin/img/favicon.png')); ?>">

    <!-- Bootstrap core CSS -->

    <link rel="stylesheet" href="<?php echo e(url('templates/frontend/assets/css/bootstrap.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(url('templates/frontend/assets/css/style.css')); ?>">
    <link href="<?php echo e(url('templates/admin/font-awesome/css/font-awesome.css')); ?>" rel="stylesheet">
    <script src="<?php echo e(url('templates/frontend/assets/js/vendor/jquery-1.12.4.min.js')); ?>"></script>
    <script src="<?php echo e(url('templates/admin/js/bootstrap.min.js')); ?>"></script>
    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }

      <?php if(@$login->id_user==""){ ?>
      body{
        background: #28505b url("<?php echo e(url('templates/admin/img/bg-06.png')); ?>") no-repeat;
        background-size: cover;
      }
      <?php } ?>
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
    <header <?php if(Request::segment(1)==""){ echo "style='position: absolute;width: 100%;'"; } ?>>
      <div class="navbar navbar shadow-sm">
        <div class="container d-flex justify-content-between mobile-nopadding">
          <a href="<?php echo e(url('')); ?>" class="navbar-brand d-flex align-items-center">
          <?php if(@$login->id_user!=""){ ?>
    			  <?php if(Request::segment(1)==""){ ?>
                <img src="<?php echo e(url('templates/admin/img/logo.jpg')); ?>" alt="Logo" class="main-logo">
    			  <?php }else{ ?>
    			       <img src="<?php echo e(url('templates/admin/img/logo.jpg')); ?>" alt="Logo" class="main-logo">
    			  <?php } ?>
			    <?php } ?>
          </a>
          <?php if(@$login){ ?>
          <div class="head-menu text-right" style="width:520px;">
            <div class="row">
              <div class="col-6 col-sm-7">
                <div class="box-saldo">
                  Balance: Rp. <?=number_format(@$saldo,0)?>
                </div>
              </div>
              <div class="col-6 col-sm-5">
                <?php
                  if($login->avatar==""){
                    $avatar = "https://icons.iconarchive.com/icons/papirus-team/papirus-status/512/avatar-default-icon.png";
                  }else{
                    $avatar = url($login->avatar);
                  }
                ?>
                <dicv class="box-avatar">
                  <div class="btn-group btn-none-decor" role="group" style="max-width:110px;text-overflow:ellipsis;">
                    <button id="btnGroupDrop1" type="button" class="btn btn-custom btn-none-decor dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      Hi, <?=$login->first_name?>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                      <a class="dropdown-item" href="<?php echo e(url('signout')); ?>">Signout</a>
                      <a class="dropdown-item" href="<?php echo e(url('profile')); ?>">Update Profile</a>
                    </div>
                  </div>

                  <img src="<?=$avatar?>" style="width:49px;height:49px;float:right;">
                </div>
              </div>
            </div>
          </div>
          <?php } ?>
          <button id="head-menu-button" class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
            <span class="fa fa-list"></span>
          </button>
        </div>
      </div>
    </header>

    <?=$content?>

    <?php if(Request::segment(1)!="" && @$login->id_user!=""){ ?>
    <footer class="text-white">
      <div class="container">
        <div class="row footer-area">
          <div class="col-8">copyright - zeals.asia &copy; 2021</div>
          <div class="col-4 text-right text-black">
            <!--<i class="fa fa-instagram"></i> @
            -->
          </div>
        </div>
      </div>
    </footer>
    <?php } ?>
    <!--
    <div style="position:fixed;right: 10px;bottom: 0px;">
      <a href="https://api.whatsapp.com/send?phone=<?=$official_phone_number?>&text=Hai">
          <img src="https://www.freepngimg.com/thumb/whatsapp/77239-instant-messaging-logo-whatsapp-message-android-thumb.png" style="width: 60px;height: 60px;">
      </a>
    </div>
    -->
  </body>
</html>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/myzeals/resources/views/frontend/body.blade.php ENDPATH**/ ?>