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
                        <strong>Order Pesanan</strong>
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
{!! $errors->first('nama_pemesan', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
{!! $errors->first('no_id', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
{!! $errors->first('tanggal_ambil', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
{!! $errors->first('dp', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
@include('backend.flash_message')
    <div class="col-md-4">
            <div class="ibox">
                <div class="ibox-title">
                    <i class="fas fa-cogs mr-1"></i>
                    Settings
                </div>
                <div class="ibox-content">
                    <div class="form-group row">
                    <label for="kode_order_hni" class="col-sm-5 col-form-label">Kode Pesanan</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="input_kode_order_hni" value="{{ $kd_order_hni }}" readonly>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="user" class="col-sm-5 col-form-label">User</label>
                    <div class="col-sm-7">
                      <Input type="text" class="form-control" id="input_user" placeholder="User" Value="{{ $login->first_name}}" readonly>
                    </div>
                  </div> 
                  <div class="form-group row">
                    <label for="sale_type" class="col-sm-5 col-form-label">Nama Toko</label>
                    <div class="col-sm-7">
                    <Input type="text" class="form-control" id="input_toko" placeholder="Toko1" Value="{{ $nama_toko }}" readonly>
                    </div>
                  </div>
                  <div class="form-group row"><label class="col-sm-5 control-label">Member</label>
                    <div class="col-sm-1">
                    <input type="checkbox" class="custom-control-input" id="cek_hni">
                    </div><strong><span>ya/tidak?</span></strong>
                </div>
                </div>
            </div>
    </div>

    <div class="col-md-4">
        <div class="ibox-deck">
            <div class="ibox">
                <div class="ibox-title">
                    <i class="fas fa-edit mr-1"></i>
                    Informasi Order

                </div>
                <div class="ibox-content">

                    <div class="form-group row">
                    <label for="input_nama_pemesan" class="col-sm-5 col-form-label">Nama Pemesan</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="input_nama_pemesan" required>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="input_no_id" class="col-sm-5 col-form-label">No ID</label>
                    <div class="col-sm-7">
                      <Input type="text" class="form-control" id="input_no_id">
                    </div>
                  </div> 
                  <div class="form-group row">
                    <label for="input_tanggal_ambil" class="col-sm-5 col-form-label">Tanggal Ambil</label>
                    <div class="col-sm-7">
                    <input type="date" class="form-control" id="input_tanggal_ambil" required>
                    </div>
                  </div>
                  <div class="form-group row {{ ($errors->has('dp')?"has-error":"") }}">
                        <label for="input_dp" class="col-sm-5 col-form-label">DP</label>
                        <div class="col-sm-7">
                            <input type="number" name="input_dp" id="input_dp" class="form-control" placeholder="0" required>
                        </div>
                    </div>
                </div>
                <br>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="ibox-deck">
            <div class="ibox">
                <div class="ibox-title">
                    <i class="fas fa-edit mr-1"></i>
                    Detail Pesanan
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
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="qty" class="col-sm-5 col-form-label">Qty</label>
                        <div class="col-sm-7">
                            <input type="number" name="qty" id="qty" value="" min="1" class="form-control" placeholder="qty" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="total_akhir" class="col-sm-5 col-form-label">Total Akhir</label>
                        <div class="col-sm-7">
                        <input type="hidden" class="form-control" id="input_diskon" placeholder="diskon">
                        <input type="hidden" class="form-control" id="stotal" value="0" readonly>
                        <input type="text" class="form-control" id="total_akhir" placeholder="Total Akhir" onchange="cek()" readonly>
                        </div>
                  </div>
                </div>
                <div class="ibox-footer">
                <div class="row">
                    <div class="col-sm-5">
                        <button id="tambah" class="text-right btn btn-primary btn-sm" disabled>
                                <span class="fa fa-shopping-cart"></span>
                                <span> Tambah</span>
                        </button>
                    </div>
                    <div class="col-sm-7 text-right">
                        <button type="submit" form="simpan_transaksi" class="float-right btn btn-sm btn-primary" id="buy">
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
    <div class="ibox-deck col-md-12">
        <div class="ibox">
            <div class="ibox-content">
            {!! Form::open(['url' => url($main_url), 'method' => 'post', 'id' => 'simpan_transaksi','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
     
            <input type="hidden" name="nama_pemesan" id="nama_pemesan">
            <input type="hidden" name="no_id" id="no_id">
            <input type="hidden" name="tanggal_ambil" id="tanggal_ambil">
            <input type="hidden" name="member" id="member">            
            <!-- <input type="hidden" name="nama_toko" id="nama_toko" value="" class="form-control"> -->

            <input type="hidden" name="alltotal" id="alltotal" value="0">
            <input type="hidden" name="diskon" id="diskon">
            <input type="hidden" name="total_akhir" id="grand_total">
            <input type="hidden" name="dp" id="dp">
            <input type="hidden" value="1" id="no">
            <input type="hidden" name="kode_order_hni" id="kode_order_hni">
            <input type="hidden" name="user" id="user" value="{{ $login->id_user }}">
            <input type="hidden" name="toko" id="toko" value="{{ $id_toko }}">
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <!-- <th>No</th> -->
                                <th>Nama Barang</th>
                                <th>Harga</th>
                                <th>Qty</th>
                                <th>Catatan Item</th>
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
        <div class="ibox-content">
            <div class="table-responsive">
                <!-- <table class="table table-striped table-bordered" id="dataTransaksi"> -->
                <table id="transaksi" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>No </th>
                            <th>Kode Pesanan </th>
                            <th>Pemesan </th>
                            <th>ID </th>
                            <th>Tanggal Ambil </th>
                            <th>DP </th>
                            <th>Total </th>
                            <th>Aksi </th>
                        </tr>
                    </thead>
                    <tbody>
                    @php $i=1 @endphp
                    @foreach($kode_order_hni as $d)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $d->id}}</td>
                            <td>{{ $d->nama_pemesan}}</td>
                            <?php if($d->id_member){?>
                                <td>{{ $d->id_member}}</td>
                            <?php } else { ?>
                                <td>Bukan Member</td>
                            <?php } ?>
                            <td>{{ $d->tanggal_ambil}}</td>
                            <td>Rp {{ number_format($d->dp) }}</td>
                            <td>Rp {{ number_format($d->total) }}</td>
                            <td>
                            <a class="btn btn-primary btn-sm" href="{{ route('detail_order_hni', $d->id) }}"><i class="fa fa-eye"></i></a>
                            <?php if($previlege->isAllow($login->id_user,$login->id_department,$methods['edit'])){?>
                            <a href="{{ route('edit_kode_order_hni',$d->id) }}" class="btn btn-primary dim btn-sm"><i class="fa fa-paste"></i> ubah</a>
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
    var kode_order_hni = $('#input_kode_order_hni').val();
    $('#kode_order_hni').val(kode_order_hni);
    var user = $('#input_user').val();
    $('#user').val(user);

    $('#input_no_id').prop("disabled", true);
    $('#cek_hni').click(function(){
        if($(this).prop("checked") == true){
            $('#input_no_id').prop("disabled", false);
            $('#cek_hni').val("Member");
            $('#member').val("Member");
            var zero=""
            $('#namaBrg').text(zero);
            $('#cek_hni').prop("disabled", true);
            // console.log("Checkbox is checked.");
        }
        else if($(this).prop("checked") == false){
            $('#input_no_id').prop("disabled", true);
            $('#cek_hni').val("Bukan");
            $('#member').val("Bukan");
            var zero=""
            $('#namaBrg').text(zero);
            // console.log("Checkbox is unchecked.");
        }
    });
    
    var toko= $('#select_toko').val()
    $('#namaBrg').select2({
        ajax : {
            url : '{{ route('ajax_get_hni') }}',
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
         var nm = $("#namaBrg option:selected").val();
         $.ajax({
            type : 'GET',
            url : '{{route('ajax_get_hni_detail') }}',
            data : {data : nm, select_toko : toko},
            dataType : 'JSON',
            success : function(res){
                // console.log(res[0]);
                if (member == "Member") {
                    $('#harga').val(res[0].hpp);
                } else {
                    $('#harga').val(res[0].harga_outlet);
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

        $('#input_dp').on('change',function(){
        var dp = $('#input_dp').val();
        // console.log(tanggal_ambil);
        $('#dp').val(dp);
        })

        $('#input_no_id').on('change',function(){
        var no_id = $('#input_no_id').val();
        // console.log(tanggal_ambil);
        $('#no_id').val(no_id);
        })


        $('#input_tanggal_ambil').on('change',function(){
        var tanggal_ambil = $('#input_tanggal_ambil').val();
        // console.log(tanggal_ambil);
        $('#tanggal_ambil').val(tanggal_ambil);
        })



        $('#input_nama_pemesan').on('keyup',function(){
        var nama_pemesan = $('#input_nama_pemesan').val();
        $('#nama_pemesan').val(nama_pemesan);
        })

        $('#input_kode_order_hni').on('keyup',function(){
        var kode_order_hni = $('#input_kode_order_hni').val();
        $('#kode_order_hni').val(kode_order_hni);
        })

        $('#input_user').on('select',function(){
        var user = $('#input_user').val();
        $('#user').val(user);
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
        var kd_order_hni= $('#kd_order_hni').val();
        var catatan_item= $('#catatan_item').val();
        // console.log(kd_order_hni);
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
                            +'<input name="catatan_item[]" class="form-control col-md-12" id="catatan_item'+ no +'" readonly>'
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
        $('#kd_order_hni'+no).val(kd_order_hni);  
        $('#catatan_item'+no).val(catatan_item);


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
 


