<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Ticket</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('admin/master') }}">Master</a>
                    </li>
                    <li>
                        <a href="{{ url('admin/discount') }}">Layanan</a>
                    </li>
                    <li class="active">
                        <strong>Manage Ticket</strong>
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
                        <h5>Manage Ticket</h5>
                    </div>
                    <div class="ibox-content">
                      @if ($errors->any())
                          <div class="alert alert-danger">
                              Ada kesalahan! mohon cek formulir.
                              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                          </div>
                      @endif
                      @include('backend.flash_message')
                      {!! Form::model($data, ['files'=>'true','url' => url('master/ticket/storecomment/'.$data->id_ticket), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}

                        <div class="form-group {{ ($errors->has('ticket_code')?"has-error":"") }}"><label class="col-sm-2 control-label">Kode Ticket</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::text('ticket_code',null, ['class' => 'form-control disabled' ,'disabled']) !!}
                                {!! $errors->first('ticket_code', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('subject')?"has-error":"") }}"><label class="col-sm-2 control-label">Subjek</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::text('subject', null, ['class' => 'form-control disabled' ,'disabled','rows' => '3']) !!}
                                {!! $errors->first('subject', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('sender')?"has-error":"") }}"><label class="col-sm-2 control-label">Pembuat</label>
                            <div class="col-sm-4 col-xs-12">
                              {!! Form::text('name_sender',$data->name_sender, ['class' => 'form-control disabled' ,'disabled']) !!}
                              {!! $errors->first('ticket_code', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('target')?"has-error":"") }}"><label class="col-sm-2 control-label">Penerima</label>
                            <div class="col-sm-4 col-xs-12">
                              {!! Form::text('name_target',$data->name_target, ['class' => 'form-control disabled' ,'disabled']) !!}
                              {!! $errors->first('ticket_code', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('status')?"has-error":"") }}"><label class="col-sm-2 control-label">Status</label>
                            <div class="col-sm-4 col-xs-12">
                            <?php
                              $status = array('open' => 'Dibuka', 'close' => 'Selesai/Tutup');
                            ?>
                            {!! Form::text('status',$status[$data->status], ['class' => 'form-control disabled' ,'disabled']) !!}
                            </div>
                            <div class="col-sm-4">
                              <?php if($data->status=="open"){ ?>
                              <a class="btn btn-primary confirm" data-url="{{ url('master/ticket/markdone/') }}" data-id="{{ $data->id_ticket }}">
                                  <i class="fa fa-check"></i> Tandai sudah selesai
                              </a>

                              <?php } ?>
                              <?php if($previlege->isAllow($login->id_user,$login->id_department,"master-ticket-remove")){?>
                                  <a data-id="{{ $data->id_ticket }}" data-url="{{ url('master/ticket/remove/') }}" class="btn btn-danger confirm"><i class="fa fa-trash"></i> hapus</a>
                              <?php }?>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                          <div class="col-sm-10 col-sm-offset-2">
                            <label class="control-label">Percakapan</label>
                            <div class="chatbox">
                              <?php
                                if($percakapan){
                                  foreach ($percakapan as $key => $value) {
                                    if($login->id_user==$value->author){
                                      ?>
                                      @include('backend.master.ticket.comment_pembicara',$value)
                                      <?php
                                    }else{
                                      ?>
                                      @include('backend.master.ticket.comment_lawanbicara',$value),
                                      <?php
                                    }
                                  }
                                }
                              ?>
                            </div>
                          </div>
                        </div>
                        <?php if($data->status=="open"){ ?>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('status')?"has-error":"") }}">
                            <label class="col-sm-2 control-label">Balasan</label>
                            <div class="col-sm-10 col-xs-12">
                              {!! Form::textarea('content', null, ['class' => 'form-control','rows' => 3]) !!}
                              {!! $errors->first('content', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                              <br>
                              {!! Form::file('photo', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <?php } ?>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                                <a class="btn btn-white" href="{{ url('master/ticket') }}">
                                    <i class="fa fa-angle-left"></i> kembali
                                </a>
                            </div>
                            <div class="col-sm-6 text-right">
                              <?php if($data->status=="open"){ ?>
                              <button class="btn btn-white" type="reset">Reset</button>
                              <button class="btn btn-primary" type="submit">Kirim</button>
                              <?php } ?>
                            </div>
                        </div>
                      {!! Form::close() !!}
                    </div>
                </div>
                </div>
            </div>
        </div>
    @include('backend.footer')
    @include('backend.do_confirm')
  </div>
</div>
<script>
  $(document).ready(function() {
    $('.thetarget, .thesender').select2({
      ajax: {
        url: '{{ url("get-list-user") }}',
        dataType: 'json',
        data: function (params) {
          var query = {
            search: params.term,
            type: 'public'
          }
          // Query parameters will be ?search=[term]&type=public
          return query;
        }
      }
    });
  });
</script>
