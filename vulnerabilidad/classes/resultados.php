<?php

	class resultados
	{

		var $base = null;

		//inicializa la base de datos
		function resultados()
		{
 				$this->base = new connect();
		}
				
		//Regresa los resultaods
		function obtenerResultados($id)
		{
			
			$query = "select * from resultados where aplicacion_id = ".$id." ";
			
			$test = $this->base->queryArray($query);
			return $test;
		}
		
		//Regresa los resultados ordenados por la sección de forma númerica
		function obtenerResultadosSeccionesNumericas($id)
		{
			
			$query = "select *,CAST(resultados_seccion as UNSIGNED) as ressec  from resultados where aplicacion_id = ".$id." order by ressec";
			$test = $this->base->queryArray($query);
			return $test;
		}
		
		function obtenerAplicacion($id)
		{
			
			$query = "select * from aplicacion where aplicacion_id = ".$id." ";
			$test = $this->base->queryArrayUnico($query);
			return $test;
		}		
		
		//TODO armar para grupo
		function elimina($aplicacion)
		{
			
				$this->base->query("DELETE FROM resultados WHERE aplicacion_id='".$aplicacion."'");
				 $this->base->query("DELETE FROM aplicacion WHERE aplicacion_id='".$aplicacion."'");
				 
			
			
		}
		
		function almacena($usuario, $idCuestionario, $idGrupo, $tiempo, $resultados)
		{
			
			$dato['usuario_id'] = $usuario;
			$dato['cuestionario_id'] = $idCuestionario;
			$dato['aplicacion_fecha'] =  date("Y-m-d");
			$dato['aplicacion_tiempo']	=date("H:i:s",$tiempo);										
			$dato['grupo_id'] = $idGrupo;			
			
			$this->base->generateSQLInsert('aplicacion',$dato);
			
			$dato = array();
			$lastid = mysql_insert_id();
			
			foreach($resultados as $key=>$item)
			{

				$dato['aplicacion_id'] = $lastid.'';
				$dato['resultados_seccion'] = $key.'';
				$dato['resultados_grade'] = $item.'';

				$this->base->generateSQLInsert('resultados',$dato);
			}
			
			return $lastid;
		
			
		}
		function almacenaValue($usuario, $idCuestionario, $idGrupo, $tiempo, $resultados,$sesion = -1)
		{

			
			$dato['usuario_id'] = $usuario;
			$dato['cuestionario_id'] = $idCuestionario;
			$dato['aplicacion_fecha'] =  date("Y-m-d");
			$dato['aplicacion_tiempo']	=date("H:i:s",$tiempo);										
			
			if($idGrupo!=-1)$dato['grupo_id'] = $idGrupo;
			if($sesion!=-1)$dato['sesion_id'] = $sesion;			
			
			$this->base->generateSQLInsert('aplicacion',$dato);
			
			$dato = array();
			$lastid = mysql_insert_id();
			
			foreach($resultados as $key=>$item)
			{

				$dato['aplicacion_id'] = $lastid.'';
				$dato['resultados_seccion'] = $key.'';
				
				if(is_array($item)){
					$end = " # ";
					foreach($item as $temp)$end.=$temp." / ";
					$item = $end;
				}
				
				$dato['resultados_value'] = $item.'';
				
				

				$this->base->generateSQLInsert('resultados',$dato);
			}
			
			return $lastid;
		
			
		}

		
	}	
?>