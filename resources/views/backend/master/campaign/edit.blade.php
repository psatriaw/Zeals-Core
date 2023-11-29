<?php
  $campaigntypes = array(
    "o2o"         => "O2O",
    "banner"      => "Banner - AMP",
    "shopee"      => "Shopee - AMP",
    "event"       => "Event",
  );

  if($detail->running_status=="open"){
    $editable = "readonly";
  }else{
    $editable = "";
  }

  //print_r($detail->toArray());
  //exit();
?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<div id="wrapper">
    @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
    <div id="page-wrapper" class="gray-bg">
      @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Campaign</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url('master/campaign') }}">Campaign</a>
                    </li>
                    <li class="active">
                        <strong>Edit Campaign</strong>
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
                            <h5>Edit Campaign</h5>
                        </div>
                        <div class="ibox-content">
                            @if ($errors->any())
                            <div class="alert alert-danger">
                                Ada kesalahan! mohon cek formulir.
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            </div>
                            @endif
                            @include('backend.flash_message')
                            <!-- campaign title -->
                            <div class="row">
                              <div class="col-lg-6 col-xs-12">
                                {!! Form::model($detail,['url' => url('master/campaign/update/'.$detail->id_campaign), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate novalidate']) !!}
                                <input type="hidden" name="id_campaign" value="<?=$detail->id_campaign?>">
                                <input type="hidden" name="campaign_link" value="<?=$detail->campaign_link?>">
                                <input type="hidden" name="backlink" value="<?=url('master/campaign/edit/'.$detail->campaign_link)?>">

                                <h3>Detail Campaign</h3>

                                <div class="form-group {{ ($errors->has('campaign_title')?"has-error":"") }}"><label class="col-sm-4 control-label">Type</label>
                                    <div class="col-sm-8 col-xs-12">
                                        {!! Form::text('ct', $campaigntypes[$detail->campaign_type], ['class' => 'form-control','readonly']) !!}
                                        {!! $errors->first('ct', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                    </div>
                                </div>

                                <div class="form-group {{ ($errors->has('campaign_title')?"has-error":"") }}"><label class="col-sm-4 control-label">Campaign Title</label>
                                    <div class="col-sm-8 col-xs-12">
                                        {!! Form::text('campaign_title', $detail->campaign_title, ['class' => 'form-control',$editable]) !!}
                                        {!! $errors->first('campaign_title', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                    </div>
                                </div>

                                <!-- id user -->
                                <div class="form-group {{ ($errors->has('id_penerbit')?"has-error":"") }}"><label class="col-sm-4 control-label">Brand</label>
                                    <div class="col-sm-8 col-xs-12">
                                        <?php if(@$id_penerbit==''){ ?>
                                            {!! Form::text('id_penerbit', $penerbits[$detail->id_penerbit], ['class' => 'form-control thetarget disabled','disabled' => true]) !!}
                                        <?php }else{?>
                                            {!! Form::text('id_penerbit', $penerbits[@$id_penerbit], ['class' => 'form-control',(@$id_penerbit!='')?'readonly':'']) !!}
                                        <?php }?>

                                        {!! $errors->first('id_penerbit', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                    </div>
                                </div>
                                <!-- campaign img -->
                                <div class="form-group {{ ($errors->has('photos')?"has-error":"") }}"><label class="col-sm-4 control-label">Banner<sub>(6:4)</sub></label>
                                    <div class="col-sm-8 col-xs-12">
                                        <div class="">
                                          <div class="row">
                                            <div class="col-sm-6">
                                                <?php
                                                  $banner = $detail->photos;
                                                ?>
                                                <img class='img img-responsive img-thumbnail' src="<?=url($banner)?>">
                                            </div>
                                          </div>
                                        </div>
                                        <input type="file" name="photos" <?=($editable=="readonly")?"style='display:none;'":""?>>
                                        {!! $errors->first('photos', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group {{ ($errors->has('budget')?"has-error":"") }}"><label class="col-sm-4 control-label">Budget</label>
                                    <div class="col-sm-8 col-xs-12">
                                      <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        {!! Form::text('budget', $detail->budget, ['class' => 'form-control text-right',$editable]) !!}
                                      </div>
                                        {!! $errors->first('budget', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                    </div>
                                </div>

                                <div class="form-group {{ ($errors->has('campaign_internal')?"has-error":"") }}"><label class="col-sm-4 control-label">Campaign Type</label>
                                    <div class="col-sm-8 col-xs-12">
                                      {!! Form::select('campaign_internal', ['yes' => 'Hidden (Support/Internal use)', '' => 'Public'],$detail->campaign_internal, ['class' => 'form-control']) !!}
                                      {!! $errors->first('campaign_internal', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                    </div>
                                </div>

                                <!-- campaign description -->
                                <div class="form-group {{ ($errors->has('max_commission')?"has-error":"") }}"><label class="col-sm-4 control-label">Max Commission <sub>(per influencer)</sub></label>
                                    <div class="col-sm-8 col-xs-12">
                                      <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        {!! Form::text('max_commission', $detail->max_commission, ['class' => 'form-control text-right',$editable]) !!}
                                      </div>
                                        {!! $errors->first('max_commission', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                    </div>
                                </div>
                                <!-- campaign description -->
                                <div class="form-group {{ ($errors->has('campaign_description')?"has-error":"") }}"><label class="col-sm-4 control-label">Campaign Description</label>
                                    <div class="col-sm-8 col-xs-12">
                                        {!! Form::textarea('campaign_description', null, ['class' => 'form-control','rows' => 10,$editable]) !!}
                                        {!! $errors->first('campaign_description', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                    </div>
                                </div>
                                <!-- campaign description -->
                                <div class="form-group {{ ($errors->has('campaign_instruction')?"has-error":"") }}"><label class="col-sm-4 control-label">Campaign Instruction</label>
                                    <div class="col-sm-8 col-xs-12">
                                        {!! Form::textarea('campaign_instruction', null, ['class' => 'form-control','rows' => 10,$editable]) !!}
                                        {!! $errors->first('campaign_instruction', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                    </div>
                                </div>
                                <!-- Start date -->
                                <!-- campaign description -->
                                <div class="form-group {{ ($errors->has('campaign_do_n_dont')?"has-error":"") }}"><label class="col-sm-4 control-label">Do & Don't</label>
                                    <div class="col-sm-8 col-xs-12">
                                        {!! Form::textarea('campaign_do_n_dont', null, ['class' => 'form-control','rows' => 10,$editable]) !!}
                                        {!! $errors->first('campaign_do_n_dont', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                    </div>
                                </div>
                                <!-- Start date -->
                                <div class="form-group {{ ($errors->has('start_date')?"has-error":"") }}"><label class="col-sm-4 control-label">Start Date</label>
                                    <div class="col-sm-3 col-xs-12">
                                        {!! Form::date('start_date', null, ['class' => 'form-control',$editable]) !!}
                                        {!! $errors->first('start_date', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                    </div>
                                </div>
                                <!-- end date -->
                                <div class="form-group {{ ($errors->has('end_date')?"has-error":"") }}"><label class="col-sm-4 control-label">End Date</label>
                                    <div class="col-sm-3 col-xs-12">
                                        {!! Form::date('end_date', null, ['class' => 'form-control',$editable]) !!}
                                        {!! $errors->first('end_date', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                </div>
                                <div class="col-xs-12 col-sm-8">
                                  <button type="submit" class="btn btn-primary btn-md btn-rounded" type="submit">Save Campaign Detail</button>
                                </div>

                                {!! Form::close() !!}
                              </div>

                              <div class="col-lg-6 col-xs-12">
                                {!! Form::model($detail,['url' => url('master/campaign/update/'.$detail->id_campaign), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate novalidate']) !!}
                                  <input type="hidden" name="backlink" value="<?=url('master/campaign/edit/'.$detail->campaign_link)?>">
                                  <input type="hidden" name="update_type" value="program">

                                  <h3>Detail Program</h3>
                                  <?php if(@$program['visit']){ ?>

                                  <div class="form-group {{ ($errors->has('visit_total')?"has-error":"") }}"><label class="col-sm-4 control-label">Max visitor</label>
                                      <div class="col-sm-8 col-xs-12">
                                        <div class="input-group">
                                          {!! Form::text('visit_total', @$program['visit']->total_item, ['class' => 'form-control' ,'multiple' => true]) !!}
                                          <span class="input-group-addon" style="background:#5eb6f0 !important;">Visitors</span>
                                        </div>
                                          {!! $errors->first('visit_total', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                      </div>
                                  </div>

                                  <!-- Target fund -->
                                  <div class="form-group {{ ($errors->has('visit_commission')?"has-error":"") }}"><label class="col-sm-4 control-label">Commission per visitor </label>
                                      <div class="col-sm-8 col-xs-12">
                                        <div class="input-group">
                                          <span class="input-group-addon" style="background:#5eb6f0 !important;">Rp. </span>
                                          {!! Form::text('visit_commission', @$program['visit']->commission, ['class' => 'form-control' ,'multiple' => true]) !!}
                                        </div>
                                          {!! $errors->first('visit_commission', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                      </div>
                                  </div>

                                  <!-- Target fund -->
                                  <div class="form-group {{ ($errors->has('visit_fee')?"has-error":"") }}"><label class="col-sm-4 control-label">Fee per visitor </label>
                                      <div class="col-sm-8 col-xs-12">
                                          <div class="input-group">
                                            <span class="input-group-addon" style="background:#5eb6f0 !important;">Rp. </span>
                                            {!! Form::text('visit_fee', @$program['visit']->fee, ['class' => 'form-control' ,'multiple' => true]) !!}
                                          </div>
                                          {!! $errors->first('visit_fee', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                      </div>
                                  </div>

                                  <?php } ?>

                                  <?php if(@$program['read']){ ?>
                                  <div class="hr-line-dashed"></div>
                                  <!-- Target fund -->
                                  <div class="form-group {{ ($errors->has('reader_total')?"has-error":"") }}"><label class="col-sm-4 control-label">Max reader</label>
                                      <div class="col-sm-8 col-xs-12">
                                        <div class="input-group">
                                          {!! Form::text('reader_total', @$program['read']->total_item, ['class' => 'form-control' ,'multiple' => true]) !!}
                                          <span class="input-group-addon" style="background:#c82360 !important;">Readers</span>
                                        </div>
                                          {!! $errors->first('reader_total', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                      </div>
                                  </div>

                                  <!-- Target fund -->
                                  <div class="form-group {{ ($errors->has('reader_commission')?"has-error":"") }}"><label class="col-sm-4 control-label">Commission per reader </label>
                                      <div class="col-sm-8 col-xs-12">
                                        <div class="input-group">
                                          <span class="input-group-addon" style="background:#c82360 !important;">Rp. </span>
                                          {!! Form::text('reader_commission', @$program['read']->commission, ['class' => 'form-control' ,'multiple' => true]) !!}
                                        </div>
                                          {!! $errors->first('reader_commission', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                      </div>
                                  </div>

                                  <!-- Target fund -->
                                  <div class="form-group {{ ($errors->has('reader_fee')?"has-error":"") }}"><label class="col-sm-4 control-label">Fee per reader </label>
                                      <div class="col-sm-8 col-xs-12">
                                          <div class="input-group">
                                            <span class="input-group-addon" style="background:#c82360 !important;">Rp. </span>
                                            {!! Form::text('reader_fee', @$program['read']->fee, ['class' => 'form-control' ,'multiple' => true]) !!}
                                          </div>
                                          {!! $errors->first('reader_fee', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                      </div>
                                  </div>
                                  <?php } ?>

                                  <?php if(@$program['action']){ ?>
                                  <div class="hr-line-dashed"></div>

                                  <!-- Target fund -->
                                  <div class="form-group {{ ($errors->has('action_total')?"has-error":"") }}"><label class="col-sm-4 control-label">Max action</label>
                                      <div class="col-sm-8 col-xs-12">
                                        <div class="input-group">
                                          {!! Form::text('action_total', @$program['action']->total_item, ['class' => 'form-control' ,'multiple' => true]) !!}
                                          <span class="input-group-addon" style="background:#fcb13b !important;">Actions</span>
                                        </div>
                                          {!! $errors->first('action_total', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                      </div>
                                  </div>

                                  <!-- Target fund -->
                                  <div class="form-group {{ ($errors->has('action_commission')?"has-error":"") }}"><label class="col-sm-4 control-label">Commission per action </label>
                                      <div class="col-sm-8 col-xs-12">
                                        <div class="input-group">
                                          <span class="input-group-addon" style="background:#fcb13b !important;">Rp. </span>
                                          {!! Form::text('action_commission', @$program['action']->commission, ['class' => 'form-control' ,'multiple' => true]) !!}
                                        </div>
                                          {!! $errors->first('action_commission', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                      </div>
                                  </div>

                                  <!-- Target fund -->
                                  <div class="form-group {{ ($errors->has('action_fee')?"has-error":"") }}"><label class="col-sm-4 control-label">Fee per action </label>
                                      <div class="col-sm-8 col-xs-12">
                                          <div class="input-group">
                                            <span class="input-group-addon" style="background:#fcb13b !important;">Rp. </span>
                                            {!! Form::text('action_fee', @$program['action']->fee, ['class' => 'form-control' ,'multiple' => true]) !!}
                                          </div>
                                          {!! $errors->first('action_fee', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                      </div>
                                  </div>
                                  <?php } ?>

                                  <?php if(@$program['acquisition']){ ?>
                                  <?php
                                    $callback_mode = true;
                                  ?>
                                  <div class="hr-line-dashed"></div>
                                  <!-- Target fund -->
                                  <div class="form-group {{ ($errors->has('acquisition_total')?"has-error":"") }}"><label class="col-sm-4 control-label">Max acquisition</label>
                                      <div class="col-sm-8 col-xs-12">
                                        <div class="input-group">
                                          {!! Form::text('acquisition_total', @$program['acquisition']->total_item, ['class' => 'form-control' ,'multiple' => true]) !!}
                                          <span class="input-group-addon" style="background:#961515 !important;">Acquisitions</span>
                                        </div>
                                          {!! $errors->first('acquisition_total', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                      </div>
                                  </div>

                                  <div class="form-group {{ ($errors->has('acquisition_total')?"has-error":"") }}"><label class="col-sm-4 control-label">Comission Type</label>
                                      <div class="col-sm-8 col-xs-12">
                                        
                                          {!! Form::select('acquisition_type',['amount' => 'Amount','percent' => 'Percent'],  @$program['acquisition']->type, ['class' => 'form-control','onchange' => 'selectTypeCommission(this.value)']) !!}
                                          {!! $errors->first('acquisition_total', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                      </div>
                                  </div>

                                  <!-- Target fund -->
                                  <div class="form-group {{ ($errors->has('acquisition_commission')?"has-error":"") }}"><label class="col-sm-4 control-label">Commission per acquisition </label>
                                      <div class="col-sm-8 col-xs-12">
                                        <div class="input-group">
                                          <span class="input-group-addon com-type" style="background:#961515 !important;" id="">
                                            <?php 
                                              if($program['acquisition']->type=='percent'){
                                                echo "%";
                                              }else{
                                                echo "Rp.";
                                              }
                                            ?>
                                          </span>
                                          {!! Form::text('acquisition_commission', @$program['acquisition']->commission, ['class' => 'form-control' ,'multiple' => true]) !!}
                                        </div>
                                          {!! $errors->first('acquisition_commission', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                      </div>
                                  </div>

                                  <script>
                                      function selectTypeCommission(val){
                                        var type = "Rp.";

                                        if(val=='percent'){
                                          type  = "%";
                                        }

                                        $(".com-type").html(type);
                                      }
                                  </script>

                                  <!-- Target fund -->
                                  <div class="form-group {{ ($errors->has('acquisition_fee')?"has-error":"") }}"><label class="col-sm-4 control-label">Fee per acquisition </label>
                                      <div class="col-sm-8 col-xs-12">
                                          <div class="input-group">
                                            <span class="input-group-addon com-type" style="background:#961515 !important;">
                                            <?php 
                                              if($program['acquisition']->type=='percent'){
                                                echo "%";
                                              }else{
                                                echo "Rp.";
                                              }
                                            ?>
                                            </span>
                                            {!! Form::text('acquisition_fee', @$program['acquisition']->fee, ['class' => 'form-control' ,'multiple' => true]) !!}
                                          </div>
                                          {!! $errors->first('acquisition_fee', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                      </div>
                                  </div>

                                  <?php } ?>

                                  <?php if(@$program['voucher']){ ?>
                                  <?php
                                    $callback_mode = true;
                                  ?>
                                  <div class="hr-line-dashed"></div>

                                  <!-- Target fund -->
                                  <div class="form-group {{ ($errors->has('voucher_total')?"has-error":"") }}"><label class="col-sm-4 control-label">Max voucher</label>
                                      <div class="col-sm-8 col-xs-12">
                                        <div class="input-group">
                                          {!! Form::text('voucher_total', @$program['voucher']->total_item, ['class' => 'form-control' ,'multiple' => true]) !!}
                                          <span class="input-group-addon" style="background:#961515 !important;">vouchers</span>
                                        </div>
                                          {!! $errors->first('voucher_total', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                      </div>
                                  </div>

                                  <!-- Target fund -->
                                  <div class="form-group {{ ($errors->has('voucher_commission')?"has-error":"") }}"><label class="col-sm-4 control-label">Commission per redemption </label>
                                      <div class="col-sm-8 col-xs-12">
                                        <div class="input-group">
                                          <span class="input-group-addon" style="background:#961515 !important;">Rp. </span>
                                          {!! Form::text('voucher_commission', @$program['voucher']->commission, ['class' => 'form-control' ,'multiple' => true]) !!}
                                        </div>
                                          {!! $errors->first('voucher_commission', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                      </div>
                                  </div>

                                  <!-- Target fund -->
                                  <div class="form-group {{ ($errors->has('voucher_fee')?"has-error":"") }}"><label class="col-sm-4 control-label">Fee per redemption </label>
                                      <div class="col-sm-8 col-xs-12">
                                          <div class="input-group">
                                            <span class="input-group-addon" style="background:#961515 !important;">Rp. </span>
                                            {!! Form::text('voucher_fee', @$program['voucher']->fee, ['class' => 'form-control' ,'multiple' => true]) !!}
                                          </div>
                                          {!! $errors->first('voucher_fee', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                      </div>
                                  </div>

                                  <?php } ?>

                                  <?php if(@$program['cashback']){ ?>
                                  <?php
                                    $callback_mode = true;
                                  ?>
                                  <div class="hr-line-dashed"></div>

                                  <!-- Target fund -->
                                  <div class="form-group {{ ($errors->has('voucher_total')?"has-error":"") }}"><label class="col-sm-4 control-label">Max transaction</label>
                                      <div class="col-sm-8 col-xs-12">
                                        <div class="input-group">
                                          {!! Form::text('voucher_total', @$program['cashback']->total_item, ['class' => 'form-control' ,'multiple' => true , $editable]) !!}
                                          <span class="input-group-addon" style="background:#e76734 !important;border-top-right-radius:15px;border-bottom-right-radius:15px;">transactions</span>
                                        </div>
                                          {!! $errors->first('voucher_total', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                      </div>
                                  </div>

                                  <!-- Target fund -->
                                  <div class="form-group {{ ($errors->has('voucher_commission')?"has-error":"") }}"><label class="col-sm-4 control-label">Commission per transaction </label>
                                      <div class="col-sm-8 col-xs-12">
                                        <div class="input-group">
                                          {!! Form::text('voucher_commission', @$program['cashback']->commission, ['class' => 'form-control' ,'multiple' => true,$editable]) !!}
                                          <span class="input-group-addon" style="background:#e76734 !important;border-top-right-radius:15px;border-bottom-right-radius:15px;">%</span>
                                        </div>
                                          {!! $errors->first('voucher_commission', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                      </div>
                                  </div>

                                  <!-- Target fund -->
                                  <div class="form-group {{ ($errors->has('voucher_fee')?"has-error":"") }}"><label class="col-sm-4 control-label">Fee per transaction </label>
                                      <div class="col-sm-8 col-xs-12">
                                          <div class="input-group">
                                            {!! Form::text('voucher_fee', @$program['cashback']->fee, ['class' => 'form-control' ,'multiple' => true, $editable]) !!}
                                            <span class="input-group-addon" style="background:#e76734 !important;border-top-right-radius:15px;border-bottom-right-radius:15px;">%</span>
                                          </div>
                                          {!! $errors->first('voucher_fee', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                      </div>
                                  </div>

                                  <div class="form-group {{ ($errors->has('custom_link')?"has-error":"") }}"><label class="col-sm-4 control-label">Shopee URL</label>
                                      <div class="col-sm-8 col-xs-12">
                                          {!! Form::textarea('visit_total', @$program['cashback']->custom_link, ['class' => 'form-control' ,$editable, 'rows' => 8]) !!}
                                      </div>
                                  </div>
                                  <?php } ?>

                                  <?php if(!@$program['visit'] && !@$program['read'] && !@$program['action'] && !@$program['acquisition'] && !@$program['voucher'] && !@$program['cashback']){ ?>
                                    <div class="alert alert-warning">
                                      <h3>You haven't setup the program!</h3>
                                      <p>Please define your program by clicking the button <a href="{{url('master/campaign/setprogram/'.$detail->campaign_link)}}" class='btn btn-secondary btn-sm'>set program</a>.</p>
                                    </div>
                                  <?php } ?>
                                  <div class="col-sm-4">
                                  </div>
                                  <div class="col-xs-12 col-sm-8">
                                    <button type="submit" class="btn btn-primary btn-md btn-rounded" type="submit">Save Programs</button>
                                  </div>

                                {!! Form::close() !!}
                                <br><br>
                                <div class="hr-line-dashed"></div>
                                  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
                                  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

                                  <h3>Target Campaign</h3>

                                  {!! Form::model($detail,['url' => url('master/campaign/update/'.$detail->id_campaign), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate novalidate']) !!}
                                  <input type="hidden" name="id_campaign" value="<?=$detail->id_campaign?>">
                                  <input type="hidden" name="campaign_link" value="<?=$detail->campaign_link?>">
                                  <input type="hidden" name="backlink" value="<?=url('master/campaign/edit/'.$detail->campaign_link)?>">
                                  <input type="hidden" name="update_type" value="target">

                                  <!-- Target fund -->
                                  <div class="form-group {{ ($errors->has('id_categories')?"has-error":"") }}"><label class="col-sm-4 control-label">Target Categories</label>
                                      <div class="col-sm-8 col-xs-12">
                                          {!! Form::select('id_categories[]', $categoriess, $categories, ['class' => 'form-control categories' ,'multiple' => true, $editable]) !!}
                                          {!! $errors->first('id_categories', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                      </div>
                                  </div>

                                  <!-- Target fund -->
                                  <div class="form-group {{ ($errors->has('id_domisili')?"has-error":"") }}"><label class="col-sm-4 control-label">Domisili</label>
                                      <div class="col-sm-8 col-xs-12">
                                          {!! Form::select('id_domisili[]', $domisilis, $domisili, ['class' => 'form-control categories' ,'multiple' => true, $editable]) !!}
                                          {!! $errors->first('id_domisili', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                      </div>
                                  </div>

                                  <!-- Target fund -->
                                  <div class="form-group {{ ($errors->has('estimation_affiliator')?"has-error":"") }}"><label class="col-sm-4 control-label">Affiliator</label>
                                      <div class="col-sm-8 col-xs-12">
                                        <div class="input-group">
                                          {!! Form::text('estimation_affiliator', null, ['class' => 'form-control' ,'readonly' => true,'id' => 'estimation_affiliator']) !!}
                                          <span class="input-group-addon">Accounts</span>
                                        </div>
                                          {!! $errors->first('estimation_affiliator', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                      </div>
                                  </div>
                                  <?php if($detail->campaign_type=="banner"){ ?>

                                  <div class="form-group {{ ($errors->has('tipe_url')?"has-error":"") }}"><label class="col-sm-4 control-label">URL Type</label>
                                      <div class="col-sm-8 col-xs-12">
                                        {!! Form::select('tipe_url', ['slashed' => 'Slashed (https://domain/xx/yyy/enctypted_code))','formatted' => 'Formatted (https://domain/xxx_[encrypted_code]_yyy-zzz )', '' => 'Normal (https://domain/xxx-yyy?encrypted_code)'],$detail->tipe_url, ['class' => 'form-control']) !!}
                                        {!! $errors->first('tipe_url', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                      </div>
                                  </div>

                                  <!-- campaign title -->
                                  <div class="form-group {{ ($errors->has('landing_url')?"has-error":"") }}"><label class="col-sm-4 control-label">Landing/Target URL</label>
                                      <div class="col-sm-8 col-xs-12">
                                          {!! Form::text('landing_url', null, ['class' => 'form-control','placeholder' => 'https://abcdef.com/register',$editable]) !!}
                                          {!! $errors->first('landing_url', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                      </div>
                                  </div>

                                <?php }?>

                                  <div class="form-group">
                                      <div class="col-sm-4">

                                      </div>
                                      <div class="col-sm-4">
                                          <button class="btn btn-primary" type="submit"> Save Targets</button>
                                      </div>
                                  </div>
                                  {!! Form::close() !!}
                              </div>
                            </div>

                            <div class="hr-line-dashed"></div>
                            <br>
                            <div class="form-group">
                                <div class="col-sm-8 col-sm-offset-2">
                                    <a class="btn btn-white" href="{{ url('master/campaign') }}">
                                        <i class="fa fa-angle-left"></i> back
                                    </a>
                                </div>
                            </div>
                            <br>
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
$('.categories').select2();
$('.categories').on('select2:select', function (e) {
  $.each(".select2-selection__choice").function()
});
$(".categories").keydown(function(e){
  if(e.keyCode==13){
    return false;
  }
});
</script>
