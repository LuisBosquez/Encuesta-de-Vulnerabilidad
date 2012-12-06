<?php


class sesion
{
	var $base = null;
	
	function sesion()
	{
		$this->base = new connect();
	}
	

	function obtenerGrupos($password){
		//Este query regresa los grupos dados de alta por un profe
		$query = "select * from sesion where (now()>=sesion_inicio and now()<=sesion_fin) and sesion_habilitado = true and sesion_almacenado = false and sesion_password='".$password."'";
		
		return $this->base->queryArrayUnico($query);
		
	}
	function llenaArregloCrearDesdePost(){
				$datosAsig['sesion_inicio'] = $_POST['datevalue'].' '.$_POST['hora_i'].":".$_POST['minutos_i'].":00";
				$datosAsig['sesion_fin'] = $_POST['datevalue2'].' '.$_POST['hora_f'].":".$_POST['minutos_f'].":00";		
				$datosAsig['sesion_tiempo'] = $_POST['hora'].":".$_POST['minutos'].":00";		
				$datosAsig['cuestionario_id'] = $_POST['id_cuestionario'];
				$datosAsig['sesion_nombre'] =$_POST['nombre'];
				$datosAsig['sesion_password'] =$_POST['password'];
				$datosAsig['periodo_id'] =$_POST['periodo'];	
				return $datosAsig;
	}
	
	function altaSesion($Arreglo){
		$datos = array(
		'usuario_id'=>$_SESSION['usuario_id'],
		'sesion_habilitado'=>'1',
		'sesion_almacenado'=>'0',
		
		);
		
		$datos = array_merge($Arreglo,$datos);
		
		$this->base->generateSQLInsert('sesion',$datos);
		return mysql_insert_id();
	}	
	
		
}