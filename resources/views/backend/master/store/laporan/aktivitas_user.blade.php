<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" href="{{ asset('style2/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('style2/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('style2/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('style2/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<?php
  $main_url      = $config['main_url'];
  $methods       = $config;
?>
<!-- Select2 -->
<!-- <link rel="stylesheet" href="{{ asset('style2/plugins/daterangepicker/daterangepicker.css') }}"> -->

<!-- DataTables -->
<!-- <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}"> -->
<!-- <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}"> -->
<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
  
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Aktivitas User</h2>
                <ol class="breadcrumb">
                    <li>
                        <p>Laporan</p>
                    </li>
                    <li class="active">
                        <strong>Aktivitas User</strong>
                    </li>
                </ol>
            </div>
    </div> 
    <br>

    <!-- <div class="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox">
                  <div class="ibox-title">
                  {!! Form::open(['url' => url($config['main_url']), 'method' => 'get', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
                    <div class="row">
                      <div class="col-lg-4">
                        <div class="ibox-tools">
                            <input type="text" class="form-control-sm form-control" id="date" name="dates" value="<?=$dates?>">
                        </div>
                      </div>
                      <div class="col-lg-2">
                        <button class="btn btn-primary btn-block">
                            <i class="fa fa-search"></i> filter
                        </button>
                      </div>
                    </div>
                    {!! Form::close() !!}
                  </div>
                </div>
            </div>
        </div>           
    </div>

   
    
<div class="row">
    <div class="col-md-12">
        <div class="ibox">
                <div class="ibox-title">
                    <h3>Aktivitas User</h3>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="laporan" cellpadding="0">
                            <thead>
                                <th>No</th>
                                <th>User</th>
                                <th>Total Penjualan</th>
                            </thead>
                            <tbody>
                            @php $i=1 @endphp
                            @foreach($omset_user as $o)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $o->first_name }}</td>
                                <td>Rp {{ number_format($o->jumlah) }}</td>
                            
                            </tr>
                           @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
        </div>
    </div>


</div> -->
<div class="ibox">
    <div class="ibox-title">
    
    <h1>
    <center>
    COMING SOON
    </center>
    </div>
    </h1>

</div>
@include('backend.footer')

</div>
</div>

<script>
    $(document).ready(function() {

        $('#date').daterangepicker({
          locale: {
            format: 'YYYY-MM-DD'
          },
          singleDatePicker: true,
        });
    });
</script>
