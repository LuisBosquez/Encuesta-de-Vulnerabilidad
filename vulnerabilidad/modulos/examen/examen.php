<?php
/**
 * Sac 2.0 Version ITESM CCM
 * Modulo que maneja lo relacionado a contestar el examen
 * @author  Cesar Sanchez <oropeza@gmail.com>
 * @date  21 Agosto 07
 */

		//template
		$data['template.contenido']='./templates/sac/examen/examen.tp';

		//cargamos variables de la sesion
		//print_r($_SESSION);
		$idGrupo = - 1; 
		if(isset($_SESSION['id_grupo']))$idGrupo=$_SESSION['id_grupo'];
		$idCuestionario = $_SESSION['cuestionario_id'];
		$cuestionario = $_SESSION['cuestionario'] ;
		$tiempoInicial = $_SESSION['tiempo_ini'] ;
		$tiempoFinal = $_SESSION['tiempo_fin']  ;
		$tiempoMaximo = $_SESSION['tiempo_max'] ;

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
		$accionPagina = "";

		//Inicialozamos o cargamos las respuestas
		if(isset($_SESSION['respuestasAlm']))$respuestas = $_SESSION['respuestasAlm'];

		if($respuestas==null){//INICIALIZAMOS							
			$respuestas  = array();
			for($i = 0; $i < $totalPreguntas;$i++){				
				$pregunta = $cuestionario->obtenerPregunta($i);
				
				
				if(!isset($pregunta['id'])){
					$respuestas[$i] = '-1';
				}else if(!isset($pregunta['respuesta'])){
					$respuestas[$pregunta['id']] = '999';
				}
				else if(is_array($pregunta['respuesta'])){		
					if(isset($pregunta['noRespuesta']))$respuestas[$pregunta['id']] = $pregunta['noRespuesta'];
					else $respuestas[$pregunta['id']] = '9';
				}else{
					$r_inicio =  strrpos($pregunta['respuesta'],'|');				
					if($r_inicio!=false)$respuestas[$pregunta['id']] = substr($pregunta['respuesta'],$r_inicio+1);
					else $respuestas[$pregunta['id']] = $pregunta['respuesta'];
				}			
			}
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
		//print_r($respuestas);
		$_SESSION['respuestasAlm'] = $respuestas;
		//print_r($respuestas);
		//CALCULOS DE LA INDEXACION DE PAGINAS
		$paginaActual =1;
		if(isset($_SESSION['paginaAct']))$paginaActual=$_SESSION['paginaAct'];
		else $_SESSION['paginaAct'] = $paginaActual;
		if($_POST)
		{
			if($accionPagina=='Siguiente'  && $paginaActual< $totalPaginas)$paginaActual++;
			else if($accionPagina=='Anterior' && $paginaActual>1)$paginaActual--;
		}

		//$paginaActual =16;
		
		$_SESSION['paginaAct'] = $paginaActual;
		$indiceInicio = $cuestionario->calcularIndiceInicio($paginaActual);
		
		$indiceFin = $cuestionario->calcularIndiceFin($paginaActual);
		$data["validacion.reglas"]="";
		$data["validacion.reglas.adicional"]="";
		//ARMAMOS LAS PREGUNTAS
		$preguntas = "";
		$numeracion = 1;
		for($i = $indiceInicio; $i < $indiceFin && $i< $totalPreguntas;$i++)
		{
			
			$preguntaActual = $cuestionario->obtenerPregunta($i);

			$PREGUNTA_ID = '';
			if(isset($preguntaActual['id']))$PREGUNTA_ID = $preguntaActual['id'];
			else $PREGUNTA_ID = $i;
			
			$PREGUNTA_TIPO = $preguntaActual['tipo'];
			$PREGUNTA_EVAL = "";
			if(isset($preguntaActual['eval']))$PREGUNTA_EVAL = $preguntaActual['eval'];
			//echo $PREGUNTA_EVAL;
			$subtitulo =($cuestionario->obtenerSubtituloPregunta($i));
			if($subtitulo!=null)
			{
				if($subtitulo=="br")$preguntas.="<p><br><br></p>";
				else $preguntas.="<h3>".$subtitulo."</h3>";
			}

			$preguntas.="<div class=\"pregunta\">";
			$preguntas.="<label>";
			if($cuestionario->obtenerFormaNumeracion()==2) $preguntas.=$numeracion.".- ";
			else if($cuestionario->obtenerFormaNumeracion()==3) $preguntas.=($i+1).".- ";
			$preguntas.=preguntaHTML::bbcode_format($cuestionario->obtenerDescripcionPregunta($i));
			//$preguntas.="<i>".$PREGUNTA_ID."</i></label>";
			$preguntas.="</label>";

			if(isset($preguntaActual['id']))	$preguntas.=(preguntaHTML::obtenerPRegunta($i,$PREGUNTA_TIPO,$respuestas[$preguntaActual['id']]));
			else $preguntas.=(preguntaHTML::obtenerPRegunta($i,$PREGUNTA_TIPO,$respuestas[$i]));
			$preguntas.="</div>";

			
			$reglas_arr = (preguntaHTML::obtenerRegla2($PREGUNTA_ID,$i+1,$PREGUNTA_EVAL,$PREGUNTA_TIPO));
			$data["validacion.reglas"] .= $reglas_arr[0];
			$data["validacion.reglas.adicional"].= $reglas_arr[1];

			$numeracion++;
		}
		
		$botonAnt = $cuestionario->isBotonAnterior();
		
		if($totalPaginas==$paginaActual)$data['examen.siguiente']='';
		else $data['examen.siguiente']='<input name="Submit" value="Siguiente" type="submit">	';
		
	
		if($botonAnt!=null && $botonAnt=="false")$data['examen.anterior']='';
		else $data['examen.anterior']='<input name="Submit"  value="Anterior" type="submit">';
		

		if( $totalPaginas==$paginaActual)$data['examen.submit']='<input name="Submit" value="TERMINAR" type="submit" onClick="return confirmSubmitExamen()">';
		else if(false)$data['examen.submit']='<input name="Submit" value="TERMINAR" type="submit" onClick="return confirmSubmitExamen()">';
		else $data['examen.submit']='';

		$data['examen.preguntas']=''.$preguntas;
		$titulo = $cuestionario->obtenerTitulo($paginaActual);
		if($titulo==null)$data['examen.titulo']='';
		else $data['examen.titulo']=''.$titulo;


		$textInicio = $cuestionario->obtenerTextoInicio();
		if($paginaActual!=1)$data['examen.inicio']='';
		else if($textInicio!=null && $paginaActual==1)$data['examen.inicio']=''.$textInicio;
		else $data['examen.inicio']='';
		//MENSAJE
		$mensajes['menu.location']='Pagina '.$paginaActual.' de '.$totalPaginas.". Tiempo ".date("H:i:s",$dateTranscurrido) ;
		$mensajes['template.menuoptions']='';
?>