<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<div id="wrapper">
    <?php echo $__env->make('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div id="page-wrapper" class="gray-bg">
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Campaign</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo e(url('dashboard/view')); ?>">Dashboard</a>
                    </li>
                    <li>
                        <a href="<?php echo e(url('master/campaign')); ?>">Campaign</a>
                    </li>
                    <li class="active">
                        <strong>Create Campaign</strong>
                    </li>
                </ol>
            </div>
            <div class="col-lg-2">

            </div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-12 col-md-3">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Banner Campaign</h5>
                        </div>
                        <div class="ibox-content text-center">
                            <div class="icons" style="border: 1px solid #ccc;padding: 25px;border-radius: 10px;background: #5eb6f0;font-size:38px;color:#fff;">
                                <i class="fa fa-images" style="font-size:58px;"></i>
                                <br>
                                AMP
                            </div>
                            <br>
                            <a href="<?php echo e(url('master/campaign/create/banner')); ?>" class="btn btn-secondary">create campaign</a>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-3">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Video Campaign</h5>
                        </div>
                        <div class="ibox-content text-center">
                            <div class="icons" style="border: 1px solid #ccc;padding: 25px;border-radius: 10px;background: #fcb13b;font-size:38px;color:#fff;">
                                <i class="fa fa-video" style="font-size:58px;"></i><br>
                                AMP
                            </div>
                            <br>
                            <a href="<?php echo e(url('master/campaign/create/video')); ?>" class="btn btn-secondary">create campaign</a>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-3">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Text Campaign</h5>
                        </div>
                        <div class="ibox-content text-center">
                            <div class="icons" style="border: 1px solid #ccc;padding: 25px;border-radius: 10px;background: #c82360;font-size:38px;color:#fff;">
                                <i class="fa fa-file" style="font-size:58px;"></i><br>
                                AMP
                            </div>
                            <br>
                            <a href="<?php echo e(url('master/campaign/create/text')); ?>" class="btn btn-secondary">create campaign</a>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-3">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Voucher Campaign (O2O)</h5>
                        </div>
                        <div class="ibox-content text-center">
                            <div class="icons" style="border: 1px solid #ccc;padding: 25px;border-radius: 10px;background: #961516;font-size:38px;color:#fff;">
                                <i class="fa fa-qrcode" style="font-size:58px;"></i><br>
                                O2O
                            </div>
                            <br>
                            <a href="<?php echo e(url('master/campaign/create/o2o')); ?>" class="btn btn-secondary">create campaign</a>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-3">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Shopee Campaign</h5>
                        </div>
                        <div class="ibox-content text-center">
                            <div class="icons" style="border: 1px solid #ccc;padding: 25px;border-radius: 10px;background: #e76734;font-size:38px;color:#fff;">
                                <i class="fa fa-images" style="font-size:58px;"></i>
                                <br>
                                AMP Shopee
                            </div>
                            <br>
                            <a href="<?php echo e(url('master/campaign/create/shopee')); ?>" class="btn btn-secondary">create campaign</a>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-6">
                        <a class="btn btn-white" href="<?php echo e(url('master/campaign')); ?>">
                            <i class="fa fa-angle-left"></i> back
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php echo $__env->make('backend.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
</div>
<?php /**PATH /home2/zealsasi/new.zeals.asia/resources/views/backend/master/campaign/create_choose.blade.php ENDPATH**/ ?>