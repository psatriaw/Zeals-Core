<?php
  $main_url = $config['main_url'];
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
                    <li class="active">
                        <strong>Pra Penawaran</strong>
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
                        <span class="label label-danger pull-right">dukungan</span>
                        <h5>Total Dukungan Tercatat</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins"><i class="fa fa-heart"></i> <?=number_format(@$total_like)?></h1>
                        <div class="stat-percent font-bold text-danger">
                            x% dukungan minggu ini
                        </div>
                        <small>dukungan</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <span class="label label-success pull-right">komentar</span>
                        <h5>Total Komentar</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins"><i class="fa fa-comments"></i> <?=number_format(@$total_comments)?></h1>
                        <div class="stat-percent font-bold text-success">
                          y% komentar minggu ini
                        </div>
                        <small>dukungan</small>
                    </div>
                </div>
            </div>
          </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Tabel Data Penerbit</h5>
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
                                            <th>Kode</th>
                                            <th>J. Komentar</th>
                                            <th>J. Like</th>
                                            <th>Akun PIC</th>
                                            <th>Tgl. Dibuah</th>
                                            <th>Tgl. Update</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $counter = 0;
                                        if ($page != "") {
                                            $counter = ($page - 1) * $limit;
                                        }

                                        if ($data) {
                                            foreach ($data as $key => $value) {
                                                $counter++;
                                        ?>
                                                <tr>
                                                    <td><?= $counter ?></td>
                                                    <td> {{$value->nama_penerbit}} </td>
                                                    <td> {{$value->kode_penerbit}} </td>
                                                    <td> {{$value->total_comment}} komentar </td>
                                                    <td> {{$value->total_like}} like </td>
                                                    <td> {{$value->first_name}}  {{$value->last_name}}</td>
                                                    <td><?= date("Y-m-d H:i:s", $value->time_created) ?></td>
                                                    <td><?= date("Y-m-d H:i:s", $value->last_update) ?></td>
                                                    <td>
                                                        <?php if ($previlege->isAllow($login->id_user, $login->id_department, $config['edit'])) { ?>
                                                          <a href="{{ url('master/pra-penawaran/edit/'.$value->id_penerbit) }}" class="btn btn-primary dim btn-xs btn-rounded"><i class="fa fa-paste"></i> ubah</a>
                                                        <?php } ?>

                                                        <?php if ($previlege->isAllow($login->id_user, $login->id_department, $config['view'])) { ?>
                                                            <a href="{{ url('master/pra-penawaran/detail/'.$value->id_penerbit) }}" class="btn btn-white dim btn-xs btn-rounded"><i class="fa fa-info-circle"></i> Detail </a>
                                                        <?php } ?>

                                                        <?php if ($previlege->isAllow($login->id_user, $login->id_department, $config['approve'])) { ?>
                                                            <a href="{{ url('master/pra-penawaran/approve/'.$value->id_penerbit) }}" class="btn btn-primary dim btn-xs btn-rounded"><i class="fa fa-check"></i> Approve </a>
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
