<?php

	class validacion{
		
			var $estatus = true;
			var $mensaje = "";
			//inicializa la base de datos
		function validacion()
		{

        		require_once("../classes/connect.php");
				$this->base = new connect();


		}		
		/**
		 * Valida un campo y regresa un mensajes
		 *
		 * @param unknown_type $dato
		 * @param int $min
		 * @param int $max
		 * @param boolean $requerido
		 * @param string $tipo
		 */
		 function valida($nombre, $dato, $min, $max, $requerido, $tipo)
		{
				
			$dato = trim($dato);

			if(!($requerido && $dato && strlen($dato)>0))
			{
				$this->mensaje  = "El campo <i>".$nombre."</i> debe contener datos";
				$this->estatus = false;
				return;				
			}
			
			if(strlen($dato)<$min || strlen($dato)>$max)
			{				
				$this->mensaje  = "Tamano incorrecto en el campo <i>".$nombre."</i>";
				$this->estatus = false;
				return;
			}
			
			
			
			switch ($tipo) {
			
				case '':
					;
					break;
				
				default:
					;
				break;
			}

			
			return true;
			
		}
		
		 function validaCombo($nombre, $dato,$requerido)
		{

				if($requerido && $dato==-1)
				{
			
					$this->mensaje  = "Debe seleccionar un elemento en el campo <i>".$nombre."</i>";
					$this->estatus = false;			
				}
			
			
			return true;
			
		}
			 function validaContrasena($nombre, $dato,$dato2)
		{

				$dato = trim($dato);			
				$dato2 = trim($dato2);							
				
					
				if($dato != $dato2)
				{
			
					$this->mensaje  = "La confirmación del campo <i>".$nombre."</i> es erronea";
					$this->estatus = false;			
				}
			
			
			return true;
			
		}				
		
		 function validaFormaUsuarioRegistra()
		{
			
			
				$this->verificaUsuario($_POST['correo'],$_POST['usuario']);
				if($this->estatus)$this->valida('usuario',$_POST['usuario'],1,100,true,"");
				if($this->estatus)$this->valida('contrasena',$_POST['contrasena'],1,100,true,"");
				if($this->estatus)$this->valida('confirmar contrasena',$_POST['contrasena2'],1,100,true,"");				
				if($this->estatus)$this->valida('nombre',$_POST['nombre'],1,100,true,"");
				if($this->estatus)$this->valida('correo',$_POST['correo'],1,50,true,"");
				if($this->estatus)$this->validaContrasena('contrasena',$_POST['contrasena'],$_POST['contrasena2']);
					
				
	
		}
		
		function verificaUsuario($correo,$usuario)
		{
			
			$query = "select * from usuarios where usuarios_usuario = '".$usuario."'";		
			$data = $this->base->queryArrayUnico($query);		
			if($data!=null)
			{
					$this->mensaje  = "El usuario <i>".$usuario."</i> ya existe";
					$this->estatus = false;			
				
			}
	//		else return $data;			
			$query = "select * from usuarios where  usuarios_correo = '".$correo."'";
			$data = $this->base->queryArrayUnico($query);		
			if($data!=null) 
			{
					$this->mensaje  = "El correo <i>".$correo."</i> ya tiene asiganada una cuenta";
					$this->estatus = false;			
				
			}


			
		}		
		
			 function validaArchivoForma()
		{
			
				
				if($this->estatus)$this->valida('nombre',$_POST['nombre'],1,100,true,"");
				if($this->estatus)$this->valida('descripcion',$_POST['descripcion'],1,100000,true,"");
//				if($this->estatus)$this->valida('direccion',$_POST['file'],1,100,true,"");		
				if($this->estatus)$this->validaCombo('categoria',$_POST['categoria'],true);
				if($this->estatus)$this->validaCombo('tipo',$_POST['tipo'],true);				

				
			
			
		}		
		
			 function validaCodigoForma()
		{
			
				
				if($this->estatus)$this->valida('nombre',$_POST['nombre'],1,100,true,"");
				if($this->estatus)$this->valida('descripcion',$_POST['descripcion'],1,100,true,"");
				if($this->estatus)$this->valida('codigo',$_POST['codigo'],1,100000000,true,"");		
				if($this->estatus)$this->validaCombo('lenguaje',$_POST['lenguaje'],true);

				
			
			
		}		
				
	}


?>