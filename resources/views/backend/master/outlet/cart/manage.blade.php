<?php
  $main_url    = $config['main_url'];
  $methods     = $config;
  $production_cost = 0;
  $production_permintaan = 0;

  $statuses = array(
    "pending"       => "<span class='text-danger'><i class='fa fa-exclamation'></i> Pending</span>",
    "disetujui"     => "<span class='text-info'><i class='fa fa-hourglass'></i> Proses Pengiriman</span>",
    "dikirim"       => "<span class='text-info'><i class='fa fa-truck'></i> Proses Pengiriman</span>",
    "selesai"       => "<span class='text-success'><i class='fa fa-check'></i> Selesai</span>",
  );
?>
<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Kelola Permintaan</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url($main_url) }}">Permintaan</a>
                    </li>
                    <li class="active">
                        <strong>Kelola Permintaan</strong>
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
                                      <input type="text" class="form-control disabled" disabled value="<?=date("d M Y H:i:s",$data->time_created)?>">
                                  </div>
                              </div>
                              <div class="form-group {{ ($errors->has('mitra_name')?"has-error":"") }}"><label class="col-sm-4 control-label">Terakhir diubah</label>
                                  <div class="col-sm-8 col-xs-12">
                                      <input type="text" class="form-control disabled" disabled value="<?=date("d M Y H:i:s",$data->last_update)?>">
                                  </div>
                              </div>
                              <div class="form-group {{ ($errors->has('purchase_code')?"has-error":"") }}"><label class="col-sm-4 control-label">Kode Permintaan</label>
                                  <div class="col-sm-8 col-xs-12">
                                      <input type="text" class="form-control disabled" readonly value="<?=$data->cart_code?>" name="purchase_code">
                                  </div>
                              </div>
                              <div class="form-group {{ ($errors->has('purchase_title')?"has-error":"") }}"><label class="col-sm-4 control-label">Outlet</label>
                                  <div class="col-sm-8 col-xs-12">
                                      <input type="text" class="form-control disabled" readonly value="<?=$data->mitra_name?> [<?=$data->mitra_code?>] a/n <?=$data->first_name?>" name="purchase_code">
                                  </div>
                              </div>
                            </div>
                            <div class="col-sm-6">
                              <div class="form-group {{ ($errors->has('purchase_code')?"has-error":"") }}"><label class="col-sm-4 control-label">Tanggal Transaksi</label>
                                  <div class="col-sm-8 col-xs-12">
                                      <input type="text" class="form-control disabled" readonly value="<?=$data->cart_date?>" name="purchase_code">
                                  </div>
                              </div>
                              <div class="form-group {{ ($errors->has('purchase_title')?"has-error":"") }}"><label class="col-sm-4 control-label">Status</label>
                                  <div class="col-sm-8 col-xs-12" style="padding:10px;padding-left:20px;">
                                      <?=$statuses[$data->status]?>
                                  </div>
                              </div>

                            </div>
                        </div>
                      {!! Form::close() !!}
                    </div>
                    
                    <?php
                      //print "<pre>";
                      //print_r($packages->toArray());
                      $materials = $materials->toArray();
                      //print_r($materials);
                      $inarray = array();

                      if($packages){
                        foreach ($packages as $kp => $vp) {
                          $pembagihasilterkecil = 0;
                          $hasilbagiterkecil  = 1000000;

                          $vpitem = explode(";",$vp->items);
                          if(sizeof($vpitem)){
                            foreach ($vpitem as $kdata => $vdata) {
                              $vdata = explode("_",$vdata);
                              if($materials){
                                foreach ($materials as $key => $value){
                                  if($vdata[0]==$value['id_material']){
                                    //print "ketemu disini ".$vdata[0].' vs '.$value['id_material'];
                                    $hasilbagi = (int)($value['material_quantity']/$vdata[1]);
                                    if($hasilbagiterkecil>$hasilbagi){
                                      $hasilbagiterkecil = $hasilbagi;
                                    }
                                    
                                    if(!in_array($value['id_material'],$inarray)){
                                        $inarray[] = $value['id_material'];
                                        $materials[$key]['type'] = "package";
                                        //print_r($materials[$key]);
                                    }
                                  }else{
                                      if(!in_array($value['id_material'],$inarray)){
                                        $materials[$key]['type'] = "non-package";
                                      }
                                  }
                                }
                              }
                            }
                          }

                          $newmaterial = array(
                            "id_cart_detail_material" => $vp->id_material_package,
                            "id_cart"                 => 0,
                            "id_material"             => "custom",
                            "material_price"          => 0,
                            "material_quantity"       => $vp->quantity,
                            "material_discount"       => 0,
                            "time_created"            => time(),
                            "last_update"             => time(),
                            "status"                  => "active",
                            "author"                  => 0,
                            "initial_quantity"        => 0,
                            "disetujui_quantity"      => $vp->quantity,
                            "diterima_quantity"       => 0,
                            "material_name"           => $vp->package_name,
                            "material_code"           => $vp->package_unit,
                            "material_unit"           => $vp->package_unit,
                            "type"                    => 'non-package'
                          );
                          
                          //print_r($newmaterial);
                          
                          $materials[] = $newmaterial;
                        }
                      }


                      //print_r($materials);
                      //print "</pre>";
                    ?>

                    <div class="ibox-title">
                        <h5>Item Permintaan Bahan Baku</h5>
                    </div>
                    <div class="ibox-content">
                      @include('backend.flash_message')
                      <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example" id="datatable">
                          <thead>
                            <tr>
                                <th>No.</th>
                                <th>Kode Bahan</th>
                                <th>Nama Bahan</th>
                                <!--<th>Disarankan</th>-->
                                <th>Diminta</th>
                                <th>Disetujui</th>
                                <th>Diterima Outlet</th>
                                <th>Stock Outlet</th>
                                <th>Satuan</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php

                              $total_reject = 0;
                              $total_biaya = 0;
                              $total_pemenuhan = 0;
                              $total_quantity = 0;
                              $counter = 0;
                              $total   = 0;
                              $total_grand = 0;

                              $countermaterial = 0;
                              $counterdisetujui = 0;

                              if($materials){
                                foreach ($materials as $key => $value) {
                                  $counter++;
                                  $value = (object)$value;
                                  //$subtotal = $value->quantity * ($value->price_outlet - $value->price_outlet_discount);
                                  //$total_grand = $total_grand + $subtotal;
                                  if($value->type=="non-package"){
                                    $countermaterial++;
                                    ?>
                                    <tr>
                                      <td><?=$counter?></td>
                                      <td><?=$value->material_code?></td>
                                      <td><?=$value->material_name?></td>
                                      <!--<td  style="width:150px;" class="text-right"><?=number_format($value->initial_quantity,0,",",".")?></td>-->
                                      <td  style="width:150px;" class="text-right"><?=number_format($value->material_quantity,0,",",".")?></td>
                                      <?php if($data->status=="pending"){?>
                                          <td  style="width:150px;" class="text-right">
                                              <input type="text" class="form-control disetujui" autocomplete="off" id_material="<?=$value->id_material?>" id_cart_detail_material="<?=$value->id_cart_detail_material?>" id="disetujui_quantity_<?=$value->id_material?>" value="<?=$value->disetujui_quantity?>">
                                          </td>
                                      <?php }else{ ?>
                                          <td  style="width:150px;" class="text-right"><?=number_format($value->disetujui_quantity,0,",",".")?></td>
                                      <?php } ?>
                                      <td  style="width:150px;" class="text-right"><?=number_format($value->diterima_quantity,0,",",".")?></td>
                                      <td  style="width:150px;" class="text-right"><?=number_format(@$value->stock,0,",",".")?></td>
                                      <td><?=@$value->material_unit?> </td>
                                    </tr>
                                    <?php
                                  }
                                }

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

                    <?php if($data->mc_status!=""){ ?>
                    {!! Form::model($data,['url' => url($main_url.'/doconfirmpayment/'.$data->id_transaction), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
                    <div class="ibox-title">
                        <h5>Detail Konfirmasi</h5>
                    </div>
                    <div class="ibox-content bottom30">
                        <div class="row">
                            <div class="col-sm-6">
                              <div class="form-group {{ ($errors->has('mc_date')?"has-error":"") }}">
                                  <label class="col-sm-4 control-label">Tanggal Konfirmasi</label>
                                  <div class="col-sm-8 col-xs-12">
                                      {!! Form::hidden('id', $data->id_transaction, ['class' => 'form-control','readonly']) !!}
                                      {!! Form::text('mc_date', null, ['class' => 'form-control thetarget','readonly']) !!}
                                      {!! $errors->first('mc_date', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                  </div>
                              </div>
                              <div class="form-group {{ ($errors->has('mc_bank_target')?"has-error":"") }}"><label class="col-sm-4 control-label">Tujuan Transfer</label>
                                  <div class="col-sm-8 col-xs-12">
                                      {!! Form::text('mc_bank_target', $tujuan_konfirmasi, ['class' => 'form-control','readonly']) !!}
                                      {!! $errors->first('mc_bank_target', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                  </div>
                              </div>
                              <?php
                                $data->mc_total_amount = "Rp. ".number_format($data->mc_total_amount,0,",",".");
                              ?>
                              <div class="form-group {{ ($errors->has('mc_total_amount')?"has-error":"") }}"><label class="col-sm-4 control-label">Nominal Transfer</label>
                                  <div class="col-sm-8 col-xs-12">
                                      {!! Form::text('mc_total_amount', null, ['class' => 'form-control','readonly']) !!}
                                      {!! $errors->first('mc_total_amount', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                  </div>
                              </div>
                              <div class="form-group {{ ($errors->has('mc_bank_name')?"has-error":"") }}"><label class="col-sm-4 control-label">Bank</label>
                                  <div class="col-sm-8 col-xs-12">
                                      {!! Form::text('mc_bank_name', null, ['class' => 'form-control','readonly']) !!}
                                      {!! $errors->first('mc_bank_name', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                  </div>
                              </div>
                              <div class="form-group {{ ($errors->has('mc_bank_account_number')?"has-error":"") }}"><label class="col-sm-4 control-label">Nomor Rekening</label>
                                  <div class="col-sm-8 col-xs-12">
                                      {!! Form::text('mc_bank_account_number', null, ['class' => 'form-control','readonly']) !!}
                                      {!! $errors->first('mc_bank_account_number', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                  </div>
                              </div>
                              <div class="form-group {{ ($errors->has('mc_bank_account')?"has-error":"") }}"><label class="col-sm-4 control-label">Nama Pada Rekening</label>
                                  <div class="col-sm-8 col-xs-12">
                                      {!! Form::text('mc_bank_account', null, ['class' => 'form-control','readonly']) !!}
                                      {!! $errors->first('mc_bank_account', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                  </div>
                              </div>
                            </div>
                            <div class="col-sm-6">
                              <div class="alert alert-info">
                                <h3>Informasi</h3>
                                <p>Data konfirmasi pembayaran sedang dalam moderasi oleh bagian finance, mohon menunggu.</p>
                                <p>Setelah proses moderasi selesai dan konfirmasi pembayaran diterima, pesanan akan segera diproses</p>
                              </div>
                            </div>
                        </div>

                    </div>

                    {!! Form::close() !!}
                    <?php } ?>

                    <div class="row">
                      <div class="form-group top15">
                          <div class="col-sm-6">
                              <a class="btn btn-white" href="{{ url($main_url) }}">
                                  <i class="fa fa-angle-left"></i> kembali
                              </a>
                          </div>
                          <div class="col-sm-6 text-right">

                            <?php if($data->status=="pending" && ($previlege->isAllow($login->id_user,$login->id_department,@$methods['approve']))){ ?>
                              <button class="btn btn-success confirm" data-id="{{ $data->id_cart }}" id="btn-setujui" data-url="{{ url($config['main_url'].'/approve') }}" type="button">Perbarui & Setujui</button>
                            <?php } ?>


                            <?php if($data->status=="disetujui" && $previlege->isAllow($login->id_user,$login->id_department,$methods['kirim'])){?>
                              <button class="btn btn-info confirm" data-id="{{ $data->id_cart }}" data-url="{{ url($config['main_url'].'/send') }}" type="button">Kirim ke Outlet <i class='fa fa-angle-right'></i></button>
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

    function updateDisetujui(id_cart_detail_material, total, id, tipe){
        console.log(id_cart_detail_material+" "+total+" "+id);

        $("#"+id).addClass("disabled").prop("disabled",true);
        $("#btn-setujui").addClass("disabled").prop("disabled",true);
        $.ajax({
          type: "POST",
          url: "<?=url("update-disetujui")?>",
          data: {
            id_cart_detail_material: id_cart_detail_material,
            total: total,
            tipe: tipe,
            id_cart: <?=$data->id_cart?>
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


          $("#"+id).removeClass("disabled").prop("disabled",false);
          $("#btn-setujui").removeClass("disabled").prop("disabled",false);

          checkIsi();

        })
        .fail(function(e){
          console.log(e);
          $("#"+id).removeClass("disabled").prop("disabled",false);
          $("#btn-setujui").removeClass("disabled").prop("disabled",false);
          checkIsi();
        });
    }

    $(".disetujui").focusout(function(){
        var total   = $(this).val();
        var id_cart_detail_material = $(this).attr("id_cart_detail_material");
        var id      = $(this).attr("id");
        var tipe      = $(this).attr("id_material");

        updateDisetujui(id_cart_detail_material, total, id, tipe);
    });


    function checkIsi(){
        $(".disetujui").each(function(){
           var isi = $(this).val();
           console.log("isinya adalah "+isi);
           if(isi==""){
               $("#btn-setujui").addClass("disabled").prop("disabled",true);
               return false;
           }
        });
    }

    checkIsi();
</script>
