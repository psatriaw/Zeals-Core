<?php
  $main_url      = $config['main_url'];
  $methods       = $config;
  ?>
<link rel="stylesheet" href="{{ asset('style2/plugins/fontawesome-free/css/all.min.css') }}">
<!-- Ionicons -->
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
<!-- Theme style -->
<!-- <link rel="stylesheet" href="{{ asset('style2/dist/css/adminlte.min.css') }}"> -->
<link rel="stylesheet" href="{{ asset('style2/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('style2/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('style2/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('style2/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('style2/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('style2/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
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
                        <strong>Transaksi Retur</strong>
                    </li>
                </ol>
            </div>
            <div class="col-lg-2">

            </div>
        </div>      
<div class="row">
@include('backend.flash_message')
    <div class="col-md-6">
        <div class="ibox-deck">
            <div class="ibox">
                <div class="ibox-title">
                    <i class="fas fa-edit mr-1"></i>
                    Pilih Items

                </div>
                <form action="javascript:void(0);">
                <div class="ibox-content">
                <div class="form-group row">
                        <label for="namaBrg" class="col-sm-5 col-form-label">Nama Barang </label>
                        <div class="col-sm-7">
                            <select name="nama_barang" id="namaBrg" class="form-control"></select>
                            <input type="hidden" id="id">                        
                            <!-- <input type="hidden" id="stok" class="form-control" readonly> -->
                            <!-- <input type="hidden" name="harga" id="harga" value="" class="form-control" placeholder="Harga"> -->
                            <input type="hidden" name="total" id="total" value="" class="form-control" placeholder="Total" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="brand_id" class="col-sm-5 col-form-label">Brand</label>
                        <div class="col-sm-7">
                            <select name="nama_kode" id="namaBrand" class="form-control"></select>
                            <input type="hidden" name="brand_id" id="brand_id" class="form-control"  readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="namaKode" class="col-sm-5 col-form-label">Kode </label>
                        <div class="col-sm-7">
                            <select name="nama_kode" id="namaKode" class="form-control"></select>
                            <input type="hidden" id="kode_barang" class="form-control">                         
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="harga_beli" class="col-sm-5 col-form-label">Harga Beli</label>
                        <div class="col-sm-7">
                            <input type="number" name="harga_beli" id="harga_beli" class="form-control"  readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="sisa_stok" class="col-sm-5 col-form-label">Stok</label>
                        <div class="col-sm-7">
                            <input type="number" name="sisa_stok" id="sisa_stok" class="form-control"  readonly>
                            <input type="hidden" name="harga" id="harga" class="form-control" placeholder="Harga Retur Satuan" required>
                        </div>
                    </div>
                    <!-- <div class="form-group row">
                        <label for="harga_satuan" class="col-sm-5 col-form-label">Harga Retur</label>
                        <div class="col-sm-7"> -->
                        <!-- </div>
                    </div> -->
                    <div class="form-group row">
                        <label for="qty" class="col-sm-5 col-form-label">Qty</label>
                        <div class="col-sm-7">
                            <input type="number" name="qty" id="qty" value="" min="1"max="" class="form-control" placeholder="qty" required>
                        </div>
                    </div>
                    <div class="form-group row text-right">
                        <button id="tambah" class="text-right btn btn-primary btn-sm" disabled>
                            <span class="fa fa-shopping-cart"></span>
                            <span> Tambah</span>
                        </button>
                    </div>
                    </form>
                </div> 
            </div>
        </div>
    </div>

    <div class="col-md-6">
            <div class="ibox">
                <div class="ibox-title">
                    <i class="fas fa-cogs mr-1"></i>
                    Settings
                </div>
                <div class="ibox-content">
                    <div class="form-group row">
                    <label for="kode_retur" class="col-sm-5 col-form-label">Kode Retur</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="input_kode_retur" value="{{ $kd_retur }}" readonly>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="user" class="col-sm-5 col-form-label">User</label>
                    <div class="col-sm-7">
                      <Input type="text" class="form-control" id="input_user" placeholder="User" Value="{{ $login->first_name }}" readonly>
                    </div>
                  </div> 
                  <div class="form-group row">
                    <label for="input_keterangan" class="col-sm-5 col-form-label" required>Keterangan</label>
                    <div class="col-sm-7">
                      <Input type="text" class="form-control" id="input_keterangan" >
                    </div>
                  </div> 
                  <div class="form-group row text-right">
                        <button type="submit" form="simpan_transaksi" class="btn btn-sm btn-primary" id="buy">
                                <span class="fa fa-plus"></span>
                                <span> SIMPAN</span>
                        </button>
                    </div>
                </div>
            </div>
    </div>
</div>

<br>
<div class="row">
    <div class="ibox-deck col-md-12">
        <div class="ibox">
            <div class="ibox-content">
            {!! Form::open(['url' => url($main_url), 'method' => 'post', 'id' => 'simpan_transaksi','class' => 'form-horizontal']) !!}
            <input type="hidden" value="1" id="no">
            <input type="hidden" name="kode_retur" id="kode_retur">
            <input type="hidden" name="keterangan" id="keterangan">
            <input type="hidden" name="user" id="user" value="{{ $login->id_user }}">
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <!-- <th>No</th> -->
                                <th>Jenis Barang</th>
                                <th>Kode</th>
                                <!-- <th>Harga</th> -->
                                <th>Qty</th>
                                <!-- <th>Total</th> -->
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
        <div class="ibox-content">
            <div class="table-responsive">
                <!-- <table class="table table-striped table-bordered" id="dataTransaksi"> -->
                <table id="transaksi" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>No </th>
                            <th>Kode Retur </th>
                            <th>Kode Barang</th>
                            <th>Jenis Barang</th>
                            <th>Brand</th>
                            <th>Tanggal </th>
                            <th>Qty </th>
                        </tr>
                    </thead>
                    <tbody>
                    @php $i=1 @endphp
                    @foreach($barang_retur as $value)
                    <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $value->id }}</td>
                            <td>{{ $value->kode_barang }}</td>
                            <td>{{ $value->jenis }}</td>
                            <td>{{ $value->brand }}</td>
                            <td>{{ $value->created_at }}</td>
                            <td>{{ $value->jumlah }}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @include('backend.do_confirm')
    @include('backend.footer')
  </div>
</div>

<!-- Bootstrap 4 -->
<script src="{{ asset('style2/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('style2/dist/js/adminlte.min.js') }}"></script>
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
    var kode_retur = $('#input_kode_retur').val();
    $('#kode_retur').val(kode_retur);
    var user = $('#input_user').val();
    $('#user').val(user);
    var keterangan = $('#input_keterangan').val();
    $('#keterangan').val(keterangan);
    $('#buy').prop("disabled", true);

    $('#namaBrg').select2({
        ajax : {
            url : '{{ route('ajax_bcs_barang') }}',
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
                        text : item.jenis
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
            url : '{{route('ajax_bcs_barang_detail') }}',
            data : {data : nm},
            dataType : 'JSON',
            success : function(res){
                $('#id').val(res.id);
                $('#qty').val(1);
                
                var harga = $('#harga').val();
                var qty = parseInt($('#qty').val());

                var total = harga * qty;
                $('#total').val(total);
                // $('#tambah').prop("disabled", false);

            }
        })
    });

    $('#namaBrg').on('change',function(){    
    var zero=""
    $('#namaBrand').text(zero); 
    $('#namaKode').text(zero); 
    var nmbrg = $('#namaBrg option:selected').val();
    $('#namaBrand').select2({
        ajax : {
            url : '{{ route('ajax_bcs_brand') }}',
            type : 'GET',
            dataType : 'JSON',
            data : function(params){
                return{
                    data : params.term,
                    jenis : nmbrg
                };
            },
            processResults : function(data){
                var results = [];

                $.each(data, function(index, item){
                    results.push({
                        id : item.brand_id,
                        text : item.brand
                    })
                }); 
                return { 
                    results : results
                };
            }
        }
    }).on('select2:select', function (evt) {
         var nm = $("#namaBrand option:selected").text();
         $.ajax({
            type : 'GET',
            url : '{{route('ajax_bcs_brand_detail') }}',
            data : {data : nm},
            dataType : 'JSON',
            success : function(res){

            }
        })
    });
})

    $('#namaBrand').on('change',function(){    
    var zero=""
    $('#namaKode').text(zero); 
    $('#brand_id').val(zero);
    $('#sisa_stok').val(zero);
    $('#qty').prop('max', zero);
    $('#harga_beli').val(zero);
    var nmBrand = $('#namaBrand option:selected').val();
    var nmbrg = $('#namaBrg option:selected').val();
    $('#namaKode').select2({
        ajax : {
            url : '{{ route('ajax_bcs_kode') }}',
            type : 'GET',
            dataType : 'JSON',
            data : function(params){
                return{
                    data : params.term,
                    jenis : nmbrg,
                    brand : nmBrand
                };
            },
            processResults : function(data){
                var results = [];

                $.each(data, function(index, item){
                    results.push({
                        id : item.kode_barang,
                        text : item.kode_barang
                    })
                }); 
                return { 
                    results : results
                };
            }
        }
    }).on('select2:select', function (evt) {
         var nm = $("#namaKode option:selected").text();
         $.ajax({
            type : 'GET',
            url : '{{route('ajax_bcs_kode_detail') }}',
            data : {data : nm},
            dataType : 'JSON',
            success : function(res){
                console.log(res[0].brand)
                // document.getElementById("brand_id").text = res[0].brand;
                $('#brand_id').val(res[0].brand);
                // console.log($('#brand_id').text(res[0].brand))
                $('#sisa_stok').val(res[0].stok);
                $('#qty').prop('max', res[0].stok);
                // console.log($('#qty').prop('max'));
                $('#harga_beli').val(res[0].hpp);
                // $('#kode_barang').val(res[0].kode_barang);
                // console.log(res[0].bcs_brand_id);
                // $('#brand_id').val(res.brand_id);
                
                $('#tambah').prop("disabled", false);
                

            }
        })
    });
})


        $('#input_keterangan').on('change',function(){
        var keterangan = $('#input_keterangan').val();
        $('#keterangan').val(keterangan);
        })



    $('#harga').on('keyup', function(){
        var harga = $(this).val();
        var qty = parseInt($('#qty').val());
        var stok = parseInt($('#stok').val());
        var total = harga * qty;

        $('#total').val(total);
    })

    $('#harga').on('change', function(){
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
    $('#qty').on('change', function(){
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
        var kode_barangnya = $('#namaKode option:selected').text();
        // var h_awal = $('#h_awal').val();
        var qty = $('#qty').val();
        var subtotal = $('#total').val();
        // var idbrand= $('#brand_id').val();
        // var kode_barangnya= $('#kode_barang');
        var idbrg = $('#id').val();
        var kd_retur= $('#kd_retur').val();
        var stok= $('#sisa_stok').val();


        if(stok<qty){
            

        }else {

        

// console.log(idbrand);
        var list = '<tr id="row'+no+'">'
                        +'<td>'
                            +'<input name="nama[]" class="form-control col-md-12" id="nama'+ no +'" readonly>'
                            +'<input type="hidden" name="idbrg[]" class="form-control col-md-12" id="idbrg'+ no +'" readonly>'
                        +'</td>'
                        +'<td>'
                            +'<input name="kode_barangnya[]" class="form-control col-md-12" id="kode_barangnya'+ no +'" readonly>'
                            // +'<input type="hidden" name="idbrand[]" class="form-control col-md-12" id="idbrand'+ no +'" readonly>'
                            // +'<input name="hpp[]"class="form-control col-md-2" id="h_awal'+ no +'" readonly>'
                        +'</td>'
                        // +'<td>'
                            // +'<input name="harga[]" class="form-control col-md-12" id="harga'+ no +'" readonly>'
                            // +'<input name="hpp[]"class="form-control col-md-2" id="h_awal'+ no +'" readonly>'
                        // +'</td>'
                        +'<td>'
                            +'<input name="qty[]" class="form-control col-md-12" id="qty'+ no +'" readonly>'
                        +'</td>'
                        // +'<td>'
                            // +'<input name="subtotal[]" class="form-control col-md-12" id="subtotal'+ no +'" readonly>'
                        // +'</td>'
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
        $('#kode_barangnya'+no).val(kode_barangnya);  
        $('#idbrg'+no).val(idbrg);  
        // $('#idbrand'+no).val(idbrand);  
        $('#harga'+no).val(harga);  
        // $('#h_awal'+no).val(h_awal);  
        $('#qty'+no).val(qty);  
        $('#subtotal'+no).val(subtotal);
        $('#kd_retur'+no).val(kd_retur);  


        stotal +=   parseInt(subtotal);
        // console.log(stotal);
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
        
        }
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


