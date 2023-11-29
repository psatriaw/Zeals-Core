<main role="main">

  <section class="jumbotron text-center">
    <div class="container">
      <div class="superline"></div>
      <div class="custom-container">
        <h1>COMPLAIN</h1>
      </div>
    </div>
    <div class="container text-center top100">
      <h3>Tambah Complain Baru</h3>
      <p class="text-gray">Mohon menceritakan dengan detail mengenai komplain yang anda lakukan</p>
      <p class="description"><a href='{{ url('complain') }}' class="text-black"><i class='fa fa-arrow-left'></i> kembali</a></p>
    </div>
  </section>

  <div class="album pb-5">
    <div class="container">
      <div class="album-content">
        {!! Form::open(['url' => url('complain-store'), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate', "enctype" => "multipart/form-data"]) !!}
        <div class="row justify-content-md-center">
          <div class="col-12 col-sm-6 col-md-4">
            <div class="form-group {{ ($errors->has('ticket_code')?"has-error":"") }}">
                <div class="col-xs-12">
                    {!! Form::text('ticket_code', $ticket_code, ['class' => 'form-control disabled' ,'disabled']) !!}
                    {!! $errors->first('ticket_code', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                </div>
            </div>

            <div class="form-group {{ ($errors->has('subject')?"has-error":"") }}">
                <div class="col-xs-12">
                    {!! Form::textarea('subject', null, ['class' => 'form-control','rows' => '5','placeholder' => 'Subject']) !!}
                    {!! $errors->first('subject', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                    <input type="hidden" name="sender" value="<?=$login->id_user?>">
                </div>
            </div>

            <div class="form-group">
                <div class="text-center">
                  <button class="btn btn-primary btn-block shadow-none" type="submit">BUAT COMPLAIN</button>
                </div>
            </div>
        </div>
      </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>
</main>
<script>

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
</script>
