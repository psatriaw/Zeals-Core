<?php
  $main_url = $config['main_url'];
?>
<div id="wrapper">
  <?php echo $__env->make('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  <div id="page-wrapper" class="gray-bg">
    <?php echo $__env->make('backend.menus.top',array("login" => $login, "previlege" => $previlege), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Data Module</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo e(url('dashboard/view')); ?>">Dashboard</a>
                    </li>
                    <li>
                        <a href="<?php echo e(url($main_url)); ?>">Daftar Module Sistem</a>
                    </li>
                    <li>
                        <a href="<?php echo e(url($main_url.'/manage/'.$parent_id)); ?>">Manage Module</a>
                    </li>
                    <li class="active">
                        <strong>Tambah Method</strong>
                    </li>
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
                        <h5>Tambah Method</h5>
                    </div>
                    <div class="ibox-content">
                      <?php if($errors->any()): ?>
                          <div class="alert alert-danger">
                              Ada kesalahan! mohon cek formulir.
                              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                          </div>
                      <?php endif; ?>
                      <?php echo $__env->make('backend.flash_message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                      <?php echo Form::open(['url' => url('admin/module/method/store/'.$parent_id), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']); ?>

                        <div class="form-group <?php echo e(($errors->has('method')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Method</label>
                            <div class="col-sm-4 col-xs-12">
                                <?php echo Form::text('method', null, ['class' => 'form-control','placeholder' => 'contoh: admin-master-module-event']); ?>

                                <?php echo $errors->first('method', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                            </div>
                        </div>

                        <div class="form-group <?php echo e(($errors->has('description')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Deskripsi</label>
                            <div class="col-sm-4 col-xs-12">
                                <?php echo Form::textarea('description', null, ['class' => 'form-control','rows' => 3]); ?>

                                <?php echo $errors->first('description', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                                <a class="btn btn-white" href="<?php echo e(url('admin/module/manage/'.$parent_id)); ?>">
                                    <i class="fa fa-angle-left"></i> kembali
                                </a>
                            </div>
                            <div class="col-sm-6 text-right">
                              <button class="btn btn-white" type="reset">Reset</button>
                              <button class="btn btn-primary btn-rounded" type="submit">Simpan</button>
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
<?php /**PATH /home2/zealsasi/new.zeals.asia/resources/views/backend/master/module/method/create.blade.php ENDPATH**/ ?>