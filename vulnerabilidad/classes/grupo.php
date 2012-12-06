<?php


class grupo
{
	
	function grupo()
	{
		$this->base = new connect();
	}
	
	function altaGrupo($Arreglo){
		$datos = array('usuario_id'=>$_SESSION['usuario_id']);
		$datos = array_merge($Arreglo,$datos);
		$this->base->generateSQLInsert('grupo',$datos);
		return mysql_insert_id();
	}	

	
	
	function altaAlumnoEnGrupo($usuario,$idGrupo)
	{
			//TODO: EN UN FUTURO, PENSAR EN AGRGEAR LA FECHA DE ALTA
			//TODO: VALIDAR, si el usuario no existe invitarlo
			$datos = array(
				'usuario_usuario'=>$usuario,
				'grupo_id'=>$idGrupo

			);

		$query = "select * from usuariogrupo where usuario_usuario='".$usuario."' and grupo_id=".$idGrupo;
		$test = $this->base->queryArrayUnico($query);
		
		
		if($test!=null)return false;
		
		$this->base->generateSQLInsert('usuariogrupo',$datos);return true;

	}		

	function bajaAlumnoEnGrupo($usuario,$idGrupo)
	{
		
		

		$query = "delete from usuariogrupo where usuario_usuario='".$usuario."' and grupo_id=".$idGrupo;
		
		$test = $this->base->query($query);
		
		
		

	}		
	
	function obtenerGrupos($id)
	{
		//Este query regresa los grupos dados de alta por un profe
		$query = "select grupo_id,grupo_clave,grupo_grupo,periodo_nombre
		,grupo_nombre,grupo_estado from grupo natural join periodo where grupo_estado='1' and usuario_id='".$id."'";
		$data = $this->base->queryArray($query);
		if($data==null) return array();			
		else return $data;		
		
	}
	
	function obtenerAlumnosGruposAsignados($idcarrera,$idcuestionario)
	{
		$query ="select usuario.usuario_id,grupo.grupo_id,usuario.usuario_usuario,usuario.usuario_nombre,usuario.usuario_apellido,grupo_clave,grupo_nombre,asignacion_fechainicio
		from usuario, usuariogrupo,grupo,asignacion
		where carrera_id=".$idcarrera." AND usuario.usuario_usuario=usuariogrupo.usuario_usuario and usuariogrupo.grupo_id = grupo.grupo_id
		and grupo.grupo_id = asignacion.grupo_id and cuestionario_id =".$idcuestionario."
		order by usuario.usuario_usuario";
		$data = $this->base->queryArray($query);
		if($data==null) return array();			
		else return $data;				
		
	}
	
	function obtenerGrupo($idGrupo)
	{
								
		$query="select * from grupo natural join periodo where grupo_id=".$idGrupo;	

		$data = $this->base->queryArrayUnico($query);
		if($data==null) return null;			
		else return $data;		
	}
	

		function llenaArregloDesdePost()
		{
			$arreglo['grupo_clave'] = $_POST['clave']; 
			$arreglo['grupo_nombre'] = $_POST['nombre']; 
			$arreglo['grupo_grupo'] =  $_POST['grupo']; 		
			$arreglo['periodo_id'] =  $_POST['periodo']; 			
			return $arreglo;
		}

		
	function obtenerCuestionariosAsignados($grupo)
	{
		$query ="select cuestionario_nombre,cuestionario_descripcion,asignacion_fechainicio
			from asignacion natural join cuestionario 
			where grupo_id='".$grupo."'";

			$data = $this->base->queryArray($query);
			if($data==null) return Array();			
			else return $data;			
	
	}
	
	function obtenerAlumnos($grupo)
	{
		$query="select usuario_usuario from usuariogrupo where grupo_id =".$grupo;			
			$data = $this->base->queryArray($query);
			if($data==null) return Array();			
			else return $data;		
	}
	
	
	function llenaArregloAsignacionDesdePost()
	{
				$datosAsig['asignacion_fechainicio'] = $_POST['datevalue'];
				$datosAsig['asignacion_fechafin'] = $_POST['datevalue2'];		
				$datosAsig['asignacion_horainicio'] = $_POST['hora_i'].":".$_POST['minutos_i'].":00";		
				$datosAsig['asignacion_horafin'] = $_POST['hora_f'].":".$_POST['minutos_f'].":00";		
				$datosAsig['asignacion_tiempo'] = $_POST['hora'].":".$_POST['minutos'].":00";		
				$datosAsig['cuestionario_id'] = $_POST['id_cuestionario'];
				$datosAsig['grupo_id'] =$_POST['id_grupo'];	
				return $datosAsig;
		
	}
	
	
	/**
	 * Asigna un cuestionario a un grupo.Revisa que el grupo no tenga un cuestionario
	 * ya asignado
	 *
	 * @param array datos asignacion
	 * @return verdadero en caso de exito,
	 */
	function asignaCuestionario($datos){
		$query = "select * from asignacion where cuestionario_id='".$datos['cuestionario_id']."' and grupo_id=".$datos['grupo_id'];
		$test = $this->base->queryArrayUnico($query);
		if($test!=null)return false;		
		$this->base->generateSQLInsert('asignacion',$datos);
		return true;		
	}
	
	
	
	function editaCuestionario($idCuestionario,$idGrupo,$POST)
	{
			$datos['asignacion_fechainicio'] = $POST['datevalue'];
			$datos['asignacion_fechafin'] = $POST['datevalue2'];
			$datos['asignacion_horainicio'] = $POST['hora_i'].":".$POST['minutos_i'].":00";		
			$datos['asignacion_horafin'] = $POST['hora_f'].":".$POST['minutos_f'].":00";		
			$datos['asignacion_tiempo'] = $POST['hora'].":".$POST['minutos'].":00";	
							

			$cond = "cuestionario_id=".$idCuestionario." AND grupo_id=".$idGrupo;
			$this->base->generateSQLUpdate('asignacion',$datos,$cond);
			return true;	
		
	}
	
	
}