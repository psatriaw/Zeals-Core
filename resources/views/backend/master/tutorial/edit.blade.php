<?php
  $main_url = $config['main_url'];
?>
<div id="wrapper">
    @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
    <div id="page-wrapper" class="gray-bg">
      @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Video Tutorial</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url($main_url) }}">Tutorial</a>
                    </li>
                    <li class="active">
                        <strong>Ubah Video Tutorial</strong>
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
                            <h5>Ubah Video Tutorial</h5>
                        </div>
                        <div class="ibox-content">
                            @if ($errors->any())
                            <div class="alert alert-danger">
                                Ada kesalahan! mohon cek formulir.
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            </div>
                            @endif
                            @include('backend.flash_message')
                            {!! Form::model($data,['url' => url('master/tutorial/update/'.$data->id_video), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}

                            <div class="form-group {{ ($errors->has('title')?"has-error":"") }}"><label class="col-sm-2 control-label">Judul</label>
                                <div class="col-sm-4 col-xs-12">
                                    <textarea class="form-control" name="title">{{$data->title}}</textarea>
                                </div>
                            </div>


                            <div class="form-group {{ ($errors->has('url_video')?"has-error":"") }}"><label class="col-sm-2 control-label">URL Video</label>
                                <div class="col-sm-4 col-xs-12">
                                    <input type="text" name="url_video" class="form-control disabled" value="<?= $data->url_video; ?>">
                                </div>
                            </div>

                            
                            <div class="form-group {{ ($errors->has('video_code')?"has-error":"") }}"><label class="col-sm-2 control-label">Kode Video</label>
                                <div class="col-sm-4 col-xs-12">
                                    <input type="text" name="video_code" class="form-control disabled" value="<?= $data->video_code; ?>">
                                </div>
                                <div class="col">
                                    <span>Contoh link youtube : https://www.youtube.com/watch?v=15udt3ku7ik</span>
                                    <br>
                                    <span>Kodenya adalah : <b>15udt3ku7ik</b></span>
                                </div>
                            </div>

                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-2">
                                    <a class="btn btn-white" href="{{ url($main_url) }}">
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
