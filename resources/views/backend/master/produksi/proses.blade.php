<?php
  $main_url    = $config['main_url'];
  $methods     = $config;
?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
  var production = new Array();
</script>
<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Kelola Produksi</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url($main_url) }}">Transaksi</a>
                    </li>
                    <li class="active">
                        <strong>Kelola Produksi</strong>
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
                        <h5>Detail Permintaan</h5>
                    </div>
                    <div class="ibox-content bottom30">
                      {!! Form::model($data,['url' => url($main_url.'/store'), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
                        <div class="row">
                            <div class="col-sm-6">
                              <div class="form-group {{ ($errors->has('mitra_name')?"has-error":"") }}">
                                  <label class="col-sm-4 control-label">Tanggal dibuat</label>
                                  <div class="col-sm-8 col-xs-12">
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
                              <div class="form-group {{ ($errors->has('purchase_title')?"has-error":"") }}"><label class="col-sm-4 control-label">Merchant</label>
                                  <div class="col-sm-8 col-xs-12">
                                      <input type="text" class="form-control disabled" readonly value="<?=$data->mitra_name?>" name="purchase_code">
                                  </div>
                              </div>
                            </div>
                            <div class="col-sm-6">
                              <div class="form-group {{ ($errors->has('purchase_code')?"has-error":"") }}"><label class="col-sm-4 control-label">Kode Transaksi</label>
                                  <div class="col-sm-8 col-xs-12">
                                      <input type="text" class="form-control disabled" readonly value="<?=$data->transaction_code?>" name="purchase_code">
                                  </div>
                              </div>
                              <div class="form-group {{ ($errors->has('purchase_title')?"has-error":"") }}"><label class="col-sm-4 control-label">Status</label>
                                  <div class="col-sm-8 col-xs-12">
                                      <?php
                                        $productionb_status = array('queue' => 'Dalam Antrian', "production" => "Proses Produksi","ready" => "Selesai Produksi","packed" => "Siap/Packing","shipted" => "Dikirim", "received" => "Diterima");
                                      ?>
                                      <input type="text" class="form-control disabled" readonly value="<?=$productionb_status[$data->status]?>" name="purchase_code">
                                  </div>
                              </div>
                              <div class="form-group {{ ($errors->has('purchase_title')?"has-error":"") }}"><label class="col-sm-4 control-label">Admin</label>
                                  <div class="col-sm-8 col-xs-12">
                                      <input type="text" class="form-control disabled" readonly value="<?=$data->admin_name?>" name="purchase_code">
                                  </div>
                              </div>
                            </div>
                        </div>
                      {!! Form::close() !!}
                    </div>

                    <div class="ibox-title">
                        <h5>Item Produksi</h5>
                    </div>
                    {!! Form::open(['url' => url($main_url.'/updateproses/'.$data->id_transaction), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
                      <div class="ibox-content">
                      @include('backend.flash_message')
                          <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover dataTables-example" id="datatable">
                              <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Kode Produk</th>
                                    <th>Nama Produk</th>
                                    <th>Quantity</th>
                                    <th>Status MRP</th>
                                    <?php
                                      $arraystat = array("production","ready","packed","shipted");
                                      if(in_array($data->status,$arraystat)){
                                    ?>
                                    <th>Update</th>
                                    <th>Hasil Produksi</th>
                                    <?php if($data->status=="production"){ ?>
                                    <th style="width:240px;"></th>
                                    <?php } ?>
                                    <?php } ?>
                                </tr>
                              </thead>
                              <tbody>
                                <?php
                                  $counter = 0;
                                  $total   = 0;
                                  if($items){
                                    foreach ($items as $key => $value) {
                                      $counter++;
                                      ?>
                                      <tr>
                                        <td><?=$counter?></td>
                                        <td><?=$value->product_code?></td>
                                        <td><?=$value->product_name?></td>
                                        <td class="text-right"><?=number_format($value->quantity,2,",",".")?></td>
                                        <td><?php
                                            $mrp = explode("-",$value->mrp_item);
                                            $strall = array();
                                            foreach ($mrp as $k => $val) {
                                              $item = explode("_",$val);

                                              foreach ($item as $k2 => $v2) {
                                                switch($k2){
                                                  case '0':
                                                    $datamrp['qty'] = (double)$v2 * $value->quantity;
                                                  break;

                                                  case '1':
                                                    $datamrp['biaya'] = (double)$v2 * $value->quantity;
                                                  break;

                                                  case '2':
                                                    $datamrp['stok'] = (double)$v2;
                                                  break;

                                                  case '3':
                                                    $datamrp['kode'] = (string)$v2;
                                                  break;

                                                  case '4':
                                                    $datamrp['nama'] = (string)$v2;
                                                  break;

                                                  case '5':
                                                    $datamrp['id_material'] = (int)$v2;
                                                  break;

                                                  case '6':
                                                    $datamrp['unit'] = (string)$v2;
                                                  break;
                                                }
                                              }
                                              $allitem[] = $datamrp;
                                              $str = "<span class='label label-info'>".$datamrp['qty']." ".$datamrp['unit']." ".$datamrp['nama']."</span>";
                                              $strall[] = $str;
                                            }

                                            print implode(" ",$strall);

                                            ?>
                                        </td>
                                        <?php if(in_array($data->status,$arraystat)){ ?>
                                        <td><?=date("d m Y H:i:s",$value->completion_terakhir)?></td>
                                        <td id="produce_label_<?=$value->id_cart_detail?>">
                                          <?=(@$value->production_completion!="")?$value->production_completion:0?>
                                        </td>

                                          <?php
                                            if($data->status=="production"){
                                              ?>
                                              <td>
                                              <div class="slider" id="slider_<?=$value->id_cart_detail?>"></div>
                                              <input type="hidden" id="completion_<?=$value->id_production?>" name="completion_<?=$value->id_production?>" value="<?=$value->id_production?>">
                                              <input type="hidden" id="produce_<?=$value->id_production?>" name="produce_<?=$value->id_production?>" value="<?=$value->production_completion?>">
                                              <script>
                                                  production.push(<?=json_encode($value)?>);

                                                  $("#slider_<?=$value->id_cart_detail?>").slider({
                                                    value: <?=(@$value->production_completion!="")?$value->production_completion:0?>,
                                                    min: 0,
                                                    max: <?=$value->quantity?>,
                                                    step: 1,
                                                    slide: function( event, ui ) {
                                                      $( "#produce_label_<?=$value->id_cart_detail?>" ).html(ui.value);
                                                      $("#produce_<?=$value->id_production?>").val(ui.value);
                                                      checkallproduction("<?=$value->id_cart_detail?>",ui.value);
                                                    }
                                                  });

                                                  console.log(production);
                                              </script>
                                              </td>
                                              <?php
                                            }
                                          } ?>
                                      </tr>
                                      <?php
                                    }
                                    ?>
                                    <?php
                                  }else{
                                    ?>
                                    <tr>
                                      <td colspan="6">Tidak ada data</td>
                                    </tr>
                                    <?php
                                  }
                                ?>
                              </tbody>
                              </tfoot>
                            </table>
                          </div>
                        </div>

                        <div class="form-group top15">
                          <div class="col-sm-6">

                          </div>
                          <div class="col-sm-6 text-right">
                            <?php if($data->status=="production"){?>
                            <button class="btn btn-info" type="submit" id="finishbtn">Selesai Produksi <i class="fa fa-check"></i></button>

                            <button class="btn btn-success" id="updatebtn" style="display:none;">Simpan <i class="fa fa-disk"></i></button>
                            <?php } ?>

                            <?php if($data->status=="ready"){?>
                              <button class="btn btn-info">Siap Kirim <i class="fa fa-truck"></i></button>
                            <?php } ?>
                          </div>
                        </div>
                      {!! Form::close() !!}
                    <?php if($data->status=="production"){ ?>
                    <div class="ibox-title">
                        <h5>Bahan Baku yang Digunakan</h5>
                    </div>
                    <div class="ibox-content">
                      <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example" id="datatable">
                          <thead>
                            <tr>
                                <th>No.</th>
                                <th>Kode Bahan</th>
                                <th>Nama Bahan</th>
                                <th>Quantity</th>
                                <th>Status</th>
                            </tr>
                          </thead>
                          <tbody>
                          <?php
                            $datasummary = array();
                            $counter = 0;

                            foreach ($allitem as $key => $value) {

                              $itemsum = $value;
                              if($datasummary){
                                $ketemu = false;
                                foreach ($datasummary as $k => $v) {
                                  if($value['kode']==$v['kode']){
                                    $itemsum['qty'] = $value['qty']+$v['qty'];
                                    $datasummary[$k]  = $itemsum;
                                    $ketemu = true;
                                    break;
                                  }
                                }
                                if(!$ketemu){
                                  $datasummary[]  = $itemsum;
                                }
                              }else{
                                $datasummary[]  = $itemsum;
                              }
                            }

                            foreach ($datasummary as $key => $value) {
                              $counter++;
                              ?>
                              <tr>
                                  <td><?=$counter?></td>
                                  <td><?=$value['kode']?></td>
                                  <td><?=$value['nama']?></td>
                                  <td><?=$value['qty']?> <?=$value['unit']?></td>
                                  <td><?=($value['stok']>=$value['qty'])?"<span class='label label-info'>Cukup</span>":"<span class='label label-danger'>Tidak cukup</span>"?></td>
                              </tr>
                              <?php
                            }
                          ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <?php } ?>

                    <?php if($data->status=="packed"){ ?>
                      <?php
                        $mitra = $mitra_model->getDetail($data->id_mitra);
                      ?>
                      <div class="row">
                        <div class="col-sm-6">
                          <div class="ibox-title">
                              <h5>Detail Pengiriman</h5>
                          </div>
                          <div class="ibox-content">
                            {!! Form::model($mitra,['url' => url($main_url.'/store'), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}

                            <div class="form-group {{ ($errors->has('mitra_name')?"has-error":"") }}"><label class="col-sm-4 control-label">Nama Mitra/Cabang</label>
                                <div class="col-sm-8 col-xs-12">
                                    {!! Form::text('mitra_name', null, ['class' => 'form-control disabled', 'disabled' => 'disabled']) !!}
                                    {!! $errors->first('mitra_name', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group {{ ($errors->has('mitra_name')?"has-error":"") }}"><label class="col-sm-4 control-label">Kode mitra</label>
                                <div class="col-sm-8 col-xs-12">
                                    <input type="text" class="form-control disabled" disabled value="<?=$data->mitra_code?>">
                                </div>
                            </div>
                            <div class="form-group {{ ($errors->has('mitra_name')?"has-error":"") }}"><label class="col-sm-4 control-label">Status</label>
                                <div class="col-sm-8 col-xs-12">
                                    <input type="text" class="form-control disabled" disabled value="<?=strtoupper($data->status)?>">
                                </div>
                            </div>
                            <div class="form-group {{ ($errors->has('email')?"has-error":"") }}"><label class="col-sm-4 control-label">Email</label>
                                <div class="col-sm-8 col-xs-12">
                                    {!! Form::email('email', null, ['class' => 'form-control disabled', 'disabled' => 'disabled']) !!}
                                    {!! $errors->first('email', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <div class="form-group {{ ($errors->has('telp')?"has-error":"") }}"><label class="col-sm-4 control-label">No. Telp</label>
                                <div class="col-sm-8 col-xs-12">
                                    {!! Form::text('telp', null, ['class' => 'form-control disabled', 'disabled' => 'disabled']) !!}
                                    {!! $errors->first('telp', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <div class="form-group {{ ($errors->has('address')?"has-error":"") }}"><label class="col-sm-4 control-label">Alamat</label>
                                <div class="col-sm-8 col-xs-12">
                                    {!! Form::textarea('address', null, ['class' => 'form-control disabled', 'disabled' => 'disabled', 'id' => 'address', 'rows' => 2]) !!}
                                    {!! $errors->first('address', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <div class="form-group {{ ($errors->has('landmark')?"has-error":"") }}">
                                <label class="col-sm-4 control-label">Landmark</label>
                                <div class="col-sm-8 col-xs-12">
                                  {!! Form::textarea('landmark', null, ['class' => 'form-control disabled', 'disabled' => 'disabled', 'id' => 'landmark', 'rows' => 2,'placeholder' => 'Landmark/penjelasan posisi tempat, misal:  Sebelah kantor telkom solo']) !!}
                                  {!! $errors->first('landmark', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>
                            {!! Form::close() !!}
                          </div>
                        </div>
                        <div class="col-sm-6">
                          <div class="ibox-title">
                              <h5>Item Pengiriman</h5>
                          </div>
                          {!! Form::open(['url' => url($main_url.'/updateproses/'.$data->id_transaction), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
                            <div class="ibox-content">
                            @include('backend.flash_message')
                                <div class="table-responsive">
                                  <table class="table table-striped table-bordered table-hover dataTables-example" id="datatable">
                                    <thead>
                                      <tr>
                                          <th>No.</th>
                                          <th>Kode Produk</th>
                                          <th>Nama Produk</th>
                                          <th>Quantity</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <?php
                                        $counter = 0;
                                        $total   = 0;
                                        if($items){
                                          foreach ($items as $key => $value) {
                                            $counter++;
                                            ?>
                                            <tr>
                                              <td><?=$counter?></td>
                                              <td><?=$value->product_code?></td>
                                              <td><?=$value->product_name?></td>
                                              <td class="text-right"><?=number_format($value->quantity,2,",",".")?></td>
                                            </tr>
                                            <?php
                                          }
                                          ?>
                                          <?php
                                        }else{
                                          ?>
                                          <tr>
                                            <td colspan="4">Tidak ada data</td>
                                          </tr>
                                          <?php
                                        }
                                      ?>
                                    </tbody>
                                    </tfoot>
                                  </table>
                                </div>
                              </div>

                              <div class="form-group top15">
                                <div class="col-sm-6">

                                </div>
                                <div class="col-sm-6 text-right">
                                  <?php if($data->status=="packed"){?>
                                  <button class="btn btn-info" type="submit">Kirim Sekarang <i class="fa fa-truck"></i></button>

                                  <?php } ?>
                                </div>
                              </div>
                            {!! Form::close() !!}
                        </div>
                      </div>

                    <?php } ?>
                    <div class="row">
                      <div class="form-group top15">
                          <div class="col-sm-6">
                              <a class="btn btn-white" href="{{ url($main_url) }}">
                                  <i class="fa fa-angle-left"></i> kembali
                              </a>
                          </div>
                          <div class="col-sm-6 text-right">
                            <?php if($data->status=="production"){?>
                            <button class="btn btn-danger confirm" data-id="{{ $data->id_transaction }}" data-url="{{ url($config['main_url'].'/remove') }}" type="button">Reset Produksi</button>
                            <?php } ?>
                            <?php if($data->status=="queue"){?>
                            <button class="btn btn-info confirm" type="button"  data-id="{{ $data->id_transaction }}" data-url="{{ url($config['main_url'].'/doproduction') }}" >Mulai Produksi</button>
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
  var terpenuhi = 0;
  console.log(production);
  function checkallproduction(idcd, value){

    for(var i in production){
      if(production[i].id_cart_detail == idcd){
        production[i].produce = value;
        if(production[i].produce >= production[i].quantity){
          production[i].status = "terpenuhi";
        }else{
          production[i].status = "belum terpenuhi";
        }
      }
    }

    console.log(production);
    terpenuhi = 0;
    $("#finishbtn").hide();
    for(var i in production){
      if(production[i].status=="terpenuhi"){
        terpenuhi++;
      }
    }

    if(terpenuhi>=production.length){
      $("#finishbtn").fadeIn(200);
      $("#updatebtn").hide();
    }else{
      $("#finishbtn").hide();
      $("#updatebtn").fadeIn(200);
    }
  }
</script>
