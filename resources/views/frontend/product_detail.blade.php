<main role="main">
  <?php
    $sizes = array();

    if($stocks){
      foreach ($stocks as $key => $value) {
        $sizes[$value->size] = $value->size;
      }
    }
  ?>
  <section class="jumbotron text-center">
    <div class="container">
      <div class="superline"></div>
      <div class="custom-container">
        <h1><?=@$detail->product_name?></h1>
      </div>
    </div>
  </section>
 

	<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
	<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

  <div class="album pb-5 top30">
    <div class="container">
      @include('backend.flash_message')
      <div class="album-content">
        <div class="row justify-content-md-center">
          <div class="col-md-6 col-12">
            <div class="" style="margin-bottom:25px;">
				<?php 
				if($detail->paths!=""){
					?><div class="swiper-container"><?php
					?><div class="swiper-wrapper"><?php
					$paths = explode("#",$detail->paths);
					foreach($paths as $key=>$val){
						?>
							<div class="swiper-slide">
							  <img class="d-block w-100" src="<?=$val?>">
							</div>
						<?php
					}
					
					?></div><?php 
					?><div class="swiper-pagination"></div><?php 
					?></div><?php 
					
				}
			  ?>
			  <script>
				var swiper = new Swiper('.swiper-container', {
				  slidesPerView: 'auto',
				  pagination: {
					el: '.swiper-pagination',
					clickable: true,
				  },
				});
			  </script>
			  
            </div>
          </div>

          <div class="col-md-6 col-12">
            <div class="">
              <h2>Rp. <?=number_format($detail->price - $detail->discount,0,",",".")?></h2>
              {!! Form::open(['url' => url('add-to-cart'), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate', "enctype" => "multipart/form-data"]) !!}
              <div class="description form-group {{ ($errors->has('first_name')?"has-error":"") }}">
                <?=nl2br($detail->description)?>
              </div>

              <div class="description form-group {{ ($errors->has('first_name')?"has-error":"") }}">
                <?=nl2br($detail->info_size_guide)?>
              </div>

              <?php if(sizeof($sizes)){ ?>
              <div class="form-group top30 {{ ($errors->has('size')?"has-error":"") }}">
                  <input type="hidden" value="<?=$detail->id_product?>" name="id_product">
                  <input type="hidden" value="<?=$detail->permalink?>" name="permalink">
                  {!! Form::select('size', $sizes,null, ['class' => 'form-control','placeholder' => 'Pilih Ukuran','id' => 'thesize']) !!}
                  {!! $errors->first('size', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
              </div>

              <div class="form-group {{ ($errors->has('quantity')?"has-error":"") }}">
                  {!! Form::select('quantity', array(),null, ['class' => 'form-control','placeholder' => 'Silahkan pilih ukuran terlebih dahulu','id' => 'availability']) !!}
                  {!! $errors->first('quantity', '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
              </div>

              <div class="form-group">
                  <div class="text-center">
                    <button class="btn btn-primary btn-block shadow-none text-orange disabled" type="button" id="add-to-cart" disabled>ADD TO CART</button>
                  </div>
              </div>
              <?php }else{ ?>
                <div class='alert alert-warning'>
                  <p class='description'>Oops, Stok Kosong!</p>
                </div>
              <?php }?>

              {!! Form::close() !!}
          </div>
      </div>
    </div>
  </div>


      <div class="album-content top100">
        <div class="row">
          <?php
            if($active_products){
              foreach ($active_products as $key => $value) {
                $path = $value->paths;
                $exploded = explode("/",$path);
                $exploded[sizeof($exploded)-1] = "thumbnail_".$exploded[sizeof($exploded)-1];
                $newpath  = implode("/",$exploded);

                ?>
                <div class="col-md-3 col-6">
                  <div class="card mb-4 shadow-sm">
                    <a href="{{ url('/shop/'.$value->permalink) }}" class="product-link">
                      <div class="product-item">
                        <div class="preview-product" style="background:url('<?=$newpath?>')"></div>
                        <div class="card-body">
                          <p class="card-text text-center"><?=$value->product_name?></p>
                          <div class="text-center">
                            <span class="text-muted">Rp. <?=number_format($value->price,0,",",".")?></span>
                          </div>
                        </div>
                      </div>
                    </a>
                  </div>
                </div>
                <?php
              }
            }
          ?>

        </div>
      </div>
    </div>
  </div>
</main>
<!-- Facebook Pixel Code -->

<script>
	  $("#thesize").change(function(){
		var thesize = $(this).val();
		$("#availability").html("");

		$.ajax({
		  type:"GET",
		  dataType: "json",
		  url:"<?=url('get-list-stock-of-product')?>",
		  data: {id_product: <?=$detail->id_product?>, thesize : thesize}
		})
		.done(function(result){
		  console.log(result);

		  if(result.status=="success"){
			$("#availability").html(result.html);
		  }
		})
		.fail(function(msg){
		  console.log(msg);
		})
		.always(function(){

		});
	  });
	  
	$("#availability").change(function(e){
		if($(this).val()!="0"){
			$("#add-to-cart").removeClass("disabled").prop("disabled",false);
		}else{
			$("#add-to-cart").addClass("disabled").prop("disabled",true);
		}
	});
	
	$("#add-to-cart").click(function(e){
		var size 	= $("#thesize").value;
		var stock 	= $("#availability").value;
		
		console.log(size);
		console.log(stock);
		
		if(size!="" && stock!=""){
			$("#formmain").submit();
		}
	});
</script>
<script>
	$("#carouselExampleSlidesOnly").carousel('1');
</script>
<!-- End Facebook Pixel Code -->
