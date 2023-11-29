<?php
  $main_url      = $config['main_url'];
  $methods       = $config;
?>
<link rel="stylesheet" href="{{ asset('style2/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('style2/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Brand</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li class="active">
                        <strong>Brand</strong>
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
                          <!-- <option value="{{ $k->id }}" {{ old('toko', $k->id) == $k->id ? 'selected' : '' }}> {{ $k->nama }}</option> -->
                          <!-- <option value="{{ $k->id }}" @if(old('toko') ==  $k->id  )selected @endif >{{ $k->nama }}</option> -->

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
                        <h5>Brand</h5>
                        <?php if($previlege->isAllow($login->id_user,$login->id_department,$methods['create'])){?>
                        <div class="ibox-tools">
                            <button type="button" class="float-right btn btn-primary" data-toggle="modal" data-target="#modal-default">
                              Tambah Brand
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
                                <th>Brand </th>
                                <th>Toko</th>
                                <th>Opsi</th>
                            </tr>
                          </thead>
                          <tbody>
                          @php $i=1 @endphp
                           @foreach($data1 as $value)
                                  <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $value->brand }}</td>
                                    @if($value->store_outlet)
                                    <td>{{ $value->store_outlet->nama }}</td>
                                    @else
                                    <td>Semua Toko</td>
                                    @endif
                                    <td>
                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$methods['edit'])){?>
                                          <a href="{{ route('brand.edit',$value->id) }}" class="btn btn-primary dim btn-xs"><i class="fa fa-paste"></i> ubah</a>
                                      <?php }?>

                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$methods['remove'])){?>
                                          <a data-id="{{ $value->id }}" data-url="{{ route('brand.destroy',$value->id) }}" class="btn btn-danger btn-outline dim btn-xs confirm"><i class="fa fa-trash"></i> hapus</a>
                                      <?php }?>
                                    </td>
                                  </tr>
                            @endforeach
                           @foreach($data as $value)
                                  <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $value->brand }}</td>
                                    @if($value->store_outlet)
                                    <td>{{ $value->store_outlet->nama }}</td>
                                    @else
                                    <td>Semua Toko</td>
                                    @endif
                                    <td>
                                    @if($toko)
                                    @else
                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$methods['edit'])){?>
                                          <a href="{{ route('brand.edit',$value->id) }}" class="btn btn-primary dim btn-xs"><i class="fa fa-paste"></i> ubah</a>
                                      <?php }?>

                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$methods['remove'])){?>
                                          <a data-id="{{ $value->id }}" data-url="{{ route('brand.destroy',$value->id) }}" class="btn btn-danger btn-outline dim btn-xs confirm"><i class="fa fa-trash"></i> hapus</a>
                                      <?php }?>
                                      @endif
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
              <h4 class="modal-title">Tambah Brand</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
            {!! Form::open(['url' => url($main_url.'/store'), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
              <div class="form-group {{ ($errors->has('brand')?"has-error":"") }}"><label class="col-sm-2 control-label">Brand</label>
                  <div class="col-sm-6 col-xs-12">
                      {!! Form::text('brand', null, ['class' => 'form-control']) !!}
                      {!! Form::hidden('id', null, ['class' => 'form-control']) !!}
                      {!! $errors->first('brand', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                  </div>
              </div>
              <?php if($toko){ ?>
                <div class="form-group {{ ($errors->has('brand')?"has-error":"") }}"><label class="col-sm-2 control-label"></label>
                  <div class="col-sm-6 col-xs-12">
                      {!! Form::hidden('toko_id', $id_toko, ['class' => 'form-control']) !!}
                  </div>
              </div>
              <?php } else { ?>
              <div class="form-group {{ ($errors->has('brand')?"has-error":"") }}"><label class="col-sm-2 control-label">Brand</label>
                  <div class="col-sm-6 col-xs-12">
                    <select class="form-control" name="toko_id"> 
                          @foreach($opttoko as $k)
                          <option value="{{ $k->id }}" <?=($opttoko2== $k->id)?"selected":""?>>{{ $k->nama }}</option>
                          @endforeach
                          <option value="0">Semua Toko</option>
                      </select>
                  </div>
              </div>
              <?php } ?>
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

