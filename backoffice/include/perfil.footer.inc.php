 <?php if($act==""){ ?>
 
 <script>
$(document).ready(function() {  

$( "form" ).on( "submit", function( event ) {
  event.preventDefault();
  
  $.ajax({
  type: "POST",
  url: "<?php echo URLBASE;?>/data/perfil",
  data: $( this ).serialize(),
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
  dataType: "json"
});

});
  
});

</script>
 
 
 <?php } if($act=="edit"){ ?>
<script src="<?php echo URLBASE;?>/build/js/jasny-bootstrap/js/jasny-bootstrap.min.js"></script>
<script src="<?php echo URLBASE;?>/build/js/jquery.ajaxfileupload.js"></script>

 <!-- bootstrap-wysiwyg -->
 <script src="<?php echo URLBASE;?>/vendors/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js"></script>
 <script src="<?php echo URLBASE;?>/vendors/jquery.hotkeys/jquery.hotkeys.js"></script>
 <script src="<?php echo URLBASE;?>/vendors/google-code-prettify/src/prettify.js"></script>

<!-- validator -->
<script src="<?php echo URLBASE;?>/vendors/validator/validator.js"></script>
<script type="text/javascript" src="<?php echo URLBASE;?>/build/js/forms.js"></script>
<script type="text/JavaScript" src="<?php echo URLBASE;?>/build/js/sha512.js"></script>
<script>
$(document).ready(function() {  

$( "form" ).on( "submit", function( event ) {
  event.preventDefault();
  
  var validatorResult = validator.checkAll(this);

  if(validatorResult){
	  
  if($('#password').val()!="" && $('#password2').val()!=""){
	  	formhashJQ(userform, document.getElementById("password"), document.getElementById("password2"));
  }		  

  $.ajax({
  type: "POST",
  url: "<?php echo URLBASE;?>/data/perfil",
  data: $( this ).serialize(),
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
  dataType: "json"
});

  }
  
});





 	$('#uploadfile').ajaxfileupload({
      'action': '<?php echo URLBASE;?>/data/perfil.php',
	  'params': {'idusredit': "<?php if(isset($num) && $num>0){ echo $num; } else { echo $_SESSION['user_id']; }	?>", 'accaoP': "editafoto"},
	  'valid_extensions' : ['gif','png','jpg','jpeg'],
	  onStart: function() {
		 $('#uploadfile').hide(); 
		 $('#fotouser').attr('src','<?php echo URLBASE;?>/build/images/loading.gif').attr('height',$(this).height());
	  },
	   onComplete: function(filename, response) { 
	   console.log(response);
		   $.getJSON("<?php echo URLBASE;?>/data/perfil.php?accaoG=getUserPic&user_id=<?php echo $_SESSION['user_id'];?>", function( data ) {
			 $('#fotouser').fadeOut("fast").attr('src','data:image/jpeg;base64,'+data.fotodata+'').fadeIn("fast");	
			 $('.profile_img').fadeOut("fast").attr('src','data:image/jpeg;base64,'+data.fotodata+'').fadeIn("fast");	
			 	 
		   });
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
<?php } ?>