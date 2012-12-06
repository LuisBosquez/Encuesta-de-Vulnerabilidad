// JavaScript Document


function disableOtros(id)
{

	document.getElementById(id).disabled = true;
	document.getElementById(id).value = '';		
}




// JavaScript Document

function toggleBox(szDivID, iState) // 1 visible, 0 hidden
{
   var obj = document.layers ? document.layers[szDivID] :
   document.getElementById ?  document.getElementById(szDivID).style : document.all[szDivID].style;
   //obj.display= document.layers ? (iState ? "show" : "none") :  (iState ? "inline" : "none");
   obj.display  =  (iState ? "inline" : "none");
}


function clasificacion(cantidad,MAX){
		
	for(i = 1; i <= cantidad ; i++){
		toggleBox('G'+i,1);		
		document.getElementById('c_nombre_'+i).disabled = false;
		document.getElementById('c_color_'+i).disabled = false;		
		document.getElementById('c_desc_'+i).disabled = false;		
	
	}
	for(i = i; i <= MAX ; i++){
		toggleBox('G'+i,0);
     	document.getElementById('c_nombre_'+i).disabled = true;				
		document.getElementById('c_color_'+i).disabled = true;
		document.getElementById('c_desc_'+i).disabled = true;				
	}



}


function ocultarDim()
{

	document.getElementById("ndim").value = 1;
	document.submitDimForm.dimension1[0].selected=true;
	toggleBox("dimP", 1);
	toggleBox("dimSegunda", 0);
	document.submitDimForm.submit();

	

}


function enableOtros(id)
{

	document.getElementById(id).disabled = false;		
	document.getElementById(id).focus();
	document.getElementById(id).select();	
	
}


function confirmSubmit(id)
{

	var texto = "";
	if(id==1) texto = "Esta acción data de baja al alumno del grupo. De click en aceptar para confirmar. ";

	var agree=confirm(texto);
	
	if (agree) 
			return true ;
	else
		return false;
	
}


function confirmSubmit2()
{
var agree=confirm("Esta acci?n borrara los resultados del cuestionario. Seleccione Aceptar para proceder o Cancelar para no alterar los resultados.");
if (agree) 
	return true ;
else
	return false ;
}

function confirmSubmitDatabase()
{
var agree=confirm("Esta opci?n almacenara los registros en el historico. Estos cuestionarios asinados no podran volver a aplicarse.");
if (agree) 
	return true ;
else
	return false ;
}


function confirmSubmitExamen()
{
var agree=confirm("Presione ACEPTAR para enviar el cuestionario. CANCELAR para continuar contestando las preguntas.");
if (agree)
	return true ;
else
	return false ;
}
