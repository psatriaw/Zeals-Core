<?php
$campaigntypes = [
    'o2o' => 'O2O',
    'banner' => 'Banner - AMP',
    'shopee' => 'Shopee - AMP',
    'event' => 'Event - O2O',
];
?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<div id="wrapper">
    @include('backend.menus.sidebar_menu', ['login' => $login, 'previlege' => $previlege])
    <div id="page-wrapper" class="gray-bg">
        @include('backend.menus.top', ['login' => $login, 'previlege' => $previlege])
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Campaign</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url('master/campaign') }}">Campaign</a>
                    </li>
                    <li class="active">
                        <strong>Create Campaign</strong>
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
                        <li class="active"><a href="javascript:void(0);">Campaign Detail</a></li>
                        <?php if(Request::segment(4)=="event"){ ?>
                        <li class="next"><a href="javascript:void(0);">Custom Field</a></li>
                        <?php } ?>
                        <li class="next"><a href="javascript:void(0);">Program</a></li>
                        <li class="next"><a href="javascript:void(0);">Target</a></li>
                        <?php if(Request::segment(4)=="o2o" || Request::segment(4)=="event"){ ?>
                        <li class="next"><a href="javascript:void(0);">Outlet Setup</a></li>
                        <?php } ?>
                        <li class="next"><a href="javascript:void(0);">Ready</a></li>
                    </ul>
                    <br>
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Campaign Detail</h5>
                        </div>
                        <div class="ibox-content">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    Some error due to your input. Please check the fields!
                                    <button type="button" class="close" data-dismiss="alert"
                                        aria-hidden="true">&times;</button>
                                </div>
                            @endif
                            @include('backend.flash_message')
                            {!! Form::open([
                                'url' => url('master/campaign/store'),
                                'method' => 'post',
                                'id' => 'formmain',
                                'class' => 'form-horizontal',
                                'enctype' => 'multipart/form-data',
                                'data-parsley-validate novalidate',
                            ]) !!}
                            <input type="hidden" name="campaign_type" value="<?= Request::segment(4) ?>">
                            <!-- campaign title -->
                            <div class="form-group {{ $errors->has('campaign_title') ? 'has-error' : '' }}"><label
                                    class="col-sm-2 control-label">Type</label>
                                <div class="col-sm-6 col-xs-12">
                                    {!! Form::text('cite', $campaigntypes[Request::segment(4)], ['class' => 'form-control', 'readonly']) !!}
                                    {!! $errors->first('campaign_title', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('campaign_title') ? 'has-error' : '' }}"><label
                                    class="col-sm-2 control-label">Campaign Title</label>
                                <div class="col-sm-6 col-xs-12">
                                    {!! Form::text('campaign_title', null, ['class' => 'form-control']) !!}
                                    {!! $errors->first('campaign_title', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <!-- id user -->
                            <div class="form-group {{ $errors->has('id_penerbit') ? 'has-error' : '' }}"><label
                                    class="col-sm-2 control-label">Brand</label>
                                <div class="col-sm-6 col-xs-12">
                                    <?php if(@$id_penerbit==''){ ?>
                                    {!! Form::select('id_penerbit', $penerbits, @$id_penerbit, ['class' => 'form-control thetarget']) !!}
                                    <?php }else{?>
                                    {!! Form::text('id_penerbit', $penerbits[@$id_penerbit], [
                                        'class' => 'form-control',
                                        @$id_penerbit != '' ? 'readonly' : '',
                                    ]) !!}
                                    <?php }?>

                                    {!! $errors->first('id_penerbit', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>
                            <!-- campaign img -->
                            <div class="form-group {{ $errors->has('photos') ? 'has-error' : '' }}"><label
                                    class="col-sm-2 control-label">Banner<sub>(6:4)</sub></label>
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
                            <div class="form-group {{ $errors->has('budget') ? 'has-error' : '' }}"><label
                                    class="col-sm-2 control-label">Budget</label>
                                <div class="col-sm-6 col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        {!! Form::number('budget', null, ['class' => 'form-control']) !!}
                                    </div>
                                    {!! $errors->first('budget', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('campaign_internal') ? 'has-error' : '' }}"><label
                                    class="col-sm-2 control-label">Campaign Type</label>
                                <div class="col-sm-6 col-xs-12">
                                    {!! Form::select('campaign_internal', ['yes' => 'Hidden (Support/Internal use)', '' => 'Public'], '', [
                                        'class' => 'form-control',
                                    ]) !!}
                                    {!! $errors->first('campaign_internal', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <!-- campaign description -->
                            <div class="form-group {{ $errors->has('max_commission') ? 'has-error' : '' }}"><label
                                    class="col-sm-2 control-label">Max Commission <sub>(per influencer)</sub></label>
                                <div class="col-sm-6 col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        {!! Form::number('max_commission', null, ['class' => 'form-control']) !!}
                                    </div>
                                    {!! $errors->first('max_commission', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>
                            <!-- campaign description -->
                            <div class="form-group {{ $errors->has('campaign_description') ? 'has-error' : '' }}"><label
                                    class="col-sm-2 control-label">Campaign Description</label>
                                <div class="col-sm-6 col-xs-12">
                                    <textarea name="campaign_description" class="form-control" rows="10"></textarea>
                                    {!! $errors->first('campaign_description', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>
                            <!-- campaign description -->
                            <div class="form-group {{ $errors->has('campaign_instruction') ? 'has-error' : '' }}"><label
                                    class="col-sm-2 control-label">Campaign Instruction</label>
                                <div class="col-sm-6 col-xs-12">
                                    <textarea name="campaign_instruction" class="form-control" rows="10"></textarea>
                                    {!! $errors->first('campaign_instruction', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>
                            <!-- Start date -->
                            <!-- campaign description -->
                            <div class="form-group {{ $errors->has('campaign_do_n_dont') ? 'has-error' : '' }}"><label
                                    class="col-sm-2 control-label">Do & Don't</label>
                                <div class="col-sm-6 col-xs-12">
                                    <textarea name="campaign_do_n_dont" class="form-control" rows="10"></textarea>
                                    {!! $errors->first('campaign_do_n_dont', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
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
                                <div class="col-sm-6 col-sm-offset-2">
                                    <a class="btn btn-white" href="{{ url('master/campaign') }}">
                                        <i class="fa fa-angle-left"></i> back
                                    </a>
                                </div>
                                <div class="col-sm-4 text-right">
                                    <button class="btn btn-primary" type="submit">Save & Set Program <i
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
<script>
    $(document).ready(function() {
        $(document).ready(function() {
            $('.thetarget, .thesender').select2({
                ajax: {
                    url: '{{ url('get-list-penerbit') }}',
                    dataType: 'json',
                    data: function(params) {
                        var query = {
                            search: params.term,
                            type: 'public'
                        }
                        // Query parameters will be ?search=[term]&type=public
                        return query;
                    }
                }
            });
        })

        $('.categories').select2();
    });

    function selectPhoto() {
        $("#photos").click();
    }

    $("#photos").change(function(e) {
        $("#file-preview").html("<img src='" + (URL.createObjectURL(e.target.files[0])) +
            "' class='img img-responsive img-thumbnail'> <br> <i class='fa fa-check'></i> " + $(this).val()
            .replace(/C:\\fakepath\\/i, ''));
    });
</script>
