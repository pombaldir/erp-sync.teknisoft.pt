 <?php if($act==""){ ?>
 <script src="<?php echo URLBASE;?>/build/js/jasny-bootstrap/js/jasny-bootstrap.min.js"></script>
<script src="<?php echo URLBASE;?>/build/js/jquery.ajaxfileupload.js"></script>


  <script src="<?php echo URLBASE;?>/vendors/iCheck/icheck.min.js"></script>
  <script src="<?php echo URLBASE;?>/vendors/fastclick/lib/fastclick.js"></script>
  <script src="<?php echo URLBASE;?>/vendors/select2/dist/js/select2.full.min.js"></script>
  
  <script src="<?php echo URLBASE;?>/vendors/autosize/dist/autosize.min.js"></script>
 <script>
$(document).ready(function() {  

$( "form" ).on( "submit", function( event ) {
  event.preventDefault();
  
  $.ajax({
  type: "POST",
  url: "<?php echo URLBASE;?>/data/settings",
  data: $( this ).serialize(),
  dataType: "json",
  success: function(data){
	
	new PNotify({
		title: "Formulário submetido",
		type: ""+data.type+"",
		text: ""+data.message+"",
		nonblock: {
		nonblock: true
		},
		addclass: 'dark',
		styling: 'bootstrap3',
		hide: true,
			  before_close: function(PNotify) {
				PNotify.update({
				  title: PNotify.options.title + " - Enjoy your Stay",
				  before_close: null
				});
				PNotify.queueRemove();
				return false;
			  }
	}); 
  },
 	error: function (request, status, error) {
	alert("Erro: "+request.responseText);
	console.log(error);
}
  
});

});


function validaFields(){
	var tpWebsite=$('#store').val();
	if(tpWebsite=="prestashop"){
		$('#ws_secret').hide();
		$(".lbws_secret").hide();
		$(".divportes").show(); 
	}
	if(tpWebsite=="woocommerce"){
		$('#ws_secret').show();
		$(".lbws_secret").show();
		$(".divportes").hide(); 
	}
	
}
$( "#store" ).change(function() {
  validaFields();
});
validaFields(); 


 	$('#uploadfile').ajaxfileupload({
      'action': '<?php echo URLBASE;?>/data/settings.php',
	  'params': {'accaoP': "editafotodef"},
	  'valid_extensions' : ['gif','png','jpg','jpeg'],
	  onStart: function() {
		 $('#uploadfile').hide(); 
		 $('#fotouser').attr('src','<?php echo URLBASE;?>/build/images/loading.gif').attr('height',$(this).height());
	  },
	   onComplete: function(filename, response) { 
	   console.log(response);
	   //$('#fotouser').fadeOut("fast").attr('src','<?php echo DOCROOT."/attachments/".$_SESSION['empresaID']."/defimage-".$_SESSION['empresaID'].".jpg";?>').fadeIn("fast");				 	 
	   $('#uploadfile').show();
	   
	   	new PNotify({
			title: "Foto editada",
			type: "info",
			text: "",
			nonblock: {
				nonblock: true
			},
			addclass: 'dark',
			styling: 'bootstrap3',
			hide: true
		});
	   //console.log('Resposta: '); console.log(response); console.log(this); 
	   },
	 // 'submit_button' : $('a[id="upload"]')
    });
 





});

</script>
 
 
 <?php  } ?>