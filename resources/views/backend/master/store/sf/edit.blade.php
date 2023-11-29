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
                <h2>Sf</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url($main_url) }}">Sf</a>
                    </li>
                    <li class="active">
                        <strong>Ubah Sf</strong>
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
                        <h5>Ubah Sf</h5>
                    </div>
                    <div class="ibox-content">
                      @if ($errors->any())
                          <div class="alert alert-danger">
                              Ada kesalahan! mohon cek formulir.
                              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                          </div>
                      @endif
                      @include('backend.flash_message')
                      {!! Form::model($sf,['url' => url($main_url.'/update/'.$sf->id), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}

                        <div class="form-group {{ ($errors->has('tanggal')?"has-error":"") }}"><label class="col-sm-2 control-label">Tanggal</label>
                            <div class="col-sm-6 col-xs-12">
                                {!! Form::date('tanggal', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="form-group"><label class="col-sm-2 control-label">SF</label>
                            <div class="col-sm-6 col-xs-12"> 
                            {!! Form::select('sf_id', $optsf, null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="form-group {{ ($errors->has('jumlah')?"has-error":"") }}"><label class="col-sm-2 control-label">Jumlah</label>
                            <div class="col-sm-6 col-xs-12">        
                            {!! Form::number('total', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="form-group {{ ($errors->has('jumlah')?"has-error":"") }}"><label class="col-sm-2 control-label">Jumlah</label>
                            <div class="col-sm-6 col-xs-12">        
                            {!! Form::number('diskon', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                            <!-- <button type="submit" class="btn btn-success">Simpan</button>   -->
                            <div class="hr-line-dashed"></div> 
                        <div class="form-group">
                            <div class="col text-right">
                                <button class="btn btn-primary text-right" type="submit">Simpan</button>
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
