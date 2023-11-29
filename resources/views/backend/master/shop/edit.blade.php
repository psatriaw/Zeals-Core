<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Akun Pengguna</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('admin/master') }}">Master</a>
                    </li>
                    <li>
                        <a href="{{ url('admin/user') }}">Akun Pengguna</a>
                    </li>
                    <li class="active">
                        <strong>Ubah Pengguna</strong>
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
                        <h5>Ubah Pengguna</h5>
                    </div>
                    <div class="ibox-content">
                      @if ($errors->any())
                          <div class="alert alert-danger">
                              Ada kesalahan! mohon cek formulir.
                              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                          </div>
                      @endif
                      @include('backend.flash_message')
                      {!! Form::model($data,['url' => url('admin/user/update/'.$data->id_user), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}

                        <div class="form-group {{ ($errors->has('first_name')?"has-error":"") }}"><label class="col-sm-2 control-label">Nama Depan</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::text('first_name', null, ['class' => 'form-control']) !!}
                                {!! $errors->first('first_name', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('last_name')?"has-error":"") }}"><label class="col-sm-2 control-label">Nama Belakang</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::text('last_name', null, ['class' => 'form-control']) !!}
                                {!! $errors->first('last_name', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('email')?"has-error":"") }}"><label class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::email('email', null, ['class' => 'form-control']) !!}
                                {!! $errors->first('email', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                <span class="help-block">Harus unik/belum pernah digunakan</span>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('phone')?"has-error":"") }}"><label class="col-sm-2 control-label">No. Telp</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::text('phone', null, ['class' => 'form-control']) !!}
                                {!! $errors->first('phone', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('username')?"has-error":"") }}"><label class="col-sm-2 control-label">Username</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::text('username', null, ['class' => 'form-control']) !!}
                                {!! $errors->first('username', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                <span class="help-block">Harus unik/belum pernah digunakan</span>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('id_department')?"has-error":"") }}"><label class="col-sm-2 control-label">Department</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::select('id_department', $optdepartment, null, ['class' => 'form-control']) !!}
                                {!! $errors->first('id_department', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('address')?"has-error":"") }}"><label class="col-sm-2 control-label">Alamat</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::textarea('address', null, ['class' => 'form-control']) !!}
                                {!! $errors->first('address', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
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
                        <div class="form-group {{ ($errors->has('password')?"has-error":"") }}"><label class="col-sm-2 control-label">Password</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::password('password', ['class' => 'form-control']) !!}
                                {!! $errors->first('password', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                <span class="help-block">Mohon tidak diisi jika tidak ingin mengganti password</span>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                                <a class="btn btn-white" href="{{ url('admin/user') }}">
                                    <i class="fa fa-angle-left"></i> kembali
                                </a>
                            </div>
                            <div class="col-sm-6 text-right">
                              <button class="btn btn-white" type="reset">Reset</button>
                              <button class="btn btn-primary" type="submit">Perbarui</button>
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
