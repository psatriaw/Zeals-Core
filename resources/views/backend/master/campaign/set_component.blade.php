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
                        <strong>Add Campaign</strong>
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
                        <li class="completed"><a href="javascript:void(0);">Campaign Detail</a></li>
                        <li class="active"><a href="javascript:void(0);">Custom Field</a></li>
                        <li class="next"><a href="javascript:void(0);">Program</a></li>
                        <li class="next"><a href="javascript:void(0);">Target</a></li>
                        <?php if($detail->campaign_type=="o2o" || $detail->campaign_type=="event"){ ?>
                        <li class="next"><a href="javascript:void(0);">Outlet Setup</a></li>
                        <?php } ?>
                        <li class="next"><a href="javascript:void(0);">Ready</a></li>
                    </ul>
                    <br>

                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Custom Field</h5>
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
                            {!! Form::model($detail, [
                                'url' => url('master/campaign/storecomponent'),
                                'method' => 'post',
                                'id' => 'formmain',
                                'class' => 'form-horizontal',
                                'enctype' => 'multipart/form-data',
                                'data-parsley-validate novalidate',
                            ]) !!}
                            <input type="hidden" name="id_campaign" value="<?= $detail->id_campaign ?>">
                            <input type="hidden" name="campaign_link" value="<?= $detail->campaign_link ?>">
                            <!-- campaign title -->
                            <div class="form-group {{ $errors->has('campaign_title') ? 'has-error' : '' }}"><label
                                    class="col-sm-2 control-label">Campaign Detail</label>
                                <div class="col-sm-6 col-xs-12">
                                    {!! Form::text('campaign_title', $detail->campaign_title . ' [' . $detail->campaign_link . ']', [
                                        'class' => 'form-control',
                                        'readonly',
                                    ]) !!}
                                    {!! $errors->first('campaign_title', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('field_name') ? 'has-error' : '' }}"><label
                                    class="col-sm-2 control-label">Field Name</label>
                                <div class="col-sm-6 col-xs-12">
                                    <div class="">
                                        {!! Form::text('field_name', null, ['class' => 'form-control', 'multiple' => true]) !!}
                                    </div>
                                    {!! $errors->first('field_name', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('input_type') ? 'has-error' : '' }}">
                                <label class="col-sm-2 control-label">Input Type</label>
                                <div class="col-sm-6 col-xs-12">
                                    {!! Form::select(
                                        'input_type',
                                        ['text' => 'Text', 'checkbox' => 'Check Box', 'radio' => 'Radio', 'date' => 'Date'],
                                        null,
                                        ['class' => 'form-control'],
                                    ) !!}
                                    {!! $errors->first('input_type', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>


                            <div class="form-group {{ $errors->has('input_source') ? 'has-error' : '' }}"><label
                                    class="col-sm-2 control-label">Input Source</label>
                                <div class="col-sm-6 col-xs-12">
                                    <div class="">
                                        {!! Form::textarea('input_source', null, ['class' => 'form-control', 'rows' => '3', 'multiple' => true]) !!}
                                    </div>
                                    {!! $errors->first('input_source', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('rules') ? 'has-error' : '' }}"><label
                                    class="col-sm-2 control-label">Rules</label>
                                <div class="col-sm-6 col-xs-12">
                                    <div class="">
                                        {!! Form::select(
                                            'input_type',
                                            ['required' => 'Required', 'numeric' => 'Numeric', 'date' => 'Date', 'email' => 'Email'],
                                            null,
                                            ['class' => 'js-example-basic-single form-control'],
                                        ) !!}
                                    </div>
                                    {!! $errors->first('rules', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('Status') ? 'has-error' : '' }}"><label
                                    class="col-sm-2 control-label">Status</label>
                                <div class="col-sm-6 col-xs-12">
                                    <div class="">
                                        {!! Form::select('status', ['active' => 'Active', 'inactive' => 'Inactive'], null, ['class' => 'form-control']) !!}
                                    </div>
                                    {!! $errors->first('Status', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
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
                                    <button class="btn btn-white" type="button" onclick="setAddmore()">Save & Set new
                                        Component <i class="fa fa-save"></i></button>
                                    <button class="btn btn-primary" type="submit">Save & Set Target <i
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
        $('.categories').on('select2:select', function(e) {
            $.each(".select2-selection__choice").function()
        });
        $(".categories").keydown(function(e) {
            if (e.keyCode == 13) {
                return false;
            }
        })
    });

    $(document).ready(function() {
        $('.js-example-basic-single').select2();
    });

    function setAddmore() {
        $("#type_submit").val("more");
        $("#formmain").submit();
    }
</script>
