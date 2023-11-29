<?php
  $main_url      = $config['main_url'];
  $methods       = $config;
?>
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('style2/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('style2/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('style2/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('style2/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">

<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Uang_real</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li class="active">
                        <strong>Uang_real</strong>
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

        <div class="wrapper wrapper-content animated fadeInRight">

        <div class="ibox">
            <div class="ibox">
                <div class="ibox-title">
                    <i class="fas fa-cogs mr-1"></i>
                    Tambah Uang_real <p>(Peringatan Data yang sudah dibuat tidak bisa dihapus)</p>
                </div>
                <div class="ibox-content">
                {!! Form::open(['url' => url($main_url.'/store'), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}

                <div class="form-group {{ ($errors->has('tanggal')?"has-error":"") }}"><label class="col-sm-2 control-label">Tanggal</label>
                    <div class="col-sm-6 col-xs-12">
                        <input type="date" name="tanggal" class="form-control" value="{{ $today }}" autofocus required>
                        <input type="hidden" name="toko_id" class="form-control" value="{{ $opttoko2 }}" autofocus required>
                        {!! $errors->first('tanggal', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group {{ ($errors->has('uang')?"has-error":"") }}"><label class="col-sm-2 control-label">Uang (Rp)</label>
                    <div class="col-sm-6 col-xs-12">        
                        <input type="number" name="uang" class="form-control" autofocus required>
                        {!! $errors->first('uang', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                    </div>
                </div>
                    <!-- <button type="submit" class="btn btn-success">Simpan</button>   -->
                    <div class="hr-line-dashed"></div> 
                <div class="form-group">
                    <div class="col text-right">
                        <button class="btn btn-primary text-right" type="submit">Simpan</button>
                    </div>
                </div>
                {!! Form::close() !!}
                </div>
            </div>
        </div>

            <div class="row">
                <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Uang_real</h5>
                        <!-- <?php if($previlege->isAllow($login->id_user,$login->id_department,$methods['create'])){?>
                        <div class="ibox-tools">
                            <button type="button" class="float-right btn btn-primary" data-toggle="modal" data-target="#modal-default">
                              Tambah Uang_real
                            </button>
                        </div>
                        <?php } ?> -->
                    </div>
            <!-- /.card-header -->
            <div class="ibox-content">
            @include('backend.flash_message')
            <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example" id="datatable">
                          <thead>
                            <tr>
                                <th>Tanggal </th>
                                <th>Jumlah (Rp) </th>
                                <th>Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($uang_real as $k)
                        <tr>
                            <td>{{ $k->tanggal }}</td>
                            <td>Rp. {{ number_format($k->uang) }}</td>
                            <td>
                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$methods['edit'])){?>
                                          <a href="{{ route('uang_real.edit',$k->id) }}" class="btn btn-primary dim btn-xs"><i class="fa fa-paste"></i> ubah</a>
                                      <?php }?>

                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$methods['remove'])){?>
                                          <a data-id="{{ $k->id }}" data-url="{{ route('uang_real.destroy',$k->id) }}" class="btn btn-danger btn-outline dim btn-xs confirm"><i class="fa fa-trash"></i> hapus</a>
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
        </div>
    @include('backend.do_confirm')
    @include('backend.footer')
    @include('backend.master.purchase.modal_material_add')
  </div>
</div> 
