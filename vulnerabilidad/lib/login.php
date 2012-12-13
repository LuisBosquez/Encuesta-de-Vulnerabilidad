<?php
class login
{
	
		var $base = null;

		//inicializa la base de datos
		function login()
		{
				$this->base = new connect();
		}
		
		/**
		 * Verifica la existencia del usuario y la correspondencia con su password. 
		 * Guarda en las variables de la sesion los datos basicos. La sesion ya debe estar inicializada. 
		 * 
		 * @param String usuario
		 * @param String password
		 * @return verdadero si se registro exitosamenes
		 */
		function logon($user,$pass){
			$user = trim($user);
			$pass = trim($pass);
			$pass  = get_magic_quotes_gpc() ? $pass : addslashes($pass);								
            // primero comprobamos el password
			if($user == NULL || $pass == NULL || $pass == "") return null;
            $pass = md5($pass);           
	 		$usuarioObject = new usuario();			
			$data = $usuarioObject->verificaUsuario($user,$pass);
			if($data){				
				$_SESSION['usuario_id'] = $data['usuario_id'];
				$_SESSION['usuario_usuario'] = $data['usuario_usuario'];
				$_SESSION['usuario_nivel'] = $data['permiso_id'];
				$_SESSION['usuario_nombre'] = $data['usuario_nombre'].' '.$data['usuario_apellido'];				
				$_SESSION['carrera_id'] = $data['carrera_id'];
				
				
				
				$_SESSION['loged'] = true;			
				return true;
			}
			return false;                                   
	}

	function logout()
	{
		
		$_SESSION = array();
		session_destroy();
		header("Location: ".URL."");
		
		
	}
	
	
	function isLoged()
	{
		if(isset($_SESSION['loged'])&&$_SESSION['loged'] == true)return true;
		else header("Location: ".URL."usuario.php?logout");
		
	}
		
	
	
}


?>