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
        <h1>Withdrawal</h1>
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
            <div class="row">
              <div class="col-12 col-md-6">
                <h3 class="text-black">Balance</h3>
                <div class="box-saldo text-left">
                  Rp. <?=number_format(@$saldo,0)?>
                </div>
                <p class="campaign-description mt-3">
                  <?php
                    $contoh = 125000;
                  ?>
                  The maximum request of withdrawal is <strong> 1 x 24</strong>  hours. <br><br>
                  You will be charged for administration fee about <strong> Rp.<?=number_format($fee,0,',','.')?></strong>  per withdrawal request.<br><br>
                  <strong>for example</strong>: <br>
                  If your total amount of withdrawal is <strong> Rp. <?=number_format($contoh,0,',','.')?></strong> due to administration fee, we will transfer
                  <strong> Rp. <?=number_format($contoh - $fee,0,',','.')?></strong>  to your bank account.
                </p>
              </div>

              <div class="col-12 col-sm-6 col-md-6">
                <h3 class="text-black">Withdrawal Form</h3>
                <?php echo Form::model($detail,['url' => url('my-wallet/withdraw/store'), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate', "enctype" => "multipart/form-data"]); ?>

                <div class="form-group mt-3 <?php echo e(($errors->has('total_pencairan')?"has-error":"")); ?>">
                  <div class="input-group">
                    <span class="input-group-addon" style='background: #5db6f2;padding: 7px 15px;color: #fff;'>Rp</span>
                    <?php echo Form::text('total_pencairan', null, ['class' => 'form-control','placeholder' => 'Total Amount']); ?>

                  </div>
                    <?php echo $errors->first('total_pencairan', '<p class="bg-danger p-xs b-r-sm m-t text-white p-1">:message</p>'); ?>

                </div>
                <div class="form-group <?php echo e(($errors->has('nama_bank')?"has-error":"")); ?>">
                    <?php echo Form::select('nama_bank',$banks, null, ['class' => 'form-control','readonly','disabled' => true,'placeholder' => 'Select Bank']); ?>

                    <?php echo $errors->first('nama_bank', '<p class="bg-danger p-xs b-r-sm m-t text-white p-1">:message</p>'); ?>

                </div>
                <div class="form-group <?php echo e(($errors->has('nama_pemilik_rekening')?"has-error":"")); ?>">
                    <?php echo Form::text('nama_pemilik_rekening', null, ['class' => 'form-control','readonly','placeholder' => 'Account Name']); ?>

                    <?php echo $errors->first('nama_pemilik_rekening', '<p class="bg-danger p-xs b-r-sm m-t text-white p-1">:message</p>'); ?>

                </div>
                <div class="form-group <?php echo e(($errors->has('nomor_rekening')?"has-error":"")); ?>">
                    <?php echo Form::text('nomor_rekening', null, ['class' => 'form-control','readonly','placeholder' => 'Account Number']); ?>

                    <?php echo $errors->first('nomor_rekening', '<p class="bg-danger p-xs b-r-sm m-t text-white p-1">:message</p>'); ?>

                </div>


                <div class="form-group mt-5">
                    <div class="text-center">
                      <div id="updatealert"></div>
                      <button class="btn btn-primary shadow-none" type="submit" id="btnupdate" >SEND REQUEST</button>
                    </div>
                </div>
                <?php echo Form::close(); ?>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/myzeals/resources/views/frontend/withdraw.blade.php ENDPATH**/ ?>