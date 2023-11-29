<main role="main">

  <section class="jumbotron text-center">
    <div class="container">
      <div class="superline"></div>
      <div class="custom-container">
        <h1>COMPLAIN</h1>
      </div>
    </div>

    <div class="container text-center top100">
      <h3>Kelola Komplain</h3>
      <p class="text-gray">Mohon menceritakan dengan detail mengenai komplain yang anda lakukan</p>
      <p class="description"><a href='{{ url('complain') }}' class="text-black"><i class='fa fa-arrow-left'></i> kembali</a></p>
    </div>
  </section>

  <div class="album py-1 pb-5 top30">
    <div class="container">
      <div class="row justify-content-md-center">
        <div class="col-12 col-sm-6">

          @include('backend.flash_message')
          {!! Form::model($data, ['files'=>'true','url' => url('complain-store-comment/'.$data->id_ticket), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}

            <div class="form-group {{ ($errors->has('ticket_code')?"has-error":"") }}"><label class="col-sm-12 control-label">Ticket code</label>
                <div class="col-xs-12">
                    {!! Form::text('ticket_code',null, ['class' => 'form-control disabled' ,'disabled']) !!}
                    {!! $errors->first('ticket_code', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group {{ ($errors->has('subject')?"has-error":"") }}"><label class="col-sm-2 control-label">Subject</label>
                <div class="col-xs-12">
                    {!! Form::text('subject', null, ['class' => 'form-control disabled' ,'disabled','rows' => '3']) !!}
                    {!! $errors->first('subject', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group {{ ($errors->has('status')?"has-error":"") }}"><label class="col-sm-12 control-label">Status</label>
                <div class="col-xs-12">
                <?php
                  $status = array('open' => 'Open', 'close' => 'Closed');
                ?>
                {!! Form::text('status',$status[$data->status], ['class' => 'form-control disabled' ,'disabled']) !!}
                </div>
                <div class="top20">
                  <?php if($data->status=="open"){ ?>
                  <a class="btn btn-success btn-block text-white" href="{{ url('complain-done/'.$data->id_ticket) }}">
                      <i class="fa fa-check"></i> close support
                  </a>

                  <?php } ?>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="card">
              <h3>Percakapan</h3>
              <div class="well">
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
                  }else{
                    echo "<p>Belum ada percakapan</p>";
                  }
                ?>
              </div>
            </div>
            <?php if($data->status=="open"){ ?>
            <div class="hr-line-dashed top30"></div>
            <div class="form-group {{ ($errors->has('status')?"has-error":"") }}">
                <label class="col-sm-12 control-label">Pesan anda</label>
                <div class="col-xs-12">
                  {!! Form::textarea('content', null, ['class' => 'form-control','rows' => 3]) !!}
                  {!! $errors->first('content', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                  <br>
                  {!! Form::file('photo', null, ['class' => 'form-control']) !!}
                </div>
            </div>
            <?php } ?>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <div class="12">
                  <?php if($data->status=="open"){ ?>
                  <button class="btn btn-primary btn-block" type="submit">SAMPAIKAN PESAN</button>
                  <?php } ?>
                </div>
            </div>
          {!! Form::close() !!}
        </div>
      </div>
    </div>
  </div>
</div>
</main>
