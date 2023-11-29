<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<?php
  $main_url = $config['main_url'];
?>
<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
          <div class="col-lg-10">
              <h2>Stock Resep</h2>
              <ol class="breadcrumb">
                  <li>
                      <a href="{{ url('dashboard/view') }}">Dashboard</a>
                  </li>
                  <li>
                      <a href="{{ url($main_url) }}">Stock Resep</a>
                  </li>
                  <li class="active">
                      <strong>Restock Item "<?=$detail->stock_unit_code?>"</strong>
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
                        <h5>Restock Item Resep</h5>
                    </div>
                    <div class="ibox-content">
                      @if ($errors->any())
                          <div class="alert alert-danger">
                              Ada kesalahan! mohon cek formulir.
                              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                          </div>
                      @endif
                      @include('backend.flash_message')
                      {!! Form::open(['url' => url($main_url.'/manage/'.$id.'/dorestock/'.$detail->id_unit), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
                        <div class="form-group {{ ($errors->has('production_code')?"has-error":"") }}"><label class="col-sm-2 control-label">Kode Stock</label>
                            <div class="col-sm-3 col-xs-12">
                                <input type="text" name="production_code" value="<?=$detail->stock_unit_code?>" class="form-control" readonly>
                                {!! $errors->first('production_code', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('production_code')?"has-error":"") }}"><label class="col-sm-2 control-label">Nama Rumus</label>
                            <div class="col-sm-3 col-xs-12">
                                <input type="text" name="production_code" value="<?=$detail->naikan_rumus_name?>" class="form-control" readonly>
                                {!! $errors->first('production_code', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('stock_date')?"has-error":"") }}"><label class="col-sm-2 control-label">Tanggal Restock</label>
                            <div class="col-sm-3 col-xs-12">
                                {!! Form::text('stock_date', null, ['class' => 'form-control thetarget','id' => "production_date"]) !!}
                                {!! $errors->first('stock_date', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                                <a class="btn btn-white" href="{{ url($main_url.'/manage/'.$id) }}">
                                    <i class="fa fa-angle-left"></i> kembali
                                </a>
                            </div>
                            <div class="col-sm-6 text-right">
                              <button class="btn btn-white" type="reset">Reset</button>
                              <button class="btn btn-primary" type="submit">Simpan</button>
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
    $('#production_date').datepicker();
    $('#production_date').datepicker("option", "dateFormat", "yy-mm-dd");
  });
</script>
