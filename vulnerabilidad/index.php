<?php
/*  +----------------------------------------------------------------------+
 // | SAC Ver 2.1 TEC                                                      |
 // +----------------------------------------------------------------------+
 // | 2009. Cesar Sanchez						                           |
 // +----------------------------------------------------------------------+
 // |  CONTROLADOR DEL INICIO, REGISTRO DE USUARIOS                        |
 // +----------------------------------------------------------------------+
 // | Authors: Cesar Sanchez <oropeza@gmail.com>                           |
 // +----------------------------------------------------------------------+
 */
require_once('./lib/load.php');
$tp=&new templateParser('');
$data = array('template.body'=>'./templates/sac/inicio.tp');


if($_GET && isset($_GET['SICAM_recurso'])){
	include_once('./examen/registro_sicamrecurso.php');
}
else if($_GET && isset($_GET['vulnerabilidad'])){
	include_once('./modulos/examen/vulnerabilidadRegistro.php');
}
if($_GET && isset($_GET['vulnerabilidadTest'])){
	include_once('./modulos/examen/vulnerabilidadTest.php');
}
else if($_GET && isset($_GET['about'])){
	$mensajes['menu.location']='Acerca De';
	$data['template.body']='./templates/sac/about.tp';
}
else if($_GET && isset($_GET['registro'])) include_once('./modulos/aplicante/registroAlumno.php');
else if($_GET && isset($_GET['login'])) {
	require_once('./classes/usuario.php');
	$login = new login();

	if(isset($_POST['login'])&&$login->logon($_POST['login'],$_POST['password'])){
		header("Location: usuario.php ");

		$mensajes['mensaje.error']='Error al cargar permisos. ';
		$mensajes['login.user']='';
		$mensajes['menu.location']='Inicio';
	}else{
		$mensajes['menu.location']='Inicio';
		$mensajes['login.user']='';
		$mensajes['mensaje.error']='Usuario no existe o contraseña invalida. ';
	}

}else{
	$mensajes['login.user']='';
	$mensajes['menu.location']='Inicio';
}

$mensajes['template.menuoptions']='';
if(VERSION_DEMO)$mensajes['mensaje.demo']='VERSION DE DEMOSTRACION';
else $mensajes['mensaje.demo']='';
$tags2 = array_merge($data,settings::paginaSettings(),application::appSettings(),$mensajes);
$tp->parseTemplate($tags2);
echo $tp->display();

?>