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
                <h2>Jenis</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li class="active">
                        <strong>Jenis</strong>
                    </li>
                </ol>
            </div>
            <div class="col-lg-2">

            </div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Jenis</h5>
                        <?php if($previlege->isAllow($login->id_user,$login->id_department,$methods['create'])){?>
                        <div class="ibox-tools">
                            <button type="button" class="float-right btn btn-primary" data-toggle="modal" data-target="#modal-default">
                              Tambah Jenis
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
                                <th>Jenis </th>
                                <th>Satuan</th>
                                <th>Brand</th>
                                <th>Opsi</th>
                            </tr>
                          </thead>
                           @foreach($cek2 as $value)
                                  <tr>
                                    <td>{{ $value->id }}</td>
                                    <td>{{ $value->jenis }}</td>
                                    <td>{{ $value->satuan }}</td>
                                    <td>
                                    @foreach($cek as $v)
                                    <?php if($v->id == $value->id){ ?>
                                    <li>{{ $v->brand }}</li>
                                    <?php } ?>
                                    @endforeach
                                    </td>
                                    <td>
                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$methods['edit'])){?>
                                          <a href="{{ route('jenis_barang.edit',$value->id) }}" class="btn btn-primary dim btn-xs"><i class="fa fa-paste"></i> ubah</a>
                                      <?php }?>

                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$methods['remove'])){?>
                                          <a data-id="{{ $value->id }}" data-url="{{ route('jenis_barang.destroy',$value->id) }}" class="btn btn-danger btn-outline dim btn-xs confirm"><i class="fa fa-trash"></i> hapus</a>
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
              <h4 class="modal-title">Tambah Jenis</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
            {!! Form::open(['url' => url($main_url.'/store'), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
              <div class="form-group {{ ($errors->has('id')?"has-error":"") }}"><label class="col-sm-2 control-label">Kode</label>
                  <div class="col-sm-6 col-xs-12">
                      {!! Form::text('id', null, ['class' => 'form-control']) !!}
                      <!-- {!! Form::hidden('id', null, ['class' => 'form-control']) !!} -->
                      {!! $errors->first('id', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                  </div>
              </div>
              <div class="form-group {{ ($errors->has('jenis')?"has-error":"") }}"><label class="col-sm-2 control-label">Jenis</label>
                  <div class="col-sm-6 col-xs-12">
                      {!! Form::text('jenis', null, ['class' => 'form-control']) !!}
                      <!-- {!! Form::hidden('id', null, ['class' => 'form-control']) !!} -->
                      {!! $errors->first('jenis', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                  </div>
              </div>
              <div class="form-group {{ ($errors->has('bcs_uom_id')?"has-error":"") }}"><label class="col-sm-2 control-label">Satuan</label>
                  <div class="col-sm-6 col-xs-12">
                      {!! Form::select('bcs_uom_id', $optuom, null, ['class' => 'form-control']) !!}
                      <!-- {!! Form::hidden('id', null, ['class' => 'form-control']) !!} -->
                      {!! $errors->first('bcs_uom_id', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                  </div>
              </div> 
              <div class="form-group {{ ($errors->has('id_brand')?"has-error":"") }}"><label class="col-sm-2 control-label">Brand*)</label>
                  <div class="col-sm-4 col-xs-12">
                      {!! Form::select('id_brand[]', $optbrand, null, ['class' => 'form-control select2',"multiple" => "multiple",'required'=>'required']) !!}
                      <span class="help-block">*) untuk pengelompokan barang</span>
                      {!! $errors->first('id_brand', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
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
});
</script>
