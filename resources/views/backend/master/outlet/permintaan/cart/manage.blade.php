<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<?php
  $main_url    = $config['main_url'];
  $methods     = $config;
?>
<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Kelola Keranjang</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url($main_url) }}">Transaksi</a>
                    </li>
                    <li class="active">
                        <strong>Kelola Keranjang</strong>
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
                        <h5>Detail Keranjang</h5>
                    </div>
                    <div class="ibox-content bottom30">
                      {!! Form::model($data,['url' => url($main_url.'/store'), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
                        <div class="row">
                            <div class="col-sm-6">
                              <div class="form-group {{ ($errors->has('mitra_name')?"has-error":"") }}">
                                  <label class="col-sm-4 control-label">Tanggal dibuat</label>
                                  <div class="col-sm-8 col-xs-12">
                                      <?php print $data->id_order; ?>
                                      <input type="text" class="form-control disabled" disabled value="<?=date("d M Y H:i:s",$data->time_created)?>">
                                  </div>
                              </div>
                              <div class="form-group {{ ($errors->has('mitra_name')?"has-error":"") }}"><label class="col-sm-4 control-label">Terakhir diubah</label>
                                  <div class="col-sm-8 col-xs-12">
                                      <input type="text" class="form-control disabled" disabled value="<?=date("d M Y H:i:s",$data->last_update)?>">
                                  </div>
                              </div>
                              <div class="form-group {{ ($errors->has('purchase_code')?"has-error":"") }}"><label class="col-sm-4 control-label">Kode Order</label>
                                  <div class="col-sm-8 col-xs-12">
                                      <input type="text" class="form-control disabled" readonly value="<?=$data->cart_code?>" name="purchase_code">
                                  </div>
                              </div>
                              <div class="form-group {{ ($errors->has('purchase_title')?"has-error":"") }}"><label class="col-sm-4 control-label">Gudang/Pabrik</label>
                                  <div class="col-sm-8 col-xs-12">
                                      <input type="text" class="form-control disabled" readonly value="<?=$data->mitra_name?>" name="purchase_code">
                                  </div>
                              </div>
                            </div>
                            <div class="col-sm-6">
                              <div class="form-group {{ ($errors->has('purchase_title')?"has-error":"") }}"><label class="col-sm-4 control-label">Tanggal Permintaan</label>
                                  <div class="col-sm-8 col-xs-12">
                                      <input type="text" class="form-control disabled" readonly value="<?=$data->cart_date?>" name="purchase_code">
                                  </div>
                              </div>
                              <div class="alert alert-warning">
                                  <h3>Informasi</h3>
                                  <p>Setelah melakukan konfirmasi, anda dapat akan di arahkan ke tagihan yang harus anda bayarkan. Permintaan baru akan diproses setelah dilakukan konfirmasi pembayaran</p>
                              </div>
                            </div>
                        </div>
                      {!! Form::close() !!}
                    </div>

                    <div class="ibox-title">
                        <h5>Item Permintaan</h5>
                        <?php if($data->status=="pending"){?>
                          <?php if($previlege->isAllow($login->id_user,$login->id_department,$methods['create'])){?>
                          <div class="ibox-tools">
                              <a class="btn btn-primary btn-sm" href="{{ url($config['main_url'].'/'.$data->id_cart.'/additem') }}">
                                  <i class="fa fa-plus"></i> tambah item permintaan
                              </a>
                          </div>
                          <?php } ?>
                        <?php } ?>
                    </div>
                    <div class="ibox-content">
                      @include('backend.flash_message')
                      <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example" id="datatable">
                          <thead>
                            <tr>
                                <th>No.</th>
                                <th>Kode Produk</th>
                                <th>Nama Produk</th>
                                <th>Waktu Penambahan</th>
                                <th>Quantity</th>
                                <?php if($data->status=="pending"){?>
                                <th>Aksi</th>
                                <?php } ?>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                              $counter = 0;
                              $total   = 0;
                              $totalqty = 0;
                              if($items){
                                foreach ($items as $key => $value) {
                                  $counter++;
                                  ?>
                                  <tr>
                                    <td><?=$counter?></td>
                                    <td><?=$value->product_code?></td>
                                    <td><?=$value->product_name?></td>
                                    <td><?=date("Y-m-d H:i:s",$value->time_created)?></td>
                                    <td class="text-right"><?=number_format($value->quantity,2,",",".")?></td>
                                    <?php
                                      $totalqty = $totalqty + $value->quantity;
                                      $subtot = $value->item_price*$value->quantity;
                                      $total  = $total+$subtot;
                                    ?>

                                    <td>
                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$methods['remove'])){?>
                                          <a parent-id="{{ $data->id_cart }}" data-id="{{ $value->id_cart_detail }}" data-url="{{ url($main_url.'/remove/item/') }}" class="btn btn-danger btn-outline dim btn-xs confirm"><i class="fa fa-trash"></i> hapus</a>
                                      <?php }?>
                                    </td>

                                  </tr>
                                  <?php
                                }
                                ?>

                                  <tr>
                                    <td colspan="4"></td>

                                    <td class="text-right"><strong><?=number_format($totalqty,2,",",".")?></strong></td>
                                    <td/>
                                  </tr>

                                <?php
                              }else{
                                ?>
                                <tr>
                                  <td colspan="10">Tidak ada data</td>
                                </tr>
                                <?php
                              }
                            ?>
                          </tbody>
                          </tfoot>
                        </table>
                      </div>
                    </div>
                    <br><br>

                    <?php
                      $counter = 0;
                      $total   = 0;
                      $totalqty = 0;

                      $kumpulanrumus = array();

                      if($items){
                        foreach ($items as $key => $value) {
                          $quantity_product = $value->quantity;

                          $rumusanproduct = $rumus_of_product->getData($value->id_product);
                          if($rumusanproduct){
                            foreach ($rumusanproduct as $key2 => $value2) {
                              //$detailrumus  = $rumus_item->getData($value2->id_naikan_rumus);
                              //if($detailrumus){
                                //foreach ($detailrumus as $key3 => $value3) {
                                $value2->qty = $value2->qty * $quantity_product;
                                  $kumpulanrumus[] = $value2;
                                //}
                              //}
                            }
                          }
                        }
                      }
                      //print "<pre>";
                      if(@$kumpulanrumus){
                        foreach ($kumpulanrumus as $key => $value) {
                          $counter++;

                          $id_cart          = $data->id_cart;
                          $id_naikan_rumus  = $value->id_naikan_rumus;
                          $quantity         = $value->qty;

                          $arraydata        = array(
                            "id_cart"             => $id_cart,
                            "id_naikan_rumus"     => $id_naikan_rumus,
                            "qty"                 => $quantity,
                            "time_created"        => time(),
                            "last_update"         => time(),
                            "author"              => $login->id_user,
                            "status"              => "active"
                          );

                          //print_r($arraydata);

                          $check    = $naikan_model->checkData($arraydata);
                          if($check){
                            unset($arraydata['time_created']);
                            $result   = $naikan_model->updateDataByArray($arraydata);
                          }else{
                            $result   = $naikan_model->insertData($arraydata);
                          }
                        }

                      }else{

                      }
                    ?>


                    <div class="row">
                      <div class="form-group top15">
                          <div class="col-sm-6">
                              <a class="btn btn-white" href="{{ url($main_url) }}">
                                  <i class="fa fa-angle-left"></i> kembali
                              </a>
                          </div>
                          <div class="col-sm-6 text-right">
                            <button class="btn btn-danger confirm" data-id="{{ $data->id_cart }}" data-url="{{ url($config['main_url'].'/remove') }}" type="button">Batalkan Transaksi</button>
                            <?php if($data->status=="pending"){?>
                            <button class="btn btn-info confirm" type="button" id="continue-checkout-btn"  data-id="{{ $data->id_cart }}" data-url="{{ url($config['main_url'].'/checkout') }}" >lanjutkan ke pembayaran</button>
                            <?php } ?>
                          </div>
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
  var perumusan = <?=json_encode(array());?>;
  var keperluan = <?=json_encode(array());?>;
  var data_produk = <?=json_encode(array());?>;

  <?php

  if($loyang){
    foreach ($loyang as $key => $value) {
      ?>
      data_produk['<?=$value->id_naikan_rumus?>'] = 0;
      <?php
    }
  }


  if($rumus){
    foreach ($rumus as $key => $value) {
      $detail        = $rumus_item->getData($value->id_naikan_rumus);
      echo "var dataset = ".json_encode($detail).";";
      if($detail){
        foreach ($detail as $keyd => $valued) {
          $detailrumus[] = $valued;
        }
      }
    }

    echo "perumusan = ".json_encode($detailrumus).";";
  }
  if($rumus){
    foreach ($rumus as $key => $value) {
      ?>

      $( "#slider_s_<?=$value->id_naikan_rumus?>" ).slider({
        value:0,
        min: 0,
        max: 100,
        step: 1,
        slide: function( event, ui ) {
          $( "#label-info-<?=$value->id_naikan_rumus?>" ).html(ui.value );
          $("#naik_<?=$value->id_naikan_rumus?>").val(ui.value);
          console.log(ui.value);

          calculateRumusan(<?=$value->id_naikan_rumus?>, ui.value);
        }
      });

      $("#naik_<?=$value->id_naikan_rumus?>").keyup(function(e){
        var nilai = $(this).val();
        $( "#slider_s_<?=$value->id_naikan_rumus?>").slider("value",nilai);
        calculateRumusan(<?=$value->id_naikan_rumus?>, nilai);
      });
      <?php
    }
  }
  ?>

  function calculateRumusan(id_naikan, qty){

    for(var i in perumusan){
      if(perumusan[i].id_naikan_rumus==id_naikan){
        var datanaik  = [];
        datanaik['id_cetakan'] = perumusan[i].id_cetakan;
        datanaik['total']      = perumusan[i].cetakan_quantity * qty;
        keperluan['item_'+perumusan[i].id_cetakan+'_'+id_naikan] = datanaik;
      }
    }

    console.log(keperluan);

    var total_pemenuhan = 0;
    for(var i in data_produk){
      var total_qty = 0;
      for(var j in keperluan){
        if(i==keperluan[j].id_cetakan){
          total_qty = total_qty + keperluan[j].total;
        }
      }
      total_pemenuhan = total_pemenuhan + total_qty;
      $("#pemenuhan_loyang_"+i).html(total_qty);
    }

    $("#total_pemenuhan").html(total_pemenuhan);

    updateOrderData(<?=$data->id_cart?>, id_naikan, qty);
  }

  function updateOrderData(id_order, id_naikan_rumus, qty){
    $("#continue-checkout-btn").html("menyimpan.. mohon menunggu").addClass("disabled").prop("disabled",true);
    $.ajax({
      type: "POST",
      url: "<?=url("update-order-data")?>",
      data: {
        id_order: id_order,
        id_naikan_rumus: id_naikan_rumus,
        qty: qty
      }
    })
    .done(function(result){
      var ret = result;
      if(result.status==200){
        console.log("berhasil");
        console.log(result);
      }else{
        console.log("gagal");
        console.log(result);
      }
      $("#continue-checkout-btn").html("lanjutkan ke pergudangan").removeClass("disabled").prop("disabled",false);
    })
    .fail(function(e){
      console.log(e);
      $("#continue-checkout-btn").html("lanjutkan ke pergudangan").removeClass("disabled").prop("disabled",false);
    });
  }

</script>
