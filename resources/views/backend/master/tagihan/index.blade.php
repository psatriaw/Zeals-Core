<?php
  $main_url = $config['main_url'];
?>
<style>
  .teal-green-bg a{
    color: #fff;
    font-size: 18px;
  }
  .teal-green-bg h1{
    border: 1px solid;
    padding: 5px 10px;
    border-radius: 5px;
    margin-bottom: 20px;
  }
  .teal-green-bg{
    border: 1px solid;
    background: #22457d;
    color: #fff;
  }
  .widget .flot-chart {
    padding-top: 20px;
    padding-bottom: 15px;
    height: auto;
  }
  .paid-green-bg{
    border: 1px solid #ccc;
    background: #ececec;
    color: #444;
  }
  .paid-green-bg a{
    color: #444;
    font-size: 18px;
  }
  .paid-green-bg h1{
    border: 1px solid;
    padding: 5px 10px;
    border-radius: 5px;
    margin-bottom: 20px;
  }
</style>
<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
      <div class="col-lg-10">
        <h2>Tagihan</h2>
        <ol class="breadcrumb">
          <li>
            <a href="{{ url('dashboard/view') }}">Dashboard</a>
          </li>
          <li class="active">
            <strong>Tagihan</strong>
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
              <h5>Tabel Tagihan</h5>
            </div>
            <div class="ibox-content">
              <?php if ($previlege->isAllow($login->id_user, $login->id_department, @$config['control'])) { ?>
                <div class="row">
                  <div class="col-sm-4">
                      <div class="input-group m-b">
                          <span class="input-group-addon">Pilih Penerbit</span>
                          <div class="input-group-btn bg-white">
                              <button data-toggle="dropdown" class="btn btn-white dropdown-toggle btn-sm bg-white" type="button" aria-expanded="false"><?= (@$_GET['id_penerbit'] == "") ? "semua" : $penerbit[$_GET['id_penerbit']] ?> <span class="caret"></span></button>

                              <ul class="dropdown-menu">
                                  <?php
                                  foreach ($penerbit as $key => $val) {
                                  ?>
                                      <li class="<?= ($key == Request::input("short")) ? "active" : "" ?>">
                                          <a href="<?= url($main_url.'/?id_penerbit=' . $key)  ?>"><?= $val ?></a>
                                      </li>
                                  <?php
                                  }
                                  ?>
                              </ul>
                          </div>
                      </div>
                  </div>
                </div>
              <?php } ?>

              @include('backend.flash_message')
              <div class="row">
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
                              <div class="col-sm-3">
                              <div class="widget <?=($value->status=='unpaid')?'teal-green-bg':'paid-green-bg'?> no-padding">
                                  <div class="p-m">
                                      <h1 class="m-xs text-right">Rp.<?=number_format($value->total_tagihan,0)?></h1>

                                      <h3 class="font-bold mb-3" style="margin-bottom:15px;">
                                          <?= $value->invoice_code ?>
                                      </h3>
                                      <small>deviden <?= $value->campaign_title ?></small><br>
                                      <small>periode <?= $months[$value->deviden_month] ?> <?=$value->deviden_year?></small>
                                  </div>
                                  <div class="flot-chart text-right">
                                    <div style="padding:15px;color:#fff;">
                                      <?php if($value->status=="unpaid"){ ?>
                                        <a href ="{{ url($main_url.'/payment/'.$value->id_deviden) }}">
                                            lakukan pembayaran
                                            <i class="fa fa-angle-right"></i>
                                        </a>
                                      <?php }else{ ?>
                                        <a href ="{{ url($main_url.'/payment/'.$value->id_deviden) }}">
                                            detail pembayaran
                                            <i class="fa fa-angle-right"></i>
                                        </a>
                                      <?php } ?>
                                    </div>
                                  </div>
                              </div>
                            </div>
                          <?php
                      }
                    }
                    ?>
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
