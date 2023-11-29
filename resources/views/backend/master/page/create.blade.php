<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
	@include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Pages</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url('master-page') }}">Pages</a>
                    </li>
                    <li class="active">
                        <strong>Add Page</strong>
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
                        <h5>Add Page</h5>
                    </div>
                    <div class="ibox-content">
                      @if ($errors->any())
                          <div class="alert alert-danger">
                              Oops!! Please check the form below!
                              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                          </div>
                      @endif
                      @include('backend.flash_message')
                      {!! Form::open(['url' => url($config['main_url'].'/store'), 'files'=>'true', 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}

                         <div class="form-group {{ ($errors->has('page_code')?"has-error":"") }}"><label class="col-sm-2 control-label">Page Code</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::text('page_code', null, ['class' => 'form-control']) !!}
                                {!! $errors->first('page_code', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
						
						<div class="form-group {{ ($errors->has('title')?"has-error":"") }}"><label class="col-sm-2 control-label">Title</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::text('title', null, ['class' => 'form-control']) !!}
                                {!! $errors->first('title', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
						
						<div class="form-group {{ ($errors->has('keyword')?"has-error":"") }}"><label class="col-sm-2 control-label">Keyword</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::text('keyword', null, ['class' => 'form-control']) !!}
                                {!! $errors->first('keyword', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
						
						<div class="form-group {{ ($errors->has('content')?"has-error":"") }}"><label class="col-sm-2 control-label">Content</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::textarea('content', null, null, ['class' => 'form-control','rows' => 5]) !!}
                                {!! $errors->first('content', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
						
                        <div class="form-group {{ ($errors->has('status')?"has-error":"") }}"><label class="col-sm-2 control-label">Status</label>
                            <div class="col-sm-4 col-xs-12">
                              {!! Form::select('status', ['active' => 'Aktif', 'inactive' => 'Tidak Aktif'], null, ['class' => 'form-control']) !!}
                              {!! $errors->first('status', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                                <a class="btn btn-white" href="{{ url('master-page') }}">
                                    <i class="fa fa-angle-left"></i> kembali
                                </a>
                            </div>
                            <div class="col-sm-6 text-right">
                              <button class="btn btn-white" type="reset">Reset</button>
                              <button class="btn btn-primary" type="submit">Simpan</button>
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
