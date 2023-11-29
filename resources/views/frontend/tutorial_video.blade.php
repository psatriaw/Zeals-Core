<div class="modal fade" id="tutorialHowTo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body text-center">
        <h1>How to earn Money?</h1>
        <div class="">
           <iframe style="width:100% !important;" height="315" src="https://www.youtube.com/embed/zLlh_LxDNcw" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
        </div>   
        <p class="mt-5">
            <a href="{{ url('signin') }}" class="btn btn-full-secondary btn-xl">Share your link now</a>
        </p>
      </div>
    </div>
  </div>
</div>

<style>
.float-button{    
    position: fixed;
    bottom: 0px;
    width: 120px;
    height: 120px;
    overflow: hidden;
    margin: 10px;
    border-radius: 50%;
    box-shadow: 0px 7px 9px -4px #000;
}

.float-button img{
    width:100%;
    cursor:pointer;
}
</style>
<?php if($login){ ?>
<div class="float-button" onclick="openTutorial()">
    <img src="{{ url('how_does_it_work_shortcut.png') }}">
</div>
<?php } ?>

<script>
    
        function openTutorial(){
            $("#tutorialHowTo").modal("show");
        } 
</script>