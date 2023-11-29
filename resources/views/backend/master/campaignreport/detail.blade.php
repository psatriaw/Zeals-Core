<?php
  $backlink = @$_GET["backlink"];
  if($backlink==""){
    $backlink = $config['main_url'];
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
                        <strong>Detail Laporan</strong>
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
                            <h5>Detail Laporan</h5>
                        </div>
                        <div class="ibox-content">
                            @if ($errors->any())
                            <div class="alert alert-danger">
                                Ada kesalahan! mohon cek formulir.
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            </div>
                            @endif
                            
                            @include('backend.flash_message')
                            {!! Form::model($data,['url' => url('master/laporan-campaign/changestatus/'.@$data->id_report), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
                            <!-- create date -->
                            <input type="hidden" name="backlink" value="<?=$backlink?>">
                            <div class="form-group {{ ($errors->has('time_created')?"has-error":"") }}"><label class="col-sm-2 control-label">Waktu Terdaftar</label>
                                <div class="col-sm-4 col-xs-12">
                                    <input type="text" class="form-control disabled" disabled readonly value="<?= date("Y-m-d H:i:s", $data->time_created) ?>">
                                </div>
                            </div>
                            <!-- last update -->
                            <div class="form-group {{ ($errors->has('last_update')?"has-error":"") }}"><label class="col-sm-2 control-label">Pembaruan Terakhir</label>
                                <div class="col-sm-4 col-xs-12">
                                    <input type="text" class="form-control disabled" disabled readonly value="<?= date("Y-m-d H:i:s", $data->last_update) ?>">
                                </div>
                            </div>
                            <!-- report code -->
                            <div class="form-group {{ ($errors->has('report_code')?"has-error":"") }}"><label class="col-sm-2 control-label">Report Code</label>
                                <div class="col-sm-4 col-xs-12">
                                    {!! Form::text('report_code', null, ['class' => 'form-control disabled', 'readonly','disabled']) !!}
                                    {!! $errors->first('report_code', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>
                            <!-- report date -->
                            <div class="form-group {{ ($errors->has('report_date')?"has-error":"") }}"><label class="col-sm-2 control-label">Tanggal Pelaporan</label>
                                <div class="col-sm-4 col-xs-12">
                                    {!! Form::text('report_date', null, ['class' => 'form-control disabled', 'readonly','disabled']) !!}
                                    {!! $errors->first('report_date', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>
                            <!-- PIC -->
                            <div class="form-group {{ ($errors->has('first_name')?"has-error":"") }}"><label class="col-sm-2 control-label">Nama PIC</label>
                                <div class="col-sm-4 col-xs-12">
                                    {!! Form::text('first_name', null, ['class' => 'form-control disabled', 'readonly','disabled']) !!}
                                    {!! $errors->first('first_name', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>
                            <!-- status -->
                            <div class="form-group {{ ($errors->has('status')?"has-error":"") }}"><label class="col-sm-2 control-label">Status</label>
                                <div class="col-sm-4 col-xs-12">
                                    {!! Form::text('status', null, ['class' => 'form-control disabled', 'readonly','disabled']) !!}
                                    {!! $errors->first('status', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>
                            <!-- status -->
                            <div class="form-group {{ ($errors->has('status')?"has-error":"") }}"><label class="col-sm-2 control-label">Bulan-Tahun Laporan</label>
                                <div class="col-sm-4 col-xs-12">
                                    <input type="text" class="form-control disabled" disabled readonly value="{{$data->report_month . '-' . $data->report_year}}">
                                    {!! $errors->first('status', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>
                            <!-- file -->
                            <div class="form-group">
                                <label class="col-sm-2 control-label">File Laporan</label>
                                <div class="col-sm-4 col-xs-12">
                                    <a href="/<?= $data->file_path ?>" class="btn btn-primary" target="_blank">Lihat Laporan</a>
                                </div>
                            </div>
                            <!-- catatan -->
                            <div class="form-group {{ ($errors->has('catatan')?"has-error":"") }}"><label class="col-sm-2 control-label">Catatan</label>
                                <div class="col-sm-4 col-xs-12">
                                    <textarea name="catatan" id="catatan" class="form-control">{{$data->catatan}}</textarea>
                                    <span>Silahkan berikan catatan perusahaan di sini!</span>
                                    {!! $errors->first('catatan', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>
                            <!-- status laporan -->
                            <div class="form-group"><label class="col-sm-2 control-label">Status Penerimaan</label>
                                <div class="col-sm-4 col-xs-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="approve" value="approved" <?= $data->status == 'approved' ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="approve">
                                            Terima
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="reject" value="rejected" <?= $data->status == 'rejected' ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="reject">
                                            Tolak
                                        </label>
                                    </div>

                                    {!! $errors->first('status', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                    </>
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-2">
                                    <a class="btn btn-white" href="{{ url($backlink) }}">
                                        <i class="fa fa-angle-left"></i> kembali
                                    </a>
                                </div>
                                <div class="col-sm-6 text-right">
                                    <button class="btn btn-white" type="reset">Reset</button>
                                    <button class="btn btn-primary btn-rounded" type="submit">Simpan Perubahan</button>
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
