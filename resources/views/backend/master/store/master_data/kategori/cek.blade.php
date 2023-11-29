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
                                <form action="{{ route('kategori.update', [$kategori->id]) }}" method="POST">
                                    {{ csrf_field() }}
                                    @method('PUT')
                                    <div class="form-group">
                                        <label> Kategori</label>
                                        <input type="text" name="kategori" class="form-control" value="{{ $kategori->kategori }}" autofocus required>
                                    </div>
                                    <button type="submit" class="btn btn-success">Simpan</button>  
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
    </div>
</div>




@endsection 