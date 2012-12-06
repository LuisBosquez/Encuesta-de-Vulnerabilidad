<?php
/**
 * Modulo de usuarios. Mostrar usuarios, filtrarlos y crear usuarios no Alumnos. 
 * @name Cesar Sanchez
 * @date 21/AGO/2007
 */	
include_once('./classes/usuario.php');
include_once('./classes/catalogos.php');
$usuario = new usuario();
$catalogos = new catalogos();

if(isset($_GET['alta'])){
	$mensajes['menu.location']= 'Crear Usuario';
	
	if($_POST){//
		$_POST['contrasena'] = get_magic_quotes_gpc() ? $_POST['contrasena'] : addslashes($_POST['contrasena']);
		$_POST['contrasena2'] = get_magic_quotes_gpc() ? $_POST['contrasena2'] : addslashes($_POST['contrasena2']);
	
		$usuarioAnt = $usuario->obtenerUsuario($_POST['matricula']);
		
		
		
		
		if($usuarioAnt==null && $_POST['contrasena'] ==$_POST['contrasena2'] &&  $_POST['tipo']!=USUARIO_DIRECTOR){

			$usrdata = $usuario->llenaArregloDesdePostUsuario();
			$usuario->altaUsuarioAvanzado($usrdata);
			content::armaMensaje($data,'Exito','El usuario fue dado de alta');				
	
		}
		else if($_POST['tipo']==USUARIO_DIRECTOR && $_POST['carrera']!=-1 ){
			$usrdata = $usuario->llenaArregloDesdePostUsuario();
			$usuario->altaUsuarioAvanzado($usrdata);
			content::armaMensaje($data,'Exito','El usuario fue dado de alta');				
	
		}else{
			$data = array_merge($data,$usuario->llenaFormaDesdePost());	
			$data['contrasena']= $_POST['contrasena'];
			$data['contrasena2']=		$_POST['contrasena2'];	
			$data['forma.tipousuario'] = content::getComboOpciones('tipo',$catalogos->obtenerListaPermisos());													
			$data['template.body']='./templates/sac/usuarios/crearUsuario.tp';		
			$data['forma.carrera'] = content::getComboOpciones('carrera',$catalogos->obtenerCarreras());														
			
			if($_POST['tipo']==USUARIO_DIRECTOR && $_POST['carrera']==-1 )$mensajes['mensaje.error']='ERROR. Usuario tipo director de carrera debe de tener una carrera asignada.';
			else if($_POST['contrasena'] !=$_POST['contrasena2'])$mensajes['mensaje.error']='ERROR. La confirmaci&oacute;n de la contrase&ntilde;a no coincide. ';			
			else if($usuarioAnt!=null)$mensajes['mensaje.error']='ERROR. Usuario ya existe en el sistema.';			
		}
	}else{
		$data['forma.carrera'] = content::getComboOpciones('carrera',$catalogos->obtenerCarreras());														
		$data['contrasena']= '';$data['contrasena2']=	'';		
		$data = array_merge($data,$usuario->inicializaForma());	
		$data['forma.tipousuario'] = content::getComboOpciones('tipo',$catalogos->obtenerListaPermisos());													
		$data['template.body']='./templates/sac/usuarios/crearUsuario.tp';		
	}
}else if(isset($_GET['usuarios'])){
	$mensajes['menu.location']= 'Lista Usuarios';
	$usrs = $usuario->obtenerUsuariosAlumnosActivos();
	$usrsarray = array();
	foreach($usrs as $element){
		$temp = array();
		$temp[] = $element['usuario_usuario'];	
		$temp[] = $element['usuario_nombre'].' '.$element['usuario_apellido'];
		$temp[] = $element['usuario_correo'];
		$temp[] = $element['usuario_nacimiento'];		
		
		if(false && $element['permiso_id']==0)$temp[] = $element['carrera_nombrecorto'];
		else $temp[]='';
		$usrsarray[]=$temp;								
	}	
	$data['tabla.usuarios']=content::generaFilaDeTabla($usrsarray,3);		
				$data['template.body']='./templates/sac/usuarios/usuarios.tp';	

}
			
?>