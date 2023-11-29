<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<div id="wrapper">
    @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
    <div id="page-wrapper" class="gray-bg">
      @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
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
                  <br>
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Edit Your Outlet</h5>
                        </div>
                        <div class="ibox-content">
                            @if ($errors->any())
                            <div class="alert alert-danger">
                                Some error due to your input. Please check the fields!
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            </div>
                            @endif
                            @include('backend.flash_message')
                            {!! Form::model($outlet,['url' => url('master/campaign/updateoutlet'), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate novalidate']) !!}
                            <input type="hidden" name="id_outlet" value="<?=$outlet->id_outlet?>">
                            <input type="hidden" name="campaign_link" value="<?=$detail->campaign_link?>">
                            <!-- campaign title -->
                            <div class="form-group {{ ($errors->has('outlet_name')?"has-error":"") }}"><label class="col-sm-2 control-label">Outlet name</label>
                                <div class="col-sm-6 col-xs-12">
                                    {!! Form::text('outlet_name', null, ['class' => 'form-control']) !!}
                                    {!! $errors->first('outlet_name', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <!-- Target fund -->
                            <div class="form-group {{ ($errors->has('outlet_address')?"has-error":"") }}"><label class="col-sm-2 control-label">Address</label>
                              <div class="col-sm-6 col-xs-12">
                                  {!! Form::text('outlet_address', null, ['class' => 'form-control']) !!}
                                  {!! $errors->first('outlet_address', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                              </div>
                            </div>

                            <!-- Target fund -->
                            <div class="form-group {{ ($errors->has('outlet_phone')?"has-error":"") }}"><label class="col-sm-2 control-label">Phone number</label>
                              <div class="col-sm-3 col-xs-12">
                                  {!! Form::text('outlet_phone', null, ['class' => 'form-control']) !!}
                                  {!! $errors->first('outlet_phone', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                              </div>
                            </div>

                            <!-- Target fund -->
                            <div class="form-group {{ ($errors->has('longitude')?"has-error":"") }}"><label class="col-sm-2 control-label">Longitude</label>
                              <div class="col-sm-3 col-xs-12">
                                  {!! Form::text('longitude', null, ['class' => 'form-control']) !!}
                                  {!! $errors->first('longitude', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                              </div>
                            </div>

                            <!-- Target fund -->
                            <div class="form-group {{ ($errors->has('latitude')?"has-error":"") }}"><label class="col-sm-2 control-label">Latitude</label>
                              <div class="col-sm-3 col-xs-12">
                                  {!! Form::text('latitude', null, ['class' => 'form-control']) !!}
                                  {!! $errors->first('latitude', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                              </div>
                            </div>
                            
                            <!-- Target fund -->
                            <div class="form-group {{ ($errors->has('max_redemption')?"has-error":"") }}"><label class="col-sm-2 control-label">Max redemption</label>
                                <div class="col-sm-3 col-xs-12">
                                  <div class="input-group">
                                    {!! Form::text('max_redemption', null, ['class' => 'form-control']) !!}
                                    <span class="input-group-addon">vouchers</span>
                                  </div>
                                  <p class="help-block">let it blank if there is no maximum redemption for this outlet</p>
                                    {!! $errors->first('max_redemption', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <!-- Target fund -->
                            <div class="form-group {{ ($errors->has('max_redemption_per_day')?"has-error":"") }}"><label class="col-sm-2 control-label">Max redemption per day</label>
                                <div class="col-sm-3 col-xs-12">
                                  <div class="input-group">
                                    {!! Form::text('max_redemption_per_day', null, ['class' => 'form-control']) !!}
                                    <span class="input-group-addon">vouchers</span>
                                  </div>
                                  <p class="help-block">let it blank if there is no maximum redemption per day for this outlet</p>
                                    {!! $errors->first('max_redemption_per_day', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>


                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <div class="col-sm-6 col-sm-offset-2">
                                    <a class="btn btn-white" href="{{ url('master/campaign/resume/'.Request::segment(4)) }}">
                                        <i class="fa fa-angle-left"></i> back
                                    </a>
                                </div>
                                <div class="col-sm-4 text-right">
                                    <button class="btn btn-primary" type="submit">Save Changes <i class="fa fa-angle-right"></i></button>
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
                    url: '{{ url("get-list-penerbit") }}',
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
        $('.categories').on('select2:select', function (e) {
          $.each(".select2-selection__choice").function()
        });
        $(".categories").keydown(function(e){
          if(e.keyCode==13){
            return false;
          }
        });

        $("#formmain").keydown(function(e){
          if(e.keyCode==13){
            return false;
          }
        })
    });

    function setAddmore(){
      $("#type_submit").val("more");
      $("#formmain").submit();
    }
</script>
