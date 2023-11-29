<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Akun Layanan</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('admin/master') }}">Master</a>
                    </li>
                    <li>
                        <a href="{{ url('admin/user') }}">Akun Layanan</a>
                    </li>
                    <li class="active">
                        <strong>Detail Akun</strong>
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
                        <h5>Detail Akun</h5>
                    </div>
                    <div class="ibox-content">
                      @if ($errors->any())
                          <div class="alert alert-danger">
                              Ada kesalahan! mohon cek formulir.
                              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                          </div>
                      @endif
                      <?php
                        $data['account']['account_start_time']  = date("Y-m-d H:i:s",$data['account']['account_start_time']);
                        $data['account']['account_end_time']    = date("Y-m-d H:i:s",$data['account']['account_end_time']);
                        $data['transaction']['total_amount']    = number_format($data['transaction']['total_amount'],0,",",".");
                        $data['transaction']['discount']        = number_format($data['transaction']['discount'],0,",",".");
                      ?>
                      @include('backend.flash_message')
                      {!! Form::model($data['service'],['url' => url('admin/user/update/'), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}

                        <div class="form-group {{ ($errors->has('service_name')?"has-error":"") }}"><label class="col-sm-2 control-label">Jenis Layanan</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::text('service_name', null, ['class' => 'form-control disabled', 'readonly','disabled']) !!}
                                {!! $errors->first('service_name', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('code')?"has-error":"") }}"><label class="col-sm-2 control-label">Kode Layanan</label>
                            <div class="col-sm-2 col-xs-12">
                                {!! Form::text('code', null, ['class' => 'form-control disabled', 'readonly','disabled']) !!}
                                {!! $errors->first('code', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                      {!! Form::close() !!}

                      {!! Form::model($data['account'],['url' => url('admin/user/update/'), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('account_start_time')?"has-error":"") }}"><label class="col-sm-2 control-label">Mulai Aktif</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::text('account_start_time', null, ['class' => 'form-control disabled', 'readonly','disabled']) !!}
                                {!! $errors->first('account_start_time', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('account_end_time')?"has-error":"") }}"><label class="col-sm-2 control-label">Waktu Berakhir</label>
                            <div class="col-sm-4 col-xs-12">
                                {!! Form::email('account_end_time', null, ['class' => 'form-control disabled', 'readonly','disabled']) !!}
                                {!! $errors->first('account_end_time', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('status')?"has-error":"") }}"><label class="col-sm-2 control-label">Status</label>
                            <div class="col-sm-2 col-xs-12">
                                {!! Form::text('status', null, ['class' => 'form-control disabled', 'readonly','disabled']) !!}
                                {!! $errors->first('status', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                      {!! Form::close() !!}
                    </div>

                    <div class="ibox-title m-t">
                        <h5>Detail Tagihan</h5>
                    </div>
                    <div class="ibox-content">

                        {!! Form::model($data['transaction'],['url' => url('admin/user/update/'), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}

                          <div class="form-group {{ ($errors->has('transaction_code')?"has-error":"") }}"><label class="col-sm-2 control-label">Nomor Tagihan</label>
                              <div class="col-sm-4 col-xs-12">
                                  {!! Form::text('transaction_code', null, ['class' => 'form-control disabled', 'readonly','disabled']) !!}

                              </div>
                          </div>
                          <div class="hr-line-dashed"></div>
                          <div class="form-group {{ ($errors->has('transaction_status')?"has-error":"") }}"><label class="col-sm-2 control-label">Status Pembayaran</label>
                              <div class="col-sm-4 col-xs-12">
                                  {!! Form::text('transaction_status', null, ['class' => 'form-control disabled', 'readonly','disabled']) !!}
                                  {!! $errors->first('transaction_status', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                                  <span class="help-block"><?=(@$data['transaction']['trx_code']!="")?$data['transaction']['trx_code']:"Tidak ada kode referensi pembayaran"?></span>
                              </div>
                          </div>
                          <div class="hr-line-dashed"></div>
                          <div class="form-group {{ ($errors->has('total_amount')?"has-error":"") }}"><label class="col-sm-2 control-label">Total Tagihan</label>
                              <div class="col-sm-2 col-xs-12">
                                <div class="input-group">
                                  <span class="input-group-addon bg-primary">Rp. </span>
                                  {!! Form::text('total_amount', null, ['class' => 'form-control disabled text-right', 'readonly','disabled']) !!}
                                </div>
                              </div>
                          </div>
                          <div class="hr-line-dashed"></div>
                          <div class="form-group {{ ($errors->has('discount')?"has-error":"") }}"><label class="col-sm-2 control-label">Diskon/Potongan</label>
                              <div class="col-sm-2 col-xs-12">
                                <div class="input-group">
                                  <span class="input-group-addon bg-primary">Rp. </span>
                                  {!! Form::text('discount', null, ['class' => 'form-control disabled text-right', 'readonly','disabled']) !!}
                                </div>
                                  {!! $errors->first('discount', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                              </div>
                              <div class="col-sm-2 col-xs-12">
                                  {!! Form::text('id_discount', null, ['class' => 'form-control disabled', 'readonly','disabled']) !!}
                                  {!! $errors->first('id_discount', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                              </div>
                          </div>
                          {!! Form::close() !!}
                      </div>

                      <div class="ibox-title m-t">
                          <h5>Detail Konfirmasi</h5>
                      </div>
                      <div class="ibox-content">
                          {!! Form::model($data['transaction'],['url' => url('admin/user/update/'), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
                          <div class="form-group {{ ($errors->has('mc_bank_name')?"has-error":"") }}"><label class="col-sm-2 control-label">Nama Bank Pengirim</label>
                              <div class="col-sm-4 col-xs-12">
                                  {!! Form::text('mc_bank_name', null, ['class' => 'form-control disabled', 'readonly','disabled']) !!}
                                  {!! $errors->first('mc_bank_name', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                              </div>
                          </div>
                          <div class="hr-line-dashed"></div>
                          <div class="form-group {{ ($errors->has('mc_bank_account')?"has-error":"") }}"><label class="col-sm-2 control-label">Atas Nama Pengirim</label>
                              <div class="col-sm-4 col-xs-12">
                                  {!! Form::text('mc_bank_account', null, ['class' => 'form-control disabled', 'readonly','disabled']) !!}
                                  {!! $errors->first('mc_bank_account', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                              </div>
                          </div>
                          <div class="hr-line-dashed"></div>
                          <div class="form-group {{ ($errors->has('mc_bank_account_number')?"has-error":"") }}"><label class="col-sm-2 control-label">Nomor Rekening</label>
                              <div class="col-sm-4 col-xs-12">
                                  {!! Form::text('mc_bank_account_number', null, ['class' => 'form-control disabled', 'readonly','disabled']) !!}
                                  {!! $errors->first('mc_bank_account_number', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                              </div>
                          </div>
                          <div class="hr-line-dashed"></div>
                          <div class="form-group {{ ($errors->has('mc_bank_name')?"has-error":"") }}"><label class="col-sm-2 control-label">Bank Tujuan Transfer</label>
                              <div class="col-sm-4 col-xs-12">
                                  {!! Form::text('mc_bank_name', null, ['class' => 'form-control disabled', 'readonly','disabled']) !!}
                                  {!! $errors->first('mc_bank_name', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                              </div>
                          </div>
                          <div class="hr-line-dashed"></div>
                          <div class="form-group {{ ($errors->has('mc_status')?"has-error":"") }}"><label class="col-sm-2 control-label">Status</label>
                              <div class="col-sm-4 col-xs-12">
                                {!! Form::select('mc_status', ['pending' => 'Menunggu Moderasi', 'paid' => 'Terbayar'], null, ['class' => 'form-control disabled','readonly','disabled']) !!}
                                {!! $errors->first('mc_status', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                              </div>
                          </div>
                          <div class="hr-line-dashed"></div>
                          <div class="form-group {{ ($errors->has('mc_status')?"has-error":"") }}"><label class="col-sm-2 control-label">Moderator</label>
                              <div class="col-sm-2 col-xs-6">
                                <input type="text" enabled class="form-control enabled" readonly value="<?=(@$data['transaction']['mc_moderator_id']!="")?date("Y-m-d H:i:s",$data['transaction']['mc_moderator_time']):"-"?>">
                              </div>
                              <div class="col-sm-2 col-xs-6">
                                <input type="text" enabled class="form-control enabled" readonly value="<?=(@$data['transaction']['mc_moderator_id']!="")?"by ".$data['transaction']['mc_moderator']:"-"?>">
                              </div>
                              <div class="col-sm-2 col-xs-6">
                                <input type="text" enabled class="form-control enabled" readonly value="<?=(@$data['transaction']['mc_moderator_id']!="")?$data['transaction']['mc_moderator_name']:"-"?>">
                              </div>
                          </div>

                        {!! Form::close() !!}
                    </div>

                    <div class="ibox-title m-t">
                        <h5>Detail Pengguna</h5>
                    </div>
                    <div class="ibox-content">

                        {!! Form::model($data['user'],['url' => url('admin/user/update/'), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}

                          <div class="form-group {{ ($errors->has('first_name')?"has-error":"") }}"><label class="col-sm-2 control-label">Nama Depan</label>
                              <div class="col-sm-4 col-xs-12">
                                  {!! Form::text('first_name', null, ['class' => 'form-control disabled', 'readonly','disabled']) !!}
                                  {!! $errors->first('first_name', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                              </div>
                          </div>
                          <div class="hr-line-dashed"></div>
                          <div class="form-group {{ ($errors->has('last_name')?"has-error":"") }}"><label class="col-sm-2 control-label">Nama Belakang</label>
                              <div class="col-sm-4 col-xs-12">
                                  {!! Form::text('last_name', null, ['class' => 'form-control disabled', 'readonly','disabled']) !!}
                                  {!! $errors->first('last_name', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                              </div>
                          </div>
                          <div class="hr-line-dashed"></div>
                          <div class="form-group {{ ($errors->has('email')?"has-error":"") }}"><label class="col-sm-2 control-label">Email</label>
                              <div class="col-sm-4 col-xs-12">
                                  {!! Form::email('email', null, ['class' => 'form-control disabled', 'readonly','disabled']) !!}
                                  {!! $errors->first('email', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                              </div>
                          </div>
                          <div class="hr-line-dashed"></div>
                          <div class="form-group {{ ($errors->has('phone')?"has-error":"") }}"><label class="col-sm-2 control-label">No. Telp</label>
                              <div class="col-sm-4 col-xs-12">
                                  {!! Form::text('phone', null, ['class' => 'form-control disabled', 'readonly','disabled']) !!}
                                  {!! $errors->first('phone', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                              </div>
                          </div>
                          <div class="hr-line-dashed"></div>
                          <div class="form-group {{ ($errors->has('username')?"has-error":"") }}"><label class="col-sm-2 control-label">Username</label>
                              <div class="col-sm-4 col-xs-12">
                                  {!! Form::text('username', null, ['class' => 'form-control disabled', 'readonly','disabled']) !!}
                                  {!! $errors->first('username', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                              </div>
                          </div>
                          <div class="hr-line-dashed"></div>
                          <div class="form-group {{ ($errors->has('id_department')?"has-error":"") }}"><label class="col-sm-2 control-label">Department</label>
                              <div class="col-sm-4 col-xs-12">
                                  {!! Form::select('id_department', $optdepartment, null, ['class' => 'form-control disabled', 'readonly','disabled']) !!}
                                  {!! $errors->first('id_department', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                              </div>
                          </div>
                          <div class="hr-line-dashed"></div>
                          <div class="form-group {{ ($errors->has('address')?"has-error":"") }}"><label class="col-sm-2 control-label">Alamat</label>
                              <div class="col-sm-4 col-xs-12">
                                  {!! Form::textarea('address', null, ['class' => 'form-control disabled', 'readonly','disabled']) !!}
                                  {!! $errors->first('address', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                              </div>
                          </div>
                          <div class="hr-line-dashed"></div>
                          <div class="form-group {{ ($errors->has('status')?"has-error":"") }}"><label class="col-sm-2 control-label">Status</label>
                              <div class="col-sm-4 col-xs-12">
                                {!! Form::select('status', ['active' => 'Aktif', 'inactive' => 'Tidak Aktif'], null, ['class' => 'form-control disabled','readonly','disabled']) !!}
                                {!! $errors->first('status', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                              </div>
                          </div>
                          <div class="hr-line-dashed"></div>
                          <div class="form-group">
                              <div class="col-sm-4 col-sm-offset-2">
                                  <a class="btn btn-white" href="{{ url('admin/account') }}">
                                      <i class="fa fa-angle-left"></i> kembali
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
