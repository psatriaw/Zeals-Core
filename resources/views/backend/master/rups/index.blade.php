<?php
  $main_url = $config['main_url'];

  $statuses = array(
    "pending"           => "<span class='text text-info'>Belum terlaksana</span>",
    "terlaksana"        => "<span class='text text-success'>Terlaksana</span>",
    "ditetapkan"        => "<span class='text text-success'>Sudah Ditetapkan</span>",
    "tidakterlaksana"   => "<span class='text text-danger'>Tidak Terlaksana</span>",
  );
?>

<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
      <div class="col-lg-10">
        <h2>RUPS Penerbit</h2>
        <ol class="breadcrumb">
          <li>
            <a href="{{ url('dashboard/view') }}">Dashboard</a>
          </li>
          <li class="active">
            <strong>RUPS Penerbit</strong>
          </li>
        </ol>
      </div>
      <div class="col-lg-2">

      </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
      <div class="row">
        <div class="col-lg-4">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-info pull-right">RUPS</span>
                    <h5>Jadwal RUPS Aktif</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins"><?=number_format(@$belum_terlaksana)?></h1>
                    <div class="stat-percent font-bold text-info">
                        <?=number_format(($belum_terlaksana*100)/$total_data,2)?>% RUPS belum terlaksana
                    </div>
                    <small>pelaksanaan</small>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-success pull-right">RUPS</span>
                    <h5>Total RUPS Terlaksana</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins"><?=number_format(@$terlaksana)?></h1>
                    <div class="stat-percent font-bold text-success">
                        <?=number_format(($terlaksana*100)/$total_data,2)?>% RUPS terlaksana
                    </div>
                    <small>pelaksanaan</small>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-danger pull-right">RUPS</span>
                    <h5>Jadwal Tidak Dilaksanakan</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins"><?=number_format(@$tak_terlaksana)?></h1>
                    <div class="stat-percent font-bold text-danger">
                        <?=number_format(($tak_terlaksana*100)/$total_data,2)?>% RUPS tidak terlaksana
                    </div>
                    <small>terbengkalai</small>
                </div>
            </div>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-12">
          <div class="ibox float-e-margins">
            <div class="ibox-title">
              <h5>Tabel RUPS</h5>
              <?php if ($previlege->isAllow($login->id_user, $login->id_department, $config['create'])) { ?>
                <div class="ibox-tools">
                  <a class="btn btn-secondary btn-sm" href="{{ url($main_url.'/create') }}">
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
                  <form class="" role="form" method="GET" id="loginForm" action="<?=url($main_url.'/?page=' . @$input['page'] . '&keyword=' . @$input['keyword'] . '&short=' . @$input['short'] . '&shortmode='.@$shortmode)?>">
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
                      <th>No.</th>
                      <th>Penerbit</th>
                      <th>Campaign/Penawaran</th>
                      <th>Tanggal Pelsaksanaan</th>
                      <th>Total Peserta</th>
                      <th>Besar Deviden</th>
                      <th>Status</th>
                      <th>Tgl. Dibuat</th>
                      <th>Tgl. Update</th>
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

                    if ($data) {
                      foreach ($data as $key => $value) {
                        $counter++;

                        if($value->status=="pending" && strtotime($value->tanggal_rups)<time()){
                          $value->status = "tidakterlaksana";
                        }
                    ?>
                        <tr>
                          <td><?= $counter ?></td>
                          <td><?= $value->nama_penerbit ?></td>
                          <td><?= $value->campaign_title ?></td>
                          <td><?= $value->tanggal_rups ?> <?= $value->jam_rups ?></td>
                          <td><?= number_format($value->total_peserta) ?></td>
                          <td><?= number_format($value->besar_pembagian,2) ?> %</td>
                          <td><?= $statuses[$value->status] ?></td>
                          <td><?= date("Y-m-d H:i:s", $value->time_created) ?></td>
                          <td><?= date("Y-m-d H:i:s", $value->last_update) ?></td>
                          <td>
                            <?php if ($previlege->isAllow($login->id_user, $login->id_department, $config['edit']) && $value->status=="pending") { ?>
                                <a href="{{ url($main_url.'/edit/'.$value->id_rups) }}" class="btn btn-primary dim btn-xs btn-rounded"><i class="fa fa-paste"></i> ubah</a>
                            <?php }else{ ?>
                                <a href="#" class="btn btn-primary dim btn-xs btn-rounded disabled" disabled><i class="fa fa-paste"></i> ubah</a>
                            <?php } ?>

                            <!--
                            <?php if ($previlege->isAllow($login->id_user, $login->id_department, $config["view"])) { ?>
                              <a href="{{ url($main_url.'/detail/'.$value->id_rups) }}" class="btn btn-white btn-outline dim btn-xs"><i class="fa fa-eye"></i> detail</a>
                            <?php } ?>
                            -->

                            <?php if ($previlege->isAllow($login->id_user, $login->id_department, $config["approve"])) { ?>
                              <?php if($value->status=="pending"){ ?>
                                <a data-id="{{ $value->id_rups }}" data-url="{{ url($main_url.'/start/' . $value->id_rups) }}" class="btn btn-primary btn-rounded btn-outline dim btn-xs confirm tooltips text-white" title="mulai RUPS"><i class="fa fa-play"></i> mulai RUPS</a></a>
                              <?php }elseif($value->status=="terlaksana"){ ?>
                                <!--
                                <a data-id="{{ $value->id_rups }}" data-url="{{ url('master/penerbit/deactivate/' . $value->id_rups) }}" class="btn btn-danger btn-outline dim btn-xs confirm tooltips" title="deactivate data <?= $value->title ?>"><i class="fa fa-stop"></i> stop RUPS</a></a>
                                -->
                              <?php } ?>
                            <?php } ?>

                            <?php if ($previlege->isAllow($login->id_user, $login->id_department, $config["manage"])) { ?>
                              <a href="{{ url($main_url.'/manage/'.$value->id_rups) }}" class="btn btn-white btn-outline dim btn-xs"><i class="fa fa-cogs"></i> kelola</a>
                            <?php } ?>

                            <?php if ($previlege->isAllow($login->id_user, $login->id_department, $config["remove"])) { ?>
                              <a data-id="{{ $value->id_penerbit }}" data-url="{{ url('master/penerbit/remove/' . $value->id_penerbit) }}" class="btn btn-danger btn-outline dim btn-xs confirm"><i class="fa fa-trash"></i> hapus</a>
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
      </div>
    </div>
    @include('backend.do_confirm')
    @include('backend.footer')
  </div>
</div>
