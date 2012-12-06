<?php
/**
 * Esta clase maneja todos los catalogos o listas pequenas. Para ser usado principalmente en combosboxes
 *
 */

	class catalogos
	{

		var $base = null;

		//inicializa la base de datos
		function catalogos(){
 				$this->base = new connect();
		}

		function obtenerCarrera($id)
		{
			
			$query = "select carrera_nombrecorto,carrera_nombre from carrera where carrera_id =  '".$id."'";		
			$data = $this->base->queryArrayUnico($query);
			if($data==null) return null;			
			else return $data;			
			
		}		
		function obtenerCarrerasConAlumnos(){
			$query = "select carrera_id as value ,carrera_nombre as descripcion from carrera where carrera_estado=1 order by carrera_nombre ";
			return $this->base->queryArray($query);
		}		
		function obtenerCarreras(){
			$query = "select carrera_id as value ,carrera_nombre as descripcion from carrera where carrera_estado=1 order by carrera_nombre ";
			return $this->base->queryArray($query);
		}
		function obtenerListaPermisos(){
			$query = "select permiso_id as value,permiso_nombre as descripcion from permiso where permiso_id >0 and permiso_estado = true order by permiso_id";
			return $this->base->queryArray($query);
		}		
		function obtenerPeriodos(){
			$query = "select periodo_id as value ,periodo_nombre as descripcion from periodo where periodo_estado=1 order by periodo_id";
			return $this->base->queryArray($query);
		}
		function obtenerPeriodoId($id){
			$query = "select periodo_id as value ,periodo_nombre as descripcion from periodo where periodo_id=".$id." order by periodo_id";
			return $this->base->queryArrayUnico($query);
		}		
		function obtenerCuestionariosUsuario(){
			$query = "select cuestionario_id as value , cuestionario_nombre as descripcion from cuestionario where  cuestionario_estado='1' order by  cuestionario_nombre ";
			return $this->base->queryArray($query);
		}
		
		function obtenerGruposUsuario($usuario){
			$query = "select grupo_id as value ,concat(grupo_nombre,' ',grupo_grupo) as descripcion from grupo where grupo_estado='1' and usuario_id='".$usuario."' order by grupo_nombre ";
			return $this->base->queryArray($query);
		}				
		
		 static function obtenerDias(){
			$data = array();
			for($i=1;$i<32;$i++){
				if($i<10) $temp = "0";
					else $temp ="";
					$data[] = array('value'=>$temp.$i,'descripcion'=>$temp.$i);
			}	return $data;		
			
			
		}
		
		/**
		 * Obtener los meses del año
		 *
		 * @return Arreglo de String de meses
		 */
		static function obtenerMeses(){			
			$data = array();
			for($i = 1 ; $i <= 12; $i++){
				if($i<=9)$temp="0";
				else $temp="";
				$data[] = array('value'=>$temp.$i,'descripcion'=>$temp.$i);
			}	
			return $data;					
		}		
		
		/**
		 * Obtener los años desde 1950 a la fecha
		 *
		 * @return Arreglo de String de años
		 */
		static function obtenerYear(){			
			$data = array();			
			for($i = 1950 ; $i <= date("Y"); $i++)
				$data[] = array('value'=>$i,'descripcion'=>$i);				
			return $data;		
		}		
		
		static function obtenerHorasDia(){			
			$data = array();
			for($i = 7 ; $i <= 23; $i++){
				if($i<=9)$temp="0";
				else $temp="";
				$data[] = array('value'=>$temp.$i,'descripcion'=>$temp.$i);
			}	
			return $data;	
		}
		
		static function obtenerHoras($lim){			
			$data = array();
			for($i = 0 ; $i <= $lim; $i++){
				if($i<=9)$temp="0";
				else $temp="";
				$data[] = array('value'=>$temp.$i,'descripcion'=>$temp.$i);
			}	
			return $data;	
		}
		static function obtenerMinutosCino(){			
			$data = array();
			for($i = 0 ; $i <= 60; $i=$i+5){
				if($i<=9)$temp="0";
				else $temp="";
				$data[] = array('value'=>$temp.$i,'descripcion'=>$temp.$i);
			}	
			return $data;	
		}		

		static function obtenerCuartosDeHora(){			
			$data = array();
			$data[] = array('value'=>'00','descripcion'=>'00');
			$data[] = array('value'=>'15','descripcion'=>'15');
			$data[] = array('value'=>'30','descripcion'=>'30');
			$data[] = array('value'=>'45','descripcion'=>'45');									
			return $data;	
		}		
		
		
	}

?>