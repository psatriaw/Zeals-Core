<?php
  $main_url      = $config['main_url'];
  $methods       = $config;
?>
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('style2/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('style2/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('style2/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('style2/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">

<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Tester</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li class="active">
                        <strong>Tester</strong>
                    </li>
                </ol>
            </div>
            <div class="col-lg-2">
 
            </div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">

        <div class="ibox">
            <div class="ibox">
                <div class="ibox-title">
                    <i class="fas fa-cogs mr-1"></i>
                    Tambah Tester <p>(Peringatan Data yang sudah dibuat tidak bisa dihapus)</p>
                </div>
                <div class="ibox-content">
                {!! Form::open(['url' => url($main_url.'/store'), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}

                <div class="form-group {{ ($errors->has('tanggal')?"has-error":"") }}"><label class="col-sm-2 control-label">Tanggal</label>
                    <div class="col-sm-6 col-xs-12">
                        <input type="date" name="tanggal" class="form-control" value="{{ $today }}" autofocus required>
                    </div>
                </div>
                <div class="form-group"><label class="col-sm-2 control-label">Produk</label>
                    <div class="col-sm-6 col-xs-12"> 
                    <select name="nama_barang" id="namaBrg" class="form-control"></select>
                    </div>
                </div>
                <div class="form-group {{ ($errors->has('jumlah')?"has-error":"") }}"><label class="col-sm-2 control-label">Jumlah (Rp)</label>
                    <div class="col-sm-6 col-xs-12">        
                        <input type="number" name="jumlah" class="form-control" autofocus required>
                    </div>
                </div>
                    <!-- <button type="submit" class="btn btn-success">Simpan</button>   -->
                    <div class="hr-line-dashed"></div> 
                <div class="form-group">
                    <div class="col text-right">
                        <button class="btn btn-primary text-right" type="submit">Simpan</button>
                    </div>
                </div>
                {!! Form::close() !!}
                </div>
            </div>
        </div>

            <div class="row">
                <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Tester</h5>
                        <!-- <?php if($previlege->isAllow($login->id_user,$login->id_department,$methods['create'])){?>
                        <div class="ibox-tools">
                            <button type="button" class="float-right btn btn-primary" data-toggle="modal" data-target="#modal-default">
                              Tambah Tester
                            </button>
                        </div>
                        <?php } ?> -->
                    </div>
            <!-- /.card-header -->
            <div class="ibox-content">
            @include('backend.flash_message')
            <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example" id="datatable">
                          <thead>
                            <tr>
                                <th>Tanggal </th>
                                <th>Produk </th>
                                <th>Jumlah (Rp) </th>
                                <th>Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($tester as $k)
                        <tr>
                            <td>{{ $k->tanggal }}</td>
                            <td>{{ $k->store_master_barang_id }}</td>
                            <td>Rp. {{ number_format($k->jumlah) }}</td>
                            <td>
                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$methods['edit'])){?>
                                          <a href="{{ route('tester.edit',$k->id) }}" class="btn btn-primary dim btn-xs"><i class="fa fa-paste"></i> ubah</a>
                                      <?php }?>

                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$methods['remove'])){?>
                                          <a data-id="{{ $k->id }}" data-url="{{ route('tester.destroy',$k->id) }}" class="btn btn-danger btn-outline dim btn-xs confirm"><i class="fa fa-trash"></i> hapus</a>
                                      <?php }?>
                                    </td>
                        </tr>
                        @endforeach
                        </tbody>    
                </table>
                </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    @include('backend.do_confirm')
    @include('backend.footer')
    @include('backend.master.purchase.modal_material_add')
  </div>
</div> 

<!-- MODAL -->
    <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Tambah Tester</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
            {!! Form::open(['url' => url($main_url.'/store'), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}

            <div class="form-group {{ ($errors->has('tanggal')?"has-error":"") }}"><label class="col-sm-2 control-label">Tanggal</label>
                <div class="col-sm-6 col-xs-12">
                    <input type="date" name="tanggal" class="form-control" value="{{ $today }}" autofocus required>
                </div>
            </div>
            <div class="form-group"><label class="col-sm-2 control-label">Produk</label>
                <div class="col-sm-6 col-xs-12"> 
                <select name="nama_barang" id="namaBrg" class="form-control"></select>
                </div>
            </div>
            <div class="form-group {{ ($errors->has('jumlah')?"has-error":"") }}"><label class="col-sm-2 control-label">Jumlah</label>
                <div class="col-sm-6 col-xs-12">        
                    <input type="number" name="jumlah" class="form-control" autofocus required>
                </div>
            </div>
                <!-- <button type="submit" class="btn btn-success">Simpan</button>   -->
                <div class="hr-line-dashed"></div> 
              <div class="form-group">
                  <div class="col-sm-4 col-sm-offset-2">
                      <a class="btn btn-white" href="{{ url($main_url) }}">
                          <i class="fa fa-angle-left"></i> kembali
                      </a>
                  </div>
                  <div class="col-sm-6 text-right">
                    <button class="btn btn-white" type="reset">Reset</button>
                    <button class="btn btn-primary" type="submit">Simpan</button>
                  </div>
              </div>
              {!! Form::close() !!}
            </div>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
<!-- Select2 -->
<script src="{{ asset('style2/plugins/select2/js/select2.full.min.js') }}"></script>
<!-- DataTables -->
<script src="{{ asset('style2/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('style2/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('style2/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('style2/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script> 
 <script>
 $(document).ready(function() {

 $('#namaBrg').select2({
        ajax : {
            url : '{{ route('ajax_get_primer') }}',
            type : 'GET',
            dataType : 'JSON',
            data : function(params){
                return{
                    data : params.term
                };
            },
            processResults : function(data){
                var results = [];

                $.each(data, function(index, item){
                    results.push({
                        id : item.id,
                        text : item.nama
                    })
                }); 
                return {
                    results : results
                };
            }
        }
    }).on('select2:select', function (evt) {
         var nm = $("#namaBrg option:selected").text();
         $.ajax({
            type : 'GET',
            url : '{{route('ajax_get_namakode') }}',
            data : {data : nm},
            dataType : 'JSON',
            success : function(res){
                if (res.area_toko == 1) {
                $('#harga').val(res[0].harga1);
                } else {
                $('#harga').val(res[0].harga2);
                }
                
                
                $('#id').val(res[0].id);
                // $('#h_awal').val(res[0].hpp);
                // $('#harga').val(res[0].harga1);
                $('#stok').val(res[0].stok);
                $('#qty').val(1);
                
                var harga = $('#harga').val();
                var qty = parseInt($('#qty').val());

                var total = harga * qty;
                $('#total').val(total);
                $('#tambah').prop("disabled", false);
            }
        })
    });
});

 </script>