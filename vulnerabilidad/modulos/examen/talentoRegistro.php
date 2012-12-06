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
	

	$usrdata = $usuario->llenaArregloDesdePostSencillo();
	$usuario->altaUsuarioSencillo($usrdata);
	$usr = $usuario->obtenerUsuario($usrdata['matricula']);
	

	$_SESSION['usuario_id'] =  $usr['usuario_id'];
	
	$password = $_POST['password'];
	
	$sesion = new sesion();
	
	$ses = $sesion->obtenerGrupos($password);
	


	if($ses==null){
		$data['template.body']='./templates/sac/forma/registroTalento.tp';
		$mensajes['mensaje.error']='Constraseña incorrecta. La sesión puede no existir, estar inactiva o estar fuera de tiempo.';
		
	}else{
		$data = array('template.body'=>'./templates/sac/content.tp');
		$sesion_id=$ses['sesion_id'];
		$_SESSION['sesion_id'] = $sesion_id;
		$cuestionario_id=$ses['cuestionario_id'];
		$_SESSION['loged'] = true;
		include_once('./modulos/examen/preparaExamen.php');
	}
	
	//if($_POST['password'] !=$_POST['password2'])$mensajes['mensaje.error']='ERROR. La confirmaci&oacute;n de la contrase&ntilde;a no coincide. ';			
	/*	else if($usuarioAnt!=null)$mensajes['mensaje.error']='ERROR. Usuario ya existe en el sistema.';			
		else if(!isset($_POST['sexo']))$mensajes['mensaje.error']='ERROR. Debes de seleccionar el campo sexo ';			
		else $mensajes['mensaje.error']='ERROR. Fecha de nacimiento invalida ';*/			
	
}else{
	$mensajes['menu.location']='Registro';
	$data['template.body']='./templates/sac/forma/registroTalento.tp';
	$data['forma.day'] = content::getComboOpciones('dateDay',catalogos::obtenerDias(),"small");	
	$data['forma.month'] = content::getComboOpciones('dateMonth',catalogos::obtenerMeses(),"small");	
	$data['forma.year'] = content::getComboOpciones('dateYear',catalogos::obtenerYear(),"small");							
	$data['forma.carrera'] = content::getComboOpciones('carrera',$catalogos->obtenerCarreras());												
	$data = array_merge($data,$usuario->inicializaForma());	
}
		
?>