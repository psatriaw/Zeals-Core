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
                                <th>Stock Gudang</th>
                                <th>Satuan</th>
                                <th>Aksi</th>
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
                              
                              //print "<pre>";
                              //print_r($data);
                              //exit();
                                
                              if($data){
                                foreach ($data as $key => $value) {
                                  $counter++;
                                
                                  $value = (object)$value;
                                
                                  if($value->type=='non-package'){
                                      $countermaterial++;
                                      ?>
                                      <tr>
                                        <td><?=$counter?></td>
                                        <td><?=$value->material_code?></td>
                                        <td><?=$value->material_name?></td>
                                        <!--<td  style="width:150px;" class="text-right"><?=number_format($value->initial_quantity,0,",",".")?></td>-->
                                        <td  style="width:150px;" class="text-right"><?=number_format($value->material_quantity,0,",",".")?></td>
                                        <td  style="width:150px;" class="text-right"><?=number_format($value->disetujui_quantity,0,",",".")?></td>
                                        <td  style="width:150px;" class="text-right"><?=number_format(@$value->stock,0,",",".")?></td>
                                        <td  style="width:150px;" class="text-right"><?=$value->material_unit?></td>
                                        
                                        <td>
                                            <?php if($previlege->isAllow($login->id_user,$login->id_department,$config['manage'])){?>
                                                <?php if($value->id_material!="custom"){ ?>
                                                    <a href="{{ url($main_url.'/manage_carts_perbahan/'.$date.'/'.$value->id_material) }}" class="btn btn-white btn-outline dim btn-xs"><i class="fa fa-cogs"></i> kelola</a>
                                                <?php }else{ ?>
                                                    <a href="{{ url($main_url.'/manage_carts_perbahan/'.$date.'/custom/'.$value->id_cart_detail_material) }}" class="btn btn-white btn-outline dim btn-xs"><i class="fa fa-cogs"></i> kelola</a>
                                                <?php }?>
                                          <?php }?>
                                        </td>
                                      </tr>
                                      <?php
                                  }
                                }

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
                              <a class="btn btn-white" href="{{ url($main_url.'/manage_carts/'.$date) }}">
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

    function updateDisetujui(id_cart_detail_material, total, id){
        console.log(id_cart_detail_material+" "+total+" "+id);

        $("#"+id).addClass("disabled").prop("disabled",true);
        $("#btn-setujui").addClass("disabled").prop("disabled",true);
        $.ajax({
          type: "POST",
          url: "<?=url("update-disetujui")?>",
          data: {
            id_cart_detail_material: id_cart_detail_material,
            total: total
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
        updateDisetujui(id_cart_detail_material, total, id);
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
