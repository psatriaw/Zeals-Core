<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Shop Setting</h2>
            </ol>
        </div>
        <div class="col-lg-2">

        </div>

    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
      @include('backend.master.shop.alert_activation',array("login" => $login, "data_shop" => $data_shop))
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Setting</h5>
                    </div>
                    <div class="ibox-content">
                      @if ($errors->any())
                          <div class="alert alert-danger">
                              Oops! Please check your input.
                              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                          </div>
                      @endif
                      @include('backend.flash_message')
                      {!! Form::model($data,['url' => url('master/shop/setting/update/'), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
                        <div class="form-group {{ ($errors->has('shop_name')?"has-error":"") }}"><label class="col-sm-2 control-label">Shop name</label>
                            <div class="col-sm-10 col-xs-12">
                                {!! Form::text('shop_name', $data->shop_name, ['class' => 'form-control']) !!}
                                {!! Form::hidden('id_shop', $data->id_shop, ['class' => 'form-control']) !!}
                                {!! $errors->first('shop_name', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group {{ ($errors->has('phone')?"has-error":"") }}"><label class="col-sm-2 control-label">Phone Number</label>
                            <div class="col-sm-10 col-xs-12">
                                {!! Form::text('phone', null, ['class' => 'form-control']) !!}
                                {!! $errors->first('phone', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group {{ ($errors->has('email')?"has-error":"") }}"><label class="col-sm-2 control-label">Email Address</label>
                            <div class="col-sm-10 col-xs-12">
                                {!! Form::text('email', null, ['class' => 'form-control']) !!}
                                {!! $errors->first('email', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group {{ ($errors->has('description')?"has-error":"") }}"><label class="col-sm-2 control-label">Shop description</label>
                            <div class="col-sm-10 col-xs-12">
                                {!! Form::textarea('description',$data->description, ['class' => 'form-control', 'rows' => 4]) !!}
                                {!! $errors->first('description', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group {{ ($errors->has('country_code')?"has-error":"") }}"><label class="col-sm-2 control-label">Shop location</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::select('country_code', $countries, $data->country_code, ['class' => 'form-control']) !!}
                                {!! $errors->first('country_code', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>

                        <div class="form-group {{ ($errors->has('address')?"has-error":"") }}"><label class="col-sm-2 control-label">Address</label>
                            <div class="col-sm-10 col-xs-12">
                                {!! Form::textarea('address',$data->address, ['class' => 'form-control ', 'rows' => 4,'id' => 'address']) !!}
                                {!! $errors->first('address', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                <div class="map-preview" id="map-canvas"></div>
                                <p>please drag n drop the marker inside the map if your position is incorrect.</p>
                            </div>
                            <input type="hidden" id="current_location" name="current_location">
                        </div>
                        <div class="form-group {{ ($errors->has('longitude')?"has-error":"") }}"><label class="col-sm-2 control-label">Coordinate location</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::text('longitude',$data->longitude, ['class' => 'form-control disabled', 'readonly' => 'readonly', 'rows' => 4, 'id' => 'the_long']) !!}
                                {!! $errors->first('longitude', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::text('latitude',$data->latitude, ['class' => 'form-control disabled', 'readonly' => 'readonly', 'rows' => 4,'id' => 'the_lat']) !!}
                                {!! $errors->first('latitude', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>

                        <div class="form-group {{ ($errors->has('shipping_include_state')?"has-error":"") }}"><label class="col-sm-2 control-label">Shipping for same state</label>
                            <div class="col-sm-4 col-xs-12">
                              <div class="input-group">
                                <span class="input-group-addon">AUD </span>
                                {!! Form::number('shipping_include_state',$data->shipping_include_state, ['class' => 'form-control', 'rows' => 4]) !!}
                              </div>
                                {!! $errors->first('shipping_include_state', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                            <div class="col-sm-4 col-xs-12">
                              <div class="input-group">
                                <span class="input-group-addon">Days </span>
                                {!! Form::number('shipping_include_state_time',$data->shipping_include_state_time, ['class' => 'form-control', 'rows' => 4]) !!}
                              </div>
                                {!! $errors->first('shipping_include_state_time', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group {{ ($errors->has('shipping_exclude_state')?"has-error":"") }}"><label class="col-sm-2 control-label">Shipping for different state</label>
                            <div class="col-sm-4 col-xs-12">
                              <div class="input-group">
                                <span class="input-group-addon">AUD </span>
                                {!! Form::number('shipping_exclude_state',$data->shipping_exclude_state, ['class' => 'form-control', 'rows' => 4]) !!}
                                </div>
                                {!! $errors->first('shipping_exclude_state', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                            <div class="col-sm-4 col-xs-12">
                              <div class="input-group">
                                <span class="input-group-addon">Days </span>
                                {!! Form::number('shipping_exclude_state_time',$data->shipping_exclude_state_time, ['class' => 'form-control', 'rows' => 4]) !!}
                                </div>
                                {!! $errors->first('shipping_exclude_state_time', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">

                            </div>
                            <div class="col-sm-6 text-right">
                              <button class="btn btn-white" type="reset">RESET</button>
                              <button class="btn btn-primary" type="submit">SAVE CHANGES</button>
                            </div>
                        </div>
                      {!! Form::close() !!}
                    </div>
                </div>

                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Bank Account</h5>
                    </div>
                    <div class="ibox-content">
                      @if ($errors->any())
                          <div class="alert alert-danger">
                              Oops! Please check your input.
                              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                          </div>
                      @endif
                      @include('backend.flash_message')
                      {!! Form::model($data,['url' => url('master/shop/setting/bank/update/'), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
                        <div class="form-group {{ ($errors->has('bank_name')?"has-error":"") }}"><label class="col-sm-2 control-label">Bank name</label>
                            <div class="col-sm-10 col-xs-12">
                                {!! Form::text('bank_name', $data->bank_name, ['class' => 'form-control']) !!}
                                {!! Form::hidden('id_shop', $data->id_shop, ['class' => 'form-control']) !!}
                                {!! $errors->first('bank_name', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group {{ ($errors->has('account_name')?"has-error":"") }}"><label class="col-sm-2 control-label">Account name</label>
                            <div class="col-sm-10 col-xs-12">
                                {!! Form::text('account_name', $data->account_name, ['class' => 'form-control']) !!}
                                {!! $errors->first('account_name', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>

                        <div class="form-group {{ ($errors->has('account_number')?"has-error":"") }}"><label class="col-sm-2 control-label">Account number</label>
                            <div class="col-sm-10 col-xs-12">
                                {!! Form::text('account_number', $data->account_number, ['class' => 'form-control']) !!}
                                {!! $errors->first('account_number', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">

                            </div>
                            <div class="col-sm-6 text-right">
                              <button class="btn btn-white" type="reset">RESET</button>
                              <button class="btn btn-primary" type="submit">SAVE CHANGES</button>
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
    function gotoID(id){
      $('html, body').animate({
				scrollTop: $("#"+id).offset().top - 200
			}, 500);
    }

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
      if($data->longitude!=""){
        echo "latitude  = ".$data->latitude.";";
        echo "longitude = ".$data->longitude.";";
      }else{
      $base_city_coor = explode(",",$base_city_coor);
        echo "latitude  = ".$base_city_coor[0].";";
        echo "longitude = ".$base_city_coor[1].";";
      }
    ?>

    function initialize() {
  		latlng = new google.maps.LatLng(latitude, longitude);

  		geocoder = new google.maps.Geocoder();
  		var mapOptions = {
  			zoom: 18,
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
