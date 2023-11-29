<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Detail Thread/Post</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url('master/post') }}">Post</a>
                    </li>
                    <li class="active">
                        <strong>Detail Thread/Post</strong>
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
                        <h5>Detail Thread/Post</h5>
                    </div>
                    <div class="ibox-content">
                      @if ($errors->any())
                          <div class="alert alert-danger">
                              Ada kesalahan! mohon cek formulir.
                              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                          </div>
                      @endif
                      @include('backend.flash_message')
                      {!! Form::model($data,['url' => url('admin/category/update/'.$data->id_product_category), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
                        <div class="form-group {{ ($errors->has('first_name')?"has-error":"") }}"><label class="col-sm-2 control-label">Time created</label>
                            <div class="col-sm-4 col-xs-12">
                                <input type="text" class="form-control disabled" disabled readonly value="<?=date("Y-m-d H:i:s",$data->time_created)?>">
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('first_name')?"has-error":"") }}"><label class="col-sm-2 control-label">Last update</label>
                            <div class="col-sm-4 col-xs-12">
                                <input type="text" class="form-control disabled" disabled readonly value="<?=date("Y-m-d H:i:s",$data->last_update)?>">
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('brand_name')?"has-error":"") }}"><label class="col-sm-2 control-label">Photo</label>
                            <div class="col-sm-4 col-xs-12">
                                <img src="<?=$data->photo?>" class="img-thumbnail">
                                {!! $errors->first('brand_name', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('permalink')?"has-error":"") }}"><label class="col-sm-2 control-label">Permalink</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::text('permalink', null, ['class' => 'form-control disabled','disabled' => 'true']) !!}
                                {!! $errors->first('permalink', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('caption')?"has-error":"") }}"><label class="col-sm-2 control-label">Caption</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::text('caption', null, ['class' => 'form-control disabled','disabled' => 'true']) !!}
                                {!! $errors->first('caption', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('mention')?"has-error":"") }}"><label class="col-sm-2 control-label">Mention</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::text('mention', null, ['class' => 'form-control disabled','disabled' => 'true']) !!}
                                {!! $errors->first('mention', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('hashtag')?"has-error":"") }}"><label class="col-sm-2 control-label">Hashtag</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::text('hashtag', null, ['class' => 'form-control disabled','disabled' => 'true']) !!}
                                {!! $errors->first('hashtag', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('total_like')?"has-error":"") }}"><label class="col-sm-2 control-label">Total Like</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::text('total_like', null, ['class' => 'form-control disabled','disabled' => 'true']) !!}
                                {!! $errors->first('total_like', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('total_like')?"has-error":"") }}">
                          <label class="col-sm-2 control-label">People who likes</label>
                          <div class="col-sm-4 col-xs-12">
                              <div class="row">
                                <?php
                                if($wholikes){
                                  foreach ($wholikes as $key => $value) {

                                    $photo  = $value->avatar;
                                    $filename = basename($photo);
                                    $path   = str_replace($filename,"thumbnail_".$filename,$photo);

                                    ?><div class="col-sm-12"><img src="<?=url($path)?>" class="img img-thumbnail img-circle" style="width:60px;"> <?=$value->first_name?> (<?=$value->email?>) on <?=date("M d Y, H:i:s",$value->time_created)?></div><?php
                                  }
                                }else{
                                  print "no data found";
                                }
                                ?>
                              </div>
                          </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('total_comment')?"has-error":"") }}"><label class="col-sm-2 control-label">Total Comment</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::text('total_comment', null, ['class' => 'form-control disabled','disabled' => 'true']) !!}
                                {!! $errors->first('total_comment', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group {{ ($errors->has('total_like')?"has-error":"") }}">
                          <label class="col-sm-2 control-label">People who comments</label>
                          <div class="col-sm-4 col-xs-12">
                              <div class="row">
                                <?php
                                  if($whocomment){
                                    foreach ($whocomment as $key => $value) {

                                      $photo  = $value->avatar;
                                      $filename = basename($photo);
                                      $path   = str_replace($filename,"thumbnail_".$filename,$photo);

                                      ?><div class="col-sm-12"><img src="<?=url($path)?>" class="img img-thumbnail img-circle" style="width:60px;"> <?=$value->first_name?> (<?=$value->email?>) on <?=date("M d Y, H:i:s",$value->time_created)?></div><?php
                                    }
                                  }else{
                                    print "no data found";
                                  }
                                ?>
                              </div>
                          </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('status')?"has-error":"") }}"><label class="col-sm-2 control-label">Status</label>
                            <div class="col-sm-4 col-xs-12">
                              {!! Form::select('status', ['active' => 'Active', 'inactive' => 'Inactive'], null, ['class' => 'form-control disabled','disabled' => 'disabled', 'readonly' => 'readonly']) !!}
                              {!! $errors->first('status', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                                <a class="btn btn-white" href="{{ url($config['main_url']) }}">
                                    <i class="fa fa-angle-left"></i> back
                                </a>
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
