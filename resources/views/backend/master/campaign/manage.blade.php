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
                <h2>Campaign</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('master') }}">Master</a>
                    </li>
                    <li>
                        <a href="{{ url('master/campaign') }}">Campaign</a>
                    </li>
                    <li class="active">
                        <strong>Kelola Campaign</strong>
                    </li>
                </ol>
            </div>
            <div class="col-lg-2">

            </div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">
          @include('backend.flash_message')
            <div class="row">
              {!! Form::model($data,['url' => url($main_url.'/updatemanage/'.$data->id_campaign), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
                <input type="hidden" name="backlink" value="master/campaign/manage/<?=$data->id_campaign?>">
                <div class="col-lg-6 col-xs-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Info Campaign</h5>
                        </div>
                        <div class="ibox-content">
                            @if ($errors->any())
                            <div class="alert alert-danger">
                                Ada kesalahan! mohon cek formulir.
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            </div>
                            @endif

                            <!-- create date -->
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group {{ ($errors->has('time_created')?"has-error":"") }}"><label class="col-sm-4 control-label">Waktu Terdaftar</label>
                                        <div class="col-sm-8 col-xs-12">
                                            <input type="text" class="form-control disabled" disabled readonly value="<?= date("Y-m-d H:i:s", $data->time_created) ?>">
                                        </div>
                                    </div>
                                    <!-- last update -->
                                    <div class="form-group {{ ($errors->has('last_update')?"has-error":"") }}"><label class="col-sm-4 control-label">Pembaruan Terakhir</label>
                                        <div class="col-sm-8 col-xs-12">
                                            <input type="text" class="form-control disabled" disabled readonly value="<?= date("Y-m-d H:i:s", $data->last_update) ?>">
                                        </div>
                                    </div>
                                    <!-- campaign title -->
                                    <div class="form-group {{ ($errors->has('campaign_title')?"has-error":"") }}"><label class="col-sm-4 control-label">Judul Campaign</label>
                                        <div class="col-sm-8 col-xs-12">
                                            {!! Form::text('campaign_title', null, ['class' => 'form-control disabled', 'readonly','disabled']) !!}
                                            {!! $errors->first('campaign_title', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                        </div>
                                    </div>

                                    <!-- campaign title -->
                                    <div class="form-group {{ ($errors->has('nama_penerbit')?"has-error":"") }}"><label class="col-sm-4 control-label">Nama Perusahaan</label>
                                        <div class="col-sm-8 col-xs-12">
                                            {!! Form::text('nama_penerbit', null, ['class' => 'form-control disabled', 'readonly','disabled']) !!}
                                            {!! $errors->first('nama_penerbit', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                        </div>
                                    </div>
                                    <!-- campaign img -->
                                    <div class="form-group {{ ($errors->has('photos')?"has-error":"") }}"><label class="col-sm-4 control-label">Foto</label>
                                        <div class="col-sm-8 col-xs-12">
                                            <img src="<?= url($data->photos) ?>" class="img-fluid" style="height: 100px;" alt="">
                                            {!! $errors->first('photos', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                        </div>
                                    </div>
                                    <!-- campaign description -->
                                    <div class="form-group {{ ($errors->has('campaign_description')?"has-error":"") }}"><label class="col-sm-4 control-label">Deskripsi Campaign</label>
                                        <div class="col-sm-8 col-xs-12">
                                            <textarea name="campaign_description" class="form-control disabled" disabled rows="10">{{$data->campaign_description}}</textarea>
                                            {!! $errors->first('campaign_description', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                        </div>
                                    </div>

                                    <!-- Start date -->
                                    <div class="form-group {{ ($errors->has('start_date')?"has-error":"") }}"><label class="col-sm-4 control-label">Tanggal Mulai</label>
                                        <div class="col-sm-8 col-xs-12">
                                            {!! Form::text('start_date', null, ['class' => 'form-control disabled', 'readonly','disabled']) !!}
                                            {!! $errors->first('start_date', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                        </div>
                                    </div>
                                    <!-- end date -->
                                    <div class="form-group {{ ($errors->has('end_date')?"has-error":"") }}"><label class="col-sm-4 control-label">Tanggal Berakhir</label>
                                        <div class="col-sm-8 col-xs-12">
                                            {!! Form::text('end_date', null, ['class' => 'form-control disabled', 'readonly','disabled']) !!}
                                            {!! $errors->first('end_date', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                        </div>
                                    </div>
                                    <!-- Target fund -->
                                    <div class="form-group {{ ($errors->has('target_fund')?"has-error":"") }}"><label class="col-sm-4 control-label">Target Pendanaan</label>
                                        <div class="col-sm-8 col-xs-12">
                                            <input type="text" class="form-control disabled" disabled value="Rp.<?= number_format($data->target_fund) ?>">
                                            {!! $errors->first('target_fund', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                        </div>
                                    </div>
                                    <!-- Total Lembar -->
                                    <div class="form-group {{ ($errors->has('total_lembar')?"has-error":"") }}"><label class="col-sm-4 control-label">Total Lembar</label>
                                        <div class="col-sm-8 col-xs-12">
                                            {!! Form::text('total_lembar', number_format($data->total_lembar).' lembar', ['class' => 'form-control disabled', 'readonly','disabled']) !!}
                                            {!! $errors->first('total_lembar', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                        </div>
                                    </div>
                                    <!-- minimum pembelian -->
                                    <div class="form-group {{ ($errors->has('minimum_pembelian')?"has-error":"") }}"><label class="col-sm-4 control-label">Pembelian Minimum (awal)</label>
                                        <div class="col-sm-8 col-xs-12">
                                            <input type="text" class="form-control disabled" disabled value="<?= number_format($data->minimum_pembelian) ?>">
                                            {!! $errors->first('minimum_pembelian', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                        </div>
                                    </div>
                                    <!-- maximum investor -->
                                    <div class="form-group {{ ($errors->has('maximum_investor')?"has-error":"") }}"><label class="col-sm-4 control-label">Maksimal Jumlah Pemodal</label>
                                        <div class="col-sm-8 col-xs-12">
                                            <input type="text" class="form-control disabled" disabled value="<?= number_format($data->maximum_investor) ?>">
                                            {!! $errors->first('maximum_investor', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                        </div>
                                    </div>
                                    <!-- periode deviden -->
                                    <div class="form-group {{ ($errors->has('periode_deviden')?"has-error":"") }}"><label class="col-sm-4 control-label">Periode Deviden</label>
                                        <div class="col-sm-8 col-xs-12">
                                            <input type="text" class="form-control disabled" disabled value="<?= number_format($data->periode_deviden) ?> bulan">
                                            {!! $errors->first('periode_deviden', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                        </div>
                                    </div>
                                    <!-- Tipe Invest -->
                                    <div class="form-group {{ ($errors->has('tipe_produk')?"has-error":"") }}"><label class="col-sm-4 control-label">Tipe Investasi</label>
                                        <div class="col-sm-8 col-xs-12">
                                            <input type="text" class="form-control disabled" disabled value="<?= $data->tipe_produk ?>">
                                            {!! $errors->first('tipe_produk', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                        </div>
                                    </div>

                                    <!-- Tipe Invest -->
                                    <div class="form-group {{ ($errors->has('tipe_produk')?"has-error":"") }}"><label class="col-sm-4 control-label">File Prospektus</label>
                                        <div class="col-sm-8 col-xs-12">
                                            <?php if($data->prospektus!=""){ ?>
                                              <a href="{{ url(str_replace('public/','',$data->prospektus)) }}"><?=$data->prospektus?></a>
                                            <?php }else{ ?>
                                              <p style="padding-top:5px;">
                                              belum ada file prospektus, mohon segera upload file prospektus
                                              </p>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <div class="col-sm-8 col-sm-offset-4">
                                            <?php if($data->release_status!='released'){ ?>
                                            <a class="btn btn-secondary btn-md" href="{{ url('master/campaign/edit/'.$data->id_campaign.'?backlink=master/campaign/manage/'.$data->id_campaign) }}">
                                                <i class="fa fa-edit"></i> Ubah Data
                                            </a>
                                            <?php }?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-sm-12">
                            <a class="btn btn-white" href="{{ url('master/campaign') }}">
                                <i class="fa fa-angle-left"></i> kembali
                            </a>
                        </div>
                    </div>
                  </div>
                  <div class="col-lg-6 col-xs-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Info Investasi</h5>
                        </div>
                        <?php
                          //print "<pre>";
                          //print_r($data);
                          //print "</pre>";

                          $danaterkumpul  = $data->total_terpenuhi;
                          $totalpemodal   = $data->slot_terpakai;

                          if($data->target_fund<=$danaterkumpul){
                            $statuspengumpulandana = "terkumpul";
                          }else{
                            $statuspengumpulandana = "belum";
                          }


                          $status_pendanaan = array(
                            "terkumpul" => "Pendanaan Terkumpul",
                            "belum"     => "Pendanaan Belum Terkumpul"
                          );


                          if(strtotime($data->end_date)< time() && $data->running_status=="open"){
                            $data->running_status = "expired";
                          }

                          //$data->running_status = "expired";

                          $runningstatus = array(
                            "close"     => "Tidak Jalan/Stop",
                            "open"      => "Sedang Berjalan",
                            "expired"   => "Stop karena expired",
                          );

                        ?>
                        <div class="ibox-content">
                          <div class="row">
                              <div class="col-xs-12">
                                <div class="form-group {{ ($errors->has('tipe_produk')?"has-error":"") }}"><label class="col-sm-4 control-label">Total Dana Terkumpul</label>
                                    <div class="col-sm-8 col-xs-12">
                                        <input type="text" class="form-control disabled" disabled value="Rp.<?= number_format($danaterkumpul,0)?>">
                                        {!! $errors->first('tipe_produk', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                    </div>
                                </div>

                                <div class="form-group {{ ($errors->has('tipe_produk')?"has-error":"") }}"><label class="col-sm-4 control-label">Total Pemodal</label>
                                    <div class="col-sm-8 col-xs-12">
                                        <input type="text" class="form-control disabled" disabled value="<?= number_format($totalpemodal,0)?> pemodal">
                                        {!! $errors->first('tipe_produk', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                    </div>
                                </div>

                                <div class="form-group {{ ($errors->has('status_pengumpulan')?"has-error":"") }}"><label class="col-sm-4 control-label">Status Pengumpulan Dana</label>
                                    <div class="col-sm-8 col-xs-12">
                                        <input type="text" class="form-control disabled" disabled value="<?=($status_pendanaan[$statuspengumpulandana])?>">
                                        {!! $errors->first('tipe_produk', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                    </div>
                                </div>

                                <div class="form-group {{ ($errors->has('tanggal_release_dana')?"has-error":"") }}"><label class="col-sm-4 control-label">Tanggal Release Dana</label>
                                    <div class="col-sm-8 col-xs-12">
                                        {!! Form::date('tanggal_release_dana', null, ['class' => 'form-control disabled','disabled']) !!}
                                        {!! $errors->first('tanggal_release_dana', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                        <?=($data->id_rups=='' || ($data->target_fund > $danaterkumpul))?"<p class='alert alert-danger top5'>Anda tidak dapat menentukan tanggal release dana sampai dengan data RUPS pertama diisi & pengumpulan dana tercapai</p>":''?>
                                    </div>
                                </div>

                                <div class="form-group {{ ($errors->has('release_status')?"has-error":"") }}"><label class="col-sm-4 control-label">Status Pendanaan</label>
                                    <div class="col-sm-8 col-xs-12">
                                        {!! Form::text('release_status', $data->release_status, ['class' => 'form-control disabled', 'readonly','disabled']) !!}
                                        {!! $errors->first('release_status', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                    </div>
                                </div>

                                <div class="form-group {{ ($errors->has('id_rups')?"has-error":"") }}"><label class="col-sm-4 control-label">RUPS Pertama</label>
                                    <div class="col-sm-8 col-xs-12">
                                        {!! Form::select('id_rups', @$rupss ,null, ['class' => 'form-control',($data->id_rups!='')?'disabled':'']) !!}
                                        {!! $errors->first('id_rups', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                        <?php if($data->id_rups==''){ ?>
                                        <p class="top15">
                                          Tidak menemukan RUPS yang ditunjuk? Kelola RUPS <a href="{{ url('master/rups') }}"><strong>disini</strong></a>
                                        </p>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="form-group {{ ($errors->has('running_status')?"has-error":"") }}"><label class="col-sm-4 control-label">Running Status</label>
                                    <div class="col-sm-8 col-xs-12">
                                        {!! Form::text('running_status', $runningstatus[$data->running_status], ['class' => 'form-control disabled', 'readonly','disabled']) !!}
                                        {!! $errors->first('running_status', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col-sm-8 col-sm-offset-4">
                                      <?php if($data->id_rups!="" && ($data->target_fund<=$danaterkumpul) && $data->release_status!='released'){ ?>
                                        <a data-url="{{ url('master/campaign/releasedana/'.$data->id_campaign.'?backlink=master/campaign/manage/'.$data->id_campaign) }}" data-id="<?=$data->id_campaign?>" class="btn btn-danger confirm"><strong>Release Dana</strong></a>
                                      <?php } ?>

                                      <?php if($data->release_status=='released'){ ?>
                                        <a href="{{ url('master/campaign/releasedana/'.$data->id_campaign.'?backlink=master/campaign/manage/'.$data->id_campaign) }}" data-id="<?=$data->id_campaign?>" class="btn btn-info btn-rounded"><strong>Info Pendanaan</strong></a>
                                      <?php } ?>

                                      <?php if($data->release_status!='released'){ ?>
                                        <?php if($data->running_status=="close" && $data->id_rups!=""){ ?>
                                          <a data-url="{{ url('master/campaign/runcampaign?backlink=master/campaign/manage/'.$data->id_campaign) }}" data-id="<?=$data->id_campaign?>" class="btn btn-success btn-rounded confirm"><strong>Jalankan Campaign</strong></a>
                                        <?php }elseif($data->running_status=="open" && $data->id_rups!=""){ ?>
                                          <a data-url="{{ url('master/campaign/closecampaign?backlink=master/campaign/manage/'.$data->id_campaign) }}" data-id="<?=$data->id_campaign?>" class="btn btn-danger btn-rounded confirm"><strong>Stop Campaign</strong></a>
                                        <?php }?>

                                        <button class="btn btn-primary btn-rounded" href="{{ url('master/campaign') }}" type="submit">
                                            Simpan Perubahan
                                        </button>
                                      <?php }?>
                                    </div>
                                </div>
                              </div>
                            </div>
                        </div>
                    </div>
                  </div>
                  {!! Form::close() !!}
                <div class="col-xs-12">

                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Daftar Laporan Keuangan Campaign</h5>
                            <?php if ($previlege->isAllow($login->id_user, $login->id_department, $config['create'])) { ?>
                                <div class="ibox-tools">
                                    <a class="btn btn-secondary btn-sm" href="{{ url('master/laporan-campaign/create/'.$data->id_penerbit.'?backlink=master/campaign/manage/'.$data->id_campaign.'&id_campaign='.$data->id_campaign) }}">
                                        <i class="fa fa-plus"></i> tambah
                                    </a>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="ibox-content">
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
                                                            <a href="{{ url('master/laporan-campaign/detail/'.$value->id_report.'?backlink=master/campaign/manage/'.$data->id_campaign) }}" class="btn btn-primary btn-rounded dim btn-xs"><i class="fa fa-cogs"></i> kelola </a>
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

                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Daftar Deviden</h5>
                            <?php if ($previlege->isAllow($login->id_user, $login->id_department, $config['create'])) { ?>
                                <div class="ibox-tools">
                                    <a class="btn btn-secondary btn-sm" href="{{ url('master/deviden/create/'.$data->id_penerbit.'?backlink=master/campaign/manage/'.$data->id_campaign.'&id_campaign='.$data->id_campaign) }}">
                                        <i class="fa fa-plus"></i> tambah
                                    </a>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="ibox-content">
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
                                                      Rp.<?=number_format($value->total_tagihan,0)?>,-
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

                    <div class="row">
                      <div class="col-sm-6">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <h5>Grafik Perkembangan Laporan</h5>
                            </div>
                            <div class="ibox-content">
                              <div class="row">
                                  <div class="col-xs-12">
                                    <canvas id="lineChart_laporan" height="125"></canvas>
                                  </div>
                                </div>
                            </div>
                        </div>
                      </div>

                      <div class="col-sm-6">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <h5>Grafik Perkembangan Deviden</h5>
                            </div>
                            <div class="ibox-content">
                              <div class="row">
                                  <div class="col-xs-12">
                                    <canvas id="lineChart_deviden" height="125"></canvas>
                                  </div>
                                </div>
                            </div>
                        </div>
                      </div>
                    </div>

                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Daftar Pemodal</h5>
                        </div>
                        <div class="ibox-content">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover dataTables-example" id="datatable">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Pemodal</th>
                                            <th>Banyak Saham</th>
                                            <th>Total Pembelian</th>
                                            <th>Tanggal Pembelian</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                      <?php
                                        $counter = 0;

                                        if($pemodals){
                                          foreach ($pemodals as $key => $value) {
                                            $counter++;
                                            //print_r($value);
                                            //exit();
                                            ?>
                                            <tr>
                                              <td><?=$counter?></td>
                                              <td><?=@$value->first_name?> <?=@$value->last_name?></td>
                                              <td class="text-right"><?=number_format(@$value->quantity,0)?> lembar</td>
                                              <td class="text-right">Rp.<?=number_format(@$value->quantity*@$value->nilai_beli,0)?></td>
                                              <td><?=date("Y-m-d H:i:s",@$value->time_created)?></td>
                                            </tr>
                                            <?php
                                          }
                                        }
                                      ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @include('backend.footer')
        @include('backend.do_confirm')
    </div>
</div>
<script src="{{ url('templates/admin/js/plugins/chartJs/Chart.min.js') }}"></script>
<?php
  if($laporan){
      $labelx[] = "";
      $labelxvalue[] = 0;

      foreach ($laporan as $key => $value) {
        $labelx[] = $months[$value->report_month]." - ".$value->report_year;
        $labelxvalue[] = $value->profit;
      }
  }

  if($deviden){
      $labelx_dev[] = "";
      $labelxvalue_dev[] = 0;

      foreach ($deviden as $key => $value) {
        $labelx_dev[]       = $months[$value->deviden_month]." - ".$value->deviden_year;
        $labelxvalue_dev[]  = $value->total_tagihan;
      }
  }
?>
<script>
  var lineData = {
      labels: <?=json_encode($labelx)?>,
      datasets: [
          {
              label: "Profit",
              backgroundColor: "rgba(57,182,255,0.5)",
              borderColor: "rgb(34 69 125)",
              pointBackgroundColor: "#ffffff",
              pointBorderColor: "#fff",
              data: <?=json_encode($labelxvalue)?>
          }
      ]
  };

  var lineOptions = {
      responsive: true
  };


  var ctx = document.getElementById("lineChart_laporan").getContext("2d");
  new Chart(ctx, {type: 'bar', data: lineData, options:lineOptions});

  var lineData = {
      labels: <?=json_encode($labelx_dev)?>,
      datasets: [
          {
              label: "Tagihan Deviden",
              backgroundColor: "rgba(57,182,255,0.5)",
              borderColor: "rgb(34 69 125)",
              pointBackgroundColor: "#ffffff",
              pointBorderColor: "#fff",
              data: <?=json_encode($labelxvalue_dev)?>
          }
      ]
  };

  var lineOptions = {
      responsive: true
  };


  var ctx = document.getElementById("lineChart_deviden").getContext("2d");
  new Chart(ctx, {type: 'bar', data: lineData, options:lineOptions});
</script>
