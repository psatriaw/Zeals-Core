<link href="{{ url('templates/admin/css/plugins/iCheck/custom.css') }}" rel="stylesheet">
<style>
    .form-control,
    .btn {
        height: 52px;
    }

    .album-content h3 {
        margin: 2rem 0rem 1rem 0rem;
    }

    .dropdown-toggle {
        color: #fff;
        font-size: 14px;
    }

    .input-group-addon {
        border: 1px solid #2f2f7c;
        background: #2f2f7c;
        color: #fff;
        border-top-left-radius: 5px;
        border-bottom-left-radius: 5px;
        padding: 10px;
    }

    @media(max-width:480px) {
        .loginscreen.middle-box {
            padding-left: 5px;
            padding-right: 5px;
            width: auto !important;
        }

        .dark-bg {
            background-repeat: repeat-y;
        }
    }
</style>
<main role="main">
    <div class="container">
        <div class="bg-yellow registration">
            <div class="row">
                <div class="col-xs-12 col-md-7 reg-left">
                    <h2>Join us now!</h2>
                    <h1>
                        Worlds #1<br>
                        Digital Marketing <br>
                        Platform
                    </h1>
                </div>
                <div class="col-xs-12 col-md-5 reg-right">
                    {{-- <div class="container"> --}}
                    <div class="row">
                        <div class="p-2">
                            <div class="album-content">
                                <img src="{{ url('templates/admin/img/logo.jpg') }}" alt="Logo" class="main-logo">
                                <h3>Create Account</h3>
                                <?= $is_google_registration != '' ? "<h4 style='font-size:18px;'>You are about to register your new Zeals Account connected to your <span style='color:#6f0000;'>Google Account</span>.</h4>" : '' ?>
                                <form class="m-t top30 text-left" role="form" action="{{ url('register-submit') }}"
                                    method="POST" id="registerForm">
                                    <input type="hidden" id="token" name="_token" value="{{ csrf_token() }}">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-lg-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control" placeholder="First Name"
                                                    requireds="" name="first_name" id="first_name"
                                                    data-placement="right">
                                                <div class="alert alert-danger" style="display:none;margin-top:5px;"
                                                    id="alert-first_name"></div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-lg-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control" placeholder="Last Name"
                                                    requireds="" name="last_name" id="last_name"
                                                    data-placement="right">
                                                <div class="alert alert-danger" style="display:none;margin-top:5px;"
                                                    id="alert-first_name"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <div class="dropdown show">
                                                    <a class=" dropdown-toggle" href="#" role="button"
                                                        id="dropdownMenuLink" data-toggle="dropdown"
                                                        aria-haspopup="true" aria-expanded="false">
                                                        <img src="https://flagcdn.com/16x12/id.png"> +62
                                                    </a>

                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                        <a class="dropdown-item" href="#"><img
                                                                src="https://flagcdn.com/16x12/id.png"> +62</a>
                                                        <a class="dropdown-item" href="#"><img
                                                                src="https://flagcdn.com/16x12/id.png"> +62</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control" placeholder="812xxx"
                                                requireds="" name="phone" id="phone" data-placement="right">
                                        </div>
                                        <div class="alert alert-danger" style="display:none;margin-top:5px;"
                                            id="alert-phone"></div>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Email" requireds=""
                                            name="email" id="email" data-placement="right"
                                            <?= $is_google_registration != '' ? 'readonly' : '' ?>
                                            value="<?= $is_google_registration != '' ? $google_email : '' ?>">
                                        <div class="alert alert-danger" style="display:none;margin-top:5px;"
                                            id="alert-email"></div>
                                    </div>

                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Username" requireds=""
                                            name="username" id="username" data-placement="right">
                                        <div class="alert alert-danger" style="display:none;margin-top:5px;"
                                            id="alert-username"></div>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control" placeholder="Password"
                                            requireds="" name="password" id="password" data-placement="right">
                                        <div class="alert alert-danger" style="display:none;margin-top:5px;"
                                            id="alert-password"></div>
                                    </div>

                                    <?php if(Request::segment(2)!=""){ ?>
                                    <div class="form-group">
                                        <input type="text" class="form-control" value="<?= Request::segment(2) ?>"
                                            name="department_code" id="department_code" data-placement="right"
                                            readonly>
                                    </div>
                                    <?php } ?>

                                    <div class="form-group">
                                        <div class="checkbox i-checks text-grey p1"><label> <input type="checkbox"
                                                    name="agree"><i></i> I agree to the <a
                                                    href="<?= url('kebijakan') ?>" target="_blank">Terms &
                                                    Conditions </a></label></div>
                                        <p class="alert alert-warning mt-3 text-small">Please make sure your email
                                            and phone are valid for better registration experience.</p>
                                    </div>
                                    <div id="regalert"></div>
                                    <button type="submit" class="btn btn-primary block btn-block"
                                        id="btnreg">Register Now</button>

                                    <!--
                <p class="text-muted text-center"><small>Sudah punya akun?</small></p>
                <a class="btn btn-sm btn-white btn-block" href="{{ url('login') }}">Login</a>
                -->

                                    <?php if($is_google_registration!=""){ ?>
                                    <input type="hidden" name="google_id" value="<?= $is_google_registration ?>">
                                    <?php }?>
                                    <br>
                                    <div class="form-group py-4 text-center bottom100"
                                        style="margin-bottom:50px !important;">
                                        <p class="p1 text-grey"><a href="{{ url('signin') }}"
                                                class="text-grey"><strong>Sign In</strong></a> Page</p>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    {{-- </div> --}}
                </div>
            </div>
        </div>
    </div>
</main>
<script src="{{ url('templates/admin/js/plugins/iCheck/icheck.min.js') }}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=<?= $google_api_key ?>&libraries=places&v=3.exp"></script>
<script>
    $(document).ready(function() {
        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
        });
    });

    $("#registerForm").submit(function(e) {
        e.preventDefault();
        $("#btnreg").addClass("disabled").prop("disabled", true).html("Processing..");
        $.ajax({
                type: "POST",
                dataType: "json",
                url: "<?= url('register-submit') ?>",
                data: $(this).serialize()
            })
            .done(function(result) {
                console.log(result);
                if (result.status == "success") {
                    setTimeout(function() {
                        //document.location = "{{ url('login') }}";
                    }, 5000);
                    $("#regalert").html(result.response);
                } else if (result.status == "error_validation") {
                    var errors = result.response;
                    for (var i in errors) {
                        gotoID(i);
                        $("#alert-" + i).html(errors[i][0]).show();
                        //$("#"+i).prop("title",errors[i][0]).prop("data-placement","right").tooltip().parent(".form-group").addClass("has-error");
                    }
                    $("#regalert").html(
                        "<div class='alert alert-danger'>Please check the registration fields.</div>");
                } else {
                    $("#regalert").html(result.response);
                }
                $("#btnreg").removeClass("disabled").prop("disabled", false).html("Register Now");
            })
            .fail(function(msg) {
                console.log(msg);
                $("#btnreg").removeClass("disabled").prop("disabled", false).html("Register Now");
            })
            .always(function() {

            });
    });

    function gotoID(id) {
        $('html, body').animate({
            scrollTop: $("#" + id).offset().top - 200
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
    $base_city_coor = explode(',', $base_city_coor);
    echo 'latitude  = ' . $base_city_coor[0] . ';';
    echo 'longitude = ' . $base_city_coor[1] . ';';
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
        latitude = latit;
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

    $("#address").keyup(function(e) {
        clearTimeout(timeout);
        var alamat = $(this).val();
        timeout = setTimeout(function() {
            getCoordinate(alamat);
        }, 500);
    });

    function getCoordinate(alamat) {
        $.ajax({
                url: "<?= url('get-address-coordinate') ?>",
                type: "POST",
                data: {
                    address: alamat,
                    _token: $("input[name='_token']").val()
                }
            })
            .done(function(result) {
                setMapCoordinate(result.latitude, result.longitude);
            })
            .fail(function(msg) {
                alert("Gagal memuat peta");
            });
    }

    $(document).ready(function() {
        initialize();
        $("#map-canvas").tooltip();
    });
</script>
