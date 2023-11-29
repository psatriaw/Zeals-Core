<div id="wrapper">
  <?php echo $__env->make('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  <div id="page-wrapper" class="gray-bg">
    <?php echo $__env->make('backend.menus.top',array("login" => $login, "previlege" => $previlege), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Pengaturan</h2>
            </ol>
        </div>
        <div class="col-lg-2">

        </div>

    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Pengaturan</h5>
                    </div>
                    <div class="ibox-content">
                      <?php if($errors->any()): ?>
                          <div class="alert alert-danger">
                              Ada kesalahan! mohon cek formulir.
                              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                          </div>
                      <?php endif; ?>
                      <?php echo $__env->make('backend.flash_message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                      <?php echo Form::open(['url' => url('master/pengaturan/update/'), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']); ?>


                        <?php
                          foreach($data as $key=>$val){
                              ?>
                              <div class="form-group <?php echo e(($errors->has($val->code_setting)?"has-error":"")); ?>"><label class="col-sm-3 control-label"><?=ucwords($val->description)?></label>
                                  <div class="col-sm-9 col-xs-12">
                                      <?php echo Form::text($val->code_setting, $val->setting_value, ['class' => 'form-control']); ?>

                                      <?php echo $errors->first($val->code_setting, '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                  </div>
                              </div>
                              <?php
                          }
                        ?>

                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">

                            </div>
                            <div class="col-sm-6 text-right">
                              <button class="btn btn-white" type="reset">Reset</button>
                              <button class="btn btn-primary" type="submit">Simpan</button>
                            </div>
                        </div>
                      <?php echo Form::close(); ?>

                    </div>
                </div>
                </div>
            </div>
        </div>
        <?php echo $__env->make('backend.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  </div>
</div>
<?php /**PATH /home/zealsasi/new.zeals.asia/resources/views/backend/setting.blade.php ENDPATH**/ ?>