<div id="wrapper">
    @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
    <div id="page-wrapper" class="gray-bg">
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Campaign</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('master') }}">Master</a>
                    </li>
                    <li>
                        <a href="{{ url('master/campaign') }}">Campaign</a>
                    </li>
                    <li class="active">
                        <strong>Detail Campaign</strong>
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
                            <h5>Detail Campaign</h5>
                        </div>
                        <div class="ibox-content">
                            @if ($errors->any())
                            <div class="alert alert-danger">
                                Ada kesalahan! mohon cek formulir.
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            </div>
                            @endif
                            @include('backend.flash_message')
                            {!! Form::model($data,['url' => url('master/campaign/update/'.$data->id_penerbit), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
                            <!-- create date -->
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
                            <!-- campaign title -->
                            <div class="form-group {{ ($errors->has('campaign_title')?"has-error":"") }}"><label class="col-sm-2 control-label">Campaign Title</label>
                                <div class="col-sm-4 col-xs-12">
                                    {!! Form::text('campaign_title', null, ['class' => 'form-control disabled', 'readonly','disabled']) !!}
                                    {!! $errors->first('campaign_title', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>
                            <!-- campaign img -->
                            <div class="form-group {{ ($errors->has('photos')?"has-error":"") }}"><label class="col-sm-2 control-label">Foto</label>
                                <div class="col-sm-4 col-xs-12">
                                    <img src="<?= url($data->photos) ?>" class="img-fluid" style="height: 100px;" alt="">
                                    {!! $errors->first('photos', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>
                            <!-- campaign description -->
                            <div class="form-group {{ ($errors->has('campaign_description')?"has-error":"") }}"><label class="col-sm-2 control-label">Campaign Description</label>
                                <div class="col-sm-4 col-xs-12">
                                    <textarea name="campaign_description" class="form-control disabled" disabled rows="10">{{$data->campaign_description}}</textarea>
                                    {!! $errors->first('campaign_description', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>
                            <!-- Start date -->
                            <div class="form-group {{ ($errors->has('start_date')?"has-error":"") }}"><label class="col-sm-2 control-label">Start Date</label>
                                <div class="col-sm-4 col-xs-12">
                                    {!! Form::text('start_date', null, ['class' => 'form-control disabled', 'readonly','disabled']) !!}
                                    {!! $errors->first('start_date', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>
                            <!-- end date -->
                            <div class="form-group {{ ($errors->has('end_date')?"has-error":"") }}"><label class="col-sm-2 control-label">End Date</label>
                                <div class="col-sm-4 col-xs-12">
                                    {!! Form::text('end_date', null, ['class' => 'form-control disabled', 'readonly','disabled']) !!}
                                    {!! $errors->first('end_date', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>
                            <!-- Target fund -->
                            <div class="form-group {{ ($errors->has('target_fund')?"has-error":"") }}"><label class="col-sm-2 control-label">Target Fund</label>
                                <div class="col-sm-4 col-xs-12">
                                    <input type="text" class="form-control disabled" disabled value="<?= number_format($data->target_fund) ?>">
                                    {!! $errors->first('target_fund', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>
                            <!-- Total Lembar -->
                            <div class="form-group {{ ($errors->has('total_lembar')?"has-error":"") }}"><label class="col-sm-2 control-label">Total Lembar</label>
                                <div class="col-sm-4 col-xs-12">
                                    {!! Form::text('total_lembar', null, ['class' => 'form-control disabled', 'readonly','disabled']) !!}
                                    {!! $errors->first('total_lembar', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>
                            <!-- minimum pembelian -->
                            <div class="form-group {{ ($errors->has('minimum_pembelian')?"has-error":"") }}"><label class="col-sm-2 control-label">Minimum Pembelian</label>
                                <div class="col-sm-4 col-xs-12">
                                    <input type="text" class="form-control disabled" disabled value="<?= number_format($data->minimum_pembelian) ?>">
                                    {!! $errors->first('minimum_pembelian', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>
                            <!-- maximum investor -->
                            <div class="form-group {{ ($errors->has('maximum_investor')?"has-error":"") }}"><label class="col-sm-2 control-label">Maksimum Investor</label>
                                <div class="col-sm-4 col-xs-12">
                                    <input type="text" class="form-control disabled" disabled value="<?= number_format($data->maximum_investor) ?>">
                                    {!! $errors->first('maximum_investor', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>
                            <!-- periode deviden -->
                            <div class="form-group {{ ($errors->has('periode_deviden')?"has-error":"") }}"><label class="col-sm-2 control-label">Periode Deviden</label>
                                <div class="col-sm-4 col-xs-12">
                                    <input type="text" class="form-control disabled" disabled value="<?= number_format($data->periode_deviden) ?> bulan">
                                    {!! $errors->first('periode_deviden', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>
                            <!-- Tipe Invest -->
                            <div class="form-group {{ ($errors->has('tipe_produk')?"has-error":"") }}"><label class="col-sm-2 control-label">Tipe Investasi</label>
                                <div class="col-sm-4 col-xs-12">
                                    <input type="text" class="form-control disabled" disabled value="<?= $data->tipe_produk ?>">
                                    {!! $errors->first('tipe_produk', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-2">
                                    <a class="btn btn-white" href="{{ url('master/campaign') }}">
                                        <i class="fa fa-angle-left"></i> kembali
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