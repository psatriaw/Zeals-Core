<div id="wrapper">
    @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
    <div id="page-wrapper" class="gray-bg">
      @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Brands</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url('master/penerbit') }}">Brands</a>
                    </li>
                    <li class="active">
                        <strong>Ubah Brand</strong>
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
                            <h5>Ubah Brand</h5>
                        </div>
                        <div class="ibox-content">
                            @if ($errors->any())
                            <div class="alert alert-danger">
                                Ada kesalahan! mohon cek formulir.
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            </div>
                            @endif
                            @include('backend.flash_message')
                            {!! Form::model($data,['url' => url('master/penerbit/update/'.$data->id_penerbit), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate novalidate']) !!}


                            <div class="form-group {{ ($errors->has('nama_penerbit')?"has-error":"") }}"><label class="col-sm-2 control-label">Nama Brand</label>
                                <div class="col-sm-4 col-xs-12">
                                    {!! Form::text('nama_penerbit', null, ['class' => 'form-control disabled']) !!}
                                    {!! $errors->first('nama_penerbit', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <div class="form-group {{ ($errors->has('photos')?"has-error":"") }}"><label class="col-sm-2 control-label">Foto Brand</label>
                                <div class="col-sm-4 col-xs-12">
                                    <img src="/<?= $data->photos; ?>" class="img-fluid" style="height: 200px" alt="">
                                    {!! $errors->first('photos', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <div class="form-group {{ ($errors->has('brand_avatar')?"has-error":"") }}"><label class="col-sm-2 control-label">Update Foto</label>
                                <div class="col-sm-4 col-xs-12">
                                    <input type="file" name="brand_avatar">
                                    {!! $errors->first('brand_avatar', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <div class="form-group {{ ($errors->has('kode_penerbit')?"has-error":"") }}"><label class="col-sm-2 control-label">Kode Brand</label>
                                <div class="col-sm-4 col-xs-12">
                                    {!! Form::text('kode_penerbit', null, ['class' => 'form-control','readonly']) !!}
                                    {!! $errors->first('kode_penerbit', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <div class="form-group {{ ($errors->has('alamat')?"has-error":"") }}"><label class="col-sm-2 control-label">Alamat</label>
                                <div class="col-sm-4 col-xs-12">
                                    {!! Form::text('alamat', null, ['class' => 'form-control disabled']) !!}
                                    {!! $errors->first('alamat', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <div class="form-group {{ ($errors->has('no_telp')?"has-error":"") }}"><label class="col-sm-2 control-label">Nomor Telp</label>
                                <div class="col-sm-4 col-xs-12">
                                    {!! Form::text('no_telp', null, ['class' => 'form-control disabled']) !!}
                                    {!! $errors->first('no_telp', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <div class="form-group {{ ($errors->has('status')?"has-error":"") }}"><label class="col-sm-2 control-label">Status</label>
                                <div class="col-sm-4 col-xs-12">
                                    {!! Form::select('status', ['active' => 'Aktif', 'inactive' => 'Tidak Aktif', 'pending' => 'Pending'], null, ['class' => 'form-control']) !!}
                                    {!! $errors->first('status', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>
                            <!--
                            <div class="form-group {{ ($errors->has('siup')?"has-error":"") }}"><label class="col-sm-2 control-label">SIUP</label>
                                <div class="col-sm-4 col-xs-12">
                                    {!! Form::text('siup', null, ['class' => 'form-control disabled']) !!}
                                    {!! $errors->first('siup', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <div class="form-group {{ ($errors->has('nib')?"has-error":"") }}"><label class="col-sm-2 control-label">NIB</label>
                                <div class="col-sm-4 col-xs-12">
                                    <a class="btn btn-primary" target="_blank" href="/<?= $data->nib; ?>">Lihat</a>
                                    {!! $errors->first('nib', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <div class="form-group {{ ($errors->has('nib')?"has-error":"") }}"><label class="col-sm-2 control-label">NIB Update</label>
                                <div class="col-sm-4 col-xs-12">
                                    <input type="file" name="nib_update">
                                    {!! $errors->first('nib', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>
                            -->

                            <div class="form-group {{ ($errors->has('pic_name')?"has-error":"") }}"><label class="col-sm-2 control-label">PIC</label>
                                <div class="col-sm-4 col-xs-12">
                                    {!! Form::text('pic_name', null, ['class' => 'form-control disabled']) !!}
                                    {!! $errors->first('pic_name', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <div class="form-group {{ ($errors->has('pic_telp')?"has-error":"") }}"><label class="col-sm-2 control-label">PIC Telp</label>
                                <div class="col-sm-4 col-xs-12">
                                    {!! Form::text('pic_telp', null, ['class' => 'form-control disabled']) !!}
                                    {!! $errors->first('pic_telp', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <div class="form-group {{ ($errors->has('nama_sektor_industri')?"has-error":"") }}"><label class="col-sm-2 control-label">Nama Sektor Industri</label>
                                <div class="col-sm-4 col-xs-12">
                                    <select name="id_sektor_industri" id="id_sektor_industri" class="form-control" value="<?= $data->nama_sektor_industri; ?>">
                                        @foreach($sektor_industri as $si)
                                        <option <?=($si->id_sektor_industri == $data->id_sektor_industri)?"selected":""?> value="<?= $si->id_sektor_industri ?>"> {{$si->nama_sektor_industri}} </option>
                                        @endforeach
                                    </select>
                                    {!! $errors->first('id_sektor_industri', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>
                            <!--
                            <div class="form-group {{ ($errors->has('longitude')?"has-error":"") }}"><label class="col-sm-2 control-label">Longitude</label>
                                <div class="col-sm-4 col-xs-12">
                                    {!! Form::text('longitude', null, ['class' => 'form-control disabled']) !!}
                                    {!! $errors->first('longitude', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group {{ ($errors->has('latitude')?"has-error":"") }}"><label class="col-sm-2 control-label">Latitude</label>
                                <div class="col-sm-4 col-xs-12">
                                    {!! Form::text('latitude', null, ['class' => 'form-control disabled']) !!}
                                    {!! $errors->first('latitude', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>
                            -->

                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-2">
                                    <a class="btn btn-white" href="{{ url('master/penerbit') }}">
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
