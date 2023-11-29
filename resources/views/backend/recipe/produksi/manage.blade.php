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
                <h2>Produksi Stock Resep</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url($main_url) }}">Produksi Stock</a>
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
                        <h5>Detail Kegiatan Produksi</h5>
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
                              <div class="form-group {{ ($errors->has('purchase_code')?"has-error":"") }}"><label class="col-sm-4 control-label">Kode Produksi</label>
                                  <div class="col-sm-8 col-xs-12">
                                      <input type="text" class="form-control disabled" readonly value="<?=$data->production_code?>" name="purchase_code">
                                  </div>
                              </div>
                              <div class="form-group {{ ($errors->has('purchase_code')?"has-error":"") }}"><label class="col-sm-4 control-label">Waktu Kegiatan</label>
                                  <div class="col-sm-8 col-xs-12">
                                      <input type="text" class="form-control disabled" readonly value="<?=$data->production_date?>" name="purchase_code">
                                  </div>
                              </div>
                              <div class="form-group {{ ($errors->has('purchase_title')?"has-error":"") }}"><label class="col-sm-4 control-label">Total Item</label>
                                  <div class="col-sm-8 col-xs-12">
                                      <input type="text" class="form-control disabled" readonly value="<?=$data->total_item?>" name="purchase_code">
                                  </div>
                              </div>
                            </div>
                            <div class="col-sm-6">
                              <div class="alert alert-info">Jika pada "Item Alokasi Bahan Baku" masih kosong, kemungkinan logistik/gudang perlu mendistribusikan/mengalokasikan bahan baku untuk permintaan produksi ini</div>
                            </div>
                        </div>
                      {!! Form::close() !!}
                    </div>
                    <br><br>
                    <?php if(@$bahan!=""){ ?>
                    <div class="ibox-title">
                        <h5>Item Alokasi Bahan Baku Berdasarkan Permintaan Resep #<?=$data->cart_code?></h5>
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
                                <th>Source Pembelian</th>
                                <th>Satuan</th>
                                <th>Alokasi</th>
                                <th>Penggunaan</th>
                                <th>Sisa</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                              $total_quantity = 0;
                              $counter = 0;
                              $total   = 0;
                              if(@$bahan){
                                foreach ($bahan as $key => $value) {
                                  $counter++;
                                  ?>
                                  <tr class="<?=($value->usage=="0")?"success":""?>">
                                    <td><?=$counter?></td>
                                    <td><?=$value->material_code?></td>
                                    <td><?=$value->material_name?></td>
                                    <td><?=$value->purchase_code?></td>
                                    <td><?=$value->material_unit?></td>
                                    <td class="text-right"><?=number_format(@$value->movement_qty,2,",",".")?></td>
                                    <td><?=number_format(@$value->usage,2,",",".")?></td>
                                    <td><?=number_format(@$value->movement_qty-$value->usage,2,",",".")?></td>
                                  </tr>
                                  <?php
                                }
                              }else{
                                ?>
                                <tr>
                                  <td colspan="8">
                                    <div class="alert alert-danger">
                                        Jika pada "Item Alokasi Bahan Baku" masih kosong, kemungkinan logistik/gudang perlu mendistribusikan/mengalokasikan bahan baku untuk permintaan produksi ini<br>
                                        Solusi: Mohon memberitahukan kepada pihak logistik/gudang untuk memenuhi permintaan
                                    </div>
                                  </td>
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
                    <?php } ?>
                    <div class="ibox-title">
                        <h5>Item Produksi</h5>
                          <!--
                          <?php if($previlege->isAllow($login->id_user,$login->id_department,$methods['create']) && $data->status=="active"){?>
                          <div class="ibox-tools">
                              <a class="btn btn-primary btn-sm" href="{{ url($config['main_url'].'/'.$data->id_production.'/additem') }}">
                                  <i class="fa fa-plus"></i> tambah item produksi
                              </a>

                              <?php if($previlege->isAllow($login->id_user,$login->id_department,$methods['create']) && $data->status=="active"){?>
                                  <a class="btn btn-info text-white" href="{{ url($config['main_url'].'/'.$data->id_production.'/join') }}">
                                      <i class="fa fa-link"></i> hubungkan ke permintaan
                                  </a>
                              <?php } ?>
                          </div>
                          <?php } ?>
                        -->
                    </div>
                    <div class="ibox-content">
                      @include('backend.flash_message')
                      <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example" id="datatable">
                          <thead>
                            <tr>
                                <th>No.</th>
                                <th>Kode Rumus</th>
                                <th>Nama Rumus</th>
                                <th>Waktu Penambahan</th>
                                <th>Quantity</th>
                                <th>Aksi</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                              $telaksana = 0;
                              $counter = 0;
                              $total   = 0;
                              $totalqty = 0;
                              if($items){
                                foreach ($items as $key => $value) {
                                  $counter++;
                                  ?>
                                  <tr>
                                    <td><?=$counter?></td>
                                    <td><?=$value->naikan_rumus_code?></td>
                                    <td><?=$value->naikan_rumus_name?></td>
                                    <td><?=date("Y-m-d H:i:s",$value->time_created)?></td>
                                    <td class="text-right"><?=number_format($value->total_implementasi,0,",",".")?></td>
                                    <?php
                                      $totalqty = $totalqty + $value->total_implementasi;
                                      $subtot = $value->item_price*$value->total_implementasi;
                                      $total  = $total+$subtot;
                                    ?>

                                    <td style="max-width:450px;">
                                      <?php if($value->type!="joined"){ ?>
                                        <?php if($previlege->isAllow($login->id_user,$login->id_department,$methods['remove']) && $data->status=="active"){?>
                                            <a parent-id="{{ $data->id_production }}" data-id="{{ $value->id_implementasi }}" data-url="{{ url($main_url.'/remove/item/') }}" class="btn btn-danger btn-outline dim btn-xs confirm"><i class="fa fa-trash"></i> hapus</a>
                                        <?php }else{ ?>
                                            <a parent-id="{{ $data->id_production }}" data-id="{{ $value->id_implementasi }}" data-url="{{ url($main_url.'/remove/item/') }}" class="btn btn-default btn-outline dim btn-xs confirm disabled" disabled="true"><i class="fa fa-trash"></i> hapus</a>
                                        <?php }?>
                                      <?php }?>

                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$methods['remove']) && $data->status=="active" && $value->status_produksi=="prepared"){?>
                                        <a parent-id="{{ $data->id_production }}" data-id="{{ $value->id_implementasi }}" data-url="{{ url($main_url.'/doprod/'.(($value->id_source!='')?$value->id_source:0)) }}" class="btn btn-info dim btn-xs confirm">buat stock <i class="fa fa-angle-right"></i></a>
                                      <?php }else{ ?>
                                        <?php
                                          $info = $value->stocks;
                                          if($info!=""){
                                            //print $info;
                                            $info = explode("<>",$info);

                                            foreach ($info as $keys => $values){
                                            print_r($values);
                                            //exit();
                                              $items = explode("_",$values);
                                              //print "<div class='item-brownies'>";
                                              //print "<img src='".url("templates/admin/img/brownies_active.svg")."' class='ico-brownies'>";
                                              //print "<span class='label label-default mg-bt-20'> ".$items[0]." HPP Rp. ".number_format($items[2],0,",",".")."</span> ";
                                              //print "</div>";
                                            }

                                            $telaksana++;
                                          }
                                        ?>
                                      <?php }?>
                                    </td>

                                  </tr>
                                  <?php
                                }
                                ?>

                                  <tr>
                                    <td colspan="4"></td>

                                    <td class="text-right"><strong><?=number_format($totalqty,0,",",".")?> items</strong></td>
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


                    <div class="row">
                      <div class="form-group top15">
                          <div class="col-sm-6">
                              <a class="btn btn-white" href="{{ url($main_url) }}">
                                  <i class="fa fa-angle-left"></i> kembali
                              </a>
                          </div>
                          <div class="col-sm-6 text-right">
                            <?php if($telaksana==$counter){ ?>
                              <button class="btn btn-info confirm" type="button" id="continue-checkout-btn"  data-id="{{ $data->id_production }}" data-url="{{ url($config['main_url'].'/doproduction') }}" >Selesai Produksi <i class="fa fa-angle-right"></i> </button>
                            <?php }else{ ?>
                              <button class="btn btn-info disabled" disable=true type="button" id="continue-checkout-btn"  data-id="{{ $data->id_production }}" data-url="{{ url($config['main_url'].'/doproduction') }}" >Selesai Produksi <i class="fa fa-angle-right"></i> </button>
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
