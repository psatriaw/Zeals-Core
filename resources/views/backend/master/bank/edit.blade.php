<?php
  $main_url = $config['main_url'];
?>
<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Bank</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url($main_url) }}">Bank</a>
                    </li>
                    <li class="active">
                        <strong>Edit Bank</strong>
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
                        <h5>Edit Bank</h5>
                    </div>
                    <div class="ibox-content">
                      @if ($errors->any())
                          <div class="alert alert-danger">
                              Oop! Please check the fields
                              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                          </div>
                      @endif
                      @include('backend.flash_message')
                      {!! Form::model($data,['url' => url('admin/bank/update/'.$data->kode), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}

                      <!-- <div class="form-group {{ ($errors->has('kode')?"has-error":"") }}"><label class="col-sm-2 control-label">Kode</label>
                          <div class="col-lg-4 col-md-8 col-sm-12 col-xs-12">
                              {!! Form::text('kode', null, ['class' => 'form-control']) !!}
                              {!! $errors->first('kode', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                          </div>
                      </div> -->

                      <div class="form-group {{ ($errors->has('nama')?"has-error":"") }}"><label class="col-sm-2 control-label">Name Bank</label>
                          <div class="col-lg-4 col-md-8 col-sm-12 col-xs-12">
                              {!! Form::text('nama', null, ['class' => 'form-control']) !!}
                              {!! $errors->first('nama', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                          </div>
                      </div>

                      <div class="form-group {{ ($errors->has('bank_code')?"has-error":"") }}"><label class="col-sm-2 control-label">ID Bank</label>
                          <div class="col-lg-4 col-md-8 col-sm-12 col-xs-12">
                              {!! Form::text('bank_code', null, ['class' => 'form-control']) !!}
                              {!! $errors->first('bank_code', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
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

</script>
