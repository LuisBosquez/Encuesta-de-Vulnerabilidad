<?php
/* +----------------------------------------------------------------------+
// | SAC Ver 2.1 TEC                                                    |
// +----------------------------------------------------------------------+
// |  2009. Cesar Sanchez						          |
// +----------------------------------------------------------------------+
// |  CONTROLADOR PARA LA EJECUCION DE UN EXAMEN
// +----------------------------------------------------------------------+
// | Authors: Cesar Sanchez <oropeza@gmail.com>                           |
// +----------------------------------------------------------------------+
//
*/
	require_once('./classes/cuestionario.php');
	require_once('./classes/cuestionarioXML.php'); 	
	require_once('./classes/preguntaHTML.php'); 	
	require_once('./lib/load.php');	
	$login->isLoged();
	
	$mensajes = array();


    $tp=&new templateParser('');

	$data = array('template.body'=>'./templates/sac/content.tp');
	
	
	if($_POST && isset($_POST['Submit']) && $_POST['Submit']=='TERMINAR')
		include_once('./modulos/examen/califica.php');	
	else if($_GET && isset($_GET['contestar']) && isset($_SESSION['cuestionario']))
	{
			
		include_once('./modulos/examen/examen.php');
	}
	else if($_GET && isset($_GET['resultado']))			
		include_once('./modulos/examen/resultado.php');
	else 
	{
				include_once('./modulos/usuarioAlumno.php');	
	}

	$tags2 = array_merge(
		$data,
		settings::paginaSettings(),application::appSettings(),$mensajes);
    $tp->parseTemplate($tags2);
    echo $tp->display();
?>