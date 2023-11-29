<?php
  $main_url = $config['main_url'];
  $id_department  = $previlege->getDepartmentByCode('penerbit');
?>
<div id="wrapper">
    @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
    <div id="page-wrapper" class="gray-bg">
      @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
              <?php if($login->id_department==$id_department){ ?>
                <h2>Edit Profil Perusahaan</h2>
              <?php }else{ ?>
                <h2>Edit Pra Penawaran</h2>
              <?php } ?>

              <?php if($login->id_department!=$id_department){ ?>
              <ol class="breadcrumb">
                  <li>
                      <a href="{{ url('dashboard/view') }}">Dashboard</a>
                  </li>
                  <li>
                      <a href="{{ url('master/pra-penawaran') }}">Pra Penawaran</a>
                  </li>
                  <li class="active">
                      <strong>Ubah Pra Penawaran</strong>
                  </li>
              </ol>

              <?php }else{ ?>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url('master/pra-penawaran/'.$data->id_penerbit) }}">Profil Perusahaan</a>
                    </li>
                    <li class="active">
                        <strong>Ubah Profil Perusahaan</strong>
                    </li>
                </ol>
              <?php } ?>

            </div>
            <div class="col-lg-2">

            </div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <?php if($login->id_department!=$id_department){ ?>
                              <h5>Ubah Pra Penawaran</h5>
                            <?php }else{ ?>
                              <h5>Ubah Profil Perusahaan</h5>
                            <?php } ?>
                        </div>
                        <div class="ibox-content">
                            @if ($errors->any())
                            <div class="alert alert-danger">
                                Ada kesalahan! mohon cek formulir.
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            </div>
                            @endif
                            @include('backend.flash_message')
                            {!! Form::model($data,['url' => url($main_url.'/update/'.$data->id_penerbit), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate novalidate']) !!}
                            <div class="row">
                              <div class="col-sm-6 col-xs-12">

                                <div class="form-group {{ ($errors->has('nama_penerbit')?"has-error":"") }}"><label class="col-sm-4 control-label">Nama Penerbit</label>
                                    <div class="col-sm-8 col-xs-12">
                                        {!! Form::text('nama_penerbit', null, ['class' => 'form-control disabled']) !!}
                                        {!! $errors->first('nama_penerbit', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                    </div>
                                </div>

                                <div class="form-group {{ ($errors->has('photos')?"has-error":"") }}"><label class="col-sm-4 control-label">Gambar Penerbit</label>
                                    <div class="col-sm-8 col-xs-12">
                                        <img src="/<?= $data->photos; ?>" class="img-fluid" style="height: 200px" alt="">
                                        {!! $errors->first('photos', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                    </div>
                                </div>

                                <div class="form-group {{ ($errors->has('photos')?"has-error":"") }}"><label class="col-sm-4 control-label">Update Gambar</label>
                                    <div class="col-sm-8 col-xs-12">
                                        <input type="file" name="photos_update">
                                        {!! $errors->first('photos', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                    </div>
                                </div>

                                <div class="form-group {{ ($errors->has('kode_penerbit')?"has-error":"") }}"><label class="col-sm-4 control-label">Kode Penerbit</label>
                                    <div class="col-sm-8 col-xs-12">
                                        {!! Form::text('kode_penerbit', null, ['class' => 'form-control disabled','readonly']) !!}
                                        {!! $errors->first('kode_penerbit', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                    </div>
                                </div>

                                <div class="form-group {{ ($errors->has('alamat')?"has-error":"") }}"><label class="col-sm-4 control-label">Alamat</label>
                                    <div class="col-sm-8 col-xs-12">
                                        {!! Form::text('alamat', null, ['class' => 'form-control disabled']) !!}
                                        {!! $errors->first('alamat', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                    </div>
                                </div>

                                <div class="form-group {{ ($errors->has('no_telp')?"has-error":"") }}"><label class="col-sm-4 control-label">Nomor Telp</label>
                                    <div class="col-sm-8 col-xs-12">
                                        {!! Form::text('no_telp', null, ['class' => 'form-control disabled']) !!}
                                        {!! $errors->first('no_telp', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                    </div>
                                </div>

                                <div class="form-group {{ ($errors->has('status')?"has-error":"") }}"><label class="col-sm-4 control-label">Status</label>
                                    <div class="col-sm-8 col-xs-12">
                                        {!! Form::select('status', ['active' => 'Aktif', 'inactive' => 'Tidak Aktif', 'pending' => 'Pending'], null, ['class' => 'form-control']) !!}
                                        {!! $errors->first('status', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                    </div>
                                </div>

                                <div class="form-group {{ ($errors->has('siup')?"has-error":"") }}"><label class="col-sm-4 control-label">SIUP</label>
                                    <div class="col-sm-8 col-xs-12">
                                        {!! Form::text('siup', null, ['class' => 'form-control disabled']) !!}
                                        {!! $errors->first('siup', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                    </div>
                                </div>

                                <div class="form-group {{ ($errors->has('nib')?"has-error":"") }}"><label class="col-sm-4 control-label">NIB</label>
                                    <div class="col-sm-8 col-xs-12">
                                        <a class="btn btn-primary" target="_blank" href="/<?= $data->nib; ?>">Lihat</a>
                                        {!! $errors->first('nib', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                    </div>
                                </div>

                                <div class="form-group {{ ($errors->has('nib')?"has-error":"") }}"><label class="col-sm-4 control-label">NIB Update</label>
                                    <div class="col-sm-8 col-xs-12">
                                        <input type="file" name="nib_update">
                                        {!! $errors->first('nib', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                    </div>
                                </div>

                                <div class="form-group {{ ($errors->has('pic_name')?"has-error":"") }}"><label class="col-sm-4 control-label">PIC</label>
                                    <div class="col-sm-8 col-xs-12">
                                        {!! Form::text('pic_name', null, ['class' => 'form-control disabled']) !!}
                                        {!! $errors->first('pic_name', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                    </div>
                                </div>

                                <div class="form-group {{ ($errors->has('pic_telp')?"has-error":"") }}"><label class="col-sm-4 control-label">PIC Telp</label>
                                    <div class="col-sm-8 col-xs-12">
                                        {!! Form::text('pic_telp', null, ['class' => 'form-control disabled']) !!}
                                        {!! $errors->first('pic_telp', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                    </div>
                                </div>

                                <div class="form-group {{ ($errors->has('nama_sektor_industri')?"has-error":"") }}"><label class="col-sm-4 control-label">Nama Sektor Industri</label>
                                    <div class="col-sm-8 col-xs-12">
                                        <select name="sektor_industri" id="sektor_industri" class="form-control" value="<?= $data->nama_sektor_industri; ?>">
                                            @foreach($sektor_industri as $si)
                                            <option selected="<?= $si->id_sektor_industri == $data->id_sektor_industri; ?>" value="<?= $si->id_sektor_industri ?>"> {{$si->nama_sektor_industri}} </option>
                                            @endforeach
                                        </select>
                                        {!! $errors->first('nama_sektor_industri', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                    </div>
                                </div>

                                <?php if($login->id_department!=$id_department){ ?>

                                <div class="form-group {{ ($errors->has('longitude')?"has-error":"") }}"><label class="col-sm-4 control-label">Longitude</label>
                                    <div class="col-sm-8 col-xs-12">
                                        {!! Form::text('longitude', null, ['class' => 'form-control disabled']) !!}
                                        {!! $errors->first('longitude', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group {{ ($errors->has('latitude')?"has-error":"") }}"><label class="col-sm-4 control-label">Latitude</label>
                                    <div class="col-sm84 col-xs-12">
                                        {!! Form::text('latitude', null, ['class' => 'form-control disabled']) !!}
                                        {!! $errors->first('latitude', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                    </div>
                                </div>
                                <?php } ?>
                              </div>
                              <div class="col-sm-6 col-xs-12">
                                <div class="form-group {{ ($errors->has('nib_file')?"has-error":"") }}"><label class="col-sm-4 control-label">File NIB</label>
                                    <div class="col-sm-8 col-xs-12">
                                        {!! Form::file('nib_file', null, ['class' => 'form-control']) !!}
                                        <a href="<?=url("public/".$data->nib_file)?>" target="_blank"><?=$data->nib_file?></a>
                                        {!! $errors->first('nib_file', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group {{ ($errors->has('pbb_file')?"has-error":"") }}"><label class="col-sm-4 control-label">File PBB</label>
                                    <div class="col-sm-8 col-xs-12">
                                        {!! Form::file('pbb_file', null, ['class' => 'form-control']) !!}
                                        <a href="<?=url("public/".$data->pbb_file)?>" target="_blank"><?=$data->pbb_file?></a>
                                        {!! $errors->first('pbb_file', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                    </div>
                                </div>

                                <div class="form-group {{ ($errors->has('neraca_file')?"has-error":"") }}"><label class="col-sm-4 control-label">File Neraca Laba Rugi 6 Bulan</label>
                                    <div class="col-sm-8 col-xs-12">
                                        {!! Form::file('neraca_file', null, ['class' => 'form-control']) !!}
                                        <a href="<?=url("public/".$data->neraca_file)?>" target="_blank"><?=$data->neraca_file?></a>
                                        {!! $errors->first('neraca_file', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                    </div>
                                </div>

                                <div class="form-group {{ ($errors->has('pos_file')?"has-error":"") }}"><label class="col-sm-4 control-label">File POS/Rekening Koran</label>
                                    <div class="col-sm-8 col-xs-12">
                                        {!! Form::file('pos_file', null, ['class' => 'form-control']) !!}
                                        <a href="<?=url("public/".$data->pos_file)?>" target="_blank"><?=$data->pos_file?></a>
                                        {!! $errors->first('pos_file', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                    </div>
                                </div>

                                <div class="form-group {{ ($errors->has('rab_file')?"has-error":"") }}"><label class="col-sm-4 control-label">File RAB</label>
                                    <div class="col-sm-8 col-xs-12">
                                        {!! Form::file('rab_file', null, ['class' => 'form-control']) !!}
                                        <a href="<?=url("public/".$data->rab_file)?>" target="_blank"><?=$data->rab_file?></a>
                                        {!! $errors->first('rab_file', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                    </div>
                                </div>

                                <div class="form-group {{ ($errors->has('proyeksi_file')?"has-error":"") }}"><label class="col-sm-4 control-label">File Proyeksi</label>
                                    <div class="col-sm-8 col-xs-12">
                                        {!! Form::file('proyeksi_file', null, ['class' => 'form-control']) !!}
                                        <a href="<?=url("public/".$data->proyeksi_file)?>" target="_blank"><?=$data->proyeksi_file?></a>
                                        {!! $errors->first('proyeksi_file', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                    </div>
                                </div>

                                <div class="form-group {{ ($errors->has('photo_video_file')?"has-error":"") }}"><label class="col-sm-4 control-label">File Foto & Video</label>
                                    <div class="col-sm-8 col-xs-12">
                                        {!! Form::file('photo_video_file', null, ['class' => 'form-control']) !!}
                                        <a href="<?=url("public/".$data->photo_video_file)?>" target="_blank"><?=$data->photo_video_file?></a>
                                        {!! $errors->first('photo_video_file', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                    </div>
                                </div>

                                <?php if($login->id_department==$id_department){ ?>
                                  <div class="alert alert-info">
                                    Perusahaan wajib mengisi dan mengupdate seluruh field untuk keperluan validasi penyelenggara (Urun Mandiri) dalam hal validasi perusahaan.
                                  </div>
                                <?php  } ?>
                              </div>
                            </div>

                            <div class="hr-line-dashed"></div>
                            <div class="form-group">

                                <div class="col-sm-4 col-sm-offset-2">
                                  <?php if($login->id_department!=$id_department){ ?>
                                    <a class="btn btn-white" href="{{ url($main_url) }}">
                                        <i class="fa fa-angle-left"></i> kembali
                                    </a>
                                  <?php } ?>
                                </div>
                                <div class="col-sm-6 text-right">
                                    <?php if($login->id_department!=$id_department){ ?>
                                    <button class="btn btn-white" type="reset">Reset</button>
                                    <?php } ?>
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
