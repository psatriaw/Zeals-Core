<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<main role="main">
  <?php

    if($detail->avatar==""){
      $avatar = "https://icons.iconarchive.com/icons/papirus-team/papirus-status/512/avatar-default-icon.png";
    }else{
      $avatar = url($detail->avatar);
    }
  ?>

  <div class="album">
    <div class="container">
      <div class="row">
        <div class="col-12 col-md-3 mb-3">
          @include('frontend.menu_sidebar')
        </div>
        <div class="col-12 col-md-9 mobile-nopadding">
          <div class="album-content">
            @if ($errors->any())
            <div class="alert alert-danger">
                Any Error Occured!
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            </div>
            @endif
            @include('backend.flash_message')
            {!! Form::model($detail,['url' => url('profile/update'), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate', "enctype" => "multipart/form-data"]) !!}
            <div class="row justify-content-md-center">
              <div class="col-12 col-md-6">
                <div class="boxes">
                  <h3 class="text-black mb-3 text-center">Avatar</h3>
                  <div class="form-group {{ ($errors->has('first_name')?"has-error":"") }}">
                      <div class="text-center">
                        <img src="<?=($avatar)?>" class="img-avatar" id="avatar-preview"><br>
                        <button type="button" class="btn btn-primary" onclick="changeavatar()">Change Avatar</button>
                        <input type="file" id="avatar" name="avatar" style="display:none;">
                      </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12 col-sm-6 col-md-6">
                <h3 class="text-black mb-3 mt-5">Personal Information</h3>
                <div class="form-group {{ ($errors->has('first_name')?"has-error":"") }}">
                    <input type="hidden" name="id_user" value="<?=$detail->id_user?>">
                    {!! Form::text('first_name', null, ['class' => 'form-control','placeholder' => 'First Name']) !!}
                    {!! $errors->first('first_name', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                </div>
                <div class="form-group {{ ($errors->has('last_name')?"has-error":"") }}">
                    {!! Form::text('last_name', null, ['class' => 'form-control','placeholder' => 'Last Name']) !!}
                    {!! $errors->first('last_name', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                </div>
                <div class="form-group {{ ($errors->has('email')?"has-error":"") }}">
                    {!! Form::text('email', null, ['class' => 'form-control','placeholder' => 'Email']) !!}
                    {!! $errors->first('email', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                </div>

                <div class="form-group {{ ($errors->has('username')?"has-error":"") }}">
                    {!! Form::text('username', null, ['class' => 'form-control','placeholder' => 'Username']) !!}
                    {!! $errors->first('username', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                </div>

                <div class="form-group {{ ($errors->has('phone')?"has-error":"") }}">
                    {!! Form::text('phone', null,['class' => 'form-control','placeholder' => 'Phone']) !!}
                    {!! $errors->first('phone', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                </div>
                <div class="form-group {{ ($errors->has('id_job')?"has-error":"") }}">
                    {!! Form::select('id_job', $pekerjaans,null, ['class' => 'form-control select2','placeholder' => 'Select Job']) !!}
                    {!! $errors->first('id_job', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                </div>

                <div class="form-group {{ ($errors->has('id_wilayah')?"has-error":"") }}">
                    {!! Form::select('id_wilayah', $wilayah,null, ['class' => 'form-control select2','placeholder' => 'Select Domisili']) !!}
                    {!! $errors->first('id_wilayah', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                </div>

                <h3 class="text-black mb-3 mt-5">Account Security</h3>
                <div class="form-group {{ ($errors->has('n_password')?"has-error":"") }}">
                    {!! Form::password('n_password',['class' => 'form-control','placeholder' => 'Password','type' => 'password']) !!}
                    {!! $errors->first('n_password', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                </div>

                <div class="form-group {{ ($errors->has('c_password')?"has-error":"") }}">
                    {!! Form::password('c_password', ['class' => 'form-control','placeholder' => 'Retype Password','type' => 'password']) !!}
                    {!! $errors->first('c_password', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                    <p class='help-block text-small mt-2'>Let it blank if you do not want to change your password account</p>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-6">
              <h3 class="text-black mb-3 mt-5">Bank Account</h3>
              <div class="form-group {{ ($errors->has('nama_bank')?"has-error":"") }}">
                  {!! Form::select('nama_bank', $bank,null, ['class' => 'form-control select2','placeholder' => 'Bank Name']) !!}
                  {!! $errors->first('nama_bank', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
              </div>
              <div class="form-group {{ ($errors->has('nomor_rekening')?"has-error":"") }}">
                  {!! Form::text('nomor_rekening', null, ['class' => 'form-control','placeholder' => 'Account Number']) !!}
                  {!! $errors->first('nomor_rekening', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
              </div>
              <div class="form-group {{ ($errors->has('nama_pemilik_rekening')?"has-error":"") }}">
                  {!! Form::text('nama_pemilik_rekening', null, ['class' => 'form-control','placeholder' => 'Account Name']) !!}
                  {!! $errors->first('nama_pemilik_rekening', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
              </div>

              <h3 class="text-black mb-3 mt-5">Personal Categories</h3>
              <a href="{{ url('profile/preferences') }}" class="pull-right text-black btn btn-sm btn-info btn-rounded text-white"><i class="fa fa-cogs"></i> configure</a>

              <div class="" id="preferences">
                <?php
                  if($preferences){
                    if(sizeof($preferences)){
                      foreach ($preferences as $key => $value) {
                        ?><label class="label label-pink"><?=$value->nama_sektor_industri?></label> <?php
                      }
                    }else{
                      ?>
                      <div class="text-small text-danger">
                        <p>Please configure your personal interest through the configuration button beside to get campaign quicker</p>
                      </div>
                      <?php
                    }
                  }else{
                    ?>

                    <?php
                  }
                ?>
              </div>
            </div>
            <div class="col-12 mt-4">
              <div class="form-group">
                  <div class="text-center">
                    <div id="updatealert"></div>
                    <button class="btn btn-primary shadow-none" type="submit" id="btnupdate" >Submit Changes</button>
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
</main>
<script>
    function changeavatar(){
      $("#avatar").click();
    }

    $("#avatar").change(function(e){
      $("#avatar-preview").prop("src",URL.createObjectURL(e.target.files[0]));
    });

    $(".select2").select2();
</script>
