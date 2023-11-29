<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Lokasi Gudang/Outlet
            </h2>
        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
      <div class="map-preview" style="height:100vh;" id="map-canvas-full-height">
      </div>
    </div>
    @include('backend.footer')
  </div>
</div>
<script src="https://maps.googleapis.com/maps/api/js?key=<?=$google_api_key?>&map_ids=529d4c3c7fed9d26&libraries=places&v=3.exp"></script>
<script>

    var geocoder;
  	var map;
  	var marker;
  	var pos;
  	var latlng;
  	var map_called = 0;
  	var autocomp;
  	var timeout;
    var latitude, longitude;
    var markers = new Array();

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
        mapId:"529d4c3c7fed9d26"
  		}
  		map = new google.maps.Map(document.getElementById('map-canvas-full-height'), mapOptions);
  	}

    $(document).ready(function(){
      initialize();


      <?php
        if($data){
          foreach ($data as $key => $value) {
            $locations[] = array("<div class='lh-20'>".$value->mitra_name." <strong>[ ".$value->mitra_code." ] </strong>"."<br> ".$value->address." <br><i class='fa fa-phone'></i> ".$value->telp." <br><i class='fa fa-envelope'></i> ".$value->email."<br><br><a href='".url("master/merchant/detail/".$value->id_mitra)."' target='_blank' class='btn btn-primary btn-xs'>lihat detail <i class='fa fa-angle-right'></i></a></div>", $value->latitude, $value->longitude,$key);
          }
        }
      ?>

      var locations = <?=json_encode(@$locations)?>;

      setTimeout(function(){
          var infowindow = new google.maps.InfoWindow();

          var marker, i;

          for (i = 0; i < locations.length; i++) {
            marker = new google.maps.Marker({
              position: new google.maps.LatLng(locations[i][1], locations[i][2]),
              map: map,
              icon: "<?=url('public/templates/admin/img/marker_fremilt_merchant.png')?>",
              //origin: new google.maps.Point(0,39), // origin
              //anchor: new google.maps.Point(0,39) // anchor
            });

            markers[i] = marker;

            google.maps.event.addListener(marker, 'click', (function(marker, i) {
              return function() {
                infowindow.setContent(locations[i][0]);
                infowindow.open(map, marker);
              }
            })(marker, i));
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
