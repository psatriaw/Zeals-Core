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
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Campaign Control</h5>
                        </div>
                        <div class="ibox-content">
                            <?php if($errors->any()): ?>
                            <div class="alert alert-danger">
                                Ada kesalahan! mohon cek formulir.
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            </div>
                            <?php endif; ?>
                            <?php echo $__env->make('backend.flash_message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <?php echo Form::model($detail,['url' => url('master/campaign/storetarget'), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate novalidate']); ?>

                            <input type="hidden" name="id_campaign" value="<?=$detail->id_campaign?>">
                            <input type="hidden" name="campaign_link" value="<?=$detail->campaign_link?>">
                            <!-- campaign title -->
                            <div class="row">
                              <div class="col-lg-6 col-xs-12">
                                <h3>Detail Campaign</h3>

                                <div class="form-group <?php echo e(($errors->has('campaign_title')?"has-error":"")); ?>"><label class="col-sm-4 control-label">Campaign Title</label>
                                    <div class="col-sm-8 col-xs-12">
                                        <?php echo Form::text('campaign_title', $detail->campaign_title.' ['.$detail->campaign_link.']', ['class' => 'form-control','readonly']); ?>

                                        <?php echo $errors->first('campaign_title', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                    </div>
                                </div>

                                <!-- id user -->
                                <div class="form-group <?php echo e(($errors->has('id_penerbit')?"has-error":"")); ?>"><label class="col-sm-4 control-label">Brand</label>
                                    <div class="col-sm-8 col-xs-12">
                                        <?php if(@$id_penerbit==''){ ?>
                                            <?php echo Form::select('id_penerbit', $penerbits, @$id_penerbit, ['class' => 'form-control thetarget disabled','disabled' => true]); ?>

                                        <?php }else{?>
                                            <?php echo Form::text('id_penerbit', $penerbits[@$id_penerbit], ['class' => 'form-control',(@$id_penerbit!='')?'readonly':'']); ?>

                                        <?php }?>

                                        <?php echo $errors->first('id_penerbit', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                    </div>
                                </div>
                                <!-- campaign img -->
                                <div class="form-group <?php echo e(($errors->has('photos')?"has-error":"")); ?>"><label class="col-sm-4 control-label">Banner<sub>(6:4)</sub></label>
                                    <div class="col-sm-8 col-xs-12">
                                        <div class="">
                                          <div class="row">
                                            <div class="col-sm-6">
                                                <?php
                                                  $banner = $detail->photos;
                                                ?>
                                                <img class='img img-responsive img-thumbnail' src="<?=url($banner)?>">
                                            </div>
                                          </div>
                                        </div>
                                        <input type="file" name="photos" style="display:none;">
                                        <?php echo $errors->first('photos', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                    </div>
                                </div>
                                <div class="form-group <?php echo e(($errors->has('budget')?"has-error":"")); ?>"><label class="col-sm-4 control-label">Budget</label>
                                    <div class="col-sm-8 col-xs-12">
                                      <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <?php echo Form::number('budget', null, ['class' => 'form-control text-right']); ?>

                                      </div>
                                        <?php echo $errors->first('budget', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                    </div>
                                </div>
                                <!-- campaign description -->
                                <div class="form-group <?php echo e(($errors->has('max_commission')?"has-error":"")); ?>"><label class="col-sm-4 control-label">Max Commission <sub>(per influencer)</sub></label>
                                    <div class="col-sm-8 col-xs-12">
                                      <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <?php echo Form::number('max_commission', number_format($detail->max_commission,0,',','.'), ['class' => 'form-control text-right']); ?>

                                      </div>
                                        <?php echo $errors->first('max_commission', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                    </div>
                                </div>
                                <!-- campaign description -->
                                <div class="form-group <?php echo e(($errors->has('campaign_description')?"has-error":"")); ?>"><label class="col-sm-4 control-label">Campaign Description</label>
                                    <div class="col-sm-8 col-xs-12">
                                        <?php echo Form::textarea('campaign_description', null, ['class' => 'form-control','rows' => 10]); ?>

                                        <?php echo $errors->first('campaign_description', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                    </div>
                                </div>
                                <!-- campaign description -->
                                <div class="form-group <?php echo e(($errors->has('campaign_instruction')?"has-error":"")); ?>"><label class="col-sm-4 control-label">Campaign Instruction</label>
                                    <div class="col-sm-8 col-xs-12">
                                        <?php echo Form::textarea('campaign_instruction', null, ['class' => 'form-control','rows' => 10]); ?>

                                        <?php echo $errors->first('campaign_instruction', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                    </div>
                                </div>
                                <!-- Start date -->
                                <!-- campaign description -->
                                <div class="form-group <?php echo e(($errors->has('campaign_do_n_dont')?"has-error":"")); ?>"><label class="col-sm-4 control-label">Do & Don't</label>
                                    <div class="col-sm-8 col-xs-12">
                                        <?php echo Form::textarea('campaign_do_n_dont', null, ['class' => 'form-control','rows' => 10]); ?>

                                        <?php echo $errors->first('campaign_do_n_dont', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                    </div>
                                </div>
                                <!-- Start date -->
                                <div class="form-group <?php echo e(($errors->has('start_date')?"has-error":"")); ?>"><label class="col-sm-4 control-label">Start Date</label>
                                    <div class="col-sm-3 col-xs-12">
                                        <?php echo Form::date('start_date', null, ['class' => 'form-control']); ?>

                                        <?php echo $errors->first('start_date', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                    </div>
                                </div>
                                <!-- end date -->
                                <div class="form-group <?php echo e(($errors->has('end_date')?"has-error":"")); ?>"><label class="col-sm-4 control-label">End Date</label>
                                    <div class="col-sm-3 col-xs-12">
                                        <?php echo Form::date('end_date', null, ['class' => 'form-control']); ?>

                                        <?php echo $errors->first('end_date', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                    </div>
                                </div>

                              </div>
                              <div class="col-lg-6 col-xs-12">
                                <h3>Detail Program</h3>
                                <?php if(@$program['visit']){ ?>
                                <div class="form-group <?php echo e(($errors->has('visit_total')?"has-error":"")); ?>"><label class="col-sm-4 control-label">Unique Visitor</label>
                                    <div class="col-sm-6 col-xs-12">
                                      <div class="input-group">
                                        <?php echo Form::text('visit_total', number_format(@$program['visit']->total_item,0,',','.'), ['class' => 'form-control' ,'readonly' => true]); ?>

                                        <span class="input-group-addon">Visitor</span>
                                      </div>
                                    </div>
                                </div>

                                <div class="form-group <?php echo e(($errors->has('visit_total')?"has-error":"")); ?>"><label class="col-sm-4 control-label">Commission</label>
                                    <div class="col-sm-6 col-xs-12">
                                      <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <?php echo Form::text('visit_total', number_format(@$program['visit']->commission,0,',','.'), ['class' => 'form-control' ,'readonly' => true]); ?>

                                      </div>
                                    </div>
                                </div>

                                <div class="form-group <?php echo e(($errors->has('visit_total')?"has-error":"")); ?>"><label class="col-sm-4 control-label">Fee</label>
                                    <div class="col-sm-6 col-xs-12">
                                      <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <?php echo Form::text('visit_total', number_format(@$program['visit']->fee,0,',','.'), ['class' => 'form-control' ,'readonly' => true]); ?>

                                      </div>
                                    </div>
                                </div>
                                <?php } ?>

                                <?php if(@$program['read']){ ?>
                                <div class="hr-line-dashed"></div>
                                <div class="form-group <?php echo e(($errors->has('visit_total')?"has-error":"")); ?>"><label class="col-sm-4 control-label">Unique Reader</label>
                                    <div class="col-sm-6 col-xs-12">
                                      <div class="input-group">
                                        <?php echo Form::text('visit_total', number_format(@$program['read']->total_item,0,',','.'), ['class' => 'form-control' ,'readonly' => true]); ?>

                                        <span class="input-group-addon">Visitor</span>
                                      </div>
                                    </div>
                                </div>

                                <div class="form-group <?php echo e(($errors->has('visit_total')?"has-error":"")); ?>"><label class="col-sm-4 control-label">Commission</label>
                                    <div class="col-sm-6 col-xs-12">
                                      <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <?php echo Form::text('visit_total', number_format(@$program['read']->commission,0,',','.'), ['class' => 'form-control' ,'readonly' => true]); ?>

                                      </div>
                                    </div>
                                </div>

                                <div class="form-group <?php echo e(($errors->has('visit_total')?"has-error":"")); ?>"><label class="col-sm-4 control-label">Fee</label>
                                    <div class="col-sm-6 col-xs-12">
                                      <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <?php echo Form::text('visit_total', number_format(@$program['read']->fee,0,',','.'), ['class' => 'form-control' ,'readonly' => true]); ?>

                                      </div>
                                    </div>
                                </div>
                                <?php } ?>

                                <?php if(@$program['action']){ ?>
                                <div class="hr-line-dashed"></div>
                                <div class="form-group <?php echo e(($errors->has('visit_total')?"has-error":"")); ?>"><label class="col-sm-4 control-label">Unique Action</label>
                                    <div class="col-sm-6 col-xs-12">
                                      <div class="input-group">
                                        <?php echo Form::text('visit_total', number_format(@$program['action']->total_item,0,',','.'), ['class' => 'form-control' ,'readonly' => true]); ?>

                                        <span class="input-group-addon">Visitor</span>
                                      </div>
                                    </div>
                                </div>

                                <div class="form-group <?php echo e(($errors->has('visit_total')?"has-error":"")); ?>"><label class="col-sm-4 control-label">Commission</label>
                                    <div class="col-sm-6 col-xs-12">
                                      <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <?php echo Form::text('visit_total', number_format(@$program['action']->commission,0,',','.'), ['class' => 'form-control' ,'readonly' => true]); ?>

                                      </div>
                                    </div>
                                </div>

                                <div class="form-group <?php echo e(($errors->has('visit_total')?"has-error":"")); ?>"><label class="col-sm-4 control-label">Fee</label>
                                    <div class="col-sm-6 col-xs-12">
                                      <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <?php echo Form::text('visit_total', number_format(@$program['action']->fee,0,',','.'), ['class' => 'form-control' ,'readonly' => true]); ?>

                                      </div>
                                    </div>
                                </div>
                                <?php } ?>

                                <?php if(@$program['acquisition']){ ?>
                                <?php
                                  $callback_mode = true;
                                ?>
                                <div class="hr-line-dashed"></div>
                                <div class="form-group <?php echo e(($errors->has('visit_total')?"has-error":"")); ?>"><label class="col-sm-4 control-label">Unique Acquisition</label>
                                    <div class="col-sm-6 col-xs-12">
                                      <div class="input-group">
                                        <?php echo Form::text('visit_total', number_format(@$program['acquisition']->total_item,0,',','.'), ['class' => 'form-control' ,'readonly' => true]); ?>

                                        <span class="input-group-addon">Visitor</span>
                                      </div>
                                    </div>
                                </div>

                                <div class="form-group <?php echo e(($errors->has('visit_total')?"has-error":"")); ?>"><label class="col-sm-4 control-label">Commission</label>
                                    <div class="col-sm-6 col-xs-12">
                                      <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <?php echo Form::text('visit_total', number_format(@$program['acquisition']->commission,0,',','.'), ['class' => 'form-control' ,'readonly' => true]); ?>

                                      </div>
                                    </div>
                                </div>

                                <div class="form-group <?php echo e(($errors->has('visit_total')?"has-error":"")); ?>"><label class="col-sm-4 control-label">Fee</label>
                                    <div class="col-sm-6 col-xs-12">
                                      <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <?php echo Form::text('visit_total', number_format(@$program['acquisition']->fee,0,',','.'), ['class' => 'form-control' ,'readonly' => true]); ?>

                                      </div>
                                    </div>
                                </div>
                                <?php } ?>

                                <?php if(!@$program['visit'] && !@$program['read'] && !@$program['action'] && !@$program['acquisition']){ ?>
                                  <div class="alert alert-warning">
                                    <h3>Belum ada program!</h3>
                                    <p>Mohon memilih program melalui tombol berikut ini <a href="<?php echo e(url('master/campaign/setprogram/'.$detail->campaign_link)); ?>" class='btn btn-secondary btn-sm'>set program</a>.</p>
                                  </div>
                                <?php } ?>

                                <h3>Target</h3>
                                <!-- Target fund -->
                                <div class="form-group <?php echo e(($errors->has('id_categories')?"has-error":"")); ?>"><label class="col-sm-4 control-label">Target Categories</label>
                                    <div class="col-sm-8 col-xs-12">
                                      <?php
                                        if($categories){
                                          foreach ($categories as $key => $value) {
                                            ?>
                                            <label class='label label-default' style='margin-bottom:3px;display: inline-block;padding: 8px;font-size:13px;color:#777;'><?=$value->nama_sektor_industri?></label>
                                            <?php
                                          }
                                        }
                                      ?>
                                    </div>
                                </div>

                                <!-- Target fund -->
                                <div class="form-group <?php echo e(($errors->has('id_domisili')?"has-error":"")); ?>"><label class="col-sm-4 control-label">Domisili</label>
                                    <div class="col-sm-8 col-xs-12">
                                        <?php
                                          if($domisili){
                                            foreach ($domisili as $key => $value) {
                                              ?>
                                              <label class='label label-default' style='margin-bottom:3px;display: inline-block;padding: 8px;font-size:13px;color:#777;'><?=$value->namakab?></label>
                                              <?php
                                            }
                                          }
                                        ?>
                                    </div>
                                </div>

                                <!-- Target fund -->
                                <div class="form-group <?php echo e(($errors->has('estimation_affiliator')?"has-error":"")); ?>"><label class="col-sm-4 control-label">Affiliator</label>
                                    <div class="col-sm-6 col-xs-12">
                                      <div class="input-group">
                                        <?php echo Form::text('estimation_affiliator', null, ['class' => 'form-control' ,'readonly' => true,'id' => 'estimation_affiliator']); ?>

                                        <span class="input-group-addon">Accounts</span>
                                      </div>
                                        <?php echo $errors->first('estimation_affiliator', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                    </div>
                                </div>

                                <!-- Target fund -->
                                <div class="form-group <?php echo e(($errors->has('landing_url')?"has-error":"")); ?>"><label class="col-sm-4 control-label">Landing/Target URL</label>
                                    <div class="col-sm-6 col-xs-12">
                                        <?php echo Form::text('landing_url', null, ['class' => 'form-control' ,'readonly' => true]); ?>

                                        <?php echo $errors->first('landing_url', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                    </div>
                                </div>

                              </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="row">
                              <div class="col-lg-12 col-xs-12">
                                <h3>Tracker Configuration</h3>
                                <div class="form-group <?php echo e(($errors->has('id_domisili')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Tracker Code</label>
                                    <div class="col-sm-10 col-xs-12">
                                        <div class="pre-code">
                                            &lt;script src="<?=url('')?>/platform/js/zealsamp.js"&gt; &lt;/script&gt;
                                        </div>
                                        <h4>instructions:</h4>
                                        <p>Please put the code above into the landing/target page (<?=$detail->landing_url?>). Make sure the javascript tag works by clicking this button below</p>
                                        <button class='btn btn-sm btn-secondary' type="button">check tracker code</button>
                                    </div>
                                </div>

                                <div class="form-group <?php echo e(($errors->has('id_domisili')?"has-error":"")); ?>"><label class="col-sm-2 control-label" id="testurl">Test URL</label>
                                    <div class="col-sm-10 col-xs-12">
                                      <a href="<?=url('/link/ZEALS1234')?>" target="_blank" style="font-size:16px;margin-top: 5px;display: inline-block;font-weight: 600;color: #b7b60c;"><?=url('/link/ZEALS1234')?></a>
                                    </div>
                                </div>
                                <?php if(@$callback_mode){ ?>
                                  <div class="alert alert-info text-small">You are using "acquisition program" for this campaign, you may need one of the endpoints below to make a callback to our system</div>
                                  <div class="form-group <?php echo e(($errors->has('id_domisili')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Callback Endpoint <sub>(PHP)</sub></label>
                                      <div class="col-sm-10 col-xs-12">
                                          <div class="pre-code">
                                              file_get_contents('<?=url('')?>/platform/api/AMPcallback/<strong>[affiliate_ID]</strong>/<?=$detail->campaign_link?>');<br>
                                              <br><br>
                                              Your Affiliator_ID test : <strong>ZEALS1234</strong>
                                          </div>
                                          <h4>instructions:</h4>
                                          <p class="intro">
                                              Please put the code above into the landing/target page assumed as the final/target page after acquisition process done. Make sure the code works with simple flow test<br>
                                              through the <a href="<?=url('/link/ZEALS1234')?>" target="_blank" style="font-weight: 600;color: #b7b60c;"><?=url('/link/ZEALS1234')?></a> until the final/target page accessed then make sure this button below colored in green.
                                          </p>
                                          <button class='btn btn-sm btn-danger' type="button">no acquisition test/transaction in last 5 minutes</button>
                                      </div>
                                  </div>

                                  <div class="form-group <?php echo e(($errors->has('id_domisili')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Callback Endpoint <sub>(javascript)</sub></label>
                                      <div class="col-sm-10 col-xs-12">
                                        <div class="pre-code">
                                            &lt;script src="<?=url('')?>/platform/js/zealsamp.js"&gt; &lt;/script&gt;<br>
                                            &lt;script&gt; <br>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;makeACall();<br>
                                            &lt;/script&gt;
                                        </div>
                                        <h4>instructions:</h4>
                                        <p class="intro">
                                            Please put the code above into the landing/target page assumed as the final/target page after acquisition process done. Make sure the code works with simple flow test<br>
                                            through the <a href="<?=url('/link/ZEALS1234')?>" target="_blank" style="font-weight: 600;color: #b7b60c;"><?=url('/link/ZEALS1234')?></a>  until the final/target page accessed then make sure the javascript tag works by clicking this button below.
                                        </p>
                                        <button class='btn btn-sm btn-secondary' type="button">check JS callback</button>
                                      </div>
                                  </div>
                                <?php } ?>
                              </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <div class="col-sm-6 col-sm-offset-2">
                                    <a class="btn btn-white" href="<?php echo e(url('master/campaign')); ?>">
                                        <i class="fa fa-angle-left"></i> back
                                    </a>
                                </div>
                                <div class="col-sm-4 text-right">
                                  <a href="<?php echo e(url('master/campaign/report/'.$detail->campaign_link.'?backlink=master/campaign/resume/'.$detail->campaign_link)); ?>" class="btn btn-info btn-sm confirm btn-rounded" type="submit"><i class="fa fa-chart-pie"></i> Statistic/Report</a>

                                  <?php if($detail->running_status=="close"){ ?>
                                    <a data-url="<?php echo e(url('master/campaign/setrun/?backlink=master/campaign/resume/'.$detail->campaign_link)); ?>" data-id="<?=$detail->id_campaign?>" class="btn btn-primary btn-sm confirm" type="submit"><i class="fa fa-play"></i> Run Campaign</a>
                                  <?php }else{ ?>
                                    <a data-url="<?php echo e(url('master/campaign/setrun/?backlink=master/campaign/resume/'.$detail->campaign_link)); ?>" data-id="<?=$detail->id_campaign?>" class="btn btn-danger btn-sm confirm" type="submit"><i class="fa fa-stop"></i> Stop Campaign</a>
                                  <?php } ?>
                                </div>
                            </div>
                            <?php echo Form::close(); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php echo $__env->make('backend.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php echo $__env->make('backend.do_confirm', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
</div>

<script>

</script>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/myzeals/resources/views/backend/master/campaign/resume.blade.php ENDPATH**/ ?>