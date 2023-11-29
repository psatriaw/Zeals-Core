<style>
  .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td, .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th{
    border-top:0px !important;
    font-size:14px;
  }

  .wrapper-content strong{
    font-weight: 600;
    color: #ffbc09;
  }
</style>
<?php
  $campaigntypes = array(
    "o2o"         => "O2O",
    "banner"      => "Banner - AMP",
    "shopee"      => "Shopee - AMP",
    "event"         => "Event - O2O",
  );
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
                        <strong>Manage Campaign</strong>
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
                            <h5>Campaign Control</h5>
                        </div>
                        <div class="ibox-content">
                            @if ($errors->any())
                            <div class="alert alert-danger">
                                Ada kesalahan! mohon cek formulir.
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            </div>
                            @endif
                            @include('backend.flash_message')
                            {!! Form::model($detail,['url' => url('master/campaign/storetarget'), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate novalidate']) !!}
                            <input type="hidden" name="id_campaign" value="<?=$detail->id_campaign?>">
                            <input type="hidden" name="campaign_link" value="<?=$detail->campaign_link?>">
                            <!-- campaign title -->
                            <div class="row">
                              <div class="col-lg-6 col-xs-12">
                                <h3>Detail Campaign</h3>

                                <div class="form-group {{ ($errors->has('campaign_title')?"has-error":"") }}"><label class="col-sm-4 control-label">Type</label>
                                    <div class="col-sm-8 col-xs-12">
                                        {!! Form::text('ct', $campaigntypes[$detail->campaign_type], ['class' => 'form-control','readonly']) !!}
                                        {!! $errors->first('ct', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                    </div>
                                </div>

                                <div class="form-group {{ ($errors->has('campaign_title')?"has-error":"") }}"><label class="col-sm-4 control-label">Campaign Title</label>
                                    <div class="col-sm-8 col-xs-12">
                                        {!! Form::text('campaign_title', $detail->campaign_title.' ['.$detail->campaign_link.']', ['class' => 'form-control','readonly']) !!}
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
                                        <input type="file" name="photos" style="display:none;">
                                        {!! $errors->first('photos', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group {{ ($errors->has('budget')?"has-error":"") }}"><label class="col-sm-4 control-label">Budget</label>
                                    <div class="col-sm-8 col-xs-12">
                                      <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        {!! Form::text('budget', number_format($detail->budget,0,',','.'), ['class' => 'form-control text-right','readonly']) !!}
                                      </div>
                                        {!! $errors->first('budget', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                    </div>
                                </div>
                                <!-- campaign description -->
                                <div class="form-group {{ ($errors->has('max_commission')?"has-error":"") }}"><label class="col-sm-4 control-label">Max Commission <sub>(per influencer)</sub></label>
                                    <div class="col-sm-8 col-xs-12">
                                      <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        {!! Form::text('max_commission', number_format($detail->max_commission,0,',','.'), ['class' => 'form-control text-right','readonly']) !!}
                                      </div>
                                        {!! $errors->first('max_commission', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                    </div>
                                </div>
                                <!-- campaign description -->
                                <div class="form-group {{ ($errors->has('campaign_description')?"has-error":"") }}"><label class="col-sm-4 control-label">Campaign Description</label>
                                    <div class="col-sm-8 col-xs-12">
                                        {!! Form::textarea('campaign_description', null, ['class' => 'form-control','rows' => 10,'readonly']) !!}
                                        {!! $errors->first('campaign_description', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                    </div>
                                </div>
                                <!-- campaign description -->
                                <div class="form-group {{ ($errors->has('campaign_instruction')?"has-error":"") }}"><label class="col-sm-4 control-label">Campaign Instruction</label>
                                    <div class="col-sm-8 col-xs-12">
                                        {!! Form::textarea('campaign_instruction', null, ['class' => 'form-control','rows' => 10,'readonly']) !!}
                                        {!! $errors->first('campaign_instruction', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                    </div>
                                </div>
                                <!-- Start date -->
                                <!-- campaign description -->
                                <div class="form-group {{ ($errors->has('campaign_do_n_dont')?"has-error":"") }}"><label class="col-sm-4 control-label">Do & Don't</label>
                                    <div class="col-sm-8 col-xs-12">
                                        {!! Form::textarea('campaign_do_n_dont', null, ['class' => 'form-control','rows' => 10,'readonly']) !!}
                                        {!! $errors->first('campaign_do_n_dont', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                    </div>
                                </div>
                                <!-- Start date -->
                                <div class="form-group {{ ($errors->has('start_date')?"has-error":"") }}"><label class="col-sm-4 control-label">Start Date</label>
                                    <div class="col-sm-3 col-xs-12">
                                        {!! Form::date('start_date', null, ['class' => 'form-control','readonly']) !!}
                                        {!! $errors->first('start_date', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                    </div>
                                </div>
                                <!-- end date -->
                                <div class="form-group {{ ($errors->has('end_date')?"has-error":"") }}"><label class="col-sm-4 control-label">End Date</label>
                                    <div class="col-sm-3 col-xs-12">
                                        {!! Form::date('end_date', null, ['class' => 'form-control','readonly']) !!}
                                        {!! $errors->first('end_date', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                    </div>
                                </div>

                              </div>
                              <div class="col-lg-6 col-xs-12">
                                <h3>Detail Program</h3>
                                <?php if(@$program['visit']){ ?>
                                <div class="form-group {{ ($errors->has('visit_total')?"has-error":"") }}"><label class="col-sm-4 control-label">Unique Visitor</label>
                                    <div class="col-sm-6 col-xs-12">
                                      <div class="input-group">
                                        {!! Form::text('visit_total', number_format(@$program['visit']->total_item,0,',','.'), ['class' => 'form-control' ,'readonly' => true]) !!}
                                        <span class="input-group-addon">Visitor</span>
                                      </div>
                                    </div>
                                </div>

                                <div class="form-group {{ ($errors->has('visit_total')?"has-error":"") }}"><label class="col-sm-4 control-label">Commission</label>
                                    <div class="col-sm-6 col-xs-12">
                                      <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        {!! Form::text('visit_total', number_format(@$program['visit']->commission,0,',','.'), ['class' => 'form-control' ,'readonly' => true]) !!}
                                      </div>
                                    </div>
                                </div>

                                <div class="form-group {{ ($errors->has('visit_total')?"has-error":"") }}"><label class="col-sm-4 control-label">Fee</label>
                                    <div class="col-sm-6 col-xs-12">
                                      <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        {!! Form::text('visit_total', number_format(@$program['visit']->fee,0,',','.'), ['class' => 'form-control' ,'readonly' => true]) !!}
                                      </div>
                                    </div>
                                </div>
                                <?php } ?>

                                <?php if(@$program['read']){ ?>
                                <div class="hr-line-dashed"></div>
                                <div class="form-group {{ ($errors->has('visit_total')?"has-error":"") }}"><label class="col-sm-4 control-label">Unique Reader</label>
                                    <div class="col-sm-6 col-xs-12">
                                      <div class="input-group">
                                        {!! Form::text('visit_total', number_format(@$program['read']->total_item,0,',','.'), ['class' => 'form-control' ,'readonly' => true]) !!}
                                        <span class="input-group-addon">Visitor</span>
                                      </div>
                                    </div>
                                </div>

                                <div class="form-group {{ ($errors->has('visit_total')?"has-error":"") }}"><label class="col-sm-4 control-label">Commission</label>
                                    <div class="col-sm-6 col-xs-12">
                                      <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        {!! Form::text('visit_total', number_format(@$program['read']->commission,0,',','.'), ['class' => 'form-control' ,'readonly' => true]) !!}
                                      </div>
                                    </div>
                                </div>

                                <div class="form-group {{ ($errors->has('visit_total')?"has-error":"") }}"><label class="col-sm-4 control-label">Fee</label>
                                    <div class="col-sm-6 col-xs-12">
                                      <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        {!! Form::text('visit_total', number_format(@$program['read']->fee,0,',','.'), ['class' => 'form-control' ,'readonly' => true]) !!}
                                      </div>
                                    </div>
                                </div>
                                <?php } ?>

                                <?php if(@$program['action']){ ?>
                                <div class="hr-line-dashed"></div>
                                <div class="form-group {{ ($errors->has('visit_total')?"has-error":"") }}"><label class="col-sm-4 control-label">Unique Action</label>
                                    <div class="col-sm-6 col-xs-12">
                                      <div class="input-group">
                                        {!! Form::text('visit_total', number_format(@$program['action']->total_item,0,',','.'), ['class' => 'form-control' ,'readonly' => true]) !!}
                                        <span class="input-group-addon">Visitor</span>
                                      </div>
                                    </div>
                                </div>

                                <div class="form-group {{ ($errors->has('visit_total')?"has-error":"") }}"><label class="col-sm-4 control-label">Commission</label>
                                    <div class="col-sm-6 col-xs-12">
                                      <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        {!! Form::text('visit_total', number_format(@$program['action']->commission,0,',','.'), ['class' => 'form-control' ,'readonly' => true]) !!}
                                      </div>
                                    </div>
                                </div>

                                <div class="form-group {{ ($errors->has('visit_total')?"has-error":"") }}"><label class="col-sm-4 control-label">Fee</label>
                                    <div class="col-sm-6 col-xs-12">
                                      <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        {!! Form::text('visit_total', number_format(@$program['action']->fee,0,',','.'), ['class' => 'form-control' ,'readonly' => true]) !!}
                                      </div>
                                    </div>
                                </div>
                                <?php } ?>

                                <?php if(@$program['acquisition']){ ?>
                                <?php
                                  $callback_mode = true;
                                ?>
                                <div class="hr-line-dashed"></div>
                                <div class="form-group {{ ($errors->has('visit_total')?"has-error":"") }}"><label class="col-sm-4 control-label">Unique Acquisition</label>
                                    <div class="col-sm-6 col-xs-12">
                                      <div class="input-group">
                                        {!! Form::text('visit_total', number_format(@$program['acquisition']->total_item,0,',','.'), ['class' => 'form-control' ,'readonly' => true]) !!}
                                        <span class="input-group-addon">Visitor</span>
                                      </div>
                                    </div>
                                </div>

                                <div class="form-group {{ ($errors->has('visit_total')?"has-error":"") }}"><label class="col-sm-4 control-label">Commission</label>
                                    <div class="col-sm-6 col-xs-12">
                                      <div class="input-group">
                                        <span class="input-group-addon">
                                          <?php 
                                              if($program['acquisition']->type=='percent'){
                                                echo "%";
                                              }else{
                                                echo "Rp.";
                                              }
                                            ?>
                                        </span>
                                        {!! Form::text('visit_total', number_format(@$program['acquisition']->commission,2,',','.'), ['class' => 'form-control' ,'readonly' => true]) !!}
                                      </div>
                                    </div>
                                </div>

                                <div class="form-group {{ ($errors->has('visit_total')?"has-error":"") }}"><label class="col-sm-4 control-label">Fee</label>
                                    <div class="col-sm-6 col-xs-12">
                                      <div class="input-group">
                                        <span class="input-group-addon">
                                          <?php 
                                              if($program['acquisition']->type=='percent'){
                                                echo "%";
                                              }else{
                                                echo "Rp.";
                                              }
                                            ?>
                                        </span>
                                        {!! Form::text('visit_total', number_format(@$program['acquisition']->fee,2,',','.'), ['class' => 'form-control' ,'readonly' => true]) !!}
                                      </div>
                                    </div>
                                </div>
                                <?php } ?>

                                <?php if(@$program['voucher']){ ?>
                                <?php
                                  $callback_mode = true;
                                ?>
                                <div class="hr-line-dashed"></div>
                                <div class="form-group {{ ($errors->has('visit_total')?"has-error":"") }}"><label class="col-sm-4 control-label">Voucher</label>
                                    <div class="col-sm-6 col-xs-12">
                                      <div class="input-group">
                                        {!! Form::text('visit_total', number_format(@$program['voucher']->total_item,0,',','.'), ['class' => 'form-control' ,'readonly' => true]) !!}
                                        <span class="input-group-addon">vouchers</span>
                                      </div>
                                    </div>
                                </div>

                                <div class="form-group {{ ($errors->has('visit_total')?"has-error":"") }}"><label class="col-sm-4 control-label">Commission per redemption</label>
                                    <div class="col-sm-6 col-xs-12">
                                      <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        {!! Form::text('visit_total', number_format(@$program['voucher']->commission,0,',','.'), ['class' => 'form-control' ,'readonly' => true]) !!}
                                      </div>
                                    </div>
                                </div>

                                <div class="form-group {{ ($errors->has('visit_total')?"has-error":"") }}"><label class="col-sm-4 control-label">Fee per redemption</label>
                                    <div class="col-sm-6 col-xs-12">
                                      <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        {!! Form::text('visit_total', number_format(@$program['voucher']->fee,0,',','.'), ['class' => 'form-control' ,'readonly' => true]) !!}
                                      </div>
                                    </div>
                                </div>
                                <?php } ?>

                                <?php if(@$program['cashback']){ ?>
                                <?php
                                  $callback_mode = true;
                                ?>
                                <div class="hr-line-dashed"></div>
                                <div class="form-group {{ ($errors->has('visit_total')?"has-error":"") }}"><label class="col-sm-4 control-label">Sales/Transaction</label>
                                    <div class="col-sm-6 col-xs-12">
                                      <div class="input-group">
                                        {!! Form::text('visit_total', number_format(@$program['cashback']->total_item,0,',','.'), ['class' => 'form-control' ,'readonly' => true]) !!}
                                        <span class="input-group-addon">transactions</span>
                                      </div>
                                    </div>
                                </div>

                                <div class="form-group {{ ($errors->has('visit_total')?"has-error":"") }}"><label class="col-sm-4 control-label">Commission per transaction</label>
                                    <div class="col-sm-6 col-xs-12">
                                      <div class="input-group">
                                        {!! Form::text('visit_total', number_format(@$program['cashback']->commission,0,',','.'), ['class' => 'form-control' ,'readonly' => true]) !!}
                                        <span class="input-group-addon">%</span>
                                      </div>
                                    </div>
                                </div>

                                <div class="form-group {{ ($errors->has('visit_total')?"has-error":"") }}"><label class="col-sm-4 control-label">Fee per trnsaction</label>
                                    <div class="col-sm-6 col-xs-12">
                                      <div class="input-group">
                                        {!! Form::text('visit_total', number_format(@$program['cashback']->fee,0,',','.'), ['class' => 'form-control' ,'readonly' => true]) !!}
                                        <span class="input-group-addon">%</span>
                                      </div>
                                    </div>
                                </div>

                                <div class="form-group {{ ($errors->has('custom_link')?"has-error":"") }}"><label class="col-sm-4 control-label">Shopee URL</label>
                                    <div class="col-sm-6 col-xs-12">
                                        {!! Form::textarea('visit_total', @$program['cashback']->custom_link, ['class' => 'form-control' ,'readonly' => true, 'rows' => 8]) !!}
                                    </div>
                                </div>
                                <?php } ?>

                                <?php if(!@$program['visit'] && !@$program['read'] && !@$program['action'] && !@$program['acquisition'] && !@$program['voucher'] && !@$program['cashback']){ ?>
                                  <div class="alert alert-warning">
                                    <h3>You haven't setup the program!</h3>
                                    <p>Please define your program by clicking the button <a href="{{url('master/campaign/setprogram/'.$detail->campaign_link)}}" class='btn btn-secondary btn-sm'>set program</a>.</p>
                                  </div>
                                <?php } ?>

                                <div class="hr-line-dashed"></div>
                                <h3>Target</h3>
                                <!-- Target fund -->
                                <div class="form-group {{ ($errors->has('id_categories')?"has-error":"") }}"><label class="col-sm-4 control-label">Target Categories</label>
                                    <div class="col-sm-8 col-xs-12">
                                      <?php
                                        if($categories){
                                          foreach ($categories as $key => $value) {
                                            ?>
                                            <label class='label label-default' style='margin-bottom:3px;display: inline-block;padding: 8px;font-size:13px;color:#777;'><?=$value->nama_sektor_industri?></label>
                                            <?php
                                          }
                                        }
                                      ?>
                                    </div>
                                </div>

                                <!-- Target fund -->
                                <div class="form-group {{ ($errors->has('id_domisili')?"has-error":"") }}"><label class="col-sm-4 control-label">Domisili</label>
                                    <div class="col-sm-8 col-xs-12">
                                        <?php
                                          if($domisili){
                                            foreach ($domisili as $key => $value) {
                                              ?>
                                              <label class='label label-default' style='margin-bottom:3px;display: inline-block;padding: 8px;font-size:13px;color:#777;'><?=$value->namakab?></label>
                                              <?php
                                            }
                                          }
                                        ?>
                                    </div>
                                </div>

                                <!-- Target fund -->
                                <div class="form-group {{ ($errors->has('estimation_affiliator')?"has-error":"") }}"><label class="col-sm-4 control-label">Affiliator</label>
                                    <div class="col-sm-6 col-xs-12">
                                      <div class="input-group">
                                        {!! Form::text('estimation_affiliator', null, ['class' => 'form-control' ,'readonly' => true,'id' => 'estimation_affiliator']) !!}
                                        <span class="input-group-addon">Accounts</span>
                                      </div>
                                        {!! $errors->first('estimation_affiliator', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                    </div>
                                </div>

                                <?php if($detail->campaign_type=="banner"){ ?>
                                <!-- Target fund -->
                                <div class="form-group {{ ($errors->has('landing_url')?"has-error":"") }}"><label class="col-sm-4 control-label">Landing/Target URL</label>
                                    <div class="col-sm-6 col-xs-12">
                                        {!! Form::text('landing_url', null, ['class' => 'form-control' ,'readonly' => true]) !!}
                                        {!! $errors->first('landing_url', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                    </div>
                                </div>
                                <?php } ?>

                                <?php if($detail->campaign_type=="o2o" || $detail->campaign_type=="event"){ ?>
                                <div class="hr-line-dashed"></div>
                                <h3>Outlets</h3>
                                <!-- Target fund -->

                                <div class="row">
                                <?php
                                  foreach ($outlets as $key => $value) {
                                    ?>
                                    <div class="col-sm-6 col-sm-6">
                                      <div class="item-outlet">
                                        <div class="row">
                                          <div class="col-sm-4 text-center">
                                            <div class="img-thumbnail">
                                            {{ QrCode::size(100)->generate($value->outlet_code) }}
                                            </div>
                                            <h4 class="text-center"><?=$value->outlet_code?></h4>
                                          </div>
                                          <div class="col-sm-8 ellipsis">
                                            <h4><?=$value->outlet_name?></h4>
                                            <i class='fa fa-map-marker' style="width:20px;"></i> <?=$value->outlet_address?> 
                                              <?php if($value->longitude!=""){ ?>
                                                (<?=$value->latitude?>,<?=$value->longitude?>)
                                              <?php } ?>
                                            <br>
                                            <i class='fa fa-phone' style="width:20px;"></i> <?=$value->outlet_phone?><br><br>
                                            <?php if($value->max_redemption){ ?>
                                              <?=number_format($value->max_redemption,0)?> redemptions<br>
                                            <?php }else{ ?>
                                              No maximum redemption for this outlet<br>
                                            <?php } ?>

                                            <?php if($value->max_redemption){ ?>
                                              <?=number_format($value->max_redemption_per_day,0)?> redemptions per day<br>
                                            <?php }else{ ?>
                                              No maximum redemption per day for this outlet
                                            <?php } ?>
                                          </div>
                                          <div class="col-sm-12">
                                            <a href="{{ url('master/campaign/editoutlet/'.Request::segment(4).'/'.$value->id_outlet) }}" class="btn btn-primary pull-right btn-sm"><i class='fa fa-pen'></i></a>
                                            <a data-url="{{ url('master/campaign/removeoutlet/'.Request::segment(4).'/'.$value->id_outlet) }}" class="btn btn-danger pull-right btn-sm confirm"><i class='fa fa-trash'></i></a>
                                            <a href="{{ url('master/campaign/report/outlet/'.Request::segment(4).'/'.$value->id_outlet) }}" class="btn btn-info pull-right btn-sm btn-rounded"><i class='fa fa-chart-pie'></i></a>
                                            <?php if($detail->campaign_type=="event"){?>
                                            <a href="{{ url('master/campaign/scan/outlet/'.Request::segment(4).'/'.$value->id_outlet) }}" class="btn btn-info pull-right btn-sm btn-rounded"><i class='fa fa-qrcode'></i></a>
                                            <?php } ?>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <?php
                                  }
                                ?>
                                  <div class="col-sm-12 text-center">
                                    <a href="{{ url('master/campaign/setoutlet/'.Request::segment(4)) }}" class="btn btn-primary">Add more outlet</a>
                                  </div>
                                </div>
                                <?php } ?>

                              </div>
                            </div>

                            <?php if($detail->campaign_type=="banner"){ ?>
                            <div class="hr-line-dashed"></div>
                            <div class="row">
                              <div class="col-lg-12 col-xs-12">
                                <h3>Tracker Configuration</h3>
                                <div class="form-group {{ ($errors->has('id_domisili')?"has-error":"") }}"><label class="col-sm-2 control-label">Tracker Code</label>
                                    <div class="col-sm-10 col-xs-12">
                                        <div class="pre-code">
                                            <strong>&lt;script src="<?=url('')?>/platform/js/zealsamp.js?aff_id=<?=$penerbit->kode_penerbit?>"&gt; &lt;/script&gt;</strong>
                                        </div>
                                        <h4>instructions:</h4>
                                        <p>Please put the code above into the landing/target page (<?=$detail->landing_url?>). Make sure the javascript tag works by clicking this button below</p>
                                        <?php if(@$tracker_test){ ?>
                                          <a href="" class='btn btn-sm btn-success btn-rounded' type="button">tracker active (last test on <?=$tracker_test?>)</a>
                                        <?php }else{ ?>
                                          <button onclick="location.reload();" class='btn btn-sm btn-secondary' type="button">no activity, press to check</button>
                                        <?php }?>
                                    </div>
                                </div>

                                <div class="form-group {{ ($errors->has('id_domisili')?"has-error":"") }}"><label class="col-sm-2 control-label" id="testurl">Test URL</label>
                                    <div class="col-sm-10 col-xs-12">
                                      <a href="<?=url('/link/'.$test_link)?>" target="_blank" style="font-size:16px;margin-top: 5px;display: inline-block;font-weight: 600;color: #b7b60c;"><?=url('/link/'.$test_link)?></a>
                                    </div>
                                </div>
                                <?php if(@$callback_mode){ ?>
                                  <div class="alert alert-info text-small">You are using "acquisition program" for this campaign, you may need one of the endpoints below to make a callback to our system</div>
                                  <div class="form-group {{ ($errors->has('id_domisili')?"has-error":"") }}"><label class="col-sm-2 control-label">Callback Endpoint <sub>(PHP)</sub></label>
                                      <div class="col-sm-10 col-xs-12">
                                          <div class="pre-code">
                                              <strong><?=url('apiv1/AMPcallback')?></strong>
                                              <br>
                                              <br>
                                              parameters:<br><br>
                                              {<br>
                                              	   &nbsp;&nbsp;&nbsp;<strong>encrypted_code</strong>: xxx,<br>
                                                   &nbsp;&nbsp;&nbsp;<strong>aff_id</strong>: <strong><?=$penerbit->kode_penerbit?></strong>,<br>
                                                   &nbsp;&nbsp;&nbsp;<strong>unique_random_code</strong>: yyy,<br>
                                                   &nbsp;&nbsp;&nbsp;<strong>transaction_value</strong>: zzz<br>
                                              }<br>
                                              <br>
                                              <br>
                                              <table class="table">
                                                <tr>
                                                  <td><strong>encrypted_code</strong></td><td style='color:#ccc;'> encrypted automatically generated by zeals platform that contains unique information of audience and affiliator. you can generate the encrypted code from the test URL above</td>
                                                </tr>
                                                <tr>
                                                  <td><strong>aff_id</strong></td><td style='color:#ccc;'>Affiliate ID represent the client/brand (please do not change this value, it is already registered in our system)</td>
                                                </tr>
                                                <tr>
                                                  <td><strong>unique_random_code</strong></td><td style='color:#ccc;'>Unique number/string that represent a transaction related the campaign goals. For example: transaction ID on client's side</td>
                                                </tr>
                                                <tr>
                                                  <td><strong>transaction_value</strong></td><td style='color:#ccc;'>The amount of transaction for % commission (if the campaign goal/sales indicates a flexible commission)</td>
                                                </tr>
                                            </table>

                                          </div>
                                          <h4>instructions:</h4>
                                          <p class="intro">
                                              Please go to <a href="<?=url('/link/'.$test_link)?>" target="_blank" style="font-weight: 600;color: #b7b60c;"><?=url('/link/'.$test_link)?></a> and let the system redirects you to the targetted landing page with extra query string writen as ?<strong>encrypted_code</strong>=xxxx... see the picture below:<br>
                                              <img src="<?=url('templates/admin/img/tutorial_callback_1.jpg')?>" style="height:40px;"><br>
                                              Take the encrypted_code as variable to make a call to zeals system.<br>
                                              Just bring the encryted_code as a variable everywhere as target of acquisition.<br>
                                              Then make a test of a simple journey of your system until the final/target page accessed then put the callback code above to make a callback, then make sure this button below colored in green.
                                          </p>
                                          <button class='btn btn-sm btn-danger' type="button">no acquisition test/transaction in last 5 minutes</button>
                                      </div>
                                  </div>

                                  <div class="form-group {{ ($errors->has('id_domisili')?"has-error":"") }}"><label class="col-sm-2 control-label">Callback Endpoint <sub>(javascript)</sub></label>
                                      <div class="col-sm-10 col-xs-12">
                                        <div class="pre-code">
                                          <strong>
                                            &lt;script src="<?=url('')?>/platform/js/zealsamp.js?aff_id=<?=$penerbit->kode_penerbit?>"&gt; &lt;/script&gt;<br>
                                            &lt;script&gt; <br>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;makeACall();<br>
                                            &lt;/script&gt;
                                          </strong>
                                        </div>
                                        <h4>instructions:</h4>
                                        <p class="intro">
                                            Please put the code above into the landing/target page assumed as the final/target page after acquisition process done. Make sure the code works with simple flow test<br>
                                            through the <a href="<?=url('/link/'.$test_link)?>" target="_blank" style="font-weight: 600;color: #b7b60c;"><?=url('/link/'.$test_link)?></a>  until the final/target page accessed then make sure the javascript tag works by clicking this button below.
                                        </p>
                                        <?php if(@$callback_test){ ?>
                                          <a href="" class='btn btn-sm btn-success btn-rounded' type="button">callback success (last callback on <?=$last_callback?>)</a>
                                        <?php }else{ ?>
                                          <a href="" class='btn btn-sm btn-secondary' type="button">check JS callback</a>
                                        <?php }?>
                                      </div>
                                  </div>
                                <?php } ?>
                              </div>
                            </div>
                            <?php }else{ ?>
                              <div class="hr-line-dashed"></div>
                              <div class="row">
                                <div class="col-lg-12 col-xs-12">
                                  <h3>Test Voucher</h3>
                                  <div class="form-group {{ ($errors->has('id_domisili')?"has-error":"") }}"><label class="col-sm-2 control-label" id="testurl">Test URL</label>
                                      <div class="col-sm-10 col-xs-12">
                                        <a href="<?=url('/link/'.$test_link)?>" target="_blank" style="font-size:16px;margin-top: 5px;display: inline-block;font-weight: 600;color: #b7b60c;"><?=url('/link/'.$test_link)?></a>
                                      </div>
                                  </div>
                                </div>
                              </div>
                            <?php } ?>

                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <div class="col-sm-6 col-sm-offset-2">
                                    <a class="btn btn-white" href="{{ url('master/campaign') }}">
                                        <i class="fa fa-angle-left"></i> back
                                    </a>
                                </div>
                                <div class="col-sm-4 text-right">
                                  <a href="{{ url('master/campaign/report/'.$detail->campaign_link.'?backlink=master/campaign/resume/'.$detail->campaign_link) }}" class="btn btn-info btn-sm btn-rounded" type="submit"><i class="fa fa-chart-pie"></i> Statistic/Report</a>

                                  <?php if($detail->running_status=="closed" || $detail->running_status=="close"){ ?>
                                    <a data-url="{{ url('master/campaign/setrun/?backlink=master/campaign/resume/'.$detail->campaign_link) }}" data-id="<?=$detail->id_campaign?>" class="btn btn-primary btn-sm confirm" type="submit"><i class="fa fa-play"></i> Run Campaign</a>
                                  <?php }else{ ?>
                                    <a data-url="{{ url('master/campaign/setclose/?backlink=master/campaign/resume/'.$detail->campaign_link) }}" data-id="<?=$detail->id_campaign?>" class="btn btn-danger btn-sm confirm" type="submit"><i class="fa fa-stop"></i> Stop Campaign</a>
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

</script>
