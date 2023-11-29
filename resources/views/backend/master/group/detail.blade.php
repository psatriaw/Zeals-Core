<?php
  $main_url = $config['main_url'];
?>
<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Group Account</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url($main_url) }}">Group Account</a>
                    </li>
                    <li class="active">
                        <strong>Detail Group</strong>
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
                        <h5>Detail Group</h5>
                    </div>
                    <div class="ibox-content">
                      {!! Form::model($data,['url' => url('admin/group/update/'.$data->id_department), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}

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

                        <div class="form-group {{ ($errors->has('name')?"has-error":"") }}"><label class="col-sm-2 control-label">Group Name</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::text('name', null, ['class' => 'form-control disabled', 'readonly','disabled']) !!}
                                {!! $errors->first('name', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>

                        <div class="form-group {{ ($errors->has('department_code')?"has-error":"") }}"><label class="col-sm-2 control-label">Group Code</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::text('department_code', null, ['class' => 'form-control']) !!}
                                {!! $errors->first('department_code', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>

                        <div class="form-group {{ ($errors->has('status')?"has-error":"") }}"><label class="col-sm-2 control-label">Status</label>
                            <div class="col-sm-4 col-xs-12">
                              {!! Form::select('status', ['active' => 'Aktif', 'inactive' => 'Tidak Aktif'], null, ['class' => 'form-control disabled', 'readonly','disabled']) !!}
                              {!! $errors->first('status', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                                <a class="btn btn-white" href="{{ url($main_url) }}">
                                    <i class="fa fa-angle-left"></i> Back
                                </a>
                            </div>
                            <div class="col-sm-6 text-right">

                            </div>
                        </div>
                      {!! Form::close() !!}
                    </div>
                    <br>
                    <div class="ibox-title">
                        <h5>Installed Modules</h5>
                    </div>
                    <div class="ibox-content">
                      <table class="table table-striped table-bordered table-hover dataTables-example" id="datatable">
                        <thead>
                          <tr>
                              <th>No.</th>
                              <th>Module Name</th>
                              <th>Methods</th>
                              <th>Granted</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                            $counter = 0;

                            if(@$modules){
                              foreach ($modules as $key => $value) {
                                $counter++;
                                ?>
                                <tr>
                                  <td><?=$counter?></td>
                                  <td><?=$value->module_name?></td>
                                  <td><?=($value->total_method!="")?$value->total_method:0?> method</td>
                                  <td><?=($value->total_granted!="")?$value->total_granted:0?> method</td>
                                <?php
                              }
                            }
                          ?>
                        </tbody>
                      </table>

                      <?php if($previlege->isAllow($login->id_user,$login->id_department,"admin-master-group-manage")){?>

                          <a href="{{ url('admin/group/manage/'.$value->id_department) }}" class="btn btn-white"><i class="fa fa-cogs"></i> manage</a>

                      <?php }?>
                    </div>
                </div>
                </div>
            </div>
        </div>
    @include('backend.footer')
  </div>
</div>
