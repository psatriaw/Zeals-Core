<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<?php
  $main_url = $config['main_url'];
  $stringselected = "";
  $stringselectedarray = array();

  if($items){
    foreach ($items as $key => $value) {
      $stringselectedarray[] = $value->id_product;
    }

    $stringselected = implode(",",$stringselectedarray);
  }
?>
<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Produk</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ url($main_url) }}">Produk</a>
                    </li>
                    <li>
                        <a href="{{ url($main_url.'/rumus/'.$data->id_product) }}"> Edit Bahan Pada <?=$data->product_name?></a>
                    </li>
                    <li class="active">
                        <strong>Ubah Produk</strong>
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
                        <h5>Ubah Produk ke dalam Produk "<?=$data->product_name?>"</h5>
                    </div>
                    <div class="ibox-content">
                      @if ($errors->any())
                          <div class="alert alert-danger">
                              Ada kesalahan! mohon cek formulir.
                              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                          </div>
                      @endif
                      @include('backend.flash_message')
                      {!! Form::model($detail,['url' => url($main_url.'/mrp/'.$data->id_product.'/update-product/'.$id_product_on_product), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
                        <div class="form-group {{ ($errors->has('id_sub_product')?"has-error":"") }}"><label class="col-sm-2 control-label">Produk</label>
                            <div class="col-sm-6 col-xs-12">
                                {!! Form::select('id_sub_product', $products, null, ['class' => 'form-control thetarget']) !!}
                                {!! $errors->first('id_sub_product', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group {{ ($errors->has('product_quantity')?"has-error":"") }}"><label class="col-sm-2 control-label">Quantity</label>
                            <div class="col-sm-3 col-xs-12">
                              <div class="input-group">
                                {!! Form::text('product_quantity', null, ['class' => 'form-control']) !!}
                                <span class="input-group-addon"> dalam satuan unit produk</span>
                              </div>
                                {!! $errors->first('product_quantity', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                                <a class="btn btn-white" href="{{ url($main_url.'/mrp/'.$data->id_product) }}">
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

<script>
  $(document).ready(function() {
    $('.thetarget').select2({
      ajax: {
        url: '{{ url("get-list-product") }}',
        dataType: 'json',
        data: function (params) {
          var query = {
            search: params.term,
            type: 'public',
            selected:"<?=$stringselected?>"
          }
          // Query parameters will be ?search=[term]&type=public
          return query;
        }
      }
    });
  });
</script>
