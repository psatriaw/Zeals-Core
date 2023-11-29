<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<?php
  $main_url = $config['main_url'];
?>
<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Pemenuhan Material "<?=$data->material_name?>" pada Permintaan "<?=$transaksi->cart_code?>"</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url($main_url) }}">Permintaan</a>
                    </li>
                    <li class="active">
                        <strong>Pilih Item dari Pembelian</strong>
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
                        <h5>Detail Bahan Baku</h5>
                    </div>
                    <div class="ibox-content">
                      @if ($errors->any())
                          <div class="alert alert-danger">
                              Ada kesalahan! mohon cek formulir.
                              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                          </div>
                      @endif
                      @include('backend.flash_message')
                      {!! Form::model($data,['url' => url($main_url.'/update/'.$data->id_material), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
                      <div class="row">
                        <div class="col-sm-6">

                              <div class="form-group {{ ($errors->has('mitra_name')?"has-error":"") }}"><label class="col-sm-4 control-label">Tanggal dibuat</label>
                                  <div class="col-xs-8">
                                      <input type="text" class="form-control disabled" disabled value="<?=date("d M Y H:i:s",$data->time_created)?>">
                                  </div>
                              </div>
                              <div class="form-group {{ ($errors->has('mitra_name')?"has-error":"") }}"><label class="col-sm-4 control-label">Terakhir diubah</label>
                                  <div class="col-xs-8">
                                      <input type="text" class="form-control disabled" disabled value="<?=date("d M Y H:i:s",$data->last_update)?>">
                                  </div>
                              </div>
                              <div class="form-group {{ ($errors->has('material_name')?"has-error":"") }}"><label class="col-sm-4 control-label">Nama Bahan Baku</label>
                                  <div class="col-xs-8">
                                      {!! Form::text('material_name', null, ['class' => 'form-control disabled', 'disabled' => 'disabled']) !!}
                                      {!! $errors->first('material_name', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                  </div>
                              </div>
                              <div class="form-group {{ ($errors->has('material_code')?"has-error":"") }}"><label class="col-sm-4 control-label">Kode Bahan Baku</label>
                                  <div class="col-xs-8">
                                      {!! Form::text('material_code', null, ['class' => 'form-control thetarget  disabled', 'disabled' => 'disabled','rows' => '3']) !!}
                                      {!! $errors->first('material_code', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                  </div>
                              </div>

                          </div>
                          <div class="col-sm-6">

                              <div class="form-group {{ ($errors->has('material_unit')?"has-error":"") }}"><label class="col-sm-4 control-label">Satuan</label>
                                  <div class="col-xs-8">
                                      {!! Form::email('material_unit', null, ['class' => 'form-control disabled', 'disabled' => 'disabled']) !!}
                                      {!! $errors->first('material_unit', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                  </div>
                              </div>
                              <div class="form-group {{ ($errors->has('quantity')?"has-error":"") }}"><label class="col-sm-4 control-label">Stock</label>
                                  <div class="col-xs-8">
                                      {!! Form::text('quantity', $stock, ['class' => 'form-control disabled', 'disabled' => 'disabled']) !!}
                                      {!! $errors->first('quantity', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                  </div>
                              </div>
                              <div class="form-group {{ ($errors->has('minimum_stock')?"has-error":"") }}"><label class="col-sm-4 control-label">Minimum Stok</label>
                                  <div class="col-xs-8">
                                      {!! Form::text('minimum_stock', null, ['class' => 'form-control disabled', 'disabled' => 'disabled', 'id' => 'address', 'rows' => 2]) !!}
                                      {!! $errors->first('minimum_stock', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                  </div>
                              </div>
                              <div class="form-group {{ ($errors->has('status')?"has-error":"") }}"><label class="col-sm-4 control-label">Status</label>
                                  <div class="col-xs-8">
                                    {!! Form::select('status', ['active' => 'Aktif', 'inactive' => 'Tidak Aktif'], null, ['class' => 'form-control disabled', 'disabled' => 'disabled']) !!}
                                    {!! $errors->first('status', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                  </div>
                              </div>

                          </div>
                          <div class="col-sm-12">
                            <div class="form-group">
                                <div class="col-sm-offset-2">
                                  <div class="col-xs-8">
                                    <a class="btn btn-white" href="{{ url('master/logistik/permintaan/manage/'.$transaksi->id_transaction) }}">
                                        <i class="fa fa-angle-left"></i> kembali
                                    </a>
                                  </div>
                                </div>
                            </div>
                          </div>
                        </div>

                      {!! Form::close() !!}
                    </div>
                </div>
                <div class="row">
                  {!! Form::model($data,['url' => url('master/logistik/permintaan/manage/'.$transaksi->id_transaction.'/ambil-item/'.$data->id_material.'/picking/'.$item_purchase->id_purchase_detail), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
                  <div class="col-sm-offset-3 col-sm-6 col-xs-12">
                    <div class="alert alert-info text-center">
                      <?php if($need>0){ ?>
                        <div class="alert alert-info text-center">
                          <h3>Dibutuhkan: <?=number_format(@$need,2,",",".")?> <?=$data->material_unit?></h3>
                        </div>
                      <?php }else{ ?>
                        <div class="alert alert-success text-center">
                          <h3>Terpenuhi</h3>
                        </div>
                      <?php }?>
                    </div>

                    <div class="panel panel-default">
                      <div class="panel-heading">Pilih Penggunaan Stock</div>
                      <div class="panel-body">
                        <div id="" class="label-area">0</div><div class="label-meter" id="keperluan"></div><div id="" class="label-area"><?=$stock?></div>
                        <div class="text-center" style="margin-top:25px;">
                            <input type="text" name="movement_qty" id="picked" class="form-control" style="width: 100px;display: inline;border: 1px solid #aaa;height: 30px;border-radius: 3px;font-weight: 700;color: #62291e;margin-top: -1px;margin-right:10px;" value="0">
                            dari <div id="" class="label-area" style="width:200px;"><?=number_format($stock,0,",",".")?> <?=$data->material_unit?></div>
                        </div>
                      </div>
                      <div class="panel-footer text-right">
                          <button type="submit" name="submit" class="btn btn-primary">Gunakan <i class='fa fa-angle-right'></i></button>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-4">
                  </div>
                  {!! Form::close() !!}
                </div>

                <div class="ibox-title">
                    <h5>Penggunaan Item</h5>
                </div>
                <div class="ibox-content">
                  @include('backend.flash_message')
                  <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover dataTables-example" id="datatable">
                      <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kode Order</th>
                            <th>Waktu Pencatatan</th>
                            <th>Author</th>
                            <th>Total</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                          $total_quantity = 0;
                          $counter = 0;
                          $total   = 0;
                          if(@$usages){
                            foreach ($usages as $key => $value) {
                              $counter++;
                              ?>
                              <tr>
                                <td><?=$counter?></td>
                                <td><?=$value->cart_code?></td>
                                <td><?=date("Y-m-d H:i:s",$value->time_created)?></td>
                                <td class="text-right"><?=$value->first_name?></td>
                                <td class="text-right"><?=number_format($value->movement_qty,2,",",".")?></td>
                                <?php
                                  $total_quantity = $total_quantity + $value->movement_qty;
                                ?>
                              </tr>
                              <?php
                            }
                            ?>
                              <tr>
                                <td colspan="4"></td>
                                <td class="text-right"><strong><?=number_format(@$total_quantity,2,",",".")?></strong></td>
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


              </div>
            </div>
        </div>
    @include('backend.footer')
  </div>
</div>
<script>
$( "#keperluan" ).slider({
  value:0,
  min: 0,
  max: <?=$stock?>,
  step: 1,
  slide: function( event, ui ) {
    $( "#picked" ).val(ui.value );
  }
});

$(document).ready(function(){
  $( "#picked" ).keyup(function(e){
    var values = $("#picked").val();
    var max    = <?=$stock?>;

    if(values>=max){
      $("#picked").val(max);
      $( "#keperluan" ).slider('value',max);
    }else{
      $( "#keperluan" ).slider('value',values);
    }
  });
});
</script>
