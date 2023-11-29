<?php
  if($avatar!=""){
    $avatar = url($avatar);
  }else{
    $avatar = url("public/templates/assets/img/avatar_default.png");
  }
?>
<div class="media lawanbicara">
  <div class="media-right text-center pull-right">

      <img class="media-object avatar" src="{{ $avatar }}" alt="...">
      <?=$first_name?>

  </div>
  <div class="media-body pull-right">
    <p class="text-strong">
      <?=($content!="")?$content."<br>":""?>
      <?php
        if($file_path!=""){
          ?><a href="<?=url($file_path)?>"><?=basename($file_path)?></a><?php
        }
      ?>
    </p>
    <p>pada <?=date("d, M Y - H:i:s",$time_created)?></p>
  </div>
</div>
