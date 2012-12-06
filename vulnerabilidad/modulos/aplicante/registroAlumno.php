<?php
/**
 * Modulo que maneja el registro de los alumnos desde la pagina principal
 * @date 21 AGO 2007
 * @author Cesar Sanchez
 */

include_once('./classes/catalogos.php');
include_once('./classes/usuario.php');
$usuario = new usuario();
$catalogos = new catalogos();						


if($_POST){//
	$_POST['password'] = get_magic_quotes_gpc() ? $_POST['password'] : addslashes($_POST['password']);
	$_POST['password2'] = get_magic_quotes_gpc() ? $_POST['password2'] : addslashes($_POST['password2']);

	$usuarioAnt = $usuario->obtenerUsuario($_POST['matricula']);

	if(isset($_POST['sexo'])&& $usuarioAnt==null && $_POST['password'] ==$_POST['password2'] && checkdate ( $_POST["dateMonth"],$_POST["dateDay"],$_POST["dateYear"] )){
		$usrdata = $usuario->llenaArregloDesdePost();
		$usuario->altaUsuario($usrdata);
		$mensajes['login.user']='';						
		$mensajes['menu.location']='Inicio';
		$mensajes['mensaje.error']='Usuario registrado exitosamente. Ahora puede ingresar usando su matricula y contraseña. ';						
	}else{
		$mensajes['menu.location']='Registro';
		$data['template.body']='./templates/sac/forma/registro.tp';	
		$data['forma.day'] = content::getComboOpciones('dateDay',catalogos::obtenerDias());	
		$data['forma.month'] = content::getComboOpciones('dateMonth',catalogos::obtenerMeses());	
		$data['forma.year'] = content::getComboOpciones('dateYear',catalogos::obtenerYear());							
		$data['forma.carrera'] = content::getComboOpciones('carrera',$catalogos->obtenerCarreras());												
		$data = array_merge($data,$usuario->llenaFormaDesdePost());		

		if($_POST['password'] !=$_POST['password2'])$mensajes['mensaje.error']='ERROR. La confirmaci&oacute;n de la contrase&ntilde;a no coincide. ';			
		else if($usuarioAnt!=null)$mensajes['mensaje.error']='ERROR. Usuario ya existe en el sistema.';			
		else if(!isset($_POST['sexo']))$mensajes['mensaje.error']='ERROR. Debes de seleccionar el campo sexo ';			
		else $mensajes['mensaje.error']='ERROR. Fecha de nacimiento invalida ';			
	}
}else{
	$mensajes['menu.location']='Registro';
	$data['template.body']='./templates/sac/forma/registro.tp';
	$data['forma.day'] = content::getComboOpciones('dateDay',catalogos::obtenerDias(),"small");	
	$data['forma.month'] = content::getComboOpciones('dateMonth',catalogos::obtenerMeses(),"small");	
	$data['forma.year'] = content::getComboOpciones('dateYear',catalogos::obtenerYear(),"small");							
	$data['forma.carrera'] = content::getComboOpciones('carrera',$catalogos->obtenerCarreras());												
	$data = array_merge($data,$usuario->inicializaForma());	
}
		
?>