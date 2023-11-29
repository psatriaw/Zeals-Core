<?php
  $main_url      = $config['main_url'];
  $methods       = $config;
?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<link rel="stylesheet" href="{{ asset('style2/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('style2/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<style>
.select2-close-mask{
    z-index: 2099;
}
.select2-dropdown{
    z-index: 3051;
}
</style>
<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Master Barang</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li class="active">
                        <strong>Master Barang</strong>
                    </li>
                </ol>
            </div>
            <div class="col-lg-2">

            </div>
        </div>

      <div class="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox">
                  <div class="ibox-title">
                  {!! Form::open(['url' => url($config['main_url']), 'method' => 'get', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
                    <div class="row">
                      <div class="col-lg-3">
                      <select class="form-control" name="toko"> 
                          <option value="0">BCHNI</option>
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




        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Master Barang</h5>
                        <?php if($previlege->isAllow($login->id_user,$login->id_department,$methods['create'])){?>
                        <div class="ibox-tools">
                            <button type="button" class="float-right btn btn-primary" data-toggle="modal" data-target="#modal-default">
                              Tambah Master Barang
                            </button>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="ibox-content">
                      @include('backend.flash_message')
                      <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example" id="datatable">
                          <thead>
                            <tr>
                                <th>No </th>
                                <th>Nama </th>
                                <th>Kategori </th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Last Update </th>
                                <th>Opsi</th>
                            </tr>
                          </thead>
                          <tbody>
                          @php $i=1 @endphp
                          @foreach($data as $value)
                          <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $value->nama }}</td>
                            <td>{{ $value->kategori }}</td>
                            <td>
                            <li>Harga Member: Rp {{ number_format($value->hpp) }}</li>
                            <li>Harga Customer: Rp {{ number_format($value->harga1) }}</li>
                            <li>poin: Rp {{ number_format($value->poin) }}</li>
                            </td>
                            <td>0</td>
                            <td>{{ $value->updated_at }}</td>
                            <td>opsi</td>
                          
                          </tr>
                          @endforeach
                          </tbody>  
                          </tfoot>
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
              <h4 class="modal-title">Tambah Master Barang</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
            {!! Form::open(['url' => url($main_url.'/store'), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
              <div class="form-group {{ ($errors->has('brand')?"has-error":"") }}"><label class="col-sm-3 control-label">Kepemilikan</label>
                  <div class="col-sm-6 col-xs-12">
                  {!! Form::select('toko_harga[]', $selecttoko, null, ['id' => 'toko_harga','class' => 'form-control select2',"multiple" => "multiple",'required'=>'required']) !!}

                  </div>
              </div>
              <div class="form-group {{ ($errors->has('nama')?"has-error":"") }}"><label class="col-sm-3 control-label">Nama</label>
                  <div class="col-sm-6 col-xs-12">
                      {!! Form::text('nama', null, ['class' => 'form-control']) !!}
                      {!! $errors->first('nama', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                  </div>
              </div>
              <div class="form-group {{ ($errors->has('kategori')?"has-error":"") }}"><label class="col-sm-3 control-label">Kategori</label>
                  <div class="col-sm-6 col-xs-12">
                  {!! Form::select('kategori', ['Dagang' => 'Dagang', 'non-Dagang' => 'non-Dagang'], null, ['class' => 'form-control']) !!}
                      {!! $errors->first('kategori', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                  </div>
              </div>
              <div class="form-group {{ ($errors->has('hpp')?"has-error":"") }}"><label class="col-sm-3 control-label">HPP/Harga Member</label>
                  <div class="col-sm-6 col-xs-12">
                      {!! Form::text('hpp', null, ['class' => 'form-control']) !!}
                      {!! $errors->first('hpp', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                  </div>
              </div>
              <div class="form-group {{ ($errors->has('harga1')?"has-error":"") }}"><label class="col-sm-3 control-label">Harga customer</label>
                  <div class="col-sm-6 col-xs-12">
                      {!! Form::text('harga1', null, ['class' => 'form-control']) !!}
                      {!! $errors->first('harga1', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                  </div>
              </div>
              <!-- <div class="form-group {{ ($errors->has('harga2')?"has-error":"") }}"><label class="col-sm-3 control-label">Harga Member</label>
                  <div class="col-sm-6 col-xs-12">
                      {!! Form::text('harga2', null, ['class' => 'form-control']) !!}
                      {!! $errors->first('harga2', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                  </div>
              </div> -->
              <div class="form-group {{ ($errors->has('poin')?"has-error":"") }}"><label class="col-sm-3 control-label">Poin</label>
                  <div class="col-sm-6 col-xs-12">
                      {!! Form::text('poin', null, ['class' => 'form-control']) !!}
                      {!! $errors->first('poin', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                  </div>
              </div>
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
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->

<script>
$(document).ready(function() {
  $(".select2").select2();



    $('#namaToko').on('change',function(){    
    var zero=""
    $('#namaKategori').text(zero); 
    $('#namaBrand').text(zero); 
    var nmtoko = $('#namaToko option:selected').val();
    console.log(nmtoko)
    if(nmtoko == 0){
      $('#toko_harga').prop("disabled", false);
      $('#alt_toko_harga').prop("disabled", true);
    }else{
      $('#toko_harga').prop("disabled", true);
      $('#alt_toko_harga').prop("disabled", false);
      $('#alt_toko_harga').val(nmtoko);
    }
    // console.log(nmtoko);
    $('#namaKategori').select2({
        ajax : {
            url : '{{ route('ajax_store_kategori') }}',
            type : 'GET',
            dataType : 'JSON',
            data : function(params){
                return{
                    data : params.term,
                    toko : nmtoko
                };
            },
            processResults : function(data){
                var results = [];

                $.each(data, function(index, item){
                    results.push({
                        id : item.id,
                        text : item.kategori
                    })
                }); 
                return { 
                    results : results
                };
            }
        }
    }).on('select2:select', function (evt) {
         var nm = $("#namaKategori option:selected").text();
         $.ajax({
            type : 'GET',
            url : '{{route('ajax_store_kategori') }}',
            data : {data : nm},
            dataType : 'JSON',
            success : function(res){

            }
        })
    });


    $('#namaBrand').select2({
        ajax : {
            url : '{{ route('ajax_store_brand') }}',
            type : 'GET',
            dataType : 'JSON',
            data : function(params){
                return{
                    data : params.term,
                    toko : nmtoko,
                };
            },
            processResults : function(data){
                var results = [];

                $.each(data, function(index, item){
                    results.push({
                        id : item.id,
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
            url : '{{route('ajax_store_brand') }}',
            data : {data : nm},
            dataType : 'JSON',
            success : function(res){
                console.log(res[0].brand)

            }
        })
    });

})



});
</script>