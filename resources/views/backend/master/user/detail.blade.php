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
                        <strong>Detail Account</strong>
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
                        <h5>Detail Account</h5>
                    </div>
                    <div class="ibox-content">
                      @if ($errors->any())
                          <div class="alert alert-danger">
                              Ada kesalahan! mohon cek formulir.
                              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                          </div>
                      @endif
                      @include('backend.flash_message')
                      {!! Form::model($data,['url' => url('admin/user/update/'.$data->id_user), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}

                      <div class="form-group {{ ($errors->has('first_name')?"has-error":"") }}"><label class="col-sm-2 control-label">Time Created</label>
                          <div class="col-sm-4 col-xs-12">
                              <input type="text" class="form-control disabled" disabled readonly value="<?=date("Y-m-d H:i:s",$data->date_created)?>">
                          </div>
                      </div>

                      <div class="form-group {{ ($errors->has('first_name')?"has-error":"") }}"><label class="col-sm-2 control-label">Last Update</label>
                          <div class="col-sm-4 col-xs-12">
                              <input type="text" class="form-control disabled" disabled readonly value="<?=date("Y-m-d H:i:s",$data->last_update)?>">
                          </div>
                      </div>

                      <div class="form-group {{ ($errors->has('first_name')?"has-error":"") }}"><label class="col-sm-2 control-label">First Name</label>
                          <div class="col-sm-4 col-xs-12">
                              {!! Form::text('first_name', null, ['class' => 'form-control disabled','disabled' => true]) !!}
                              {!! $errors->first('first_name', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                          </div>
                      </div>

                      <div class="form-group {{ ($errors->has('last_name')?"has-error":"") }}"><label class="col-sm-2 control-label">Last Name</label>
                          <div class="col-sm-4 col-xs-12">
                              {!! Form::text('last_name', null, ['class' => 'form-control disabled','disabled' => true]) !!}
                              {!! $errors->first('last_name', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                          </div>
                      </div>

                      <div class="form-group {{ ($errors->has('email')?"has-error":"") }}"><label class="col-sm-2 control-label">Email</label>
                          <div class="col-sm-4 col-xs-12">
                              {!! Form::email('email', null, ['class' => 'form-control disabled','disabled' => true,'autocomplete' => "off"]) !!}
                              {!! $errors->first('email', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                          </div>
                      </div>

                      <div class="form-group {{ ($errors->has('phone')?"has-error":"") }}"><label class="col-sm-2 control-label">Phone</label>
                          <div class="col-sm-4 col-xs-12">
                              {!! Form::text('phone', null, ['class' => 'form-control disabled','disabled' => true]) !!}
                              {!! $errors->first('phone', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                          </div>
                      </div>

                      <div class="form-group {{ ($errors->has('username')?"has-error":"") }}"><label class="col-sm-2 control-label">Username</label>
                          <div class="col-sm-4 col-xs-12">
                              {!! Form::text('username', null, ['class' => 'form-control disabled','disabled' => true]) !!}
                              {!! $errors->first('username', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                          </div>
                      </div>

                      <div class="form-group {{ ($errors->has('id_department')?"has-error":"") }}"><label class="col-sm-2 control-label">Department</label>
                          <div class="col-sm-4 col-xs-12">
                              {!! Form::select('id_department', $optdepartment, null, ['class' => 'form-control disabled','disabled' => true,'onchange' => 'checkDepartmentCode(this.value)']) !!}
                              {!! $errors->first('id_department', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                          </div>
                      </div>

                      <div class="form-group {{ ($errors->has('address')?"has-error":"") }}"><label class="col-sm-2 control-label">Address</label>
                          <div class="col-sm-4 col-xs-12">
                              {!! Form::textarea('address', null, ['class' => 'form-control disabled','disabled' => true]) !!}
                              {!! $errors->first('address', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                          </div>
                      </div>

                      <div class="form-group {{ ($errors->has('status')?"has-error":"") }}"><label class="col-sm-2 control-label">Status</label>
                          <div class="col-sm-4 col-xs-12">
                            {!! Form::select('status', ['active' => 'Active', 'inactive' => 'Inactive'], null, ['class' => 'form-control disabled','disabled' => true]) !!}
                            {!! $errors->first('status', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                          </div>
                      </div>


                      <div class="form-group {{ ($errors->has('id_brand')?"has-error":"") }} brand-area" <?=($data->department_code=="BRAND")?"":"style='display:none;'"?>>
                          <label class="col-sm-2 control-label">Brand ID</label>
                          <div class="col-sm-4 col-xs-12">
                              {!! Form::select('id_brand', $brands, $data->id_brand,['class' => 'form-control disabled','disabled' => true,'placeholder' => "Select Brand ID"]) !!}
                              {!! $errors->first('id_brand', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                          </div>
                      </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                                <a class="btn btn-white" href="{{ url($main_url) }}">
                                    <i class="fa fa-angle-left"></i> back
                                </a>
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
