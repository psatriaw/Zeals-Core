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
                <h2>Rumus Konversi</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li class="active">
                        <strong>Rumus Konversi</strong>
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
                        <h5>Kategori</h5>
                        <?php if($previlege->isAllow($login->id_user,$login->id_department,$methods['create'])){?>
                        <div class="ibox-tools">
                            <button type="button" class="float-right btn btn-primary" data-toggle="modal" data-target="#modal-default">
                              Tambah Rumus
                            </button>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="ibox-content">
                    @include('backend.flash_message')
                    <div class="table-responsive">
                        <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Nama Rumus</th>
                                        <th>Dari</th>
                                        <th>Qty</th>
                                        <th>Ke</th>
                                        <th>Qty</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody> 
                                <tr>
                                @foreach($rumus_konversi as $k)
                                    <td>{{ $k->nama_rumus }}</td>
                                    <td>{{ $k->bahan[0]->nama }}</td>
                                    <td>{{ $k->qty1 }}</td>
                                    <td>{{ $k->hasil[0]->nama }}</td>
                                    <td>{{ $k->qty2 }}</td>
                                    <td>
                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$methods['edit'])){?>
                                          <a href="{{ route('rumus_konversi.edit',$k->id) }}" class="btn btn-primary dim btn-xs"><i class="fa fa-paste"></i> ubah</a>
                                      <?php }?>

                                      <?php if($previlege->isAllow($login->id_user,$login->id_department,$methods['remove'])){?>
                                          <a data-id="{{ $k->id }}" data-url="{{ route('rumus_konversi.destroy',$k->id) }}" class="btn btn-danger btn-outline dim btn-xs confirm"><i class="fa fa-trash"></i> hapus</a>
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


<!-- MODAL -->
    <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Tambah Rumus Konversi</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div> 
            <div class="modal-body">
            {!! Form::open(['url' => url($main_url.'/store'), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
            <div class="form-group {{ ($errors->has('nama_rumus')?"has-error":"") }}">
                    <label> Nama Rumus</label>
                    <input type="text" name="nama_rumus" class="form-control" autofocus required>
                    {!! $errors->first('nama_rumus', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!} 
                </div>
                <div class="form-group {{ ($errors->has('dari')?"has-error":"") }}">
                    <label> Dari</label>
                    <select type="text" name="dari" class="form-control" autofocus required>
                    <option value="">- Pilih -</option>
                    @foreach($barang as $b)
                    <option value="{{ $b->id }}">{{ $b->nama  }}</option>
                    @endforeach
                    </select>
                    {!! $errors->first('dari', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!} 
                </div>
                <div class="form-group {{ ($errors->has('qty1')?"has-error":"") }}">
                    <label> Qty</label>
                    <input type="number" name="qty1" class="form-control" autofocus required>
                    {!! $errors->first('qty1', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!} 
                </div>
                <div class="form-group {{ ($errors->has('ke')?"has-error":"") }}">
                    <label> Ke</label>
                    <select type="text" name="ke" class="form-control" autofocus required>
                    <option value="">- Pilih -</option>
                    @foreach($barang as $b)
                    <option value="{{ $b->id }}">{{ $b->nama  }}</option>
                    @endforeach
                    </select>
                    {!! $errors->first('ke', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!} 
                </div>
                <div class="form-group {{ ($errors->has('qty2')?"has-error":"") }}">
                    <label> Qty</label>
                    <input type="text" name="qty2" class="form-control" autofocus required>
                    {!! $errors->first('qty2', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!} 
                </div>
                <!-- <button type="submit" class="btn btn-success">Simpan</button>   -->
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

 