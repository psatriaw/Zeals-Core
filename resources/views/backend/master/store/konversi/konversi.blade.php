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
                        <strong>Konversi</strong>
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
                <button disabled> 
                    <span type="hidden" class="fa fa-cogs"></span>
                    <span> Setting</span>
                </button>
                </div>
                <div class="ibox-content">
                    <div class="form-group row">
                    <label for="kode_konversi" class="col-sm-5 col-form-label">Kode Konversi</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="input_kode_konversi" value="{{ $kd_konversi }}" readonly>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="user" class="col-sm-5 col-form-label">User</label>
                    <div class="col-sm-7">
                      <input type="text" class="form-control" id="input_user" placeholder="User" value="{{ $login->first_name }}" readonly>
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
                    <button id="tambah" class="pull-right btn btn-primary btn-sm" disabled>
                        <span class="fa fa-shopping-cart"></span>
                        <span> Tambah</span>
                    </button>
                </div>
                <div class="ibox-content">
                <form action="javascript:void(0);">
                    <div class="form-group row">
                        <label for="namaBrg" class="col-sm-5 col-form-label">Rumus </label>
                        <div class="col-sm-7">
                            <select name="nama_rumus" id="nama_rumus" class="form-control"></select>
                            <input type="hidden" id="id_rumus" class="form-control">                        
                            <input type="hidden"  id="dari" class="form-control" readonly>
                            <input type="hidden"  id="qty_dari" class="form-control">
                            <input type="hidden"  id="qty11" class="form-control">
                            <input type="hidden"  id="ke" class="form-control" readonly>
                            <input type="hidden"  id="qty_ke" class="form-control">
                            <input type="hidden"  id="qty22" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="qty" class="col-sm-5 col-form-label">Jumlah Konversi</label>
                        <div class="col-sm-7">
                            <input type="number" name="qty" id="qty" value="" min="1" class="form-control" placeholder="qty" required>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>


    <div class="col-md-4">
        <div class="ibox-deck">
            <div class="ibox">
            <div class="ibox-title">
                    <i class="fas fa-edit mr-1"></i>
                    Hasil Konversi
                <button type="submit" form="simpan_transaksi" class="pull-right btn btn-sm btn-primary" id="buy">
                        <span class="fa fa-plus"></span>
                        <span> SIMPAN</span>
                </button>
            </div>
            <!-- <div class="ibox-content">
                  <div class="form-group row">
                        <label for="nama_hasil" class="col-sm-5 col-form-label">Nama</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="hasil_konversi" placeholder="Nama" readonly>
                        </div>
                  </div>
                  <div class="form-group row">
                        <label for="qty_konversi" class="col-sm-5 col-form-label">Qty</label>
                        <div class="col-sm-7">
                            <input type="number" class="form-control" id="qty_konversi" value="0" readonly>
                        </div>
                  </div>
            </div> -->
            </div>
        </div>
    </div>
</div>

<br>
<div class="row">
    <div class="ibox-deck col-md-12">
        <div class="ibox">
            <div class="ibox-content">
            {!! Form::open(['url' => url($main_url), 'method' => 'post', 'id' => 'simpan_transaksi','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
             <input type="hidden" name="kode_konversi" id="kode_konversi">
            <input type="hidden" value="1" id="no">
            <input type="hidden" name="user" value="{{ $login->id_user }}">
            <input type="hidden" name="qty_total" id="qty_total" value="">
            <input type="hidden" name="toko_id" id="toko_id" value="{{ $id_toko }}">
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <!-- <th>No</th> -->
                                <th>Rumus Konversi</th>
                                <th>Jumlah Konversi</th>
                                <th>Dari</th>
                                <th>Qty</th>
                                <th>Ke</th>
                                <th>Qty</th>
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
                            <th>Kode Konversi </th>
                            <th>Tanggal </th>
                            <!-- <th>Jumlah Konversi </th> -->
                            <th>Aksi </th>
                        </tr>
                    </thead>
                    <tbody>
                    @php $i=1 @endphp
                    @foreach($kode_konversi as $d)
                        <tr>
                            <th>{{ $i++ }}</th>
                            <th>{{ $d->id}}</th>
                            <th>{{ $d->created_at}}</th>
                            <!-- <th>{{ $d->total }}</th> -->
                            <th>
                            <a class="btn btn-primary btn-sm" href="{{ route('detail_konversi', $d->id) }}"><i class="fa fa-eye"></i></a>
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
    var qty_subtotal= parseInt($('#qty_total'+no).val());
    var qty_totalan= parseInt($('#qty_total').val());
    var newtotal= qty_totalan-qty_subtotal;
    
    $('#qty_total').val(newtotal);
    $('#row'+no).remove();
}

var table;
$(document).ready(function() {
    var kode_konversi = $('#input_kode_konversi').val();
    $('#kode_konversi').val(kode_konversi);
    var tujuan_konversi = $('#input_tujuan_konversi').val();
    $('#tujuan_konversi').val(tujuan_konversi);
    $('#buy').prop("disabled", true);

    $('#nama_rumus').select2({
        ajax : {
            url : '{{ route('get_rumus') }}',
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
                        text : item.nama_rumus
                    })
                }); 
                return {
                    results : results
                };
            }
        }
    }).on('select2:select', function (evt) {
         var nm = $("#nama_rumus option:selected").text();
         $.ajax({
            type : 'GET',
            url : '{{route('get_rumus_detail') }}',
            data : {data : nm},
            dataType : 'JSON',
            success : function(res){
                console.log(res[0]);
                $('#id_rumus').val(res[0].id);
                $('#dari').val(res[0].dari);
                $('#qty_dari').val(res[0].qty1);
                $('#qty11').val(res[0].qty1);
                $('#ke').val(res[0].ke);
                $('#qty_ke').val(res[0].qty2);
                $('#qty22').val(res[0].qty2);
                $('#qty').val(1);   
                
                var harga = $('#harga').val();
                var qty = parseInt($('#qty').val());

                var total = harga * qty;
                $('#total').val(total);
                $('#tambah').prop("disabled", false);
            }
        })
    });

        $('#input_kode_konversi').on('keyup',function(){
        var kode_konversi = $('#input_kode_konversi').val();
        $('#kode_konversi').val(kode_konversi);
        })

    $('#qty').on('keyup', function(){   
        // var dari = $('#dari').val();
        var qty = $('#qty').val();
        console.log(qty);
        var qty_dari = $('#qty_dari').val();
        // var ke = $('#ke').val();
        var qty_ke = $('#qty_ke').val();

        var qty11 = qty_dari * qty;
        var qty22 = qty_ke * qty;

        $('#qty11').val(qty11);
        $('#qty22').val(qty22);

    })

    // $('#qty').on('click', function(){
    //     var qty = parseInt($('#qty').val());
    //     var qty_dari = parseInt($('#qty_dari').val());
    //     // var ke = $('#ke').val();
    //     var qty_ke = parseInt($('#qty_ke').val());

    //     var qty_dari = qty_dari * qty;
    //     var qty_ke = qty_ke * qty;

    //     $('#qty_ke').val(qty_ke);
    //     $('#qty_dari').val(qty_dari);

    // })

   
    var qty_total= 0;
    // debugger;

    $('#tambah').on('click', function(){
        var no = $('#no').val();

        var nmrms = $('#nama_rumus option:selected').text();
        var id_rumus = $('#id_rumus').val();
        var dari = $('#dari').val();
        var qty11 = $('#qty11').val();
        var ke = $('#ke').val();
        var qty22 = $('#qty22').val();
        var kd_konversi= $('#kd_konversi').val();
        var catatan= $('#catatan').val();
        var qty = $('#qty').val();
        var qty_awal= parseInt($('#qty').val());

        var list = '<tr id="row'+no+'">'
                        +'<td>'
                            +'<input name="nama_rumus[]" class="form-control col-md-12" id="nama_rumus'+ no +'" readonly>'
                            +'<input type="hidden" name="id_rumus[]" class="form-control col-md-12" id="id_rumus'+ no +'" readonly>'
                        +'</td>'
                        +'<td>'
                            +'<input name="qty[]" class="form-control col-md-12" id="qty'+ no +'" readonly>'
                        +'</td>'
                        +'<td>'
                            +'<input name="dari[]" class="form-control col-md-12" id="dari'+ no +'" readonly>'
                            // +'<input name="hpp[]"class="form-control col-md-2" id="h_awal'+ no +'" readonly>'
                        +'</td>'
                        +'<td>'
                            +'<input name="qty11[]" class="form-control col-md-12" id="qty11'+ no +'" readonly>'
                        +'</td>'
                        +'<td>'
                            +'<input name="ke[]" class="form-control col-md-12" id="ke'+ no +'" readonly>'
                        +'</td>'
                        +'<td>'
                            +'<input name="qty22[]" class="form-control col-md-12" id="qty22'+ no +'" readonly>'
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
        $('#nama_rumus'+no).val(nmrms);  
        $('#id_rumus'+no).val(id_rumus);  
        $('#qty'+no).val(qty);  
        $('#dari'+no).val(dari);  
        // $('#h_awal'+no).val(h_awal);  
        $('#qty11'+no).val(qty11);  
        $('#ke'+no).val(ke);
        $('#qty22'+no).val(qty22);
        $('#kd_konversi'+no).val(kd_konversi);  
        // $('#catatan'+no).val(catatan);

        console.log(qty_awal);
        
        qty_total += parseInt(qty_awal);
        console.log(qty_total);
        // document.getElementById("qty_total").innerHTML = qty_total;
        $('#qty_total').val(qty_total);


        
        var no = (no-1) + 2;
        $('#no').val(no);
        $('#buy').prop("disabled", false)

    })



    
  
    
 });
 
</script>
 