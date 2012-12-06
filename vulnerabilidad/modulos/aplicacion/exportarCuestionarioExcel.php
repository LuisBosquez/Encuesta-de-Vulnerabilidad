<?php

	require_once("./lib/Spreadsheet/Excel/Writer.php");
	require_once("./classes/cuestionario.php");
	require_once("./classes/grupo.php");
	require_once("./classes/usuario.php");
	require_once("./classes/cuestionarioXML.php");
	require_once("./classes/resultados.php");
	
	error_reporting(E_ALL ^ E_NOTICE);
	
	$idGrupo = -1;
	$idSesion = -1;
	
	if(isset($_GET['idGrupo']))$idGrupo = $_GET['idGrupo'];
	if(isset($_GET['idSesion']))$idSesion = $_GET['idSesion'];
	
	$idCuestionario = $_GET['idCuest'];
	

	$cuestionario = new cuestionario();
	$cuexml = new cuestionarioXML();
	$resultados = new resultados();
	$usuarios = new usuario();
	$grupo = new grupo();

	
	$cto = null;
	$gpo = null;
	if($idGrupo!=-1){
		
		$cto = $cuestionario->obtenerDatosCuestionario($idGrupo,$idCuestionario);
		$gpo = $grupo->obtenerGrupo($cto['grupo_id']);
	}
	else if($idSesion!=-1){
		
		$cto = $cuestionario->obtenerDatosCuestionarioSesion($idSesion,$idCuestionario);
	}
	if($cto==null)die("No existen datos de cuestionario aplicado.");
	 
	$cuexml->abrir('./archivo/'.$cto['cuestionario_fuente']);
	

	if(false && $cto['cuestionario_id']==1){
		
		include_once('./modulos/aplicacion/exportarExelSAC.php');
	}
	//else if($cto['cuestionario_id']==2 && isset($_GET['exportarXLSAnalisis'])){
	else if(isset($_GET['exportarXLSAnalisis'])){
		
		include_once('./modulos/aplicacion/exportarExelVulAnalisis.php');	
	}
	//else if($cto['cuestionario_id']==2){
	else if(true){
		
		include_once('./modulos/aplicacion/exportarExelVul.php');	
	}
	
	exit();
	content::armaMensaje($data,'Error','Esta opcion permite almacenar el exportarXLS');	

?>