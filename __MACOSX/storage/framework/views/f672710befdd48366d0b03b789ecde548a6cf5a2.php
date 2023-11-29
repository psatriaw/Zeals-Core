<?php
  $main_url = $config['main_url'];
?>
<div id="wrapper">
  <?php echo $__env->make('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  <div id="page-wrapper" class="gray-bg">
    <?php echo $__env->make('backend.menus.top',array("login" => $login, "previlege" => $previlege), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Akun Pengguna</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo e(url('dashboard/view')); ?>">Dashboard</a>
                    </li>
                    <li>
                        <a href="<?php echo e(url($main_url)); ?>">Akun Pengguna</a>
                    </li>
                    <li class="active">
                        <strong>Tambah Pengguna</strong>
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
                        <h5>Tambah Pengguna</h5>
                    </div>
                    <div class="ibox-content">
                      <?php if($errors->any()): ?>
                          <div class="alert alert-danger">
                              Ada kesalahan! mohon cek formulir.
                              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                          </div>
                      <?php endif; ?>

                      <?php echo $__env->make('backend.flash_message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                      <?php echo Form::open(['url' => url('admin/user/store'), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']); ?>


                        <div class="form-group <?php echo e(($errors->has('first_name')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Nama Depan</label>
                            <div class="col-sm-4 col-xs-12">
                                <?php echo Form::text('first_name', null, ['class' => 'form-control']); ?>

                                <?php echo $errors->first('first_name', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                            </div>
                        </div>

                        <div class="form-group <?php echo e(($errors->has('last_name')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Nama Belakang</label>
                            <div class="col-sm-4 col-xs-12">
                                <?php echo Form::text('last_name', null, ['class' => 'form-control']); ?>

                                <?php echo $errors->first('last_name', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                            </div>
                        </div>

                        <div class="form-group <?php echo e(($errors->has('email')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-4 col-xs-12">
                                <?php echo Form::email('email', null, ['class' => 'form-control']); ?>

                                <?php echo $errors->first('email', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                <span class="help-block">Harus unik/belum pernah digunakan</span>
                            </div>
                        </div>

                        <div class="form-group <?php echo e(($errors->has('phone')?"has-error":"")); ?>"><label class="col-sm-2 control-label">No. Telp</label>
                            <div class="col-sm-4 col-xs-12">
                                <?php echo Form::text('phone', null, ['class' => 'form-control']); ?>

                                <?php echo $errors->first('phone', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                            </div>
                        </div>

                        <div class="form-group <?php echo e(($errors->has('username')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Username</label>
                            <div class="col-sm-4 col-xs-12">
                                <?php echo Form::text('username', null, ['class' => 'form-control']); ?>

                                <?php echo $errors->first('username', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                                <span class="help-block">Harus unik/belum pernah digunakan</span>
                            </div>
                        </div>

                        <div class="form-group <?php echo e(($errors->has('id_department')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Department</label>
                            <div class="col-sm-4 col-xs-12">
                                <?php echo Form::select('id_department', $optdepartment, null, ['class' => 'form-control']); ?>

                                <?php echo $errors->first('id_department', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                            </div>
                        </div>

                        <div class="form-group <?php echo e(($errors->has('address')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Alamat</label>
                            <div class="col-sm-4 col-xs-12">
                                <?php echo Form::textarea('address', null, ['class' => 'form-control']); ?>

                                <?php echo $errors->first('address', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                            </div>
                        </div>

                        <div class="form-group <?php echo e(($errors->has('password')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Password</label>
                            <div class="col-sm-4 col-xs-12">
                                <?php echo Form::password('password', ['class' => 'form-control']); ?>

                                <?php echo $errors->first('password', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                            </div>
                        </div>

                        <div class="form-group <?php echo e(($errors->has('tipe_user')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Tipe Pegawai</label>
                            <div class="col-sm-4 col-xs-12">
                              <?php echo Form::select('tipe_user', ['0' => 'External', '1' => 'Internal (Bergaji)'], null, ['class' => 'form-control','onchange' => 'changeTypeUser(this.value)']); ?>

                              <?php echo $errors->first('tipe_user', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                            </div>
                        </div>
                        <div style="<?=($errors->has('gaji') || $errors->has('insentif') || old('tipe_user')==1)?"":"display:none;"?>" id="internalyes">

                          <div class="form-group <?php echo e(($errors->has('gaji')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Gaji Pokok</label>
                              <div class="col-sm-4 col-xs-12">
                                <div class="input-group">
                                  <span class="input-group-addon">Rp. </span>
                                  <?php echo Form::text('gaji',null, ['class' => 'form-control','onkeyup' => 'formatNumber(this.value,"gaji")','id' => 'gaji']); ?>

                                  <span class="input-group-addon">per bulan</span>
                                </div>
                                  <?php echo $errors->first('gaji', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                              </div>
                          </div>

                          <div class="form-group <?php echo e(($errors->has('insentif')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Insentif</label>
                              <div class="col-sm-4 col-xs-12">
                                <div class="input-group">
                                  <span class="input-group-addon">Rp. </span>
                                  <?php echo Form::text('insentif',null, ['class' => 'form-control','onkeyup' => 'formatNumber(this.value,"insentif")','id' => 'insentif']); ?>

                                  <span class="input-group-addon">per hari</span>
                                </div>
                                  <?php echo $errors->first('insentif', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                              </div>
                          </div>

                          <div class="form-group <?php echo e(($errors->has('uang_makan')?"has-error":"")); ?>"><label class="col-sm-2 control-label">Uang Makan</label>
                              <div class="col-sm-4 col-xs-12">
                                <div class="input-group">
                                  <span class="input-group-addon">Rp. </span>
                                  <?php echo Form::text('uang_makan',null, ['class' => 'form-control','onkeyup' => 'formatNumber(this.value,"uang_makan")','id' => 'uang_makan']); ?>

                                  <span class="input-group-addon">per hari</span>
                                </div>
                                  <?php echo $errors->first('uang_makan', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                              </div>
                          </div>

                          <div class="form-group <?php echo e(($errors->has('id_reff')?"has-error":"")); ?>"><label class="col-sm-2 control-label">ID REFF</label>
                              <div class="col-sm-4 col-xs-12">
                                  <?php echo Form::text('id_reff',null, ['class' => 'form-control']); ?>

                                  <?php echo $errors->first('id_reff', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>'); ?>

                              </div>
                          </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                                <a class="btn btn-white" href="<?php echo e(url($main_url)); ?>">
                                    <i class="fa fa-angle-left"></i> kembali
                                </a>
                            </div>
                            <div class="col-sm-6 text-right">
                              <button class="btn btn-white" type="reset">Reset</button>
                              <button class="btn btn-primary btn-rounded" type="submit">Simpan</button>
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
    <?php if(old('tipe_user')==1){ ?>
      setTimeout(function(){
        formatNumber(<?=(old('gaji')!="")?old('gaji'):"0"?>,'gaji');
        formatNumber(<?=(old('insentif')!="")?old('insentif'):"0"?>,'insentif');
      },400);
    <?php } ?>
  });
</script>
<?php /**PATH /home/zealsasi/new.zeals.asia/resources/views/backend/master/user/create.blade.php ENDPATH**/ ?>