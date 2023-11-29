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
                        <li class="completed"><a href="javascript:void(0);">Site Detail</a></li>
                        <li class="active"><a href="javascript:void(0);">Component</a></li>
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
                                'url' => url('master/microsite/savecomponent'),
                                'method' => 'post',
                                'id' => 'formmain',
                                'class' => 'form-horizontal',
                                'enctype' => 'multipart/form-data',
                                'data-parsley-validate novalidate',
                            ]) !!}

                            <!-- ID website
tipe input (text, file, textarea, select)
input_source (json/dst dst)
name_field
rules (laravel form_validation ➝) json
status (active, deleted) -->

                            {{-- <div class="form-group {{ $errors->has('id_microsite') ? 'has-error' : '' }}">
                                <div class="col-lg-4 col-md-8 col-sm-12 col-xs-12">
                                    {!! Form::text('id_microsite', $id_microsite, ['class' => 'form-control', 'disabled' => true]) !!}
                                    <!-- {!! $errors->first('id_microsite', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!} -->
                                </div>
                            </div> --}}

                            <div class="form-group {{ $errors->has('field_name') ? 'has-error' : '' }}">
                                <label class="col-sm-2 control-label">Field Name</label>
                                <div class="col-lg-4 col-md-8 col-sm-12 col-xs-12">
                                    {!! Form::text('field_name', null, ['class' => 'form-control']) !!}
                                    {!! $errors->first('field_name', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('input_type') ? 'has-error' : '' }}">
                                <label class="col-sm-2 control-label">Input Type</label>
                                <div class="col-lg-4 col-md-8 col-sm-12 col-xs-12">
                                    {!! Form::select(
                                        'input_type',
                                        ['text' => 'Text', 'textarea' => 'Text Area', 'select' => 'Select', 'file' => 'File'],
                                        null,
                                        ['class' => 'form-control'],
                                    ) !!}
                                    {!! $errors->first('input_type', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>


                            <div class="form-group {{ $errors->has('input_source') ? 'has-error' : '' }}"><label
                                    class="col-sm-2 control-label">Input Source</label>
                                <div class="col-lg-4 col-md-8 col-sm-12 col-xs-12">
                                    <div class="">
                                        {!! Form::textarea('input_source', null, ['class' => 'form-control', 'rows' => '3', 'multiple' => true]) !!}
                                    </div>
                                    {!! $errors->first('input_source', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>


                            <div class="form-group {{ $errors->has('rules') ? 'has-error' : '' }}"><label
                                    class="col-sm-2 control-label">Rules</label>
                                <div class="col-lg-4 col-md-8 col-sm-12 col-xs-12">
                                    {!! Form::select(
                                        'input_type',
                                        ['required' => 'Required', 'numeric' => 'Numeric', 'date' => 'Date', 'email' => 'Email'],
                                        null,
                                        ['class' => 'form-control'],
                                    ) !!}
                                    {!! $errors->first('rules', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>


                            <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
                                <label class="col-sm-2 control-label">Status</label>
                                <div class="col-lg-4 col-md-8 col-sm-12 col-xs-12">
                                    {!! Form::select('status', ['active' => 'Active', 'inactive' => 'Inactive'], null, ['class' => 'form-control']) !!}
                                    {!! $errors->first('status', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
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
                                    <button class="btn btn-white" type="button" onclick="setAddmore()">Save & Add more
                                        Component <i class="fa fa-save"></i></button>
                                    <button class="btn btn-primary" type="submit">Save & Play the Microsite <i
                                            class="fa fa-angle-right"></i></button>
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
<script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select-2').select2();
    });

    function setAddmore() {
        $("#type_submit").val("more");
        $("#formmain").submit();
    }
</script>
