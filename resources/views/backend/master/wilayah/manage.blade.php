<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Kelola Kecamatan</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="{{ url('admin/master') }}">Master</a>
                </li>
                <li>
                    <a href="{{ url('master/wilayah') }}">Wilayah/Kecamatan</a>
                </li>
                <li class="active">
                    <strong>Kelola Kecamatan</strong>
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
                    <h5>Detail Kecamatan</h5>
                </div>
                <div class="ibox-content">
                  @if ($errors->any())
                      <div class="alert alert-danger">
                          Ada kesalahan! mohon cek formulir.
                          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                      </div>
                  @endif
                  @include('backend.flash_message')
                  {!! Form::model($data,['url' => url('master/wilayah/update/'.$data->id_kecamatan), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}

                    <div class="form-group {{ ($errors->has('nama_kecamatan')?"has-error":"") }}"><label class="col-sm-2 control-label">Waktu Dibuat</label>
                        <div class="col-sm-4 col-xs-12">
                            <input type="text" readonly class="form-control" value="<?=date("d M Y H:i:s",$data->time_created)?>">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group {{ ($errors->has('nama_kecamatan')?"has-error":"") }}"><label class="col-sm-2 control-label">Terakhir Diperbarui</label>
                        <div class="col-sm-4 col-xs-12">
                              <input type="text" readonly class="form-control" value="<?=date("d M Y H:i:s",$data->last_update)?>">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group {{ ($errors->has('nama_kecamatan')?"has-error":"") }}"><label class="col-sm-2 control-label">Nama Kecamatan</label>
                        <div class="col-sm-4 col-xs-12">
                            {!! Form::text('nama_kecamatan', null, ['class' => 'form-control disabled', 'disabled' => 'disabled']) !!}
                            {!! $errors->first('nama_kecamatan', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group {{ ($errors->has('status')?"has-error":"") }}"><label class="col-sm-2 control-label">Status</label>
                        <div class="col-sm-4 col-xs-12">
                          {!! Form::select('status', ['active' => 'Aktif', 'inactive' => 'Tidak Aktif'], null, ['class' => 'form-control disabled', 'disabled' => 'disabled']) !!}
                          {!! $errors->first('status', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-sm-4 col-sm-offset-2">
                            <a class="btn btn-white" href="{{ url('master/wilayah') }}">
                                <i class="fa fa-angle-left"></i> kembali
                            </a>
                        </div>
                    </div>
                  {!! Form::close() !!}
                </div>
                <br>
                <div class="ibox-title">
                    <h5>Kelola Kelurahan</h5>
                    <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['manage'])){?>
                    <div class="ibox-tools">
                        <a class="btn btn-primary btn-sm" href="{{ url('master/wilayah/kelurahan/create/'.$data->id_kecamatan) }}">
                            <i class="fa fa-plus"></i> tambah kelurahan
                        </a>
                    </div>
                    <?php } ?>
                </div>
                <div class="ibox-content">
                  @include('backend.flash_message')

                  <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover dataTables-example" id="datatable">
                      <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Kelurahan</th>
                            <th>Tgl Daftar</th>
                            <th>Status</th>
                            <th>Tgl Diperbarui</th>
                            <th>Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                          $counter = 0;


                          if($list){
                            foreach ($list as $key => $value) {
                              $counter++;
                              ?>
                              <tr>
                                <td><?=$counter?></td>
                                <td><?=$value->nama_kelurahan?></td>
                                <td><?=date("Y-m-d H:i:s",$value->time_created)?></td>
                                <td <?=($value->status=="active")?"class='text-info'":""?>><?=$value->status?></td>
                                <td><?=date("Y-m-d H:i:s",$value->last_update)?></td>
                                <td>
                                  <?php if($previlege->isAllow($login->id_user,$login->id_department,"wilayah-view")){?>
                                      <a href="{{ url('master/wilayah/kelurahan/edit/'.$value->id_kelurahan.'/'.$data->id_kecamatan) }}" class="btn btn-primary dim btn-xs"><i class="fa fa-paste"></i> ubah</a>
                                  <?php }?>

                                  <?php if($previlege->isAllow($login->id_user,$login->id_department,"wilayah-remove")){?>
                                      <a data-id="{{ $value->id_kelurahan }}" data-parent="{{ $data->id_kecamatan }}" data-url="{{ url('master/wilayah/kelurahan/remove/') }}" class="btn btn-danger btn-outline dim btn-xs confirm"><i class="fa fa-trash"></i> hapus</a>
                                  <?php }?>
                                </td>
                              </tr>
                              <?php
                            }
                          }
                        ?>
                      </tbody>
                      </tfoot>
                    </table>
                  </div>
                </div>
            </div>
            </div>
        </div>
    </div>
    @include('backend.do_confirm')
    @include('backend.footer')
  </div>
</div>
