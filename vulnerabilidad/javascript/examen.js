	/**
	* Valida que una sere de ComboBoxes sean validos
	*/
	function validaReqCB(grupo,nombre){
		var buttons = $('forma').getInputs('radio', grupo);		
		if(buttons[0].disabled==true) return true;  		
   		for (var i=buttons.length-1; i > -1; i--) if (buttons[i].checked) {return true;}   		
   		alert("La pregunta "+nombre+" requiere que este contestada con por lo menos una opción");
 		$(grupo).focus();
		return false;
	}
	/**
	* Valida que una sere de ComboBoxes con opción de texto sea valido sean validos
	*/
	function validaReqCBEspecifica(grupo,nombre){
		var buttons = $('forma').getInputs('radio', grupo);		
		if(buttons[0].disabled==true) return true;  		
   		for (var i=buttons.length-1; i > -1; i--) if (buttons[i].checked) {
   			
   			if(buttons[i].value=='on'){   			
   				if(($(grupo+'-TEXT').value)==''){
   					alert("La pregunta "+nombre+" requiere que se especifique");
   					return false;   				
   				}
   			}
   			return true;
   		}   		
   		alert("La pregunta "+nombre+" requiere que este contestada con por lo menos una opción");
 		$(grupo).focus();
		return false;
	}	
	/**
	* Valida que una sere de ComboBoxes sean validos
	*/
	function validaReqCheckBox(grupo,nombre){
		
		var buttons = $('forma').getInputs('checkbox', grupo);
		if(buttons[0].disabled==true) return true;  		
   		for (var i=buttons.length-1; i > -1; i--) if (buttons[i].checked) {return true;}   		
   		alert("La pregunta "+nombre+" requiere que este contestada con por lo menos una opción");
 		$(grupo).focus();
		return false;
	}	