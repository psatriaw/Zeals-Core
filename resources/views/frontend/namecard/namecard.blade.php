<div class="container">
  <div class="row justify-content-center">
    <div class="col-12 col-md-7 no-padding">
      <div class="namecard">
        <div class="name">
          <?=($full_name);?>
        </div>

        <div class="job-desk">
          <?=($job_desk);?>
        </div>

        <div class="email">
          <i class="fa fa-envelope"></i> <?=($email);?>
        </div>

        <div class="phone">
            <i class="fa fa-phone"></i> <?=($phone);?>
        </div>

        <div class="address">
            <i class="fa fa-map-marker"></i> <?=($address);?>
        </div>

        <div class="qr">
          <img  src="{{ url('templates/namecard/qr/'.$slug.'.png') }}">
        </div>
      </div>
    </div>
  </div>
  <div class="row justify-content-center">
    <div class="col-12 text-center">
      <a href="tel:<?=$phone?>" class="btn btn-primary mt-5 btn-lg">Call me</a>
    </div>
  </div>
</div>
