<?PHP
//21 AGO 07
	class usuario
	{
		var $base = null;

		//inicializa la base de datos
		function usuario()
		{
				$this->base = new connect();
		}


		/**
		 * Inserta un usuario en la base de datos
		 * @param arreglo Datos del usuario a insertar
		 */
		function altaUsuario($Arreglo){
			$user['usuario_usuario']=$Arreglo['matricula'];
			$user['usuario_correo']=$Arreglo['correo'];
			$user['usuario_nombre']=$Arreglo['nombre'];
			$user['usuario_apellido']=$Arreglo['apellido'];
			$user['usuario_sexo']=$Arreglo['sexo'];
			$user['carrera_id']=$Arreglo['carrera'];
			$user['usuario_password']=$Arreglo['password'];
			$user['usuario_nacimiento']=$Arreglo['fecha_nac'];
			$user['permiso_id']='0';
		
			$this->base->generateSQLInsert('usuario',$user);
		}		
		/**
		 * Inserta un usuario en la base de datos
		 * @param arreglo Datos del usuario a insertar
		 */
		function altaUsuarioSencillo($Arreglo){
			
			$query = "select * from usuario where usuario_usuario='".$Arreglo['matricula']."'";			
			$usr = $this->base->queryArrayUnico($query);
			
			if($usr==null){
				$user['usuario_usuario']=$Arreglo['matricula'];
				$user['usuario_correo']=$Arreglo['correo'];
				$user['usuario_nombre']=$Arreglo['nombre'];
				$user['usuario_apellido']=$Arreglo['apellido'];
				$user['usuario_password']='-';
				$user['permiso_id']='0';
				
			
				$this->base->generateSQLInsert('usuario',$user);			
			}
			else{//el usuario ya esta dado de alta
				
			}
			
			

		}			
		/**
		 * Inserta un usuario en la base de datos con permiso
		 * @param arreglo Datos del usuario a insertar
		 */
		function altaUsuarioAvanzado($Arreglo){
			

			$user['usuario_usuario']=$Arreglo['matricula'];
			$user['usuario_correo']=$Arreglo['correo'];
			$user['usuario_nombre']=$Arreglo['nombre'];
			$user['usuario_apellido']=$Arreglo['apellido'];
			$user['carrera_id']=$Arreglo['carrera'];
			$user['usuario_password']=$Arreglo['password'];
			$user['permiso_id']=$Arreglo['permiso'];
			if($Arreglo['carrera']==-1)$Arreglo['carrera']='0';			
			$user['carrera_id']=$Arreglo['carrera'];			
			$this->base->generateSQLInsert('usuario',$user);
		}			
		/**
		 * Busca un usuario por el nombre de usuario o matricula en este caso
		 * 
		 * @param String Nombre del usuario
		 * @return Un arreglo con los datos del usuario o nulo si es que este no existe
		 */
		function obtenerUsuario($user){			
			$query = "select * from usuario where usuario_usuario = '".$user."'";	
			$data = $this->base->queryArrayUnico($query);
			if($data==null) return null;			
			else return $data;						
		}



		
		function obtenerUsuarioId($id){			
			$query = "select * from usuario where usuario_id = '".$id."'";		
			$data = $this->base->queryArrayUnico($query);
			if($data==null) return null;			
			else return $data;			
			
		}
		function obtenerUsuarioUsuario($usuario)
		{
			
			$query = "select * from usuario where usuario_usuario = '".$usuario."'";		
			$data = $this->base->queryArrayUnico($query);
			if($data==null) return null;			
			else return $data;			
			
		}
							
	function obtenerUsuariosPorCarrera($carrera)
		{
			
			$query = "select * from usuario where permiso_id=0 and carrera_id=".$carrera." order by usuario_usuario";		
			$data = $this->base->queryArray($query);
			if($data==null) return array();			
			else return $data;			
			
		}
	
		function activaUsuario($correo,$clave)
		{
			$query = "select usuario.* from usuario where correo ='".$correo."'";		
			$data = $this->base->queryArrayUnico($query);	

			if($data['estado']!='-1')return false;

			$claveOriginal = md5($data['usuario'].$data['nombre'].$data['password']);
			
			
			if($claveOriginal==$clave)
			{
				$query = 'UPDATE usuario SET estado=\'1\' WHERE id_usuario='.$data['id_usuario'];
				$this->base->query($query);
				return true;
			}
			else return false;
			
			
		}
		
		
		function verificaUsuario($usuario,$contrasena)
		{
			
			
			if(strlen(trim($usuario))==0)return null;
			if(strlen(trim($contrasena))==0)return null;
			
			$query = "select * from usuario where usuario_estado= true and usuario_usuario = '".$usuario."' and usuario_password = '".$contrasena."'";					
			$data = $this->base->queryArrayUnico($query);

			if($data==null) return null;			
			else return $data;
					
			
		}
		
		
		function obtenerOpcionesAdmin($tipo)
		{
			
			
			
			$query = "select
				 permiso_id,permiso_titulo,permiso_clave,permiso_descripcion,permisocategoria_descripcion
				 from permiso,permisocategoria
				 where permiso_clave <> '' and permiso.permiso_permisocategoria_id = permisocategoria.permisocategoria_id and permiso.tipousuario_id <=".$tipo." order by permisocategoria.permisocategoria_id,permiso_id";		

			$data = $this->base->queryArray($query);
			if($data==null) return null;			
			else return $data;
					
			
		}		
		function obtenerCantidadOpcionesAdmin($tipo)
		{
			
			
			
			$query = "select permisocategoria_descripcion as descripcion,count(*) as valor from permiso,permisocategoria
			 where permiso_clave <> '' and permiso.permiso_permisocategoria_id = permisocategoria.permisocategoria_id and permiso.tipousuario_id <=".$tipo." 
			 group by permisocategoria_descripcion order by permisocategoria.permisocategoria_id,permiso_id		
					";
			$data = $this->base->queryArray($query);
			if($data==null) return null;			
			else return $data;
					

		}		


		function llenaArregloDesdePost(){

			$_usuario['matricula'] = $_POST['matricula']; 
			$_usuario['correo'] = $_POST['correo']; 
			$_usuario['nombre'] = $_POST['nombre']; 
			$_usuario['apellido'] = $_POST['apellido']; 
			$_usuario['sexo'] = $_POST['sexo']; 	
			$_usuario['carrera'] = $_POST['carrera'];					
			$_usuario['password'] = md5($_POST['password']); 
			$_usuario['fecha_nac'] = $_POST['dateYear'].'-'.$_POST['dateMonth'].'-'.$_POST['dateDay']; 
			return $_usuario;
		}
			function llenaArregloDesdePostSencillo(){

			$_usuario['matricula'] = $_POST['matricula']; 
			$_usuario['correo'] = $_POST['correo']; 
			$_usuario['nombre'] = $_POST['nombre']; 
			$_usuario['apellido'] = $_POST['apellido']; 
			return $_usuario;
		}		
		function llenaArregloDesdePostUsuario(){
			$_usuario['permiso'] = $_POST['tipo']; 
			$_usuario['matricula'] = $_POST['matricula']; 
			$_usuario['correo'] = $_POST['correo']; 
			$_usuario['nombre'] = $_POST['nombre']; 
			$_usuario['apellido'] = $_POST['apellido']; 				
			$_usuario['carrera'] = $_POST['carrera']; 				
			$_usuario['password'] = md5($_POST['contrasena']); 
			return $_usuario;
		}		

		function llenaArregloDesdePostEdit()
		{
			$_usuario['nombre'] = $_POST['nombre']; 
			$_usuario['apellido'] = $_POST['apellido']; 
			$_usuario['fecha_nac'] =  $_POST['fecha_nac']; 
			return $_usuario;
		}
				
		
		function inicializaForma()
		{
			$datosCurso['usuario.apellido'] = '';
			$datosCurso['usuario.nombre'] = '';
			$datosCurso['usuario.correo'] = '';
			$datosCurso['usuario.usuario'] = '';
			
			return $datosCurso;
			
		}			
			
		function llenaFormaDesdePost()
		{
			$datosCurso['usuario.apellido'] = $_POST['apellido'];
			$datosCurso['usuario.nombre'] = $_POST['nombre'];
			$datosCurso['usuario.correo'] = $_POST['correo'];	
			$datosCurso['usuario.usuario'] = $_POST['matricula'];	
			$datosCurso['usuario.carrera'] = $_POST['carrera'];				
			
			
			return $datosCurso;			
		}	

		function editarUsuario($cambios, $id)
		{
			
			$this->base->generateSQLUpdate('usuario',$cambios,' usuario_id =\''.$id.'\'' );
			
		}
		function obtenerUsuariosAlumnosActivos(){			
			//$query = "select * from usuario natural join carrera where usuario_estado=true order by usuario_usuario";
			$query = "select * from usuario  where usuario_estado=true order by usuario_usuario";	
			$data = $this->base->queryArray($query);
			if($data==null) return null;			
			else return $data;						
		}

		
			
	}		
?>