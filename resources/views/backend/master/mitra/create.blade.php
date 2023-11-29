<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<?php
  $main_url = "master/merchant";
?>
<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Gudang/Pabrik</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url($main_url) }}">Gudang/Pabrik</a>
                    </li>
                    <li class="active">
                        <strong>Tambah Gudang/Pabrik</strong>
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
                        <h5>Tambah Gudang/Pabrik</h5>
                    </div>
                    <div class="ibox-content">
                      @if ($errors->any())
                          <div class="alert alert-danger">
                              Ada kesalahan! mohon cek formulir.
                              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                          </div>
                      @endif
                      @include('backend.flash_message')
                      {!! Form::open(['url' => url($main_url.'/store'), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}

                        <div class="form-group {{ ($errors->has('mitra_name')?"has-error":"") }}"><label class="col-sm-2 control-label">Nama Gudang/Pabrik/Outlet</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::text('mitra_name', null, ['class' => 'form-control']) !!}
                                {!! $errors->first('mitra_name', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('tipe')?"has-error":"") }}"><label class="col-sm-2 control-label">Tipe</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::select('tipe', array('gudang' => 'Gudang', 'pabrik' => 'Pabrik','outlet' => 'Outlet'), null,['class' => 'form-control','id' => 'tipe']) !!}
                                {!! $errors->first('tipe', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('id_user')?"has-error":"") }}"><label class="col-sm-2 control-label">Akun PIC</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::select('id_user', array(), null, ['class' => 'form-control thetarget','rows' => '3']) !!}
                                {!! $errors->first('id_user', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('email')?"has-error":"") }}"><label class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::email('email', null, ['class' => 'form-control']) !!}
                                {!! $errors->first('email', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('telp')?"has-error":"") }}"><label class="col-sm-2 control-label">No. Telp</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::text('telp', null, ['class' => 'form-control']) !!}
                                {!! $errors->first('telp', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('address')?"has-error":"") }}"><label class="col-sm-2 control-label">Alamat</label>
                            <div class="col-sm-10 col-xs-12">
                                {!! Form::textarea('address', null, ['class' => 'form-control', 'id' => 'address', 'rows' => 2]) !!}
                                {!! $errors->first('address', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}

                                <input type="hidden" id="current_location" name="current_location">
                                <input type="hidden" id="the_lat" name="latitude">
                                <input type="hidden" id="the_long" name="longitude">

                                <div class="map-preview top5" id="map-canvas">
                                </div>
                                <p class="help-block">Drag icon lokasi berwarna merah didalam peta untuk mengarahkan lokasi alamat jika posisi dalam peta belum sesuai.</p>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Landmark</label>
                            <div class="col-sm-4 col-xs-12">
                              <textarea class="form-control" placeholder="Landmark/penjelasan posisi tempat, misal:  Sebelah kantor telkom solo" requireds="" name="landmark" id="landmark" data-placement="right" rows="3"></textarea>
                            </div>
                        </div>
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
                </div>
            </div>
        </div>
    @include('backend.footer')
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

  <?php
    $base_city_coor = explode(",",$base_city_coor);
    echo "latitude  = ".$base_city_coor[0].";";
    echo "longitude = ".$base_city_coor[1].";";
  ?>

  function initialize() {
    latlng = new google.maps.LatLng(latitude, longitude);

    geocoder = new google.maps.Geocoder();
    var mapOptions = {
      zoom: 14,
      center: latlng,
      disableDefaultUI: true
    }
    map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

    marker = new google.maps.Marker({
      map: map,
      draggable: true,
      animation: google.maps.Animation.DROP,
      position: latlng
    });

    google.maps.event.addListener(marker, 'dragend', function(e) {
      var poss = e.latLng;
      var post = (JSON.parse(JSON.stringify(poss)));
      latitude = post.lat;
      longitude = post.lng;
      $("#current_location").val(e.latLng);
      $("#the_lat").val(post.lat);
      $("#the_long").val(post.lng);
    });
  }

  function setMapCoordinate(latit, longit) {
    latitude  = latit;
    longitude = longit;

    var thecenter = new google.maps.LatLng(latitude, longitude);
    map.setCenter(thecenter);
    marker.setPosition(thecenter);
    jQuery("#current_location").val(thecenter);
    jQuery("#the_lat").val(latitude);
    jQuery("#the_long").val(longitude);

    map.setZoom(18);

    google.maps.event.addListener(marker, 'dragend', function(e) {
      var poss = e.latLng;
      var post = (JSON.parse(JSON.stringify(poss)));
      latitude = post.lat;
      longitude = post.lng;
      $("#current_location").val(e.latLng);
      $("#the_lat").val(post.lat);
      $("#the_long").val(post.lng);
    });
  }

  $("#address").keyup(function(e){
    clearTimeout(timeout);
    var alamat = $(this).val();
    timeout = setTimeout(function() {
      getCoordinate(alamat);
    }, 500);
  });

  function getCoordinate(alamat) {
    $.ajax({
        url: "<?=url("get-address-coordinate")?>",
        type: "POST",
        data: {
          address: alamat,
          _token:$("input[name='_token']").val()
        }
      })
      .done(function(result) {
        setMapCoordinate(result.latitude, result.longitude);
      })
      .fail(function(msg) {
        alert("Gagal memuat peta");
      });
  }

  $(document).ready(function(){
    initialize();
    $("#map-canvas").tooltip();
  });
</script>
