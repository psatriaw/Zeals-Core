<main role="main">

  <section class="jumbotron text-center">
    <div class="container">
      <div class="superline"></div>
      <div class="custom-container">
        <h1><?=$title?></h1>
      </div>
    </div>
	<?php if($content!=""){ ?>
	<div class="container text-center top100">
      <?=$content?>
    </div>
	<?php } ?>
	
  </section>

  <div class="album py-5">
    <div class="container">
      <div class="album-content">
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
