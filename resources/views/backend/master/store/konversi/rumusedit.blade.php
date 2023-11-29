<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<?php
  $main_url = $config['main_url'];
?>
<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Rumus_konversi</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url($main_url) }}">Rumus Konversi</a>
                    </li>
                    <li class="active">
                        <strong>Ubah Rumus Konversi</strong>
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
                        <h5>Ubah Rumus Konversi</h5>
                    </div>
                    <div class="ibox-content">
                      @if ($errors->any())
                          <div class="alert alert-danger">
                              Ada kesalahan! mohon cek formulir.
                              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                          </div>
                      @endif
                      @include('backend.flash_message')
                      {!! Form::model($data,['url' => url($main_url.'/update/'.$data->id), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}

                <div class="form-group {{ ($errors->has('nama_rumus')?"has-error":"") }}">
                    <label> Nama Rumus</label>
                    {!! Form::text('nama_rumus', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('nama_rumus', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!} 
                </div>
                <div class="form-group {{ ($errors->has('dari')?"has-error":"") }}">
                    <label> Dari</label>
                    {!! Form::select('dari', $barang, null, ['class' => 'form-control']) !!}
                    {!! $errors->first('dari', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!} 
                </div>
                <div class="form-group {{ ($errors->has('qty1')?"has-error":"") }}">
                    <label> Qty</label>
                    {!! Form::text('qty1', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('qty1', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!} 
                </div>
                <div class="form-group {{ ($errors->has('ke')?"has-error":"") }}">
                    <label> Ke</label>
                    {!! Form::select('ke', $barang, null,['class' => 'form-control']) !!}
                    {!! $errors->first('ke', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!} 
                </div>
                <div class="form-group {{ ($errors->has('qty2')?"has-error":"") }}">
                    <label> Qty</label>
                    {!! Form::text('qty2', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('qty2', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!} 
                </div>
                <!-- <button type="submit" class="btn btn-success">Simpan</button>   -->
            <div class="hr-line-dashed"></div> 
              <div class="form-group">
                  <div class="col-sm-4 col-sm-offset-2">
                      <a class="btn btn-white" href="{{ url($main_url) }}">
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
