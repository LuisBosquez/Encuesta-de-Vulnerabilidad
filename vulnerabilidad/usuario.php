<?php
/* +----------------------------------------------------------------------+
// | SAC Ver 2.0.1 TEC                                                    |
// +----------------------------------------------------------------------+
// | Copyright © 2004-2006. Cesar Sanchez						          |
// +----------------------------------------------------------------------+
// |  CONTROLADOR PRINCIPAL DE LA APLICACION
// +----------------------------------------------------------------------+
// | Authors: Cesar Sanchez <oropeza@gmail.com>                           |
// +----------------------------------------------------------------------+
//
*/
	require_once('./lib/load.php');
	$login->isLoged();	
    $tp=&new templateParser('');
	$data = array('template.body'=>'./templates/sac/content.tp');
	
	if($_GET && isset($_GET['examen']))include_once('./modulos/examen/preparaExamen.php');
	else if($_GET && isset($_GET['logout']))$login->logout();
	else if($_GET && isset($_GET['usuario']))include("modulos/usuarioEdit.php");
	else if($_SESSION['usuario_nivel']==USUARIO_ADMINISTRADOR)include_once('./modulos/usuarioAdministrador.php'); //ADMIN1ISTRADOR
	else if($_SESSION['usuario_nivel']==USUARIO_APLICADOR)include_once('./modulos/usuarioAplicador.php'); //MAESTTRO
	else if($_SESSION['usuario_nivel']==USUARIO_APLICADOR_EXTA)include_once('./modulos/usuarioAplicador.php'); //MAESTTRO
	else if($_SESSION['usuario_nivel']==USUARIO_ALUMNOS)include_once('./modulos/usuarioAlumno.php'); // ALUMNO
	else if($_SESSION['usuario_nivel']==USUARIO_DIRECTOR)include_once('./modulos/usuarioDirectorCarrera.php'); // ALUMNO	
	else content::armaMensaje($data,"Error","Comando no existe");
	

	if($_SESSION['usuario_nivel']==USUARIO_ADMINISTRADOR)$mensajes['template.menuoptions']='.: <a href="usuario.php">Inicio</a> :..: <a href="usuario.php?usuarios">Usuarios</a> :..: <a href="usuario.php?reportes">Reportes</a> :..: <a href="usuario.php?usuario">Mi Usuario</a> :..: <a href="usuario.php?logout">Logout</a> :.:.';
	else if($_SESSION['usuario_nivel']==USUARIO_APLICADOR)$mensajes['template.menuoptions']='.: <a href="usuario.php">Inicio</a> :..: <a href="usuario.php?sesion">Sesiones</a>  :..: <a href="usuario.php?cuestionario">Cuestionarios</a> :..: <a href="usuario.php?grupos">Grupos</a> :..: <a href="usuario.php?usuario">Mi Usuario</a> :..: <a href="usuario.php?logout">Logout</a> :.:.';	
	else if($_SESSION['usuario_nivel']==USUARIO_DIRECTOR)$mensajes['template.menuoptions']='.: <a href="usuario.php">Inicio</a> :..: <a href="usuario.php?cuestionarios">Cuestionarios</a> :..: <a href="usuario.php?reportes">Reportes</a> :..: <a href="usuario.php?usuario">Mi Usuario</a> :..: <a href="usuario.php?logout">Logout</a> :.:.';
 	else if($_SESSION['usuario_nivel']==USUARIO_ALUMNOS) $mensajes['template.menuoptions']='.: <a href="usuario.php">Cuestionarios</a> .::. <a href="usuario.php?usuario">Usuario</a> .::. <a href="usuario.php?logout">Logout</a> :.';
	else if($_SESSION['usuario_nivel']==USUARIO_APLICADOR_EXTA)$mensajes['template.menuoptions']='.: <a href="usuario.php">Inicio</a> :..: <a href="usuario.php?sesion">Sesiones</a>  :..: <a href="usuario.php?cuestionario">Cuestionarios</a> :..: <a href="usuario.php?grupos">Grupos</a> :..: <a href="usuario.php?reportes">Reportes</a> :..:  <a href="usuario.php?usuario">Mi Usuario</a> :..: <a href="usuario.php?logout">Logout</a> :.:.';	
 	
	

	$tags2 = array_merge(
		$data,
		settings::paginaSettings(),application::appSettings(),$mensajes
	);
    $tp->parseTemplate($tags2);
    echo $tp->display();

?>