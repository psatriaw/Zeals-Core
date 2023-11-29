@extends('layouts.main')
<link rel="stylesheet" href="{{ asset('style2/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('style2/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">


@section('title', 'POS BC')




@section('content')
<div class="content mt-3">
    <div class="animated fadeIn">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 offset-md-4">
                                <form action="{{ route('tambahan.update', [$tambahan->id]) }}" method="POST">
                                    {{ csrf_field() }}
                                    @method('PUT')
                                    <div class="form-group">
                                        <label> Nama</label>
                                        <input type="text" name="nama" class="form-control" value="{{ $tambahan->nama }}" autofocus required>
                                    </div>
                                    <div class="form-group">
                                        <label> Harga Area 1</label>
                                        <input type="text" name="harga1" class="form-control" value="{{ $tambahan->harga1 }}" autofocus required>
                                    </div>
                                    <div class="form-group">
                                        <label> Harga Area 2</label>
                                        <input type="text" name="harga2" class="form-control" value="{{ $tambahan->harga2  }}" autofocus required>
                                    </div>
                                    <button type="submit" class="btn btn-success">Simpan</button>  
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
    </div>
</div>



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