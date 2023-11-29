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
                        <strong>Order HNI</strong>
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

<br>
    <div class="ibox">
    @include('backend.flash_message')
    <div class="ibox-title">
        Internal
    </div>
        <div class="ibox-content">
            <div class="table-responsive">
                <!-- <table class="table table-striped table-bordered" id="dataTransaksi"> -->
                <table id="transaksi" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>No </th>
                            <th>Kode Order HNI </th>
                            <!-- <th>Produk</th> -->
                            <!-- <th>Qty</th> -->
                            <th>Pemesan </th>
                            <th>Tanggal Ambil </th>
                            <th>DP</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Aksi </th>
                        </tr>
                    </thead>
                    <tbody>
                    @php $i=1 @endphp
                    @foreach($kode_order_hni as $d)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $d->store_kode_order_hni_id}}</td>
                            <!-- <td>{{ $d->nama_barang }}</td> -->
                            <!-- <td>{{ $d->qty }}</td> -->
                            <td>{{ $d->nama_pemesan}}</td>
                            <td>{{ $d->tanggal_ambil}}</td>
                            <td>Rp. {{ number_format($d->dp) }}</td>
                            <td>Rp. {{ number_format($d->total) }}</td>
                            <td> <strong> {{ $d->status }} </strong>
                            <?php if($previlege->isAllow($login->id_user,$login->id_department,$methods['edit'])){?>
                            <a href="{{ route('pelunasan_hni_edit_admin',$d->store_kode_order_hni_id) }}" class="btn btn-primary dim btn-xs"><i class="fa fa-paste"></i> ubah</a>
                            <?php }?>
                            </td>
                            <td> 
                            <div class="form-group">
                                <div class="col-sm-6 text-right">
                                    <a class="btn btn-primary btn-xs" href="{{ route('detail_order_hni', $d->store_kode_order_hni_id) }}"><i class="fa fa-eye"></i> detail</a>
                                        <!-- <button class="btn btn-white btn-outline btn-xs" href="{{ route('pelunasan.kelola', $d->store_kode_order_hni_id) }}"><i class="fa fa-cogs"></i>kelola</button> -->
 
                                    <?php if($d->status == "Diproses") { ?>
                                        {!! Form::model($d,['url' => url($main_url.'/update/'.$d->store_kode_order_hni_id), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
                                        <input type="hidden" name="status" value="{{ $d->status }}">
                                        <input type="hidden" name="id" value="{{ $d->store_kode_order_hni_id }}">
                                    <button class="btn btn-danger btn-outline btn-xs" type="submit"><i class="fa fa-check"></i> diterima</button>
                                        {!! Form::close() !!}
                                    <?php } ?>

                                    <?php if($d->status == "Diterima") { ?>
                                        {!! Form::model($d,['url' => url($main_url.'/update/'.$d->store_kode_order_hni_id), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
                                        <input type="hidden" name="status" value="{{ $d->status }}">
                                        <input type="hidden" name="id" value="{{ $d->store_kode_order_hni_id }}">
                                        <button class="btn btn-info btn-outline btn-xs" type="submit"><i class="fa fa-money"></i> lunas</button >
                                        {!! Form::close() !!}
                                    <?php } ?>

                                    <?php if($d->status == "Diterima" && $d->toko_id == "7" ) { ?>
                                        {!! Form::model($d,['url' => url($main_url.'/update/'.$d->store_kode_order_hni_id), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
                                        <input type="hidden" name="status" value="transfer">
                                        <input type="hidden" name="id" value="{{ $d->store_kode_order_hni_id }}">
                                        <button class="btn btn-info btn-outline btn-xs" type="submit"><i class="fa fa-money"></i> Tf Olshop</button >
                                        {!! Form::close() !!}
                                    <?php } ?>

                                    <?php if($d->status == "Lunas") { ?>
                                        {!! Form::model($d,['url' => url($main_url.'/update/'.$d->store_kode_order_hni_id), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
                                        <input type="hidden" name="status" value="Selesai">
                                        <input type="hidden" name="id" value="{{ $d->store_kode_order_hni_id }}">
                                        <button class="btn btn-success btn-outline btn-xs" type="submit"><i class="fa fa-money"></i> Diambil</button >
                                        {!! Form::close() !!}
                                    <?php } ?>

                                    <?php if($d->status == "Lunas" || $d->status == "Diterima") { ?>
                                        {!! Form::model($d,['url' => url($main_url.'/update/'.$d->store_kode_order_hni_id), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
                                        <input type="hidden" name="status" value="Stok">
                                        <input type="hidden" name="id" value="{{ $d->store_kode_order_hni_id }}">
                                        <button class="btn btn-info btn-outline btn-xs" type="submit"><i class="fa fa-money"></i> Stok</button >
                                        {!! Form::close() !!}
                                    <?php } ?>

                                    <!-- <?php if($d->status == "Lunas" || $d->status == "Diterima") { ?>
                                        {!! Form::model($d,['url' => url($main_url.'/update/'.$d->store_kode_order_hni_id), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
                                        <input type="hidden" name="status" value="Retur">
                                        <input type="hidden" name="id" value="{{ $d->store_kode_order_hni_id }}">
                                        <button class="btn btn-danger btn-outline btn-xs" type="submit"><i class="fa fa-money"></i> Retur</button >
                                        {!! Form::close() !!}
                                    <?php } ?> -->
                                </div>
                            </div>
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
    var sale_type = $('#input_sale_type').val();
    $('#sale_type').val(sale_type);
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
            url : '{{route('ajax_get_namakode') }}',
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

        $('#input_dp').on('change',function(){
        var dp = $('#input_dp').val();
        // console.log(tanggal_ambil);
        $('#dp').val(dp);
        })
        
        $('#input_tempat_ambil').on('change',function(){
        var tempat_ambil = $('#input_tempat_ambil').val();
        // console.log(tanggal_ambil);
        $('#tempat_ambil').val(tempat_ambil);
        })

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
        console.log(jenis_ambil);
        $('#jenis_ambil').val(jenis_ambil);
        })

        $('#input_no_hp').on('keyup',function(){
        var no_hp = $('#input_no_hp').val();
        $('#no_hp').val(no_hp);
        })

        $('#input_pemesan').on('keyup',function(){
        var pemesan = $('#input_pemesan').val();
        $('#pemesan').val(pemesan);
        })

        $('#input_kode_order_hni').on('keyup',function(){
        var kode_order_hni = $('#input_kode_order_hni').val();
        $('#kode_order_hni').val(kode_order_hni);
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
 


