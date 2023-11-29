<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Setting Address</h2>
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
                        <h5>Setting Address</h5>
                    </div>
                    <div class="ibox-content">
						<div style="padding-left:15px;padding-right:15px;">
						{!! Form::model($detail,['url' => url('setting-address-update'), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate', "enctype" => "multipart/form-data"]) !!}
						<div class="row justify-content-md-center">
						  <div class="col-12 col-sm-6 col-md-4">
							<div class="form-group {{ ($errors->has('id_provinsi')?"has-error":"") }}">
								{!! Form::select('id_provinsi', $provinsi, $detail->id_provinsi, ['class' => 'form-control','id' => 'id_provinsi','placeholder' => 'Pilih Provinsi']) !!}
								{!! $errors->first('id_provinsi', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
							</div>
							<div class="form-group {{ ($errors->has('id_kota')?"has-error":"") }}">
								{!! Form::select('id_kota', array(),null, ['class' => 'form-control','id'=>'id_kota','placeholder' => 'Pilih Kota/Kabupaten']) !!}
								{!! $errors->first('id_kota', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
							</div>

							<div class="form-group {{ ($errors->has('id_kecamatan')?"has-error":"") }}">
								{!! Form::select('id_kecamatan', array(), null,['class' => 'form-control','id' => 'id_kecamatan','placeholder' => 'Pilih Kecamatan']) !!}
								{!! $errors->first('id_kecamatan', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
							</div>

							<div class="form-group {{ ($errors->has('alamat')?"has-error":"") }}">
								{!! Form::textarea('alamat', $detail->address_1, ['class' => 'form-control','placeholder' => 'Alamat']) !!}
								{!! $errors->first('alamat', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}

								{!! Form::hidden('provinsi', null, ['id'=>'label_provinsi']) !!}
								{!! Form::hidden('kota', null, ['id'=>'label_kota']) !!}
								{!! Form::hidden('kecamatan', null, ['id'=>'label_kecamatan']) !!}
							</div>

							<div class="form-group">
								<div class="text-center">
								  <button class="btn btn-primary btn-block shadow-none" type="submit">SIMPAN PERUBAHAN</button>
								</div>
							</div>
						</div>
					  </div>
					  </div>
					  {!! Form::close() !!}
	  
					</div>
				  </div>
				</div>
			</div>
		</div>
	</div>
</div>
</main>
<script>
  var selected_provinsi   = <?=$detail->id_provinsi?>;
  var selected_kota       = <?=$detail->id_kota?>;
  var selected_kecamatan  = <?=$detail->id_kecamatan?>;

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
        $("#id_kota").val(selected_kota);
        $("#id_kota").change();
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
        $("#id_kecamatan").val(selected_kecamatan);
    });
  });

  $("#id_kecamatan").change(function(e){
    var id_kota = $(this).val();
    $("#label_kecamatan").val($( "#id_kecamatan option:selected" ).text());
  });

  $(document).ready(function(){
    setTimeout(function(){
      $("#id_provinsi").val(selected_provinsi);
      $("#id_provinsi").change();
    },1500);
  });
</script>
