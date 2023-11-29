<?php
  $main_url      = $config['main_url'];
  $methods       = $config;
  ?>
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('style2/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('style2/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('style2/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('style2/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->

<!-- DataTables -->
<!-- <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}"> -->
<!-- <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}"> -->
<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Kasir</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li class="active">
                        <strong>Transaksi Jual</strong>
                    </li>
                </ol>
            </div>
            <div class="col-lg-2">

            </div>
        </div>       

        <?php if($toko){ ?>
<input type="hidden" id="select_toko" value="{{ $toko->id }}">
<?php } else { ?>
      <div class="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox">
                  <div class="ibox-title">
                  {!! Form::open(['url' => url($config['main_url']), 'method' => 'get', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
                    <div class="row">
                      <div class="col-lg-3">
                      <select class="form-control" name="toko" id="select_toko"> 
                          @foreach($opttoko as $k)
                          <option value="{{ $k->id }}" <?=($opttoko2== $k->id)?"selected":""?>>{{ $k->nama }}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="col-lg-2">
                        <button class="btn btn-primary btn-block">
                            <i class="fa fa-search"></i> Pilih
                        </button>
                      </div>
                    </div>
                    {!! Form::close() !!}
                  </div>
                </div>
            </div>
        </div>           
    </div>
<?php } ?>

<div class="row">
@include('backend.flash_message')
    <div class="col-md-4">
        <div class="ibox">
            <div class="ibox">
                <div class="ibox-title">
                    <i class="fas fa-cogs mr-1"></i>
                    Settings
                </div>
                <div class="ibox-content">
                    <div class="form-group row">
                    <label for="kode_jual" class="col-sm-5 col-form-label">Kode Jual</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="input_kode_jual" value="{{ $kd_jual }}" readonly>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="user" class="col-sm-5 col-form-label">User</label>
                    <div class="col-sm-7">
                      <Input type="text" class="form-control" id="input_user" placeholder="User" Value="{{ $login->first_name }}" readonly>
                    </div>
                  </div> 
                  <div class="form-group row">
                    <label for="sale_type" class="col-sm-5 col-form-label">Tipe Jual</label>
                    <div class="col-sm-7">
                      <select type="sale_type" class="form-control" id="input_sale_type" placeholder="sale_type">
                      <option value="">-Pilih-</option>
                      <option value="Langsung">Langsung</option>
                      <option value="Reseller">Reseller</option>
                      <option value="GrabFood">GrabFood</option>
                      <option value="GoResto">GoResto</option>
                      </select>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="ibox">
            <div class="ibox">
                <div class="ibox-title">
                    <i class="fas fa-edit mr-1"></i>
                    Pilih Items

                </div>
                <div class="ibox-content">
                    <div class="form-group row">
                        <label for="namaBrg" class="col-sm-5 col-form-label">Nama Barang </label>
                        <div class="col-sm-7">
                            <select name="nama_barang" id="namaBrg" class="form-control"></select>
                            <input type="hidden" id="id">                        
                            <input type="hidden" id="stok" class="form-control" readonly>
                            <input type="hidden" name="harga" id="harga" value="" class="form-control" placeholder="Harga">
                            <input type="hidden" name="total" id="total" value="" class="form-control" placeholder="Total" readonly>
                            <input type="hidden" name="store_kategori" id="store_kategori" value="" class="form-control" >
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="qty" class="col-sm-5 col-form-label">Qty</label>
                        <div class="col-sm-7">
                            <input type="number" name="qty" id="qty" value="" min="1" class="form-control" placeholder="qty" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-5 ">
                        <button type="button" class="btn btn-sm btn-primary" id="tombol_hni" data-toggle="modal" data-target="#modal2">
                                <!-- <span class="fa fa-plus"></span> -->
                                <span>HNI</span>
                        </button>
                        </div>
                        <div class="col-sm-7  text-right">
                        <button id="tambah" class="text-right btn btn-primary btn-sm" disabled>
                            <span class="fa fa-shopping-cart"></span>
                            <span> Tambah</span>
                        </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="col-md-4">
        <div class="ibox">
            <div class="ibox">
            <div class="ibox-content">
            
                  <div class="form-group row">
                    <label for="stotal" class="col-sm-5 col-form-label">Subtotal</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="stotal" value="0" readonly>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="input_diskon" class="col-sm-5 col-form-label">Diskon</label>
                    <div class="col-sm-7">
                      <input type="text" class="form-control" id="input_diskon" placeholder="diskon">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="total_akhir" class="col-sm-5 col-form-label">Total Akhir</label>
                    <div class="col-sm-7">
                      <input type="text" class="form-control" id="total_akhir" placeholder="Total Akhir" onchange="cek()" readonly>
                    </div>
                  </div>
                    <div class="form-group row">
                        <div class="col-sm-5">
                        <button type="button" class="btn btn-sm btn-primary" id="tombol_dp" data-toggle="modal" data-target="#modal-default">
                                <!-- <span class="fa fa-plus"></span> -->
                                <span>Bayar dengan DP</span>
                        </button>
                        </div>
                        <div class="col-sm-7 text-right">

                            <button type="submit" form="simpan_transaksi" class="btn btn-sm btn-primary" id="buy">
                                    <span class="fa fa-plus"></span>
                                    <span> SIMPAN</span>
                            </button>
                        </div>
                    </div>
            </div> 
            </div>
        </div>
    </div>
</div>

<br>
<div class="row">
    <div class="ibox col-md-12">
        <div class="ibox">
            <div class="ibox-content">
            {!! Form::open(['url' => url($main_url), 'method' => 'post', 'id' => 'simpan_transaksi','class' => 'form-horizontal']) !!}
            <input type="hidden" name="alltotal" id="alltotal" value="0">
            <input type="hidden" name="diskon" id="diskon">
            <input type="hidden" name="total_akhir" id="grand_total">
            <input type="hidden" value="1" id="no">
            <input type="hidden" name="dp" id="dp">
            <input type="hidden" name="cek_dp" id="cek_dp">
            <input type="hidden" name="kode_jual" id="kode_jual">
            <input type="hidden" name="user" value="{{ $login->id_user }}">
            <input type="hidden" name="sale_type" id="sale_type">
            <input type="hidden" name="cek_hni" id="cek_hni">
            <input type="hidden" name="nama_hni" id="nama_hni">
            <input type="hidden" name="id_hni" id="id_hni">
            <input type="hidden" name="get_toko" value="{{ $opttoko2 }}">



                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <!-- <th>No</th> -->
                                <th>Nama Barang</th>
                                <th>Harga</th>
                                <th>Qty</th>
                                <th>Total</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="cart">
                        
                        </tbody>
                    </table>
                <!-- </div> -->
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>    
<br>
<!-- Horizontal Form -->

<br>
    <div class="ibox">
    <div class="ibox-title">
    <h3>
    <strong>Penjualan Langsung Lunas</strong>
    </h3>
    </div>
        <div class="ibox-content">
            <div class="table-responsive">
                <!-- <table class="table table-striped table-bordered" id="dataTransaksi"> -->
                <table id="transaksi" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>No </th>
                            <th>Kode Jual </th>
                            <th>Sarana Jual </th>
                            <th>Tanggal dan Waktu </th>
                            <th>Diskon</th>
                            <th>Total </th>
                            <th>Aksi </th>
                        </tr>
                    </thead>
                    <tbody>
                    @php $i=1 @endphp
                    @foreach($kode_jual as $d)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $d->id}}</td>
                            <td>{{ $d->sale_type}}</td>
                            <td>{{ $d->created_at}}</td>
                            <td>Rp {{ number_format($d->diskon) }}</td>
                            <td>Rp {{ number_format($d->total) }}</td>
                            <td>
                            <a class="btn btn-primary btn-sm" href="{{ route('detail_jual', $d->id) }}"><i class="fa fa-eye"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="ibox">
    <div class="ibox-title">
    <h3>
    <strong>
    Penjualan Dengan DP
    </strong>
    </h3>
    </div>
        <div class="ibox-content">
            <div class="table-responsive">
                <!-- <table class="table table-striped table-bordered" id="dataTransaksi"> -->
                <table id="transaksi2" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>No </th>
                            <th>Kode Jual </th>
                            <th>Tanggal dan Waktu </th>
                            <th>DP </th>
                            <th>Diskon</th>
                            <th>Total </th>
                            <th>Status</th>
                            <th>Aksi </th>
                        </tr>
                    </thead>
                    <tbody>
                    @php $i=1 @endphp
                    @foreach($kode_jual2 as $d)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $d->id}}</td>
                            <td>{{ $d->created_at}}</td>
                            <td>Rp {{ number_format($d->dp)}}</td>
                            <td>Rp {{ number_format($d->diskon)}}</td>
                            <td>Rp {{ number_format($d->total) }}</td>
                            <td>{{ $d->status_bayar }}</td>
                            <td>
                            <a class="btn btn-primary btn-sm" href="{{ route('detail_jual', $d->id) }}"><i class="fa fa-eye"></i></a>
                            <?php if($d->status_bayar == "Dengan DP") { ?>
                                        {!! Form::model($d,['url' => url($main_url.'/update_status/'.$d->id), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
                                        <input type="hidden" name="status" value="{{ $d->status_bayar }}">
                                        <input type="hidden" name="id" value="{{ $d->id }}">
                                    <button class="btn btn-info btn-outline btn-xs" type="submit"><i class="fa fa-money"></i> lunas</button>
                                        {!! Form::close() !!}
                                    <?php } ?>
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

<!-- MODAL -->
<div class="modal fade" id="modal-default">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">DP (jika ada piutang)</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="form-group"><label class="col-sm-2 control-label">DP</label>
                    <div class="col-sm-6 col-xs-12">
                    <input type="text" name="input_dp" id="input_dp" class="form-control">
                    </div>
                </div>
                    <div class="custom-control custom-switch">
                      <input type="checkbox" class="custom-control-input" id="cek_input_dp">
                      <label class="custom-control-label" for="cek_input_dp">bayar dengan dp</label>
                    </div>
                <!-- Default unchecked
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="input_cek_dp">
                    <label class="custom-control-label" for="input_cek_dp">bayar dengan dp</label>
                </div> -->

            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->

      <!-- MODAL -->
<div class="modal fade" id="modal2">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Input Data BCHNI</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="form-group row"><label class="col-sm-2 control-label">Member</label>
                    <div class="col-sm-1">
                    <input type="checkbox" class="custom-control-input" id="input_cek_hni">
                    </div><strong><span>ya/tidak?</span></strong>
                </div>
                <!-- <div id="input_hni"> -->
                    <div class="form-group row"><label class="col-sm-2 control-label">No ID</label>
                        <div class="col-sm-6 col-xs-12">
                        <input type="number" id="input_id_hni" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row"><label class="col-sm-2 control-label">Nama</label>
                        <div class="col-sm-6 col-xs-12">
                        <input type="text" id="input_nama_hni" class="form-control">
                        </div>
                    </div>
                <!-- </div> -->
                    
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
$(function () {
    $('#transaksi').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
})

$(function () {
    $('#transaksi2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
})
function del(no)
{
    var stotal = parseInt($('#subtotal'+no).val());
    var alltotal = parseInt($('#alltotal').val());
    var newtotal = alltotal - stotal;

    $('#stotal').val(newtotal);
    $('#alltotal').val(newtotal);
    $('#total_akhir').val(newtotal);
    $('#row'+no).remove();
}

var table;
$(document).ready(function() {
    var kode_jual = $('#input_kode_jual').val();
    $('#kode_jual').val(kode_jual);
    var user = $('#input_user').val();
    $('#user').val(user);
    var sale_type = $('#input_sale_type').val();
    $('#sale_type').val(sale_type);
    
    
    $('#input_dp').prop("disabled", true);
    $('#cek_input_dp').click(function(){
        if($(this).prop("checked") == true){
            $('#input_dp').prop("disabled", false);
            $('#cek_dp').val("on");
            // console.log("Checkbox is checked.");
        }
        else if($(this).prop("checked") == false){
            $('#input_dp').prop("disabled", true);
            $('#cek_dp').val("off");
            // console.log("Checkbox is unchecked.");
        }
    });

    $('#input_id_hni').prop("disabled", true);
    $('#input_nama_hni').prop("disabled", true);

    $('#input_cek_hni').click(function(){
        if($(this).prop("checked") == true){
            $('#input_id_hni').prop("disabled", false);
            $('#input_nama_hni').prop("disabled", false);
            $('#cek_hni').val("Member");
            var zero=""
            $('#namaBrg').text(zero);
            console.log("Checkbox is checked.");
        }
        else if($(this).prop("checked") == false){
            $('#input_id_hni').prop("disabled", true);
            $('#input_nama_hni').prop("disabled", true);
            $('#cek_hni').val("Bukan");
            var zero=""
            $('#namaBrg').text(zero);
            console.log("Checkbox is unchecked.");
        }
    });
    
   

    // $('#input_hni').prop("disabled", true);
    $('#buy').prop("disabled", true);
    $('#namaBrg').prop("disabled", true);
    $('#qty').prop("disabled", true);



    var toko= $('#select_toko').val()
    $('#namaBrg').select2({
        ajax : {
            url : '{{ route('ajax_get_namakode') }}',
            type : 'GET',
            dataType : 'JSON',
            data : function(params){
                return{
                    data : params.term,
                    select_toko : toko
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
        var member= $('#cek_hni').val()
        var jenis_jual= $('#sale_type').val()
        var nm = $("#namaBrg option:selected").val();
        var toko= $('#select_toko').val()

        //  console.log(member);
         $.ajax({
            type : 'GET',
            url : '{{route('ajax_get_namakode_detail') }}',
            data : {data : nm, select_toko : toko},
            dataType : 'JSON',
            success : function(res){
                // console.log(res)
                if(res[0].kategori != "HNI"){
                        if(jenis_jual == "Langsung" || jenis_jual== "Reseller"){
                            $('#harga').val(res[0].harga_outlet);
                        }else if( jenis_jual == "GrabFood"){
                            $('#harga').val(res[0].harga_grab);
                        }else if( jenis_jual == "GoResto"){
                            $('#harga').val(res[0].harga_gofood);
                        }
                }else if(res[0].kategori == "HNI"){
                    if (member == "Member") {
                    $('#harga').val(res[0].hpp);
                    } else {
                    $('#harga').val(res[0].harga_outlet);
                    }
                }
                 
                
                $('#id').val(res[0].id);
                $('#store_kategori').val(res[0].store_kategori_id);
                // console.log(res[0].store_kategori_id);
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

    $('#input_id_hni').on("keyup", function(){
        var id_hni= $('#input_id_hni').val();
        $('#id_hni').val(id_hni)
    });

    $('#input_nama_hni').on("keyup", function(){
        var nama_hni= $('#input_nama_hni').val();
        $('#nama_hni').val(nama_hni)
    });
   

        $('#input_kode_jual').on('keyup',function(){
        var kode_jual = $('#input_kode_jual').val();
        $('#kode_jual').val(kode_jual);
        })

        $('#input_user').on('select',function(){
        var user = $('#input_user').val();
        $('#user').val(user);
        })

        $('#input_sale_type').on('change',function(){
        $('#namaBrg').prop("disabled", false);
        $('#qty').prop("disabled", false);
        var sale_type = $('#input_sale_type').val();
        $('#sale_type').val(sale_type);
        var zero=""
        $('#namaBrg').text(zero );
        $('#input_sale_type').prop("disabled", true);
        if(sale_type=="Langsung"){
        $('#tombol_hni').prop("disabled", false);
        $('#tombol_dp').prop("disabled", false);
        }else{
            $('#tombol_hni').prop("disabled", true);
            $('#tombol_dp').prop("disabled", true);
        }

        })
// console.log(sale_type);
    $('#input_diskon').on('keyup',function(){

        var diskon = $('#input_diskon').val();
        var stotal= $('#stotal').val();
        var total_akhir= stotal-diskon;

        $('#total_akhir').val(total_akhir);
        $('#grand_total').val(total_akhir);
        $('#diskon').val(diskon);

    })

    $('#input_dp').on('keyup', function(){
        var dp = $(this).val();
        $('#dp').val(dp);
    })
    $('#input_dp').on('change', function(){
        var dp = $('#input_dp').val();
        $('#dp').val(dp);
    })

   




    $('#harga').on('keyup', function(){
        var harga = $(this).val();
        var qty = parseInt($('#qty').val());
        var stok = parseInt($('#stok').val());
        var total = harga * qty;

        $('#total').val(total);
    })

    $('#qty').on('keyup', function(){   
        var harga = $('#harga').val();
        var qty = parseInt($('#qty').val());
        var stok = parseInt($('#stok').val());
        var total = harga * qty;

        $('#total').val(total);

    })

    $('#qty').on('click', function(){
        var harga = $('#harga').val();
        var qty = parseInt($('#qty').val());
        var stok = parseInt($('#stok').val());
        var total = harga * qty;

        $('#total').val(total);

    })

   
        // debugger;

    $('#tambah').on('click', function(){
        var no = $('#no').val();
        var diskon =$('#input_diskon').val();
        var stotal = parseInt($('#stotal').val());

        var nmbrg = $('#namaBrg option:selected').text();
        var harga = $('#harga').val();
        // var h_awal = $('#h_awal').val();
        var qty = $('#qty').val();
        var subtotal = $('#total').val();
        var idbrg = $('#id').val();
        var kd_jual= $('#kd_jual').val();
        var store_kategori= $('#store_kategori').val();
        // console.log(store_kategori);
        // console.log(idbrg);

        var list = '<tr id="row'+no+'">'
                        +'<td>'
                            +'<input name="nama[]" class="form-control col-md-12" id="nama'+ no +'" readonly>'
                            +'<input type="hidden" name="idbrg[]" class="form-control col-md-12" id="idbrg'+ no +'" readonly>'
                            +'<input type="hidden" name="store_kategori[]" id="store_kategori'+ no +'" readonly>'
                        +'</td>'
                        +'<td>'
                            +'<input name="harga[]" class="form-control col-md-12" id="harga'+ no +'" readonly>'
                            // +'<input name="hpp[]"class="form-control col-md-2" id="h_awal'+ no +'" readonly>'
                        +'</td>'
                        +'<td>'
                            +'<input name="qty[]" class="form-control col-md-12" id="qty'+ no +'" readonly>'
                        +'</td>'
                        +'<td>'
                            +'<input name="subtotal[]" class="form-control col-md-12" id="subtotal'+ no +'" readonly>'
                        +'</td>'
                        +'<td>'
                        +'<button type="button"class="btn btn-danger btn-sm" onClick="del('+no+');">'
                            +'<b>X</b>'
                        +'</button>'
                        +'</td>'
                    +'</tr>';
                    
        // console.log(list);
        $('#cart').append(list);

        $('#no'+no).val(no);  
        $('#nama'+no).val(nmbrg);  
        $('#idbrg'+no).val(idbrg);  
        $('#harga'+no).val(harga);  
        // $('#h_awal'+no).val(h_awal);  
        $('#qty'+no).val(qty);  
        $('#subtotal'+no).val(subtotal);
        $('#kd_jual'+no).val(kd_jual);  
        $('#store_kategori'+no).val(store_kategori);

        stotal += parseInt(subtotal);
        $('#stotal').val(stotal);
        $('#alltotal').val(stotal);
        // $('#vtotal').text(stotal);
        // console.log(cek);
        var total_akhir= stotal-diskon;
        $('#total_akhir').val(total_akhir);
        $('#grand_total').val(total_akhir);
        var no = (no-1) + 2;
        $('#no').val(no);
        $('#buy').prop("disabled", false)
    })

    $('#stotal').on('keyup',function(){
        var diskon =$('#input_diskon').val();
        var stotal= $('#stotal').val();
        var total_akhir= stotal-diskon;

        $('#total_akhir').val(total_akhir);
        $('#grand_total').val(total_akhir);
    })


    
  
    
 });
 
</script>
