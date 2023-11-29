<main role="main">

  <section class="jumbotron text-center">
    <div class="container">
      <div class="superline"></div>
      <div class="custom-container">
        <h1>Keranjang Belanja</h1>
      </div>
    </div>
    <div class="container text-center top100">
      <?php if($login){ ?>
        <h3>Hai <?=$login->first_name?></h3>
      <?php }else{ ?>
        <h3>Hai</h3>
      <?php }?>
      <p class="text-gray">Silahkan lakukan pemesanan untuk toko Anda melalui formulir dibawah ini</p>
    </div>
  </section>
  <div class="album py-1 pb-5 top30">
    <div class="container">
      @include('backend.flash_message')
      <div class="album-content top20">
        <div class="table-responsive">
          {!! Form::open(['url' => url('do-pesan'), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate', "enctype" => "multipart/form-data"]) !!}
          <table class="table table-bordered table-hover dataTables-example" id="datatable">
            <thead>
              <tr>
                  <th style="width:30px;">No.</th>
                  <th>Nama Item</th>
                  <th style="width:150px;">Harga Satuan</th>
                  <th style="width:80px;">Beli</th>
              </tr>
            </thead>
            <tbody>
              <?php
                $no = 0;
                if(@$items){
                  foreach ($items as $key => $value) {
                    $no++;
                    $detail = $product_model->getDetail($value['id_product']);
                    ?>
                    <tr>
                        <td class="text-gray" style="width:30px;"><?=$no?></td>
                        <td class="text-gray"><?=@$detail->product_name?></td>
                        <td class="text-gray text-right">Rp. <?=number_format(@$detail->price - @$detail->discount)?></td>
                        <td class="text-gray">
                          <?php
                              echo "<input type='text' name='qty_".@$detail->id_product."' style='width:80px;' value='0'>";
                          ?>
                        </td>
                    </tr>
                    <?php
                  }
                }
              ?>
            </tbody>
          </table>
          <button type="submit" class="btn btn-lg btn-primary pull-right" id="checkout">PROSES PEMESANAN</button>
          {!! Form::close() !!}
        </div>
      </div>
    </div>
  </div>
</div>
</main>
<script>
  function updatecartitem(val,key){
    $("#checkout").addClass("disabled").prop("disabled",true);

    $.ajax({
      type:"GET",
      dataType: "json",
      url:"<?=url('update-cart')?>",
      data: {val: val, key:key}
    })
    .done(function(result){

    })
    .fail(function(msg){
      console.log(msg);
    })
    .always(function(){
        $("#checkout").toggleClass("disabled").prop("disabled",false);
    });
  }
</script>
