<?php
  $main_url    = $config['main_url'];
  $methods     = $config;
  $production_cost = 0;
  $production_permintaan = 0;

  $statuses = array(
    "pending"       => "<span class='text-danger'><i class='fa fa-exclamation'></i> Pending</span>",
    "disetujui"     => "<span class='text-info'><i class='fa fa-hourglass'></i> Proses Pengiriman</span>",
    "selesai"       => "<span class='text-success'><i class='fa fa-check'></i> Selesai</span>",
  );
?>
<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Kelola Permintaan Tanggal <?=$date?></h2>
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
                        <h5>Detail Bahan Baku (Jenis Paket)</h5>
                    </div>
                    <div class="ibox-content bottom30">
                      {!! Form::model($data,['url' => url($main_url.'/store'), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
                        <div class="row">
                            <div class="col-sm-6">
                              <div class="form-group {{ ($errors->has('purchase_code')?"has-error":"") }}"><label class="col-sm-4 control-label">Kode Bahan</label>
                                  <div class="col-sm-8 col-xs-12">
                                      <input type="text" class="form-control disabled" readonly value="<?=@$material->package_name?>" name="purchase_code">
                                  </div>
                              </div>
                              <div class="form-group {{ ($errors->has('purchase_title')?"has-error":"") }}"><label class="col-sm-4 control-label">Nama Bahan</label>
                                  <div class="col-sm-8 col-xs-12">
                                      <input type="text" class="form-control disabled" readonly value="<?=$material->package_name?>" name="purchase_code">
                                  </div>
                              </div>
                              <div class="form-group {{ ($errors->has('purchase_title')?"has-error":"") }}"><label class="col-sm-4 control-label">Stock Gudang (DC)</label>
                                  <div class="col-sm-8 col-xs-12">
                                      <input type="text" class="form-control disabled" readonly value="<?=$material->stock?>" name="purchase_code">
                                  </div>
                              </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group {{ ($errors->has('purchase_title')?"has-error":"") }}"><label class="col-sm-4 control-label">Susunan Paket</label>
                                  <div class="col-sm-8 col-xs-12">
                                      <?php 
                                        if(@$packages){
                                            foreach($packages as $key=>$val){
                                                ?><div class="label label-info" style='margin-bottom: 3px;display: inline-block;padding: 7px;'><?=@$val->material_name?></div> <?php
                                            }
                                        }
                                      ?>
                                  </div>
                              </div>
                            </div>
                        </div>
                      {!! Form::close() !!}
                    </div>


                    <div class="ibox-title">
                        <h5>Item Permintaan Bahan Baku Setiap Outlet</h5>
                    </div>
                    <div class="ibox-content">
                      @include('backend.flash_message')
                      <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example" id="datatable">
                          <thead>
                            <tr>
                                <th>No.</th>
                                <th>Kode Permintaan</th>
                                <th>Outlet</th>
                                <th>PIC</th>
                                <th>Waktu Permintaan</th>
                                <th>Disarankan</th>
                                <th>Diminta</th>
                                <th>Disetujui</th>
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
                              $counterdisarankan = 0;
                              $counterdiminta   = 0;
                              $counterdisetujui = 0;

                              if($data){
                                foreach ($data as $key => $value) {
                                  $counter++;
                                  $subtotal = $value->quantity * ($value->price_outlet - $value->price_outlet_discount);
                                  $total_grand = $total_grand + $subtotal;

                                  $countermaterial++;
                                  //print_r($value->toArray());
                                  ?>
                                  <tr>
                                    <td><?=$counter?></td>
                                    <td><?=$value->cart_code?> [<?=$value->status_cart?>] </td>
                                    <td><?=$value->mitra_name?></td>
                                    <td><?=$value->author?></td>
                                    <td><?=date("Y-m-d H:i:s",$value->waktu)?></td>
                                    <td  style="width:150px;" class="text-right"><?=number_format($value->quantity,0,",",".")?></td>
                                    <td  style="width:150px;" class="text-right"><?=number_format($value->quantity,0,",",".")?></td>

                                    <td  style="width:150px;" class="text-right">
                                        <input type="text" <?=($value->status_cart=="pending")?"":"readonly"?> class="form-control disetujui" autocomplete="off" id_material="custom" id_cart_detail_material="<?=$value->id_product?>" id_cart="<?=$value->id_cart?>" id="disetujui_quantity_<?=$value->id_cart?>" value="<?=$value->quantity?>">
                                    </td>
                                    <td><?=$value->id_outlet?> <?=number_format($checkstock->getStockOfMaterialOnOutlet($value->id_outlet,$value->id_material),0)?></td>
                                    <?php
                                      $total_quantity = $total_quantity + $value->quantity;
                                      $subtot = $value->item_price*$value->material_quantity;
                                      $total  = $total+$subtot;

                                      $counterdisarankan = $counterdisarankan + $value->initial_quantity;
                                      $counterdiminta = $counterdiminta + $value->material_quantity;
                                    ?>
                                    <td><?=$value->package_unit?></td>
                                  </tr>
                                  <?php
                                }

                                ?>
                                    <tr>
                                    <td colspan="5"> Total </td>
                                    <td id="total_disarankan" class='text-right'> <?=number_format($counterdisarankan,0)?> </td>
                                    <td id="total_diminta" class='text-right'> <?=number_format($counterdiminta,0)?> </td>
                                    <td id="total_disetujui"> 0 </td>

                                    <?php
                                      $total_quantity = $total_quantity + @$value->quantity;
                                      $subtot = @$value->item_price * @$value->material_quantity;
                                      $total  = $total+$subtot;
                                    ?>
                                  </tr>
                                <?php
                                $production_permintaan = $total_quantity;
                                $production_pemenuhan = $total_pemenuhan;
                              }else{
                                ?>
                                <tr>
                                  <td colspan="10">Tidak ada data</td>
                                </tr>
                                <?php
                              }
                              $production_cost = $production_cost + $total_biaya;
                            ?>
                          </tbody>
                          </tfoot>
                        </table>
                      </div>
                    </div>


                    <div class="row">
                      <div class="form-group top15">
                          <div class="col-sm-6">
                              <a class="btn btn-white" href="{{ url($main_url.'/manage_material/'.$date) }}">
                                  <i class="fa fa-angle-left"></i> kembali
                              </a>
                          </div>
                          <div class="col-sm-6 text-right">



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

    function updateDisetujui(id_cart_detail_material, total, id, tipe, id_cart){
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
            id_cart: id_cart
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
        var id_cart      = $(this).attr("id_cart");

        updateDisetujui(id_cart_detail_material, total, id, tipe, id_cart);
    });


    function checkIsi(){
        var total_disetujui = 0;
        $(".disetujui").each(function(){
           var isi = $(this).val();
           console.log("isinya adalah "+isi);
           if(isi!=""){
            total_disetujui = total_disetujui + parseInt(isi);
           }
        });

        $("#total_disetujui").html(total_disetujui);
    }

    checkIsi();
</script>
