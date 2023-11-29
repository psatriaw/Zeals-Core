<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Layanan Uronshop</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('admin/master') }}">Master</a>
                    </li>
                    <li>
                        <a href="{{ url('admin/service') }}">Layanan</a>
                    </li>
                    <li class="active">
                        <strong>Ubah Layanan</strong>
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
                        <h5>Ubah Layanan</h5>
                    </div>
                    <div class="ibox-content">
                      @if ($errors->any())
                          <div class="alert alert-danger">
                              Ada kesalahan! mohon cek formulir.
                              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                          </div>
                      @endif
                      @include('backend.flash_message')
                      {!! Form::model($data,['url' => url('admin/service/update/'.$data->id_service), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}

                        <div class="form-group {{ ($errors->has('service_name')?"has-error":"") }}"><label class="col-sm-2 control-label">Nama Service</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::text('service_name', null, ['class' => 'form-control']) !!}
                                {!! $errors->first('service_name', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('description')?"has-error":"") }}"><label class="col-sm-2 control-label">Deskripsi</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::textarea('description', null, ['class' => 'form-control','rows' => 3]) !!}
                                {!! $errors->first('description', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('expiration')?"has-error":"") }}"><label class="col-sm-2 control-label">Masa Layanan </label>
                            <div class="col-sm-2 col-xs-12">
                              <div class="input-group">
                                {!! Form::email('expiration', null, ['class' => 'form-control']) !!}
                                <span class="input-group-addon bg-primary">
                                  hari
                                </span>
                              </div>
                                {!! $errors->first('expiration', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('price')?"has-error":"") }}"><label class="col-sm-2 control-label">Harga</label>
                            <div class="col-sm-2 col-xs-12">
                              <div class="input-group">
                                <span class="input-group-addon bg-primary">
                                  Rp.
                                </span>
                                {!! Form::text('price', null, ['class' => 'form-control']) !!}
                              </div>
                                {!! $errors->first('price', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('code')?"has-error":"") }}"><label class="col-sm-2 control-label">Kode</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::text('code', null, ['class' => 'form-control']) !!}
                                {!! $errors->first('code', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                <span class="help-block">Harus unik/belum pernah digunakan</span>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('type')?"has-error":"") }}"><label class="col-sm-2 control-label">Tipe</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::select('type', ['seller' => 'Seller', 'vendor' => 'Vendor'], null, ['class' => 'form-control']) !!}
                                {!! $errors->first('type', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('status')?"has-error":"") }}"><label class="col-sm-2 control-label">Status</label>
                            <div class="col-sm-4 col-xs-12">
                              {!! Form::select('status', ['active' => 'Aktif', 'inactive' => 'Tidak Aktif'], null, ['class' => 'form-control']) !!}
                              {!! $errors->first('status', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                                <a class="btn btn-white" href="{{ url('admin/service') }}">
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
