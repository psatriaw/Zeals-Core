<!-- carousel -->
<!-- owl carousel -->
<link rel="stylesheet" href="{{ url('templates/frontend/plugin/owlcarousel/assets/owl.carousel.min.css') }}">
<link rel="stylesheet" href="{{ url('templates/frontend/plugin/owlcarousel/assets/owl.theme.default.min.css') }} ">
<link type="text/css" rel="stylesheet" href="{{ url('templates/frontend/plugin/lightslider/css/lightslider.min.css') }}" />
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

<script src="{{ url('templates/frontend/plugin/jquery-easing/jquery.easing.min.js') }}"></script>
<script src="{{ url('templates/frontend/plugin/owlcarousel/owl.carousel.min.js') }}"></script>
<script src="{{ url('templates/frontend/plugin/lightslider/js/lightslider.min.js') }}"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<main role="main" class="main-content">
  <div class="mobile-nopadding">
    <div class="album-content">
      <h1 class="text-black mb-3">My Wallet</h1>
      <div class="row mt-3">
        <div class="col-12 col-md-6 mt-3">
          <h3 class="text-black">Withdrawal Rules</h3>
          <div class="panel">
            <p class="campaign-description mt-3">
              <?php
                $contoh = 125000;
              ?>
              The maximum request of withdrawal is <strong> 3 x 24</strong>  hours. <br><br>
              You will be charged for administration fee about <strong> Rp.<?=number_format($fee,0,',','.')?></strong>  per withdrawal request.<br><br>
              <strong>for example</strong>: <br>
              If your total amount of withdrawal is <strong> Rp. <?=number_format($contoh,0,',','.')?></strong> due to administration fee, we will transfer
              <strong> Rp. <?=number_format($contoh - $fee,0,',','.')?></strong>  to your bank account.
            </p>
          </div>
        </div>
      </div>
      <div class="row mt-3">
        <div class="col-12 col-sm-6 col-md-6">
          <h3 class="text-black">Withdrawal Form</h3>
          @include('backend.flash_message')
          <div class="panel mt-3">
            {!! Form::model($detail,['url' => url('my-wallet/withdraw/store'), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate', "enctype" => "multipart/form-data"]) !!}
            <div class="form-group mt-3 {{ ($errors->has('total_pencairan')?"has-error":"") }}">
              <div class="input-group">
                <span class="input-group-addon" style='background: #5db6f2;padding: 7px 15px;color: #fff;'>Rp</span>
                {!! Form::text('total_pencairan', null, ['class' => 'form-control','placeholder' => 'Total Amount']) !!}
              </div>
                {!! $errors->first('total_pencairan', '<p class="bg-danger p-xs b-r-sm m-t text-white p-1">:message</p>') !!}
            </div>
            <div class="form-group {{ ($errors->has('nama_bank')?"has-error":"") }}">
                {!! Form::text('nama_bank',@$banks[$detail->nama_bank], ['class' => 'form-control','readonly','disabled' => true,'placeholder' => 'Select Bank']) !!}
                {!! $errors->first('nama_bank', '<p class="bg-danger p-xs b-r-sm m-t text-white p-1">:message</p>') !!}
            </div>
            <div class="form-group {{ ($errors->has('nama_pemilik_rekening')?"has-error":"") }}">
                {!! Form::text('nama_pemilik_rekening', null, ['class' => 'form-control','readonly','placeholder' => 'Account Name']) !!}
                {!! $errors->first('nama_pemilik_rekening', '<p class="bg-danger p-xs b-r-sm m-t text-white p-1">:message</p>') !!}
            </div>
            <div class="form-group {{ ($errors->has('nomor_rekening')?"has-error":"") }}">
                {!! Form::text('nomor_rekening', null, ['class' => 'form-control','readonly','placeholder' => 'Account Number']) !!}
                {!! $errors->first('nomor_rekening', '<p class="bg-danger p-xs b-r-sm m-t text-white p-1">:message</p>') !!}
            </div>


            <div class="form-group mt-3">
                <div class="">
                  <div id="updatealert"></div>
                  <button class="btn btn-primary shadow-none" type="submit" id="btnupdate" >SEND REQUEST</button>
                </div>
            </div>
            {!! Form::close() !!}
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
