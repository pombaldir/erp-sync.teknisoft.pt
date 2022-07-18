 <?php if($act=="edit"){ ?>  
 <!-- jQuery Smart Wizard -->
    <script src="<?php echo URLBASE;?>/vendors/jQuery-Smart-Wizard/js/jquery.smartWizard.js"></script>
<!-- bootstrap-progressbar -->
    <script src="<?php echo URLBASE;?>/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>  
   <link rel="stylesheet" type="text/css" href="<?php echo URLBASE;?>/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css">
 <script>
$(document).ready(function() {  

$('#wizard2').smartWizard({
  // Properties
    selected: 0,  // Selected Step, 0 = first step   
    keyNavigation: true, // Enable/Disable key navigation(left and right keys are used if enabled)
    enableAllSteps: false,  // Enable/Disable all steps on first load
    transitionEffect: 'fade', // Effect on navigation, none/fade/slide/slideleft
    contentURL:null, // specifying content url enables ajax content loading
    contentURLData:null, // override ajax query parameters
    contentCache:true, // cache step contents, if false content is fetched always from ajax url
    cycleSteps: false, // cycle step navigation
    enableFinishButton: false, // makes finish button enabled always
	hideButtonsOnDisabled: true, // when the previous/next/finish buttons are disabled, hide them instead
    errorSteps:[],    // array of step numbers to highlighting as error steps
    labelNext:'Seguinte', // label for Next button
    labelPrevious:'Anterior', // label for Previous button
    labelFinish:'Terminar',  // label for Finish button        
    noForwardJumping:true,
    ajaxType: 'POST',
  // Events
    onLeaveStep: null, // triggers when leaving a step
    onShowStep: null,  // triggers when showing a step
    onFinish: termina,  // triggers when Finish button is clicked  
    buttonOrder: ['finish', 'next', 'prev']  // button order, to hide a button remove it from the list
}); 

<?php

if(isset($prefs) && $prefs['store']=="woocommerce"){
//$artigos=get_produtosTotal("id,sku");
$artigos=getAllArtigos();// die(print_r($artigos));

}
if(isset($prefs) && $prefs['store']=="prestashop"){
$artigos=get_produtosTotal("id,reference");
}
//print_r($artigos);
$artigosArr=array();

$totalArt=0;
foreach($artigos as $k=>$v){
	if($v['sku']!=""){
		$artigosArr[]=array("sku"=>$v['sku'],"idws"=>$v['id'],"idref"=>$v['idArtRef']);
		$totalArt++;	
	}
}

$js_array = json_encode($artigosArr);

?>


function termina(){
var tipo = $('#tpo').val();	 
var promises = [];
var total=<?php echo $totalArt;?>;	
var i=0;

<?php echo "var javascript_array = ". $js_array . ";\n"; ?>


if(tipo=="ArtwebToErp"){


$('#textoDetalhe').text("A obter artigos do Website");
$('.progress-bar').css('width', '14%').attr('data-transitiongoal', 14);
$('.buttonFinish').attr("disabled", true); 

 	var requestReset = $.ajax({
	type : "POST", 
	url : '<?php echo $prefs['erp_ws'];?>/artigos.php',
   	data : {"act_p": "updtArtigos", "auth_userid": "<?php echo $prefs['ws_token'];?>", "extra": <?php echo $js_array;?>},
	success: function(data, textStatus, xhr) {	
	 	$('#textoDetalhe').text("A sincronizar artigos no ERP");
		
		if(data.success==1){
			$('.progress-bar').css('width', '50%').attr('data-transitiongoal', 50);
			
			var request2 = $.ajax({
			type : "POST", 
			url : '<?php echo URLBASE;?>/data/callb',
			data : {"accaoP": "cb_artigo_upserial","dados":data.dados},
			success: function(data, textStatus, xhr) {	
				console.log(data);
				var percent=100;
				$('#textoDetalhe').text(""+data.message).removeClass('warning').addClass('success');
				$('.progress-bar').css('width', ''+percent+'%').attr('data-transitiongoal', percent);
			},
			error: function (request, status, error) {
				alert("Erro 3: "+request.responseText);
				console.log(error);
			}
			});	
			
			promises.push(request2);
		}
		
	},
    error: function (request, status, error) {
		alert("Erro: "+request.responseText);
		console.log(error);
	}
	});	 
   
   promises.push(requestReset);

}


if(tipo=="ArtStock"){
	var x=0; 		
	$('#textoDetalhe').text("A obter stocks no ERP");
	var requestStock = $.ajax({
	type : "POST", 
	url : '<?php echo $prefs['erp_ws'];?>/artigos.php',
   	data : {"act_p": "getStocks", "auth_userid": "<?php echo $prefs['ws_token'];?>", "dados": <?php echo $js_array;?>},
	success: function(data, textStatus, xhr) {	
	 	//console.log("Stock GET OK");	
		$('#textoDetalhe').text("Stocks obtidos do ERP");
		console.log("Stock Obtido");
		//console.log(data); 
		if(data!=""){
		var i=1; 
		$.each(data, function(k, v) {
			
		var refPrinc=javascript_array[x]['idref'];   

		var requestUpdStock = $.ajax({
		type : "POST", 
		url : '<?php echo URLBASE;?>/data/artigos.php',
		data : {"accaoP": "updtStk", "art": k, "stk": v, "ref": refPrinc},
		success: function(data, textStatus, xhr) {	
			console.log("Stock "+i+" PUT Refª "+refPrinc+", Artigo "+k+" Stock: "+v+"  ");  
			$('#textoDetalhe').text("Artigo: "+k+" Stock: "+v).removeClass('warning').addClass('success');
			console.log(data);   
		},
		error: function (request, status, error) {
			console.log(error);
			$('#textoDetalhe').text(k+" Erro: "+error).removeClass('success').addClass('warning');
		},
		complete: function () {
			var percent=Math.floor((i/total)*100);	
			$('.progress-bar').css('width', ''+percent+'%').attr('data-transitiongoal', percent);
			if(percent==100){
				$('.progress-bar').removeClass('progress-bar-warning').addClass('progress-bar-success');	
				setTimeout(function(){ $('#textoDetalhe').text(total+" artigos sincronizados");	}, 3000);
				new PNotify({
					title: "Sincronização de Dados",
					type: "info",
					text: "Processo concluído com sucesso",
					nonblock: { nonblock: true },
					addclass: 'light',
					styling: 'bootstrap3',
					hide: true
				}); 
			}
			i++;
		}
		});	 
		
		promises.push(requestUpdStock);
		x++;
		
		});	 
		
		}
				
	},
    error: function (request, status, error) {
		console.log(error);
	}
	});	 
	
	
	
	promises.push(requestStock);	
}



$.when.apply(null, promises).done(function(){
   //$('.progress-bar').css('width', '100%').attr('data-transitiongoal', 100);
   $('.buttonFinish').attr("disabled", true);
   var promises = "";
   

})

}


$('#tpo').change(function() {
  	$('.spandescr').hide();
	$('#span'+$(this).val()).show();
});
$('.spandescr').hide();



});	
</script>
<?php } ?>