<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<main role="main" class="main-content">
  <?php

    if($detail->avatar==""){
      $avatar = "https://icons.iconarchive.com/icons/papirus-team/papirus-status/512/avatar-default-icon.png";
    }else{
      $avatar = url($detail->avatar);
    }
  ?>
        <h1>Setting & Profile</h1>
        <div class="mobile-nopadding">
          <div class="album-content">
            @if ($errors->any())
            <div class="alert alert-danger">
                Any Error Occured! Please check all fields below.
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            </div>
            @endif
            @include('backend.flash_message')
            {!! Form::model($detail,['url' => url('profile/update'), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate', "enctype" => "multipart/form-data"]) !!}
            <div class="row justify-content-md-center">
              <div class="col-12">
                <div class="media mt-3 mb-3">
                  <img src="<?=$avatar?>" class="avatar img img-rounded" >
                  <div class="media-body pl-3">
                    <button type="button" class="btn btn-secondary mt-2" onclick="changeavatar()">Change Avatar</button><br>
                    <p class="mt-2">At least 100x100px PNG or JPG file, max 2MB</p>
                    <input type="file" id="avatar" name="avatar" style="display:none;">
                  </div>
                </div>
              </div>
            </div>

            <h3 class="text-black mb-3 mt-5">Personal Information</h3>
            <div class="panel p3">
              <div class="row">
                <div class="col-12 col-sm-6 col-md-6">
                  <div class="form-group {{ ($errors->has('first_name')?"has-error":"") }}">
                      <label>First Name</label>
                      <div class="input-group">
                        <input type="hidden" name="id_user" value="<?=$detail->id_user?>">
                        {!! Form::text('first_name', null, ['class' => 'form-control','placeholder' => 'First Name']) !!}
                        <div class="input-group-addon"><i class='icon icon-name'></i></div>
                      </div>
                      {!! $errors->first('first_name', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                  </div>
                </div>
                <div class="col-12 col-sm-6 col-md-6">
                  <div class="form-group {{ ($errors->has('last_name')?"has-error":"") }}">
                      <label>Last Name</label>
                      <div class="input-group">
                        {!! Form::text('last_name', null, ['class' => 'form-control','placeholder' => 'Last Name']) !!}
                        <div class="input-group-addon"><i class='icon icon-name'></i></div>
                      </div>
                      {!! $errors->first('last_name', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                  </div>
                </div>
                <div class="col-12 col-sm-6 col-md-6">
                  <div class="form-group {{ ($errors->has('email')?"has-error":"") }}">
                      <label>Email</label>
                      <div class="input-group">
                        {!! Form::text('email', null, ['class' => 'form-control','placeholder' => 'Email']) !!}
                        <div class="input-group-addon"><i class='icon icon-email'></i></div>
                      </div>
                      {!! $errors->first('email', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                  </div>
                </div>
                <div class="col-12 col-sm-6 col-md-6">
                  <div class="form-group {{ ($errors->has('username')?"has-error":"") }}">
                      <label>Username</label>
                      <div class="input-group">
                        {!! Form::text('username', null, ['class' => 'form-control','placeholder' => 'Username']) !!}
                        <div class="input-group-addon"><i class='icon icon-id'></i></div>
                      </div>
                      {!! $errors->first('username', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                  </div>
                </div>
                <div class="col-12 col-sm-6 col-md-6">
                  <div class="form-group {{ ($errors->has('phone')?"has-error":"") }}">
                      <label>Phone Number</label>
                      <div class="input-group">
                        {!! Form::text('phone', null,['class' => 'form-control','placeholder' => 'Phone']) !!}
                        <div class="input-group-addon"><i class='icon icon-phone'></i></div>
                      </div>
                      {!! $errors->first('phone', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                  </div>
                </div>
                <div class="col-12 col-sm-6 col-md-6">
                  <div class="form-group {{ ($errors->has('id_job')?"has-error":"") }}">
                      <label>Occupation</label>
                      {!! Form::select('id_job', $pekerjaans,null, ['class' => 'form-control select2','placeholder' => 'Select Job']) !!}
                      {!! $errors->first('id_job', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                  </div>
                </div>
                <div class="col-12 col-sm-6 col-md-6">
                  <div class="form-group {{ ($errors->has('id_wilayah')?"has-error":"") }}">
                      <label>Location</label>
                      {!! Form::select('id_wilayah', $wilayah,null, ['class' => 'form-control select2','placeholder' => 'Select Domisili']) !!}
                      {!! $errors->first('id_wilayah', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                  </div>
                </div>
              </div>
            </div>

            <h3 class="text-black mb-3 mt-5">Account Security</h3>
            <div class="panel p3">
              <div class="row">
                <div class="col-12 col-sm-6 col-md-6">
                  <div class="form-group {{ ($errors->has('n_password')?"has-error":"") }}">
                      <label>Password</label>
                      {!! Form::password('n_password',['class' => 'form-control','placeholder' => 'Password','type' => 'password']) !!}
                      {!! $errors->first('n_password', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                  </div>
                </div>
                <div class="col-12 col-sm-6 col-md-6">
                  <div class="form-group {{ ($errors->has('c_password')?"has-error":"") }}">
                      <label>Retype Password</label>
                      {!! Form::password('c_password', ['class' => 'form-control','placeholder' => 'Retype Password','type' => 'password']) !!}
                      {!! $errors->first('c_password', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                  </div>
                </div>
              </div>
              <p class='help-block text-small mt-2'>Let it blank if you do not want to change your password account</p>
            </div>

            <h3 class="text-black mb-3 mt-5">Bank Account</h3>
            <div class="panel p3">
              <div class="row">
                <div class="col-12 col-sm-6 col-md-6">
                  <div class="form-group {{ ($errors->has('nama_bank')?"has-error":"") }}">
                      <label>Bank Name</label>
                      {!! Form::select('nama_bank', $bank,null, ['class' => 'form-control select2','placeholder' => 'Bank Name']) !!}
                      {!! $errors->first('nama_bank', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                  </div>
                </div>
                <div class="col-12 col-sm-6 col-md-6">
                  <div class="form-group {{ ($errors->has('nomor_rekening')?"has-error":"") }}">
                      <label>Account Number</label>
                      {!! Form::text('nomor_rekening', null, ['class' => 'form-control','placeholder' => 'Account Number']) !!}
                      {!! $errors->first('nomor_rekening', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                  </div>
                </div>
                <div class="col-12 col-sm-6 col-md-6">
                  <div class="form-group {{ ($errors->has('nama_pemilik_rekening')?"has-error":"") }}">
                      <label>Account Name</label>
                      {!! Form::text('nama_pemilik_rekening', null, ['class' => 'form-control','placeholder' => 'Account Name']) !!}
                      {!! $errors->first('nama_pemilik_rekening', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                  </div>
                </div>
              </div>
            </div>

              <h3 class="text-black mb-3 mt-5">Personal Categories</h3>
              <div class="panel">
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
            </div>

              <div class="form-group mt-4">
                  <div class="">
                    <div id="updatealert"></div>
                    <button class="btn btn-primary shadow-none" type="submit" id="btnupdate" >Submit Changes</button>
                  </div>
              </div>
            </div>

          {!! Form::close() !!}
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
