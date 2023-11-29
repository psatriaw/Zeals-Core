<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Create Withdrawal</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url('master/withdrawal') }}">Withdrawals</a>
                    </li>
                    <li class="active">
                        <strong>Create Withdrawal</strong>
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
                        <h5>Create Withdrawal</h5>
                    </div>
                    <div class="ibox-content">
                      @if ($errors->any())
                          <div class="alert alert-danger">
                              Ada kesalahan! mohon cek formulir.
                              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                          </div>
                      @endif

                      @include('backend.flash_message')
                      {!! Form::model($data,['url' => url($config['main_url'].'/store/'), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
                        <div class="form-group {{ ($errors->has('bank_name')?"has-error":"") }}"><label class="col-sm-2 control-label">Bank name</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::text('bank_name', $data->bank_name, ['class' => 'form-control disabled', "disabled" => true]) !!}
                                {!! Form::hidden('id_shop', $data->id_shop, ['class' => 'form-control']) !!}
                                {!! $errors->first('bank_name', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group {{ ($errors->has('account_name')?"has-error":"") }}"><label class="col-sm-2 control-label">Account name</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::text('account_name', $data->account_name, ['class' => 'form-control disabled', "disabled" => true]) !!}
                                {!! $errors->first('account_name', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>

                        <div class="form-group {{ ($errors->has('account_number')?"has-error":"") }}"><label class="col-sm-2 control-label">Account number</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::text('account_number', $data->account_number, ['class' => 'form-control disabled', "disabled" => true]) !!}
                                {!! $errors->first('account_number', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>

                        <div class="form-group {{ ($errors->has('balance')?"has-error":"") }}"><label class="col-sm-2 control-label">Your Balance</label>
                            <div class="col-sm-4 col-xs-12">
                                <div class="input-group">
                                  <div class="input-group-addon">$</div>
                                  {!! Form::text('balance', $balance, ['class' => 'form-control', "readonly" => true]) !!}
                                </div>

                                {!! $errors->first('balance', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>

                        <div class="form-group {{ ($errors->has('total_amount')?"has-error":"") }}"><label class="col-sm-2 control-label">Total Amount</label>
                            <div class="col-sm-4 col-xs-12">
                              <div class="input-group">
                                <div class="input-group-addon">$</div>
                                {!! Form::text('total_amount', 0, ['class' => 'form-control']) !!}
                              </div>
                                {!! $errors->first('total_amount', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                <div class="alert alert-info top30">
                                  Note: Withdrawal request will be processed within 3 - 4 business days.
                                </div>
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                              <a class="btn btn-white" href="{{ url($config['main_url']) }}">
                                  <i class="fa fa-angle-left"></i> BACK
                              </a>
                            </div>
                            <div class="col-sm-6 text-right">
                              <button class="btn btn-white" type="reset">RESET</button>
                              <button class="btn btn-primary" type="submit">CREATE REQUEST</button>
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
