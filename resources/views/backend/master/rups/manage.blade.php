<?php
  $main_url = $config['main_url'];
?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<style>
  .box-vote{
    border: 2px solid #e5e6e7;
    padding: 15px;
    border-radius: 5px;
    text-align: center;
    padding-top: 25px;
    padding-bottom: 25px;
    font-size: 18px;
  }
</style>

<div id="wrapper">
    @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
    <div id="page-wrapper" class="gray-bg">
      @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>RUPS Penerbit</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url($main_url) }}">RUPS Penerbit</a>
                    </li>
                    <li class="active">
                        <strong>Kelola RUPS</strong>
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
                            <h5>Kelola RUPS Penerbit</h5>
                        </div>
                        <div class="ibox-content">
                            @if ($errors->any())
                            <div class="alert alert-danger">
                                Ada kesalahan! mohon cek formulir.
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            </div>
                            @endif
                            @include('backend.flash_message')
                            {!! Form::model($data,['url' => url($main_url.'/update-running/'.$data->id_rups), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate novalidate']) !!}
                            <div class="row">
                                <div class="col-lg-6">
                                  <div class="form-group {{ ($errors->has('id_penerbit')?"has-error":"") }}"><label class="col-sm-4 control-label">Terupdate</label>
                                      <div class="col-sm-8 col-xs-12">
                                          <input type="text" name="" class="form-control disabled" readonly value="<?=date("Y-m-d H:i",$data->last_update)?>">
                                      </div>
                                  </div>
                                  <div class="form-group {{ ($errors->has('id_penerbit')?"has-error":"") }}"><label class="col-sm-4 control-label">Penerbit</label>
                                      <div class="col-sm-8 col-xs-12">
                                          {!! Form::select('id_penerbit', $penerbits, $data->id_penerbit, ['class' => 'form-control disabled', 'readonly','disabled']) !!}
                                          {!! $errors->first('id_penerbit', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                      </div>
                                  </div>
                                  <div class="form-group {{ ($errors->has('id_campaign')?"has-error":"") }}"><label class="col-sm-4 control-label">Campaign/Penawaran</label>
                                      <div class="col-sm-8 col-xs-12">
                                          {!! Form::select('id_campaign', $campaigns ,$data->id_campaign, ['class' => 'form-control disabled','readonly','disabled']) !!}
                                          {!! $errors->first('id_campaign', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                      </div>
                                  </div>

                                  <div class="form-group {{ ($errors->has('tanggal_rups')?"has-error":"") }}"><label class="col-sm-4 control-label">Tanggal RUPS</label>
                                      <div class="col-sm-8 col-xs-12">
                                          {!! Form::text('tanggal_rups', $data->tanggal_rups, ['class' => 'form-control','id' => 'tanggal_rups', 'readonly']) !!}
                                          {!! $errors->first('tanggal_rups', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                      </div>
                                  </div>

                                  <div class="form-group {{ ($errors->has('agenda')?"has-error":"") }}"><label class="col-sm-4 control-label">Agenda</label>
                                      <div class="col-sm-8 col-xs-12">
                                          {!! Form::textarea('agenda', null, ['class' => 'form-control', 'rows' => 8, 'readonly']) !!}
                                          {!! $errors->first('agenda', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                      </div>
                                  </div>

                                  <div class="form-group {{ ($errors->has('target_fund')?"has-error":"") }}"><label class="col-sm-4 control-label">Total Fund</label>
                                      <div class="col-sm-8 col-xs-12">
                                          {!! Form::text('target_fund', 'Rp.'.number_format($data->target_fund,2), ['class' => 'form-control', 'rows' => 8, 'readonly']) !!}
                                          {!! $errors->first('target_fund', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                      </div>
                                  </div>

                                  <div class="form-group {{ ($errors->has('periode_deviden')?"has-error":"") }}"><label class="col-sm-4 control-label">Periode Deviden</label>
                                      <div class="col-sm-8 col-xs-12">
                                          {!! Form::text('periode_deviden', $data->periode_deviden.' bulan', ['class' => 'form-control', 'rows' => 8, 'readonly']) !!}
                                          {!! $errors->first('periode_deviden', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                      </div>
                                  </div>

                                  <div class="form-group {{ ($errors->has('periode_deviden')?"has-error":"") }}"><label class="col-sm-4 control-label">Total Saham</label>
                                      <div class="col-sm-8 col-xs-12">
                                          {!! Form::text('periode_deviden', number_format($total_saham).' lembar saham', ['class' => 'form-control', 'rows' => 8, 'readonly']) !!}
                                          {!! $errors->first('periode_deviden', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                      </div>
                                  </div>
                                </div>
                                <div class="col-lg-6">
                                  <div class="form-group {{ ($errors->has('keputusan')?"has-error":"") }}"><label class="col-sm-4 control-label">Keputusan RUPS</label>
                                      <div class="col-sm-8 col-xs-12">
                                          {!! Form::textarea('keputusan', null, ['class' => 'form-control '.(($data->status=='ditetapkan')?'disabled':''), 'rows' => 8, (($data->status=='ditetapkan')?'disabled':'')]) !!}
                                          {!! $errors->first('keputusan', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                      </div>
                                  </div>

                                  <div class="form-group {{ ($errors->has('besar_pembagian')?"has-error":"") }}"><label class="col-sm-4 control-label">Besar Pembagian Saham</label>
                                      <div class="col-sm-8 col-xs-12">
                                        <div class="row">
                                          <div class="col-sm-4 col-xs-12">
                                            <div class="input-group">
                                              {!! Form::text('besar_pembagian', null, ['class' => 'form-control '.(($data->status=='ditetapkan')?'disabled':''), (($data->status=='ditetapkan')?'disabled':'')]) !!}
                                              <span class="input-group-addon">%</span>
                                            </div>
                                          </div>
                                        </div>
                                        {!! $errors->first('besar_pembagian', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                      </div>
                                  </div>

                                  <div class="form-group {{ ($errors->has('video_link')?"has-error":"") }}"><label class="col-sm-4 control-label">Video Link/Live URL</label>
                                      <div class="col-sm-8 col-xs-12">
                                          {!! Form::text('video_link', null, ['class' => 'form-control','id' => 'video_link','placeholder' => 'https://youtube.com', (($data->status=='ditetapkan')?'disabled':'')]) !!}
                                          {!! $errors->first('video_link', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                      </div>
                                  </div>

                                  <div class="form-group {{ ($errors->has('video_link')?"has-error":"") }}"><label class="col-sm-4 control-label">Hasil Vote</label>
                                      <div class="col-sm-4 col-xs-12">
                                        <div class="box-vote">
                                          <i class="fa fa-thumbs-up text-success"></i>
                                          <?php if($total_saham!=0){ ?>
                                            <span><?=number_format($vote_yes*100/$total_saham,2)?>%</span>
                                          <?php }else{ ?>
                                            belum ada data
                                          <?php } ?>
                                        </div>
                                      </div>

                                      <div class="col-sm-4 col-xs-12">
                                        <div class="box-vote">
                                          <i class="fa fa-thumbs-down text-danger"></i>
                                          <?php if($total_saham!=0){ ?>
                                            <span><?=number_format($vote_no*100/$total_saham,2)?>%</span>
                                          <?php }else{ ?>
                                            belum ada data
                                          <?php } ?>
                                        </div>
                                      </div>
                                  </div>

                                  <div class="form-group {{ ($errors->has('video_link')?"has-error":"") }}"><label class="col-sm-4 control-label">Status</label>
                                      <div class="col-sm-4 col-xs-12">
                                        {!! Form::text('status', null, ['class' => 'form-control','disabled']) !!}
                                      </div>
                                  </div>

                                </div>
                              </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-2">
                                    <a class="btn btn-white" href="{{ url($main_url) }}">
                                        <i class="fa fa-angle-left"></i> kembali
                                    </a>
                                </div>
                                <div class="col-sm-6 text-right">
                                    <a href="" class="btn btn-white" type="reset"><i class="fa fa-sync-alt"></i> Refresh</a>
                                    <?php if($data->status!='ditetapkan'){ ?>
                                      <button class="btn btn-primary btn-rounded" type="submit">Simpan</button>
                                    <?php } ?>
                                    <?php if($data->besar_pembagian=="" || $data->status=='ditetapkan'){ ?>
                                      <a data-url="" data-id="" class="btn btn-danger disabled"><i class="fa fa-lock"></i> Tetapkan RUPS & Kirim Notifikasi</a>
                                    <?php }else{ ?>

                                      <a data-url="<?=url($main_url."/lockrups/")?>" data-id="<?=$data->id_rups?>" class="btn btn-danger confirm"><i class="fa fa-lock"></i> Tetapkan RUPS & Kirim Notifikasi</a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>

                    <?php
                      $persetujuan = array(
                        "ya"    => "<span class='text-success'><i class='fa fa-thumbs-up'></i> Setuju</span>",
                        "tidak"    => "<span class='text-danger'><i class='fa fa-thumbs-down'></i> Tidak Setuju</span>",
                      );
                    ?>

                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Peserta RUPS</h5>
                        </div>
                        <div class="ibox-content">
                          <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover dataTables-example" id="datatable">
                              <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nama Pemodal</th>
                                    <th>Persetujuan</th>
                                    <th>Besar Saham</th>
                                    <th>Update Terakhir</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php
                                  $counter = 0;

                                  if($attendances){
                                    foreach ($attendances as $key => $value) {
                                      $counter++;
                                      ?>
                                      <tr>
                                        <td><?=$counter?></td>
                                        <td><?=$value->first_name?> <?=$value->last_name?></td>
                                        <td><?=$persetujuan[$value->persetujuan]?></td>
                                        <td class="text-right"><?=number_format(($value->total_saham*100)/$total_saham,2)?>%</td>
                                        <td><?=date("Y-m-d H:i:s",$value->last_update)?></td>
                                      </tr>
                                      <?php
                                    }
                                  }
                                ?>
                              </tbody>
                              </tfoot>
                            </table>
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
    $('#id_penerbit').select2();
  });

  $(document).ready(function() {
    $('#tanggal_rups').datepicker("setDate", "<?=$data->tanggal_rups?>");
    $('#tanggal_rups').datepicker("option", "dateFormat", "yy-mm-dd");
    $("#id_penerbit").val(<?=$data->id_penerbit?>);
    changePenerbit();
  });

  $("#id_penerbit").change(function(){
    changePenerbit();
  });

  function changePenerbit(){
    $("#id_campaign").select2({
      ajax: {
        url: '{{ url("get-list-campaign-by-penerbit") }}',
        dataType: 'json',
        data: function (params) {
          var id_penerbit = $("#id_penerbit").val();
          var query = {
            search: params.term,
            id_penerbit: id_penerbit,
            type: 'public'
          }
          // Query parameters will be ?search=[term]&type=public
          return query;
        }
      }
    });
  }
</script>
