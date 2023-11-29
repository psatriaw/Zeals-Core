<?php
  $main_url      = $config['main_url'];
  $methods       = $config;
?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<link rel="stylesheet" href="{{ asset('style2/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('style2/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('style2/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('style2/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
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
                      <select class="form-control" name="toko"> 
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
                                <th>Brand </th>
                                <th>Harga</th>
                                <th>Last Update </th>
                                <th>Opsi</th>
                            </tr>
                          </thead>
                          <tbody>
                          @php $i=1 @endphp
                           @foreach($template as $value)
                                  <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $value->nama }}</td>
                                    <td>{{ $value->store_kategori->kategori }}</td>
                                    <td>{{ $value->store_brand->brand }}</td>
                                    <td>
                                    @if($data1)
                                    @foreach($data1 as $d)
                                    @if($d->id == $value->id)
                                      <li>Harga Outlet: <b>Rp {{ number_format($d->harga_outlet) }}</b></li>
                                      <?php if($value->store_kategori->kategori == "HNI"){ ?>
                                        <li>Harga Member: <b> Rp {{ number_format($d->hpp) }} </b></li>
                                      <?php } ?>
                                      <?php if($d->harga_gofood){ ?>
                                        <li>Harga Go Resto: <b> Rp {{ number_format($d->harga_gofood) }} </b></li>
                                      <?php } ?>
                                      <?php if($d->harga_grab){ ?>
                                        <li>Harga Grab: <b> Rp {{ number_format($d->harga_grab) }} </b></li>
                                      <?php } ?>

                                    @endif
                                    @endforeach
                                    @else
                                    <li><b>Harga Belum Diatur</b></li>
                                    @endif
                                    </td>
                                    <td>{{ $value->updated_at }}</td>
                                    <td>

 
                                      <?php if($toko) { ?>
                                        <a  class="btn btn-primary dim btn-xs"><i class="fa fa-info"></i>-Hak admin kasir</a>
                                      <?php }else{ ?>

                                        <?php if($previlege->isAllow($login->id_user,$login->id_department,$methods['edit'])){?>
                                          <a href="{{ route('master_barang.edit_admin',$value->id) }}" class="btn btn-success dim btn-xs"><i class="fa fa-paste"></i> ubah bacth</a>
                                        <?php }?>

                                        <?php if($previlege->isAllow($login->id_user,$login->id_department,$methods['edit'])){?>
                                          <a href="{{ route('master_barang.edit',[$value->id, $id_toko]) }}" class="btn btn-primary dim btn-xs"><i class="fa fa-paste"></i> ubah</a>
                                        <?php }?>

                                        <?php if($previlege->isAllow($login->id_user,$login->id_department,$methods['remove'])){?>
                                          <a data-id="{{ $value->id }}" data-url="{{ route('master_barang.destroy',$value->id) }}" class="btn btn-danger btn-outline dim btn-xs confirm"><i class="fa fa-trash"></i> hapus</a>
                                        <?php }?>

                                      <?php } ?>

                                      
                                     

                                    </td>
                                  </tr>
                            @endforeach
                           @foreach($data as $value)
                                  <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $value->nama }}</td>
                                    <td>{{ $value->store_kategori->kategori }}</td>
                                    <td>{{ $value->store_brand->brand }}</td>
                                    <td>
                                    <li>Harga Outlet: <b>Rp {{ number_format($value->harga_outlet) }}</b></li>
                                    <?php if($value->harga_gofood){ ?>
                                      <li>Harga Go Resto: <b> Rp {{ number_format($value->harga_gofood) }} </b></li>
                                    <?php } ?>
                                    <?php if($value->harga_grab){ ?>
                                      <li>Harga Grab: <b> Rp {{ number_format($value->harga_grab) }} </b></li>
                                    <?php } ?>
                                    </td>
                                    <td>{{ $value->updated_at }}</td>
                                    <td>
                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$methods['edit'])){?>
                                          <a href="{{ route('master_barang.edit',[$value->id, $id_toko]) }}" class="btn btn-primary dim btn-xs"><i class="fa fa-paste"></i> ubah</a>
                                      <?php }?>
                                      
                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$methods['remove'])){?>
                                          <a data-id="{{ $value->id }}" data-url="{{ route('master_barang.destroy',$value->id) }}" class="btn btn-danger btn-outline dim btn-xs confirm"><i class="fa fa-trash"></i> hapus</a>
                                        <?php }?>

                                    </td>
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

            <?php if($toko){ ?>
              <div class="form-group {{ ($errors->has('brand')?"has-error":"") }}"><label class="col-sm-3 control-label"></label>
                  <div class="col-sm-6 col-xs-12">
                      {!! Form::hidden('toko_id', $id_toko, ['class' => 'form-control']) !!}
                      {!! Form::hidden('toko_harga[]', $id_toko, ['class' => 'form-control']) !!}

                  </div>
              </div>
              <div class="form-group {{ ($errors->has('kategori')?"has-error":"") }}"><label class="col-sm-3 control-label">Kategori</label>
                  <div class="col-sm-6 col-xs-12">
                    <select name="kategori" id="namaKategori" class="form-control">
                    @foreach($optkategori as $o)
                    <option value="{{ $o->id }}">{{ $o->kategori }}</option>
                    @endforeach
                    </select>
                      {!! $errors->first('kategori', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                  </div>
              </div>
              <div class="form-group {{ ($errors->has('brand')?"has-error":"") }}"><label class="col-sm-3 control-label">Brand</label>
                  <div class="col-sm-6 col-xs-12">
                    <select name="brand" id="namaBrand" class="form-control">
                    @foreach($optbrand as $o)
                    <option value="{{ $o->id }}">{{ $o->brand }}</option>
                    @endforeach
                    </select>
                      {!! $errors->first('brand', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                  </div>
              </div>
              <?php } else { ?>
              <div class="form-group {{ ($errors->has('brand')?"has-error":"") }}"><label class="col-sm-3 control-label">Kepemilikan</label>
                  <div class="col-sm-6 col-xs-12">
                  <select class="form-control" name="toko_id" id="namaToko"> 
                          <option value="0">Semua Outlet</option>
                          @foreach($opttoko as $k)
                          <option value="{{ $k->id }}" <?=($opttoko2 == $k->id)?"selected":""?>>{{ $k->nama }}</option>
                          @endforeach
                        </select>
                  </div>
              </div>

              <div class="form-group {{ ($errors->has('brand')?"has-error":"") }}"><label class="col-sm-3 control-label">Kelompok Harga</label>
                  <div class="col-sm-6 col-xs-12">
                    {!! Form::select('toko_harga[]', $selecttoko, null, ['id' => 'toko_harga','class' => 'form-control select2',"multiple" => "multiple",'required'=>'required']) !!}
                    {!! Form::hidden('toko_harga[]',null, ['class' => 'form-control', 'id' => 'alt_toko_harga']) !!}
                      <span class="help-block">*) untuk pengelompokan barang</span>
                      {!! $errors->first('id_brand', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                  </div>
              </div>
              <div class="form-group {{ ($errors->has('kategori')?"has-error":"") }}"><label class="col-sm-3 control-label">Kategori</label>
                  <div class="col-sm-6 col-xs-12">
                    <select name="kategori" id="namaKategori" class="form-control"></select>

                      {!! $errors->first('kategori', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                  </div>
              </div>
              <div class="form-group {{ ($errors->has('brand')?"has-error":"") }}"><label class="col-sm-3 control-label">Brand</label>
                  <div class="col-sm-6 col-xs-12">
                    <select name="brand" id="namaBrand" class="form-control"></select>
                      {!! $errors->first('brand', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                  </div>
              </div>
              <?php } ?>

              <div class="form-group {{ ($errors->has('nama')?"has-error":"") }}"><label class="col-sm-3 control-label">Nama</label>
                  <div class="col-sm-6 col-xs-12">
                      {!! Form::text('nama',null, ['class' => 'form-control']) !!}

                      {!! $errors->first('nama', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                  </div>
              </div>
              <div class="form-group {{ ($errors->has('hpp')?"has-error":"") }}"><label class="col-sm-3 control-label">Hpp</label>
                  <div class="col-sm-6 col-xs-12">
                      {!! Form::text('hpp',null, ['class' => 'form-control']) !!}
                      {!! $errors->first('hpp', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                  </div>
              </div>
              <div class="form-group {{ ($errors->has('harga1')?"has-error":"") }}"><label class="col-sm-3 control-label">Harga</label>
                  <div class="col-sm-6 col-xs-12">
                      {!! Form::text('harga1',0, ['class' => 'form-control']) !!}
                      {!! $errors->first('harga1', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                  </div>
              </div>
              <div class="hr-line-dashed"></div> 
<span>Harga Optional</span>
              <div class="hr-line-dashed"></div> 
              <div class="form-group {{ ($errors->has('harga2')?"has-error":"") }}"><label class="col-sm-3 control-label">Harga Go Resto</label>
                  <div class="col-sm-6 col-xs-12">
                      {!! Form::text('harga2',0, ['class' => 'form-control']) !!}
                  </div>
              </div>
              <div class="form-group {{ ($errors->has('harga3')?"has-error":"") }}"><label class="col-sm-3 control-label">Harga Grab</label>
                  <div class="col-sm-6 col-xs-12">
                      {!! Form::text('harga3',0, ['class' => 'form-control']) !!}
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


      <script src="{{ asset('style2/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('style2/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('style2/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('style2/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script>
$(function () {
    $('#datatable').DataTable({
      "paging": false,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": true,
      "responsive": true,
    });
})


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