<?php
/**
 * Modulo que maneja el registro de los alumnos desde la pagina principal
 * @date 21 AGO 2007
 * @author Cesar Sanchez
 */

include_once('./classes/catalogos.php');
include_once('./classes/usuario.php');
include_once('./classes/sesion.php');
$usuario = new usuario();
$catalogos = new catalogos();						


		$data = array('template.body'=>'./templates/sac/content.tp');
		//$sesion_id=$ses['sesion_id'];
		$_SESSION['sesion_id'] = 21;
		$sesion_id = 21;
		$cuestionario_id=2;
		$_SESSION['loged'] = true;
		include_once('./modulos/examen/preparaExamen.php');

		
?>