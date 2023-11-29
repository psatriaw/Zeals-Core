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
                        <strong>Add Brand</strong>
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
                            <h5>Add Brand</h5>
                        </div>
                        <div class="ibox-content">
                            @if ($errors->any())
                            <div class="alert alert-danger">
                                Oop! Please check the fields
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            </div>
                            @endif
                            @include('backend.flash_message')
                            {!! Form::open(['url' => url('master/penerbit/store'), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate novalidate']) !!}

                            <div class="form-group {{ ($errors->has('nama_penerbit')?"has-error":"") }}"><label class="col-sm-4 col-lg-2 control-label">Brand Name</label>
                                <div class="col-lg-4 col-sm-8 col-xs-12 ">
                                    {!! Form::text('nama_penerbit', null, ['class' => 'form-control']) !!}
                                    {!! $errors->first('nama_penerbit', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group {{ ($errors->has('brand_avatar')?"has-error":"") }}"><label class="col-sm-4 col-lg-2 control-label">Brand Logo</label>
                                <div class="col-lg-4 col-sm-8 col-xs-12 ">
                                    {!! Form::file('brand_avatar', null) !!}
                                    {!! $errors->first('brand_avatar', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <div class="form-group {{ ($errors->has('kode_penerbit')?"has-error":"") }}"><label class="col-sm-4 col-lg-2 control-label">Brand Code/ID</label>
                                <div class="col-lg-4 col-sm-8 col-xs-12 ">
                                    {!! Form::text('kode_penerbit', $kode_penerbit, ['class' => 'form-control','readonly']) !!}
                                    {!! $errors->first('kode_penerbit', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <div class="form-group {{ ($errors->has('alamat')?"has-error":"") }}"><label class="col-sm-4 col-lg-2 control-label">Address</label>
                                <div class="col-lg-4 col-sm-8 col-xs-12 ">
                                    {!! Form::textarea('alamat', null, ['class' => 'form-control','rows' => 3]) !!}
                                    {!! $errors->first('alamat', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <div class="form-group {{ ($errors->has('no_telp')?"has-error":"") }}"><label class="col-sm-4 col-lg-2 control-label">Phone</label>
                                <div class="col-lg-4 col-sm-8 col-xs-12 ">
                                    {!! Form::number('no_telp', null, ['class' => 'form-control']) !!}
                                    {!! $errors->first('no_telp', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <div class="form-group {{ ($errors->has('status')?"has-error":"") }}"><label class="col-sm-4 col-lg-2 control-label">Status</label>
                                <div class="col-lg-4 col-sm-8 col-xs-12 ">
                                    {!! Form::select('status', ['active' => 'Aktif', 'inactive' => 'Tidak Aktif', 'pending' => 'Pending'], null, ['class' => 'form-control']) !!}
                                    {!! $errors->first('status', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>
                            <!--
                            <div class="form-group {{ ($errors->has('siup')?"has-error":"") }}"><label class="col-sm-4 col-lg-2 control-label">Nomor SIUP</label>
                                <div class="col-lg-4 col-sm-8 col-xs-12 ">
                                    {!! Form::number('siup', null, ['class' => 'form-control']) !!}
                                    {!! $errors->first('siup', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <div class="form-group {{ ($errors->has('nib')?"has-error":"") }}"><label class="col-sm-4 col-lg-2 control-label">Scan NIB</label>
                                <div class="col-lg-4 col-sm-8 col-xs-12 ">
                                    {!! Form::file('nib', null) !!}
                                    <span class="mt-2 badge badge-primary">Upload Format PDF</span>
                                    {!! $errors->first('nib', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>
                            -->
                            <div class="form-group {{ ($errors->has('pic_name')?"has-error":"") }}"><label class="col-sm-4 col-lg-2 control-label">PIC Name</label>
                                <div class="col-lg-4 col-sm-8 col-xs-12 ">
                                    {!! Form::text('pic_name', null, ['class' => 'form-control']) !!}
                                    {!! $errors->first('pic_name', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <div class="form-group {{ ($errors->has('pic_telp')?"has-error":"") }}"><label class="col-sm-4 col-lg-2 control-label">PIC Phone</label>
                                <div class="col-lg-4 col-sm-8 col-xs-12 ">
                                    {!! Form::number('pic_telp', null, ['class' => 'form-control']) !!}
                                    {!! $errors->first('pic_telp', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <div class="form-group {{ ($errors->has('id_sektor_industri')?"has-error":"") }}"><label class="col-sm-4 col-lg-2 control-label">Industry</label>
                                <div class="col-lg-4 col-sm-8 col-xs-12 ">
                                    {!! Form::select('id_sektor_industri', $sektor_industri,null, ['class' => 'form-control']) !!}
                                    {!! $errors->first('id_sektor_industri', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <!--
                            <div class="form-group {{ ($errors->has('longitude')?"has-error":"") }}"><label class="col-sm-4 col-lg-2 control-label">Longitude</label>
                                <div class="col-lg-4 col-sm-8 col-xs-12 ">
                                    {!! Form::number('longitude', null, ['class' => 'form-control']) !!}
                                    {!! $errors->first('longitude', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <div class="form-group {{ ($errors->has('latitude')?"has-error":"") }}"><label class="col-sm-4 col-lg-2 control-label">Latitude</label>
                                <div class="col-lg-4 col-sm-8 col-xs-12 ">
                                    {!! Form::number('latitude', null, ['class' => 'form-control']) !!}
                                    {!! $errors->first('latitude', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>
                            -->
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <div class="col-sm-4 col-xs-6 col-sm-offset-2">
                                    <a class="btn btn-white" href="{{ url('master/penerbit') }}">
                                        <i class="fa fa-angle-left"></i> back
                                    </a>
                                </div>
                                <div class="col-sm-6 col-xs-6 text-right">
                                    <button class="btn btn-white" type="reset">Reset</button>
                                    <button class="btn btn-primary btn-rounded" type="submit">Submit</button>
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
        @include('backend.footer')
    </div>
</div>
</div>
