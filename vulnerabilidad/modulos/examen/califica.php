<?php
/**
 * Califica el examen
 * @date JUNIO 2007
 * @author Cesar
 */

	if(isset($_SESSION['contestando']) && $_SESSION['contestando']==true)
	{
	
	
		$idGrupo = - 1;
		$idSesion = - 1;  
		if(isset($_SESSION['id_grupo']))$idGrupo=$_SESSION['id_grupo'];
		if(isset($_SESSION['sesion_id']))$idSesion=$_SESSION['sesion_id'];		
		
		$idCuestionario = $_SESSION['cuestionario_id'];	
		$cuestionario = $_SESSION['cuestionario'] ;
		$tiempoInicial = $_SESSION['tiempo_ini'] ;
		$tiempoFinal = $_SESSION['tiempo_fin']  ;
		$tiempoMaximo = $_SESSION['tiempo_max'] ;
		$cuestionario = $_SESSION['cuestionario'] ;
		
		//incializamos algunas variables
		$respuestas = null;
		$preguntasPorPagina  = 20;
		$totalPreguntas = $cuestionario->obtenerCantidadPreguntas();
		$totalPaginas = $cuestionario->obtenerCantidadPaginas($preguntasPorPagina);
		
		
		//CARGAMOS EL TIEMPO Y HACEMOS CALCULOS
		//TODO: QUE PASA CUANDO SE ACABA EL TIEMPO
		if(isset($_SESSION['horaInicio']))$horaInicio=$_SESSION['horaInicio'];
		else $_SESSION['horaInicio'] = $horaInicio=time();
		$horaInicio=$_SESSION['horaInicio'];
		$tiempoTranscurrido = time()-$horaInicio;
		$dateTranscurrido = mktime(0,0,$tiempoTranscurrido);
		
		//Inicializamos o cargamos las respuestas
		if(isset($_SESSION['respuestasAlm']))$respuestas = $_SESSION['respuestasAlm'];
		if($respuestas==null)//INICIALIZAMOS
		{
			$respuestas  = array();
			$respuestas = array_fill(0,$totalPreguntas,'-1');
		}
			else if($_POST && isset($_POST['resolviendo'])){//GUARDAMOS LOS RESULTADOS
				$calificar = $_POST;
				unset($calificar['resolviendo']);
				$accionPagina = $calificar['Submit'];
				unset($calificar['Submit']);
				
				foreach ($calificar as $key => $value){
					$respuestas[$key] = $value;
					
				}
	
		}
				else if($_POST && isset($_POST['resolviendo'])){//GUARDAMOS LOS RESULTADOS
				$calificar = $_POST;
				unset($calificar['resolviendo']);
				$accionPagina = $calificar['Submit'];
				unset($calificar['Submit']);
				foreach ($calificar as $key => $value){
					if($cuestionario->obtenerFormaIdenticadorPregunta()==2){
						$key = substr($key,2);
						$respuestas[$key] = $value;
					}
					else{
						$respuestas[$key] = $value;
					}
	
				}
			}
		$_SESSION['respuestasAlm'] = $respuestas;
		
				/**
				 * TERMINA
				 */
		
		
		
		
		$res_final = array(); // calificacion final
		$res_categoria = array_fill(0,$cuestionario->obtenerNumeroCategorias(),0); // categoria final
		$res_final_final = 0;
		
		require_once('./classes/resultados.php');
		$res = new resultados();
		$idUsr = $_SESSION['usuario_id'];
		
		if($cuestionario->obtenerFormaCalificar()==1 )
		{
					
			for($i = 0 ;  $i<$totalPreguntas-1;$i++){
				$valor = $cuestionario->obtenerValorPregunta($i);
				$categoria = $cuestionario->obtenerCategoriaPregunta($i);
				if($respuestas[$i] == $valor)$res_final[$i] = 1;
				else $res_final[$i]= 0;
				$res_final_final = $res_final_final + $res_final[$i];
				$res_categoria[$categoria]+=$res_final[$i];					
			}
			$id = $res->almacena($idUsr,$idCuestionario,$idGrupo,$dateTranscurrido,$res_categoria);
			
		}
		else if($cuestionario->obtenerFormaCalificar()==2 && $idCuestionario>=7)
		{
			
			
			$i = 0;
			foreach($respuestas as $key=>$item){
					$res_categoria[$key]=$item;					
			}
			$res_categoria = array_merge(array('recurso_id'=>$_SESSION['SAC_RECURSOID'] ),$res_categoria);			
			
			$id = $res->almacenaValue($idUsr,$idCuestionario,$idGrupo,$dateTranscurrido,$res_categoria,$idSesion);
			
		}		
		else if($cuestionario->obtenerFormaCalificar()==2){
		
			for($i = 1 ;  $i<=$totalPreguntas;$i++){
					$res_categoria[$i]=$respuestas["p".$i];					
			}			
			$id = $res->almacenaValue($idUsr,$idCuestionario,$idGrupo,$dateTranscurrido,$res_categoria,$idSesion);
			
		}

		


	
	
		unset($_SESSION['cuestionario']);
		unset($_SESSION['tiempo_ini']);
		unset($_SESSION['horaInicio']);
		unset($_SESSION['tiempo_max']);
		unset($_SESSION['tiempo_fin']);			
		unset($_SESSION['horaInicio']);
		unset($_SESSION['respuestasAlm']);
		unset($_SESSION['id_cuestionario']);
		unset($_SESSION['id_grupo']);	
		unset($_SESSION['contestando']);	
		unset($_SESSION['paginaAct']);
		unset($_SESSION['sesion_id']);
		unset($_SESSION['CUESTIONARIO_EXPRESS']);
				
		require_once('./lib/encryption.php');
		$enc = new Crypto();
		$id = $enc->encrypt($id);
		header("Location: examen.php?resultado&idApl=".$id);
		//content::armaMensaje($data,"Error","No existen datos de cuestionario. Contacte al administrador. <a href='usuario.php'>INICIO</a>");
		
		
		
	}
	else
	{
		content::armaMensaje($data,"Error","No existen datos de cuestionario. Contacte al administrador. <a href='usuario.php'>INICIO</a>");
		
	}
?>