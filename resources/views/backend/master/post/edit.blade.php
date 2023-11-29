<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Edit Thread/Post</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url('master/post') }}">Threads/Posts</a>
                    </li>
                    <li class="active">
                        <strong>Edit Thread/Post</strong>
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
                        <h5>Edit Thread/Post</h5>
                    </div>
                    <div class="ibox-content">
                      @if ($errors->any())
                          <div class="alert alert-danger">
                              Oops! Please check your input.
                              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                          </div>
                      @endif
                      @include('backend.flash_message')
                      {!! Form::model($data,['url' => url($config['main_url'].'/update/'.$data->id_post), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}

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
                                {!! Form::textarea('caption', null, ['class' => 'form-control','rows' => 5]) !!}
                                {!! $errors->first('caption', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('mention')?"has-error":"") }}"><label class="col-sm-2 control-label">Mention</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::textarea('mention', null, ['class' => 'form-control','rows' => 5,'rows' => 5]) !!}
                                {!! $errors->first('mention', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('hashtag')?"has-error":"") }}"><label class="col-sm-2 control-label">Hashtag</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::textarea('hashtag', null, ['class' => 'form-control','rows' => 5]) !!}
                                {!! $errors->first('hashtag', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('status')?"has-error":"") }}"><label class="col-sm-2 control-label">Status</label>
                            <div class="col-sm-4 col-xs-12">
                              {!! Form::select('status', ['active' => 'Active', 'inactive' => 'Inactive'], null, ['class' => 'form-control']) !!}
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
                            <div class="col-sm-6 text-right">
                              <button class="btn btn-white" type="reset">Reset</button>
                              <button class="btn btn-primary" type="submit">Save Changes</button>
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
