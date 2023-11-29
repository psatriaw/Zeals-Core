@extends('layouts.main')
@section('css')
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('style2/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('style2/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('style2/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('style2/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<!-- DataTables -->
<!-- <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}"> -->
<!-- <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}"> -->
@endsection

@section('title', 'POS BC')




@section('content')
<h3>Laporan Harian</h3>
<br>
<div class="box box-warning">
    <div class="box-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered" id="laporan">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal Penjualan </th>
                        <th>Barang Terjual</th>
                        <th>Total Penjualan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $i=1 @endphp
                   @foreach($laporan as $l)
                    <tr>
                    <td>{{$i++}}</td>
                    <td>{{$l->tanggal}}</td>
                    <td>{{$l->qty}}</td>
                    <td>{{$l->jumlah}}</td>
                    <td>
                        <a class="btn btn-primary btn-sm" href="{{ route('detail_laporan', $l->tanggal) }}"><i class="fa fa-eye"></i></a>
                    </td>
                    </tr>
                   @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
 var table;
 $(document).ready(function() {
     table = $('#laporan').DataTable();
 })
</script>
@section('js')