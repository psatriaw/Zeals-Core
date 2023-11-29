<style>
  .fa{
    width:18px;
  }
  .dropdown-menu li a{
    padding-left: 10px;
  }
</style>
<?php

$main_url = $config['main_url'];

?>

<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
      <div class="col-lg-10">
        <h2>Industries</h2>
        <ol class="breadcrumb">
          <li>
            <a href="{{ url('dashboard/view') }}">Dashboard</a>
          </li>
          <li class="active">
            <strong>Industries</strong>
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
                    <span class="label label-success pull-right">Sektor Industri</span>
                    <h5>Total Aktif</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins"><?=number_format(@$total_active)?></h1>
                    <div class="stat-percent font-bold text-success">
                        <?=number_format((@$total_active/@$total_data)*100,2)?>% active
                    </div>
                    <small>Industries</small>
                </div>
            </div>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-12">
          <div class="ibox float-e-margins">
            <div class="ibox-title">
              <h5>Industries</h5>
              <?php if ($previlege->isAllow($login->id_user, $login->id_department, "category-view")) { ?>
                <div class="ibox-tools">
                  <a class="btn btn-secondary btn-sm" href="{{ url('master/category/create') }}">
                    <i class="fa fa-plus"></i> create industry
                  </a>
                </div>
              <?php } ?>
            </div>
            <div class="ibox-content">
              @include('backend.flash_message')
              <div class="row">
                <div class="col-sm-2">
                    <div class="input-group m-b">
                        <span class="input-group-addon">Short By</span>
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
                            <button data-toggle="dropdown" class="btn btn-white dropdown-toggle btn-sm bg-white" type="button" aria-expanded="false"><?= (@$shortmode == "asc") ? "A to Z / Lowest to Highest / Old to New" : "Z to A / Highest to Lowest / New to Old" ?> <span class="caret"></span></button>
                            <ul class="dropdown-menu">
                              <li class="<?= ("asc" == Request::input("shortmode")) ? "active" : "" ?>">
                                  <a href="<?= url($main_url.'/?page=' . @$input['page'] . '&keyword=' . @$input['keyword'] . '&short=' . @$input['short'] . '&shortmode=asc') ?>">A to Z / Lowest to Highest/ Old to New</a>
                              </li>
                              <li class="<?= ("desc" == Request::input("shortmode")) ? "active" : "" ?>">
                                  <a href="<?= url($main_url.'/?page=' . @$input['page'] . '&keyword=' . @$input['keyword'] . '&short=' . @$input['short'] . '&shortmode=desc') ?>">Z to A / Highest to Lowest / New to Old</a>
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
                      <input type="text" placeholder="Search" class="input-sm form-control" name="keyword" value="<?= @$input['keyword'] ?>"  style="height:36px;">
                      <span class="input-group-btn">
                        <button type="submit" class="btn btn-sm btn-search"> Search</button>
                      </span>
                    </div>
                  </form>
                </div>
              </div>

              <div class="table-responsives">
                <table class="table table-striped table-bordered table-hover dataTables-example" id="datatable">
                  <thead>
                    <tr>
                      <th>No.</th>
                      <th>Industry</th>
                      <th>Campaigns</th>
                      <th>Time Created</th>
                      <th>Last Update</th>
                      <th>Action</th>
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
                    ?>
                        <tr>
                          <td><?= $counter ?></td>
                          <td><?= $value->nama_sektor_industri ?></td>
                          <td><?= @$value->total_campaign ?></td>
                          <td><?= date("Y-m-d H:i:s", $value->time_created) ?></td>
                          <td><?= date("Y-m-d H:i:s", $value->last_update) ?></td>
                          <td>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn dropdown-toggle btn-xs btn-info" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="glyphicon glyphicon-option-horizontal"></i>
                                </button>
                                <ul class="dropdown-menu">
                                  <?php if ($previlege->isAllow($login->id_user, $login->id_department, $config['edit'])) { ?>
                                    <li>
                                      <a href="{{ url('master/category/edit/'.$value->id_sektor_industri) }}" class=""><i class="fa fa-paste"></i> edit</a>
                                    </li>
                                  <?php } ?>

                                  <?php if ($previlege->isAllow($login->id_user, $login->id_department, $config['view'])) { ?>
                                    <li>
                                      <a href="{{ url('master/category/detail/'.$value->id_sektor_industri) }}" class=""><i class="fa fa-eye"></i> detail</a>
                                    </li>
                                  <?php } ?>

                                  <?php if ($previlege->isAllow($login->id_user, $login->id_department, $config['remove'])) { ?>
                                    <li class="text-danger">
                                      <a data-id="{{ $value->id_category }}" data-url="{{ url('master/category/remove/' . $value->id_sektor_industri) }}" class="confirm"><i class="fa fa-trash"></i> remove</a>
                                    </li>
                                  <?php } ?>
                                </ul>
                            </div>
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
