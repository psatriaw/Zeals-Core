<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<?php
  $main_url = $config['main_url'];
?>
<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Produksi</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('admin/master') }}">Master</a>
                    </li>
                    <li>
                        <a href="{{ url($main_url) }}">Produksi</a>
                    </li>
                    <li class="active">
                        <strong>Detail Hasil Produksi</strong>
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
                          <h5>Detail Hasil Produksi</h5>
                      </div>
                      <div class="ibox-content">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                Ada kesalahan! mohon cek formulir.
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            </div>
                        @endif
                        @include('backend.flash_message')
                        {!! Form::model($data,['url' => url($main_url.'/update/'.$data->id), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
                          <div class="form-group {{ ($errors->has('first_name')?"has-error":"") }}"><label class="col-sm-2 control-label">Waktu Terdaftar</label>
                              <div class="col-sm-4 col-xs-12">
                                  <input type="text" class="form-control disabled" disabled readonly value="<?=date("Y-m-d H:i:s",$data->time_created)?>">
                              </div>
                          </div>
                          <div class="hr-line-dashed"></div>
                          <div class="form-group {{ ($errors->has('first_name')?"has-error":"") }}"><label class="col-sm-2 control-label">Pembaruan Terakhir</label>
                              <div class="col-sm-4 col-xs-12">
                                  <input type="text" class="form-control disabled" disabled readonly value="<?=date("Y-m-d H:i:s",$data->last_update)?>">
                              </div>
                          </div>
                          <div class="hr-line-dashed"></div>
                          <div class="form-group {{ ($errors->has('production_code')?"has-error":"") }}"><label class="col-sm-2 control-label">Kode Produksi</label>
                              <div class="col-sm-3 col-xs-12">
                                {!! Form::text('production_code', null, ['class' => 'form-control','readonly' => 'readonly']) !!}
                                {!! $errors->first('production_code', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                              </div>
                          </div>
                          <div class="hr-line-dashed"></div>
                          <div class="form-group {{ ($errors->has('admin')?"has-error":"") }}"><label class="col-sm-2 control-label">Admin Record</label>
                              <div class="col-sm-3 col-xs-12">
                                  <input type="text" name="admin" value="<?=$login->first_name?> <?=$login->last_name?>" class="form-control" readonly>
                                  {!! $errors->first('admin', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                              </div>
                          </div>
                          <div class="hr-line-dashed"></div>
                          <div class="form-group {{ ($errors->has('id_product')?"has-error":"") }}"><label class="col-sm-2 control-label">Produk</label>
                              <div class="col-sm-3 col-xs-12">
                                  {!! Form::select('id_product', $products, null, ['class' => 'form-control','readonly' => 'readonly','disabled' => 'disabled']) !!}
                                  {!! $errors->first('id_product', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                              </div>
                          </div>
                          <div class="hr-line-dashed"></div>
                          <div class="form-group {{ ($errors->has('production_quantity')?"has-error":"") }}"><label class="col-sm-2 control-label">Quantity</label>
                              <div class="col-sm-3 col-xs-12">
                                {!! Form::text('production_quantity', null, ['class' => 'form-control','readonly' => 'readonly']) !!}
                                {!! $errors->first('production_quantity', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                              </div>
                          </div>
                          <div class="hr-line-dashed"></div>
                          <div class="form-group">
                              <div class="col-sm-4 col-sm-offset-2">
                                  <a class="btn btn-white" href="{{ url($main_url) }}">
                                      <i class="fa fa-angle-left"></i> kembali
                                  </a>
                              </div>
                          </div>
                        {!! Form::close() !!}
                      </div>
                  </div>

                  <div class="ibox float-e-margins">
                      <div class="ibox-title">
                          <h5>Detail Penggunaan Bahan Produksi</h5>
                      </div>
                      <div class="ibox-content">
                        <table class="table table-striped table-bordered table-hover dataTables-example" id="datatable">
                          <thead>
                            <tr>
                                <th>No.</th>
                                <th>Kode Material</th>
                                <th>Nama Bahan</th>
                                <th>Quantity</th>
                                <th>Satuan</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                              if($materials){
                                $counter = 0;
                                foreach ($materials as $key => $value) {
                                  $counter++;
                                  ?>
                                  <tr>
                                    <td><?=$counter?></td>
                                    <td><?=$value->material_code?></td>
                                    <td><?=$value->material_name?></td>
                                    <td><?=$value->quantity?></td>
                                    <td><?=$value->material_unit?></td>
                                  </tr>
                                  <?php
                                }
                              }
                            ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <div class="ibox float-e-margins">
                      <div class="ibox-title">
                          <h5>Penggunaan Item Produksi (Packing)</h5>
                      </div>
                      <div class="ibox-content">
                        <table class="table table-striped table-bordered table-hover dataTables-example" id="datatable">
                          <thead>
                            <tr>
                                <th>No.</th>
                                <th>Kode Transaksi</th>
                                <th>Kode Packing</th>
                                <th>Tgl Packing</th>
                                <th>Quantity</th>
                                <th>Admin Packing</th>
                                <th>Packing Status</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                              if($usages){
                                $counter = 0;
                                foreach ($usages as $key => $value) {
                                  $counter++;

                                  ?>
                                  <tr>
                                    <td><?=$counter?></td>
                                    <td><?=$value->cart_code?></td>
                                    <td><?=$value->packing_code?></td>
                                    <td><?=$value->tgl_packing?></td>
                                    <td><?=$value->quantity?></td>
                                    <td><?=$value->admin_name?></td>
                                    <td><?=$value->packing_status?> <?=($value->courier_name!="")?"pada <strong>".date("d M Y H:i",$value->packing_time)."</strong> oleh <strong>".$value->courier_name."</strong>":""?></td>
                                  </tr>
                                  <?php
                                }
                              }
                            ?>
                          </tbody>
                        </table>
                      </div>
                  </div>
                </div>
            </div>
        </div>
    @include('backend.footer')
  </div>
</div>
<script>
  $(document).ready(function() {
    $('.thetarget, .thesender').select2({
      ajax: {
        url: '{{ url("get-list-product") }}',
        dataType: 'json',
        data: function (params) {
          var query = {
            search: params.term,
            type: 'public'
          }
          return query;
        }
      }
    });
  });
</script>
