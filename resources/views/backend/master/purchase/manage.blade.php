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
                <h2>Update Pembelian</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url($main_url) }}">Pembelian</a>
                    </li>
                    <li class="active">
                        <strong>Update Pembelian</strong>
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
                        <h5>Item Pembelian</h5>
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
                                <th>Waktu Penambahan</th>
                                <th>Update Harga</th>
                                <th>Quantity</th>
                                <th>Sub Total</th>
                                <th>Aksi</th>
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
                                  {!! Form::model($data,['url' => url($main_url.'/update/'.$data->id_purchase.'/'.$value->id_purchase_detail), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
                                  <tr>
                                    <td><?=$counter?></td>
                                    <td><?=$value->material_code?></td>
                                    <td><?=$value->material_name?></td>
                                    <td><?=date("Y-m-d H:i:s",$value->time_created)?></td>
                                    <td class="text-right">
                                      <input type="hidden" class="form-control" name="id_purchase_detail" value="<?=$value->id_purchase_detail?>">
                                      <input type="text" class="form-control update-harga" id="update-<?=$value->id_purchase_detail?>" data-id-purchase-detail="<?=$value->id_purchase_detail?>" name="harga_purchase_detail" value="<?=$value->item_price?>">
                                    </td>
                                    <td class="text-right"><?=number_format($value->item_quantity,2,",",".")?> <?=$value->material_unit?></td>
                                    <?php
                                      $subtot = $value->item_price*$value->item_quantity;
                                      $total  = $total+$subtot;
                                    ?>
                                    <td class="text-right">Rp. <?=number_format($subtot,2,",",".")?></td>
                                    <td>
                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$methods['approve'])){?>
                                          <button type="submit" class="btn btn-primary btn-xs"><i class="fa fa-save"></i> update</button>
                                      <?php }?>
                                    </td>
                                  </tr>
                                  {!! Form::close() !!}
                                  <?php
                                }
                                ?>
                                  <tr>
                                    <td colspan="6"></td>
                                    <td class="text-right"><strong>Rp. <?=number_format($total,2,",",".")?></strong></td>
                                    <td></td>
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
                          
                          <thead>
                            <tr>
                                <th>No.</th>
                                <th>Kode Paket</th>
                                <th>Nama Paket</th>
                                <th>Waktu Penambahan</th>
                                <th>Update Harga</th>
                                <th>Quantity</th>
                                <th>Sub Total</th>
                                <th>Aksi</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                              $counter = 0;
                              $total   = 0;
                              if($items_package){
                                foreach ($items_package as $key => $value) {
                                  $counter++;
                                  ?>
                                  {!! Form::model($data,['url' => url($main_url.'/update/'.$data->id_purchase.'/'.$value->id_purchase_detail), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
                                  <tr>
                                    <td><?=$counter?></td>
                                    <td><?=$value->package_code?></td>
                                    <td><?=$value->package_name?></td>
                                    <td><?=date("Y-m-d H:i:s",$value->time_created)?></td>
                                    <td class="text-right">
                                      <input type="hidden" class="form-control" name="id_purchase_detail" value="<?=$value->id_purchase_detail?>">
                                      <input type="text" class="form-control update-harga" id="update-<?=$value->id_purchase_detail?>" data-id-purchase-detail="<?=$value->id_purchase_detail?>" name="harga_purchase_detail" value="<?=$value->item_price?>">
                                    </td>
                                    <td class="text-right"><?=number_format($value->item_quantity,2,",",".")?> <?=$value->package_unit?></td>
                                    <?php
                                      $subtot = $value->item_price*$value->item_quantity;
                                      $total  = $total+$subtot;
                                    ?>
                                    <td class="text-right">Rp. <?=number_format($subtot,2,",",".")?></td>
                                    <td>
                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$methods['approve'])){?>
                                          <button type="submit" class="btn btn-primary btn-xs"><i class="fa fa-save"></i> update</button>
                                      <?php }?>
                                    </td>
                                  </tr>
                                  {!! Form::close() !!}
                                  <?php
                                }
                                ?>
                                  <tr>
                                    <td colspan="6"></td>
                                    <td class="text-right"><strong>Rp. <?=number_format($total,2,",",".")?></strong></td>
                                    <td></td>
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
                        </table>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group top15">
                          <div class="col-sm-6">
                              <a class="btn btn-white" href="{{ url($main_url) }}">
                                  <i class="fa fa-angle-left"></i> kembali
                              </a>
                          </div>
                          <div class="col-sm-6 text-right">
                            <button class="btn btn-danger confirm" data-id="{{ $data->id_purchase }}" data-url="{{ url($config['main_url'].'/remove') }}" type="submit">Batalkan Pembelian</button>
                            <a id="btn-terima-pembelian" class="btn btn-primary confirm" data-id="{{ $data->id_purchase }}" data-url="{{ url($main_url.'/terima/') }}">Terima Pembelian</a>
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
    function updateDisetujui(id_purchase_detail, harga, id){
        $("#btn-terima-pembelian").addClass("disabled").prop("disabled",true);
        $("#"+id).addClass("disabled").prop("disabled",true);
        $.ajax({
          type: "POST",
          url: "<?=url("update-purchase-pembelian")?>",
          data: {
            id_purchase_detail: id_purchase_detail,
            harga: harga
          }
        })
        .done(function(result){
          var ret = result;
          if(result.status==200){
            console.log("berhasil");
            console.log(result);
            
            location.reload();
          }else{
            console.log("gagal");
            console.log(result);
          }


          $("#btn-terima-pembelian").removeClass("disabled").prop("disabled",false);
          $("#"+id).removeClass("disabled").prop("disabled",false);

        })
        .fail(function(e){
          $("#btn-terima-pembelian").removeClass("disabled").prop("disabled",false);
          $("#"+id).removeClass("disabled").prop("disabled",false);
        });
    }
    
    $(".update-harga").focusout(function(){
        var harga   = $(this).val();
        var id_purchase_detail = $(this).attr("data-id-purchase-detail");
        var id      = $(this).attr("id");
        updateDisetujui(id_purchase_detail, harga, id);
    });
</script>