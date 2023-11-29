<?php
  $main_url = $config['main_url'];
?>
<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Modules</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url($main_url) }}">Modules</a>
                    </li>
                    <li class="active">
                        <strong>Detail Module</strong>
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
                        <h5>Detail Module</h5>
                    </div>
                    <div class="ibox-content">
                      @if ($errors->any())
                          <div class="alert alert-danger">
                              Ada kesalahan! mohon cek formulir.
                              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                          </div>
                      @endif
                      @include('backend.flash_message')
                      {!! Form::model($data,['url' => url('admin/module/update/'.$data->id_module), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
                      <div class="form-group {{ ($errors->has('first_name')?"has-error":"") }}"><label class="col-sm-2 control-label">Registered</label>
                          <div class="col-sm-4 col-xs-12">
                              <input type="text" class="form-control disabled" disabled readonly value="<?=date("Y-m-d H:i:s",$data->time_created)?>">
                          </div>
                      </div>

                      <div class="form-group {{ ($errors->has('first_name')?"has-error":"") }}"><label class="col-sm-2 control-label">Last Update</label>
                          <div class="col-sm-4 col-xs-12">
                              <input type="text" class="form-control disabled" disabled readonly value="<?=date("Y-m-d H:i:s",$data->last_update)?>">
                          </div>
                      </div>

                        <div class="form-group {{ ($errors->has('module_name')?"has-error":"") }}"><label class="col-sm-2 control-label">Module Name</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::text('module_name', null, ['class' => 'form-control disabled', 'disabled','readonly']) !!}
                                {!! $errors->first('module_name', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>

                        <div class="form-group {{ ($errors->has('id_department')?"has-error":"") }}"><label class="col-sm-2 control-label">Group User *)</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::select('id_department', $optdepartment, null, ['class' => 'form-control disabled', 'disabled','readonly']) !!}
                                {!! $errors->first('id_department', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                                <a class="btn btn-white" href="{{ url($main_url) }}">
                                    <i class="fa fa-angle-left"></i> Back
                                </a>
                                <a class="btn btn-primary btn-rounded" href="{{ url('admin/module/manage/'.$data->id_module) }}">
                                    <i class="fa fa-cogs"></i> Manage
                                </a>
                            </div>
                            <div class="col-sm-6 text-right">

                            </div>
                        </div>
                      {!! Form::close() !!}
                    </div>
                    <br>
                    
                </div>
                </div>
            </div>
        </div>
    @include('backend.footer')
  </div>
</div>
