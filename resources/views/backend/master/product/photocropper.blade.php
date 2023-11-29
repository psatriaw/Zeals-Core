<link href='<?=url("templates/admin/css/jquery.guillotine.css")?>' media='all' rel='stylesheet'>
<div class="cropper-controls">
	<button id='zoom_out' 		class="btn btn-crop" type='button' title='Putar Kanan'> <i class='fa fa-minus-circle'></i> </button>
	<button id='fit'         	class="btn btn-crop"  type='button' title='Fit ke Layar'> <i class='fa fa-arrows-alt'></i>   </button>
	<button id='zoom_in'     	class="btn btn-crop"  type='button' title='Zoom'> <i class='fa fa-plus-circle'></i>  </button>
	<button id='choose_photo' 	class="btn btn-crop" onclick="getPhotoAgain()" type='button' title='Pilih foto dari PC'> <i class='fa fa-picture-o'></i> </button>
	<button id='save_n_crop' 	class="btn btn-red" type='button' title='Simpan cover'> <i class='fa fa-floppy-o'></i> </button>
</div>
<a href="#"><img src="<?=$cover?>" id='coverPhoto' class="img-responsive" alt="" /></a>
<script src='<?=url("templates/admin/js/jquery.guillotine.js")?>'></script>
<?php
	list($Rwidth, $Rheight) = getimagesize(str_replace(base_url(),"",$cover));
?>
<script type='text/javascript'>
	var DATACROP = [];
	var DATAFILE;

	jQuery(function() {
		var imgwidth = parseInt(<?=$Rwidth?>);
		// console.log("width "+imgwidth);
		// if(imgwidth<682){
			// $("#zoom_out").hide();
			// $("#fit").hide();
			// $("#zoom_in").hide();
		// }
		var picture = $('#coverPhoto');

		// Make sure the image is completely loaded before calling the plugin
		picture.one('load', function(){
			// Initialize plugin (with custom event)
			picture.guillotine({
				width: 682,
				height: 305,
				eventOnChange: 'guillotinechange'
			});

			var data = picture.guillotine('getData');
			for(var key in data) {
				console.log(data[key]);
			}

			$('#rotate_left').click(function(){ picture.guillotine('rotateLeft'); });
			$('#rotate_right').click(function(){ picture.guillotine('rotateRight'); });
			$('#fit').click(function(){ picture.guillotine('fit'); });
			$('#zoom_in').click(function(){ picture.guillotine('zoomIn'); });
			$('#zoom_out').click(function(){ picture.guillotine('zoomOut'); });

			// Update data on change
			picture.on('guillotinechange', function(ev, data, action) {
				data.scale = parseFloat(data.scale.toFixed(4));
				DATACROP = data;
				// for(var k in data) {
					// console.log(k+" isinya "+data[k]);
				// }
			});
		});

		if (picture.prop('complete')) picture.trigger('load')
	});

	$("#save_n_crop").click(function(){
		DATACROP.file 		= "<?=$cover?>";
		DATACROP.namafile 	= "<?=$namafile?>";
		DATACROP.id_cafe 	= <?=$id_cafe?>;
		console.log(DATACROP);
		$("#loadinglabel").html("menyimpan..");
		$.ajax({
			url: "<?=url('photo-upload-crop')?>",
			type: "POST",
			data: DATACROP
		})
		.done(function(result){
			console.log(result);
			var hasil = JSON.parse(result);
			if(hasil.status=="success"){
				$("#main_cover").prop("src",hasil.url);
				$("#pasangCover").modal("hide");
				$("#uploadLogo").modal("hide");
				$("#loadinglabel").html("Tersimpan");
				alert("Tersimpan!");
			}else{
				$("#loadinglabel").html("Gagal menyimpan");
			}
		})
		.fail(function(msg){
			console.log(msg);
		})
		.always(function(msg){
			setTimeout(function(){
				$("#loadinglabel").html("");
			},3000);
		});
	});
</script>
