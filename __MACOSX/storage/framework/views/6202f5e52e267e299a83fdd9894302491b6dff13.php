<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<div id="wrapper">
    <?php echo $__env->make('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div id="page-wrapper" class="gray-bg">
      <?php echo $__env->make('backend.menus.top',array("login" => $login, "previlege" => $previlege), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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
                        <strong>Tambah Campaign</strong>
                    </li>
                </ol>
            </div>
            <div class="col-lg-2">

            </div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                  <ul class="c-breadcrumb">
                    <li class="completed"><a href="javascript:void(0);">Campaign Detail</a></li>
                    <li class="completed"><a href="javascript:void(0);">Program</a></li>
                    <li class="active"><a href="javascript:void(0);">Target</a></li>
                    <?php if($detail->campaign_type=="o2o"){ ?>
                    <li class="next"><a href="javascript:void(0);">Outlet Setup</a></li>
                    <?php } ?>
                    <li class="next"><a href="javascript:void(0);">Ready</a></li>
                  </ul>
                  <br>
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Target Campaign [Affiliator]</h5>
                        </div>
                        <div class="ibox-content">
                            <?php if($errors->any()): ?>
                            <div class="alert alert-danger">
                                Some error due to your input. Please check the fields!
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            </div>
                            <?php endif; ?>
                            <?php echo $__env->make('backend.flash_message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <?php echo Form::model($detail,['url' => url('master/campaign/storetarget'), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate novalidate']); ?>

                            <input type="hidden" name="id_campaign" value="<?=$detail->id_campaign?>">
                            <input type="hidden" name="campaign_link" value="<?=$detail->campaign_link?>">
                            <!-- campaign title -->
                            <div class="form-group <?php echo e(($errors->has('campaign_title')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Campaign Title</label>
                                <div class="col-sm-6 col-xs-12">
                                    <?php echo Form::text('campaign_title', $detail->campaign_title.' ['.$detail->campaign_link.']', ['class' => 'form-control','readonly']); ?>

                                    <?php echo $errors->first('campaign_title', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                </div>
                            </div>

                            <!-- Target fund -->
                            <div class="form-group <?php echo e(($errors->has('id_categories')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Target Categories</label>
                                <div class="col-sm-6 col-xs-12">
                                    <?php echo Form::select('id_categories[]', $categories, null, ['class' => 'form-control categories' ,'multiple' => true]); ?>

                                    <?php echo $errors->first('id_categories', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                </div>
                            </div>

                            <!-- Target fund -->
                            <div class="form-group <?php echo e(($errors->has('id_domisili')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Domisili</label>
                                <div class="col-sm-6 col-xs-12">
                                    <?php echo Form::select('id_domisili[]', $domisili, null, ['class' => 'form-control categories' ,'multiple' => true]); ?>

                                    <?php echo $errors->first('id_domisili', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                </div>
                            </div>

                            <!-- Target fund -->
                            <div class="form-group <?php echo e(($errors->has('estimation_affiliator')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Affiliator</label>
                                <div class="col-sm-3 col-xs-12">
                                  <div class="input-group">
                                    <?php echo Form::text('estimation_affiliator', null, ['class' => 'form-control' ,'readonly' => true,'id' => 'estimation_affiliator']); ?>

                                    <span class="input-group-addon">Accounts</span>
                                  </div>
                                    <?php echo $errors->first('estimation_affiliator', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                </div>
                            </div>
                            <?php if($detail->campaign_type=="banner"){ ?>
                            <!-- campaign title -->
                            <div class="form-group <?php echo e(($errors->has('landing_url')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Landing/Target URL</label>
                                <div class="col-sm-6 col-xs-12">
                                    <?php echo Form::text('landing_url', null, ['class' => 'form-control','placeholder' => 'https://abcdef.com/register']); ?>

                                    <?php echo $errors->first('landing_url', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                </div>
                            </div>

                          <?php }?>

                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <div class="col-sm-6 col-sm-offset-2">
                                    <a class="btn btn-white" href="<?php echo e(url('master/campaign')); ?>">
                                        <i class="fa fa-angle-left"></i> back
                                    </a>
                                </div>
                                <div class="col-sm-4 text-right">
                                    <button class="btn btn-primary" type="submit">Save & Play the Campaign <i class="fa fa-angle-right"></i></button>
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
        $(document).ready(function() {
            $('.thetarget, .thesender').select2({
                ajax: {
                    url: '<?php echo e(url("get-list-penerbit")); ?>',
                    dataType: 'json',
                    data: function(params) {
                        var query = {
                            search: params.term,
                            type: 'public'
                        }
                        // Query parameters will be ?search=[term]&type=public
                        return query;
                    }
                }
            });
        })

        $('.categories').select2();
        $('.categories').on('select2:select', function (e) {
          $.each(".select2-selection__choice").function()
        });
        $(".categories").keydown(function(e){
          if(e.keyCode==13){
            return false;
          }
        });

        $("#formmain").keydown(function(e){
          if(e.keyCode==13){
            return false;
          }
        })
    });
</script>
<?php /**PATH /home2/zealsasi/new.zeals.asia/resources/views/backend/master/campaign/set_target.blade.php ENDPATH**/ ?>