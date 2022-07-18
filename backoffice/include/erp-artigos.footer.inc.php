<script src="<?php echo URLBASE;?>/vendors/gasparesganga-jquery-loading-overlay/dist/loadingoverlay.min.js"></script>
<script src="<?php echo URLBASE;?>/vendors/quagga/dist/quagga.min.js"></script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.min.js"></script>
<script src="<?php echo URLBASE;?>/vendors/bootbox/bootbox.min.js"></script>
<script src="<?php echo URLBASE;?>/vendors/switchery/dist/switchery.min.js"></script>
	
	
	<?php 
		
		$imagemdef=settings_val(1,"imagem");  
		$imagemdef=$imagemdef['filename'];
		
		if(is_file(DOCROOT."/attachments/".$_SESSION['empresaID']."/$imagemdef")){
			$imgDef="<img class=\"fotoPrincipal\" src=\"".URLBASE."/attachments/".$_SESSION['empresaID']."/$imagemdef\" alt=\"\">";	 	 
		 }  else {
			$imgDef="<img class=\"fotoPrincipal\" src=\"".URLBASE."/images/user.png\" alt=\"\">"; 
		 }
		?> 



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
			"aaSorting": [[ 0, "desc" ]],
			"bProcessing": true,
			"bServerSide": true,
			"aoColumnDefs": [ 
		 	{ "bVisible":  false, "bSearchable": false, "aTargets": [ 0 ]},
			{ "bVisible":  true, "bSearchable": false, "aTargets": [ 3,4 ]},
			{ "bVisible":  true, "bSearchable": false, "bSortable": false, "aTargets": [ 4,5 ], "sClass": "text-center"},
            { "sClass": "text-center", "aTargets": [ 3 ]},    
			{
                //data:   "active", 
                render: function ( data, type, row ) {
                    if ( type === 'display' ) {
                        return '<input type="checkbox" class="chkbx" value="1" data-familia="'+row[5]+'">';
                    }
                    return data;
                },
                className: "dt-body-center",
				"aTargets": [4]
            },
			{
			"render": function ( data, type, row ) {
                    return '<a href="#'+row[0]+'">'+data +'</a>';
            },
			"aTargets": [1]
            },
			{
			"render": function ( data, type, row ) {
                    return '<a href="<?php echo URLBASE;?>/erp-artigos/edit/'+row[0]+'" class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i>editar</a>';
            },
			"aTargets": [5]
            }		
			 
	 		],
			"sAjaxSource": "<?php echo $settings['erp_ws'];?>/artigos.php", 
            "lengthMenu": [[20, 50, 100, 200, 4000], [20, 50, 100, 200, "Todos"]],
	        "bPaginate": true,
	        "bSort": true,
			"sDom": "<'row'<'dataTables_header clearfix'<'col-md-1'l><'col-xs-2 selFamilia'><'col-xs-2 selMarca'><'col-xs-2 selWeb'><'col-md-5'f   >r>>t<'row'<'dataTables_footer clearfix'<'col-md-6'i><'col-md-6'p>>>",
			"fnServerParams": function ( aoData ) {
		    	aoData.push({ "name": "auth_userid", "value": "<?php echo $settings['ws_token'];?>" },{ "name": "act_g", "value": "list" },{ "name": "Familia", "value": $('#selFamilia').val() },{ "name": "Marca", "value": $('#selMarca').val() },{ "name": "bitDispWeb", "value": $('#webprodts').val() });
		    },
			"rowCallback": function ( row, data ) {
				$.each(data, function(i, item) {
				var idRow=data[0];	
				if(i==3){
					if(data[4]==1){ 
					$(row).find('td:eq('+i+') > input.chkbx').attr("id",idRow).prop("checked",true);
					} else {		
					$(row).find('td:eq('+i+') > input.chkbx').attr("id",idRow).prop("checked",false);
					}
				}
				}); 
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
		   
		   
	$('.selFamilia').html('<select class="form-control selFam" style="width:100%" name="selFamilia" id="selFamilia"></select>');
		$('#selFamilia').select2({
			ajax: {
				url: '<?php echo "".$settings['erp_ws']."/familias.php?act_g=listaFamNiveis&tpnivel=".$settings['tpfamilia']."&auth_userid=".$settings['ws_token']."";?>'
			},
			allowClear: true,
			placeholder: 'Família'
	}).on("change", function (e) { oTable.draw();  });	
	
	$('.selMarca').html('<select class="form-control selFam" style="width:100%" name="selMarca" id="selMarca"></select>');
		$('#selMarca').select2({
			ajax: {
				url: '<?php echo "".$settings['erp_ws']."/familias.php?act_g=listaFamNiveis&tpnivel=".$settings['tpmarcas']."&auth_userid=".$settings['ws_token']."";?>'
			},
			allowClear: true,
			placeholder: 'Marca'
	}).on("change", function (e) { oTable.draw();  });	
	$('.selWeb').html('<div class="checkbox"><label><input type="checkbox" name="webprodts" class="flat" id="webprodts" value=""> Artigos web</label></div>').on("ifChecked", function (e) { $('#webprodts').val(1); oTable.draw();  }).on("ifUnchecked", function (e) { $('#webprodts').val(0);  oTable.draw();  });	
	
	
	
		
$('div.dataTables_length').width('100%');
$('div.dataTables_length select').select2();		   
	 	   		   
// Seleciona os artigos para cada checkbox
	$("#table<?php echo $p;?> tbody").delegate("input:checkbox", "click", function( e ) {
        var idArtigo=$(this).closest('input[id]').attr('id');  
        
            if ( $(this).is(":checked") ) 
            {
                $(this).closest("tr").addClass("row_selected");  
				// Progress
				$("#row_"+idArtigo).LoadingOverlay("show", {
					image       : "",
					fontawesome : "fa fa-cog fa-spin",
					"font-size" : "14px",
					text        : "A importar para a loja...",
					progress    : true
				});
				 

 				$.ajax({
              	type : "POST",
				dataType: "json",
              	url : '<?php echo $settings['erp_ws'];?>/artigos.php',
              	data : {"act_p": "checkweb", "auth_userid": "<?php echo $settings['ws_token'];?>", "id_artigo": idArtigo},
              	success: function(data, textStatus, xhr) {
				
                    
					var artigo=data.artigo; 
					//console.log(artigo);							
					$.ajax({
						type : "POST", 
						dataType: "json",
						url : '<?php echo URLBASE;?>/data/callb',
						data : {"accaoP": "cb_artigo_a","id_artigo":idArtigo,"platform":"<?php echo $settings['store'];?>", "detail":artigo},
						success: function(data, textStatus, xhr) {
                            $('.ui-pnotify').remove();
							//console.log(data);
							new PNotify({
							title: "Importação ERP-Loja Online",
							type: ""+data.type+"",
							text: ""+data.message+"",
							nonblock: {
							nonblock: true
							},
							addclass: 'light',
							styling: 'bootstrap3',
							hide: true						  
							}); 
						$("#row_"+idArtigo).LoadingOverlay("hide");	
						},
						 error: function (request, status, error) {
							alert("Erro: "+request.responseText);
							console.log(error);
                            $("#row_"+idArtigo).LoadingOverlay("hide");	 
						}
				});	 	
				},
              	 error: function (request, status, error) {
					$("#row_"+idArtigo).LoadingOverlay("hide");	 
					alert("Erro: "+request.responseText);
					console.log(error);
				}
        		});
            }
            else 
            {
                $(this).closest("tr").removeClass("row_selected");
                $("#row_"+idArtigo).LoadingOverlay("show", {
					image       : "",
					fontawesome : "fa fa-cog fa-spin",
					"font-size" : "14px",
					text        : "A remover artigo...",
					progress    : true
				});

                
 				$.ajax({
              	type : "POST",
				dataType: "json",
              	url : '<?php echo $settings['erp_ws'];?>/artigos.php',
              	data : {"act_p": "uncheckweb", "auth_userid": "<?php echo $settings['ws_token'];?>", "id_artigo": $(this).closest('input[id]').attr('id')},
              	success: function(data, textStatus, xhr) {
				var idartgod=data.idartigo; 
				
				$.ajax({
              		type : "POST", 
              		url : '<?php echo URLBASE;?>/data/callb',
              		data : {"accaoP": "cb_artigo_d", "id_artigo": idartgod, "platform": "<?php echo $settings['store'];?>"},
					success: function(data, textStatus, xhr) {	
					    $('.ui-pnotify').remove();
						new PNotify({
						title: "Importação ERP-Loja Online",
						type: ""+data.type+"",
						text: ""+data.message+"",
						nonblock: {
						nonblock: true
						},
						addclass: 'light',
						styling: 'bootstrap3',
						hide: true						  
						});
						$("#row_"+idArtigo).LoadingOverlay("hide");	 
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
					$("#row_"+idArtigo).LoadingOverlay("hide");		
				}
				});	 		
					
              	},
              	error: function (request, status, error) {
					alert("Erro: "+request.responseText);
					console.log(error);
				}
        		});				
            }
	});		   
		   
		   
		   
		   

		   
		   
		   
		    
});

</script>
 
 
  <?php } if($act=="edit"){ ?>  
<script src="<?php echo URLBASE;?>/vendors/bootbox/bootbox.min.js"></script>
<script src="<?php echo URLBASE;?>/vendors/blueimp-file-upload/js/vendor/jquery.ui.widget.js"></script>
<script src="<?php echo URLBASE;?>/vendors/blueimp-file-upload/js/jquery.iframe-transport.js"></script>
<script src="<?php echo URLBASE;?>/vendors/blueimp-file-upload/js/jquery.fileupload.js"></script>
<script src="<?php echo URLBASE;?>/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
<!-- iCheck -->
<script src="<?php echo URLBASE;?>/vendors/iCheck/icheck.min.js"></script>
<script src="<?php echo URLBASE;?>/vendors/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js"></script>
<script src="<?php echo URLBASE;?>/vendors/jquery.hotkeys/jquery.hotkeys.js"></script>
<script src="<?php echo URLBASE;?>/vendors/google-code-prettify/src/prettify.js"></script>    
<!-- Switchery -->
<script src="<?php echo URLBASE;?>/vendors/switchery/dist/switchery.min.js"></script>
        
 <script>
$(document).ready(function() {  

<!-- Elimina Foto -->
$(document).on('click', '.elimFoto', function(){
event.preventDefault();
var idFoto=(this).id;
var tipoAtt=$(this).attr("data-tipo");
eliminaATT(tipoAtt,idFoto); 
});	
<!-- /Elimina Foto -->

function eliminaATT(tipo,idFoto){
bootbox.confirm("<h4>Deseja eliminar este ficheiro?</h4>", function(result){
		  	if (result) {
			console.log("Eliminar Foto:"+tipo+" ("+idFoto+")");
			$.ajax({
				 type: "POST",
				 url: "<?php echo $prefs['erp_ws'];?>/artigos.php",
				 data: {act_p:"delFoto",idAtt:""+idFoto+"",tipo:""+tipo+"","idartigo":"<?php echo $num;?>","auth_userid":"<?php echo $prefs['ws_token'];?>"},
				 dataType: "json",
				 success: function(data){	
					  
					new PNotify({
							title: "Eliminar Ficheiro",
							type: ""+data.type+"",
							text: ""+data.message+"",
							nonblock: {
							nonblock: true
							},
							styling: 'bootstrap3',
							hide: true
					}); 
					
					if(data.success==1 && (tipo=="mainfoto")){
						$('#imgpredef').attr('src','<?php echo URLBASE."/attachments/".$_SESSION['empresaID']."/$imagemdef";?>');	
					}	/*
					if(data.success==1 && tipo=="anexos"){
						$("#anexo"+idFoto).remove();				
					}
					*/
				},
				error: function(xhr, status, error) {
				console.log(error);
				   new PNotify({
						title: "Erro",
						type: "warning",
						text: ""+xhr.responseText+"",
						nonblock: {
						nonblock: true
						},
						styling: 'bootstrap3',
						hide: true
					}); 
				}
			});	
			}
		});	
}
<!-- /Elimina Foto -->


    // Change this to the location of your server-side upload handler:
    $('#fileupload').fileupload({
        url: '<?php echo URLBASE;?>/data/UploadHandler',
        dataType: 'json',
        done: function (e, data) {
			
            $.each(data.result.files, function (index, file) {
		    $('#imgpredef').attr('src',''+file.url+'');
			new PNotify({
				title: "Enviar Imagem",
				type: "info",
				text: ""+file.name+"",
				nonblock: { nonblock: true },
				styling: 'bootstrap3',
				hide: true
				}); 		   	    
			$('#imgpredef').attr('src',''+file.url+'');		
			/*$.ajax({
				url: ''+file.deleteUrl+'',
				type: 'DELETE'
				});*/
			});	
			
        },
		error: function (jqXHR, textStatus, errorThrown) {
			new PNotify({
						title: "Erro",
						type: "warning",
						text: ""+textStatus+"<br>"+errorThrown+"<br>"+jqXHR['responseText']+"",
						nonblock: {
						nonblock: true
						},
						styling: 'bootstrap3',
						hide: true
			}); 
			console.log(jqXHR);
		
		},
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#progressB .progress-bar').css(
                'width',
                progress + '%'
            );
        }
    }).prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');	








$( "#btnsinc" ).click(function(event) {
event.preventDefault();	
$.ajax({
	  type: "POST",
	  dataType: "json",
	  url: "<?php echo URLBASE;?>/data/artigos",
	  data: {"accaoP":"sincArtigo","id":"<?php echo getArtigoStoreId($num);?>","nome":$("#strDescricao").val(),"stock":$("#QuantStock").val(),"sku":$("#strCodigo").val(),"preco":"10"},
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
			before_close: function(PNotify) { PNotify.queueRemove(); return false; }
		}); 
		console.log(data);	
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


<?php } if($act=="scan"){ ?>  
<script src="<?php echo URLBASE;?>/vendors/blueimp-file-upload/js/vendor/jquery.ui.widget.js"></script>
<script src="<?php echo URLBASE;?>/vendors/blueimp-file-upload/js/jquery.iframe-transport.js"></script>
<script src="<?php echo URLBASE;?>/vendors/blueimp-file-upload/js/jquery.fileupload.js"></script>


<script src="<?php echo URLBASE;?>/build/js/JsBarcode.all.min.js"></script>
<script src="<?php echo URLBASE;?>/build/js/jquery.scannerdetection.js"></script>
<script>



$(document).scannerDetection({
	timeBeforeScanTest: 200, // wait for the next character for upto 200ms
	startChar: [120], // Prefix character for the cabled scanner (OPL6845R)
	endChar: [13], // be sure the scan is complete if key 13 (enter) is detected
	avgTimeByChar: 40, // it's not a barcode if a character takes longer than 40ms
	onComplete: function(barcode, qty){ 
	
	
	barcodigo(barcode);
	
	} // main callback function	
});


$(document).on('click', '.checkwebsite', function () {
if ($(this).is(':checked')) {
	var acCao="checkweb";
	var acCao2="cb_artigo_a";
	var txtLoad="A importar artigo";
	var corLoad="rgba(91,192,222, 0.5)";
} else {
	var acCao="uncheckweb";	
	var acCao2="cb_artigo_d";
	var txtLoad="A remover artigo";
	var corLoad="rgba(217,83,79, 0.5)";
}
	var idartigo=$(this).data('idart');
$(".card").LoadingOverlay("show", {
	fontawesome : "fa fa-cog fa-spin",
	image       : "",
    background  : ""+corLoad+"",
	text        : txtLoad
});	
$.ajax({
	type : "POST",
	dataType: "json",
    url : '<?php echo $settings['erp_ws'];?>/artigos.php',
    data : {"act_p": acCao, "auth_userid": "<?php echo $prefs['ws_token'];?>", "id_artigo": idartigo},
              	success: function(data, textStatus, xhr) {
				console.log(data);									
					var artigo=data.artigo; 						
					$.ajax({
						type : "POST", 
						dataType: "json",
						url : '<?php echo URLBASE;?>/data/callb',
						data : {"accaoP": acCao2,"id_artigo":idartigo,"platform":"<?php echo $prefs['store'];?>", "detail":artigo},
						success: function(data, textStatus, xhr) {
							new PNotify({
							title: "Importação ERP-Loja Online",
							type: ""+data.type+"",
							text: ""+data.message+"",
							nonblock: {
							nonblock: true
							},
							addclass: 'light',
							styling: 'bootstrap3',
							hide: true						  
							}); 
							setTimeout(function(){
								$(".card").LoadingOverlay("hide");
							}, 100);
						},
						 error: function (request, status, error) {
							$(".card").LoadingOverlay("hide");
							alert("Erro: "+request.responseText);
							console.log(error);
						}
				});	 	
				},
              	 error: function (request, status, error) {
					alert("Erro: "+request.responseText);
					console.log(error);
				}
        		});
});

$(document).on('click', '.fileupload', function () {

    $('.fileupload').fileupload({
        url: '<?php echo URLBASE;?>/data/UploadHandler',
        dataType: 'json',
        done: function (e, data) {
		$(".fotoPrincipal").LoadingOverlay("show", {
			fontawesome : "fa fa-upload",
			background  : "rgba(92,184,92, 0.5)",
			image       : ""
		});		
        $.each(data.result.files, function (index, file) {
		    $('.fotoPrincipal').attr('src',''+file.url+'');
			new PNotify({
				title: "Imagem atualizada",
				type: "info",
				text: ""+file.name+"",
				nonblock: { nonblock: true },
				styling: 'bootstrap3',
				hide: true
				}); 		  
			
			$('.fotoPrincipal').attr('src',''+file.url+'').LoadingOverlay("hide");		
		});	
        },
		error: function (jqXHR, textStatus, errorThrown) {
			new PNotify({
						title: "Erro",
						type: "warning",
						text: ""+textStatus+"<br>"+errorThrown+"",
						nonblock: {
						nonblock: true
						},
						styling: 'bootstrap3',
						hide: true
						
			}); 
			$('.fotoPrincipal').LoadingOverlay("hide");	
			console.log(jqXHR);
		
		}
    }).prop('disabled', !$.support.fileInput).parent().addClass($.support.fileInput ? undefined : 'disabled');	

}); 



$(document).on('click', '.removeFoto', function () {
var idArtigo=$(this).data('idart');	
bootbox.confirm("<h4>Deseja eliminar esta imagem?</h4>", function(result){
		  	if (result) {
			$.ajax({
				 type: "POST",
				 url: "<?php echo $prefs['erp_ws'];?>/artigos.php",
				 data: {act_p:"delFoto",idAtt:"foto"+idArtigo+"",tipo:"mainfoto","idartigo":idArtigo,"auth_userid":"<?php echo $prefs['ws_token'];?>"},
				 dataType: "json",
				 success: function(data){	
					  
					new PNotify({
							title: "Eliminar Foto",
							type: ""+data.type+"",
							text: ""+data.message+"",
							nonblock: {
							nonblock: true
							},
							styling: 'bootstrap3',
							hide: true
					}); 
					
					if(data.success==1){
						$('.fotoPrincipal').attr('src','<?php echo URLBASE."/attachments/".$_SESSION['empresaID']."/$imagemdef";?>');	
					}	
				},
				error: function(xhr, status, error) {
				console.log(error);
				   new PNotify({
						title: "Erro",
						type: "warning",
						text: ""+xhr.responseText+"",
						nonblock: {
						nonblock: true
						},
						styling: 'bootstrap3',
						hide: true
					}); 
				}
			});	
			}
		});	
});	





 



</script>
<?php  } ?>
<script>


$(document).on('click', '[data-toggle="lightbox"]', function(event) {
	event.preventDefault();               
	$(this).ekkoLightbox({
	alwaysShowClose: true
})});

$(document).on('click', '.gimages', function () {
	pesqImagem($(this).data('descricao'),$(this).data('idart'));
});	

function pesqImagem(texto,artigo=null){
$('.colextra').LoadingOverlay("show");
$.ajax({
	url: 'https://www.googleapis.com/customsearch/v1',
	data: {
		cx: '003758198843188163435:f5rieaf4bra',
		searchType: 'image',
		safe: 'active',
		c2coff: 1,
		cr: 'pt',
		filter: 1,
		gl: 'google.pt',
		fileType: 'jpg',
		num: 10,	// limite
		start: 1, 	// Página
		//lr: 'lang_pt',
		imgSize: 'large',
		imgType: 'photo',
		key: 'AIzaSyAU1HV7lc-FKJvtWMZFVjFOSx4Li3XftvM',
		q: ""+texto+""
	},
	success: function(response) {
		document.getElementById("extra").innerHTML="";
		<?php if($act=="scan"){ ?>
		
		var htmlOut='<form class="form-horizontal well" role="form">';		
		for (var i = 0; i < response.items.length; i++) {
		if((i+1)%6==0){ htmlOut += '<div class="row">'; }
        var item = response.items[i];
		htmlOut+='<div class="col-xs-2"><a data-gallery="galeria-pesquisa" data-toggle="lightbox" data-type="image" href="' + item.link+'"><img src="' + item.image.thumbnailLink+'" class="img-responsive img-radio" alt="' + item.title+'" ></a> <button type="button" class="btn btn-primary btn-xs btn-radio" data-url="' + item.link+'" data-idart="' + artigo+'">Selecionar</button> <input type="checkbox" id="center-item" value="' + item.image.thumbnailLink+'" class="hidden thumbcheck"> </div>';		
		if((i+1)%6==0){ htmlOut+='</div>'; }
	 	}
	  	htmlOut+='</form>';	
		<?php } if($act=="edit"){ ?>
		$('.gimages').hide();
		var htmlOut='';	
		for (var i = 0; i < response.items.length; i++) {	
		var item = response.items[i];
		htmlOut+='<div class="col-md-55"> <div class="thumbnail"> <div class="image view view-first"> <img id="imgpredef" style="width: 100%; display: block;" src="' + item.image.thumbnailLink+'" alt="' + item.title+'" /> <div class="mask"> <p>Ver foto</p> <div class="tools tools-bottom"> <a href="' + item.link+'" data-gallery="galeria-pesquisa" data-toggle="lightbox" data-type="image"><i class="fa fa-eye"></i></a>  </div> </div> </div> <div class="caption"> <p><button type="button" class="btn btn-primary btn-xs btn-radio" data-url="' + item.link+'" data-idart="' + artigo+'">Selecionar</button> <input type="checkbox" id="center-item" value="' + item.image.thumbnailLink+'" class="hidden thumbcheck"></p> </div> </div> </div>';
		}
		<?php } ?>
		document.getElementById("extra").innerHTML=htmlOut;
		$('.colextra').LoadingOverlay("hide");
	}
});	

}


$(document).on('click', '.btn-radio', function () {
	var idartigo=$(this).data('idart');
	var urlFoto=$(this).data('url');
bootbox.confirm("<h4>Deseja selecionar esta imagem?</h4>", function(result){
if (result) {
$.ajax({
	type: "POST",
	url: "<?php echo $prefs['erp_ws'];?>/artigos.php",
	data: {act_p:"addFotobyUrl",idart:""+idartigo+"",url:""+urlFoto+"",tipo:"mainfoto","auth_userid":"<?php echo $prefs['ws_token'];?>"},
	dataType: "json",
	success: function(data){	
	
	new PNotify({
	title: "Adicionar Foto",
	type: ""+data.type+"",
	text: ""+data.message+"",
	nonblock: { nonblock: true },
	styling: 'bootstrap3',
	hide: true
	}); 
					
	if(data.success==1){
		$('.fotoPrincipal').attr('src',urlFoto);	
		<?php if($act=="edit"){ ?>
		$('.gimages').show();
		$('#extra').html('');
		<?php	}  ?>
 
	}	
					
	},
	error: function(xhr, status, error) {
		console.log(error);
		new PNotify({
		title: "Ocorreu um Erro",
		type: "warning",
		text: ""+xhr.responseText+"",
		nonblock: { nonblock: true },
		styling: 'bootstrap3',
		hide: true
		}); 
	}
});	
} // endif
});	
});	
 	





function barcodigo(codigo){
	$('#barcInit').hide();
	$('.codigo').val(codigo); 
	JsBarcode("#barcode", ""+codigo+"", {
	  height: 40,
	  displayValue: true
	});
	
		$.ajax({
		  type: "GET",
		  dataType: "json",
		  url: "<?php echo $prefs['erp_ws'];?>/artigos.php",
		  data: {"act_g":"searchBarcode","auth_userid":"<?php echo $prefs['ws_token'];?>","code":codigo},
		  success: function(data){
			
			console.log(data);	
			if(data.success==0){
			new PNotify({
					title: ""+codigo+"",
					type: "error",
					text: "Artigo não encontrado",
					nonblock: { nonblock: true },
					addclass: 'light',
					styling: 'bootstrap3',
					hide: true						  
			}); 
			}
			if(data.success==1){

			var onlineProduto="";	
			if(data.bitPortalWeb==1){
			var onlineProduto="checked";	
			} 
			  
			
			var bottoes='<hr><div class="col-sm-offset-2 col-sm-10 col-xs-12"><a href="#" role="button" class="btn btn-info fileinput-button"><i class="fa fa-camera"></i> Foto<input class="fileupload" id="fileupload" type="file" name="files[]" data-form-data=\'{"num": "'+data.Id+'","tipo": "mainfoto"}\'></a> <a class="btn btn-danger removeFoto" data-idart="'+data.Id+'"><i class="fa fa-trash"></i> Elim. foto</a> <a class="btn btn-primary" href="<?php echo URLBASE;?>/erp-artigos/edit/'+data.Id+'"><i class="fa fa-pencil"></i> Ficha</a> <button type="submit" class="btn btn-primary" id="submitbtn"><i class="fa fa-save"></i> Editar</button> <a data-idart="'+data.Id+'" data-descricao="'+data.strDescricao+'" class="btn btn-primary gimages" href="#"><i class="fa fa-google"></i> Google</a>  <label><input data-idart="'+data.Id+'" type="checkbox" name="checkwebsite" class="checkwebsite js-switch" id="checkwebsite" value="1" '+onlineProduto+'> Online</label></div>';
			 
			$('#detalhes').html('<div class="card"><div class="container-fluid"><div class="wrapper row"><div class="preview col-xs-12 col-md-4"><div class="preview-pic tab-content"><div class="tab-pane active" id="pic-1"><?php echo $imgDef;?></div></div></div><div class="details col-xs-12 col-md-8"><h3 class="product-title">'+data.strDescricao+'</h3><div class="row"><div class="col-sm-6 col-xs-12"><p class="product-description">'+data.strDescricaoCompl+'</p><h4 class="priceArt">'+data.strCodigo+'</h4> <h4 class="sizes">Stock: <span>'+data.QuantStock+'</span></h4><span id="detalhesPreco"></span></div><div class="col-sm-6 col-xs-12"> <span id="detalhesFamilias"></span></td></div></div>    </div></div><div class="action row">'+bottoes+'</div></div></div>'); 
			if(data.imgImagem!=""){
			$('.fotoPrincipal').attr('src','data:image/jpg;base64,'+data.imgImagem+'');
			}
			var htmlPreco='<table id="tableCpreco" class="table table-striped table-bordered table-condensed display" cellspacing="0" width="80%"><tbody>';
			$.each(data.precos, function(i, item) {
    				htmlPreco+='<tr><td><h4 class="priceArt">Preço '+item.intNumero+':</h4></td><td align="center"><h4 class="priceArt"><span>'+item.fltPreco+' €</span></h4></td></tr>';
			});
			$('#detalhesPreco').html(htmlPreco+"</tbody></table>");
			
			var htmlFamilias='<table id="tableFamilias" class="display" cellspacing="0" width="100%"><tbody>';
			$.each(data.listaFam, function(i, item) { 
			 htmlFamilias+='<tr><td>'+item.valor+'</td></tr>';
			});   
			$('#detalhesFamilias').html(''+htmlFamilias+''+"</tbody></table>");
			
			$('#idnum').val(data.Id);	
			$('#strCodigo').val(data.strCodigo);	   
			
			/*
			$.each(data.listaFam, function(i, item) { 
			getSubFam(item.nivel,document.getElementById("fam_"+item.nivel).value);  
			alert(item.nivel);
			});   
			*/
			
			//getSubFam(1,document.getElementById("fam_1").value); alert(document.getElementById("fam_1").value);
			
			document.getElementById("extra").innerHTML="";
			var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
			elems.forEach(function(html) {
				  var switchery = new Switchery(html);
			});
			
			}
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
 
function getSubFam(nivel,codigo){
	if(codigo!="custom"){
	$.ajax({
		type: "GET",
		url: "<?php echo $prefs['erp_ws'];?>/familias.php",
		dataType: "json",
		data : {"act_g": "getSubFam","nivel": nivel,"codigo": codigo, "auth_userid": "<?php echo $prefs['ws_token'];?>"},
		success: function(data){
			$("#fam_"+(nivel+1)).empty();
			var optionsAsString = '<option value="">Escolha 1 opção</option>';
			if(data.total>0){
				$.each(data.lista, function(i, item) {
					optionsAsString += "<option value='" + item.strCodigo + "'>" + item.strDescricao + "</option>";
				})
				getSubFam(nivel+1,data.lista[0].strCodigo);	  		
			}
			$("#fam_"+(nivel+1)).append(optionsAsString);
			$("#fam_"+(nivel+1)).append('<option value="custom">=- Criar opção no ERP -=</option>')
		}
	});	
	}
}

$(document).on('change', '.nivelChange', function(event) {
	

  if($(this).val() == "custom") {
	 
	var legendaF=$(this).data('legenda');
	var idEelementoF=$(this).attr('id'); 
	var nivel=$(this).data('nivel');
	var topo=nivel-1;
	
    value = prompt("Introduza o nome da nova "+legendaF+":", "");
	if(value!=null){
	$.ajax({
	  type: "POST",
	  url: "<?php echo $prefs['erp_ws'];?>/familias.php",
	  dataType: "json",
	  data : {"act_p": "addFamilia", "auth_userid": "<?php echo $prefs['ws_token'];?>", "descricao": value, "nivel": nivel, "topo": $('#fam_'+topo).val()},
	  success: function(data){
		
		if(data.success==1){
			
		new PNotify({
				title: "Criar nova "+legendaF+"",
				type: "success",
				text: ""+data.msg+"",
				nonblock: { nonblock: true },
				addclass: 'light',
				styling: 'bootstrap3',
				hide: true						  
		}); 
		var option = document.createElement("option");
		option.text = ""+value+"";
		option.value = ""+data.codigo+"";
		var select = document.getElementById(idEelementoF);
		select.appendChild(option);
		$("#"+idEelementoF).val(data.codigo);

		$("#"+idEelementoF+" option:last").attr("selected", "selected");
					
		}
		
	  },
        error: function (request, status, error) {
		new PNotify({
				title: "Erro",
				type: "error",
				text: ""+request.responseText+"",
				nonblock: { nonblock: true },
				addclass: 'light',
				styling: 'bootstrap3',
				hide: true						  
				}); 
		}
	});
	} else  {
	
	

	}
  } else {
	  
	getSubFam($(this).data('nivel'),$(this).val());	  
  }
	
	
});	


$( "form" ).on( "submit", function( event ) {
  event.preventDefault();
  <?php if($act=="edit"){ ?>  
  $("#descr").val($("#editor-one").html());
  <?php }  ?> 
  $.ajax({
  type: "POST",
  url: "<?php echo $prefs['erp_ws'];?>/artigos.php",
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
		before_close: function(PNotify) { PNotify.queueRemove(); return false; }
	}); 
  },
 	error: function (request, status, error) {
	alert("Erro: "+request.responseText);
	console.log(error);
}
});
});








navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia;
            window.URL = window.URL || window.webkitURL || window.mozURL || window.msURL;
            
			
			function getUserMedia(constraints, success, failure) {
                navigator.getUserMedia(constraints, function(stream) {
                    var videoSrc = (window.URL && window.URL.createObjectURL(stream)) || stream;
                    success.apply(null, [videoSrc]);
                }, failure);
            }
			
			function onCapabilitiesReady(capabilities) {  
			  if (capabilities.torch) {
				track.applyConstraints({
				  advanced: [{torch: true}]
				})
				.catch(e => console.log(e));
			  }
			}
			
			
            function initCamera(constraints, video, callback) {
                getUserMedia(constraints, function (src) {
                    video.src = src;
                    video.addEventListener('loadeddata', function() {
                        var attempts = 10;
                        function checkVideo() {
                            if (attempts > 0) {
                                if (video.videoWidth > 0 && video.videoHeight > 0) {
                                    console.log(video.videoWidth + "px x " + video.videoHeight + "px");
                                    video.play();
                                    callback();
                                } else {
                                    window.setTimeout(checkVideo, 100);
                                }
                            } else {
                                callback('Unable to play video stream.');
                            }
                            attempts--;
                        }
                        checkVideo();
                    }, false);
                }, function(e) {
                    console.log(e);
                });
            }
            function copyToCanvas(video, ctx) {
                ( function frame() {
                    ctx.drawImage(video, 0, 0);
                    window.requestAnimationFrame(frame);
                }());
            }
            

	// Create the QuaggaJS config object for the live stream
	var liveStreamConfig = {
			inputStream: {
				type : "LiveStream",
				constraints: {
					width: {min: 640},
					height: {min: 480},
					aspectRatio: {min: 1, max: 100},
					facingMode: "environment" // or "user" for the front camera
				}
			},
			singleChannel: true,
			frequency: 20,
			multiple: false,
			locator: {
				patchSize: "medium",	// x-small, small, medium, large, x-large
				halfSample: false
			},
			numOfWorkers: (navigator.hardwareConcurrency ? navigator.hardwareConcurrency : 4),
			decoder: {
				"readers":[
					{
					"format":"code_128_reader","config":{}		
					}
				]
			},
			locate: true
		};
	// The fallback to the file API requires a different inputStream option. 
	// The rest is the same 
	var fileConfig = $.extend(
			{}, 
			liveStreamConfig,
			{
				inputStream: {
					size: 800
				}
			}
		);
	// Start the live stream scanner when the modal opens
	$('#livestream_scanner').on('shown.bs.modal', function (e) {
		Quagga.init(
			liveStreamConfig, 
			function(err) {
				if (err) {
					$('#livestream_scanner .modal-body .error').html('<div class="alert alert-danger"><strong><i class="fa fa-exclamation-triangle"></i> '+err.name+'</strong>: '+err.message+'</div>');
					Quagga.stop();
					return;
				}
				Quagga.start();
			}
		);
    });
	
	// Make sure, QuaggaJS draws frames an lines around possible 
	// barcodes on the live stream
	Quagga.onProcessed(function(result) {
		var drawingCtx = Quagga.canvas.ctx.overlay,
			drawingCanvas = Quagga.canvas.dom.overlay;
 
		if (result) {
			if (result.boxes) {
				drawingCtx.clearRect(0, 0, parseInt(drawingCanvas.getAttribute("width")), parseInt(drawingCanvas.getAttribute("height")));
				result.boxes.filter(function (box) {
					return box !== result.box;
				}).forEach(function (box) {
					Quagga.ImageDebug.drawPath(box, {x: 0, y: 1}, drawingCtx, {color: "green", lineWidth: 2});
				});
			}
 
			if (result.box) {
				Quagga.ImageDebug.drawPath(result.box, {x: 0, y: 1}, drawingCtx, {color: "#00F", lineWidth: 2});
			}
 
			if (result.codeResult && result.codeResult.code) {
				Quagga.ImageDebug.drawPath(result.line, {x: 'x', y: 'y'}, drawingCtx, {color: 'red', lineWidth: 3});
			}
		}
	});
	
	// Once a barcode had been read successfully, stop quagga and 
	// close the modal after a second to let the user notice where 
	// the barcode had actually been found.
	Quagga.onDetected(function(result) {    		
		if (result.codeResult.code){
			if(result.codeResult.code>5){
				barcodigo(result.codeResult.code)
				Quagga.stop();	
				setTimeout(function(){ $('#livestream_scanner').modal('hide'); }, 1000);	
			}
		}
	});
    
	// Stop quagga in any case, when the modal is closed
    $('#livestream_scanner').on('hide.bs.modal', function(){
    	if (Quagga){
    		Quagga.stop();	
    	}
    });
	
	// Call Quagga.decodeSingle() for every file selected in the 
	// file input
	$("#livestream_scanner input:file").on("change", function(e) {
		if (e.target.files && e.target.files.length) {
			Quagga.decodeSingle($.extend({}, fileConfig, {src: URL.createObjectURL(e.target.files[0])}), function(result) {
				alert(result.codeResult.code);
				
				});
		}
	});


</script>

