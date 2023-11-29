<?php
  $main_url      = $config['main_url'];
  $methods       = $config;
?>
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('style2/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('style2/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('style2/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('style2/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">

<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Input Laporan</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li class="active">
                        <strong>Input Laporan</strong>
                    </li>
                </ol>
            </div>
            <div class="col-lg-2">
 
            </div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">

        <div class="ibox">
            <div class="ibox">
                <div class="ibox-title">
                    <i class="fas fa-cogs mr-1"></i>
                    @include('backend.flash_message')
                    Input Laporan <p>(Peringatan Data yang sudah dibuat tidak bisa dihapus)</p>
                </div>
                <div class="ibox-content">
                {!! Form::open(['url' => url($main_url.'/store'), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}

                <div class="form-group {{ ($errors->has('tanggal')?"has-error":"") }}"><label class="col-sm-2 control-label">Tanggal</label>
                    <div class="col-sm-6 col-xs-12">
                        <input id="tanggal" type="date" name="tanggal" class="form-control" value="" autofocus required>
                    </div>
                </div>
                <div class="form-group {{ ($errors->has('diskon')?"has-error":"") }}"><label class="col-sm-2 control-label">Jenis Input Laporan</label>
                    <div class="col-sm-6 col-xs-12">        
                        <select name="jenis_input" id="jenis_input" class="form-control">
                        <option value="PO">Piutang Outlet</option>                        
                        <option value="UR">Uang Real</option>
                        <option value="PDP">Penerimaan DP</option>
                        </select>
                    </div>
                </div>
                <div class="form-group {{ ($errors->has('total')?"has-error":"") }}"><label class="col-sm-2 control-label">Jumlah Input</label>
                    <div class="col-sm-6 col-xs-12">        
                        <input type="number" name="jumlah" class="form-control" autofocus required>
                    </div>
                </div>
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
    @include('backend.do_confirm')
    @include('backend.footer')
    @include('backend.master.purchase.modal_material_add')
  </div>
</div>
 