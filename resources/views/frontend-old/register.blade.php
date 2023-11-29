<main role="main">

  <section class="jumbotron text-center">
    <div class="container">
      <div class="superline"></div>
      <div class="custom-container">
        <h1><?=$title?></h1>
      </div>
    </div>
    <div class="container text-center top100">
      <?=$content?>
    </div>
  </section>

  <div class="album pb-5">
    <div class="container">
      <div class="row justify-content-md-center">
        <div class="col-md-4 col-12">
          <ul class="reg nav nav-tabs justify-content-center">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="#">Pembeli</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ url('register/mitra') }}">Mitra</a>
            </li>
          </ul>
        </div>
      </div>

      <div class="album-content">
        {!! Form::open(['url' => url('do-registrasi'), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate', "enctype" => "multipart/form-data"]) !!}
        <div class="row justify-content-md-center">
          <div class="col-12 col-sm-6 col-md-4">
            <label>Informasi Personal</label>
            <div class="form-group checkinput {{ ($errors->has('first_name')?"has-error":"") }}">
                {!! Form::text('first_name', null, ['class' => 'form-control','placeholder' => 'Nama Lengkap','id'=> "first_name"]) !!}
                {!! $errors->first('first_name', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                <div id='first_name' class='areawarn'></div>
            </div>
            <div class="form-group checkinput {{ ($errors->has('email')?"has-error":"") }}">
                {!! Form::text('email', null, ['class' => 'form-control','placeholder' => 'Email','id'=>'isi_email']) !!}
                {!! $errors->first('email', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                <div id='email' class='areawarn'></div>
            </div>

            <div class="form-group checkinput {{ ($errors->has('phone')?"has-error":"") }}">
                {!! Form::text('phone', null,['class' => 'form-control','placeholder' => 'No. HP','id'=>'isi_phone']) !!}
                {!! $errors->first('phone', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                <div id='phone' class='areawarn'></div>
            </div>

            <div class="form-group checkinput {{ ($errors->has('password')?"has-error":"") }}">
                {!! Form::password('password',['class' => 'form-control','placeholder' => 'Password','id'=>'isi_password']) !!}
                {!! $errors->first('password', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                <div id='password' class='areawarn'></div>
            </div>

			<div class="form-group checkinput {{ ($errors->has('confirm_password')?"has-error":"") }}">
                {!! Form::password('confirm_password',['class' => 'form-control','placeholder' => 'Konfirmasi Password','id'=>'isi_c_password']) !!}
                {!! $errors->first('confirm_password', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                <div id='confirm_password' class='areawarn'></div>
            </div>
            <br>
            <label>Informasi Pengiriman</label>
            <div class="form-group checkinput {{ ($errors->has('id_provinsi')?"has-error":"") }}">
                {!! Form::select('id_provinsi', $provinsi, null, ['class' => 'form-control','id' => 'id_provinsi','placeholder' => 'Pilih Provinsi']) !!}
                {!! $errors->first('id_provinsi', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
            </div>
            <div class="form-group checkinput {{ ($errors->has('id_kota')?"has-error":"") }}">
                {!! Form::select('id_kota', array(),null, ['class' => 'form-control','id'=>'id_kota','placeholder' => 'Pilih Kota/Kabupaten']) !!}
                {!! $errors->first('id_kota', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
            </div>

            <div class="form-group checkinput {{ ($errors->has('id_kecamatan')?"has-error":"") }}">
                {!! Form::select('id_kecamatan', array(), null,['class' => 'form-control','id' => 'id_kecamatan','placeholder' => 'Pilih Kecamatan']) !!}
                {!! $errors->first('id_kecamatan', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
            </div>

            <div class="form-group checkinput {{ ($errors->has('alamat')?"has-error":"") }}">
                {!! Form::textarea('alamat', null, ['class' => 'form-control checkinput','placeholder' => 'Alamat', 'rows' => 3,'id'=>'alamat']) !!}
                {!! $errors->first('alamat', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}

                {!! Form::hidden('provinsi', null, ['id'=>'label_provinsi']) !!}
                {!! Form::hidden('kota', null, ['id'=>'label_kota']) !!}
                {!! Form::hidden('kecamatan', null, ['id'=>'label_kecamatan']) !!}
                {!! Form::hidden('backlink', @$backlink) !!}
            </div>

            <div class="form-group">
                <div class="text-center">
                  <div class='' id="registrasialert"></div>
                  <button class="btn btn-primary btn-block shadow-none" type="submit" id="registerbtn" disabled=true>REGISTRASI</button>
                </div>
            </div>
            <div class="form-group py-4 text-center">
              <p>sudah punya akun? <a href="{{ url('signin') }}" class="text-black"><strong>Login disini</strong></a></p>
            </div>
        </div>
      </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>
</main>
<script>

    $("#formmain").submit(function(e){
      e.preventDefault();
      $("#registerbtn").addClass("disabled").prop("disabled",true).html("Processing..");
      $(".areawarn").html("");
      $.ajax({
        type:"POST",
        dataType: "json",
        url:"<?=url('do-registrasi')?>",
        data: $(this).serialize()
      })
      .done(function(result){
        console.log(result);
        if(result.status=="success"){
          //setTimeout(function(){
            //document.location = "{{ url('history') }}";
          //},3000);
          <?php
            if(@$backlink!=""){
                ?>
                setTimeout(function(){
                    document.location = "<?=$backlink?>";
                },500);
                <?php
            }else{
                ?>
                setTimeout(function(){
                    document.location = "{{ url('history') }}";
                },500);
                <?php
            }
          ?>

          $("#registrasialert").html(result.response);
        }else if(result.status == "error_validation"){
          var errors = result.response;
          console.log(errors);
          for(var i in errors){
			  console.log(i);
            $("#"+i).html("<div class='text-danger'>"+errors[i][0]+"</div>");
            location.replace = "<?=url('')?>#"+i;
          }
          $("#registrasialert").html("<div class='alert alert-danger'>Tolong cek input! Ada kesalahan.</div>");
        }else{
          $("#registrasialert").html(result.response);
        }

        $("#loginbtn").removeClass("disabled").prop("disabled",false).html("REGISTRASI");
      })
      .fail(function(msg){
        console.log(msg);
      })
      .always(function(){
        $("#registerbtn").attr("disabled",false).removeClass("disabled").html("REGISTRASI");
      });
    });

  $("#id_provinsi").change(function(e){
    var id_province = $(this).val();
    $("#label_provinsi").val($( "#id_provinsi option:selected" ).text());


    $("#id_kota").html("");

    $.ajax({
      type:"GET",
      dataType: "json",
      url:"<?=url('get-list-of-city')?>",
      data: {id_province: id_province}
    })
    .done(function(result){
      console.log(result);

      if(result.status=="success"){
        $("#id_kota").html(result.html);
      }
    })
    .fail(function(msg){
      console.log(msg);
    })
    .always(function(){

    });
  });

  $("#id_kota").change(function(e){
    var id_kota = $(this).val();
    $("#label_kota").val($( "#id_kota option:selected" ).text());

    $("#id_kecamatan").html("");

    $.ajax({
      type:"GET",
      dataType: "json",
      url:"<?=url('get-list-of-kecamatan')?>",
      data: {id_kota: id_kota}
    })
    .done(function(result){
      console.log(result);

      if(result.status=="success"){
        $("#id_kecamatan").html(result.html);
      }
    })
    .fail(function(msg){
      console.log(msg);
    })
    .always(function(){

    });
  });

  $("#id_kecamatan").change(function(e){
    var id_kota = $(this).val();
    $("#label_kecamatan").val($( "#id_kecamatan option:selected" ).text());
  });

  $(".checkinput").keyup(function(){
      console.log("changed");

      if(($("#isi_first_name").val()!="") && ($("#isi_email").val()!="") && ($("#isi_phone").val()!="") && ($("#isi_password").val()!="") && ($("#isi_c_password").val()!="") && ($("#id_provinsi").val()!="") && ($("#id_kota").val()!="") && ($("#id_kecamatan").val()!="") && ($("#alamat").val()!="")){
          $("#registerbtn").prop("disabled",false);
      }else{
          $("#registerbtn").prop("disabled",true);
      }
  });

  $(".checkinput").change(function(){
      console.log("changed");

      if(($("#isi_first_name").val()!="") && ($("#isi_email").val()!="") && ($("#isi_phone").val()!="") && ($("#isi_password").val()!="") && ($("#isi_c_password").val()!="") && ($("#id_provinsi").val()!="") && ($("#id_kota").val()!="") && ($("#id_kecamatan").val()!="") && ($("#alamat").val()!="")){
          $("#registerbtn").prop("disabled",false);
      }else{
          $("#registerbtn").prop("disabled",true);
      }
  });
</script>
