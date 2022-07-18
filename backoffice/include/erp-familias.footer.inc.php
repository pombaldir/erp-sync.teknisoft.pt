 <?php if($act==""){ ?>  
 	<!-- iCheck -->
    <script src="<?php echo URLBASE;?>/vendors/iCheck/icheck.min.js"></script>
    <!-- Datatables -->
    <script src="<?php echo URLBASE;?>/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo URLBASE;?>/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script src="<?php echo URLBASE;?>/vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="<?php echo URLBASE;?>/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
    <script src="<?php echo URLBASE;?>/vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="<?php echo URLBASE;?>/vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="<?php echo URLBASE;?>/vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="<?php echo URLBASE;?>/vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
    <script src="<?php echo URLBASE;?>/vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
    <script src="<?php echo URLBASE;?>/vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="<?php echo URLBASE;?>/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
    <script src="<?php echo URLBASE;?>/vendors/datatables.net-scroller/js/dataTables.scroller.min.js"></script>
    <script src="<?php echo URLBASE;?>/vendors/jszip/dist/jszip.min.js"></script>
    <script src="<?php echo URLBASE;?>/vendors/pdfmake/build/pdfmake.min.js"></script>
    <script src="<?php echo URLBASE;?>/vendors/pdfmake/build/vfs_fonts.js"></script>  
	<script src="<?php echo URLBASE;?>/vendors/select2/dist/js/select2.min.js"></script>
  

  
 <script>
$(document).ready(function() {  

var oTable = $('#table<?php echo $p;?>').DataTable( {
			"aaSorting": [[ 0, "asc" ]],
			"bProcessing": true,
			"bServerSide": true,
			"aoColumnDefs": [ 
		 	{ "bVisible":  false, "bSearchable": false, "aTargets": [ 0 ]},
			{ "bVisible":  true, "bSearchable": false, "bSortable": false, "aTargets": [4]},
			{
			"class": 'text-center',	
			"render": function ( data, type, row ) { 
					if(data==1){ return '<button type="button" class="btn btn-xs btn-round btn-success">Sim</button>'; } else { return '<button type="button" class="btn btn-xs btn-round btn-danger">Não</button>';  }
            }, "aTargets": [3]},
			{
			"render": function ( data, type, row ) { 
					return '<a href="erp-familias/edit/'+row[0]+'" class="btn btn-xs btn-primary"><i class="fa fa-exchange"></i> Sincronizar</a>';
            }, "aTargets": [4]}			
	 		],
			"sAjaxSource": "<?php echo $settings['erp_ws'];?>/familias.php", 
	        "bPaginate": true,
	        "bSort": true,
			"sDom": "<'row'<'dataTables_header clearfix'<'col-md-1'l><'col-xs-2 selFamilia'><'col-md-9'f>r>>t<'row'<'dataTables_footer clearfix'<'col-md-6'i><'col-md-6'p>>>",
			"fnServerParams": function ( aoData ) {
		    	aoData.push({ "name": "auth_userid", "value": "<?php echo $settings['ws_token'];?>" },{ "name": "act_g", "value": "list" },{ "name": "nivel", "value": $('#selFamilia').val() });
		    },
			"oLanguage": {
				"sInfo": "_START_ a _END_ de _TOTAL_ registos",
				"sLengthMenu": "_MENU_",
				"sInfoEmpty": "Não existem registos",	
				"sEmptyTable": "Não existem registos",	
				"sZeroRecords": "Não existem registos a exibir",
				"sSearch": "Pesquisar: ",
				"oPaginate": {
				  "sPrevious": "Anterior",
				  "sNext": "Seguinte",
				  "sFirst": "Início",
				  "sLast": "Última"
				},
				"sInfoFiltered": ""
			}
	       }); 
		   
			   
	$('.selFamilia').html('<select class="form-control selFam" style="width:100%" name="selFamilia" id="selFamilia"><?php echo $htmlsel;?></select>');
	$('#selFamilia').select2().on("change", function (e) { oTable.draw();  });	

	$('div.dataTables_length').width('100%');
	$('div.dataTables_length select').select2();		   
	oTable.draw(); 	   		    
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
	  
  $.ajax({
	  type: "POST",
	  url: "<?php echo URLBASE;?>/data/familias",
	  data: $( this ).serialize(),
	  dataType: "json",
	  success: function(data){
		
		new PNotify({
				title: "Sincronizar Famílias",
				type: ""+data.type+"",
				text: ""+data.message+"",
				nonblock: {
				nonblock: true
				},
				addclass: 'light',
				styling: 'bootstrap3',
				hide: true						  
				}); 
	 	},
        error: function (request, status, error) {
		new PNotify({
				title: "Erro",
				type: "error",
				text: ""+request.responseText+"",
				nonblock: {
				nonblock: true
				},
				addclass: 'light',
				styling: 'bootstrap3',
				hide: true						  
				}); 
		}
	});

  }
  
});


function change() {
  var oItem = document.getElementById('store');
  var value = oItem.options[oItem.selectedIndex].value;
  if(value == "custom") {
	  $('#familiaModal').modal('show');
	 /* 
    value = prompt("Introduza o nome da nova categoria:", ""+$("#erp_familiaD").val()+"");
	if(value!=null){
    $("#store").prop("disabled", true);
	$.ajax({
	  type: "POST",
	  url: "<?php echo URLBASE;?>/data/familias",
	  data: {"accaoP":"createWeb","nome":""+value+"", "catERP":"<?php echo $num;?>", "platform": "<?php echo $settings['store'];?>"},
	  success: function(data){
		console.log(data);
		
		if(data.success==1){
		var option = document.createElement("option");
		option.text = ""+value+"";
		option.value = ""+data.catID+"";
		var select = document.getElementById("store");
		select.appendChild(option);
		$("#store").val(data.catID);

		$("#store option:last").attr("selected", "selected");

		}
	
		$("#store").prop("disabled", false);
		
	  },
        error: function (request, status, error) {
		new PNotify({
				title: "Erro",
				type: "error",
				text: ""+request.responseText+"",
				nonblock: {
				nonblock: true
				},
				addclass: 'light',
				styling: 'bootstrap3',
				hide: true						  
				}); 
		},
	  dataType: "json"
	});
	} else  {
	
	document.getElementById("store").selectedIndex = 0; 

	 }*/
  }
}


$( ".btnCreateCateg" ).click(function() {
  var oItem = document.getElementById('store');
  var value = oItem.options[oItem.selectedIndex].value;
  $('.btnCreateCateg').attr("disabled", true);

  
$.ajax({
	  type: "POST",
	  url: "<?php echo URLBASE;?>/data/familias",
	  data: {"accaoP":"createWeb","nome":$('#Newerp_familiaD').val(), "catParent":$('#parentstore option:selected').val(), "catERP":"<?php echo $num;?>", "platform": "<?php echo $settings['store'];?>"},
	  success: function(data){
		console.log(data);
		
		if(data.success==1){
		var option = document.createElement("option");
		option.text = $('#Newerp_familiaD').val();
		option.value = ""+data.catID+"";
		var select = document.getElementById("store");
		select.appendChild(option);
		$("#store").val(data.catID);
		$("#store option:last").attr("selected", "selected");
		}
	
		$("#store").prop("disabled", false);
		$('#familiaModal').modal('hide');
	  },
        error: function (request, status, error) {
		new PNotify({
				title: "Erro",
				type: "error",
				text: ""+request.responseText+"",
				nonblock: {
				nonblock: true
				},
				addclass: 'light',
				styling: 'bootstrap3',
				hide: true						  
				}); 
		},
	  dataType: "json"
	});

});

$('#familiaModal').on('shown.bs.modal', function (e) {
  $('.btnCreateCateg').attr("disabled", false);
})


$('select').on('change', function() {
	change();
});	

$( ".btnunlink" ).click(function(event) {
$.ajax({
	  type: "POST",
	  dataType: "json",
	  url: "<?php echo URLBASE;?>/data/familias",
	  data: {"accaoP":"remsinc","num":event.target.id},
	  success: function(data){
		new PNotify({
				title: "Remover sincronização",
				type: ""+data.type+"",
				text: ""+data.message+"",
				nonblock: {
				nonblock: true
				},
				addclass: 'light',
				styling: 'bootstrap3',
				hide: true						  
		}); 		
	  },
        error: function (request, status, error) {
		new PNotify({
				title: "Erro",
				type: "error",
				text: ""+request.responseText+"",
				nonblock: {
				nonblock: true
				},
				addclass: 'light',
				styling: 'bootstrap3',
				hide: true						  
				}); 
		}
	});	
});


});	
</script>
<?php } ?>