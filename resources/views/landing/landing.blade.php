<html>
    <head>
        <title></title>
        <meta charset="utf-8">
        <meta name="landingpage" content="width=device-width, initial-scale=1">
<!-- <link href="<?php echo e(url('templates/frontend/assets/css/bootstrap.min.css'));?>" rel="stylesheet"> -->
<!-- <script src="<?php echo e(url('templates/frontend/assets/js/vendor/bootstrap.min.js'));?>"></script> -->
<!-- <script src="<?php echo e(url('templates/frontend/assets/js/vendor/jquery-1.12.4.min.js'));?>"></script> -->
<!-- <script src="<?php echo e(url('templates/frontend/plugin/owlcarousel/owl.carousel.js'));?>"></script> -->
<!-- <script src="<?php echo e(url('templates/frontend/plugin/owlcarousel/owl.carousel.min.js'));?>"></script> -->

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <link href="<?php echo e(url('templates/frontend/assets/css/landing.css'));?>" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="<?php echo e(url('templates/admin/font-awesome/css/font-awesome.css')); ?>" rel="stylesheet">
    </head>
<body>
    <header>
        <nav class="navbar" role="navigation">
          <div>
            <img class="logo" src="<?php echo e(url('templates/frontend/assets/img/zeals_logo.png'));?>">
          </div>
          <button class="navbar-toggler pull-right nav-button" type="button" data-toggle="collapse" data-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
            <!-- <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>   -->
          </button>
          <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="nav navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Product</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Testimony</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Our Client</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Contact Us</a>
                </li>
            </ul>
          </div>
        </nav>
    </header>       
    <!-- <div class="carousel"> -->
      <div id="carouselLanding" class="carousel carousel-landing owl-theme slide" data-ride="carousel">
        <ol class="carousel-indicators">
          <li data-target="#carouselLanding" data-slide-to="0" class="active"></li>
          <li data-target="#carouselLanding" data-slide-to="1"></li>
          <li data-target="#carouselLanding" data-slide-to="2"></li>
        </ol>
        <div class="carousel-inner">
          <div class="item carousel-item-landing active">
            <div class="">
              <img class="" src="<?php echo e(url('templates/frontend/assets/img/carousel1.png'));?>" alt="First slide">
            </div>
            <div class="carousel-text">
              <div class="join">
                <span>Join Us Now!</span><br>
              </div>  
              <div class="market">
                <span>World #1 <br>Digital Marketing <br>platform</span><br>
              </div>
              <button class="btn" href="#"> Contact Us!</button>
            </div>
          </div>
          <div class="item carousel-item-landing">
          <div class="d-inline-block align-top col-5">
              <img class="d-block w-100" src="<?php echo e(url('templates/frontend/assets/img/main_slider.jpg'));?>" alt="Second slide">
            </div>
            <div class="carousel-text">
              <div class="join">
                <span>Join Us Now!</span><br>
              </div>  
              <div class="market">
                <span>World #1 <br>Digital Marketing <br>platform</span><br>
              </div>
              <button class="btn" href="#"> Contact Us!</button>
            </div>
          </div>
          <div class="item carousel-item-landing">
          <div class="d-inline-block align-top col-5">
              <img class="d-block w-100" src="<?php echo e(url('templates/frontend/assets/img/main_slider2.jpg'));?>" alt="Third slide">
            </div>
            <div class="carousel-text">
              <div class="join">
                <span>Join Us Now!</span><br>
              </div>  
              <div class="market">
                <span>World #1 <br>Digital Marketing <br>platform</span><br>
              </div>
              <button class="btn" href="#"> Contact Us!</button>
            </div>
          </div>
        </div>
        <!-- <a class="carousel-control-prev" href="#carouselLanding" role="button" data-slide="prev">
          <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
          <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselLanding" role="button" data-slide="next">
          <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
          <span class="sr-only">Next</span>
        </a> -->
      </div>
    <!-- </div> -->
    <div class="container">
        <div class="section section-product">
          <div class="section-title">
            <span>Our Product</span>
          </div>
          <div class="section-content">
            Integrated Digital Marketing Ecosystem that helps you connect with your audience.<br> Aliquam erat volutpat. Nunc vitae pharetra ipsum. Morbi ac aliquet risus.
          </div>
          <div class="section-body product-list">
            <div class="product-item product-item-left"> 
              <div class="product-item-title">Affiliate Marketing Platform</div> 
              <div class="product-item-img">
                <img src="<?php echo e(url('templates/frontend/assets/img/carousel1.png'));?>">
              </div>
              <div class="product-item-body">
                
                <div class="product-item-description">
                  Zeals Affiliate marketing are those who are socially active and are connected to your targeted real-person that will share Zeals ‘unique link’ to enhance exposure and conversion at the same time.
                </div>
              </div>
            </div>
            <div class="product-item product-item-right"> 
            <div class="product-item-title">Online to Offline Voucher</div>
              <div class="product-item-img">
                <img class="d-block" src="<?php echo e(url('templates/frontend/assets/img/carousel1.png'));?>">
              </div>
              <div class="product-item-body">
                <div class="product-item-description">
                  Our online-to-offline system enables you to create an actionable and measurable campaign at the same time, in one single platform through specifically generated QR for your campaign. 
                </div>
              </div>
            </div>
            <div class="product-item product-item-left"> 
                <div class="product-item-title">Social Media Listening</div> 
              <div class="product-item-img">
                <img src="<?php echo e(url('templates/frontend/assets/img/carousel1.png'));?>">
              </div>
              <div class="product-item-body">
                <div class="product-item-description">
                  Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially 
                </div>
              </div>
            </div>
            <div class="product-item product-item-right"> 
                <div class="product-item-title">Digital Marketing Consultant</div>
              <div class="product-item-img">
                <img class="d-block" src="<?php echo e(url('templates/frontend/assets/img/carousel1.png'));?>">
              </div>
              <div class="product-item-body">
                <div class="product-item-description">
                  Our Digital Marketing Consultant will assist designing digital strategies and providing solutions to help you achieve business goals through the implementation of Digital Marketing. 
                </div>
              </div>
            </div>
            <div class="product-item product-item-left"> 
                <div class="product-item-title">Social Media Scoring</div> 
              <div class="product-item-img">
                <img src="<?php echo e(url('templates/frontend/assets/img/carousel1.png'));?>">
              </div>
              <div class="product-item-body">
                <div class="product-item-description">
                  With Social Media Listening you will get hearing ability and strategic action by tracking online conversations related to your brand and industry to help you develop an effective solution that perfectly fits into your customer needs.
                </div>
              </div>
            </div>
            <div class="product-item product-item-right"> 
                <div class="product-item-title">Marketplace for influencer and buzzer</div>
              <div class="product-item-img">
                <img class="d-block" src="<?php echo e(url('templates/frontend/assets/img/carousel1.png'));?>">
              </div>
              <div class="product-item-body">
                <div class="product-item-description">
                  Our hand-picked influencers with strong engagement rate will take your campaigns to the next level.
                </div>
              </div>
            </div>
            <div class="product-item product-item-left"> 
                <div class="product-item-title">Awarding</div> 
              <div class="product-item-img">
                <img src="<?php echo e(url('templates/frontend/assets/img/carousel1.png'));?>">
              </div>
              <div class="product-item-body">
                <div class="product-item-description">
                  Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially 
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="section section-testimony">
          <div class="section-title">
            <span>Testimonials</span>
          </div>
          <div class="section-content">
            <span>
              Lorem ipsum dolor sit amet, consectetur adipiscing elit. In varius aliquet elit eu lobortis. Aliquam erat<br> volutpat. Nunc vitae pharetra ipsum. Morbi ac aliquet risus.
            </span>
          </div>
          <div class="section-body testimony" id="testimoni">
            <?php
              // if($campaigns){
              //   foreach ($campaigns as $key => $value) {
                for ($k=1;$k<=5;$k++){
                  ?>
                    <div class="col-4 item-testimoni d-inline-block">
                      <div class="panel-header text-dark-pink">
                      Lorem ipsum dolor sit amet, consectetur adipiscing elit. In varius aliquet elit eu lobortis.
                      </div>
                      <div class="media">
                        <img class="testimon-img" src="<?php echo e(url('templates/frontend/assets/img/testimon.jpg'));?>" alt="testimony">
                        <div class="media-body">
                          <span class="media-heading">Tunduh John</span>
                          <br>
                          <span class="media-content">Turu Expert No.<?php echo $k ?></span>
                        </div>
                      </div>
                      <div class="rating"></div>
                    </div>
                  <?php
                  }
              //   }
              // }
            ?>
          </div>
        </div>
        <div class="section section-clients">
          <div class="section-title">
            <span>our clients</span><br>
          </div>
          <div class="section-content">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. In varius aliquet elit eu lobortis. Aliquam erat<br> volutpat. Nunc vitae pharetra ipsum. Morbi ac aliquet risus.
          </div>
          <div class="section-body">
            <img class="d-inline-block client-img" src="<?php echo e(url('templates/frontend/client/img/ivosights.png'));?>" alt="client ">
            <img class="d-inline-block client-img" src="<?php echo e(url('templates/frontend/client/img/alpha.png'));?>" alt="client ">
            <img class="d-inline-block client-img" src="<?php echo e(url('templates/frontend/client/img/google_cloud.png'));?>" alt="client ">
            <img class="d-inline-block client-img" src="<?php echo e(url('templates/frontend/client/img/bfi_finance.png'));?>" alt="client ">
            <img class="d-inline-block client-img" src="<?php echo e(url('templates/frontend/client/img/bca_insurance.png'));?>" alt="client ">
          </div>
        </div>
        <div class="section section-contact">
          <div class="section-title">
            <span>contact us</span>
          </div>
          <div class="section-content">
            Get your free personalized demo
            <!-- sementara tembak ke API -->
          </div>
          <div class="section-body">
            <form action="apiv1/maildemo" method="post">
              <div class="row">
                <div class="form-group col-md-6 input">
                  <label for="fname"> Full Name</label><br>
                  <input type="text" id="fname" name="fullName">
                </div>
                <div class="form-group col-md-6 input">
                  <label for="cname"> Company Name</label><br>
                  <input type="text" id="cname" name="companyName">
                </div>
              </div>
              
              <div class="row">
                <div class="form-group col-md-6 input">
                  <label for="email"> Email</label><br>
                  <input type="email" id="email" name="email">
                </div>
                <div class="form-group col-md-6 input">
                  <label for="phone"> Phone Number</label><br>
                  <input type="text" id="phone" name="phoneNumber">
                </div>
              </div>

              <div class="row">
                <div class="form-group col-md-12 text-area input">
                  <label for="notes"> Notes</label><br>
                  <textarea type="text-area" id="notes" name="notes"></textarea>
                </div>
              </div>

              <div class="row">
                <button type="submit" class="btn btn-primary">Send</button>
              </div>
            </form>
            
          </div>
        </div>
    </div>
    <footer>
      <div class="row">
        <div class="foot-col">
          <div class="foot-title">
            Contact
          </div>
          <div class="foot-body">
              <a href="#">
                <i class="fa fa-phone fa-lg footer-icon"></i>
                +6281 1772823 <br>
              </a>
              <a href="#">
                <i class="fa fa-envelope fa-lg footer-icon"></i>
                support@zealsasia.com
              </a>
          </div>
        </div>
        <div class="foot-col">
          <div class="foot-title">
            Resources
          </div>
          <div class="foot-body">
            <a href="#"><span>API Integration</span><br></a>
            <a href="#"><span>Referral Program</span><br></a>
            <a href="#"><span>Affiliators</span><br></a>
            <a href="#"><span>Brand Platform</span><br></a>
            <a href="#"><span>Tutorials</span><br></a>
          </div>
        </div>
        <div class="foot-col">
          <div class="foot-title">
            Privacy
          </div>
          <div class="foot-body">
            <a href="#"><span>Terms & Services</span><br></a>
            <a href="#"><span>Policy Privacy</span><br></a>
            <a href="#"><span>FAQ</span><br></a>
          </div>
        </div>
        <div class="foot-col">
          <div class="foot-title">
            Partnership
          </div>
          <div class="foot-body">
            <a href="#"><span>Zeals Academy</span><br></a>
            <a href="#"><span>Zeals Campus</span><br></a>
            <a href="#"><span>Partnership Program</span><br></a>
          </div>
        </div>
      </div>
      <div class="row text-center">
        <a href="#"><i class="fa fa-facebook fa-5x footer-icon-sosmed"></i></a>
        <a href="#"><i class="fa fa-instagram fa-5x footer-icon-sosmed"></i></a>
        <a href="#"><i class="fa fa-twitter fa-5x footer-icon-sosmed"></i></a>
      </div>
      <div class="row reserv">
        2022 zealsasia. All Rights Reserved
      </div>
    </footer>
</body>
</html>