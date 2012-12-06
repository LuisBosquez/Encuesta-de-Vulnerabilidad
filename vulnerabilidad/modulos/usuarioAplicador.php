<?php

	//TODO:Manejar seguridad de $_SESSION['usuario_id']
		if($_SESSION['usuario_nivel']==USUARIO_APLICADOR_EXTA && $_GET && isset($_GET['reportes'])){			

			include_once('./modulos/reportes/reporteCHEGrupos.php');			
		}
		else if($_GET && isset($_GET['grupos']))
			include_once('./modulos/aplicacion/grupoModulo.php');			
		else if($_GET && isset($_GET['cuestionario']))
			include_once('./modulos/aplicacion/cuestionarioModulo.php');
		else if($_GET && isset($_GET['sesion']))
			include_once('./modulos/aplicacion/sesionModulo.php');
			
		else{
			$mensajes['menu.location']='Usuario: '.$_SESSION['usuario_nombre'];
			$data['template.contenido']='./templates/sac/aplicacion/contentPrincipal.tp';	
			
		}

?>