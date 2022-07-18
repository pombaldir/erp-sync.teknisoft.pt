jQuery(document).on('scroll', function() {
  if (jQuery(document).scrollTop() >= 10) {
    jQuery('.logo img').css('height', '50px').css('padding', '2px').css('margin', '2px');
  } else {
    jQuery('.logo img').css('height', 'auto');
  }
});


jQuery( ".single_variation_wrap" ).on( "show_variation", function ( event, variation ) {
    // Fired when the user selects all the required dropdowns / attributes
    // and a final variation is selected / shown
    // 
 
    var sku=jQuery( ".sku" ).attr('data-o_content');
    
	console.log(variation);
	
	var varsku=variation.sku;
	var idsku=variation.variation_id;
	
	//console.log('changed '+sku+' ('+varsku+') '+idsku);
	
	jQuery.ajax({
	type: 'GET',
	dataType: "json",
	url: 'https://apps.teknisoft.pt/lojasmarias/artigos.php?auth_userid=SmA13z?xU(hxG4e&act_g=sku',
	data: {"num":""+varsku+""},
	success: function(data, textStatus, xhr) {
	
	if(data.found==1){
		
	var data = {
    "product":  {
      stock_quantity: data.stock
    }
	};

	jQuery.ajax({
	type: 'PUT',
	dataType: "json",
	url: 'https://www.lojasmarias.com/wc-api/v2/products/'+idsku+'?consumer_key=ck_571aa5efb18b0fe5db8b61449139052bf7515099&consumer_secret=cs_156c3fd28ab49bc99d02c28ce64f3278cefc8108',
	data: JSON.stringify(data),
	success: function(data, textStatus, xhr) {
			console.log(data);
	},
	error: function (request, status, error) {					
		console.log(request.responseText);
	}
	});
		
	
	}
		
	}
		
	});	

});