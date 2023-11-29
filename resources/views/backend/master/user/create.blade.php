<?php
  $main_url = $config['main_url'];
?>
<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Accounts</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url($main_url) }}">Accounts</a>
                    </li>
                    <li class="active">
                        <strong>Add Account</strong>
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
                        <h5>Add Account</h5>
                    </div>
                    <div class="ibox-content">
                      @if ($errors->any())
                          <div class="alert alert-danger">
                              Oops! Please check the fields.
                              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                          </div>
                      @endif

                      @include('backend.flash_message')
                      {!! Form::open(['url' => url('admin/user/store'), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}

                      <div class="form-group {{ ($errors->has('first_name')?"has-error":"") }}"><label class="col-sm-2 control-label">First Name</label>
                          <div class="col-lg-4 col-md-8 col-sm-12 col-xs-12">
                              {!! Form::text('first_name', null, ['class' => 'form-control']) !!}
                              {!! $errors->first('first_name', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                          </div>
                      </div>

                      <div class="form-group {{ ($errors->has('last_name')?"has-error":"") }}"><label class="col-sm-2 control-label">Last Name</label>
                          <div class="col-lg-4 col-md-8 col-sm-12 col-xs-12">
                              {!! Form::text('last_name', null, ['class' => 'form-control']) !!}
                              {!! $errors->first('last_name', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                          </div>
                      </div>

                      <div class="form-group {{ ($errors->has('email')?"has-error":"") }}"><label class="col-sm-2 control-label">Email</label>
                          <div class="col-lg-4 col-md-8 col-sm-12 col-xs-12">
                              {!! Form::email('email', null, ['class' => 'form-control','autocomplete' => "off"]) !!}
                              {!! $errors->first('email', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                              <span class="help-block">Must be unique/never used</span>
                          </div>
                      </div>

                      <div class="form-group {{ ($errors->has('phone')?"has-error":"") }}"><label class="col-sm-2 control-label">Phone</label>
                          <div class="col-lg-4 col-md-8 col-sm-12 col-xs-12">
                              {!! Form::text('phone', null, ['class' => 'form-control']) !!}
                              {!! $errors->first('phone', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                          </div>
                      </div>

                      <div class="form-group {{ ($errors->has('username')?"has-error":"") }}"><label class="col-sm-2 control-label">Username</label>
                          <div class="col-lg-4 col-md-8 col-sm-12 col-xs-12">
                              {!! Form::text('username', null, ['class' => 'form-control']) !!}
                              {!! $errors->first('username', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                              <span class="help-block">Harus unik/belum pernah digunakan</span>
                          </div>
                      </div>

                      <div class="form-group {{ ($errors->has('custom_field_1')?"has-error":"") }}"><label class="col-sm-2 control-label">NIK</label>
                          <div class="col-lg-4 col-md-8 col-sm-12 col-xs-12">
                              {!! Form::text('custom_field_1', null, ['class' => 'form-control']) !!}
                              {!! $errors->first('custom_field_1', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                          </div>
                      </div>

                      <div class="form-group {{ ($errors->has('id_department')?"has-error":"") }}"><label class="col-sm-2 control-label">Department</label>
                          <div class="col-lg-4 col-md-8 col-sm-12 col-xs-12">
                              {!! Form::select('id_department', $optdepartment, null, ['class' => 'form-control','onchange' => 'checkDepartmentCode(this.value)']) !!}
                              {!! $errors->first('id_department', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                          </div>
                      </div>

                      <div class="form-group {{ ($errors->has('address')?"has-error":"") }}"><label class="col-sm-2 control-label">Address</label>
                          <div class="col-lg-4 col-md-8 col-sm-12 col-xs-12">
                              {!! Form::textarea('address', null, ['class' => 'form-control']) !!}
                              {!! $errors->first('address', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                          </div>
                      </div>

                      <div class="form-group {{ ($errors->has('status')?"has-error":"") }}"><label class="col-sm-2 control-label">Status</label>
                          <div class="col-lg-4 col-md-8 col-sm-12 col-xs-12">
                            {!! Form::select('status', ['active' => 'Active', 'inactive' => 'Inactive'], null, ['class' => 'form-control']) !!}
                            {!! $errors->first('status', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                          </div>
                      </div>

                      <div class="form-group {{ ($errors->has('id_brand')?"has-error":"") }} brand-area" <?=($errors->has('id_brand')?"":"style='display:none;'")?>>
                          <label class="col-sm-2 control-label">Brand ID</label>
                          <div class="col-lg-4 col-md-8 col-sm-12 col-xs-12">
                              {!! Form::select('id_brand', $brands, null,['class' => 'form-control','placeholder' => "Select Brand ID"]) !!}
                              {!! $errors->first('id_brand', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                          </div>
                      </div>

                      

                      <div class="form-group {{ ($errors->has('password')?"has-error":"") }}"><label class="col-sm-2 control-label">Password</label>
                          <div class="col-lg-4 col-md-8 col-sm-12 col-xs-12">
                              {!! Form::text('password', null, ['class' => 'form-control','type' => 'password']) !!}
                              {!! $errors->first('password', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                          </div>
                      </div>

                      <div class="hr-line-dashed"></div>
                      <div class="form-group">
                          <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 col-sm-offset-2">
                              <a class="btn btn-white" href="{{ url($main_url) }}">
                                  <i class="fa fa-angle-left"></i> back
                              </a>
                          </div>
                          <div class="col-xs-6 col-xs-12 text-right">
                            <button class="btn btn-white" type="reset">Reset</button>
                            <button class="btn btn-primary btn-rounded" type="submit">Submit</button>
                          </div>
                      </div>
                      {!! Form::close() !!}
                    </div>
                </div>
                </div>
            </div>
        </div>
    @include('backend.footer')
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

  function checkDepartmentCode(id_dep){
    $.ajax({
      url: "<?=url("api/departmentinfo")?>",
      type: "GET",
      data: { id_dep: id_dep}
    })
    .done(function(result){
      console.log(result);
      if(result.status==200){
        if(result.response.department_code=="BRAND"){
          $(".brand-area").show();
        }else{
          $(".brand-area").hide();
        }
      }else{
        $(".brand-area").hide();
      }
    })
    .fail(function(er){
      console.log(er);
    });
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
