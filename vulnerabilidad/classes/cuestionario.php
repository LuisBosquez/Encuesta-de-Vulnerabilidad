<?php

/**
 * @author CESAR SANCHEZ
 * @date 30/Juli/07
 */
class cuestionario
{

	function cuestionario()
	{
		$this->base = new connect();
	}
	
	//TODO:REVISAR
	//Para los usuarios normales
	function obtenerCuestionariosParaResolver($usuario)
	{
		// Este query regresa los cuestionairios disponibles de un Alumno
		$query = "select cuestionario.cuestionario_id,grupo.grupo_id,grupo_nombre,grupo_grupo,grupo_clave,periodo_nombre,cuestionario_nombre,
			asignacion_fechainicio,asignacion_fechafin,asignacion_horainicio,asignacion_horafin,
			asignacion_tiempo,
			UNIX_TIMESTAMP(addtime(asignacion_fechainicio,asignacion_horainicio)) as ts_inicio,
			UNIX_TIMESTAMP(addtime(asignacion_fechafin,asignacion_horafin)) as ts_fin
			from 
			usuario left join
			usuariogrupo  using (usuario_usuario)
			left join grupo on (usuariogrupo.grupo_id = grupo.grupo_id)
			left join asignacion on (grupo.grupo_id=asignacion.grupo_id)
			left join cuestionario on (asignacion.cuestionario_id=cuestionario.cuestionario_id)
			natural join periodo
			where usuario.usuario_usuario = '".$usuario."' ";		
						
						
			$data = $this->base->queryArray($query);

			if($data==null) return  Array();			
			else return $data;					
						
	}
	
	
	function obtenerCuestionarioParaUsuarioPorGrupo($usuario,$grupo,$cuestionarios){
		$query = "select * from aplicacion where usuario_id  = ".$usuario." and grupo_id  = ".$grupo." and cuestionario_id = ".$cuestionarios."";
		$data = $this->base->queryArrayUnico($query);

			if($data==null) return  null;			
			else return $data;			
	}
	
	//TODO:REVISAR
	function obtenerCuestionariosResueltos($id)
	{
		$data = null;
		$query = "select
			aplicacion_id, cuestionario.cuestionario_id,aplicacion_fecha,grupo_clave,grupo_nombre,cuestionario_nombre,periodo_nombre
			from
			aplicacion left join grupo using (grupo_id)
			left join cuestionario using (cuestionario_id) natural join periodo
			where aplicacion.usuario_id=".$id;



			$data = $this->base->queryArray($query);
			if($data==null) return  Array();			
			else return $data;			
	}
	

	function obtenerCuestionariosAsignados($idUsuario)
	{
			//Este query regresa los cuestionarios dados de alta a un maestro
		$query = 
			"select cuestionario_id,asignacion_fechafin,grupo_id,asignacion_horainicio,
				asignacion_horafin,asignacion_tiempo,asignacion_fechainicio,
				grupo_nombre,grupo_clave,grupo_grupo,periodo_nombre,
				cuestionario_nombre,asignacion_estado 
				from asignacion left join cuestionario using(cuestionario_id)
				left join grupo using(grupo_id)
				natural join periodo 
				where
				asignacion_almacenado = 0	AND
				grupo.usuario_id='".$idUsuario."' order by asignacion_fechainicio desc";		

			$data = $this->base->queryArray($query);
			if($data==null) return array();			
			else return $data;	
		
	}
	function obtenerSesionesAsignados($idUsuario)
	{
			//Este query regresa los cuestionarios dados de alta a un maestro
		$query = 
			"select * from sesion left join cuestionario  using (cuestionario_id)
			 	left join periodo  using (periodo_id)
				where
				sesion_habilitado = true and sesion_almacenado=false AND
				sesion.usuario_id='".$idUsuario."' order by sesion_inicio desc";		

			$data = $this->base->queryArray($query);
			if($data==null) return array();			
			else return $data;	
		
	}	
	function obtenerDatosCuestionario($grupo,$cuestionario)
	{
				
				$query = "select 
					periodo_nombre,
					asignacion_almacenado, 
					cuestionario_id,grupo_id,
					asignacion_fechainicio,asignacion_fechafin,asignacion_tiempo as tiempo,					
					asignacion_horainicio,asignacion_horafin,
					asignacion_estado,
					cuestionario_nombre,
					cuestionario_descripcion, 
					cuestionario_estado,
					cuestionario_fuente,
					grupo_nombre as nombre,
					cuestionario_textoinicio,
					cuestionario_textofin,periodo_nombre,
					UNIX_TIMESTAMP(addtime(asignacion_fechainicio,asignacion_horainicio)) as ts_inicio,
					TIME_TO_SEC(asignacion_tiempo) as tiempo_max,
					UNIX_TIMESTAMP(addtime(asignacion_fechafin,asignacion_horafin)) as ts_fin
				from 
					asignacion left join cuestionario using(cuestionario_id)
					left join grupo using(grupo_id)
					natural join periodo 
					where
					grupo_id='".$grupo."'
					AND
					cuestionario_id='".$cuestionario."'
					";
			
			$data = $this->base->queryArrayUnico($query);
			if($data==null) return null;			
			else return $data;	
							
	}
	function obtenerDatosCuestionarioSesion($sesion,$cuestionario)
	{
				
				$query = "select periodo_nombre, 
					sesion_inicio as hora_inicio,
					sesion_fin as hora_fin,
					cuestionario_id,
					sesion_tiempo as tiempo,					
					sesion_habilitado as estado,
					cuestionario_nombre,
					cuestionario_descripcion, 
					cuestionario_estado,
					cuestionario_fuente,
					sesion_nombre as nombre,
					cuestionario_textoinicio,
					cuestionario_textofin,periodo_nombre,
					UNIX_TIMESTAMP(sesion_inicio) as ts_inicio,
					TIME_TO_SEC(sesion_tiempo) as tiempo_max,
					UNIX_TIMESTAMP(sesion_fin) as ts_fin,
					sesion_password as password
				from 
					sesion left join cuestionario using(cuestionario_id)
					natural join periodo 
					where
	
					
					sesion_id='".$sesion."'
					AND
					cuestionario_id='".$cuestionario."'
					";
			
			$data = $this->base->queryArrayUnico($query);
			if($data==null) return null;			
			else return $data;	
							
	}	
	
		function obtenerAplicacionesSesion($sesion,$cuestionario)
		{
			
			$query="	select * from aplicacion
			  left join usuario using (usuario_id)
			where sesion_id='".$sesion."' and			 
			cuestionario_id = '".$cuestionario."'";
			
				
			$data = $this->base->queryArray($query);
			if($data==null) return array();			
			else return $data;			
			
		}	
	
	
		function obtenerAplicacion($usuario,$cuestionario,$grupo)
		{
			
			$query="	select * from aplicacion
			where usuario_id='$usuario'
			AND 
			cuestionario_id = '$cuestionario'
			and grupo_id='$grupo'";
				
			$data = $this->base->queryArrayUnico($query);
			if($data==null) return null;			
			else return $data;			
			
		}	
		
		
		function obtenerCuestionario($id)
		{
			
			$query="select * from cuestionario
			where cuestionario_id=".$id;
			
			$data = $this->base->queryArrayUnico($query);
			if($data==null) return null;			
			else return $data;			
			
		}			
		
		
		function obtenerCuestioriosResueltos($id_usuario,$id_cuestionario,$id_grupo)
		{
			//TODO CHECAR BIEN!
			$query = "select * from aplicacion where 
				 usuario_id = ".$id_usuario." and grupo_id=".$id_grupo." and cuestionario_id = ".$id_cuestionario."";
			$data = $this->base->queryArray($query);
			if($data==null) return null;			
			else return $data;					
			
		}
		
		
		function califica($resultados,$reglas)
			{

				$talleres = array();

										
				foreach($reglas as $key=>$dato)
				{	
					
					$stack = array();										
					
					$arr1 = str_split($dato);
					
					while($arr1)
					{
						//echo $arr1[0].'.';
						

						if($arr1[0]=="+") array_push($stack,(array_pop($stack)+array_pop($stack)));
						else if($arr1[0]=="/")array_push($stack,(array_pop($stack)/array_pop($stack)));
						else if($arr1[0]=="*") array_push($stack,(array_pop($stack)*array_pop($stack)));						
						else if($arr1[0]=="-") array_push($stack,(array_pop($stack)-array_pop($stack)));												
						else if($arr1[0]=="g") array_push($stack,(array_pop($stack)>=array_pop($stack)));																		
						else if($arr1[0]=="'") 
						{	
							array_shift($arr1);
							array_push($stack,$resultados[$arr1[0]]);
						}
						else array_push($stack,$arr1[0]);																		
						array_shift($arr1);

					}
					$talleres[] = $stack[0];
					//	echo "<br>";
				}

					return $talleres;
								
			}
		
}

?>