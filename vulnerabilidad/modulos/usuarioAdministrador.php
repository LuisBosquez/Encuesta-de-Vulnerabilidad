<?php
//21 AGO 07

	//TODO:Manejar seguridad de $_SESSION['usuario_id']
		if($_GET && isset($_GET['reportes'])){			

			include_once('./modulos/reportes/reporteCHEGrupos.php');			
		}
		else if($_GET && isset($_GET['usuarios']))
			include_once('./modulos/usuariosModulo.php');			
		else{
			$mensajes['menu.location']='Usuario: '.$_SESSION['usuario_nombre'];
			$data['template.contenido']='./templates/sac/aplicacion/contentPrincipal.tp';	
			
		}

?>