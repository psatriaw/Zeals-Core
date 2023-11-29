<?php
  $main_url = $config['main_url'];
?>
<div id="wrapper">
  <?php echo $__env->make('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  <div id="page-wrapper" class="gray-bg">
    <?php echo $__env->make('backend.menus.top',array("login" => $login, "previlege" => $previlege), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Account</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo e(url('dashboard/view')); ?>">Dashboard</a>
                    </li>
                    <li class="active">
                        <strong>Account</strong>
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
                        <h5>Account</h5>
                    </div>
                    <div class="ibox-content">
                      <?php if($errors->any()): ?>
                          <div class="alert alert-danger">
                              Oops! we found error! Please take a look!
                              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                          </div>
                      <?php endif; ?>
                      <?php echo $__env->make('backend.flash_message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                      <?php echo Form::model($data,['url' => url('admin/user/update/'.$data->id_user), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']); ?>

                      <div class="row">
                        <div class="col-sm-6 col-xs-12">
                          <h3>Personal Information</h3>

                            <input type="hidden" name="backlink" value="<?=url('admin/user/profile/'.$data->id_user)?>">
                            <div class="form-group <?php echo e(($errors->has('first_name')?"has-error":"")); ?>"><label class="col-sm-4 control-label">First Name</label>
                                <div class="col-sm-8 col-xs-12">
                                    <?php echo Form::text('first_name', null, ['class' => 'form-control']); ?>

                                    <?php echo $errors->first('first_name', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                </div>
                            </div>

                            <div class="form-group <?php echo e(($errors->has('last_name')?"has-error":"")); ?>"><label class="col-sm-4 control-label">Last Name</label>
                                <div class="col-sm-8 col-xs-12">
                                    <?php echo Form::text('last_name', null, ['class' => 'form-control']); ?>

                                    <?php echo $errors->first('last_name', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                </div>
                            </div>

                            <div class="form-group <?php echo e(($errors->has('email')?"has-error":"")); ?>"><label class="col-sm-4 control-label">Email</label>
                                <div class="col-sm-8 col-xs-12">
                                    <?php echo Form::email('email', null, ['class' => 'form-control','autocomplete' => "off"]); ?>

                                    <?php echo $errors->first('email', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                    <span class="help-block alert alert-info">If you are going to change this part, the value must be unique/never used</span>
                                </div>
                            </div>

                            <div class="form-group <?php echo e(($errors->has('phone')?"has-error":"")); ?>"><label class="col-sm-4 control-label">Phone</label>
                                <div class="col-sm-8 col-xs-12">
                                    <?php echo Form::text('phone', null, ['class' => 'form-control']); ?>

                                    <?php echo $errors->first('phone', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                </div>
                            </div>

                            <div class="form-group <?php echo e(($errors->has('username')?"has-error":"")); ?>"><label class="col-sm-4 control-label">Username</label>
                                <div class="col-sm-8 col-xs-12">
                                    <?php echo Form::text('username', null, ['class' => 'form-control']); ?>

                                    <?php echo $errors->first('username', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                    <span class="help-block alert alert-info">If you are going to change this part, the value must be unique/never used</span>
                                </div>
                            </div>

                            <div class="form-group <?php echo e(($errors->has('address')?"has-error":"")); ?>"><label class="col-sm-4 control-label">Address</label>
                                <div class="col-sm-8 col-xs-12">
                                    <?php echo Form::textarea('address', null, ['class' => 'form-control']); ?>

                                    <?php echo $errors->first('address', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                </div>
                            </div>


                            <?php if($previlege->isAllow($login->id_user,$login->id_department,"profile-reset-password")){?>
                            <div class="form-group <?php echo e(($errors->has('password')?"has-error":"")); ?>"><label class="col-sm-4 control-label">Password</label>
                                <div class="col-sm-8 col-xs-12">
                                    <?php echo Form::password('password', ['class' => 'form-control']); ?>

                                    <?php echo $errors->first('password', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                    <span class="alert alert-danger help-block">Just let it blank if you are not going to change this part. <br>It is recommended to use more than 6 digits containing alphabet + numeric.</span>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="col-sm-6 col-xs-12">
                          <?php if($brand!=""){ ?>
                          <h3>Brand information</h3>

                          <div class="form-group <?php echo e(($errors->has('address')?"has-error":"")); ?>"><label class="col-sm-4 control-label">Brand Name</label>
                              <div class="col-sm-8 col-xs-12">
                                  <?php echo Form::text('address', $brand->nama_penerbit, ['class' => 'form-control','readonly']); ?>

                                  <?php echo $errors->first('address', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                              </div>
                          </div>
                          <div class="form-group <?php echo e(($errors->has('address')?"has-error":"")); ?>"><label class="col-sm-4 control-label">No Telp</label>
                              <div class="col-sm-8 col-xs-12">
                                  <?php echo Form::text('address', $brand->no_telp, ['class' => 'form-control','readonly']); ?>

                                  <?php echo $errors->first('address', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                  <div class="alert alert-warning top5">
                                    If the informations are not valid, you need to contact our Administrator.
                                  </div>
                              </div>
                          </div>

                          <?php } ?>
                        </div>
                      </div>
                      <div class="hr-line-dashed"></div>
                      <div class="form-group">
                          <div class="col-sm-4 col-sm-offset-2">
                              <a class="btn btn-white" href="<?php echo e(url($main_url)); ?>">
                                  <i class="fa fa-angle-left"></i> back
                              </a>
                          </div>
                          <div class="col-sm-6 text-right">
                            <button class="btn btn-white" type="reset">Reset</button>
                            <button class="btn btn-primary btn-rounded" type="submit">Save Changes</button>
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
  function changeTypeUser(type){
    if(type==1){
      $("#internalyes").fadeIn(200);
    }else{
      $("#internalyes").fadeOut(200);
    }
  }

  function formatter(numberString){
		return numberString.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
	}

  function formatNumber(numberString, id) {
    var thestring = String(numberString);
		var ret = 0;

		if(!$.isNumeric(thestring)){
			thestring = thestring.split(".").join("");
			console.log(thestring);
			ret = formatter(thestring);
      if(ret!="0"){
  			$("#"+id).val(ret);
      }
		}else{
			thestring = thestring.replace(".","");
			ret 	= formatter(thestring);
      if(ret!="0"){
  			$("#"+id).val(ret);
      }
		}
	}

  $(document).ready(function(){
    <?php if($data->tipe_user==1){ ?>
      setTimeout(function(){
        formatNumber(<?=($data->gaji!="")?$data->gaji:"0"?>,'gaji');
        formatNumber(<?=($data->insentif!="")?$data->insentif:"0"?>,'insentif');
        formatNumber(<?=($data->uang_makan!="")?$data->uang_makan:"0"?>,'uang_makan');
      },400);
    <?php } ?>
  });
</script>
<?php /**PATH /home2/zealsasi/new.zeals.asia/resources/views/backend/master/user/profile.blade.php ENDPATH**/ ?>