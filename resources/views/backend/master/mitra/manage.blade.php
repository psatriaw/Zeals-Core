<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<?php
  $main_url     = "master/merchant";
?>
<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Kelola Mitra <span class="text-danger">#<?=$data->mitra_code?></span></h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url($main_url) }}">Mitra</a>
                    </li>
                    <li class="active">
                        <strong>Kelola Mitra</strong>
                    </li>
                </ol>
            </div>
            <div class="col-lg-2">

            </div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                  @include('backend.flash_message')
                  <div class="ibox float-e-margins">
                      <div class="ibox-title">
                          <h5>
                              Ilustrasi posisi mitra baru pada map
                          </h5>
                      </div>
                      <div class="ibox-content">
                        <div class="row">
                          <div class="col-lg-8">
                            <div class="map-preview" id="map-canvas" style="height:350px;">
                            </div>
                          </div>
                          <div class="col-lg-4">
                            <table class="table table-striped table-bordered table-hover dataTables-example" id="datatable">
                              <tr>
                                <th>Nama mitra</th>
                                <td><?=$data->mitra_name?></td>
                              </tr>

                              <tr>
                                <th>Tanggal pengajuan</th>
                                <td><?=date("d M Y H:i:s",$data->time_created)?></td>
                              </tr>

                              <tr>
                                <th>Alamat</th>
                                <td><?=$data->address?></td>
                              </tr>

                              <tr>
                                <th>Email</th>
                                <td><?=$data->email?></td>
                              </tr>

                              <tr>
                                <th>Telp</th>
                                <td><?=$data->telp?></td>
                              </tr>

                              <tr>
                                <th>Status</th>
                                <td><?=strtoupper($data->status)?></td>
                              </tr>
                            </table>
                            <?php if($data->status=="diterima"){ ?>
                              {!! Form::model($data,['url' => url('master/merchant/updateadmin/'.$data->id_mitra), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
                              <div class="alert alert-warning">Mohon memberikan tugas admin produksi pada salah satu atau lebih daftar admin produksi berikut ini</div>
                              <div class="form-group {{ ($errors->has('id_admin')?"has-error":"") }}"><label class="col-sm-4 control-label">Admin Produksi</label>
                                  <div class="col-sm-8 col-xs-12">
                                      {!! Form::select('id_admin[]', $admins, null, ['class' => 'form-control select2',"multiple" => "multiple"]) !!}
                                      {!! $errors->first('id_admin', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                  </div>
                              </div>
                              <div class="hr-line-dashed"></div>
                              <div class="form-group">
                                  <div class="col-sm-12 text-right">
                                    <button class="btn btn-white" type="reset">Reset</button>
                                    <button class="btn btn-primary" type="submit">Simpan</button>
                                  </div>
                              </div>
                            {!! Form::close() !!}
                            <?php } ?>
                          </div>
                        </div>
                      </div>
                  </div>
                </div>

                <div class="col-lg-12">
                  <div class="ibox float-e-margins">
                      <div class="ibox-title">
                          <h5>Potensi cabang sekitar lokasi (+- area <?=$jarak_check?>km)</h5>
                      </div>
                      <div class="ibox-content">
                        <div class="table-responsive">
                          <table class="table table-striped table-bordered table-hover dataTables-example" id="datatable">
                            <thead>
                              <tr>
                                  <th>No.</th>
                                  <th>Nama Cabang</th>
                                  <th>Kode Mitra</th>
                                  <th>Alamat</th>
                                  <th>Jarak</th>
                                  <th>Status</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                                $singgungan = 0;
                                $no = 0;
                                if($lokasi){
                                  foreach ($lokasi as $key => $value) {
                                    $no++;
                                    ?>
                                    <tr>
                                        <td><?=$no?></td>
                                        <td><?=$value->mitra_name?></td>
                                        <td><?=$value->mitra_code?></td>
                                        <td><?=$value->address?></td>
                                        <td><?=number_format($value->distance,3)?> km</td>
                                        <td>
                                          <?=($value->distance<$limit_distance)?"<label class='label label-danger'>Bersinggungan</label>":"<label class='label label-success'>Aman</label>"?>
                                        </td>
                                    </tr>
                                    <?php
                                    if($value->distance<$limit_distance){
                                      $singgungan++;
                                    }
                                  }
                                }else{
                                  ?>
                                  <tr>
                                    <td colspan="6">Tidak ada data mitra disekitar lokasi</td>
                                  </tr>
                                  <?php
                                }
                              ?>
                            </tbody>
                          </table>
                        </div>
                      </div>
                  </div>
                </div>
            </div>
            <div class="row bottom30">
                <?php if($data->status=="pending"){ ?>
                  <?php $singgungan = 0;?>
                  <?php if($singgungan>0){ ?>
                    <div class="col-sm-12">
                      <p class="alert alert-warning">Kemitraan tidak dapat diproses karena adanya singgungan dengan mitra lain yang sudah terlebih dahulu terdaftar. Batas singgungan antar mitra adalah <?=$limit_distance?>km. Mohon mengganti besaran batas singgungan pada <a href="{{ url('master/pengaturan') }}"> pengaturan</a> jika ingin tetap memproses mitra baru ini.</p>
                    </div>
                  <?php } ?>
                <?php }?>
                <div class="col-sm-12">
                  <a type="button" class="btn btn-white" href="{{ url('master/merchant') }}"><i class="fa fa-angle-left"></i> Kembali</a>

                  <?php if($data->status=="pending"){ ?>
                  <a type="button" class="btn btn-primary pull-right confirm <?=($singgungan>0)?"disabled":""?>" <?=($singgungan>0)?"disabled":""?> data-url="{{ url('master/merchant/terima/') }}" data-id="<?=$data->id_mitra?>">Proses Kemitraan <i class="fa fa-angle-right"></i></a>
                  <a type="button" class="btn btn-danger pull-right mgr5 confirm" data-url="{{ url('master/merchant/tolak/') }}" data-id="<?=$data->id_mitra?>">Tolak <i class="fa fa-times"></i></a>
                  <?php } ?>
                </div>
            </div>
        </div>
    @include('backend.footer')
    @include('backend.do_confirm')
  </div>
</div>
<script src="https://maps.googleapis.com/maps/api/js?key=<?=$google_api_key?>&libraries=places&v=3.exp"></script>
<script>
  $(document).ready(function() {
    $('.thetarget, .thesender').select2({
      ajax: {
        url: '{{ url("get-list-user") }}',
        dataType: 'json',
        data: function (params) {
          var query = {
            search: params.term,
            type: 'public'
          }
          // Query parameters will be ?search=[term]&type=public
          return query;
        }
      }
    });
    $("#id_user").html('<option value="<?=$data->id_user?>" selected><?=$data->first_name?></option>');
    $(".select2-selection__rendered").html("<?=$data->first_name?>");
  });

  var geocoder;
  var map;
  var marker;
  var pos;
  var latlng;
  var map_called = 0;
  var autocomp;
  var timeout;
  var latitude, longitude;
  var id_target = <?=$data->id_mitra?>;
  var markers = new Array();

  <?php
    $base_city_coor = explode(",",$base_city_coor);
    echo "latitude  = ".$data->latitude.";";
    echo "longitude = ".$data->longitude.";";
  ?>

  function initialize() {
    latlng = new google.maps.LatLng(latitude, longitude);

    geocoder = new google.maps.Geocoder();
    var mapOptions = {
      zoom: 14,
      center: latlng,

    }
    map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
  }

  $(document).ready(function(){
    initialize();

    $(document).ready(function() {
      $('.select2').select2();
    });

    <?php
      $locations[] = array("<div class='lh-20'><span class='text-danger'><strong>[TARGET]</strong></span><br>".$data->mitra_name." <strong>[ ".$data->mitra_code." ] </strong>"."<br> ".$data->address." <br><br><a href='".url("master/merchant/detail/".$data->id_mitra)."' target='_blank' class='btn btn-primary btn-xs'>lihat detail <i class='fa fa-angle-right'></i></a></div>", $data->latitude, $data->longitude,$data->id_mitra);

      if($lokasi){
        foreach ($lokasi as $key => $value) {
          $locations[] = array("<div class='lh-20'>".$value->mitra_name." <strong>[ ".$value->mitra_code." ]</strong> "."<br> ".$value->address." <br><br><a href='".url("master/merchant/detail/".$value->id_mitra)."' target='_blank' class='btn btn-primary btn-xs'>lihat detail <i class='fa fa-angle-right'></i></a></div>", $value->latitude, $value->longitude,$value->id_mitra);
        }
      }
    ?>

    var locations = <?=json_encode(@$locations)?>;

    setTimeout(function(){
        var infowindow = new google.maps.InfoWindow();

        var marker, i;

        for (i = 0; i < locations.length; i++) {
          if(id_target==locations[i][3]){
            marker = new google.maps.Marker({
              position: new google.maps.LatLng(locations[i][1], locations[i][2]),
              map: map
            });
          }else{
            marker = new google.maps.Marker({
              position: new google.maps.LatLng(locations[i][1], locations[i][2]),
              map: map,
              icon: "<?=url('public/templates/admin/img/marker_fremilt_merchant.png')?>",
            });
          }

          markers[i] = marker;

          google.maps.event.addListener(marker, 'click', (function(marker, i) {
            return function() {
              infowindow.setContent(locations[i][0]);
              infowindow.open(map, marker);
            }
          })(marker, i));

          if(id_target==locations[i][3]){
            infowindow.setContent(locations[i][0]);
            infowindow.open(map, marker);
          }
        }

        var bounds = new google.maps.LatLngBounds();

        for (var i=0; i<markers.length; i++) {
            if(markers[i].getVisible()) {
                bounds.extend( markers[i].getPosition() );
            }
        }

        map.fitBounds(bounds);

    },1000);
  });
</script>
