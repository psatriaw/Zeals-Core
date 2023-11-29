<main role="main">

  <section class="jumbotron text-center">
    <div class="container">
      <div class="superline"></div>
      <div class="custom-container">
        <h1>SHOPPING CART</h1>
      </div>
    </div>
    <div class="container text-center top100">
      <?php if($login){ ?>
        <h3>Hai <?=$login->first_name?></h3>
      <?php }else{ ?>
        <h3>Hai</h3>
      <?php }?>
      <p class="text-gray">Silahkan cek kembali  keranjang belanja kamu dibawah ini,<br>
pastikan shipping kamu sudah ke alamat yang tepat ya!</p>
    </div>
  </section>
  <div class="album py-1 pb-5 top30">
    <div class="container">
      <div class="text-right">
          <a href="{{ url('shop') }}" class='btn btn-white'><i class='fa fa-store'></i> belanja lagi<a>
      </div>
      @include('backend.flash_message')
      <div class="album-content top20">
        <div class="table-responsive">
          <table class="table table-bordered table-hover dataTables-example" id="datatable">
            <thead>
              <tr>
                  <th style="width:30px;">No.</th>
                  <th>Item</th>
                  <th style="width:150px;">Qty</th>
                  <th style="width:150px;">Satuan</th>
                  <th style="width:150px;">Potongan</th>
                  <th style="width:150px;">Sub Total</th>
                  <th style="width:150px;"></th>
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
                        <td class="text-gray"><?=$detail->product_name?></td>
                        <td class="text-gray">
                          <?php
                            $qty = $value['quantity'];


                              $html = "";
                              for ($i=0; $i <= 1000; $i++) {
                                if($value['quantity']==$i){
                                  $html   = $html."<option value='$i' selected>".$i."</option>";
                                }else{
                                  $html   = $html."<option value='$i'>".$i."</option>";
                                }
                              }

                              echo "<select name='qty_".$key."' onchange='updatecartitem(this.value,$key)' class='form-control custom-select-checkout'>";
                              echo $html;
                              echo "</select>";

                          ?>
                        </td>
                        <td class="text-gray">Rp. <?=number_format($detail->price,0,",",".")?></td>
                        <td class="text-gray">Rp. <?=number_format($detail->discount,0,",",".")?></td>
                        <td class="text-gray">Rp. <?=number_format(($detail->price - $detail->discount)*$value['quantity'],0,",",".")?></td>
                        <td class="text-right text-gray">
                          <a href="{{ url('cart-remove-item/'.$key) }}" class="text-danger">[<i class='fa fa-trash'></i> hapus]</a>
                        </td>
                    </tr>
                    <?php
                  }
                }
              ?>
            </tbody>
          </table>
          {!! Form::open(['url' => url('do-checkout'), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate', "enctype" => "multipart/form-data"]) !!}
          <button type="submit" class="btn btn-lg btn-primary pull-right" id="checkout">CHECKOUT</button>
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
