<?php
/**
 * @date 28 sept 2009
 * @author Cesar Sanchez
 */

include_once('./classes/catalogos.php');
include_once('./classes/usuario.php');
include_once('./classes/sesion.php');
$usuario = new usuario();
$catalogos = new catalogos();						

$_POST['matricula']=$_GET['usuario']; 
$_POST['correo']=''; 
$_POST['nombre']=''; 
$_POST['apellido']=''; 

$usrdata = $usuario->llenaArregloDesdePostSencillo();
$usuario->altaUsuarioSencillo($usrdata);
$usr = $usuario->obtenerUsuario($usrdata['matricula']);
$_SESSION['usuario_id'] =  $usr['usuario_id'];

	$conn = new connect();
	$query = 'select recurso_titulo,tiporecursomedio_id from recursosmoviles.recurso where recurso_id='.$_GET['id'];
	$_SESSION['SAC_RECURSOID'] =$_GET['id'];
	$recurso = $conn->queryArrayUnico($query);
	

	$password= '';
if($recurso['tiporecursomedio_id']==2)$password = 'SICAM_REC0913P';
else $password = 'SICAM_REC0913';
$sesion = new sesion();

$ses = $sesion->obtenerGrupos($password);

if($ses==null){
	$data['template.body']='./templates/sac/forma/registroVulnerabilidad.tp';
	$mensajes['mensaje.error']='Constraseña incorrecta. La sesi�n puede no existir, estar inactiva o estar fuera de tiempo.';
		
}else{
	$data = array('template.body'=>'./templates/sac/content.tp');
	$sesion_id=$ses['sesion_id'];
	$_SESSION['sesion_id'] = $sesion_id;
	$cuestionario_id=$ses['cuestionario_id'];
	$_SESSION['loged'] = true;
	include_once('./modulos/examen/preparaExamen.php');
	
	
		
	$data['cuestionario.nombre'] = $data['cuestionario.nombre'].': <b>'.$recurso['recurso_titulo'].'</b>';
	
}
	
?>