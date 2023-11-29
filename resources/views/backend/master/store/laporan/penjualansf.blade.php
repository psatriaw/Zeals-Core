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
                <h2>Rekap SF</h2>
                <ol class="breadcrumb">
                    <li>
                        <p>Laporan</p>
                    </li>
                    <li class="active">
                        <strong>SF</strong>
                    </li>
                </ol>
            </div>
    </div> 
    <br>




    <div class="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox">
                  <div class="ibox-title">
                  {!! Form::open(['url' => url($config['main_url']), 'method' => 'get', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
                    <div class="row">
                    <?php if($toko){ ?>
                      <div class="col-lg-3">
                            <input id="toko" name="toko" value="{{ $nama_toko }}" class="form-control input-sm" readonly>
                      </div>
<?php } else { ?> 
                      <div class="col-lg-3">
                      <select class="form-control" name="toko"> 
                          <!-- <option value="">- Pilih -</option> -->
                          @foreach($opttoko as $k)
                          <option value="{{ $k->id }}" <?=($opttoko2== $k->id)?"selected":""?>>{{ $k->nama }}</option>
                          <!-- <option value="{{ $k->id }}" {{ old('toko', $k->id) == $k->id ? 'selected' : '' }}> {{ $k->nama }}</option> -->
                          <!-- <option value="{{ $k->id }}" @if(old('toko') ==  $k->id  )selected @endif >{{ $k->nama }}</option> -->

                          @endforeach

                        </select>
                      </div>
<?php } ?>

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
                    <h3>Rekap Penjualan Sales Force a.n : {{ $login->first_name}}</h3>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="laporan" cellpadding="0">
                            <thead>
                                <th>Tanggal</th>
                                <th>Diskon</th>
                                <th>Total Omset</th>
                            </thead>
                            <tbody>
                            <?php for($i=0;$i<$jml;$i++){?>
                            <tr>
                                <td> {{ $data_date[$i] }}</td>
                                <td>Rp. {{ number_format($data_diskon[$i]) }}</td>
                                <td>Rp. {{ number_format($data_penjualan[$i]) }}</td>
                            </tr>
                             <?php }?>
                            
                           
                            </tbody>
                        </table>
                    </div>
                </div>
        </div>
    </div>


</div>
@include('backend.footer')

</div>
</div>

<script>
    $(document).ready(function() {

        $('#date').daterangepicker({
          locale: {
            format: 'YYYY/MM/DD'
          }
        });

        function setDatePick(val){
          if(val=="monthly"){

          }
        }
    });
</script>
