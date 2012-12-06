<?php
	include_once('./classes/catalogos.php');
	include_once('./classes/usuario.php');
	$usuario = new usuario();
	
	if($_GET && isset($_GET['editar']))
	{
	//TODO: Arreglar todo este modulo
		
		if($_POST && $_POST['boton'] == "Cambiar")
		{
			$usr = $usuario->obtenerUsuarioId($_SESSION['usuario_id']);
			
			$a = substr($_POST['fecha_nac'],0,4);
			$b = substr($_POST['fecha_nac'],5,2);
			$c = substr($_POST['fecha_nac'],8,2);
			
			if($a!="" && $b!=""&&$c!=""&& checkdate($a,$b,$c))
			{
			
	
				$datsnuevos = $usuario->llenaArregloDesdePostEdit();
				
				
				$usuario->editarUsuario($datsnuevos,$usr['id_usuario']);
				
				$mensajes['mensaje.error']='Cambio exitoso';
				$data['template.contenido']='./templates/sac/usuario.tp';
				
				$data['usuario.nombre']=$usr['nombre'];
				$data['usuario.apellido']=$usr['apellido'];
				$data['usuario.nacimiento']=$usr['fecha_nac'];
				$data['usuario.correo']=$usr['correo'];
				$data['usuario.alta']=$usr['fecha_alta'];			
			}
			else
			{
				$mensajes['mensaje.error']='Fecha incorrecta';
				$data['template.contenido']='./templates/sac/forma/usuarioEdit.tp';
					
				$data['usuario.nombre']=$usr['nombre'];
				$data['usuario.apellido']=$usr['apellido'];
				$data['usuario.nacimiento']=$usr['fecha_nac'];
				$data['usuario.correo']=$usr['correo'];
				$data['usuario.alta']=$usr['fecha_alta'];
					
				
			}
			
		}
		else if($_POST && $_POST['boton'] == "Cancelar")
		{
			$mensajes['mensaje.error']='Accion cancelada';
			
			$usr = $usuario->obtenerUsuarioId($_SESSION['usuario_id']);
			$data['template.contenido']='./templates/sac/usuario.tp';
			
			$data['usuario.nombre']=$usr['nombre'];
			$data['usuario.apellido']=$usr['apellido'];
			$data['usuario.nacimiento']=$usr['fecha_nac'];
			$data['usuario.correo']=$usr['correo'];
			$data['usuario.alta']=$usr['fecha_alta'];			
			
	
		}
		else
		{
			$usr = $usuario->obtenerUsuarioId($_SESSION['usuario_id']);
			
			$data['template.contenido']='./templates/sac/forma/usuarioEdit.tp';
				
			$data['usuario.nombre']=$usr['nombre'];
			$data['usuario.apellido']=$usr['apellido'];
			$data['usuario.nacimiento']=$usr['fecha_nac'];
			$data['usuario.correo']=$usr['correo'];
			$data['usuario.alta']=$usr['fecha_alta'];
	
		}		
		
		
	}

	else if($_GET && isset($_GET['password'])){
		$usr = $usuario->obtenerUsuarioId($_SESSION['usuario_id']);
		$data['template.contenido']='./templates/sac/forma/passwordEdit.tp';
		
		if($_POST )	{				
			if($_POST['boton'] == "Cancelar")$mensajes['mensaje.error']='Accion cancelada';
			else{
				$pw1 = get_magic_quotes_gpc() ? $_POST['P1'] : addslashes($_POST['P1']); 
				$pw2 = get_magic_quotes_gpc() ? $_POST['P2'] : addslashes($_POST['P2']); 
				$pw3 = get_magic_quotes_gpc() ? $_POST['P3'] : addslashes($_POST['P3']); 				
 	
				if(strlen($pw1)==0 || strlen($pw2)==0 || strlen($pw3)==0 )						
					$mensajes['mensaje.error']='Todos los campos deben de tener valor';
				else if($pw2 != $pw3 )
					$mensajes['mensaje.error']='Confirmacion de password incorrecta';
				else{
							$pw1 = md5($pw1);
					 		$pw2 = md5($pw2);

							if($pw1==$usr['usuario_password']){
								$datsnuevos['usuario_password']=$pw2;
								$usuario->editarUsuario($datsnuevos,$usr['usuario_id']);
								$mensajes['mensaje.error']='Password cambiado';
												
							}else{
								$mensajes['mensaje.error']='Contrasena anterior incorrecta';
							}
						}
				}
				
				$data['template.contenido']='./templates/sac/usuario.tp';
				
				$data['usuario.nombre']=$usr['usuario_nombre'];
				$data['usuario.apellido']=$usr['usuario_apellido'];
				$data['usuario.alta']=$usr['usuario_alta'];			
				
		
			}			
				
	}
	
	else
	{

		
		$usr = $usuario->obtenerUsuarioId($_SESSION['usuario_id']);
		$data['template.contenido']='./templates/sac/usuario.tp';
		
		$data['usuario.nombre']=$usr['usuario_nombre'];
		$data['usuario.apellido']=$usr['usuario_apellido'];
		$data['usuario.nacimiento']=$usr['usuario_nacimiento'];
		$data['usuario.correo']=$usr['usuario_correo'];

	
	}
	$mensajes['menu.location']='Preferencias de Usuario';
	
?>
