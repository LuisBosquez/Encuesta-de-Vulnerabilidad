<?php

	unset($_SESSION['cuestionario']);
	unset($_SESSION['tiempo_ini']);
	unset($_SESSION['horaInicio']);
	unset($_SESSION['tiempo_max']);
	unset($_SESSION['tiempo_fin']);			
	unset($_SESSION['horaInicio']);
	unset($_SESSION['respuestasAlm']);
	unset($_SESSION['id_cuestionario']);
	unset($_SESSION['id_grupo']);		
	
	
	$mensajes['menu.location']='Preparando Examen';
	
	require_once('./classes/cuestionario.php');
	require_once('./classes/cuestionarioXML.php');
	require_once('./lib/encryption.php');
	
	$cuestionario = new cuestionario();
	$cuexml = new cuestionarioXML();	
	$enc = new Crypto();
	$cto=null;
	
	
	if(isset($_GET['idGp']) && isset($_GET['idCu'])){
		$idGpo = $_GET['idGp'];
		$idCto = $_GET['idCu'];
	
		$idGpo = trim($enc->decrypt($idGpo));
		$idCto = trim($enc->decrypt($idCto));
		
	}else{
		$idCto = $cuestionario_id;
	}
		

	if(isset($idGpo) && is_numeric($idGpo) && is_numeric($idCto))$cto = $cuestionario->obtenerDatosCuestionario($idGpo,$idCto);			
	else if(is_numeric($sesion_id))$cto = $cuestionario->obtenerDatosCuestionarioSesion($sesion_id,$cuestionario_id);	

	if(true || $cto)
	{

		$cuexml->abrir('./archivo/'.$cto['cuestionario_fuente']);
		$_SESSION['cuestionario'] = $cuexml;
		
		
				//TODO Validar el tiempo correcto de inicio
		

		$_SESSION['tiempo_ini'] = $cto['ts_inicio'];
		$_SESSION['tiempo_fin'] = $cto['ts_fin'];
		$_SESSION['tiempo_max'] = $cto['tiempo_max'];
		if(isset($idGpo))$_SESSION['id_grupo'] = $idGpo ;
		else $idGpo = 1;
		
		$_SESSION['cuestionario_id'] = $idCto;	
		$_SESSION['contestando'] = true;		
			
		$tiempo_act = time();
		$tiempo =  $cto['tiempo'];
	
		unset($_SESSION['horaInicio']);
		unset($_SESSION['paginaAct']);
		unset($_SESSION['respuestasAlm']);	//TODO CHECAR
			
		//todo eliminar 
		if($_SESSION['cuestionario_id']==6){
			$data['template.contenido']='./templates/sac/examen/preparaTemp.tp';
		}else
		//todo eliminar end
		
		$data['template.contenido']='./templates/sac/examen/prepara.tp';
		$data['cuestionario.nombre']=utf8_encode($cto['cuestionario_nombre']);
		$data['cuestionario.instrucciones']=$cto['cuestionario_textoinicio'];
		$data['cuestionario.descripcion']=utf8_encode($cto['cuestionario_descripcion']);
		$data['grupo.nombre']=$cto['nombre'];
		$data['cuestionario.pregunta']=$cuexml->obtenerCantidadPreguntas();
		$data['cuestionario.tiempo']=$cto['tiempo'];
		$data['cuestionario.horaActual']=date('d-m-Y h:i:s A');
		$data['periodo.nombre'] = $cto['periodo_nombre'];
			
	}
	else content::armaMensaje($data,"Error","Examen invalido. Contacte al administrador. ");
	

?>