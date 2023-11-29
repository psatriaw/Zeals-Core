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
      <?php if($cart_code!=""){ ?>
      <p class="description"><a href='{{ url('history/'.$cart_code) }}' class="text-black"><i class='fa fa-arrow-left'></i> kembali</a></p>
      <?php } ?>
    </div>
  </section>
	<script>
	function checkInput(){
		var sudahceksmuaada = true;
		$(".confm").each(function(){
			if($(this).val()==""){
				$("#kirim_konfirmasi").addClass("disabled").prop("disabled",true);
				sudahceksmuaada = false;
				return;
			}
		});
		
		if(sudahceksmuaada){
			$("#kirim_konfirmasi").removeClass("disabled").prop("disabled",false);
		}
	}

</script>

  <div class="album py-1 pb-5">
    <div class="container">
      @include('backend.flash_message')
      <div class="album-content">
        {!! Form::open(['url' => url('do-confirmation'), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate', "enctype" => "multipart/form-data"]) !!}
        <div class="row justify-content-md-center">
          <div class="col-12 col-sm-6 col-md-4">
            <div class="form-group {{ ($errors->has('cart_code')?"has-error":"") }}">
                {!! Form::text('cart_code', $cart_code, ['class' => 'form-control confm','placeholder' => 'No. Order, contoh: ORD000001','onkeyup'=> 'checkInput()','required' => 'required']) !!}
                {!! $errors->first('cart_code', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
            </div>
            <div class="form-group {{ ($errors->has('nominal_transfer')?"has-error":"") }}">
                {!! Form::text('nominal_transfer', null, ['class' => 'form-control confm','placeholder' => 'Nominal Transfer','required' => 'required','onkeyup'=> 'checkInput()']) !!}
                {!! $errors->first('nominal_transfer', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
            </div>

            <div class="form-group {{ ($errors->has('tujuan')?"has-error":"") }}">
                {!! Form::select('tujuan', $rekeningtujuan, null,['class' => 'form-control confm','placeholder' => 'Nomor Rekening Tujuan','required' => 'required','onchange'=> 'checkInput()']) !!}
                {!! $errors->first('tujuan', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
            </div>

            <div class="form-group {{ ($errors->has('no_rekening_pengirim')?"has-error":"") }}">
                {!! Form::text('no_rekening_pengirim', null, ['class' => 'form-control confm','placeholder' => 'Nomor Rekening Pengirim','required' => 'required','onkeyup'=> 'checkInput()']) !!}
                {!! $errors->first('no_rekening_pengirim', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
            </div>

            <div class="form-group {{ ($errors->has('nama_rekening_pengirim')?"has-error":"") }}">
                {!! Form::text('nama_rekening_pengirim', null, ['class' => 'form-control confm','placeholder' => 'Nama Rekening Pengirim','required' => 'required','onkeyup'=> 'checkInput()']) !!}
                {!! $errors->first('nama_rekening_pengirim', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
            </div>

            <div class="form-group {{ ($errors->has('bank_pengirim')?"has-error":"") }}">
                {!! Form::select('bank_pengirim', $listofbank, null, ['class' => 'form-control confm','placeholder' => 'Bank Pengirim','required' => 'required','onchange'=> 'checkInput()']) !!}
                {!! $errors->first('bank_pengirim', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
            </div>

            <div class="form-group">
                <div class="text-center">
                  <button class="btn btn-primary btn-block shadow-none disabled" disabled="true" id="kirim_konfirmasi" type="submit">KIRIM KONFIRMASI</button>
                </div>
            </div>
        </div>
      </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>
</main>