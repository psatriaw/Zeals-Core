<main role="main">
  <div class="container">
    <div class="bg-yellow registration">
      <div class="row">
        <div class="col-xs-12 col-md-7 reg-left">
          <h2>Welcome <?=@$login->first_name?></h2>
          <h1>
            World #1<br>
            Digital Marketing <br>
            Platform
          </h1>
        </div>

        <div class="col-xs-12 col-md-5 reg-right">
          <img src="<?=url("templates/admin/img/logo.jpg")?>" class="logo">
          <h3>Congratulations!</h3>
          <p>Your account is now ready.</p>
          <?php
            if($status=="success"){
              ?>
              <p class="alert alert-success"><?=$response?></p>
              <?php
            }else{
              ?>
              <p class="alert alert-danger"><?=$response?></p>
              <?php
            }
          ?>
          <p class=""><a href="{{ url('signin') }}" class="text-blue"><strong>Sign In here</strong></a></p>
        </div>
      </div>
    </div>
  </div>
</main>
<script>
  $(document).ready(function(){
    setTimeout(function(){
      document.location = "<?=url('campaign')?>";
    },5000);
  });
</script>
