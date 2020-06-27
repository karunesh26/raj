jQuery(document).ready(function($){
	
	/* Allow only alphabets */
	
/*	$(document.body).bind('paste',function(event){
		event.preventDefault();
	});*/
	
	$(document.body).on('keypress',".numberonly",function(event){
		if ((event.which < 48 || event.which > 57)) 
		{
			event.preventDefault();
		}
	});
	
	$(document.body).on('keypress',".amountonly",function(event){
		if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) 
		{
			event.preventDefault();
		}
	});
	

	/* Allow only alphabets and digits and dot*/
	$(document.body).on('keypress',".alphanumeric",function(event){
	
		var keyCode = (event.which) ? event.which : event.keyCode;

		// Allow: backspace, delete
		if(event.keyCode == 8 || event.keyCode == 46 || keyCode == 9 || keyCode == 27 || keyCode == 13 || 
			//Capital and small letters
			(keyCode >= 65 && keyCode <= 90 )|| (keyCode >= 97 && keyCode <= 122)||
			//Digits Only
			(keyCode >= 48 && keyCode <= 57 ))
			return; 
	    else{ 
	    	//prevent for Special characters
	    	 event.preventDefault(); }
	});
	
	/* Allow only alphabets */
	$(document.body).on('keypress',".textonly",function(event){
	
		var keyCode = (event.which) ? event.which : event.keyCode;

		// Allow: backspace, delete, tab, escape, and enter
		if ( keyCode == 8 || event.key == 'Del' || keyCode == 9 || keyCode == 27 || keyCode == 13 || keyCode == 32 ||
				// Allow: Ctrl+A
				(keyCode == 65 && event.ctrlKey === true) || 
				//Allow: home, end, left, right
				(keyCode >= 35 && keyCode <= 39)) {
					// let it happen, don't do anything
				return;
		} else if(keyCode < 65 /* A to Z and a */ || keyCode > 122 /* z */) {
			event.preventDefault();
	    }
	});
	
	/* Allow only BACKSPACE AND DELETE */
	$(document.body).on('keypress',".datefield",function(event){
	
		var keyCode = (event.which) ? event.which : event.keyCode;

		// Allow: backspace, delete
		if(event.keyCode == 8 || event.keyCode == 46) { return; }
	    else{ event.preventDefault(); }
	});
	
	
	
	/* Allow only alphabets, Special characters and whitespaces */
	$(document.body).on('keypress',".alphaspecial",function(event){
	
		var keyCode = (event.which) ? event.which : event.keyCode;

		// Allow: backspace, delete
		if(event.keyCode == 8 || event.keyCode == 46 ||
			//Capital and small letters
			(keyCode >= 65 && keyCode <= 90 )|| (keyCode >= 97 && keyCode <= 122)||
			//special characters
			(keyCode >= 32 && keyCode <= 47 ))
			return; 
	    else{
	    	//prevent for digits
	    	if( keyCode >= 48 && keyCode <= 57  ){ event.preventDefault(); }}
	});
	

	
	/* Allow only alphabets and digits, underscore, dash*/
	$(document.body).on('keypress',".username",function(event){
	
		var keyCode = (event.which) ? event.which : event.keyCode;
		
		// Allow: backspace, delete, underscore, dash and dot
		if(keyCode == 8 || event.key == 'Del' || keyCode == 9 || keyCode == 45 || keyCode == 95 || keyCode == 46 ||
			//Capital and small letters
			(keyCode >= 65 && keyCode <= 90 )|| (keyCode >= 97 && keyCode <= 122)||
			//Digits Only
			(keyCode >= 48 && keyCode <= 57 ) ||
			//Allow: home, end, left, right
			(keyCode >= 35 && keyCode <= 39))
			return; 
		else { 
	    	//prevent for Special characters
	    	 event.preventDefault(); }
	});
});

