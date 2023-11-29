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
                    <li class="active"><a href="javascript:void(0);">Program</a></li>
                    <li class="next"><a href="javascript:void(0);">Target</a></li>
                    <?php if($detail->campaign_type=="o2o"){ ?>
                    <li class="next"><a href="javascript:void(0);">Outlet Setup</a></li>
                    <?php } ?>
                    <li class="next"><a href="javascript:void(0);">Ready</a></li>
                  </ul>
                  <br>

                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Program Campaign</h5>
                        </div>
                        <div class="ibox-content">
                            <?php if($errors->any()): ?>
                            <div class="alert alert-danger">
                                Some error due to your input. Please check the fields!
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            </div>
                            <?php endif; ?>
                            <?php echo $__env->make('backend.flash_message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <?php echo Form::model($detail,['url' => url('master/campaign/storeprogram'), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate novalidate']); ?>

                            <input type="hidden" name="id_campaign" value="<?=$detail->id_campaign?>">
                            <input type="hidden" name="campaign_link" value="<?=$detail->campaign_link?>">
                            <!-- campaign title -->
                            <div class="form-group <?php echo e(($errors->has('campaign_title')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Campaign Detail</label>
                                <div class="col-sm-6 col-xs-12">
                                    <?php echo Form::text('campaign_title', $detail->campaign_title.' ['.$detail->campaign_link.']', ['class' => 'form-control','readonly']); ?>

                                    <?php echo $errors->first('campaign_title', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                </div>
                            </div>


                            <?php if($detail->campaign_type=="banner"){ ?>
                              <div class="hr-line-dashed"></div>
                              <div class="col-sm-12">
                                <div class="row">
                                  <h3 class="text-black col-sm-2">Unique Visitor</h3>
                                </div>
                              </div>
                              <!-- Target fund -->
                              <div class="form-group <?php echo e(($errors->has('visit_total')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Max visitor</label>
                                  <div class="col-sm-3 col-xs-12">
                                    <div class="input-group">
                                      <?php echo Form::text('visit_total', null, ['class' => 'form-control' ,'multiple' => true]); ?>

                                      <span class="input-group-addon" style="background:#5eb6f0 !important;">Visitors</span>
                                    </div>
                                      <?php echo $errors->first('visit_total', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                  </div>
                              </div>

                              <!-- Target fund -->
                              <div class="form-group <?php echo e(($errors->has('visit_commission')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Commission per visitor </label>
                                  <div class="col-sm-3 col-xs-12">
                                    <div class="input-group">
                                      <span class="input-group-addon" style="background:#5eb6f0 !important;">Rp. </span>
                                      <?php echo Form::text('visit_commission', null, ['class' => 'form-control' ,'multiple' => true]); ?>

                                    </div>
                                      <?php echo $errors->first('visit_commission', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                  </div>
                              </div>

                              <!-- Target fund -->
                              <div class="form-group <?php echo e(($errors->has('visit_fee')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Fee per visitor </label>
                                  <div class="col-sm-3 col-xs-12">
                                      <div class="input-group">
                                        <span class="input-group-addon" style="background:#5eb6f0 !important;">Rp. </span>
                                        <?php echo Form::text('visit_fee', null, ['class' => 'form-control' ,'multiple' => true]); ?>

                                      </div>
                                      <?php echo $errors->first('visit_fee', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                  </div>
                              </div>


                              <div class="hr-line-dashed"></div>

                              <div class="col-sm-12">
                                <div class="row">
                                  <h3 class="text-black col-sm-2">Unique Reader/Stay</h3>
                                </div>
                              </div>
                              <!-- Target fund -->
                              <div class="form-group <?php echo e(($errors->has('reader_total')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Max reader</label>
                                  <div class="col-sm-3 col-xs-12">
                                    <div class="input-group">
                                      <?php echo Form::text('reader_total', null, ['class' => 'form-control' ,'multiple' => true]); ?>

                                      <span class="input-group-addon" style="background:#c82360 !important;">Readers</span>
                                    </div>
                                      <?php echo $errors->first('reader_total', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                  </div>
                              </div>

                              <!-- Target fund -->
                              <div class="form-group <?php echo e(($errors->has('reader_commission')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Commission per reader </label>
                                  <div class="col-sm-3 col-xs-12">
                                    <div class="input-group">
                                      <span class="input-group-addon" style="background:#c82360 !important;">Rp. </span>
                                      <?php echo Form::text('reader_commission', null, ['class' => 'form-control' ,'multiple' => true]); ?>

                                    </div>
                                      <?php echo $errors->first('reader_commission', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                  </div>
                              </div>

                              <!-- Target fund -->
                              <div class="form-group <?php echo e(($errors->has('reader_fee')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Fee per reader </label>
                                  <div class="col-sm-3 col-xs-12">
                                      <div class="input-group">
                                        <span class="input-group-addon" style="background:#c82360 !important;">Rp. </span>
                                        <?php echo Form::text('reader_fee', null, ['class' => 'form-control' ,'multiple' => true]); ?>

                                      </div>
                                      <?php echo $errors->first('reader_fee', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                  </div>
                              </div>

                              <div class="hr-line-dashed"></div>

                              <div class="col-sm-12">
                                <div class="row">
                                  <h3 class="text-black col-sm-2">Unique Action</h3>
                                </div>
                              </div>
                              <!-- Target fund -->
                              <div class="form-group <?php echo e(($errors->has('action_total')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Max action</label>
                                  <div class="col-sm-3 col-xs-12">
                                    <div class="input-group">
                                      <?php echo Form::text('action_total', null, ['class' => 'form-control' ,'multiple' => true]); ?>

                                      <span class="input-group-addon" style="background:#fcb13b !important;">Actions</span>
                                    </div>
                                      <?php echo $errors->first('action_total', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                  </div>
                              </div>

                              <!-- Target fund -->
                              <div class="form-group <?php echo e(($errors->has('action_commission')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Commission per action </label>
                                  <div class="col-sm-3 col-xs-12">
                                    <div class="input-group">
                                      <span class="input-group-addon" style="background:#fcb13b !important;">Rp. </span>
                                      <?php echo Form::text('action_commission', null, ['class' => 'form-control' ,'multiple' => true]); ?>

                                    </div>
                                      <?php echo $errors->first('action_commission', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                  </div>
                              </div>

                              <!-- Target fund -->
                              <div class="form-group <?php echo e(($errors->has('action_fee')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Fee per action </label>
                                  <div class="col-sm-3 col-xs-12">
                                      <div class="input-group">
                                        <span class="input-group-addon" style="background:#fcb13b !important;">Rp. </span>
                                        <?php echo Form::text('action_fee', null, ['class' => 'form-control' ,'multiple' => true]); ?>

                                      </div>
                                      <?php echo $errors->first('action_fee', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                  </div>
                              </div>

                              <div class="hr-line-dashed"></div>
                              <div class="col-sm-12">
                                <div class="row">
                                  <h3 class="text-black col-sm-2">Unique Acquisition</h3>
                                </div>
                              </div>
                              <!-- Target fund -->
                              <div class="form-group <?php echo e(($errors->has('acquisition_total')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Max acquisition</label>
                                  <div class="col-sm-3 col-xs-12">
                                    <div class="input-group">
                                      <?php echo Form::text('acquisition_total', null, ['class' => 'form-control' ,'multiple' => true]); ?>

                                      <span class="input-group-addon" style="background:#961515 !important;">Acquisitions</span>
                                    </div>
                                      <?php echo $errors->first('acquisition_total', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                  </div>
                              </div>

                              <!-- Target fund -->
                              <div class="form-group <?php echo e(($errors->has('acquisition_commission')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Commission per acquisition </label>
                                  <div class="col-sm-3 col-xs-12">
                                    <div class="input-group">
                                      <span class="input-group-addon" style="background:#961515 !important;">Rp. </span>
                                      <?php echo Form::text('acquisition_commission', null, ['class' => 'form-control' ,'multiple' => true]); ?>

                                    </div>
                                      <?php echo $errors->first('acquisition_commission', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                  </div>
                              </div>

                              <!-- Target fund -->
                              <div class="form-group <?php echo e(($errors->has('acquisition_fee')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Fee per acquisition </label>
                                  <div class="col-sm-3 col-xs-12">
                                      <div class="input-group">
                                        <span class="input-group-addon" style="background:#961515 !important;">Rp. </span>
                                        <?php echo Form::text('acquisition_fee', null, ['class' => 'form-control' ,'multiple' => true]); ?>

                                      </div>
                                      <?php echo $errors->first('acquisition_fee', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                  </div>
                              </div>

                            <?php }elseif($detail->campaign_type=="o2o"){ ?>

                              <div class="hr-line-dashed"></div>
                              <div class="col-sm-12">
                                <div class="row">
                                  <h3 class="text-black col-sm-2">Voucher Budget</h3>
                                </div>
                              </div>
                              <!-- Target fund -->
                              <div class="form-group <?php echo e(($errors->has('voucher_total')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Max voucher</label>
                                  <div class="col-sm-3 col-xs-12">
                                    <div class="input-group">
                                      <?php echo Form::text('voucher_total', null, ['class' => 'form-control' ,'multiple' => true]); ?>

                                      <span class="input-group-addon" style="background:#961515 !important;">vouchers</span>
                                    </div>
                                      <?php echo $errors->first('voucher_total', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                  </div>
                              </div>

                              <!-- Target fund -->
                              <div class="form-group <?php echo e(($errors->has('voucher_commission')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Commission per redemption </label>
                                  <div class="col-sm-3 col-xs-12">
                                    <div class="input-group">
                                      <span class="input-group-addon" style="background:#961515 !important;">Rp. </span>
                                      <?php echo Form::text('voucher_commission', null, ['class' => 'form-control' ,'multiple' => true]); ?>

                                    </div>
                                      <?php echo $errors->first('voucher_commission', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                  </div>
                              </div>

                              <!-- Target fund -->
                              <div class="form-group <?php echo e(($errors->has('voucher_fee')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Fee per redemption </label>
                                  <div class="col-sm-3 col-xs-12">
                                      <div class="input-group">
                                        <span class="input-group-addon" style="background:#961515 !important;">Rp. </span>
                                        <?php echo Form::text('voucher_fee', null, ['class' => 'form-control' ,'multiple' => true]); ?>

                                      </div>
                                      <?php echo $errors->first('voucher_fee', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                  </div>
                              </div>

                            <?php }elseif($detail->campaign_type=="shopee"){ ?>
                              <div class="hr-line-dashed"></div>
                              <div class="col-sm-12">
                                <div class="row">
                                  <h3 class="text-black col-sm-2">Sales/Transaction Budget</h3>
                                </div>
                              </div>
                              <!-- Target fund -->
                              <div class="form-group <?php echo e(($errors->has('voucher_total')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Max transaction</label>
                                  <div class="col-sm-3 col-xs-12">
                                    <div class="input-group">
                                      <?php echo Form::text('voucher_total', null, ['class' => 'form-control' ,'multiple' => true]); ?>

                                      <span class="input-group-addon" style="background:#e76734 !important;border-top-right-radius:15px;border-bottom-right-radius:15px;">transactions</span>
                                    </div>
                                      <?php echo $errors->first('voucher_total', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                  </div>
                              </div>

                              <!-- Target fund -->
                              <div class="form-group <?php echo e(($errors->has('voucher_commission')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Commission per transaction </label>
                                  <div class="col-sm-3 col-xs-12">
                                    <div class="input-group">
                                      <?php echo Form::text('voucher_commission', null, ['class' => 'form-control' ,'multiple' => true]); ?>

                                      <span class="input-group-addon" style="background:#e76734 !important;border-top-right-radius:15px;border-bottom-right-radius:15px;">%</span>
                                    </div>
                                      <?php echo $errors->first('voucher_commission', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                  </div>
                              </div>

                              <!-- Target fund -->
                              <div class="form-group <?php echo e(($errors->has('voucher_fee')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Fee per transaction </label>
                                  <div class="col-sm-3 col-xs-12">
                                      <div class="input-group">
                                        <?php echo Form::text('voucher_fee', null, ['class' => 'form-control' ,'multiple' => true]); ?>

                                        <span class="input-group-addon" style="background:#e76734 !important;border-top-right-radius:15px;border-bottom-right-radius:15px;">%</span>
                                      </div>
                                      <?php echo $errors->first('voucher_fee', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                  </div>
                              </div>

                              <!-- Target fund -->
                              <div class="form-group <?php echo e(($errors->has('custom_link')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Shopee URL</label>
                                  <div class="col-sm-6 col-xs-12">
                                    <div>
                                      <?php echo Form::text('custom_link', null, ['class' => 'form-control' ,'multiple' => true]); ?>


                                      <?php echo $errors->first('custom_link', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                    </div>
                                    <div class="help-block">
                                      Please make sure that the customable utm parameter is at the end of link
                                    </div>
                                  </div>

                              </div>
                            <?php } ?>

                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <div class="col-sm-6 col-sm-offset-2">
                                    <a class="btn btn-white" href="<?php echo e(url('master/campaign')); ?>">
                                        <i class="fa fa-angle-left"></i> back
                                    </a>
                                </div>
                                <div class="col-sm-4 text-right">
                                    <button class="btn btn-primary" type="submit">Save & Set Target <i class="fa fa-angle-right"></i></button>
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
        })
    });
</script>
<?php /**PATH /home2/zealsasi/new.zeals.asia/resources/views/backend/master/campaign/set_program.blade.php ENDPATH**/ ?>