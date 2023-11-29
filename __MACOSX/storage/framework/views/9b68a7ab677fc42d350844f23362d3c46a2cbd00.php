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
                        <strong>Tambah Brand</strong>
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
                            <h5>Tambah Brand</h5>
                        </div>
                        <div class="ibox-content">
                            <?php if($errors->any()): ?>
                            <div class="alert alert-danger">
                                Ada kesalahan! mohon cek formulir.
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            </div>
                            <?php endif; ?>
                            <?php echo $__env->make('backend.flash_message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <?php echo Form::open(['url' => url('master/penerbit/store'), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate novalidate']); ?>


                            <div class="form-group <?php echo e(($errors->has('nama_penerbit')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Nama Brand</label>
                                <div class="col-sm-4 col-xs-12">
                                    <?php echo Form::text('nama_penerbit', null, ['class' => 'form-control']); ?>

                                    <?php echo $errors->first('nama_penerbit', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                </div>
                            </div>
                            <div class="form-group <?php echo e(($errors->has('photos')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Foto Brand</label>
                                <div class="col-sm-4 col-xs-12">
                                    <?php echo Form::file('photos', null); ?>

                                    <?php echo $errors->first('photos', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                </div>
                            </div>
                            <!--
                            <div class="form-group <?php echo e(($errors->has('kode_penerbit')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Kode Penerbit</label>
                                <div class="col-sm-4 col-xs-12">
                                    <?php echo Form::text('kode_penerbit', null, ['class' => 'form-control']); ?>

                                    <?php echo $errors->first('kode_penerbit', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                </div>
                            </div>
                            -->

                            <div class="form-group <?php echo e(($errors->has('alamat')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Alamat</label>
                                <div class="col-sm-4 col-xs-12">
                                    <?php echo Form::textarea('alamat', null, ['class' => 'form-control']); ?>

                                    <?php echo $errors->first('alamat', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                </div>
                            </div>

                            <div class="form-group <?php echo e(($errors->has('no_telp')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Nomor Telp.</label>
                                <div class="col-sm-4 col-xs-12">
                                    <?php echo Form::number('no_telp', null, ['class' => 'form-control']); ?>

                                    <?php echo $errors->first('no_telp', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                </div>
                            </div>

                            <div class="form-group <?php echo e(($errors->has('status')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Status</label>
                                <div class="col-sm-4 col-xs-12">
                                    <?php echo Form::select('status', ['active' => 'Aktif', 'inactive' => 'Tidak Aktif', 'pending' => 'Pending'], null, ['class' => 'form-control']); ?>

                                    <?php echo $errors->first('status', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                </div>
                            </div>
                            <!--
                            <div class="form-group <?php echo e(($errors->has('siup')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Nomor SIUP</label>
                                <div class="col-sm-4 col-xs-12">
                                    <?php echo Form::number('siup', null, ['class' => 'form-control']); ?>

                                    <?php echo $errors->first('siup', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                </div>
                            </div>

                            <div class="form-group <?php echo e(($errors->has('nib')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Scan NIB</label>
                                <div class="col-sm-4 col-xs-12">
                                    <?php echo Form::file('nib', null); ?>

                                    <span class="mt-2 badge badge-primary">Upload Format PDF</span>
                                    <?php echo $errors->first('nib', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                </div>
                            </div>
                            -->
                            <div class="form-group <?php echo e(($errors->has('pic_name')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Nama PIC</label>
                                <div class="col-sm-4 col-xs-12">
                                    <?php echo Form::text('pic_name', null, ['class' => 'form-control']); ?>

                                    <?php echo $errors->first('pic_name', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                </div>
                            </div>

                            <div class="form-group <?php echo e(($errors->has('pic_telp')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Nomor Telp. PIC</label>
                                <div class="col-sm-4 col-xs-12">
                                    <?php echo Form::number('pic_telp', null, ['class' => 'form-control']); ?>

                                    <?php echo $errors->first('pic_telp', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                </div>
                            </div>

                            <div class="form-group <?php echo e(($errors->has('sektor_industri')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Sektor Industri</label>
                                <div class="col-sm-4 col-xs-12">
                                    <select name="sektor_industri" class="form-control" id="sektor_industri">
                                        <option value="null>"> Pilih </option>
                                        <?php $__currentLoopData = $sektor_industri; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $si): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?= $si->id_sektor_industri ?>"> <?php echo e($si->nama_sektor_industri); ?> </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php echo $errors->first('sektor_industri', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                </div>
                            </div>

                            <!--
                            <div class="form-group <?php echo e(($errors->has('longitude')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Longitude</label>
                                <div class="col-sm-4 col-xs-12">
                                    <?php echo Form::number('longitude', null, ['class' => 'form-control']); ?>

                                    <?php echo $errors->first('longitude', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                </div>
                            </div>

                            <div class="form-group <?php echo e(($errors->has('latitude')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Latitude</label>
                                <div class="col-sm-4 col-xs-12">
                                    <?php echo Form::number('latitude', null, ['class' => 'form-control']); ?>

                                    <?php echo $errors->first('latitude', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                </div>
                            </div>
                            -->
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-2">
                                    <a class="btn btn-white" href="<?php echo e(url('master/penerbit')); ?>">
                                        <i class="fa fa-angle-left"></i> kembali
                                    </a>
                                </div>
                                <div class="col-sm-6 text-right">
                                    <button class="btn btn-white" type="reset">Reset</button>
                                    <button class="btn btn-primary btn-rounded" type="submit">Simpan</button>
                                </div>
                            </div>
                        </div>
                        <?php echo Form::close(); ?>

                    </div>
                </div>
            </div>
        </div>
        <?php echo $__env->make('backend.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
</div>
</div>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/myzeals/resources/views/backend/master/penerbit/create.blade.php ENDPATH**/ ?>