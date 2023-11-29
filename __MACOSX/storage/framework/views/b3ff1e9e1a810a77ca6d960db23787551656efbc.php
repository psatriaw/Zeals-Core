<?php
  $main_url = $config['main_url'];
?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
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
                        <a href="<?php echo e(url($main_url)); ?>">Data Module</a>
                    </li>
                    <li class="active">
                        <strong>Ubah Module</strong>
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
                        <h5>Ubah Module</h5>
                    </div>
                    <div class="ibox-content">
                      <?php if($errors->any()): ?>
                          <div class="alert alert-danger">
                              Ada kesalahan! mohon cek formulir.
                              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                          </div>
                      <?php endif; ?>
                      <?php echo $__env->make('backend.flash_message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                      <?php echo Form::model($data,['url' => url('admin/module/update/'.$data->id_module), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']); ?>


                        <div class="form-group <?php echo e(($errors->has('module_name')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Nama Module</label>
                            <div class="col-sm-4 col-xs-12">
                                <?php echo Form::text('module_name', null, ['class' => 'form-control']); ?>

                                <?php echo $errors->first('module_name', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                            </div>
                        </div>

                        <div class="form-group <?php echo e(($errors->has('id_department')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Group Pengguna *)</label>
                            <div class="col-sm-4 col-xs-12">
                                <?php echo Form::select('id_department[]', $optdepartment, null, ['class' => 'form-control select2',"multiple" => "multiple"]); ?>

                                <span class="help-block">*) untuk mempermudah management hak akses</span>
                                <?php echo $errors->first('id_department', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                                <a class="btn btn-white" href="<?php echo e(url('admin/module')); ?>">
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
<script>
  $(document).ready(function() {
    $('.select2').select2();
  });
</script>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/myzeals/resources/views/backend/master/module/edit.blade.php ENDPATH**/ ?>