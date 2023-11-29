<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<main role="main">

  <section class="custom-jumbotron text-center">
    <div class="container">
      <div class="superline"></div>
      <div class="custom-container">
        <h1>Personal Categories</h1>
      </div>
    </div>
  </section>

  <div class="album pb-5 top30">
    <div class="container">
      <div class="row">
        <div class="col-12 col-md-3 mb-3">
          <?php echo $__env->make('frontend.menu_sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div class="col-12 col-md-9">
          <div class="album-content">
            <div class="row">
              <div class="col-12 col-md-12">
                <?php echo Form::model($detail,['url' => url('profile/preferences/store'), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate', "enctype" => "multipart/form-data"]); ?>

                <div class="campaign-description">
                  Please type maximum 5 personal categories on the field below
                </div>

                <div class="form-group mt-3 <?php echo e(($errors->has('preferences')?"has-error":"")); ?>">
                    <?php echo Form::select('preferences[]', $preferences, null, ['class' => 'form-control','id' =>"preferences",'multiple' => true]); ?>

                    <?php echo $errors->first('preferences', '<p class="bg-danger p-xs b-r-sm m-t text-white p-1">:message</p>'); ?>

                </div>

                <div class="form-group mt-5">
                    <div class="text-center">
                      <div id="updatealert"></div>
                      <button class="btn btn-primary shadow-none" type="submit" id="btnupdate" >SAVE CHANGES</button>
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
<script>
  $("#preferences").select2();
</script>
<?php /**PATH /home2/zealsasi/new.zeals.asia/resources/views/frontend/preferences.blade.php ENDPATH**/ ?>