<div id="wrapper">
    <?php echo $__env->make('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div id="page-wrapper" class="gray-bg">
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Campaign</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo e(url('master')); ?>">Master</a>
                    </li>
                    <li>
                        <a href="<?php echo e(url('master/campaign')); ?>">Campaign</a>
                    </li>
                    <li class="active">
                        <strong>Detail Campaign</strong>
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
                            <h5>Detail Campaign</h5>
                        </div>
                        <div class="ibox-content">
                            <?php if($errors->any()): ?>
                            <div class="alert alert-danger">
                                Ada kesalahan! mohon cek formulir.
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            </div>
                            <?php endif; ?>
                            <?php echo $__env->make('backend.flash_message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <?php echo Form::model($data,['url' => url('master/campaign/update/'.$data->id_campaign), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate novalidate']); ?>

                            <!-- campaign title -->
                            <input type="hidden" name="backlink" value="<?=@$_GET['backlink']?>">
                            <div class="form-group <?php echo e(($errors->has('campaign_title')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Campaign Title</label>
                                <div class="col-sm-4 col-xs-12">
                                    <?php echo Form::text('campaign_title', null, ['class' => 'form-control ']); ?>

                                    <?php echo $errors->first('campaign_title', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                </div>
                            </div>
                            <!-- user -->
                            <?php echo Form::text('id_penerbit', null, ['class' => 'form-control hidden']); ?>

                            <!-- campaign img -->
                            <div class="form-group <?php echo e(($errors->has('photos')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Foto</label>
                                <div class="col-sm-4 col-xs-12">
                                    <img src="<?= url($data->photos) ?>" class="img-fluid" style="height: 100px;" alt="">
                                    <?php echo $errors->first('photos', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                </div>
                            </div>
                            <!-- update image -->
                            <div class="form-group <?php echo e(($errors->has('photos')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Foto Update</label>
                                <div class="col-sm-4 col-xs-12">
                                    <input type="file" name="photos_update">
                                    <?php echo $errors->first('photos', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                </div>
                            </div>
                            <!-- campaign description -->
                            <div class="form-group <?php echo e(($errors->has('campaign_description')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Campaign Description</label>
                                <div class="col-sm-4 col-xs-12">
                                    <textarea name="campaign_description" class="form-control " rows="10"><?php echo e($data->campaign_description); ?></textarea>
                                    <?php echo $errors->first('campaign_description', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                </div>
                            </div>
                            <!-- Start date -->
                            <div class="form-group <?php echo e(($errors->has('start_date')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Start Date</label>
                                <div class="col-sm-4 col-xs-12">
                                    <?php echo Form::date('start_date', null, ['class' => 'form-control ']); ?>

                                    <?php echo $errors->first('start_date', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                </div>
                            </div>
                            <!-- end date -->
                            <div class="form-group <?php echo e(($errors->has('end_date')?"has-error":"")); ?>"><label class="col-sm-2 control-label">End Date</label>
                                <div class="col-sm-4 col-xs-12">
                                    <?php echo Form::date('end_date', null, ['class' => 'form-control ']); ?>

                                    <?php echo $errors->first('end_date', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                </div>
                            </div>
                            <!-- Target fund -->
                            <div class="form-group <?php echo e(($errors->has('target_fund')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Target Fund</label>
                                <div class="col-sm-4 col-xs-12">
                                    <input type="text" class="form-control" name="target_fund" value="<?= $data->target_fund ?>">
                                    <?php echo $errors->first('target_fund', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                </div>
                            </div>
                            <!-- Total Lembar -->
                            <div class="form-group <?php echo e(($errors->has('total_lembar')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Total Lembar</label>
                                <div class="col-sm-4 col-xs-12">
                                    <?php echo Form::text('total_lembar', null, ['class' => 'form-control']); ?>

                                    <?php echo $errors->first('total_lembar', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                </div>
                            </div>
                            <!-- minimum pembelian -->
                            <div class="form-group <?php echo e(($errors->has('minimum_pembelian')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Minimum Pembelian</label>
                                <div class="col-sm-4 col-xs-12">
                                    <input type="text" class="form-control" name="minimum_pembelian" value="<?= $data->minimum_pembelian ?>">
                                    <?php echo $errors->first('minimum_pembelian', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                </div>
                            </div>
                            <!-- maximum investor -->
                            <div class="form-group <?php echo e(($errors->has('maximum_investor')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Maksimum Investor</label>
                                <div class="col-sm-4 col-xs-12">
                                    <input type="text" class="form-control" name="maximum_investor" value="<?= $data->maximum_investor ?>">
                                    <?php echo $errors->first('maximum_investor', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                </div>
                            </div>
                            <!-- periode deviden -->
                            <div class="form-group <?php echo e(($errors->has('periode_deviden')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Periode Deviden (bulan)</label>
                                <div class="col-sm-4 col-xs-12">
                                    <input type="number" class="form-control" name="periode_deviden" value="<?= $data->periode_deviden ?>">
                                    <?php echo $errors->first('periode_deviden', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                </div>
                            </div>
                            <!-- Tipe Invest -->
                            <div class="form-group <?php echo e(($errors->has('tipe_produk')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Jenis</label>
                                <div class="col-sm-4 col-xs-12">
                                    <?php echo Form::select('tipe_produk', ['surat hutang' => 'Surat Hutang', 'saham' => 'Saham'], null, ['class' => 'form-control']); ?>

                                    <?php echo $errors->first('tipe_produk', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                </div>
                            </div>
                            <!-- campaign img -->
                            <div class="form-group <?php echo e(($errors->has('prospektus')?"has-error":"")); ?>"><label class="col-sm-2 control-label">File Prospektus</label>
                                <div class="col-sm-4 col-xs-12">
                                    <p>
                                      <?=str_replace("upload/prospektus/","",$data->prospektus)?>
                                    </p>
                                    <input type="file" name="prospektus">
                                    <?php echo $errors->first('prospektus', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                </div>
                            </div>
                            <!-- update image -->

                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-2">
                                    <a class="btn btn-white" href="<?php echo e(url('master/campaign')); ?>">
                                        <i class="fa fa-angle-left"></i> kembali
                                    </a>
                                </div>
                                <div class="col-sm-6 text-right">
                                    <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
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
<?php /**PATH /home/zealsasi/new.zeals.asia/resources/views/backend/master/campaign/edit.blade.php ENDPATH**/ ?>