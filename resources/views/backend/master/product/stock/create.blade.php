<?php
  $main_url = $config['main_url'];
?>
<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Produk</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url('master/product') }}">Products</a>
                    </li>
                    <li class="active">
                        <strong>Create Stock</strong>
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
                        <h5>Create new stock for "<?=$data->product_name?>" [<?=$data->product_code?>]</h5>
                    </div>
                    <div class="ibox-content">
                      @if ($errors->any())
                          <div class="alert alert-danger">
                              Oops! Please check your input.
                              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                          </div>
                      @endif
                      @include('backend.flash_message')
                      {!! Form::open(['url' => url($main_url.'/stock/'.$data->id_product.'/store'), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
						<!--
						<div class="form-group {{ ($errors->has('color')?"has-error":"") }}"><label class="col-sm-2 control-label">Color</label>
                            <div class="col-sm-3 col-xs-12">
                                {!! Form::select('color', $color, null, ['class' => 'form-control']) !!}
                                {!! $errors->first('color', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
						-->
                        <div class="form-group {{ ($errors->has('size')?"has-error":"") }}"><label class="col-sm-2 control-label">Size</label>
                            <div class="col-sm-3 col-xs-12">
                                {!! Form::select('size', $size, null, ['class' => 'form-control']) !!}
                                {!! $errors->first('size', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('stock')?"has-error":"") }}"><label class="col-sm-2 control-label">Quantity</label>
                            <div class="col-sm-3 col-xs-12">
                              <div class="input-group">
                                {!! Form::text('stock', null, ['class' => 'form-control']) !!}
                                <span class="input-group-addon"> Items</span>
                              </div>
                                {!! $errors->first('stock', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                                <a class="btn btn-white" href="{{ url($main_url.'/stock/'.$data->id_product) }}">
                                    <i class="fa fa-angle-left"></i> back
                                </a>
                            </div>
                            <div class="col-sm-6 text-right">
                              <button class="btn btn-white" type="reset">Reset</button>
                              <button class="btn btn-primary" type="submit">Save</button>
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
