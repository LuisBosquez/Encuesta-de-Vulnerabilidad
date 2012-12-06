<?PHP
//21 AGO 07
class reporte{
	var $base = null;

	function reporte(){
		$this->base = new connect();
	}

	
	function obtenerCarrerasYAlumnos($periodoId){
			
		$query = "
			select carrera.carrera_id,carrera.carrera_nombrecorto,carrera.carrera_nombre,sum(1) as alumnos from carrera,(
			select distinct usuario.usuario_id,usuario.carrera_id from usuario,usuariogrupo,grupo where usuario.usuario_usuario=usuariogrupo.usuario_usuario and usuariogrupo.grupo_id =grupo.grupo_id
			and grupo.periodo_id = ".$periodoId." and usuario.permiso_id = 0 order by usuario_id ) as usuario2 where carrera.carrera_id=usuario2.carrera_id
			group by carrera_id
			";		
		$data = $this->base->queryArray($query);
		if($data==null) return array();			
		else return $data;			
			
	}

	function obtenerCarrerasYAlumnosID($carrera,$periodoId)
	{
			
		$query = "
			select carrera.carrera_id,carrera.carrera_nombrecorto,carrera.carrera_nombre,sum(1) as alumnos from carrera,(
			select distinct usuario.usuario_id,usuario.carrera_id from usuario,usuariogrupo,grupo where usuario.usuario_usuario=usuariogrupo.usuario_usuario and usuariogrupo.grupo_id =grupo.grupo_id
			and grupo.periodo_id = ".$periodoId." and usuario.permiso_id = 0 order by usuario_id ) as usuario2 where carrera.carrera_id=usuario2.carrera_id
			and carrera.carrera_id = ".$carrera." group by carrera_id
			";		
		$data = $this->base->queryArray($query);
		if($data==null) return array();			
		else return $data;			
			
		}		
		
		function obtenerPromedioResultadosSACCarrera($carrera,$periodoId){
			
			$query = "
				select resultados_seccion,avg(resultados_grade) as resultados_grade from resultados,aplicacion,(select distinct usuario.usuario_id from usuario,usuariogrupo,grupo where usuario.usuario_usuario=usuariogrupo.usuario_usuario and usuariogrupo.grupo_id =grupo.grupo_id
				and grupo.periodo_id = ".$periodoId." and usuario.permiso_id = 0 and usuario.carrera_id = ".$carrera." order by usuario_id 
				) as usuario2
				where resultados.aplicacion_id = aplicacion.aplicacion_id and cuestionario_id = 1 and aplicacion.usuario_id =usuario2.usuario_id
				group by resultados_seccion order by resultados_seccion

				";		
			$data = $this->base->queryArray($query);
			if($data==null) return array();			
			else return $data;			
			
		}
		
		
		
		
		function obtenerCantidadCheContestadosPorCarrera($carrera,$periodoId){
			$query = "
				select count(1) as alumnos from (
				select distinct usuario2.usuario_id from aplicacion,(select distinct usuario.usuario_id from usuario,usuariogrupo,grupo where usuario.usuario_usuario=usuariogrupo.usuario_usuario and usuariogrupo.grupo_id =grupo.grupo_id
				and grupo.periodo_id = ".$periodoId." and usuario.permiso_id = 0 and usuario.carrera_id =".$carrera."  order by usuario_id 
				) as usuario2
				where cuestionario_id = 1 and aplicacion.usuario_id =usuario2.usuario_id) as dt


				";		
			$data = $this->base->queryArrayUnico($query);
			if($data==null) return null;			
			else return $data;							
		}
		/***********************/
		function ontenerGruposyAlumno($periodoId){
			
			$query = "
				select grupo.grupo_id,grupo_clave,grupo_nombre,count(*) as alumnos from grupo,usuariogrupo
				where grupo.grupo_id=usuariogrupo.grupo_id and periodo_id =".$periodoId." 
				group by grupo.grupo_id 
				";		
			$data = $this->base->queryArray($query);
			if($data==null) return array();			
			else return $data;			
			
		}		
	function obtenerCantidadCheConetsatdosPorGrupo($grupo){
		$query = "select count(*) as alumnos from aplicacion where grupo_id=".$grupo." and cuestionario_id = 1";		
		$data = $this->base->queryArrayUnico($query);
		if($data==null) return null;			
		else return $data;							
	}	
	function obtenerPromedioResultadosSACGrupo($grupo){
		$query = "
		select resultados_seccion,avg(resultados_grade) as  resultados_grade from resultados,aplicacion
		where resultados.aplicacion_id=aplicacion.aplicacion_id and aplicacion.grupo_id = ".$grupo." and cuestionario_id = 1
		group by resultados.resultados_seccion order by resultados_seccion";		
		$data = $this->base->queryArray($query);
		if($data==null) return array();			
		else return $data;			
	}		
		/**********************************************/	
		function obtenerNumeroResultadosdelCHE($periodoId){
			$query = "select count(*) as resultados 
				from aplicacion,grupo 
				where 
					aplicacion.grupo_id = grupo.grupo_id 
					and cuestionario_id = 1 and periodo_id = ".$periodoId;		
			$data = $this->base->queryArrayUnico($query);
			if($data==null) return array();			
			else return $data;					
		}			
		
		function obtenerResultadosdelCHE($periodoId){
			$query = "
				select resultados_seccion, avg(resultados_grade) as resultados_grade from resultados,aplicacion,grupo where 
				resultados.aplicacion_id=aplicacion.aplicacion_id and
				aplicacion.grupo_id = grupo.grupo_id and cuestionario_id = 1 and periodo_id = ".$periodoId."
				group by resultados.resultados_seccion order by resultados_seccion				";		
			$data = $this->base->queryArray($query);
			if($data==null) return array();			
			else return $data;			
			
		}			
		
		
		
	}		
?>