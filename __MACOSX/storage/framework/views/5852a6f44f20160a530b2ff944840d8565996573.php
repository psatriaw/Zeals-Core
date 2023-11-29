<?php
$main_url = $config['main_url'];

?>
<div id="wrapper">
    <?php echo $__env->make('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div id="page-wrapper" class="gray-bg">
        <?php echo $__env->make('backend.menus.top',array("login" => $login, "previlege" => $previlege), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Fee Type</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo e(url('dashboard/view')); ?>">Dashboard</a>
                    </li>
                    <li>
                        <a href="<?php echo e(url($main_url)); ?>">Fee Type</a>
                    </li>
                </ol>
            </div>
            <div class="col-lg-2">

            </div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight" id="app">
            <div class="row">
                <div class="col-lg-12">
                    <!-- fee beli saham -->
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Fee Beli Saham</h5>
                        </div>

                        <div class="ibox-content">
                            <?php if($errors->any()): ?>
                            <div class="alert alert-danger">
                                Ada kesalahan! mohon cek formulir.
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            </div>
                            <?php endif; ?>
                            <?php echo $__env->make('backend.flash_message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <?php echo Form::model($data,['url' => url('master/feesetting/update/belisaham'), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']); ?>

                            <!-- beli saham -->
                            <div class="form-group <?php echo e(($errors->has('name')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Fee Beli Saham Type</label>
                                <div class="col-sm-4 col-xs-12">
                                    <select name="fee_beli_saham_type" id="fee_beli_saham_type" class="form-control" v-model="fee_beli_saham_type">
                                        <option value="persen" <?= $data->fee_beli_saham_type->setting_value == 'persen' ? 'persen' : '' ?>>Persen</option>
                                        <option value="value" <?= $data->fee_beli_saham_type->setting_value == 'value' ? 'value' : '' ?>>Value</option>
                                    </select>
                                </div>
                            </div>

                            <!-- if persen -->
                            <div v-if="fee_beli_saham_type == 'persen'">
                                <div class="form-group"><label class="col-sm-2 control-label">Besaran Persen</label>
                                    <div class="col-sm-4 col-xs-12">
                                        <input type="number" class="form-control" name="fee_beli_saham_persen" value="<?php echo e($data->fee_beli_saham_persen->setting_value); ?>">
                                    </div>
                                </div>
                            </div>

                            <!-- if value -->
                            <div v-if="fee_beli_saham_type == 'value'">
                                <div class="form-group"><label class="col-sm-2 control-label">Besaran Value</label>
                                    <div class="col-sm-4 col-xs-12">
                                        <input type="number" class="form-control" name="fee_beli_saham_value" value="<?php echo e($data->fee_beli_saham_value->setting_value); ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group <?php echo e(($errors->has('name')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Pajak Beli Saham Type</label>
                                <div class="col-sm-4 col-xs-12">
                                    <select name="pajak_beli_saham_type" id="pajak_beli_saham_type" class="form-control" v-model="pajak_beli_saham_type">
                                        <option value="persen" <?= $data->pajak_beli_saham_type->setting_value == 'persen' ? 'persen' : '' ?>>Persen</option>
                                        <option value="value" <?= $data->pajak_beli_saham_type->setting_value == 'value' ? 'value' : '' ?>>Value</option>
                                    </select>
                                </div>
                            </div>

                            <!-- if persen -->
                            <div v-if="pajak_beli_saham_type == 'persen'">
                                <div class="form-group"><label class="col-sm-2 control-label">Besaran Persen</label>
                                    <div class="col-sm-4 col-xs-12">
                                        <input type="number" class="form-control" name="pajak_beli_saham_persen" value="<?php echo e($data->pajak_beli_saham_persen->setting_value); ?>">
                                    </div>
                                </div>
                            </div>

                            <!-- if value -->
                            <div v-if="pajak_beli_saham_type == 'value'">
                                <div class="form-group"><label class="col-sm-2 control-label">Besaran Value</label>
                                    <div class="col-sm-4 col-xs-12">
                                        <input type="number" class="form-control" name="pajak_beli_saham_value" value="<?php echo e($data->pajak_beli_saham_value->setting_value); ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-2">

                                </div>
                                <div class="col-sm-6 text-right">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </div>
                            <?php echo Form::close(); ?>

                        </div>

                    </div>

                    <!-- fee release deviden -->
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Fee Release Deviden</h5>
                        </div>
                        <!-- fee release deviden -->
                        <div class="ibox-content">
                            <?php if($errors->any()): ?>
                            <div class="alert alert-danger">
                                Ada kesalahan! mohon cek formulir.
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            </div>
                            <?php endif; ?>
                            <?php echo $__env->make('backend.flash_message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <?php echo Form::model($data,['url' => url('master/feesetting/update/releasedeviden'), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']); ?>




                            <!-- beli saham -->
                            <div class="form-group <?php echo e(($errors->has('name')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Fee Release Deviden Type</label>
                                <div class="col-sm-4 col-xs-12">
                                    <select name="fee_release_deviden_type" id="fee_release_deviden_type" class="form-control" v-model="fee_release_deviden_type">
                                        <option value="persen" <?= $data->fee_release_deviden_type->setting_value == 'persen' ? 'persen' : '' ?>>Persen</option>
                                        <option value="value" <?= $data->fee_release_deviden_type->setting_value == 'value' ? 'value' : '' ?>>Value</option>
                                    </select>
                                </div>
                            </div>

                            <!-- if persen -->
                            <div v-if="fee_release_deviden_type == 'persen'">
                                <div class="form-group"><label class="col-sm-2 control-label">Besaran Persen</label>
                                    <div class="col-sm-4 col-xs-12">
                                        <input type="number" class="form-control" name="fee_release_deviden_persen" value="<?php echo e($data->fee_release_deviden_persen->setting_value); ?>">
                                    </div>
                                </div>
                            </div>

                            <!-- if value -->
                            <div v-if="fee_release_deviden_type == 'value'">
                                <div class="form-group"><label class="col-sm-2 control-label">Besaran Value</label>
                                    <div class="col-sm-4 col-xs-12">
                                        <input type="number" class="form-control" name="fee_release_deviden_value" value="<?php echo e($data->fee_release_deviden_value->setting_value); ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group <?php echo e(($errors->has('name')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Pajak Release Deviden Type</label>
                                <div class="col-sm-4 col-xs-12">
                                    <select name="pajak_release_deviden_type" id="pajak_release_deviden_type" class="form-control" v-model="pajak_release_deviden_type">
                                        <option value="persen" <?= $data->pajak_release_deviden_type->setting_value == 'persen' ? 'persen' : '' ?>>Persen</option>
                                        <option value="value" <?= $data->pajak_release_deviden_type->setting_value == 'value' ? 'value' : '' ?>>Value</option>
                                    </select>
                                </div>
                            </div>

                            <!-- if persen -->
                            <div v-if="pajak_release_deviden_type == 'persen'">
                                <div class="form-group"><label class="col-sm-2 control-label">Besaran Persen</label>
                                    <div class="col-sm-4 col-xs-12">
                                        <input type="number" class="form-control" name="pajak_release_deviden_persen" value="<?php echo e($data->pajak_release_deviden_persen->setting_value); ?>">
                                    </div>
                                </div>
                            </div>

                            <!-- if value -->
                            <div v-if="pajak_release_deviden_type == 'value'">
                                <div class="form-group"><label class="col-sm-2 control-label">Besaran Value</label>
                                    <div class="col-sm-4 col-xs-12">
                                        <input type="number" class="form-control" name="pajak_release_deviden_value" value="<?php echo e($data->pajak_release_deviden_value->setting_value); ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-2">

                                </div>
                                <div class="col-sm-6 text-right">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </div>
                            <?php echo Form::close(); ?>

                        </div>
                    </div>

                    <!-- fee topup -->
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Fee Topup</h5>
                        </div>
                        <div class="ibox-content">
                            <?php if($errors->any()): ?>
                            <div class="alert alert-danger">
                                Ada kesalahan! mohon cek formulir.
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            </div>
                            <?php endif; ?>
                            <?php echo $__env->make('backend.flash_message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <?php echo Form::model($data,['url' => url('master/feesetting/update/topup'), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']); ?>


                            <div class="form-group <?php echo e(($errors->has('name')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Fee Topup Type</label>
                                <div class="col-sm-4 col-xs-12">
                                    <select name="fee_topup_type" id="fee_topup_type" class="form-control" v-model="fee_topup_type">
                                        <option value="persen" <?= $data->fee_topup_type->setting_value == 'persen' ? 'persen' : '' ?>>Persen</option>
                                        <option value="value" <?= $data->fee_topup_type->setting_value == 'value' ? 'value' : '' ?>>Value</option>
                                    </select>
                                </div>
                            </div>

                            <!-- if persen -->
                            <div v-if="fee_topup_type == 'persen'">
                                <div class="form-group"><label class="col-sm-2 control-label">Besaran Persen</label>
                                    <div class="col-sm-4 col-xs-12">
                                        <input type="number" class="form-control" name="fee_topup_persen" value="<?php echo e($data->fee_topup_persen->setting_value); ?>">
                                    </div>
                                </div>
                            </div>

                            <!-- if value -->
                            <div v-if="fee_topup_type == 'value'">
                                <div class="form-group"><label class="col-sm-2 control-label">Besaran Value</label>
                                    <div class="col-sm-4 col-xs-12">
                                        <input type="number" class="form-control" name="fee_topup_value" value="<?php echo e($data->fee_topup_value->setting_value); ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group <?php echo e(($errors->has('name')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Pajak Topup Type</label>
                                <div class="col-sm-4 col-xs-12">
                                    <select name="pajak_topup_type" id="pajak_topup_type" class="form-control" v-model="pajak_topup_type">
                                        <option value="persen" <?= $data->pajak_topup_type->setting_value == 'persen' ? 'persen' : '' ?>>Persen</option>
                                        <option value="value" <?= $data->pajak_topup_type->setting_value == 'value' ? 'value' : '' ?>>Value</option>
                                    </select>
                                </div>
                            </div>

                            <!-- if persen -->
                            <div v-if="pajak_topup_type == 'persen'">
                                <div class="form-group"><label class="col-sm-2 control-label">Besaran Persen</label>
                                    <div class="col-sm-4 col-xs-12">
                                        <input type="number" class="form-control" name="pajak_topup_persen" value="<?php echo e($data->pajak_topup_persen->setting_value); ?>">
                                    </div>
                                </div>
                            </div>

                            <!-- if value -->
                            <div v-if="pajak_topup_type == 'value'">
                                <div class="form-group"><label class="col-sm-2 control-label">Besaran Value</label>
                                    <div class="col-sm-4 col-xs-12">
                                        <input type="number" class="form-control" name="pajak_topup_value" value="<?php echo e($data->pajak_topup_value->setting_value); ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-2">

                                </div>
                                <div class="col-sm-6 text-right">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </div>
                            <?php echo Form::close(); ?>

                        </div>
                    </div>

                    <!-- fee pencairan -->
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Fee Pencairan</h5>
                        </div>
                        <!-- fee release deviden -->
                        <div class="ibox-content">
                            <?php if($errors->any()): ?>
                            <div class="alert alert-danger">
                                Ada kesalahan! mohon cek formulir.
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            </div>
                            <?php endif; ?>
                            <?php echo $__env->make('backend.flash_message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <?php echo Form::model($data,['url' => url('master/feesetting/update/pencairan'), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']); ?>




                            <!-- beli saham -->
                            <div class="form-group <?php echo e(($errors->has('name')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Fee Pencairan Type</label>
                                <div class="col-sm-4 col-xs-12">
                                    <select name="fee_pencairan_type" id="fee_pencairan_type" class="form-control" v-model="fee_pencairan_type">
                                        <option value="persen" <?= $data->fee_pencairan_type->setting_value == 'persen' ? 'persen' : '' ?>>Persen</option>
                                        <option value="value" <?= $data->fee_pencairan_type->setting_value == 'value' ? 'value' : '' ?>>Value</option>
                                    </select>
                                </div>
                            </div>

                            <!-- if persen -->
                            <div v-if="fee_pencairan_type == 'persen'">
                                <div class="form-group"><label class="col-sm-2 control-label">Besaran Persen</label>
                                    <div class="col-sm-4 col-xs-12">
                                        <input type="number" class="form-control" name="fee_pencairan_persen" value="<?php echo e($data->fee_pencairan_persen->setting_value); ?>">
                                    </div>
                                </div>
                            </div>

                            <!-- if value -->
                            <div v-if="fee_pencairan_type == 'value'">
                                <div class="form-group"><label class="col-sm-2 control-label">Besaran Value</label>
                                    <div class="col-sm-4 col-xs-12">
                                        <input type="number" class="form-control" name="fee_pencairan_value" value="<?php echo e($data->fee_pencairan_value->setting_value); ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group <?php echo e(($errors->has('name')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Pajak Pencairan Type</label>
                                <div class="col-sm-4 col-xs-12">
                                    <select name="pajak_pencairan_type" id="pajak_pencairan_type" class="form-control" v-model="pajak_pencairan_type">
                                        <option value="persen" <?= $data->pajak_pencairan_type->setting_value == 'persen' ? 'persen' : '' ?>>Persen</option>
                                        <option value="value" <?= $data->pajak_pencairan_type->setting_value == 'value' ? 'value' : '' ?>>Value</option>
                                    </select>
                                </div>
                            </div>

                            <!-- if persen -->
                            <div v-if="pajak_pencairan_type == 'persen'">
                                <div class="form-group"><label class="col-sm-2 control-label">Besaran Persen</label>
                                    <div class="col-sm-4 col-xs-12">
                                        <input type="number" class="form-control" name="pajak_pencairan_persen" value="<?php echo e($data->pajak_pencairan_persen->setting_value); ?>">
                                    </div>
                                </div>
                            </div>

                            <!-- if value -->
                            <div v-if="pajak_pencairan_type == 'value'">
                                <div class="form-group"><label class="col-sm-2 control-label">Besaran Value</label>
                                    <div class="col-sm-4 col-xs-12">
                                        <input type="number" class="form-control" name="pajak_pencairan_value" value="<?php echo e($data->pajak_pencairan_value->setting_value); ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-2">

                                </div>
                                <div class="col-sm-6 text-right">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </div>
                            <?php echo Form::close(); ?>

                        </div>
                    </div>

                    <!-- fee release pendanaan -->
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Fee Release Pendanaan</h5>
                        </div>
                        <!-- fee release deviden -->
                        <div class="ibox-content">
                            <?php if($errors->any()): ?>
                            <div class="alert alert-danger">
                                Ada kesalahan! mohon cek formulir.
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            </div>
                            <?php endif; ?>
                            <?php echo $__env->make('backend.flash_message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <?php echo Form::model($data,['url' => url('master/feesetting/update/releasependanaan'), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']); ?>




                            <!-- beli saham -->
                            <div class="form-group <?php echo e(($errors->has('name')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Fee Release Pendanaan Type</label>
                                <div class="col-sm-4 col-xs-12">
                                    <select name="fee_release_pendanaan_type" id="fee_release_pendanaan_type" class="form-control" v-model="fee_release_pendanaan_type">
                                        <option value="persen" <?= $data->fee_release_pendanaan_type->setting_value == 'persen' ? 'persen' : '' ?>>Persen</option>
                                        <option value="value" <?= $data->fee_release_pendanaan_type->setting_value == 'value' ? 'value' : '' ?>>Value</option>
                                    </select>
                                </div>
                            </div>

                            <!-- if persen -->
                            <div v-if="fee_release_pendanaan_type == 'persen'">
                                <div class="form-group"><label class="col-sm-2 control-label">Besaran Persen</label>
                                    <div class="col-sm-4 col-xs-12">
                                        <input type="number" class="form-control" name="fee_release_pendanaan_persen" value="<?php echo e($data->fee_release_pendanaan_persen->setting_value); ?>">
                                    </div>
                                </div>
                            </div>

                            <!-- if value -->
                            <div v-if="fee_release_pendanaan_type == 'value'">
                                <div class="form-group"><label class="col-sm-2 control-label">Besaran Value</label>
                                    <div class="col-sm-4 col-xs-12">
                                        <input type="number" class="form-control" name="fee_release_pendanaan_value" value="<?php echo e($data->fee_release_pendanaan_value->setting_value); ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group <?php echo e(($errors->has('name')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Pajak Release Pendanaan Type</label>
                                <div class="col-sm-4 col-xs-12">
                                    <select name="pajak_release_pendanaan_type" id="pajak_release_pendanaan_type" class="form-control" v-model="pajak_release_pendanaan_type">
                                        <option value="persen" <?= $data->pajak_release_pendanaan_type->setting_value == 'persen' ? 'persen' : '' ?>>Persen</option>
                                        <option value="value" <?= $data->pajak_release_pendanaan_type->setting_value == 'value' ? 'value' : '' ?>>Value</option>
                                    </select>
                                </div>
                            </div>

                            <!-- if persen -->
                            <div v-if="pajak_release_pendanaan_type == 'persen'">
                                <div class="form-group"><label class="col-sm-2 control-label">Besaran Persen</label>
                                    <div class="col-sm-4 col-xs-12">
                                        <input type="number" class="form-control" name="pajak_release_pendanaan_persen" value="<?php echo e($data->pajak_release_pendanaan_persen->setting_value); ?>">
                                    </div>
                                </div>
                            </div>

                            <!-- if value -->
                            <div v-if="pajak_release_pendanaan_type == 'value'">
                                <div class="form-group"><label class="col-sm-2 control-label">Besaran Value</label>
                                    <div class="col-sm-4 col-xs-12">
                                        <input type="number" class="form-control" name="pajak_release_pendanaan_value" value="<?php echo e($data->pajak_release_pendanaan_value->setting_value); ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-2">

                                </div>
                                <div class="col-sm-6 text-right">
                                    <button type="submit" class="btn btn-primary">Update</button>
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
</div>


<script src="https://vuejs.org/js/vue.js">
</script>

<script>
    var app = new Vue({
        el: '#app',

        data: {
            fee_beli_saham_type: "<?= $data->fee_beli_saham_type->setting_value; ?>",
            pajak_beli_saham_type: "<?= $data->pajak_beli_saham_type->setting_value; ?>",
            fee_release_deviden_type: "<?= $data->fee_release_deviden_type->setting_value; ?>",
            pajak_release_deviden_type: "<?= $data->pajak_release_deviden_type->setting_value; ?>",
            fee_topup_type: "<?= $data->fee_topup_type->setting_value; ?>",
            pajak_topup_type: "<?= $data->pajak_topup_type->setting_value; ?>",
            fee_pencairan_type: "<?= $data->fee_pencairan_type->setting_value; ?>",
            pajak_pencairan_type: "<?= $data->pajak_pencairan_type->setting_value; ?>",
            fee_release_pendanaan_type: "<?= $data->fee_release_pendanaan_type->setting_value; ?>",
            pajak_release_pendanaan_type: "<?= $data->pajak_release_pendanaan_type->setting_value; ?>",
        }
    })
</script>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/myzeals/resources/views/backend/master/feesetting/index.blade.php ENDPATH**/ ?>