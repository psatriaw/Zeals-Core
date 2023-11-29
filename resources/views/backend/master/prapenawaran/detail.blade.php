<?php
  $main_url       = $config['main_url'];
  $id_department  = $previlege->getDepartmentByCode('penerbit');
?>
<div id="wrapper">
    @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
    <div id="page-wrapper" class="gray-bg">
        @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <?php if($login->id_department==$id_department){ ?>
                  <h2>Profile Perusahaan</h2>
                <?php }else{ ?>
                  <h2>Pra Penawaran</h2>
                <?php } ?>


                <?php if($login->id_department==$id_department){ ?>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li class="active">
                        <strong>Profil Perusahaan</strong>
                    </li>
                </ol>
                <?php }else{ ?>
                  <ol class="breadcrumb">
                      <li>
                          <a href="{{ url('dashboard/view') }}">Dashboard</a>
                      </li>
                      <li>
                          <a href="{{ url('master/pra-penawaran') }}">Pra Penawaran</a>
                      </li>
                      <li class="active">
                          <strong>Detail Pra Penawaran</strong>
                      </li>
                  </ol>
                <?php } ?>

            </div>
            <div class="col-lg-2">

            </div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-sm-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                          <?php if($login->id_department==$id_department){ ?>
                            <h5>Profile Perusahaan</h5>
                          <?php }else{ ?>
                            <h5>Detail Pra Penawaran</h5>
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
                            {!! Form::model($data,['url' => url('master/pra-penawaran/update/'.$data->id_penerbit), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
                            <div class="row">
                                <div class="col-sm-6 col-xs-12">
                                  <div class="form-group {{ ($errors->has('first_name')?"has-error":"") }}"><label class="col-sm-4 control-label">Waktu Terdaftar</label>
                                      <div class="col-sm-8 col-xs-12">
                                          <input type="text" class="form-control disabled" disabled readonly value="<?= date("Y-m-d H:i:s", $data->date_created) ?>">
                                      </div>
                                  </div>
                                  <div class="form-group {{ ($errors->has('first_name')?"has-error":"") }}"><label class="col-sm-4 control-label">Pembaruan Terakhir</label>
                                      <div class="col-sm-8 col-xs-12">
                                          <input type="text" class="form-control disabled" disabled readonly value="<?= date("Y-m-d H:i:s", $data->last_update) ?>">
                                      </div>
                                  </div>
                                  <div class="form-group {{ ($errors->has('nama_penerbit')?"has-error":"") }}"><label class="col-sm-4 control-label">Nama Penerbit</label>
                                      <div class="col-sm-8 col-xs-12">
                                          {!! Form::text('nama_penerbit', null, ['class' => 'form-control disabled', 'readonly','disabled']) !!}
                                          {!! $errors->first('nama_penerbit', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                      </div>
                                  </div>
                                  <div class="form-group {{ ($errors->has('photos')?"has-error":"") }}"><label class="col-sm-4 control-label">Gambar Penerbit</label>
                                      <div class="col-sm-8 col-xs-12">
                                          <img src="/<?= $data->photos; ?>" class="img-fluid" style="height: 200px" alt="">
                                          {!! $errors->first('photos', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                      </div>
                                  </div>
                                  <div class="form-group {{ ($errors->has('kode_penerbit')?"has-error":"") }}"><label class="col-sm-4 control-label">Kode Penerbit</label>
                                      <div class="col-sm-8 col-xs-12">
                                          {!! Form::text('kode_penerbit', null, ['class' => 'form-control disabled', 'readonly','disabled']) !!}
                                          {!! $errors->first('kode_penerbit', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                      </div>
                                  </div>
                                  <div class="form-group {{ ($errors->has('alamat')?"has-error":"") }}"><label class="col-sm-4 control-label">Alamat</label>
                                      <div class="col-sm-8 col-xs-12">
                                          {!! Form::text('alamat', null, ['class' => 'form-control disabled', 'readonly','disabled']) !!}
                                          {!! $errors->first('alamat', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                      </div>
                                  </div>
                                  <div class="form-group {{ ($errors->has('no_telp')?"has-error":"") }}"><label class="col-sm-4 control-label">Nomor Telp</label>
                                      <div class="col-sm-8 col-xs-12">
                                          {!! Form::text('no_telp', null, ['class' => 'form-control disabled', 'readonly','disabled']) !!}
                                          {!! $errors->first('no_telp', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                      </div>
                                  </div>
                                  <div class="form-group {{ ($errors->has('status')?"has-error":"") }}"><label class="col-sm-4 control-label">Status</label>
                                      <div class="col-sm-8 col-xs-12">
                                          {!! Form::text('status', null, ['class' => 'form-control disabled', 'readonly','disabled']) !!}
                                          {!! $errors->first('status', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                      </div>
                                  </div>
                                  <div class="form-group {{ ($errors->has('siup')?"has-error":"") }}"><label class="col-sm-4 control-label">SIUP</label>
                                      <div class="col-sm-8 col-xs-12">
                                          {!! Form::text('siup', null, ['class' => 'form-control disabled', 'readonly','disabled']) !!}
                                          {!! $errors->first('siup', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                      </div>
                                  </div>
                                  <div class="form-group {{ ($errors->has('nib')?"has-error":"") }}"><label class="col-sm-4 control-label">NIB</label>
                                      <div class="col-sm-8 col-xs-12">
                                          <a class="btn btn-primary" target="_blank" href="/<?= $data->nib; ?>">Lihat</a>
                                          {!! $errors->first('nib', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                      </div>
                                  </div>
                                  <div class="form-group {{ ($errors->has('pic_name')?"has-error":"") }}"><label class="col-sm-4 control-label">PIC</label>
                                      <div class="col-sm-8 col-xs-12">
                                          {!! Form::text('pic_name', null, ['class' => 'form-control disabled', 'readonly','disabled']) !!}
                                          {!! $errors->first('pic_name', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                      </div>
                                  </div>
                                  <div class="form-group {{ ($errors->has('pic_telp')?"has-error":"") }}"><label class="col-sm-4 control-label">PIC Telp</label>
                                      <div class="col-sm-8 col-xs-12">
                                          {!! Form::text('pic_telp', null, ['class' => 'form-control disabled', 'readonly','disabled']) !!}
                                          {!! $errors->first('pic_telp', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                      </div>
                                  </div>
                                  <div class="form-group {{ ($errors->has('nama_sektor_industri')?"has-error":"") }}"><label class="col-sm-4 control-label">Nama Sektor Industri</label>
                                      <div class="col-sm-8 col-xs-12">
                                          {!! Form::text('nama_sektor_industri', null, ['class' => 'form-control disabled', 'readonly','disabled']) !!}
                                          {!! $errors->first('nama_sektor_industri', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                      </div>
                                  </div>
                                  <div class="form-group {{ ($errors->has('longitude')?"has-error":"") }}"><label class="col-sm-4 control-label">Longitude</label>
                                      <div class="col-sm-8 col-xs-12">
                                          {!! Form::text('longitude', null, ['class' => 'form-control disabled', 'readonly','disabled']) !!}
                                          {!! $errors->first('longitude', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                      </div>
                                  </div>
                                  <div class="form-group {{ ($errors->has('latitude')?"has-error":"") }}"><label class="col-sm-4 control-label">Latitude</label>
                                      <div class="col-sm-8 col-xs-12">
                                          {!! Form::text('latitude', null, ['class' => 'form-control disabled', 'readonly','disabled']) !!}
                                          {!! $errors->first('latitude', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                      </div>
                                  </div>
                                </div>
                                <div class="col-sm-6 col-xs-12">
                                  <div class="form-group"><label class="col-sm-4 control-label">File NIB</label>
                                      <div class="col-sm-8 col-xs-12">
                                          <a href="<?=url("public/".$data->nib_file)?>" target="_blank"><?=$data->nib_file?></a>
                                      </div>
                                  </div>
                                  <div class="form-group"><label class="col-sm-4 control-label">File PBB</label>
                                      <div class="col-sm-8 col-xs-12">
                                          <a href="<?=url("public/".$data->pbb_file)?>" target="_blank"><?=$data->pbb_file?></a>
                                      </div>
                                  </div>
                                  <div class="form-group"><label class="col-sm-4 control-label">File Neraca</label>
                                      <div class="col-sm-8 col-xs-12">
                                          <a href="<?=url("public/".$data->neraca_file)?>" target="_blank"><?=$data->neraca_file?></a>
                                      </div>
                                  </div>
                                  <div class="form-group"><label class="col-sm-4 control-label">File RAB</label>
                                      <div class="col-sm-8 col-xs-12">
                                          <a href="<?=url("public/".$data->rab_file)?>" target="_blank"><?=$data->rab_file?></a>
                                      </div>
                                  </div>

                                  <div class="form-group"><label class="col-sm-4 control-label">File POS/Keuangan</label>
                                      <div class="col-sm-8 col-xs-12">
                                          <a href="<?=url("public/".$data->pos_file)?>" target="_blank"><?=$data->pos_file?></a>
                                      </div>
                                  </div>

                                  <div class="form-group"><label class="col-sm-4 control-label">File Proyeksi</label>
                                      <div class="col-sm-8 col-xs-12">
                                          <a href="<?=url("public/".$data->proyeksi_file)?>" target="_blank"><?=$data->proyeksi_file?></a>
                                      </div>
                                  </div>

                                  <div class="form-group"><label class="col-sm-4 control-label">File Foto/Video</label>
                                      <div class="col-sm-8 col-xs-12">
                                          <a href="<?=url("public/".$data->photo_video_file)?>" target="_blank"><?=$data->photo_video_file?></a>
                                      </div>
                                  </div>


                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-4 col-sm-offset-2">
                                    <?php if($login->id_department!=$id_department){ ?>
                                      <a class="btn btn-white" href="{{ url('master/pra-penawaran') }}">
                                          <i class="fa fa-angle-left"></i> kembali
                                      </a>

                                      <?php if ($previlege->isAllow($login->id_user, $login->id_department, "penerbit-approve")) { ?>
                                          <a href="{{ url('master/pra-penawaran/approve/'.$data->id_penerbit) }}" class="btn btn-primary dim btn-rounded"><i class="fa fa-check"></i> Approve </a>
                                      <?php } ?>
                                    <?php } ?>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                        <hr>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="ibox">
                        <div class="ibox-content">
                            <table class="table table-responsive table-striped">
                                <thead>
                                    <tr class="font-weight-bold">
                                        <th>No</th>
                                        <th>User</th>
                                        <th>Komentar</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php($i = 1)
                                    @foreach($data->comment as $dc)
                                    <tr>
                                        <td> {{$i}} </td>
                                        <td> {{$dc->first_name}} </td>
                                        <td> {{$dc->content}} </td>
                                        <td>
                                            <?php if ($previlege->isAllow($login->id_user, $login->id_department, "prapenawaran-remove")) { ?>
                                                <a data-id="{{ $dc->id_comment }}" data-url="{{ url('master/pra-penawaran/comment/remove/' . $data->id_penerbit . '/' . $dc->id_comment) }}" class="btn btn-danger btn-outline dim btn-xs confirm"><i class="fa fa-trash"></i> hapus</a>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    @php($i++)
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('backend.do_confirm')
        @include('backend.footer')
    </div>
</div>
