<link href="<?php echo e(url('templates/admin/css/plugins/iCheck/custom.css')); ?>" rel="stylesheet">
<style>
  .form-control, .btn{
    height: 52px;
  }
  .input-group-addon {
    border: 0px;
  }
</style>
<div class="middle-box text-center loginscreen   animated fadeInDown">
    <div>
        <div>
            <img src="<?=url("templates/admin/img/logo.jpg")?>" class="logo">
        </div>
        <h3>Pendaftaran</h3>
        <p>Gabung bersama komunitas kami, dan dapatkan penghasilan maksimal mu sekarang!. Lengkapi data dalam form dibawah ini untuk bergabung.</p>
        <form class="m-t text-left" role="form" action="<?php echo e(url('register-submit')); ?>" method="POST" id="registerForm">
            <input type="hidden" id="token" name="_token" value="<?php echo e(csrf_token()); ?>">
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Nama Depan" requireds="" name="first_name" id="first_name" data-placement="right">
                <div class="alert alert-danger" style="display:none;margin-top:5px;" id="alert-first_name"></div>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Nama Belakang" requireds="" name="last_name" id="last_name" data-placement="right">
                <div class="alert alert-danger" style="display:none;margin-top:5px;" id="alert-first_name"></div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-addon">
                      <div class="dropdown show">
                        <a class=" dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <img src="https://flagcdn.com/16x12/id.png"> +62
                        </a>

                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                          <a class="dropdown-item" href="#"><img src="https://flagcdn.com/16x12/id.png"> +62</a>
                          <a class="dropdown-item" href="#"><img src="https://flagcdn.com/16x12/id.png"> +62</a>
                        </div>
                      </div>
                    </div>
                    <input type="text" class="form-control" placeholder="0000" requireds="" name="telp" id="telp" data-placement="right">
                </div>
                <div class="alert alert-danger" style="display:none;margin-top:5px;" id="alert-telp"></div>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Email" requireds="" name="email" id="email" data-placement="right">
                <div class="alert alert-danger" style="display:none;margin-top:5px;" id="alert-email"></div>
            </div>

            <div class="form-group">
                <input type="text" class="form-control" placeholder="Username" requireds="" name="username" id="username" data-placement="right">
                <div class="alert alert-danger" style="display:none;margin-top:5px;" id="alert-username"></div>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" placeholder="Password" requireds="" name="password" id="password" data-placement="right">
                <div class="alert alert-danger" style="display:none;margin-top:5px;" id="alert-password"></div>
            </div>
            <div class="form-group">
                <div class="checkbox i-checks"><label> <input type="checkbox" name="agree"><i></i> Setuju pada <a href="<?=url("kebijakan")?>" target="_blank">kebijakan </a></label></div>
                <p class="alert alert-warning">Pastikan alamat email yang anda gunakan adalah alamat email aktif untuk proses verifikasi.</p>
            </div>
            <div id="regalert"></div>
            <button type="submit" class="btn btn-primary block full-width m-b" id="btnreg">Daftarkan Sekarang</button>

            <!--
            <p class="text-muted text-center"><small>Sudah punya akun?</small></p>
            <a class="btn btn-sm btn-white btn-block" href="<?php echo e(url('login')); ?>">Login</a>
            -->
            <br><br><br>
        </form>

    </div>
</div>
<script src="<?php echo e(url('templates/admin/js/plugins/iCheck/icheck.min.js')); ?>"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=<?=$google_api_key?>&libraries=places&v=3.exp"></script>
<script>
    $(document).ready(function(){
        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
        });
    });

    $("#registerForm").submit(function(e){
      e.preventDefault();
      $("#btnreg").addClass("disabled").prop("disabled",true).html("Memproses..");
      $.ajax({
        type:"POST",
        dataType: "json",
        url:"<?=url('register-submit')?>",
        data: $(this).serialize()
      })
      .done(function(result){
        console.log(result);
        if(result.status=="success"){
          setTimeout(function(){
            //document.location = "<?php echo e(url('login')); ?>";
          },5000);
          $("#regalert").html(result.response);
        }else if(result.status == "error_validation"){
          var errors = result.response;
          for(var i in errors){
            gotoID(i);
            $("#alert-"+i).html(errors[i][0]).show();
            //$("#"+i).prop("title",errors[i][0]).prop("data-placement","right").tooltip().parent(".form-group").addClass("has-error");
          }
          $("#regalert").html("<div class='alert alert-danger'>Mohon check data yang anda masukkan, kami menemukan beberapa kesalahan yang harus diperbaiki.</div>");
        }else{
          $("#regalert").html(result.response);
        }
        $("#btnreg").removeClass("disabled").prop("disabled",false).html("Daftarkan Sekarang");
      })
      .fail(function(msg){
        console.log(msg);
        $("#btnreg").removeClass("disabled").prop("disabled",false).html("Daftarkan Sekarang");
      })
      .always(function(){

      });
    });

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
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/myzeals/resources/views/backend/user/register.blade.php ENDPATH**/ ?>