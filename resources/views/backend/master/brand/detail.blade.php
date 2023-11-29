<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Detail Brand</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url('admin/brand') }}">Brands</a>
                    </li>
                    <li class="active">
                        <strong>Detail brand</strong>
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
                        <h5>Detail brand</h5>
                    </div>
                    <div class="ibox-content">
                      @if ($errors->any())
                          <div class="alert alert-danger">
                              Ada kesalahan! mohon cek formulir.
                              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                          </div>
                      @endif
                      @include('backend.flash_message')
                      {!! Form::model($data,['url' => url('admin/category/update/'.$data->id_product_category), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
                        <div class="form-group {{ ($errors->has('first_name')?"has-error":"") }}"><label class="col-sm-2 control-label">Time created</label>
                            <div class="col-sm-4 col-xs-12">
                                <input type="text" class="form-control disabled" disabled readonly value="<?=date("Y-m-d H:i:s",$data->time_created)?>">
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('first_name')?"has-error":"") }}"><label class="col-sm-2 control-label">Last update</label>
                            <div class="col-sm-4 col-xs-12">
                                <input type="text" class="form-control disabled" disabled readonly value="<?=date("Y-m-d H:i:s",$data->last_update)?>">
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('brand_name')?"has-error":"") }}"><label class="col-sm-2 control-label">Brand name</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::text('brand_name', null, ['class' => 'form-control disabled','disabled' => 'true']) !!}
                                {!! $errors->first('brand_name', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('brand_name')?"has-error":"") }}"><label class="col-sm-2 control-label">Brand Avatar</label>
                            <div class="col-sm-4 col-xs-12">
                                <img src="<?=$data->brand_avatar?>" class="img img-thumbnail" style="width:250px;">
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('brand_code')?"has-error":"") }}"><label class="col-sm-2 control-label">Brand code</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::text('brand_code', null, ['class' => 'form-control disabled','disabled' => 'true']) !!}
                                {!! $errors->first('brand_code', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('brand_permalink')?"has-error":"") }}"><label class="col-sm-2 control-label">Brand permalink</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::text('brand_permalink', null, ['class' => 'form-control disabled','disabled' => 'true']) !!}
                                {!! $errors->first('brand_permalink', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('status')?"has-error":"") }}"><label class="col-sm-2 control-label">Status</label>
                            <div class="col-sm-4 col-xs-12">
                              {!! Form::select('status', ['active' => 'Active', 'inactive' => 'Inactive'], null, ['class' => 'form-control disabled','disabled' => 'disabled', 'readonly' => 'readonly']) !!}
                              {!! $errors->first('status', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                                <a class="btn btn-white" href="{{ url($config['main_url']) }}">
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
