<style>
    .fa {
        width: 18px;
    }

    .dropdown-menu li a {
        padding-left: 10px;
    }
</style>
<?php
$main_url = $config['main_url'];
?>

<div id="wrapper">
    @include('backend.menus.sidebar_menu', ['login' => $login, 'previlege' => $previlege])
    <div id="page-wrapper" class="gray-bg">
        @include('backend.menus.top', ['login' => $login, 'previlege' => $previlege])
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Microsite</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url($main_url) }}">Microsite</a>
                    </li>
                    <li class="active">
                        <strong>Create Microsite</strong>
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
                    <li class="active"><a href="javascript:void(0);">Site Detail</a></li>
                    <li class="next"><a href="javascript:void(0);">Component</a></li>
                    <li class="next"><a href="javascript:void(0);">Ready</a></li>
                  </ul>
                  <br>
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Create Microsite</h5>
                        </div>
                        <div class="ibox-content">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    Oops! Please check the fields.
                                    <button type="button" class="close" data-dismiss="alert"
                                        aria-hidden="true">&times;</button>
                                </div>
                            @endif

                            @include('backend.flash_message')
                            {!! Form::open([
                                'url' => url('master/microsite/save'),
                                'method' => 'post',
                                'id' => 'formmain',
                                'class' => 'form-horizontal',
                                'enctype' => 'multipart/form-data',
                                'data-parsley-validate novalidate',
                            ]) !!}

                            <div class="form-group {{ $errors->has('id_website') ? 'has-error' : '' }}">
                                <div class="col-lg-4 col-md-8 col-sm-12 col-xs-12">
                                    {!! Form::hidden('id_microsite', null, ['class' => 'form-control','disabled' => true]) !!}
                                    {!! $errors->first('id_microsite', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('web_name') ? 'has-error' : '' }}">
                                <label class="col-sm-2 control-label">Website Name</label>
                                <div class="col-lg-4 col-md-8 col-sm-12 col-xs-12">
                                    {!! Form::text('web_name', null, ['class' => 'form-control']) !!}
                                    {!! $errors->first('web_name', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('id_penerbit') ? 'has-error' : '' }}">
                                <label class="col-sm-2 control-label">Brand</label>
                                <div class="col-lg-4 col-md-8 col-sm-12 col-xs-12">
                                    {!! Form::select('id_penerbit', $penerbits, @$id_penerbit, ['class' => 'form-control thetarget']) !!}
                                    {!! $errors->first('id_penerbit', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                    <!-- <span class="help-block">Must be unique/never used</span> -->
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('notes') ? 'has-error' : '' }}"><label
                                    class="col-sm-2 control-label">Notes</label>
                                <div class="col-lg-4 col-md-8 col-sm-12 col-xs-12">
                                    {!! Form::textarea('notes', null, ['class' => 'form-control']) !!}
                                    {!! $errors->first('notes', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('photos') ? 'has-error' : '' }}"><label
                                    class="col-sm-2 control-label">Banner</label>
                                <div class="col-sm-6 col-xs-12">
                                    <div class="">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="item-upload" onclick="selectPhoto()">
                                                    <i class="fa fa-images"></i>
                                                </div>
                                            </div>
                                            <div class="col-sm-4" id="file-preview">
                                            </div>
                                        </div>
                                    </div>

                                    <input type="file" name="photos" style="display:none;" id="photos">
                                    {!! $errors->first('photos', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>
                            <!-- Start date -->
                            <div class="form-group {{ $errors->has('start_date') ? 'has-error' : '' }}"><label
                                    class="col-sm-2 control-label">Start Date</label>
                                <div class="col-sm-3 col-xs-12">
                                    {!! Form::date('start_date', null, ['class' => 'form-control']) !!}
                                    {!! $errors->first('start_date', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>
                            <!-- end date -->
                            <div class="form-group {{ $errors->has('end_date') ? 'has-error' : '' }}"><label
                                    class="col-sm-2 control-label">End Date</label>
                                <div class="col-sm-3 col-xs-12">
                                    {!! Form::date('end_date', null, ['class' => 'form-control']) !!}
                                    {!! $errors->first('end_date', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
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
                                    <button class="btn btn-primary" type="submit">Save & Set Component<i class="fa fa-angle-right"></i></button>
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
    function selectPhoto(){
      $("#photos").click();
    }

    $("#photos").change(function(e){
      $("#file-preview").html("<img src='"+(URL.createObjectURL(e.target.files[0]))+"' class='img img-responsive img-thumbnail'> <br> <i class='fa fa-check'></i> "+$(this).val().replace(/C:\\fakepath\\/i, ''));
    });
</script>