<?php
  $main_url = $config['main_url'];
  $statuses = array(
    "approved" => "<label class='label label-success'>diterima</label>",
    "rejected" => "<label class='label label-danger'>ditolak</label>",
    "pending"  => "<label class='label label-default'>pending</label>",
  );
?>
<div id="wrapper">
    @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
    <div id="page-wrapper" class="gray-bg">
      @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Penerbit</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url('master/penerbit') }}">Penerbit</a>
                    </li>
                    <li class="active">
                        <strong>Kelola Penerbit</strong>
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
                            <h5>Kelola Penerbit</h5>
                        </div>
                        <div class="ibox-content">
                          <div class="row">
                            <div class="col-sm-6">
                                @if ($errors->any())
                                <div class="alert alert-danger">
                                    Ada kesalahan! mohon cek formulir.
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                </div>
                                @endif
                                @include('backend.flash_message')
                                {!! Form::model($data,['url' => url('master/penerbit/update/'.$data->id_penerbit), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}

                                <div class="form-group {{ ($errors->has('nama_penerbit')?"has-error":"") }}"><label class="col-sm-4 control-label">Nama Penerbit</label>
                                    <div class="col-sm-8 col-xs-12">
                                        {!! Form::text('nama_penerbit', null, ['class' => 'form-control disabled', 'readonly','disabled']) !!}
                                        {!! $errors->first('nama_penerbit', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group {{ ($errors->has('kode_penerbit')?"has-error":"") }}"><label class="col-sm-4 control-label">Kode Penerbit</label>
                                    <div class="col-sm-8 col-xs-12">
                                        {!! Form::text('kode_penerbit', null, ['class' => 'form-control disabled', 'readonly','disabled']) !!}
                                        {!! $errors->first('kode_penerbit', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
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
                                <div class="form-group">
                                    <div class="col-sm-8 col-sm-offset-4">
                                        <a class="btn btn-white" href="{{ url('master/penerbit') }}">
                                            <i class="fa fa-angle-left"></i> kembali
                                        </a>
                                    </div>
                                </div>
                                {!! Form::close() !!}
                              </div>
                              <div class="col-sm-6">
                                <?php if ($previlege->isAllow($login->id_user, $login->id_department, $config['edit'])) { ?>
                                  <a href="{{ url('master/penerbit/edit/'.$data->id_penerbit) }}" class="btn btn-primary dim btn-xs btn-rounded"><i class="fa fa-paste"></i> ubah</a>
                                <?php } ?>

                                <?php if ($previlege->isAllow($login->id_user, $login->id_department, $config["view"])) { ?>
                                  <a href="{{ url('master/penerbit/detail/'.$data->id_penerbit) }}" class="btn btn-white btn-outline dim btn-xs"><i class="fa fa-eye"></i> detail</a>
                                <?php } ?>

                                @if($previlege->isAllow($login->id_user,$login->id_department,$config["approve"]) && $data->status != 'active')
                                <a data-id="{{ $data->penerbit }}" data-url="{{ url('master/penerbit/approve/' . $data->id_penerbit) }}" class="btn btn-primary btn-rounded btn-outline dim btn-xs confirm tooltips text-white btn-lg" title="approve data <?= $data->title ?>"><i class="fa fa-check"></i> approve</a></a>
                                @else
                                <a data-id="{{ $data->penerbit }}" data-url="{{ url('master/penerbit/deactivate/' . $data->id_penerbit) }}" class="btn btn-danger btn-outline dim btn-xs confirm tooltips btn-lg" title="deactivate data <?= $data->title ?>"><i class="fa fa-ban"></i> deactivate</a></a>
                                @endif

                                <?php if ($previlege->isAllow($login->id_user, $login->id_department, $config["remove"])) { ?>
                                  <a data-id="{{ $data->id_penerbit }}" data-url="{{ url('master/penerbit/remove/' . $data->id_penerbit) }}" class="btn btn-danger btn-outline dim btn-xs confirm"><i class="fa fa-trash"></i> hapus</a>
                                <?php } ?>
                              </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Daftar Laporan Penerbit</h5>
                            <?php if ($previlege->isAllow($login->id_user, $login->id_department, $config['create'])) { ?>
                                <div class="ibox-tools">
                                    <a class="btn btn-secondary btn-sm" href="{{ url('master/laporan-campaign/create/'.$data->id_penerbit.'?backlink=master/penerbit/manage/'.$data->id_penerbit) }}">
                                        <i class="fa fa-plus"></i> tambah
                                    </a>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="ibox-content">
                            @include('backend.flash_message')
                            <div class="row">
                              <div class="col-sm-2">
                                  <div class="input-group m-b">
                                      <span class="input-group-addon">Urutkan</span>
                                      <div class="input-group-btn bg-white">
                                          <button data-toggle="dropdown" class="btn btn-white dropdown-toggle btn-sm bg-white" type="button" aria-expanded="false"><?= (@$input['short'] == "") ? $shorter[$default['short']] : $shorter[$input['short']] ?> <span class="caret"></span></button>

                                          <ul class="dropdown-menu">
                                              <?php
                                              foreach ($shorter as $key => $val) {
                                              ?>
                                                  <li class="<?= ($key == Request::input("short")) ? "active" : "" ?>">
                                                      <a href="<?= url($main_url.'/?page=' . @$input['page'] . '&keyword=' . @$input['keyword'] . '&short=' . $key . '&shortmode=' . @$shortmode) ?>"><?= $val ?></a>
                                                  </li>
                                              <?php
                                              }
                                              ?>
                                          </ul>
                                      </div>
                                  </div>
                              </div>
                              <div class="col-sm-4">
                                  <div class="input-group m-b">
                                      <span class="input-group-addon">Mode</span>
                                      <div class="input-group-btn bg-white">
                                          <button data-toggle="dropdown" class="btn btn-white dropdown-toggle btn-sm bg-white" type="button" aria-expanded="false"><?= (@$shortmode == "asc") ? "A ke Z / Terendah ke Tertinggi / Terlama ke Terbaru" : "Z ke A / Tertinggi ke Terendah / Terbaru ke Terlama" ?> <span class="caret"></span></button>
                                          <ul class="dropdown-menu">
                                              <li class="<?= ("asc" == Request::input("shortmode")) ? "active" : "" ?>">
                                                  <a href="<?= url($main_url.'/?page=' . @$input['page'] . '&keyword=' . @$input['keyword'] . '&short=' . @$input['short'] . '&shortmode=asc') ?>">A ke Z / Terendah ke Tertinggi / Terlama ke Terbaru</a>
                                              </li>
                                              <li class="<?= ("desc" == Request::input("shortmode")) ? "active" : "" ?>">
                                                  <a href="<?= url($main_url.'/?page=' . @$input['page'] . '&keyword=' . @$input['keyword'] . '&short=' . @$input['short'] . '&shortmode=desc') ?>">Z ke A / Tertinggi ke Terendah / Terbaru ke Terlama</a>
                                              </li>
                                          </ul>
                                      </div>

                                  </div>
                              </div>
                              <div class="col-sm-2">
                              </div>
                              <div class="col-sm-4">
                                <form class="" role="form" method="GET" id="loginForm">
                                  <div class="input-group m-b">
                                    <input type="hidden" name="page" value="<?=@$input['page']?>">
                                    <input type="hidden" name="short" value="<?=@$input['short']?>">
                                    <input type="hidden" name="shortmode" value="<?=@$shortmode?>">
                                    <input type="text" placeholder="Search" class="input-sm form-control" name="keyword" value="<?= @$input['keyword'] ?>">
                                    <span class="input-group-btn">
                                      <button type="submit" class="btn btn-sm btn-search"> Cari</button>
                                    </span>
                                  </div>
                                </form>
                              </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover dataTables-example" id="datatable">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode Laporan</th>
                                            <th>Penawaran</th>
                                            <th>Bulan</th>
                                            <th>Tahun</th>
                                            <th>Status</th>
                                            <th>Keuntungan</th>
                                            <th>Author</th>
                                            <th>Tanggal Upload</th>
                                            <th>Catatan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $tipeuser = ['0' => 'External', '1' => 'Internal (Bergaji)'];
                                        $counter = 0;
                                        if ($page != "") {
                                            $counter = ($page - 1) * $limit;
                                        }

                                        if ($laporan) {
                                            foreach ($laporan as $key => $value) {
                                                $counter++;
                                        ?>
                                                <tr>
                                                    <td>{{$counter}}</td>
                                                    <td>{{$value->report_code}}</td>
                                                    <td>{{$value->campaign_title}}</td>
                                                    <td><?=$months[$value->report_month]?></td>
                                                    <td>{{$value->report_year}}</td>
                                                    <td><?=$statuses[$value->status]?></td>
                                                    <td class="text-right"><strong>Rp.<?=number_format($value->profit,0)?></strong></td>
                                                    <td>{{$value->first_name}}</td>
                                                    <td>{{date("d M Y H:i",$value->time_created)}}</td>
                                                    <td>{{$value->catatan}}</td>
                                                    <td>

                                                        <?php if ($previlege->isAllow($login->id_user, $login->id_department, $config['manage'])) { ?>
                                                            <a href="{{ url('master/laporan-campaign/detail/'.$value->id_report.'?backlink=master/penerbit/manage/'.$data->id_penerbit) }}" class="btn btn-primary btn-rounded dim btn-xs"><i class="fa fa-cogs"></i> kelola </a>
                                                        <?php } ?>

                                                        <?php if ($previlege->isAllow($login->id_user, $login->id_department, $config['remove'])) { ?>
                                                            <a data-id="{{ $value->id_report }}" data-url="{{ url('master/laporan-campaign/remove/' . $value->id_report) }}" class="btn btn-danger btn-outline dim btn-xs confirm"><i class="fa fa-trash"></i> hapus</a>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </tbody>
                                    </tfoot>
                                </table>
                                <?= $pagging ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Daftar Deviden Penerbit</h5>
                            <?php if ($previlege->isAllow($login->id_user, $login->id_department, $config['create'])) { ?>
                                <div class="ibox-tools">
                                    <a class="btn btn-secondary btn-sm" href="{{ url('master/laporan-campaign/create/'.$data->id_penerbit.'?backlink=master/penerbit/manage/'.$data->id_penerbit) }}">
                                        <i class="fa fa-plus"></i> tambah
                                    </a>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="ibox-content">
                            @include('backend.flash_message')
                            <div class="row">
                              <div class="col-sm-2">
                                  <div class="input-group m-b">
                                      <span class="input-group-addon">Urutkan</span>
                                      <div class="input-group-btn bg-white">
                                          <button data-toggle="dropdown" class="btn btn-white dropdown-toggle btn-sm bg-white" type="button" aria-expanded="false"><?= (@$input['short_dev'] == "") ? $shorter_dev[$default_dev['short_dev']] : $shorter_dev[$input['short_dev']] ?> <span class="caret"></span></button>


                                          <ul class="dropdown-menu">
                                              <?php
                                              foreach ($shorter_dev as $key => $val) {
                                              ?>
                                                  <li class="<?= ($key == Request::input("short_dev")) ? "active" : "" ?>">
                                                      <a href="<?= url($main_url.'/?page_dev=' . @$input['page_dev'] . '&keyword_dev=' . @$input['keyword_dev'] . '&short=' . $key . '&shortmode_dev=' . @$shortmode_dev) ?>"><?= $val ?></a>
                                                  </li>
                                              <?php
                                              }
                                              ?>
                                          </ul>
                                      </div>
                                  </div>
                              </div>
                              <div class="col-sm-4">
                                  <div class="input-group m-b">
                                      <span class="input-group-addon">Mode</span>
                                      <div class="input-group-btn bg-white">
                                          <button data-toggle="dropdown" class="btn btn-white dropdown-toggle btn-sm bg-white" type="button" aria-expanded="false"><?= (@$shortmode_dev == "asc") ? "A ke Z / Terendah ke Tertinggi / Terlama ke Terbaru" : "Z ke A / Tertinggi ke Terendah / Terbaru ke Terlama" ?> <span class="caret"></span></button>
                                          <ul class="dropdown-menu">
                                              <li class="<?= ("asc" == Request::input("shortmode_dev")) ? "active" : "" ?>">
                                                  <a href="<?= url($main_url.'/?page_dev=' . @$input['page_dev'] . '&keyword_dev=' . @$input['keyword_dev'] . '&short_dev=' . @$input['short_dev'] . '&shortmode_dev=asc') ?>">A ke Z / Terendah ke Tertinggi / Terlama ke Terbaru</a>
                                              </li>
                                              <li class="<?= ("desc" == Request::input("shortmode_dev")) ? "active" : "" ?>">
                                                  <a href="<?= url($main_url.'/?page_dev=' . @$input['page_dev'] . '&keyword_dev=' . @$input['keyword_dev'] . '&short_dev=' . @$input['short_dev'] . '&shortmode_dev=desc') ?>">Z ke A / Tertinggi ke Terendah / Terbaru ke Terlama</a>
                                              </li>
                                          </ul>
                                      </div>

                                  </div>
                              </div>
                              <div class="col-sm-2">
                              </div>
                              <div class="col-sm-4">
                                <form class="" role="form" method="GET" id="loginForm">
                                  <div class="input-group m-b">
                                    <input type="hidden" name="page_dev" value="<?=@$input['page_dev']?>">
                                    <input type="hidden" name="short_dev" value="<?=@$input['short_dev']?>">
                                    <input type="hidden" name="shortmode_dev" value="<?=@$shortmode_dev?>">
                                    <input type="text" placeholder="Search" class="input-sm form-control" name="keyword_dev" value="<?= @$input['keyword_dev'] ?>">
                                    <span class="input-group-btn">
                                      <button type="submit" class="btn btn-sm btn-search"> Cari</button>
                                    </span>
                                  </div>
                                </form>
                              </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover dataTables-example" id="datatable">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode Deviden</th>
                                            <th>Penawaran</th>
                                            <th>Bulan</th>
                                            <th>Tahun</th>
                                            <th>Status</th>
                                            <th>Tanggal Upload</th>
                                            <th>Total Pendanaan</th>
                                            <th>Keuntungan</th>
                                            <th>Total Deviden</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php

                                        $counter = 0;
                                        if ($page_dev != "") {
                                            $counter = ($page_dev - 1) * $limit_dev;
                                        }

                                        if ($deviden) {
                                            foreach ($deviden as $key => $value) {
                                                $counter++;
                                        ?>
                                                <tr>
                                                    <td>{{$counter}}</td>
                                                    <td>{{$value->invoice_code}}</td>
                                                    <td>{{$value->campaign_title}}</td>
                                                    <td><?=@$months[$value->deviden_month]?></td>
                                                    <td>{{$value->deviden_year}}</td>
                                                    <td><?=@$value->status?></td>
                                                    <td>{{date("d M Y H:i",$value->time_created)}}</td>
                                                    <td class="text-right">
                                                        Rp.<?=number_format($value->target_fund,0)?>,-
                                                    </td>
                                                    <td class="text-right">
                                                      Rp.<?=number_format(@$value->keuntungan,0)?>,-

                                                      <?php
                                                        $percent_keuntungan = ($value->keuntungan*100)/$value->target_fund;
                                                        print "<sub class='text-success'>(".number_format($percent_keuntungan,2)."%)</sub>";
                                                      ?>

                                                    </td>
                                                    <td class="text-right">
                                                      Rp.<?=number_format($value->total_deviden,0)?>,-

                                                      <?php
                                                        $percent_deviden = ($value->total_deviden*100)/$value->keuntungan;
                                                        print "<sub class='text-success'>(".number_format($percent_deviden,2)."%)</sub>";
                                                      ?>
                                                    </td>
                                                    <td>
                                                      <?php if ($previlege->isAllow($login->id_user, $login->id_department, $config['manage'])) { ?>
                                                          <a href="{{ url('master/laporan-campaign/detail/'.$value->id_report.'?backlink=master/penerbit/manage/'.$data->id_penerbit) }}" class="btn btn-primary btn-rounded dim btn-xs"><i class="fa fa-cogs"></i> kelola </a>
                                                      <?php } ?>

                                                      <?php if ($previlege->isAllow($login->id_user, $login->id_department, $config['remove'])) { ?>
                                                          <a data-id="{{ $value->id_report }}" data-url="{{ url('master/laporan-campaign/remove/' . $value->id_report) }}" class="btn btn-danger btn-outline dim btn-xs confirm"><i class="fa fa-trash"></i> hapus</a>
                                                      <?php } ?>

                                                      <?php if ($previlege->isAllow($login->id_user, $login->id_department, $config['approve'])) { ?>
                                                          <a data-id="{{ $value->id_report }}" data-url="{{ url('master/laporan-campaign/remove/' . $value->id_report) }}" class="btn btn-danger btn-outline dim btn-xs confirm"><i class="fa fa-external-alt"></i> bagikan</a>
                                                      <?php } ?>
                                                    </td>

                                                </tr>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </tbody>
                                    </tfoot>
                                </table>
                                <?= $pagging_dev ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('backend.footer')
        @include('backend.do_confirm')
    </div>
</div>
