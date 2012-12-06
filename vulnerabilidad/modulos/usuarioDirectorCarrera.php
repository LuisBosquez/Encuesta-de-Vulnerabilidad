<?php
//27 AGO 07
		

		include_once('./classes/catalogos.php');
		include_once('./classes/grupo.php');		
		include_once('./classes/usuario.php');		
		include_once('./classes/cuestionario.php');		
		include_once('./lib/encryption.php');		
		$idcarrera = $_SESSION['carrera_id'];

		if($_GET && isset($_GET['reportes'])){			

			include_once('./modulos/reportes/reporteDirectorCarrera.php');			
		}		
		else if(isset($_GET['resultados']))include_once('./modulos/examen/resultadoSupervisar.php');
		else if(isset($_GET['cuestionarios'])){
			$data['template.contenido']='./templates/sac/director/alumnosExamen.tp';								
			$catalogos = new catalogos();
			$carrera = $catalogos->obtenerCarrera($idcarrera);	
			$data['pagina.carrera']=$carrera['carrera_nombrecorto'].' : '.$carrera['carrera_nombre'];	
			$usuario = new usuario();
			$grupo = new grupo();
			$usrs = $grupo->obtenerAlumnosGruposAsignados($idcarrera,1);
			$cuestionario = new cuestionario();			
			$urstbl = array();
			$enc= new Crypto();
			foreach($usrs as $element){
					$temp = array();
					$temp[] = $element['usuario_usuario'];
					$temp[] = $element['usuario_nombre'].' '.$element['usuario_apellido'];
					$temp[] = $element['grupo_clave'].' : '.$element['grupo_nombre'];	
					$apl = $cuestionario->obtenerCuestionarioParaUsuarioPorGrupo($element['usuario_id'],$element['grupo_id'],1);
					if($apl==null)$temp[] = "Sin Resultado";
					else $temp[] = "<a href={url.grupos}&resultados&idApl=".trim($enc->encrypt($apl['aplicacion_id'])).">{imagen.verdetalle}</a>";					
					$urstbl[]=$temp;								
			}
			$data['tabla.alumnosche']=content::generaFilaDeTabla($urstbl,4);	
			
			$usrs = $grupo->obtenerAlumnosGruposAsignados($idcarrera,2);
			$cuestionario = new cuestionario();			
			$urstbl = array();
			$enc= new Crypto();
			foreach($usrs as $element){
					$temp = array();
					$temp[] = $element['usuario_usuario'];
					$temp[] = $element['usuario_nombre'].' '.$element['usuario_apellido'];
					$temp[] = $element['grupo_clave'].' : '.$element['grupo_nombre'];	
					$apl = $cuestionario->obtenerCuestionarioParaUsuarioPorGrupo($element['usuario_id'],$element['grupo_id'],2);
					if($apl==null)$temp[] = "Sin Resultado";
					else $temp[] = "<a href={url.grupos}&resultados&idApl=".trim($enc->encrypt($apl['aplicacion_id'])).">{imagen.verdetalle}</a>";					
					$urstbl[]=$temp;								
			}					
			$data['tabla.alumnosvulnerabilidad']=content::generaFilaDeTabla($urstbl,4);	
			
			$data['tabla.alumnosche'] = utf8_encode($data['tabla.alumnosche']);
			$data['tabla.alumnosvulnerabilidad'] = utf8_encode($data['tabla.alumnosvulnerabilidad']);			
			
		}
		else{
			$data['template.contenido']='./templates/sac/director/alumnosCarrera.tp';								
			$catalogos = new catalogos();
			$carrera = $catalogos->obtenerCarrera($idcarrera);	
			$data['pagina.carrera']=$carrera['carrera_nombrecorto'].' : '.$carrera['carrera_nombre'];	
			$usuario = new usuario();
			$usrs = $usuario->obtenerUsuariosPorCarrera($idcarrera);		
			$urstbl = array();
			foreach($usrs as $element){
					$temp = array();
					$temp[] = $element['usuario_usuario'];
					$temp[] = $element['usuario_nombre'].' '.$element['usuario_apellido'];
					$temp[] = "<a href={url.grupos}&id=".$element['usuario_id'].">{imagen.verdetalle}</a>";
					$urstbl[]=$temp;								
			}
			$data['tabla.alumnoscarrera']=content::generaFilaDeTabla($urstbl,3);
				
		}

		$mensajes['menu.location']='Dir.Carrera : '.$_SESSION['usuario_nombre'];
		
			

?>