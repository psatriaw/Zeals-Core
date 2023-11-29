<?php
  $main_url = $config['main_url'];
  $statuses = array(
    "paid"              => "Terbayar <strong class='text-success'>[PAID]</strong>",
    "unpaid"            => "Belum Tebayar <strong class='text-warning'>[UNPAID]</strong>",
    "production"        => "distribusi <strong class='text-danger'>[PENGIRIMAN]</strong>",
    "confirmed"         => "Dikonfirmasi outlet <strong class='text-danger'>[Belum Pembayaran]</strong>",
    "accepted"          => "Pembayaran diterima <strong class='text-success'>[PERGUDANGAN]</strong>",
    "queue"             => "Pengecekan data pembayaran <strong class='text-success'>[ANTRIAN]</strong>",
    "post-production"   => "post-production <strong class='text-success'>[DAPUR]</strong>",
    "done"              => "Selesai",
  );
?>
<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Transaksi</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li class="active">
                        <strong>Transaksi</strong>
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
                        <span class="label label-info pull-right">rupiah</span>
                        <h5>Total Pembelian</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">Rp.<?=number_format(@$total_pembelian,0)?></h1>
                        <div class="stat-percent font-bold text-info">

                        </div>
                        <small>pembelian saham</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <span class="label label-info pull-right">rupiah</span>
                        <h5>Total Pembayaran</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">Rp.<?=number_format(@$total_pembayaran,0)?></h1>
                        <div class="stat-percent font-bold text-info">

                        </div>
                        <small>pembayaran</small>
                    </div>
                </div>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <div class="ibox float-e-margins">
                  <div class="ibox-title">
                      <h5>Tabel Data Transaksi</h5>
                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['create'])){?>
                      <div class="ibox-tools">
                          <a class="btn btn-primary btn-sm" href="{{ url($main_url.'/create') }}">
                              <i class="fa fa-plus"></i> tambah permintaan
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
                                                <a href="<?= url('master/banner/?page=' . @$input['page'] . '&keyword=' . @$input['keyword'] . '&short=' . $key . '&shortmode=' . @$shortmode) ?>"><?= $val ?></a>
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
                                            <a href="<?= url('master/banner/?page=' . @$input['page'] . '&keyword=' . @$input['keyword'] . '&short=' . @$input['short'] . '&shortmode=asc') ?>">A ke Z / Terendah ke Tertinggi / Terlama ke Terbaru</a>
                                        </li>
                                        <li class="<?= ("desc" == Request::input("shortmode")) ? "active" : "" ?>">
                                            <a href="<?= url('master/banner/?page=' . @$input['page'] . '&keyword=' . @$input['keyword'] . '&short=' . @$input['short'] . '&shortmode=desc') ?>">Z ke A / Tertinggi ke Terendah / Terbaru ke Terlama</a>
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
                              <th>No.</th>
                              <th>Kode Transaksi</th>
                              <th>Akun</th>
                              <th>Total</th>
                              <th>Total Bayar</th>
                              <th>Saham</th>
                              <th>Tgl Masuk</th>
                              <th>Status</th>
                              <th>Tgl Diperbarui</th>
                              <th>Aksi</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                            $counter = 0;

                            $product_status = array('active' => 'Aktif', 'inactive' => 'Tidak Aktif', 'available' =>'Tersedia','unavailable' => 'Tidak Tersedia');

                            if($page!=""){
                              $counter = ($page-1)*$limit;
                            }

                            if($data){
                              foreach ($data as $key => $value) {
                                $counter++;
                                //print "<pre>";
                                //print_r($value);
                                ?>
                                <tr>
                                  <td><?=$counter?></td>
                                  <td><?=$value->cart_code?></td>
                                  <td>
                                    <label class='label label-info btn-rounded'>
                                      <?=$value->first_name?> <?=$value->last_name?>
                                      <a href="<?=url("admin/user/detail/".$value->id_user)?>"><i class="fa fa-external-link-alt"></i></a>
                                    </label>
                                  </td>
                                  <td class="text-right">Rp.<?=number_format(@$value->total_trx,0)?>,-</td>
                                  <td class="text-right">Rp.<?=number_format(@$value->total_bayar,0)?>,-</td>
                                  <td class="text-right"><?=$value->campaign_title?></td>
                                  <td><?=date("Y-m-d H:i:s",$value->time_created)?></td>
                                  <td><?=$statuses[$value->status]?></td>
                                  <td><?=date("Y-m-d H:i:s",$value->last_update)?></td>
                                  <td>
                                    <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['manage'])){?>
                                        <a href="{{ url($main_url.'/manage/'.$value->id_cart) }}" class="btn btn-white btn-outline dim btn-xs"><i class="fa fa-cogs"></i> kelola</a>
                                    <?php }?>

                                    <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['remove'])){?>
                                        <a data-id="{{ $value->id_cart }}" data-url="{{ url($main_url.'/remove/trx/') }}" class="btn btn-danger btn-outline dim btn-xs confirm"><i class="fa fa-trash"></i> hapus</a>
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
                      <?=$pagging?>
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
<script>
  var carts;
  var counter = 0;
  function getCostProduction(){
    var id_cart = carts[counter].id_cart;
    counter++;
    console.log(id_cart);
    $.ajax({
      "url" : "<?=url("get-transaction-cost/")?>",
      "type" : "POST",
      "data"  : {id_cart: id_cart}
    })
    .done(function(e){
      console.log(e);
      var ret = e;
      $("#cart_"+id_cart).html(ret.cost);
      if(counter < Object.keys(carts).length){
        getCostProduction();
      }
    })
    .fail(function(message){
      console.log(message.getMessage());
    });
  }

  <?php
  if($data){
    echo "carts = ".json_encode($data->toArray()).";";
    echo "getCostProduction();";
  }
  ?>
</script>
