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
<?php
  $statuses = array(
    "pending"   => "text-default",
    "rejected"  => "text-danger",
    "approved"  => "text-success",
    "invalid"   => "text-danger"
  );
?>
<main role="main">
  <div class="album pb-5 top30">
    <div class="container">
      <div class="row">
        <div class="col-12 col-md-3 mb-3">
          @include('frontend.menu_sidebar')
        </div>
        <div class="col-12 col-md-9 mobile-nopadding">
          <div class="album-content">
            <h3 class="text-black mb-3">My Wallet</h3>
            @include('backend.flash_message')
            <div class="row justify-content-md-center">
              <div class="col-12 col-md-4 mt-2">
                <div class="box-saldo">
                  Balance: Rp. <?=number_format(@$saldo,0)?>
                </div>
              </div>

              <div class="col-12 col-md-4 mt-2">
                <a href="{{ url('my-wallet/withdraw') }}" class="text-decoration-none">
                  <div class="box-saldo">
                    Witdraw Money
                  </div>
                </a>
              </div>
            </div>
            <div class="table-responsive">
              <table class="table table-log table-bordered mt-3">
                <tr>
                  <th>Withdrawal Code</th>
                  <th>Bank</th>
                  <th>Account Number</th>
                  <th>Account Name</th>
                  <th>Status</th>
                  <th>Requested</th>
                  <th>Released</th>
                </tr>
                <?php
                  if($withdrawals->count()){
                    foreach ($withdrawals as $key => $value) {
                      ?>
                      <tr>
                        <td><strong><?=$value->withdrawal_code?></strong></td>
                        <td><?=$value->nama_bank?></td>
                        <td><?=$value->nomor_rekening?></td>
                        <td><?=$value->nama_pemilik_rekening?></td>
                        <td class="text-<?=$statuses[$value->status]?>"><?=$value->status?></td>
                        <td class='text-right'>Rp. <?=number_format($value->total_pencairan,0,',','.')?></td>
                        <td class='text-right'>Rp. <?=number_format($value->total_pencairan-$value->fee,0,',','.')?></td>
                      </tr>
                      <?php
                    }
                  }else{
                    ?>
                    <tr>
                      <td colspan="6" class="text-center">No request found!</td>
                    </tr>
                    <?php
                  }
                ?>

              </table>
              <!--
              <div class="row">
                <div class="col-12 text-center">
                  <button type="button" class="btn btn-secondary">Load more</button>
                </div>
              </div>
              -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
<script src="{{ url('templates/admin/js/plugins/chartJs/Chart.min.js') }}"></script>
<script>
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
