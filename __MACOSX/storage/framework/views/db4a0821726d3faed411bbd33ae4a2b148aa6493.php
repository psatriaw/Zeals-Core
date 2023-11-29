<div id="wrapper">
    <?php echo $__env->make('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div id="page-wrapper" class="gray-bg">
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Sektor Industri</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo e(url('master')); ?>">Master</a>
                    </li>
                    <li>
                        <a href="<?php echo e(url('master/category')); ?>">Sektor Industri</a>
                    </li>
                    <li class="active">
                        <strong>Detail Sektor Industri</strong>
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
                            <h5>Detail Sektor Industri</h5>
                        </div>
                        <div class="ibox-content">
                            <?php if($errors->any()): ?>
                            <div class="alert alert-danger">
                                Ada kesalahan! mohon cek formulir.
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            </div>
                            <?php endif; ?>
                            <?php echo $__env->make('backend.flash_message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <?php echo Form::model($data,['url' => url('master/category/update/'.$data->id_sektor_industri), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']); ?>


                            <div class="form-group <?php echo e(($errors->has('first_name')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Waktu Terdaftar</label>
                                <div class="col-sm-4 col-xs-12">
                                    <input type="text" class="form-control disabled" disabled readonly value="<?= date("Y-m-d H:i:s", $data->date_created) ?>">
                                </div>
                            </div>
                            <div class="form-group <?php echo e(($errors->has('first_name')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Pembaruan Terakhir</label>
                                <div class="col-sm-4 col-xs-12">
                                    <input type="text" class="form-control disabled" disabled readonly value="<?= date("Y-m-d H:i:s", $data->last_update) ?>">
                                </div>
                            </div>
                            <div class="form-group <?php echo e(($errors->has('nama_sektor_industri')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Sektor Industri</label>
                                <div class="col-sm-4 col-xs-12">
                                    <?php echo Form::text('nama_sektor_industri', null, ['class' => 'form-control disabled', 'readonly','disabled']); ?>

                                    <?php echo $errors->first('nama_sektor_industri', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-2">
                                    <a class="btn btn-white" href="<?php echo e(url('master/category')); ?>">
                                        <i class="fa fa-angle-left"></i> kembali
                                    </a>
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
<?php /**PATH /home2/zealsasi/new.zeals.asia/resources/views/backend/master/category/detail.blade.php ENDPATH**/ ?>