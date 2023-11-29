<?php
  $main_url      = $config['main_url'];
  $methods       = $config;
  ?>
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('style2/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('style2/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('style2/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('style2/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
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
                        <strong>Transaksi Mutasi</strong>
                    </li>
                </ol>
            </div>
            <div class="col-lg-2">

            </div>
        </div>    

        <?php if($toko){ ?>

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
        <div class="ibox-deck">
            <div class="ibox">
                <div class="ibox-title">
                    <i class="fas fa-cogs mr-1"></i>
                    Settings
                </div>
                <div class="ibox-content">
                    <div class="form-group row">
                    <label for="kode_mutasi" class="col-sm-5 col-form-label">Kode Mutasi</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="input_kode_mutasi" value="{{ $kd_mutasi }}" readonly>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="user" class="col-sm-5 col-form-label">User</label>
                    <div class="col-sm-7">
                      <input type="text" class="form-control" id="input_user" placeholder="User" value="{{ $login->first_name }}" readonly>
                    </div>
                  </div> 
                  <div class="form-group row">
                    <label for="input_jenis_mutasi" class="col-sm-5 col-form-label">Jenis Mutasi</label>
                    <div class="col-sm-7">
                      <select name="input_jenis_mutasi" id="input_jenis_mutasi" class="form-control">
                      <!-- <option value="Reseller">Reseller</option> -->
                      <!-- <option value="Reseller">Reseller</option> -->
                      </select>
                    </div> 
                  </div>
                  <div class="form-group row">
                    <label for="input_tujuan_mutasi" class="col-sm-5 col-form-label">Tujuan Mutasi</label>
                    <div class="col-sm-7">
                      <select name="input_tujuan_mutasi" id="input_tujuan_mutasi" class="form-control"></select>
                      <!-- <select type="text" class="form-control" id="input_tujuan_mutasi" placeholder="Tujuan Mutasi">
                      <option value="">- Pilih -</option>
                      @foreach( $opttokostore as $value)
                      <option value="{{ $value->id }}">{{ $value->nama }}</option>
                      @endforeach
                      </select> -->
                    </div> 
                  </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="ibox-deck">
            <div class="ibox">
                <div class="ibox-title">
                    <i class="fas fa-edit mr-1"></i>
                    Pilih Items

                </div>
                <div class="ibox-content">
                <form action="javascript:void(0);">
                    <div class="form-group row">
                        <label for="namaBrg" class="col-sm-5 col-form-label">Nama Barang </label>
                        <div class="col-sm-7">
                            <select name="nama_barang" id="namaBrg" class="form-control"></select>
                            <input type="hidden" id="id">                        
                            <input type="hidden" id="stok" class="form-control" readonly>
                            <input type="hidden" name="harga" id="harga" value="" class="form-control" placeholder="Harga">
                            <input type="hidden" name="total" id="total" value="" class="form-control" placeholder="Total" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="qty" class="col-sm-5 col-form-label">Qty</label>
                        <div class="col-sm-7">
                            <input type="number" name="qty" id="qty" value="" min="1" class="form-control" placeholder="qty" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="qty" class="col-sm-5 col-form-label">Catatan</label>
                        <div class="col-sm-7">
                            <input type="text" name="catatan" id="catatan" value="" class="form-control" placeholder="Catatan">
                        </div>
                    </div>
                    <div class="form-group row float-right">
                        <button id="tambah" class="float-right btn btn-primary btn-sm" disabled>
                            <span class="fa fa-shopping-cart"></span>
                            <span> Tambah</span>
                        </button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>


    <div class="col-md-4">
        <div class="ibox-deck">
            <div class="ibox">
            <div class="ibox-content">
            
                    <div class="form-group row">
                    <!-- <label for="stotal" class="col-sm-5 col-form-label">Subtotal</label> -->
                    <div class="col-sm-7">
                        <input type="hidden" class="form-control" id="stotal" value="0" readonly>
                    </div>
                  </div>
                  <div class="form-group row">
                    <!-- <label for="input_diskon" class="col-sm-5 col-form-label">Diskon</label> -->
                    <div class="col-sm-7">
                      <input type="hidden" class="form-control" id="input_diskon" placeholder="diskon">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="total_akhir" class="col-sm-5 col-form-label">Total Akhir</label>
                    <div class="col-sm-7">
                      <input type="text" class="form-control" id="total_akhir" placeholder="Total Akhir" onchange="cek()" readonly>
                    </div>
                  </div>
                    <div class="form-group row float-right">
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

<br>
<div class="row">
    <div class="ibox-deck col-md-12">
        <div class="ibox">
            <div class="ibox-content">
            {!! Form::open(['url' => url($main_url), 'method' => 'post', 'id' => 'simpan_transaksi','class' => 'form-horizontal']) !!}
            <input type="hidden" name="alltotal" id="alltotal" value="0">
            <input type="hidden" name="diskon" id="diskon">
            <input type="hidden" name="total_akhir" id="grand_total">
            <input type="hidden" value="1" id="no">
            <input type="hidden" name="kode_mutasi" id="kode_mutasi">
            <input type="hidden" name="user" value="{{ $login->id_user }}">
            <input type="hidden" name="toko_id" id="toko_id" value="{{ $nama_toko}}">
            <input type="hidden" name="tujuan_mutasi" id="tujuan_mutasi">
            <input type="hidden" name="get_toko" value="{{ $opttoko2 }}">
            <input type="hidden" name="jenis_mutasi" id="jenis_mutasi" >

            <!-- <input type="hidden" name="jenis_toko" id="jenis_toko"> -->
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <!-- <th>No</th> -->
                                <th>Nama Barang</th>
                                <th>Harga</th>
                                <th>Qty</th>
                                <th>Catatan</th>
                                <th>Total</th>
                                <th>Aksi</th>
                            </tr>
                        <tbody id="cart">
                        </thead>
                        
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
    <div class="ibox-title">Mutasi Keluar</div>
        <div class="ibox-content">
            <div class="table-responsive">
                <!-- <table class="table table-striped table-bordered" id="dataTransaksi"> -->
                <table id="transaksi" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>No </th>
                            <th>Kode Mutasi </th>
                            <th>Tujuan </th>
                            <th>Jenis Mutasi</th>
                            <th>Tanggal </th>
                            <th>Total </th>
                            <th>Status</th>
                            <th>Aksi </th>
                        </tr>
                    </thead>
                    <tbody>
                    @php $i=1 @endphp
                    @foreach($kode_mutasi as $d)
                        <tr>
                            <th>{{ $i++ }}</th>
                            <th>{{ $d->id}}</th>
                            <th>{{ $d->nama}}</th>
                            <th>{{$d->jenis_mutasi}}</th>
                            <th>{{ $d->created_at}}</th>
                            <th>Rp {{ number_format($d->total) }}</th>
                            <th>{{ $d->status }}</th>
                            <th>
                            <a class="btn btn-primary btn-sm" href="{{ route('detail_mutasi', $d->id) }}"><i class="fa fa-eye"></i></a>
                            @if($d->status == "Menunggu Konfirmasi" && $d->toko_jenis_toko_id==1)
                                    {!! Form::model($d,['url' => url($main_url.'/update/'.$d->id), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
                                        <input type="hidden" name="status" value="Batal">
                                        <input type="hidden" name="id" value="{{ $d->id }}">
                                        <button class="btn btn-danger btn-outline btn-xs" type="submit"><i class="fa fa-window-close"></i> Batal</button>
                                        {!! Form::close() !!}
                            @endif
                            </th>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <br>
    <div class="ibox">
    <div class="ibox-title">Mutasi Masuk</div>
        <div class="ibox-content">
            <div class="table-responsive">
                <!-- <table class="table table-striped table-bordered" id="dataTransaksi"> -->
                <table id="transaksi" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>No </th>
                            <th>Kode Mutasi </th>
                            <th>dari </th>
                            <th>Jenis Mutasi</th>
                            <th>Tanggal </th>
                            <th>Total </th>
                            <th>Status</th>
                            <th>Aksi </th>
                        </tr>
                    </thead>
                    <tbody>
                    @php $i=1 @endphp
                    @foreach($kode_mutasi2 as $d)
                        <tr>
                            <th>{{ $i++ }}</th>
                            <th>{{ $d->id}}</th>
                            <th>{{ $d->nama}}</th>
                            <th>{{$d->jenis_mutasi}}</th>
                            <th>{{ $d->created_at}}</th>
                            <th>Rp {{ number_format($d->total) }}</th>
                            <th>{{ $d->status }}</th>
                            <th>
                            <a class="btn btn-primary btn-sm" href="{{ route('detail_mutasi', $d->id) }}"><i class="fa fa-eye"></i></a>
                            @if($d->status == "Menunggu Konfirmasi"&& $d->toko_jenis_toko_id== 1)
                                    {!! Form::model($d,['url' => url($main_url.'/update/'.$d->id), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
                                        <input type="hidden" name="status" value="Terkonfirmasi">
                                        <input type="hidden" name="id" value="{{ $d->id }}">
                                        <button class="btn btn-success btn-outline btn-xs" type="submit"><i class="fa fa-check"></i> Konfirmasi</button>
                                    {!! Form::close() !!}
                            @endif

                            </th>
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
    var kode_mutasi = $('#input_kode_mutasi').val();
    $('#kode_mutasi').val(kode_mutasi);
    var user = $('#input_user').val();
    $('#user').val(user);
    var tujuan_mutasi = $('#input_tujuan_mutasi').val();
    $('#tujuan_mutasi').val(tujuan_mutasi);
    $('#buy').prop("disabled", true);

    var toko= $('#select_toko').val()
    $('#namaBrg').select2({
        ajax : {
            url : '{{ route('ajax_get_primer') }}',
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
         var nm = $("#namaBrg option:selected").val();
         $.ajax({
            type : 'GET',
            url : '{{route('ajax_get_primer_detail') }}',
            data : {data : nm, select_toko : toko},
            dataType : 'JSON',
            success : function(res){
                $('#harga').val(res[0].harga_outlet);

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


    $('#input_jenis_mutasi').select2({
        ajax : {
            url : '{{ route('ajax_jenis_mutasi') }}',
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
                        text : item.jenis_toko
                    })
                }); 
                return {
                    results : results
                };
            }
        }
    }).on('select2:select', function (evt) {
         var nm = $("#input_jenis_mutasi option:selected").val();
         $.ajax({
            type : 'GET',
            url : '{{route('ajax_jenis_mutasi_detail') }}',
            data : {data : nm, select_toko : toko},
            dataType : 'JSON',
            success : function(res){
                $('#harga').val(res[0].harga_outlet);

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

    $('#input_jenis_mutasi').on('change',function(){
        var jenis_mutasi = $('#input_jenis_mutasi').val();
        var text_jenis_mutasi = $('#input_jenis_mutasi').text();
        $('#jenis_mutasi').val(text_jenis_mutasi)
        // $('#input_jenis_mutasi').prop('disabled',true);
        // console.log(jenis_mutasi);
    $('#input_tujuan_mutasi').select2({
        ajax : {
            url : '{{ route('ajax_tujuan_mutasi') }}',
            type : 'GET',
            dataType : 'JSON',
            data : function(params){
                return{
                    data : params.term,
                    select_toko : toko,
                    jenis_mutasi : jenis_mutasi
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
         var nm = $("#input_tujuan_mutasi option:selected").val();
         $.ajax({
            type : 'GET',
            url : '{{route('ajax_tujuan_mutasi_detail') }}',
            data : {data : nm},
            dataType : 'JSON',
            success : function(res){
                $('#harga').val(res[0].harga_outlet);

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

        $('#input_tujuan_mutasi').on('change',function(){
        var tujuan_mutasi = $('#input_tujuan_mutasi').val();
        $('#tujuan_mutasi').val(tujuan_mutasi);
        $('#input_tujuan_mutasi').prop("disabled", true);
        $('#input_jenis_mutasi').prop('disabled',true);
        })

        $('#input_kode_mutasi').on('keyup',function(){
        var kode_mutasi = $('#input_kode_mutasi').val();
        $('#kode_mutasi').val(kode_mutasi);
        })

       

    $('#input_diskon').on('keyup',function(){

        var diskon = $('#input_diskon').val();
        var stotal= $('#stotal').val();
        var total_akhir= stotal-diskon;

        $('#total_akhir').val(total_akhir);
        $('#grand_total').val(total_akhir);
        $('#diskon').val(diskon);

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
        var kd_mutasi= $('#kd_mutasi').val();
        var catatan= $('#catatan').val();
        // console.log(kd_mutasi);
        // console.log(idbrg);

        var list = '<tr id="row'+no+'">'
                        +'<td>'
                            +'<input name="nama[]" class="form-control col-md-12" id="nama'+ no +'" readonly>'
                            +'<input type="hidden" name="idbrg[]" class="form-control col-md-12" id="idbrg'+ no +'" readonly>'
                        +'</td>'
                        +'<td>'
                            +'<input name="harga[]" class="form-control col-md-12" id="harga'+ no +'" readonly>'
                            // +'<input name="hpp[]"class="form-control col-md-2" id="h_awal'+ no +'" readonly>'
                        +'</td>'
                        +'<td>'
                            +'<input name="qty[]" class="form-control col-md-12" id="qty'+ no +'" readonly>'
                        +'</td>'
                        +'<td>'
                            +'<input name="catatan[]" class="form-control col-md-12" id="catatan'+ no +'" readonly>'
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
        $('#kd_mutasi'+no).val(kd_mutasi);  
        $('#catatan'+no).val(catatan);


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
 

