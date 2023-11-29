<div id="wrapper">
    @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
    <div id="page-wrapper" class="gray-bg">
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Industries</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url('master/category') }}">Industries</a>
                    </li>
                    <li class="active">
                        <strong>Edit Industry</strong>
                    </li>
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
                            <h5>Edit Industry</h5>
                        </div>
                        <div class="ibox-content">
                            @if ($errors->any())
                            <div class="alert alert-danger">
                                Oops! Something error.
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            </div>
                            @endif
                            @include('backend.flash_message')
                            {!! Form::model($data,['url' => url('master/category/update/'.$data->id_sektor_industri), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate novalidate']) !!}


                            <div class="form-group {{ ($errors->has('nama_sektor_industri')?"has-error":"") }}"><label class="col-sm-2 control-label">Industry Name</label>
                                <div class="col-sm-4 col-xs-12">
                                    {!! Form::text('nama_sektor_industri', null, ['class' => 'form-control disabled']) !!}
                                    {!! $errors->first('nama_sektor_industri', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                </div>
                            </div>

                            <div class="form-group {{ ($errors->has('icon')?"has-error":"") }}"><label class="col-sm-2 control-label">Icon</label>
                              <div class="col-sm-6 col-xs-12">
                                <div class="">
                                  <div class="row">
                                    <div class="col-sm-6">
                                      <?php
                                          $qr = "";
                                      ?>
                                      <?php if($data->icon){ ?>
                                      <img class='img img-responsive img-thumbnail' src="{{ url($data->icon) }}">
                                      <?php } ?>
                                    </div>
                                  </div>
                                </div>
                                  <input type="file" name="icon" >
                                  {!! $errors->first('icon', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                              </div>
                          </div>
                            
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-2">
                                    <a class="btn btn-white" href="{{ url('master/category') }}">
                                        <i class="fa fa-angle-left"></i> back
                                    </a>
                                </div>
                                <div class="col-sm-6 text-right">
                                    <button class="btn btn-white" type="reset">Reset</button>
                                    <button class="btn btn-primary btn-rounded" type="submit">Save Changes</button>
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

<script>
    function selectPhoto(){
      $("#icon").click();
    }

    $("#icon").change(function(e){
      $("#file-preview").html("<img src='"+(URL.createObjectURL(e.target.files[0]))+"' class='img img-responsive img-thumbnail'> <br> <i class='fa fa-check'></i> "+$(this).val().replace(/C:\\fakepath\\/i, ''));
    });

</script>