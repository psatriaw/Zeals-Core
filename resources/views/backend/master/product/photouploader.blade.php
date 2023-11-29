<?php $prefix = ""; ?>
<div class="modal fade" id="<?=$prefix?>uploadphoto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
		<div class="modal-header">
			<!--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
			<h4 class="modal-title" id="<?=$prefix?>myModalLabel">Upload Photo</h4>
			<span style="position:absolute;top:15px;right:15px;z-index:10;" id="loadinglabel"></span>
		</div>
		<div class="modal-body padding15 top-bottom-30 text-center" id="<?=$prefix?>bodyupload">
			<button type="button" class="btn btn-white" onclick="selectFromPC();">Pilih dari PC</button>
		</div>
		<div id="update-cover-area" style="display:none;position:relative;">

		</div>
		<div class="modal-footer">
			<button type='button' class='btn btn-primary <?=$prefix?>afterupload' style='display:none;' onclick='selectFromPCLogo();'>Upload lainnya</button>
			<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
		</div>
    </div>
  </div>
</div>
  {{ Form::open(array('files'=>'true','id' => $prefix."uploadPC")) }}
	<input type="file" id="<?=$prefix?>fileupload" name="file">
	<input type="hidden" name="id_product" value="{{$id_product}}">
	<div id="<?=$prefix?>inputfile"></div>
  {{ Form::close() }}

<?php
	$id_user = 12;
	$id_cafe = 1;
?>
<script>
	var <?=$prefix?>uploadoption = "";
	var <?=$prefix?>photoData;
	var <?=$prefix?>photoUploaded = new Array();
	var <?=$prefix?>counterPhoto = 0;
	var <?=$prefix?>instagramSelectedPhoto = new Array();

	$(document).ready(function(){
		// $("#uploadModal").modal({"backdrop":"static"});
		<?=$prefix?>uploadoption = $("#<?=$prefix?>bodyupload").html();
	});

	function showuploadmodal(){
		$("#<?=$prefix?>uploadphoto").modal({"backdrop":"static"});
		ulangiuploadLogo();
	}

	function ulangiuploadLogo(){
		$("#<?=$prefix?>uploadlagi").hide();
		// $("#<?=$prefix?>bodyupload").html(<?=$prefix?>uploadoption);
	}

	function simpanUploadFileLogo(){
		$("#<?=$prefix?>simpanUploadFileLogo").html("Saving. Please wait..");
		var readydata = new Array();
		for(var i in <?=$prefix?>photoUploaded){
			var datanya 		= <?=$prefix?>photoUploaded[i];
			datanya['caption']	= $("#<?=$prefix?>caption_"+(<?=$prefix?>photoUploaded[i].id)).val();
			readydata.push(datanya);
		}

		$.ajax({
			url: "<?=url("cafe/uploadPhotoPC")?>",
			type: "POST",
			data: {id_user: <?=$id_user?>,photos:readydata, id_cafe: <?=$id_cafe?>}
		})
		.done(function(result){
			var hasil = JSON.parse(result);
			if(hasil.code=="success"){
				$("#<?=$prefix?>simpanUploadFileLogo").hide();
				$(".<?=$prefix?>afterupload").hide();
				$("#<?=$prefix?>uploadlagi").show();
				<?=$prefix?>photoUploaded = new Array();
				$("#<?=$prefix?>bodyupload").html("<h2 class='text-center'><i class='fa fa-check'></i></h2><p class='text-center'>Success!</p>");
			}else{
				$("#<?=$prefix?>uploadlagi").hide();
				$("#<?=$prefix?>simpanUploadFileLogo").show();
			}
		})
		.fail(function(){
			$("#<?=$prefix?>bodyupload").html("<p class='text-center'>Oops, can't upload photo this time.</p>");
		})
		.always(function(){
			$("#<?=$prefix?>simpanUploadFileLogo").html("Simpan");
		});
	}

	function selectFromPC(){
		<?=$prefix?>counterPhoto++;
		$(".<?=$prefix?>afterupload").show();
		$("#<?=$prefix?>bodyupload").html("");
		$("#<?=$prefix?>fileupload").click();
	}

	function selectFromPCLogo(){
		<?=$prefix?>counterPhoto++;
		console.log("click logo");
		$("#<?=$prefix?>fileupload").click();
	}

	$("#<?=$prefix?>fileupload").change(function(event){

		if (typeof (FileReader) != "undefined") {
            var image_holder = $("#image-holder");
            image_holder.empty();

            var reader = new FileReader();

            reader.onload = function (e) {
      				photoData 	= e.target.result;

      				var html = "<div id='<?=$prefix?>upload_"+<?=$prefix?>counterPhoto+"' class='text-left'><div class='media'><div class='media-body'><h4 class='media-heading' id='<?=$prefix?>labelupload_"+<?=$prefix?>counterPhoto+"'>Mengunggah Foto</h4><div class='progress'> <div class='progress-bar progress-bar-info progress-bar-striped' id='<?=$prefix?>progressbarval_"+<?=$prefix?>counterPhoto+"' role='progressbar' aria-valuenow='80' aria-valuemin='0' aria-valuemax='100' style='width:0%'> </div> </div> <form><p class='errorlabel' id='<?=$prefix?>errorupload_"+<?=$prefix?>counterPhoto+"'></p></form></div></div></div>";
      				$("#<?=$prefix?>bodyupload").html(html);
      			}

            image_holder.show();
            reader.readAsDataURL($(this)[0].files[0]);
        }else{
            alert("This browser does not support FileReader.");
        }

		$("#<?=$prefix?>uploadPC").submit();
	});

	function deletephoto(theid){
		if(<?=$prefix?>photoUploaded.length>0){
			for(var i in <?=$prefix?>photoUploaded){
				if(<?=$prefix?>photoUploaded[i].id==theid){
					if(confirm("Yakin menghapus foto?")){
						$("#<?=$prefix?>upload_"+theid).remove();
						<?=$prefix?>photoUploaded.splice(i,1);
					}
				}
			}
		}

		if(<?=$prefix?>photoUploaded.length==0){
			$("#<?=$prefix?>simpanUploadFileLogo").hide();
		}

		if(<?=$prefix?>instagramSelectedPhoto.length>0){
			for(var i in <?=$prefix?>instagramSelectedPhoto){
				if(<?=$prefix?>instagramSelectedPhoto[i].id==theid){
					if(confirm("Yakin menghapus foto?")){
						$("#<?=$prefix?>upload_"+theid).remove();
						<?=$prefix?>instagramSelectedPhoto.splice(i,1);
					}
				}
			}
		}

		if(<?=$prefix?>instagramSelectedPhoto.length==0){
			$("#<?=$prefix?>doimportLogo").hide();
		}
	}

	$("#<?=$prefix?>uploadPC").submit(function(event){
		$('#<?=$prefix?>progressbarval_'+<?=$prefix?>counterPhoto).addClass("progress-bar-info").removeClass("progress-bar-danger");
		event.preventDefault();
		var formData = new FormData(this);
		$.ajax({
			xhr: function()
			  {
				var xhr = new window.XMLHttpRequest();
				xhr.upload.addEventListener("progress", function(evt){
				  if (evt.lengthComputable) {
					var percentComplete = Math.round(100*evt.loaded / evt.total);
					if(percentComplete==100){
						uploading = "completed";
						$("#<?=$prefix?>labelupload_"+<?=$prefix?>counterPhoto).html("Uploaded <i class='fa fa-check'></i>");
						$('#<?=$prefix?>progressbarval_'+<?=$prefix?>counterPhoto).css({"width":"100%"});
					}else{
						uploading = "";
						$('#<?=$prefix?>labelupload_'+<?=$prefix?>counterPhoto).html("Uploading ("+(percentComplete)+"%)");
						$('#<?=$prefix?>progressbarval_'+<?=$prefix?>counterPhoto).css({"width":percentComplete+"%"});
					}
				  }
				}, false);
				xhr.addEventListener("progress", function(evt){
				  if (evt.lengthComputable) {
					var percentComplete = Math.round(100*evt.loaded / evt.total);
					if(percentComplete==100){
						uploading = "completed";
						$("#<?=$prefix?>labelupload_"+<?=$prefix?>counterPhoto).html("Selesai <i class='fa fa-check'></i>");
						$('#<?=$prefix?>progressbarval_'+<?=$prefix?>counterPhoto).css({"width":"100%"});
					}else{
						uploading = "";
						$('#<?=$prefix?>labelupload_'+<?=$prefix?>counterPhoto).html("Selesai ("+(percentComplete)+"%)");
						$('#<?=$prefix?>progressbarval_'+<?=$prefix?>counterPhoto).css({"width":percentComplete+"%"});
					}
				  }
				}, false);
				return xhr;
			},
			url:"<?=url("upload-photo")?>",
			type:"POST",
			data: formData,
			contentType: false,
			cache: false,
			processData:false,
		})
		.done(function(result){
			if(result.status=="success"){
  				$("#<?=$prefix?>simpanUploadFileLogo").show();

          <?php if($previlege->isAllow($login->id_user,$login->id_department,"admin-master-product-uronshop-remove")){?>
          var html = "<div class=\"col-sm-1\"><img src=\""+result.thumbnail+"\" class=\"img-responsive img-thumbnail\"><div class=\"float-right-top confirm\" data-url=\"{{ url('remove-photo') }}\" data-id=\""+result.id_relation+"\"><i class=\"fa fa-trash\"></i> hapus</div></div>";
          <?php }else{ ?>
          var html = "<div class=\"col-sm-1\"><img src=\""+result.thumbnail+"\" class=\"img-responsive img-thumbnail\"></div>";
          <?php }?>

			    $("#item-images").prepend(html);

          $(".confirm").click(function(){
            var id  = $(this).attr("data-id");
            var url = $(this).attr("data-url");
            $("#confirmaction").prop("action",url);
            $("#primaryKey").val(id);
            $("#confirmmodal").modal("show");
          });

			}else{
				$("#<?=$prefix?>labelupload_"+<?=$prefix?>counterPhoto).html("Gagal unggah <i class='fa fa-times'></i>");
				$("#<?=$prefix?>errorupload_"+<?=$prefix?>counterPhoto).html(result.reason+"");
				setTimeout(function(){
					$("#<?=$prefix?>upload_"+<?=$prefix?>counterPhoto).remove();
				},3000);
			}
		})
    .fail(function(msg){
      console.log(msg);
      //$("#alert-test").html(msg.responseText);
      $('#<?=$prefix?>progressbarval_'+<?=$prefix?>counterPhoto).removeClass("progress-bar-info").addClass("progress-bar-danger");
      $("#<?=$prefix?>labelupload_"+<?=$prefix?>counterPhoto).html("Gagal unggah <i class='fa fa-times'></i>");
      $("#<?=$prefix?>errorupload_"+<?=$prefix?>counterPhoto).html(data.reason+"");
    });
	});
</script>
<script>

	function loadCropper(urlfile,namafile, id_cafe){
		$.ajax({
			url: "<?=url("load-photo-cropper")?>",
			type: "POST",
			data: {cover:urlfile, namafile:namafile, id_cafe:id_cafe}
		})
		.done(function(result){
			console.log(result);
			$("#update-cover-choose-photo").hide();
			$("#update-cover-area").html(result).fadeIn(200);
		})
		.fail(function(msg){
			console.log(msg);
		});
	}

	function getPhotoAgain(){
		$("#update-cover-area").hide();
		$("#update-cover-choose-photo").fadeIn(200);
		loadGalleryCropper();
	}
</script>
