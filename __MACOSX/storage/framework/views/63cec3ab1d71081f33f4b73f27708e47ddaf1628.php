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

<main role="main">

  <section class="custom-jumbotron text-center">
    <div class="container">
      <div class="superline"></div>
      <div class="custom-container">
        <h1>Frequently Asked Question</h1>
      </div>
    </div>
  </section>

  <div class="album pb-5 top30">
    <div class="container">
      <div class="row">
        <div class="col-3">
          <?php echo $__env->make('frontend.menu_sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div class="col-9">
          <div class="album-content">
            <div id="accordion">
              <div class="card">
                <div class="card-header" id="headingOne">
                  <h5 class="mb-0">
                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                      Bagaimanakah cara kerja Affiliate marketing?
                    </button>
                  </h5>
                </div>

                <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                  <div class="card-body">
                    Menyebarkan campaign melalui sosial media atau unik-link. Affiliate akan mendapatkan fee/komisi sesuai dengan T&C (term & Condition) masing-masing campaign. Misalnya komisi dari mengisi form sebuah layanan atau komisi pembelian dari sebuah produk yang disebarkan.
                  </div>
                </div>
              </div>
              <div class="card">
                <div class="card-header" id="headingTwo">
                  <h5 class="mb-0">
                    <button class="btn btn-link collapsed show" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                      Bagaimana mendaftar sebagai marketing affiliate di platform zeals?
                    </button>
                  </h5>
                </div>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                  <div class="card-body">
                    Langsung kunjungi web: https://app.zeals.asia/ dan lengkapi Form Registrasi
                  </div>
                </div>
              </div>
              <div class="card">
                <div class="card-header" id="headingThree">
                  <h5 class="mb-0">
                    <button class="btn btn-link collapsed " data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                      Bagaimana saya bisa ikut mendapatkan uang dari program afiliasi?
                    </button>
                  </h5>
                </div>
                <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                  <div class="card-body">
                    Klik "Campaign" pada menu di sebelah kiri, lalu pilih Campaign yang akan teman ikuti, klik "Info Detail" , baca "Campaign Brief" untuk melihat detail intruksi dan pilih "Share Link" untuk mendapatkan unik link yang dapat dibagikan lewat media sosial, media chat, email dan lainnya. Untuk yang ikon media sosial ataupun media chat nya tidak tertera disitu, silahkan meng-hilite link yang berada di atas ikon-ikon media sosial, lalu ‘copy’ dan ‘paste’ di tempat yang teman-teman tuju atau inginkan yang ikonnya tidak ada di kolom “Share Link” tersebut.
                  </div>
                </div>
              </div>

              <div class="card">
                <div class="card-header" id="headingThree">
                  <h5 class="mb-0">
                    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                      Bagaimana saya mencairkan dana?
                    </button>
                  </h5>
                </div>
                <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
                  <div class="card-body">
                    1. Kilk tombol " My Wallet" 2. Pastikan nama bank, detail bank account sudah terisi dengan benar pada bagian "Payment Details" 3. Pilih menu "Withdrawal" dan konfirmasi untuk withdrawal
                  </div>
                </div>
              </div>

              <div class="card">
                <div class="card-header" id="headingFive">
                  <h5 class="mb-0">
                    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                      Berapa lamakah proses pencairan dana?
                    </button>
                  </h5>
                </div>
                <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion">
                  <div class="card-body">
                    Permintaan pencairan dana yang dilakukan sebelum hari Selasa, maka dana akan dicairkan pada hari Jumat pada minggu yang sama. Apabila permintaan pencairan dana dilakukan setelah hari Selasa, maka dana akan dicairkan pada hari Jumat minggu selanjutnya.
                  </div>
                </div>
              </div>

              <div class="card">
                <div class="card-header" id="headingSix">
                  <h5 class="mb-0">
                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                      Bagaimana caranya mendapatkan 'Unik Link' sehingga kami dapat membagikannya kepada jaringan pertemanan kami?
                    </button>
                  </h5>
                </div>
                <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordion">
                  <div class="card-body">
                    Untuk mendapatkan unik link, kami menyarankan langkah-langkah berikut: 1. Klik "Campaign" pada menu di sebelah kiri, 2. Lalu pilih Campaign yang akan teman ikuti, klik "Info Detail atau Get Code" , baca "Campaign Brief" untuk melihat detail instruksi dan berapa komisi yang dapat diterima jika unik link yang teman sebar menghasilkan transaksi penjualan, 3. Setelah itu pilih "Share Link" untuk mendapatkan unik link yang dapat dibagikan lewat media sosial, media chat, email dan lainnya. Ada dua cara mendapatkan unik link nya, pertama dengan meng-klik ikon media sosial atau media chat yang diinginkan, atau kedua, dengan cara meng-hilite link yang berada di atas ikon-ikon media sosial, lalu ‘copy’ dan ‘paste’ di tempat yang teman-teman tuju atau inginkan, misalnya di feed, DM atau Story nya IG. Atau mungkin di Line, TikTok dan lainnya, yang ikonnya tidak ada di kolom “Share Link” tersebut. 4. Unik link juga bisa di dapat dengan cara meng-hilite link yang berada di bawah kolom “Link” lalu ‘copy’ dan ‘paste’ di tempat yang teman-teman tuju atau inginkan, misalnya di feed, DM atau Story nya IG. Atau mungkin di Line, TikTok dan lainnya

                  </div>
                </div>
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
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/myzeals/resources/views/frontend/faq.blade.php ENDPATH**/ ?>