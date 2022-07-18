 <?php if($act=="edit"){ ?>  
 <!-- jQuery Smart Wizard -->
    <script src="<?php echo URLBASE;?>/vendors/jQuery-Smart-Wizard/js/jquery.smartWizard.js"></script>
<!-- bootstrap-progressbar -->
    <script src="<?php echo URLBASE;?>/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>  
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
	hideButtonsOnDisabled: false, // when the previous/next/finish buttons are disabled, hide them instead
    errorSteps:[],    // array of step numbers to highlighting as error steps
    labelNext:'Seguinte', // label for Next button
    labelPrevious:'Anterior', // label for Previous button
    labelFinish:'Terminar',  // label for Finish button        
    noForwardJumping:false,
    ajaxType: 'POST',
  // Events
    onLeaveStep: null, // triggers when leaving a step
    onShowStep: null,  // triggers when showing a step
    onFinish: termina,  // triggers when Finish button is clicked  
    buttonOrder: ['finish', 'next', 'prev']  // button order, to hide a button remove it from the list
}); 

<?php

$categorias=array();
$query = $mysqli->query("select id_store from familias") or die($mysqli->errno .' - '. $mysqli->error);
while($cat=$query->fetch_assoc()){
$categorias[]=$cat;
}
	
$totalCat=$row_cnt = $query->num_rows;
$js_array = json_encode($categorias);

?>


function termina(){
var tipo = $('#tpo').val();	 

if(tipo=="CatwebToErp"){
var promises = [];
<?php echo "var javascript_array = ". $js_array . ";\n"; ?>
var total=<?php echo $totalCat;?>;	
var i=1;
$('.progress-bar').css('width', '5%').attr('data-transitiongoal', 5);

$('.buttonFinish').attr("disabled", true); 

$.each(javascript_array, function(index, value) {
   var percent=Math.floor((i/total)*100);	    
   var request = $.ajax({
	type : "POST", 
	url : '<?php echo URLBASE;?>/data/erp-sync',
   	data : {"accaoP": "updtFamilias", "idnum": value.id_store},
	success: function(data, textStatus, xhr) {	
	 	$('.progress-bar').css('width', ''+percent+'%').attr('data-transitiongoal', percent);
	},
    error: function (request, status, error) {
	console.log(error+" "+request.responseText);
	}
	});	 

   promises.push( request);
   i++;
   });	
}

if(tipo=="FamErpReset"){

	$('.progress-bar').css('width', '5%').attr('data-transitiongoal', 5);
	
	$.ajax({
	type:"POST", 
	url:"<?php echo URLBASE;?>/data/erp-sync",
   	data : {"accaoP": ""+tipo+""},
	success: function(data, textStatus, xhr) {	
	 	console.log(data);
	},
    error: function (request, status, error) {
	//alert("Erro: "+request.responseText);
	console.log(error+" "+request.responseText);
	}
	});	 
	

}
$.when.apply(null, promises).done(function(){
   $('.progress-bar').css('width', '100%').attr('data-transitiongoal', 100);
   $('.buttonFinish').attr("disabled", false);
   
   new PNotify({
		title: "Sincronização de Dados",
		type: "info",
		text: "Processo concluído com sucesso",
		nonblock: {
		nonblock: true
		},
		addclass: 'light',
		styling: 'bootstrap3',
		hide: true
	}); 

})
}


});	
</script>
<?php } ?>