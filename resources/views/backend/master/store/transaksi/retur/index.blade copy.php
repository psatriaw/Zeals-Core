@extends('layouts.main')
@section('css')
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('style2/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('style2/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('style2/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('style2/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<style>
.cek {
  text-align: center;
  max-width: 500px;
  margin: auto;
}
</style>
<!-- DataTables -->
<!-- <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}"> -->
<!-- <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}"> -->
@endsection

@section('title', 'POS BC')




@section('content')

<div class="content mt-3">
    <div class="animated fadeIn">
        @if(session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
        @endif
    <div class="card">
        <div class="card-header">
            Kode Retur: {{ $kd_retur }}
        </div>
        <div class="card-body">
            <div class="row">
                <div class="form-group col-md-3">
                    <label for="namaBrg">Nama Barang </label>
                    <select name="nama_barang" id="namaBrg" class="form-control"></select>
                    <input type="hidden" id="id">
                    <input type="hidden" id="kd_retur" value="{{ $kd_retur }}">
                </div>
                <div class="form-group col-md-1">
                    <label for="stok">Stok</label>
                    <input type="text" id="stok" class="form-control" readonly>
                </div>
                <div class="form-group col-md-2">   
                    <label for="harga">Harga Retur</label>
                    <input type="number" name="harga" id="harga" value="" class="form-control" placeholder="Harga">
                    <!-- <input type="hidden" id="h_awal"> -->
                </div>
                <div class="form-group col-md-2">
                    <label for="qty">Qty</label>
                    <input type="number" name="qty" id="qty" value="" min="1" class="form-control" placeholder="qty" required>
                </div>
                <div class="form-group col-md-2">
                    <label for="total">Catatan</label>
                    <input type="hidden" name="total" id="total" value="" class="form-control" placeholder="Total" readonly>
                    <input type="text" name="catatan" id="catatan" value="" class="form-control" placeholder="Catatan">
                </div>
                <br>
                <div class="form-group col-md-2">
                    <br/>
                    <button id="tambah" class="btn btn-primary btn-sm" disabled>
                        <span class="fa fa-shopping-cart"></span>
                        <span> Tambah Ke Cart</span>
                    </button>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h4><span class="fa fa-shopping-cart"></span> Cart Retur</h4>
                </div>
                <div class="col-md-6">
                    <input type="hidden" id="stotal" value="0">
                    <h4>Total : Rp. 
                        <span id="vtotal">0</span>
                    </h4>
                </div>
            </div>
            <hr>
            <div class="container">
            <div class="row"  style="margin-bottom:10px;">
                <div class="col-md-2" class="cek">
                    <label>Nama Barang</barang>
                </div>
                <div class="col-md-2" class="cek">
                    <label>Harga</barang>
                </div>
                <div class="col-md-2" class="cek">
                    <label>Jumlah</barang>
                </div>
                <div class="col-md-2" class="cek">
                    <label>Subtotal</barang>
                </div>
                <div class="col-md-2" class="cek">
                    <label>Catatan</barang>
                </div>
            </div>
            </div>
            <form action="{{ route('save_transaksi_retur') }}" method="POST">
            @csrf
                <input type="hidden" name="alltotal" id="alltotal" value="0">
                <input type="hidden" value="1" id="no">
                <div class="container">
                    <div id="cart"></div>
                </div>
                <div class="container">
                    <button type="submit" name="beli" class="btn btn-sm btn-primary" id="buy">
                        <span class="fa fa-plus"></span>
                        <span> SIMPAN</span>
                    </button>
                </div>
                <hr>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <!-- <table class="table table-striped table-bordered" id="dataTransaksi"> -->
                <table id="transaksi" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>No </th>
                            <th>Kode Retur </th>
                            <th>Tanggal </th>
                            <th>Total </th>
                            <th>Aksi </th>
                        </tr>
                    </thead>
                    <tbody>
                    @php $i=1 @endphp
                    @foreach($kode_retur as $d)
                        <tr>
                            <th>{{ $i++ }}</th>
                            <th>{{ $d->id}}</th>
                            <th>{{ $d->created_at}}</th>
                            <th>Rp {{ number_format($d->total) }}</th>
                            <th>
                            <a class="btn btn-primary btn-sm" href="{{ route('detail_retur', $d->id) }}"><i class="fa fa-eye"></i></a>
                            </th>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
  </div>
</div>
@endsection

@section('js')
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
    var stotal = parseInt($('#harga'+no).val());
    var alltotal = parseInt($('#alltotal').val());
    var newtotal = alltotal - stotal;

    $('#stotal').val(newtotal);
    $('#alltotal').val(newtotal);
    $('#vtotal').text(newtotal);
    $('#row'+no).remove();
}

var table;
$(document).ready(function() {
    $('#buy').prop("disabled", true);

    $('#namaBrg').select2({
        ajax : {
            url : '{{ route('ajax_get_namakode') }}',
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
            url : '{{route('ajax_get_barang') }}',
            data : {data : nm},
            dataType : 'JSON',
            success : function(res){
                console.log(res[0]);
                $('#id').val(res[0].id);
                // $('#h_awal').val(res[0].hpp);
                $('#harga').val(res[0].harga1);
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
        // console.log(no);
        var stotal = parseInt($('#stotal').val());

        var nmbrg = $('#namaBrg option:selected').text();
        var harga = $('#harga').val();
        // var h_awal = $('#h_awal').val();
        var qty = $('#qty').val();
        var total = $('#total').val();
        var idbrg = $('#id').val();
        var kd_retur= $('#kd_retur').val();
        var catatan= $('#catatan').val();
        // console.log(kd_retur);
        // console.log(idbrg);

        var list = '<div class="row" id="row'+ no +'" style="margin-bottom:10px;">'
                        +'<div class="col-md-2">'
                            +'<input type="hidden" name="no" id="no'+ no +'">'
                            +'<input name="nama[]" class="form-control" id="nama'+ no +'" readonly>'
                            +'<input type="hidden" name="store_master_barang_id[]" id="idbrg'+ no +'">'
                            +'<input type="hidden" name="kd_retur[]" id="kd_retur'+ no +'">'
                        +'</div>'
                        +'<div class="col-md-2">'
                            +'<input name="harga[]" class="form-control" id="harga'+ no +'" readonly>'
                            // +'<input name="hpp[]"class="form-control" id="h_awal'+ no +'" readonly>'
                        +'</div>'
                        +'<div class="col-md-2">'
                            +'<input name="qty[]" class="form-control" id="qty'+ no +'" readonly>'
                        +'</div>'
                        +'<div class="col-md-2">'
                            +'<input name="subtotal[]" class="form-control" id="subtotal'+ no +'" readonly>'
                        +'</div>'
                        +'<div class="col-md-2">'
                            +'<input name="catatan[]" class="form-control" id="catatan'+ no +'" readonly>'
                        +'</div>'
                        +'<button type="button"class="btn btn-danger btn-sm" onClick="del('+no+');">'
                            +'<b>X</b>'
                        +'</button>'
                    +'</div>';
        // console.log(list);
        $('#cart').append(list);

        $('#no'+no).val(no);  
        $('#nama'+no).val(nmbrg);  
        $('#idbrg'+no).val(idbrg);  
        $('#harga'+no).val(harga);  
        // $('#h_awal'+no).val(h_awal);  
        $('#qty'+no).val(qty);  
        $('#subtotal'+no).val(total);
        $('#kd_retur'+no).val(kd_retur);  
        $('#catatan'+no).val(catatan);


        stotal += parseInt(total);
        $('#stotal').val(stotal);
        $('#alltotal').val(stotal);
        $('#vtotal').text(stotal);
        // console.log(cek);

        var no = (no-1) + 2;
        $('#no').val(no);
        $('#buy').prop("disabled", false)
    })

  
    
 });
 
</script>
@endsection 