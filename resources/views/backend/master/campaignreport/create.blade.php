<?php
  $backlink = @$_GET["backlink"];

  if($backlink==""){
    @$backlink = $config['main_url'];
  }
?>
<div id="wrapper">
    @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
    <div id="page-wrapper" class="gray-bg">
      @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Laporan</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url('master/laporan-campaign') }}">Laporan</a>
                    </li>
                    <li class="active">
                        <strong>Tambah Laporan Penerbit "<?=$perusahaan->nama_penerbit?>"</strong>
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
                            <h5>Tambah Laporan "<?=$perusahaan->nama_penerbit?>"</h5>
                        </div>
                        <div class="ibox-content">
                            @if ($errors->any())
                            <div class="alert alert-danger">
                                Ada kesalahan! mohon cek formulir.
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            </div>
                            @endif
                            @include('backend.flash_message')
                            {!! Form::model($data,['url' => url('master/laporan-campaign/create_laporan/'.$perusahaan->id_penerbit), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'enctype' => 'multipart/form-data','data-parsley-validate novalidate']) !!}
                            <!-- create date -->
                            <input type="hidden" name="backlink" value="<?=$backlink?>">

                            <!-- report code -->
                            <div class="form-group {{ ($errors->has('report_code')?"has-error":"") }}"><label class="col-sm-2 control-label">Report Code</label>
                                <div class="col-sm-4 col-xs-12">
                                    {!! Form::text('report_code', null, ['class' => 'form-control','readonly']) !!}
                                    {!! $errors->first('report_code', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>
                            <!-- report date -->
                            <div class="form-group {{ ($errors->has('report_date')?"has-error":"") }}"><label class="col-sm-2 control-label">Tanggal Pelaporan</label>
                                <div class="col-sm-4 col-xs-12">
                                    {!! Form::text('report_date', null, ['class' => 'form-control', 'id' => 'report_date','readonly']) !!}
                                    {!! $errors->first('report_date', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>
                            <!-- PIC -->
                            <div class="form-group {{ ($errors->has('first_name')?"has-error":"") }}"><label class="col-sm-2 control-label">Nama PIC</label>
                                <div class="col-sm-4 col-xs-12">
                                    {!! Form::text('first_name', null, ['class' => 'form-control', 'readonly']) !!}
                                    {!! $errors->first('first_name', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <div class="form-group {{ ($errors->has('id_campaign')?"has-error":"") }}"><label class="col-sm-2 control-label">Penawaran</label>
                                <div class="col-sm-4 col-xs-12">
                                    {!! Form::select('id_campaign', $campaigns, @$_GET['id_campaign'], ['class' => 'form-control',(@$_GET['id_campaign']!="")?'readonly':'']) !!}
                                    {!! $errors->first('id_campaign', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <div class="form-group {{ ($errors->has('report_month')?"has-error":"") }}"><label class="col-sm-2 control-label">Bulan Laporan</label>
                                <div class="col-sm-4 col-xs-12">
                                    {!! Form::select('report_month', $months, @$data->report_month, ['class' => 'form-control']) !!}
                                    {!! $errors->first('report_month', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <div class="form-group {{ ($errors->has('report_year')?"has-error":"") }}"><label class="col-sm-2 control-label">Tahun Laporan</label>
                                <div class="col-sm-4 col-xs-12">
                                    {!! Form::select('report_year', $years, @$data->report_year, ['class' => 'form-control']) !!}
                                    {!! $errors->first('report_year', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <div class="form-group {{ ($errors->has('profit')?"has-error":"") }}"><label class="col-sm-2 control-label">Profit</label>
                                <div class="col-sm-4 col-xs-12">
                                    {!! Form::text('profit', null, ['class' => 'form-control']) !!}
                                    {!! $errors->first('profit', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>
                            <!-- file -->
                            <div class="form-group">
                                <label class="col-sm-2 control-label">File Laporan <sub>(hanya pdf)</sub></label>
                                <div class="col-sm-4 col-xs-12">
                                  {!! Form::file('file_path', null, ['class' => 'form-control']) !!}
                                  {!! $errors->first('file_path', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-2">
                                    <a class="btn btn-white" href="{{ url($backlink) }}">
                                        <i class="fa fa-angle-left"></i> kembali
                                    </a>
                                </div>
                                <div class="col-sm-6 text-right">
                                    <button class="btn btn-white" type="reset">Reset</button>
                                    <button class="btn btn-primary btn-rounded" type="submit">Unggah Laporan</button>
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
