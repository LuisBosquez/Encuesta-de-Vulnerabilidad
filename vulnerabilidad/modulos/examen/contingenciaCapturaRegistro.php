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


if(true){//
	include_once('./lib/encryption.php');
	$enc= new Crypto();
			$usrdata['matricula'] = '10000001'; 
			$usrdata['correo'] = 'correo'; 
			$usrdata['nombre'] = 'nombre'; 
			$usrdata['apellido'] = 'apellido'; 
	$usuario->altaUsuarioSencillo($usrdata);
	$usr = $usuario->obtenerUsuario($usrdata['matricula']);
	
	$conn = new connect();
	
	$conn->connect();

	
	
	$_SESSION['cuestionario_titulo'] ='Encuesta' ;
	
	$_SESSION['usuario_id'] =  '1';
	
	$password = 'fwfe13gt';
	
	$sesion = new sesion();
	
	$ses = $sesion->obtenerGrupos($password);
	

		$data = array('template.body'=>'./templates/sac/content.tp');
		$sesion_id=$ses['sesion_id'];
		$_SESSION['sesion_id'] = $sesion_id;
		$cuestionario_id=$ses['cuestionario_id'];
		$_SESSION['loged'] = true;
		include_once('./modulos/examen/preparaExamen.php');
		$data['cuestionario.descripcion']="Encuesta:  Actividades durante la contingencia</b>";
		
		$_SESSION['CUESTIONARIO_EXPRESS'] = true; 

	
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