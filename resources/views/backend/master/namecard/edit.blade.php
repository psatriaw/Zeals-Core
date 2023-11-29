<?php
  $main_url = $config['main_url'];
?>
<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Namecards</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url($main_url) }}">Namecards</a>
                    </li>
                    <li class="active">
                        <strong>Edit Namecard</strong>
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
                        <h5>Edit Namecard</h5>
                    </div>
                    <div class="ibox-content">
                      @if ($errors->any())
                          <div class="alert alert-danger">
                              Oop! Please check the fields
                              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                          </div>
                      @endif
                      @include('backend.flash_message')
                      {!! Form::model($data,['url' => url('admin/namecards/update/'.$data->id), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}

                      <div class="form-group {{ ($errors->has('full_name')?"has-error":"") }}"><label class="col-sm-2 control-label">Full Name</label>
                          <div class="col-lg-4 col-md-8 col-sm-12 col-xs-12">
                              {!! Form::text('full_name', null, ['class' => 'form-control']) !!}
                              {!! $errors->first('full_name', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                          </div>
                      </div>

                      <div class="form-group {{ ($errors->has('job_desk')?"has-error":"") }}"><label class="col-sm-2 control-label">Job Desk</label>
                          <div class="col-lg-4 col-md-8 col-sm-12 col-xs-12">
                              {!! Form::text('job_desk', null, ['class' => 'form-control']) !!}
                              {!! $errors->first('job_desk', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                          </div>
                      </div>

                      <div class="form-group {{ ($errors->has('email')?"has-error":"") }}"><label class="col-sm-2 control-label">Email</label>
                          <div class="col-lg-4 col-md-8 col-sm-12 col-xs-12">
                              {!! Form::email('email', null, ['class' => 'form-control','autocomplete' => "off"]) !!}
                              {!! $errors->first('email', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                          </div>
                      </div>

                      <div class="form-group {{ ($errors->has('phone')?"has-error":"") }}"><label class="col-sm-2 control-label">Phone</label>
                          <div class="col-lg-4 col-md-8 col-sm-12 col-xs-12">
                              {!! Form::text('phone', null, ['class' => 'form-control']) !!}
                              {!! $errors->first('phone', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                          </div>
                      </div>

                      <div class="form-group {{ ($errors->has('address')?"has-error":"") }}"><label class="col-sm-2 control-label">Address</label>
                          <div class="col-lg-4 col-md-8 col-sm-12 col-xs-12">
                              {!! Form::textarea('address', null, ['class' => 'form-control']) !!}
                              {!! $errors->first('address', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                          </div>
                      </div>

                      <div class="form-group {{ ($errors->has('slug')?"has-error":"") }}"><label class="col-sm-2 control-label">Slug</label>
                          <div class="col-lg-4 col-md-8 col-sm-12 col-xs-12">
                            {!! Form::text('slug', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('slug', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                          </div>
                      </div>

                      
                    <div class="form-group {{ ($errors->has('qrcode')?"has-error":"") }}"><label class="col-sm-2 control-label">QRCode</label>
                        <div class="col-sm-6 col-xs-12">
                            <div class="">
                                <div class="row">
                                <div class="col-sm-6">
                                    <?php
                                        $qr = "";
                                    ?>
                                    <img class='img img-responsive img-thumbnail' src="{{ url('templates/namecard/qr/'.$data->slug.'.png') }}">
                                </div>
                                </div>
                            </div>
                            <input type="file" name="qrcode" style='display:none;'>
                            {!! $errors->first('qrcode', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
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
    function selectPhoto(){
      $("#qrcode").click();
    }

    $("#qrcode").change(function(e){
      $("#file-preview").html("<img src='"+(URL.createObjectURL(e.target.files[0]))+"' class='img img-responsive img-thumbnail'> <br> <i class='fa fa-check'></i> "+$(this).val().replace(/C:\\fakepath\\/i, ''));
    });

</script>
