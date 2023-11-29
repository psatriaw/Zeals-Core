<div id="wrapper">
    <?php echo $__env->make('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div id="page-wrapper" class="gray-bg">
      <?php echo $__env->make('backend.menus.top',array("login" => $login, "previlege" => $previlege), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Brands</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo e(url('dashboard/view')); ?>">Dashboard</a>
                    </li>
                    <li>
                        <a href="<?php echo e(url('master/penerbit')); ?>">Brands</a>
                    </li>
                    <li class="active">
                        <strong>Detail Brand</strong>
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
                            <h5>Detail Brand</h5>
                        </div>
                        <div class="ibox-content">
                            <?php if($errors->any()): ?>
                            <div class="alert alert-danger">
                                Ada kesalahan! mohon cek formulir.
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            </div>
                            <?php endif; ?>
                            <?php echo $__env->make('backend.flash_message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <?php echo Form::model($data,['url' => url('master/penerbit/update/'.$data->id_penerbit), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']); ?>


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
                            <div class="form-group <?php echo e(($errors->has('nama_penerbit')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Nama Brand</label>
                                <div class="col-sm-4 col-xs-12">
                                    <?php echo Form::text('nama_penerbit', null, ['class' => 'form-control disabled', 'readonly','disabled']); ?>

                                    <?php echo $errors->first('nama_penerbit', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                </div>
                            </div>
                            <div class="form-group <?php echo e(($errors->has('photos')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Foto Brand</label>
                                <div class="col-sm-4 col-xs-12">
                                    <img src="/<?= $data->photos; ?>" class="img-fluid" style="height: 200px" alt="">
                                    <?php echo $errors->first('photos', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                </div>
                            </div>
                            <!--
                            <div class="form-group <?php echo e(($errors->has('kode_penerbit')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Kode Penerbit</label>
                                <div class="col-sm-4 col-xs-12">
                                    <?php echo Form::text('kode_penerbit', null, ['class' => 'form-control disabled', 'readonly','disabled']); ?>

                                    <?php echo $errors->first('kode_penerbit', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                </div>
                            </div>
                            -->

                            <div class="form-group <?php echo e(($errors->has('alamat')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Alamat</label>
                                <div class="col-sm-4 col-xs-12">
                                    <?php echo Form::text('alamat', null, ['class' => 'form-control disabled', 'readonly','disabled']); ?>

                                    <?php echo $errors->first('alamat', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                </div>
                            </div>
                            <div class="form-group <?php echo e(($errors->has('no_telp')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Nomor Telp</label>
                                <div class="col-sm-4 col-xs-12">
                                    <?php echo Form::text('no_telp', null, ['class' => 'form-control disabled', 'readonly','disabled']); ?>

                                    <?php echo $errors->first('no_telp', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                </div>
                            </div>
                            <div class="form-group <?php echo e(($errors->has('status')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Status</label>
                                <div class="col-sm-4 col-xs-12">
                                    <?php echo Form::text('status', null, ['class' => 'form-control disabled', 'readonly','disabled']); ?>

                                    <?php echo $errors->first('status', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                </div>
                            </div>
                            <!--
                            <div class="form-group <?php echo e(($errors->has('siup')?"has-error":"")); ?>"><label class="col-sm-2 control-label">SIUP</label>
                                <div class="col-sm-4 col-xs-12">
                                    <?php echo Form::text('siup', null, ['class' => 'form-control disabled', 'readonly','disabled']); ?>

                                    <?php echo $errors->first('siup', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                </div>
                            </div>
                            <div class="form-group <?php echo e(($errors->has('nib')?"has-error":"")); ?>"><label class="col-sm-2 control-label">NIB</label>
                                <div class="col-sm-4 col-xs-12">
                                    <a class="btn btn-primary" target="_blank" href="/<?= $data->nib; ?>">Lihat</a>
                                    <?php echo $errors->first('nib', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                </div>
                            </div>
                            -->
                            <div class="form-group <?php echo e(($errors->has('pic_name')?"has-error":"")); ?>"><label class="col-sm-2 control-label">PIC</label>
                                <div class="col-sm-4 col-xs-12">
                                    <?php echo Form::text('pic_name', null, ['class' => 'form-control disabled', 'readonly','disabled']); ?>

                                    <?php echo $errors->first('pic_name', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                </div>
                            </div>
                            <div class="form-group <?php echo e(($errors->has('pic_telp')?"has-error":"")); ?>"><label class="col-sm-2 control-label">PIC Telp</label>
                                <div class="col-sm-4 col-xs-12">
                                    <?php echo Form::text('pic_telp', null, ['class' => 'form-control disabled', 'readonly','disabled']); ?>

                                    <?php echo $errors->first('pic_telp', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                </div>
                            </div>
                            <div class="form-group <?php echo e(($errors->has('nama_sektor_industri')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Nama Sektor Industri</label>
                                <div class="col-sm-4 col-xs-12">
                                    <?php echo Form::text('nama_sektor_industri', null, ['class' => 'form-control disabled', 'readonly','disabled']); ?>

                                    <?php echo $errors->first('nama_sektor_industri', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                </div>
                            </div>
                            <!--
                            <div class="form-group <?php echo e(($errors->has('longitude')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Longitude</label>
                                <div class="col-sm-4 col-xs-12">
                                    <?php echo Form::text('longitude', null, ['class' => 'form-control disabled', 'readonly','disabled']); ?>

                                    <?php echo $errors->first('longitude', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                </div>
                            </div>
                            <div class="form-group <?php echo e(($errors->has('latitude')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Latitude</label>
                                <div class="col-sm-4 col-xs-12">
                                    <?php echo Form::text('latitude', null, ['class' => 'form-control disabled', 'readonly','disabled']); ?>

                                    <?php echo $errors->first('latitude', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                </div>
                            </div>
                            -->

                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-2">
                                    <a class="btn btn-white" href="<?php echo e(url('master/penerbit')); ?>">
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
<?php /**PATH /home2/zealsasi/new.zeals.asia/resources/views/backend/master/penerbit/detail.blade.php ENDPATH**/ ?>