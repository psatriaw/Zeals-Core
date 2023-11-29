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
{!! $errors->first('pemesan', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
{!! $errors->first('no_hp', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
{!! $errors->first('tanggal_ambil', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
{!! $errors->first('jam_ambil', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
{!! $errors->first('jenis_ambil', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
{!! $errors->first('tempat_ambil', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
{!! $errors->first('alamat_antar', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
{!! $errors->first('dp', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
@include('backend.flash_message')
    <div class="col-md-3">
        <div class="ibox-deck">
            <div class="ibox">
                <div class="ibox-title">
                    <i class="fas fa-cogs mr-1"></i>
                    Settings
                </div>
                <div class="ibox-content">
                    <div class="form-group row">
                    <label for="kode_pesanan" class="col-sm-5 col-form-label">Kode Pesanan</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="input_kode_pesanan" value="{{ $kd_pesanan }}" readonly>
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
                <hr>
                    <div class="form-group row {{ ($errors->has('pemesan')?"has-error":"") }}">
                        <label for="input_pemesan" class="col-sm-5 col-form-label">Pemesan</label>
                        <div class="col-sm-7">
                            <input name="input_pemesan" id="input_pemesan" placeholder="Nama Pemesan" class="form-control" autofocus>
                        </div>
                    </div>
                    <div class="form-group row {{ ($errors->has('no_hp')?"has-error":"") }}">                        <label for="input_no_hp" class="col-sm-5 col-form-label">No HP</label>
                        <div class="col-sm-7">
                            <input type="tel" name="input_no_hp" id="input_no_hp" class="form-control" placeholder="No HP" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="ibox-deck">
            <div class="ibox">
                <div class="ibox-title">
                    <i class="fas fa-edit mr-1"></i>
                    Informasi Pesanan

                </div>
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-md-6">
                        <div class="form-group row {{ ($errors->has('tanggal_ambil')?"has-error":"") }}">
                                <label for="input_tanggal_ambil" class="col-sm-5 col-form-label">Tanggal Ambil</label>
                                <div class="col-sm-7">
                                    <input type="date" class="form-control" id="input_tanggal_ambil" >
                                </div>
                            </div>
                            <div class="form-group row {{ ($errors->has('jenis_ambil')?"has-error":"") }}">
                                <label for="input_jenis_ambil" class="col-sm-5 col-form-label">Pengambilan</label>
                                <div class="col-sm-7">
                                    <select type="text" class="form-control" id="input_jenis_ambil" name="input_jenis_ambil" required>
                                    <option value="">- Pilih -</option>
                                    <option value="Diambil">Diambil</option>
                                    <option value="Diantar">Diantar</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row {{ ($errors->has('jam_ambil')?"has-error":"") }}">
                                <label for="input_jam_ambil" class="col-sm-5 col-form-label">Jam Ambil/Antar</label>
                                <div class="col-sm-7">
                                    <input type="time" class="form-control" id="input_jam_ambil" name="input_jam_ambil">
                                </div>
                            </div>
                            
                            
                        </div>
                        <div class="col-md-6">    
                        <div class="form-group row {{ ($errors->has('tempat_ambil')?"has-error":"") }}">
                                <label for="input_tempat_ambil" class="col-sm-5 col-form-label">Tempat Ambil</label>
                                <div class="col-sm-7">
                                    <select id="input_tempat_ambil" id="input_tempat_ambil" class="form-control"></select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="input_diskon" class="col-sm-5 col-form-label">Diskon</label>
                                <div class="col-sm-7">
                                    <input type="text"  id="input_diskon" class="form-control" placeholder="Diskon">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="stotal" class="col-sm-5 col-form-label">Total</label>
                                <div class="col-sm-7">
                                    <input type="hidden" class="form-control" id="stotal" value="0" readonly>
                                    <!-- <input type="hidden" class="form-control" id="input_diskon" placeholder="diskon"> -->
                                    <input type="text" class="form-control" id="total_akhir" value="0" readonly>
                                </div>
                            </div>
                        </div>  
                    </div>  
                    <div class="form-group row">
                        <label for="input_catatan" class="col-sm-2 col-form-label">Catatan</label>
                        <div class="col-sm-10">
                            <input type="text"  id="input_catatan" class="form-control" placeholder="Catatan">
                        </div>
                    </div>
                    <div class="form-group row {{ ($errors->has('alamat_antar')?"has-error":"") }}">
                        <label for="input_alamat" class="col-sm-2 col-form-label">Alamat</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="input_alamat" name="input_alamat" placeholder="Alamat">
                        </div>
                    </div>
                </div>
                <br>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="ibox-deck">
            <div class="ibox">
                <div class="ibox-title">
                    <i class="fas fa-edit mr-1"></i>
                    Detail Pesanan
                </div>
                <div class="ibox-content">
                <div class="form-group row {{ ($errors->has('dp')?"has-error":"") }}">
                        <label for="input_dp" class="col-sm-5 col-form-label">DP</label>
                        <div class="col-sm-7">
                            <input type="number" name="input_dp" id="input_dp" class="form-control" value="0" required>
                        </div>
                    </div>
                <!-- <hr> -->
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
                        <label for="catatan_item" class="col-sm-5 col-form-label">Catatan Item</label>
                        <div class="col-sm-7">
                            <input type="text"  id="catatan_item" class="form-control" placeholder="Catatan Item">
                        </div>
                    </div>
                    <button id="tambah" class="text-right btn btn-primary btn-sm" disabled>
                            <span class="fa fa-shopping-cart"></span>
                            <span> Tambah</span>
                    </button>
                </div>
                <div class="text-right ibox-footer ibox-primary">
                    <button type="submit" form="simpan_transaksi" class="float-right btn btn-sm btn-primary" id="buy">
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
            {!! Form::open(['url' => url($main_url), 'method' => 'post', 'id' => 'simpan_transaksi','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
     
            <input type="hidden" name="pemesan" id="pemesan">
            <input type="hidden" name="no_hp" id="no_hp">
            <input type="hidden" name="jenis_ambil" id="jenis_ambil">
            <input type="hidden" name="jam_ambil" id="jam_ambil">
            <input type="hidden" name="tanggal_ambil" id="tanggal_ambil">
            <input type="hidden" name="alamat_antar" id="alamat">
            <input type="hidden" name="tempat_ambil" id="tempat_ambil">
            <input type="hidden" name="catatan" id="catatan">

            <input type="hidden" id="id_ambil">                        
            <input type="hidden" id="area_ambil" class="form-control" readonly>
            <input type="hidden" name="nama_toko" id="nama_toko" value="" class="form-control">

            <input type="hidden" name="alltotal" id="alltotal" value="0">
            <input type="hidden" name="diskon" id="diskon">
            <input type="hidden" name="total_akhir" id="grand_total">
            <input type="hidden" name="dp" id="dp">
            <input type="hidden" value="1" id="no">
            <input type="hidden" name="kode_pesanan" id="kode_pesanan">
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
                            <th>Pengambilan</th>
                            <th>Tanggal Ambil </th>
                            <th>Alamat </th>
                            <th>Tempat Ambil </th>
                            <th>Diskon</th>
                            <th>DP </th>
                            <th>Total </th>
                            <th>Aksi </th>
                        </tr>
                    </thead>
                    <tbody>
                    @php $i=1 @endphp
                    @foreach($kode_pesanan as $d)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $d->id}}</td>
                            <td>{{ $d->nama_pemesan}}</td>
                            <td>{{ $d->jenis_ambil}}</td>
                            <td>{{ $d->tanggal_ambil}}</td>
                            <td>{{ $d->alamat_antar}}</td>
                            <td>{{ $d->nama_toko}}</td>
                            <td>{{ $d->diskon}}</td>
                            <td>Rp {{ number_format($d->dp) }}</td>
                            <td>Rp {{ number_format($d->total) }}</td>
                            <td>
                            <a class="btn btn-primary btn-sm" href="{{ route('detail_pesanan', $d->id) }}"><i class="fa fa-eye"></i></a>
                                                  
                            <?php if($previlege->isAllow($login->id_user,$login->id_department,$methods['edit'])){?>
                            <a href="{{ route('edit_kode_pesanan',$d->id) }}" class="btn btn-primary dim btn-sm"><i class="fa fa-paste"></i> ubah</a>
                            <?php }?>
<!-- 
                            <?php if($previlege->isAllow($login->id_user,$login->id_department,$methods['remove'])){?>
                                <a data-id="{{ $d->id }}" data-url="{{ route('konversi_hapus_admin',$d->id) }}" class="btn btn-danger btn-outline dim btn-sm confirm"><i class="fa fa-trash"></i> hapus</a>
                            <?php }?> -->
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
    var kode_pesanan = $('#input_kode_pesanan').val();
    $('#kode_pesanan').val(kode_pesanan);
    var user = $('#input_user').val();
    $('#user').val(user);
    var dp = $('#input_dp').val();
    $('#dp').val(dp);
    var sale_type = $('#input_sale_type').val();
    $('#sale_type').val(sale_type);
    $('#buy').prop("disabled", true);


    $('#input_tempat_ambil').select2({
        ajax : {
            url : '{{ route('ajax_get_tempat_ambil') }}',
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
         var nm = $("#input_tempat_ambil option:selected").text();
         $.ajax({
            type : 'GET',
            url : '{{route('ajax_get_tempat_ambil') }}',
            data : {data : nm},
            dataType : 'JSON',
            success : function(res){
                $('#id_ambil').val(res[0].id);
                $('#nama_toko').val(res[0].nama);
                $('#area_ambil').val(res[0].toko_area_toko_id);

                var zero=""
                $('#namaBrg').text(zero);
            }
        })
    });



    $('#input_tempat_ambil').on('change',function(){
    var tempat_ambil = $('#input_tempat_ambil').val();
    $('#tempat_ambil').val(tempat_ambil);
    $('#input_tempat_ambil').prop('disabled',true)
    // console.log(tempat_ambil);

    $('#namaBrg').select2({
        ajax : {
            url : '{{ route('ajax_get_pesanan') }}',
            type : 'GET',
            dataType : 'JSON',
            data : function(params){
                return{
                    data : params.term,
                    select_toko : tempat_ambil
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
        var area_ambil = $('#area_ambil').val();
         var nm = $("#namaBrg option:selected").val();
         $.ajax({
            type : 'GET',
            url : '{{route('ajax_get_pesanan_detail') }}',
            data : {data : nm, select_toko : tempat_ambil},
            dataType : 'JSON',
            success : function(res){
                // console.log(res[0]);
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
        $('#input_dp').on('change',function(){
        var dp = $('#input_dp').val();
        // console.log(tanggal_ambil);
        $('#dp').val(dp);
        })
        
        // $('#input_tempat_ambil').on('change',function(){
        // var tempat_ambil = $('#input_tempat_ambil').val();
        // // console.log(tanggal_ambil);
        // $('#tempat_ambil').val(tempat_ambil);
        // })

        $('#input_catatan').on('keyup',function(){
        var catatan = $('#input_catatan').val();
        $('#catatan').val(catatan);
        })

        $('#input_alamat').on('keyup',function(){
        var alamat = $('#input_alamat').val();
        $('#alamat').val(alamat);
        // console.log(alamat);
        })

        $('#input_tanggal_ambil').on('change',function(){
        var tanggal_ambil = $('#input_tanggal_ambil').val();
        // console.log(tanggal_ambil);
        $('#tanggal_ambil').val(tanggal_ambil);
        })

        $('#input_jam_ambil').on('change',function(){
        var jam_ambil = $('#input_jam_ambil').val();
        console.log(jam_ambil);
        $('#jam_ambil').val(jam_ambil);
        })

        $('#input_jenis_ambil').on('change',function(){
        var jenis_ambil = $('#input_jenis_ambil').val();
        // console.log(jenis_ambil);
        $('#jenis_ambil').val(jenis_ambil);
        $('#input_jenis_ambil').prop("disabled", true);
        })

        $('#input_no_hp').on('keyup',function(){
        var no_hp = $('#input_no_hp').val();
        $('#no_hp').val(no_hp);
        })

        $('#input_pemesan').on('keyup',function(){
        var pemesan = $('#input_pemesan').val();
        $('#pemesan').val(pemesan);
        })

        $('#input_kode_pesanan').on('keyup',function(){
        var kode_pesanan = $('#input_kode_pesanan').val();
        $('#kode_pesanan').val(kode_pesanan);
        })

        $('#input_user').on('select',function(){
        var user = $('#input_user').val();
        $('#user').val(user);
        })

        $('#input_sale_type').on('select',function(){
        var sale_type = $('#input_sale_type').val();
        $('#sale_type').val(sale_type);
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
        var kd_pesanan= $('#kd_pesanan').val();
        var catatan_item= $('#catatan_item').val();
        // console.log(kd_pesanan);
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
        $('#kd_pesanan'+no).val(kd_pesanan);  
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
 


