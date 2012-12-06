<?php
/**
 * Modulo que maneja el registro de los alumnos desde la pagina principal
 * @date 21 AGO 2007
 * @author Cesar Sanchez
 */

include_once('./classes/catalogos.php');
include_once('./classes/usuario.php');
include_once('./classes/sesion.php');
$usuario = new usuario();
$catalogos = new catalogos();						


if($_POST){//
	include_once('./lib/encryption.php');
	$enc= new Crypto();
	$_SESSION['rec'] = trim($enc->decrypt($_SESSION['rec']));

	if(!is_numeric($_SESSION['rec']))$_SESSION['rec']=-1;
	$usrdata = $usuario->llenaArregloDesdePostSencillo();
	$usuario->altaUsuarioSencillo($usrdata);
	$usr = $usuario->obtenerUsuario($usrdata['matricula']);
	
	$conn = new connect();
	
	$conn->connect();
	$rs = $conn->queryArrayUnico("select recursosmoviles.recurso.* from recursosmoviles.recurso where recursosmoviles.recurso.recurso_id=".$_SESSION['rec']);
	
	
	$_SESSION['cuestionario_titulo'] =$rs['recurso_titulo'] ;
	
	$_SESSION['usuario_id'] =  $usr['usuario_id'];
	
	$password = 'apfn2311';
	
	$sesion = new sesion();
	
	$ses = $sesion->obtenerGrupos($password);
	


	if($ses==null || $_SESSION['rec']==-1){
		$data['template.body']='./templates/sac/forma/registroAM.tp';
		$mensajes['mensaje.error']='El cuestionario no esta disponible.';
		$data = array_merge($data,$usuario->inicializaForma());	
		$mensajes['menu.location']='Registro';
	}else{
		$data = array('template.body'=>'./templates/sac/content.tp');
		$sesion_id=$ses['sesion_id'];
		$_SESSION['sesion_id'] = $sesion_id;
		$cuestionario_id=$ses['cuestionario_id'];
		$_SESSION['loged'] = true;
		include_once('./modulos/examen/preparaExamen.php');
		$data['cuestionario.descripcion']="";
		
		$_SESSION['CUESTIONARIO_EXPRESS'] = true; 
	}
	
	//if($_POST['password'] !=$_POST['password2'])$mensajes['mensaje.error']='ERROR. La confirmaci&oacute;n de la contrase&ntilde;a no coincide. ';			
	/*	else if($usuarioAnt!=null)$mensajes['mensaje.error']='ERROR. Usuario ya existe en el sistema.';			
		else if(!isset($_POST['sexo']))$mensajes['mensaje.error']='ERROR. Debes de seleccionar el campo sexo ';			
		else $mensajes['mensaje.error']='ERROR. Fecha de nacimiento invalida ';*/			
	
}else{
	$mensajes['menu.location']='Registro';
	$data['template.body']='./templates/sac/forma/registroContingencia.tp';											
	$data = array_merge($data,$usuario->inicializaForma());	
	
	//$_SESSION['rec'] = $_GET['rec'];
}
		
?>