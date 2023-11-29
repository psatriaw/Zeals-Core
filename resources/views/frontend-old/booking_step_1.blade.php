<html>
<head>
    <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">

      <title><?=$title?></title>
      <meta property="og:title" content="<?=$title?>">
      <meta property="og:description" content="<?=$description?>">
      <meta property="og:keywords" content="<?=$keywords?>">

      <link rel="shortcut icon" type="image/x-icon" href="{{ url('templates/frontend/assets/img/favicon.png')}}">

      <link rel="stylesheet" href="{{ url('templates/frontend/assets/css/fontawesome.min.css') }}">
      <link rel="stylesheet" href="{{ url('templates/frontend/assets/css/themify-icons.css') }}">
      <link rel="stylesheet" href="{{ url('templates/frontend/assets/css/elegant-line-icons.css') }}">
      <link rel="stylesheet" href="{{ url('templates/frontend/assets/css/animate.min.css') }}">
      <link rel="stylesheet" href="{{ url('templates/frontend/assets/css/bootstrap.min.css') }}">
      <link rel="stylesheet" href="{{ url('templates/frontend/assets/css/slick.min.css') }}">
      <link rel="stylesheet" href="{{ url('templates/frontend/assets/css/slider.css') }}">
      <link rel="stylesheet" href="{{ url('templates/frontend/assets/css/magnific-popup.css') }}">
      <link rel="stylesheet" href="{{ url('templates/frontend/assets/css/nice-select.css') }}">
      <link rel="stylesheet" href="{{ url('templates/frontend/assets/css/odometer.min.css') }}">
      <link rel="stylesheet" href="{{ url('templates/frontend/assets/css/main.css') }}">
      <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;700&display=swap" rel="stylesheet">
      <script src="{{ url('templates/frontend/assets/js/vendor/modernizr-2.8.3-respond-1.4.2.min.js') }}"></script>
  </head>

  <body class="header-1 body-blue">
    <div class="site-preloader-wrap">
        <div class="spinner"></div>
    </div><!-- /.site-preloader-wrap -->

    <header class="header header-one">
        <div class="primary-header-one primary-header">
            <div class="container">
                <div class="primary-header-inner">
                    <div class="header-logo">
                        <a href="{{ url('') }}">
                            <img src="{{ url('templates/frontend/assets/img/logo_app.png')}}" alt="Logo"/>
                        </a>
                    </div><!-- /.header-logo -->
                    <div class="header-menu-wrap">
                        <ul class="dl-menu">
                            <li><a href="index.html">Home</a>
                                <ul>
                                    <li><a href="index.html">Homepage Default</a></li>
                                    <li><a href="index-2.html">Homepage Modern</a></li>
                                    <li><a href="index-3.html">Homepage Classic</a></li>
                                    <li><a href="index-4.html">Homepage Standard</a></li>
                                </ul>
                            </li>
                            <li><a href="#">Company</a>
                                <ul>
                                    <li><a href="about-us.html">About Us</a></li>
                                    <li><a href="about-company.html">About Company</a></li>
                                    <li><a href="services.html">Our Services</a></li>
                                </ul>
                            </li>
                            <li><a href="projects-3-col.html">Projects</a>
                                <ul>
                                    <li><a href="projects-3-col.html">Project 3 Col</a></li>
                                    <li><a href="projects-4-col.html">Project 4 Col</a></li>
                                    <li><a href="project-details.html">Project Details</a></li>
                                </ul>
                            </li>
                            <li><a href="#">Pages</a>
                                <ul>
                                    <li><a href="team.html">Cleaning Exparts</a></li>
                                    <li><a href="pricing-plans.html">Pricing Plans</a></li>
                                    <li><a href="faq.html">Help &amp; Faq's</a></li>
                                    <li><a href="404.html">404 Error</a></li>
                                </ul>
                            </li>
                            <li><a href="blog-grid.html">News</a>
                                <ul>
                                    <li><a href="blog-grid.html">Blog Grid</a></li>
                                    <li><a href="blog-classic.html">Blog Classic</a></li>
                                    <li><a href="blog-single.html">Blog Single</a></li>
                                </ul>
                            </li>
                            <li><a href="contact.html">Contact Us</a></li>
                        </ul>
                    </div><!-- /.header-menu-wrap -->
                    <div class="header-right">
                        <a class="header-btn" href="#">Book A Cleaner<span></span></a>
                        <!-- Burger menu -->
                        <div class="mobile-menu-icon">
                            <div class="burger-menu">
                                <div class="line-menu line-half first-line"></div>
                                <div class="line-menu"></div>
                                <div class="line-menu line-half last-line"></div>
                            </div>
                        </div>
                    </div><!-- /.header-right -->
                </div><!-- /.primary-header-one-inner -->
            </div>
        </div><!-- /.primary-header-one -->
    </header><!-- /.header-one -->

    <div class="container">
        <div class="row">
          <div class="col-12 text-center p-custom top20">
              <p>Hai <strong>Mr/Mrs</strong>! </p><p> Please Enjoy Your Experience with Us!</p>
          </div>
        </div>

        <div class="row main-icon top30">
          <div class="col-4  text-center">
            <img src="{{ url('templates/frontend/assets/img/icon_address_active.png')}}">
            <p>PICK YOUR ADDREES</p>
          </div>

          <div class="col-4 text-center">
            <img src="{{ url('templates/frontend/assets/img/icon_service_inactive.png')}}">
            <p>SELECT SERVICES</p>
          </div>

          <div class="col-4 text-center">
            <img src="{{ url('templates/frontend/assets/img/icon_appointment_inactive.png')}}">
            <p>MAKE AN APPOINTMENT</p>
          </div>
        </div>

        <div class="row">
          <div class="col-12 text-center p-custom top20">
              <p>Please define your location to make sure our service available on your area</p>
          </div>
          <div class="col-12 text-center form-group">
            <textarea name="address" class="form-control top20" placeholder="Your Address" rows="2"></textarea>
          </div>
          <div class="col-12">
              <div id="map-address">
              </div>
          </div>
          <div class="col-12 text-center top20 text-center">
            <button type="submit" name="submit" class="btn btn-submit">Select Service <i class='fa fa-angle-right'></i></button>
          </div>
        </div>
    </div>


    <script src="{{ url('templates/frontend/assets/js/vendor/jquery-1.12.4.min.js')}}"></script>
    <script src="{{ url('templates/frontend/assets/js/vendor/popper.min.js')}}"></script>
		<script src="{{ url('templates/frontend/assets/js/vendor/bootstrap.min.js')}}"></script>
    <script src="{{ url('templates/frontend/assets/js/vendor/waypoints.min.js')}}"></script>
		<script src="{{ url('templates/frontend/assets/js/vendor/slick.min.js')}}"></script>
    <script src="{{ url('templates/frontend/assets/js/vendor/headroom.min.js')}}"></script>
    <script src="{{ url('templates/frontend/assets/js/vendor/jquery.smoothscroll.min.js')}}"></script>
    <script src="{{ url('templates/frontend/assets/js/vendor/jquery.magnific-popup.min.js')}}"></script>
		<script src="{{ url('templates/frontend/assets/js/vendor/jquery.ajaxchimp.min.js')}}"></script>
		<script src="{{ url('templates/frontend/assets/js/vendor/jquery.mb.YTPlayer.min.js')}}"></script>
		<script src="{{ url('templates/frontend/assets/js/vendor/odometer.min.js')}}"></script>
		<script src="{{ url('templates/frontend/assets/js/vendor/jquery.nice-select.min.js')}}"></script>
		<script src="{{ url('templates/frontend/assets/js/vendor/simpleParallax.min.js')}}"></script>
		<script src="{{ url('templates/frontend/assets/js/vendor/wow.min.js')}}"></script>
		<script src="{{ url('templates/frontend/assets/js/contact.js')}}"></script>
    <script src="{{ url('templates/frontend/assets/js/appointment.js')}}"></script>
		<script src="{{ url('templates/frontend/assets/js/main.js')}}"></script>
  </body>
</html>
