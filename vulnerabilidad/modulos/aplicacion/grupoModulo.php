<?php



		include_once('./classes/catalogos.php');

		
		
		
		if($_GET && isset($_GET['asignar']))
		{
			$mensajes['menu.location']= 'Asignacion Cuestionario';
			include_once('./classes/grupo.php');
			
			
			if($_POST)
			{

				$grupo = new grupo();
				$asig = $grupo->llenaArregloAsignacionDesdePost();
				
				if($grupo->asignaCuestionario($asig))content::armaMensaje($data,'Exito','El cuestionario ha sido asignado al grupo y ha sido <b>Activado</b><br><br><a href="usuario.php?grupos&id='.$asig['grupo_id'].'">Ir al grupo</a>');			
				else content::armaMensaje($data,'Error','El cuestionario ya fue asignado a este grupo<br><br><a href="usuario.php?grupos&id='.$asig['grupo_id'].'">Ir al grupo</a>');			
			}
			else
			{

				$catalogos = new catalogos();
				
				
//				$data['forma.periodo'] = content::getComboOpciones('periodo',$catalogos->obtenerPeriodos());					
				$data['template.body']='./templates/sac/aplicacion/gruposAsignar.tp';	

				$data['grupo.id']=$_GET['id'];
				$_POST['id_grupo']=$_GET['id'];
				$data['forma.hora']= content::getComboOpciones('hora',catalogos::obtenerHoras(4),'small');									
				$data['forma.minutos']= content::getComboOpciones('minutos',catalogos::obtenerMinutosCino(),'small');													
				
				
				$data['forma.horai']= content::getComboOpciones('hora_i',catalogos::obtenerHorasDia(),'small');									
				$data['forma.minutosi']= content::getComboOpciones('minutos_i',catalogos::obtenerCuartosDeHora(),'small');													
				$data['forma.horaf']= content::getComboOpciones('hora_f',catalogos::obtenerHorasDia(),'small');									
				$data['forma.minutosf']= content::getComboOpciones('minutos_f',catalogos::obtenerCuartosDeHora(),'small');																	
				$data['forma.grupo']= content::getComboOpciones('id_grupo',$catalogos->obtenerGruposUsuario($_SESSION['usuario_id']));					
				$data['forma.cuestionario']= content::getComboOpciones('id_cuestionario',$catalogos->obtenerCuestionariosUsuario());					
				$data['forma.fechaInicio']= '';
				$data['forma.fechaFin']= '';				
			
			}

			
		}
		else if($_GET && isset($_GET['altaIntgs']) &&  isset($_GET['id']) )//ALTA DEINTEGRANTES A UN GRUPO
		{	//TODO:Encriptar idgrupo
			$mensajes['menu.location']= 'Alta de Integrantes';
			
			$id = $_GET['id'];//TODO:Validar y desencriptar
			if($_POST)
			{
				
				include_once('./classes/grupo.php');
				$grupo = new grupo();
				
				$tok1 = strtok($_POST["alumnos"],"\n\t");
			
				$usuarios = array();
				do{

				 
				  if($tok1=="")continue;
				  $tok1 = sprintf("%08d", $tok1);
				  $tok1 = str_ireplace('\n','',$tok1);
					$tok1 = str_ireplace('\t','',$tok1);
				  $usuarios[] = $tok1;
				   $tok1 = strtok("\n\t");
				}while($tok1);
	   			
				$i = 0;
				foreach($usuarios as $items)
				{
					
					if($grupo->altaAlumnoEnGrupo($items,$id))
					$i++;
					
				}
				//TODO mensahe mas claro		
				content::armaMensaje($data,'Exito',$i.' alumnos agregados exitosamente. <br><br><a hrf="usuario.php?grupos&id='.$id.'">Volver al grupo</a>');
		
			}
			else
			{
				$data['template.body']='./templates/sac/aplicacion/gruposUsuarios.tp';
				$data['grupo.id']=''.$id;
			}
			
		}	
		else if($_GET && isset($_GET['alta']))//ALTA DE UN GRUPO
		{
			
			$mensajes['menu.location']= 'Alta de Grupo';
			
			if($_POST)
			{
				include_once('./classes/grupo.php');
				$grupo = new grupo();
				$gpd = $grupo->llenaArregloDesdePost();
				$id = $grupo->altaGrupo($gpd);
					//TODO: Cambiar por bullets
				content::armaMensaje($data,'Exito','El grupo fue dado de alta exitosamente.<br><br>
						<a href="{url.grupos}&altaIntgs&id={grupo.id}">-> Dar de alta integrantes</a><br>
						<a href="{url.grupos}">-> Ver Grupos</a><br>
						<a href="{url.grupos}&id={grupo.id}">-> Ver Grupo Creado</a>');
				//TODO:Encriptar idgrupo
				$data['grupo.id']=''.$id;
				
			}else
			{
				
				$catalogos = new catalogos();
				
				$data['template.body']='./templates/sac/aplicacion/gruposCrear.tp';					
				$data['forma.periodo'] = content::getComboOpciones('periodo',$catalogos->obtenerPeriodos());					
			}
			

		}
		else if($_GET && isset($_GET['id']))//VER UN GRUPO
		{
			//TODO ENCRIPTAR Y DESENCRIPTAR
			$mensajes['menu.location']= 'Ver Grupo';
			include_once('./classes/grupo.php');
			include_once('./classes/cuestionario.php');
			include_once('./classes/usuario.php');
			
			$grupo = new grupo();
			$cuestionario=new cuestionario();
			$usr = new usuario();			
			
			if(isset($_GET['del'])&&isset($_GET['usr']))
			{
				
				$grupo->bajaAlumnoEnGrupo($_GET['usr'],$_GET['id']);
				$mensajes['mensaje.error'] = 'Usuario '.$_GET['usr'].' borrado';
				
				
			}
							

			
			$gpo = $grupo->obtenerGrupo($_GET['id']);
			$ctos = $grupo->obtenerCuestionariosAsignados($_GET['id']);
			$alms = $grupo->obtenerAlumnos($_GET['id']);
			
			$data['template.body']='./templates/sac/aplicacion/gruposVer.tp';	
			$data['grupo.nombre'] = $gpo['grupo_nombre'];		
			$data['grupo.grupo'] = $gpo['grupo_grupo'];
			$data['grupo.clave'] = $gpo['grupo_clave'];
			$data['grupo.periodo'] =$gpo['periodo_nombre'];
			$data['grupo.id'] = $gpo['grupo_id'];
			
			
			$ctonew = array();
			foreach($ctos as $element)
			{
				$temp = array();
				$temp[] = $element['cuestionario_nombre'];//TODO		echo "<td><a href=\"profesor.php?id=vgrupo&gp=".$_GET['gp']."&id_c=".$row['id_cuestionario']."\">$row[nombre]</a></td>";				
				$temp[] = $element['cuestionario_descripcion'];
				$temp[] = $element['asignacion_fechainicio'];;
				$ctonew[]=$temp;								
			}			

			$alumnos = array();
			foreach($alms as $element)
			{
				$temp = array();
				$temp[] = '<td>'.$element['usuario_usuario'].'</td>';
				
				$usuario = $usr->obtenerUsuarioUsuario($element['usuario_usuario']);
				if($usuario)
				{
					$temp[] = '<td>'.$usuario['usuario_nombre'].' '.$usuario['usuario_apellido'].'</td>';	
					$temp[] = '<td>'.$usuario['usuario_nacimiento'].'</td>';	
				}
				else
				{
					$temp[] = "<td colspan=\"2\"><b>Usuario no registrado</b></td>";
				}
					$temp[] = '<td align="center">
						<a onclick="javascript: return confirmSubmit(1)" 
						href="{url.grupos}&id='.$_GET['id'].'&usr='.$element['usuario_usuario'].'&del">{imagen.bajaalumno}</a></td>';	
					//TODO encripcion																			
				
				$alumnos[]=$temp;								
			}				
			//TODO FALTA EL QUERY 1
			$data['tabla.cuestionariosasignados']=content::generaFilaDeTabla($ctonew,6);	
			$data['tabla.alumnos']=content::generaFilaDeTablaSinTD($alumnos,6);	

			
		}
		else if($_GET && isset($_GET['grupos']))//VER TO.DOS LOS GRUPOS del usuario //TODO organizarlos por usuario
		{
			
			$mensajes['menu.location']= 'Grupos de Usuario';
			include_once('./classes/grupo.php');
			$data['template.body']='./templates/sac/aplicacion/grupos.tp';	
			
			$grupo = new grupo();
			$grp = $grupo->obtenerGrupos($_SESSION['usuario_id']);
			$grpnew = array();
			foreach($grp as $element)
			{
				$temp = array();
				$temp[] = $element['grupo_clave'];
				$temp[] = $element['grupo_nombre'];
				$temp[] = $element['grupo_grupo'];;
				$temp[] = $element['periodo_nombre'];;
				$temp[] = "
				<center>
				<a href={url.grupos}&id=".$element['grupo_id'].">{imagen.verdetalle}</a>
				<!--<a href={url.grupos}&cuestionario&gp=".$element['grupo_id'].">{imagen.asignarcuestionario}</a>-->
				</center>";
				$grpnew[]=$temp;								
			}
	
	
	
	
			
			$data['tabla.resultados']=content::generaFilaDeTabla($grpnew,6);	
		}

?>