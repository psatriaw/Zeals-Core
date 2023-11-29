<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<main role="main">

  <section class="custom-jumbotron text-center">
    <div class="container">
      <div class="custom-container">
        <h1>PROFILE</h1>
      </div>
    </div>
  </section>


  <?php

    if($detail->avatar==""){
      $avatar = "https://icons.iconarchive.com/icons/papirus-team/papirus-status/512/avatar-default-icon.png";
    }else{
      $avatar = url($detail->avatar);
    }
  ?>

  <div class="album pb-5 top30">
    <div class="container">
      <div class="row">
        <div class="col-3">
          <?php echo $__env->make('frontend.menu_sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div class="col-9">
          <div class="album-content">
            <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                Any Error Occured!
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            </div>
            <?php endif; ?>
            <?php echo $__env->make('backend.flash_message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php echo Form::model($detail,['url' => url('profile/update'), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate', "enctype" => "multipart/form-data"]); ?>

            <div class="row justify-content-md-center">
              <div class="col-12 col-md-6">
                <h3 class="text-black mb-3 text-center">Avatar</h3>
                <div class="form-group <?php echo e(($errors->has('first_name')?"has-error":"")); ?>">
                    <div class="text-center">
                      <img src="<?=($avatar)?>" class="img-avatar" id="avatar-preview"><br>
                      <button type="button" class="btn btn-primary" onclick="changeavatar()">Change Avatar</button>
                      <input type="file" id="avatar" name="avatar" style="display:none;">
                    </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12 col-sm-6 col-md-6">
                <h3 class="text-black mb-3 mt-5">Personal Information</h3>
                <div class="form-group <?php echo e(($errors->has('first_name')?"has-error":"")); ?>">
                    <input type="hidden" name="id_user" value="<?=$detail->id_user?>">
                    <?php echo Form::text('first_name', null, ['class' => 'form-control','placeholder' => 'First Name']); ?>

                    <?php echo $errors->first('first_name', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                </div>
                <div class="form-group <?php echo e(($errors->has('last_name')?"has-error":"")); ?>">
                    <?php echo Form::text('last_name', null, ['class' => 'form-control','placeholder' => 'Last Name']); ?>

                    <?php echo $errors->first('last_name', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                </div>
                <div class="form-group <?php echo e(($errors->has('email')?"has-error":"")); ?>">
                    <?php echo Form::text('email', null, ['class' => 'form-control','placeholder' => 'Email']); ?>

                    <?php echo $errors->first('email', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                </div>

                <div class="form-group <?php echo e(($errors->has('username')?"has-error":"")); ?>">
                    <?php echo Form::text('username', null, ['class' => 'form-control','placeholder' => 'Username']); ?>

                    <?php echo $errors->first('username', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                </div>

                <div class="form-group <?php echo e(($errors->has('phone')?"has-error":"")); ?>">
                    <?php echo Form::text('phone', null,['class' => 'form-control','placeholder' => 'Phone']); ?>

                    <?php echo $errors->first('phone', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                </div>
                <div class="form-group <?php echo e(($errors->has('id_pekerjaan')?"has-error":"")); ?>">
                    <?php echo Form::select('id_pekerjaan', $pekerjaans,null, ['class' => 'form-control select2','placeholder' => 'Select Job']); ?>

                    <?php echo $errors->first('id_pekerjaan', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                </div>

                <div class="form-group <?php echo e(($errors->has('id_wilayah')?"has-error":"")); ?>">
                    <?php echo Form::select('id_wilayah', $wilayah,null, ['class' => 'form-control select2','placeholder' => 'Select Domisili']); ?>

                    <?php echo $errors->first('id_wilayah', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                </div>

                <h3 class="text-black mb-3 mt-5">Account Security</h3>
                <div class="form-group <?php echo e(($errors->has('n_password')?"has-error":"")); ?>">
                    <?php echo Form::password('n_password',['class' => 'form-control','placeholder' => 'Password','type' => 'password']); ?>

                    <?php echo $errors->first('n_password', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                </div>

                <div class="form-group <?php echo e(($errors->has('c_password')?"has-error":"")); ?>">
                    <?php echo Form::password('c_password', ['class' => 'form-control','placeholder' => 'Retype Password','type' => 'password']); ?>

                    <?php echo $errors->first('c_password', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                    <p class='help-block text-small mt-2'>Let it blank if you do not want to change your password account</p>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-6">
              <h3 class="text-black mb-3 mt-5">Bank Account</h3>
              <div class="form-group <?php echo e(($errors->has('nama_bank')?"has-error":"")); ?>">
                  <?php echo Form::select('nama_bank', $bank,null, ['class' => 'form-control select2','placeholder' => 'Bank Name']); ?>

                  <?php echo $errors->first('nama_bank', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

              </div>
              <div class="form-group <?php echo e(($errors->has('nomor_rekening')?"has-error":"")); ?>">
                  <?php echo Form::text('nomor_rekening', null, ['class' => 'form-control','placeholder' => 'Account Number']); ?>

                  <?php echo $errors->first('nomor_rekening', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

              </div>
              <div class="form-group <?php echo e(($errors->has('nama_pemilik_rekening')?"has-error":"")); ?>">
                  <?php echo Form::text('nama_pemilik_rekening', null, ['class' => 'form-control','placeholder' => 'Account Name']); ?>

                  <?php echo $errors->first('nama_pemilik_rekening', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

              </div>

              <h3 class="text-black mb-3 mt-5">Personal Categories</h3>
              <a href="<?php echo e(url('profile/preferences')); ?>" class="pull-right text-black"><i class="fa fa-cogs"></i> configure</a>

              <div class="" id="preferences">
                <?php
                  if($preferences){
                    foreach ($preferences as $key => $value) {
                      ?><label class="label label-pink"><?=$value->nama_sektor_industri?></label> <?php
                    }
                  }else{
                    ?><?php
                  }
                ?>
              </div>
            </div>
            <div class="col-12 mt-4">
              <div class="form-group">
                  <div class="text-center">
                    <div id="updatealert"></div>
                    <button class="btn btn-primary shadow-none" type="submit" id="btnupdate" >SUBMIT CHANGES</button>
                  </div>
              </div>
            </div>
          </div>
          <?php echo Form::close(); ?>

        </div>
      </div>
    </div>
  </div>
</div>
</main>
<script>
    function changeavatar(){
      $("#avatar").click();
    }

    $("#avatar").change(function(e){
      $("#avatar-preview").prop("src",URL.createObjectURL(e.target.files[0]));
    });

    $(".select2").select2();
</script>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/myzeals/resources/views/frontend/profile.blade.php ENDPATH**/ ?>