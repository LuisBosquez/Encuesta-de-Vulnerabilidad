<?php


if($_GET && isset($_GET['crear'])){

	include_once('./classes/catalogos.php');
	$mensajes['menu.location']= 'Crea Sesion';
	include_once('./classes/grupo.php');
	include_once('./classes/sesion.php');			
				
	if($_POST){
		$sesion = new sesion();
		$nuevo = $sesion->llenaArregloCrearDesdePost();
		if($sesion->altaSesion($nuevo))content::armaMensaje($data,'Exito','La sesi_n ha sido creada<br><br><a href="usuario.php?sesion">Regresar</a>');			
		else content::armaMensaje($data,'Error','Error. <br><a href="usuario.php?sesion">Regresar</a>');			
	}else{
		$catalogos = new catalogos();
		$data['template.body']='./templates/sac/sesion/sesionCrear.tp';
		$data['forma.periodo'] = content::getComboOpciones('periodo',$catalogos->obtenerPeriodos());					
			
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
}else if(isset($_GET['ver']) && isset($_GET['idCuest']) && isset($_GET['idGrupo']))
	{
		
		$idcuestionario =  $_GET['idCuest'];
		$idgrupo = $_GET['idGrupo'];
		include_once('./classes/cuestionario.php');
		include_once('./classes/grupo.php');
		include_once('./lib/encryption.php');	
		include_once('./classes/usuario.php');
		$mensajes['menu.location']= 'Detalles Cuestionarios';
		
		$cuestionario = new cuestionario();
		$usr = new usuario();		
		$grupo = new grupo();
		$cuest = $cuestionario->obtenerDatosCuestionario($_GET['idGrupo'],$_GET['idCuest']);

		$data['template.body']='./templates/sac/aplicacion/cuestionariosVer.tp';	

		
		
		
		$data['cuestionario.nombre'] = $cuest['cuestionario_nombre'];
		$data['cuestionario.descripcion'] = $cuest['cuestionario_descripcion'];		
		$data['grupo.nombre'] = $cuest['grupo_nombre'];
		$data['cuestionario.periodo'] = $cuest['periodo_nombre'];
		$data['cuestionario.fechaInicio'] = $cuest['asignacion_fechainicio'];
		$data['cuestionario.horaInicio'] =  $cuest['asignacion_horainicio'];
		$data['cuestionario.contestar'] =  $cuest['asignacion_tiempo'];
		if($cuest['asignacion_estado']==1)$data['cuestionario.estado'] = 'Activado';
		else $data['cuestionario.estado'] = 'Desactivado';
		
		$data['cuestionario.acciones'] = '';

		
		if($cuest['asignacion_almacenado']=="0") $data['cuestionario.acciones'].= 
		" <a href=\"{url.cuestionarios}&editar&idGrupo=".$idgrupo."&idCuest=".$idcuestionario."\"><img src=\"imagenes/b_edit.png\" border=\"0\" alt=\"Editar\">[Editar]</a>";
      	 $data['cuestionario.acciones'].= 
      	 	 " <a href=\"{url.cuestionarios}&exportarXLS&idGrupo=".$idgrupo."&idCuest=".$idcuestionario."\"><img src=\"imagenes/b_save.png\" border=\"0\" alt=\"Guardar\">[Guardar XLS]</a> ";				  				  
		//if($cuest['asignacion_almacenado']=="0") 
		//$data['cuestionario.acciones'].= 
			// "<a  onclick=\"javascript: return confirmSubmitDatabase()\"  href=\"".$_SERVER['PHP_SELF']."?id=alm&gp=".$idgrupo."&id_c=".$idcuestionario."\"><img src=\"imagenes/mgmt_fileManager.gif\" border=\"0\" alt=\"Guardar\" width=\"16\">[Almacenar en Historico]</a>";

		if($cuest['asignacion_almacenado']=="0" && $cuest['asignacion_estado']!="1")echo "<a href=\"".$_SERVER['PHP_SELF']."?id=1&grupo=$cuest[id_grupo]&cuestionario=$cuest[id_cuestionario]\">Activar</a>  <img onMouseOver=\"stm(Text[0],Style[2])\" onMouseOut=\"htm()\" src=\"imagenes/help_icon.gif\" width=\"11\" height=\"11\">";
	
		
		
		
		
		include_once('./classes/usuario.php');
		$alms = $grupo->obtenerAlumnos($_GET['idGrupo']);
			$alumnos = array();
			foreach($alms as $element)
			{
				$temp = array();
				$temp[] = '<td>'.$element['usuario_usuario'].'</td>';
				
				$usuario = $usr->obtenerUsuarioUsuario($element['usuario_usuario']);
				if($usuario)
				{
					

					
					$temp[] = '<td>'.$usuario['usuario_nombre'].' '.$usuario['usuario_apellido'].'</td>';
					
					$apl = $cuestionario->obtenerAplicacion($usuario['usuario_id'],$_GET['idCuest'],$_GET['idGrupo']);
					
					if($apl!=null){

					$enc= new Crypto();
					$idEnc = trim($enc->encrypt($apl['aplicacion_id']));						
					$temp[] = '<td>Resuelto</td>';
					$temp[] = '<td>'.$apl['aplicacion_fecha'].'</td>';
					$temp[] = '<td>'.$apl['aplicacion_tiempo'].'</td>';
					$temp[] = "<td>
					<a 
					href=\"{url.cuestionarios}&resultados&idApl=".$idEnc."\"><img src=\"imagenes/b_search.png\" border=\"0\" alt=\"Ver Detalles\"  onMouseOver=\"stm(Text[1],Style[17])\" href=\"#\" onMouseOut=\"htm()\"></a>
				    <a onclick=\"javascript: return confirmSubmit2()\" 
					href=\"{url.cuestionarios}&borrar&idApl=".$idEnc."\"><img src=\"imagenes/delete2.gif\" border=\"0\" alt=\"Borrar\" width=\"16\" onMouseOver=\"stm(Text[2],Style[17])\" href=\"#\" onMouseOut=\"htm()\"></a>		

					</td>";									
					}
					else
					{
					$temp[] = '<td>Pendiente</td>';
					$temp[] = '<td></td>';
					$temp[] = '<td></td>';
					$temp[] = '<td></td>';

					}
				}
				else
				{
					$temp[] = "<td colspan=\"2\"><b>Usuario no registrado</b></td>";
				}

					
					//TODO encripcion																			
				
				$alumnos[]=$temp;								
			}		
			$data['forma.asignados']=content::generaFilaDeTablaSinTD($alumnos,6);				
			
	}
	else if(isset($_GET['resultados']))
	{
		include_once('./modulos/examen/resultadoSupervisar.php');
	}
	else if(isset($_GET['editar']))
	{
	


		
		include_once('./classes/cuestionario.php');
		include_once('./classes/grupo.php');
		include_once('./classes/usuario.php');
		include_once('./classes/catalogos.php');
		$mensajes['menu.location']= 'Editar Cuestionarios';
		
		
		$cuestionario = new cuestionario();
		$usr = new usuario();		
		$grupo = new grupo();	
		$catalogos = new catalogos();
		
		
		
		
		if($_POST)
		{
			$idcuestionario =  $_POST['idCuestionario'];
			$idgrupo = $_POST['idGrupo'];
			$grupo->editaCuestionario($idcuestionario,$idgrupo,$_POST);	

			//TODO validar antes y despues-seses
			
			content::armaMensaje($data,'Exito','Cuestionario editado exitosamente. <br><br> <a href="usuario.php?cuestionario&ver&idGrupo='.$idgrupo.'&idCuest='.$idcuestionario.'">Volver al Cuestionario</a>');					
			
			
		}
		else if(isset($_GET['idCuest']) && isset($_GET['idGrupo']))
		{

			$idcuestionario =  $_GET['idCuest'];
			$idgrupo = $_GET['idGrupo'];
			$cuest = $cuestionario->obtenerDatosCuestionario($_GET['idGrupo'],$_GET['idCuest']);
	
			
			$data['forma.fechaInicio']=$cuest['asignacion_fechainicio'];
			$data['forma.fechaFin']=$cuest['asignacion_fechafin'];
	
			$time1 = explode(":",$cuest['asignacion_horainicio']);  
			$time2 = explode(":",$cuest['asignacion_horafin']);  
			$time3 = explode(":",$cuest['asignacion_tiempo']);  
			
			$_POST['hora_i']=$time1[0];
			$_POST['minutos_i']=$time1[1];	
			$_POST['hora_f']=$time2[0];
			$_POST['minutos_f']=$time2[1];		
			$_POST['hora']=$time3[0];
			$_POST['minutos']=$time3[1];
	
	
			$data['cuestionario.id']=$idcuestionario;
			$data['grupo.id']=$idgrupo;
			$data['template.body']='./templates/sac/aplicacion/cuestionarioAsignadoEditar.tp';	
		
			$data['forma.hora']= content::getComboOpciones('hora',catalogos::obtenerHoras(4),'small');									
			$data['forma.minutos']= content::getComboOpciones('minutos',catalogos::obtenerMinutosCino(),'small');													
					
					
			$data['forma.horai']= content::getComboOpciones('hora_i',catalogos::obtenerHorasDia(),'small');									
			$data['forma.minutosi']= content::getComboOpciones('minutos_i',catalogos::obtenerCuartosDeHora(),'small');													
			$data['forma.horaf']= content::getComboOpciones('hora_f',catalogos::obtenerHorasDia(),'small');									
			$data['forma.minutosf']= content::getComboOpciones('minutos_f',catalogos::obtenerCuartosDeHora(),'small');																	
	
			$data['forma.grupo']= $cuest['grupo_nombre'];
			$data['forma.cuestionario']= $cuest['cuestionario_nombre'];
			
		}	
		else
			content::armaMensaje($data,'Error','Existio un error');					
		
	
		 

	
	}
	else if(isset($_GET['guardar']) && isset($_GET['idCuest']) && isset($_GET['idGrupo']))
	{
		//TODO
		
		/*
include("includes/top_maestros.htm");
			 if(isset($_GET['id']) && $_GET['id']="alm")
				{

					$query = "UPDATE asignacion SET almacenado = 1 ,habilitado = '0' WHERE id_cuestionario=".$_GET['id_c']." AND id_grupo=".$_GET['gp'];
					$result = mysql_query($query);
					if($result) echo "<p align=center><b>EL CUESTIONARIO FUE ALMACENADO CON EXITO</b></p>";

				}
		*/
		content::armaMensaje($data,'Error','Esta opcion permite almacenar el cuestionario');					
	}else if(isset($_GET['exportarXLS']) && isset($_GET['idCuest']) && isset($_GET['idSesion'])){

		include_once('./modulos/aplicacion/exportarCuestionarioExcel.php');
		

	
	}else if(isset($_GET['exportarXLSAnalisis']) && isset($_GET['idCuest']) && isset($_GET['idSesion'])){
		
		include_once('./modulos/aplicacion/exportarCuestionarioExcel.php');
		

	
	}else
	{
		include_once('./classes/cuestionario.php');
		
		$mensajes['menu.location']= 'Sesiones';
		$data['template.body']='./templates/sac/aplicacion/sesionessAsignados.tp';	

		
			$cuestionario = new cuestionario();
			$cto = $cuestionario->obtenerSesionesAsignados($_SESSION['usuario_id']);
			$ctoasig = array();
			foreach($cto as $element)
			{
				$temp = array();
				
				$temp[] = $element['sesion_nombre'];
				$temp[] = $element['cuestionario_nombre'];
				$temp[] = $element['periodo_nombre'];
				$temp[] = $element['sesion_inicio'].' <br>'.$element['sesion_fin'].'';
				$temp[] = $element['sesion_tiempo'];
				$temp[] = $element['sesion_password'];
				if($element['sesion_habilitado']=='1');
				$temp[] = "<a href=\"{url.cuestionarios}&ver&idSesion=".$element['sesion_id']."&idCuest=".$element['cuestionario_id']."\">{imagen.verCuestionario}</a>";
				$ctoasig[]=$temp;								
			}
			$data['tabla.cuestionarios']=content::generaFilaDeTabla($ctoasig,6);		
	
	}

?>