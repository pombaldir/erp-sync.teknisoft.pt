/*
 * Core script to handle all login specific things
 */



var Login = function() {

	"use strict";

	
	
	
	var initSubmt = function() {
		
		$('#loginform').submit(function() {
					
 		$.ajax({
           type: "POST",
           url: "login_do.php",
           data: $("#loginform").serialize(),
		   beforeSend: function(){
		   setTimeout(function(){$('#msglogin')},4500);
    	   $("#msglogin").html('<div class="progress progress-striped active"><div class="progress-bar progress-bar-info" style="width: 1%"></div></div>');
   		   },
           success: function(data, textStatus, jqXHR){
               console.log(jqXHR.responseText);  
			   var dados = eval("(" + jqXHR.responseText + ")");
			   
			   function newpage() {
				window.location = dados.redir;   
			   }
			   
               if(dados.mensagem=="yes"){ 
			  	 $(".progress-bar").css("width","100%");
			   	setTimeout(function(){$('#loginform').fadeOut(1000, newpage)},4500);
			   }
			   if(dados.mensagem=="no"){ 
			   $("#msglogin").html('<div class="alert alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">X</span></button><strong>Erro de Login!</strong> '+dados.htmlmsg+'</div>');
			   }  
			   
           },
            error: function(xhr, status, error) {
                alert("" + status + "\nErro: " + xhr.responseText); 
            }
         });
  		return false;
	});
	}
	
	

	return {
		// main function to initiate all plugins
		init: function () {
			initSubmt();
		},

	};
	

}();




