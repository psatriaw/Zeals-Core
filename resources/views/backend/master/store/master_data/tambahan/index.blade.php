@extends('layouts.main')
<link rel="stylesheet" href="{{ asset('style2/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('style2/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">


@section('title', 'POS BC')




@section('content')
<div class="content mt-3">
    <div class="animated fadeIn">
        @if(session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
        @endif
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Tambahan</h3>
                <button type="button" class="float-right btn btn-primary" data-toggle="modal" data-target="#modal-default">
                  Tambah Tambahan
                </button>
                <!-- <a class="pull-right btn btn-primary" href="{{ route('tambahan.create') }}"> + Tambah Tambahan</a> -->
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example2" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Nama </th>
                                <th>Harga Area 1 </th>
                                <th>Harga Area 2 </th>
                                <th>Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <tr>
                        @foreach($tambahan as $k)
                            <td>{{ $k->nama }}</td>
                            <td>{{ $k->harga1 }}</td>
                            <td>{{ $k->harga2 }}</td>
                            <td>
                                <a class="btn btn-warning btn-sm" href="{{ route('tambahan.edit', $k->id) }}"><i class="fa fa-edit"></i></a>

                                <form action="{{ route('tambahan.destroy', $k->id) }}" method="post" class=d-inline
                                    onsubmit="return confirm('Yakin hapus data?')">
                                        @method('DELETE') 
                                        @csrf
                                        <button class="btn btn-danger btn-sm">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                </form>

                            </td>
                        </tr>
                        @endforeach
                        </tbody>    
                </table>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
</div>

<!-- MODAL -->
    <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Tambah Tambahan</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action="{{ route('tambahan.store') }}" method="POST">
                {{ csrf_field() }}
                <div class="form-group">
                    <label> Nama </label>
                    <input type="text" name="nama" class="form-control" autofocus required>
                </div>
                <div class="form-group">
                    <label> Harga Area 1 </label>
                    <input type="text" name="harga1" class="form-control" autofocus required>
                </div>
                <div class="form-group">
                    <label> Harga Area 2 </label>
                    <input type="text" name="harga2" class="form-control" autofocus required>
                </div>
                <!-- <button type="submit" class="btn btn-success">Simpan</button>   -->
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save changes</button>
            </form>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->


<!-- DataTables -->
<script src="{{ asset('style2/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('style2/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('style2/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('style2/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('style2/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- page script -->
<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true,
      "autoWidth": false,
    });
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": true,
      "responsive": true,
    });
  });
</script>
@endsection 