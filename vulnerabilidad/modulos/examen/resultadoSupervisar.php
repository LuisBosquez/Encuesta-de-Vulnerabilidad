<?php
/**
 * Imprime los resultados a ser supervisados por alguien
 */

	$data['template.contenido']='./templates/sac/examen/resultadoExamen.tp';

	if($_GET && isset($_GET['idApl']))
	{
		include_once('./classes/resultados.php');
		include_once('./lib/encryption.php');
		
		$enc= new Crypto();
		
		$encriptedID = $_GET['idApl'];
		$id = trim($enc->decrypt($_GET['idApl']));
		

					
		if(is_numeric($id)){
			$resultados  = new resultados();
			$ress = $resultados->obtenerResultados($id);
			
			$aplicacion = $resultados->obtenerAplicacion($id);

			require_once('./classes/cuestionarioXML.php');
			require_once('./classes/cuestionario.php');
			
			$cuestionario = new cuestionario();
			$cto = $cuestionario->obtenerCuestionario($aplicacion['cuestionario_id']);
			
			$cuexml = new cuestionarioXML();
			$cuexml->abrir('./archivo/'.$cto['cuestionario_fuente']);		
			$data['cuestionario.nombre'] = $cto['cuestionario_nombre'];
			$data['cuestionario.tiempo'] = $aplicacion['aplicacion_tiempo'];			
			
			
			if($cuexml->obtenerFormaCalificar()==1){
				$tooltip = "";
				$numcategorias = $cuexml->obtenerNumeroCategorias();
				foreach($cuexml->obtenerCategorias() as $key=>$item)
					$tooltip .= "Text[".$key."]=[\"".utf8_decode($item["title"])."\",\"".utf8_decode($item["data"])."\"]\n";			
				
				$data['resultado.tooltips'] = $tooltip;

				$j = 0;
				$table = "";
				$sum = 0;
				$resultadosEvaluar = array();
				foreach($cuexml->obtenerCategorias() as $key=>$item){				
					$table .="<tr>";
					$table .= "<th width=\"50\">";			
					$table .= "<div onMouseOver=\"stm(Text[$key],Style[0])\" onMouseOut=\"htm()\">";
					$table .=  utf8_decode($item["nombre"]);
					$table .=  " <font color=\"gray\" size = \"1\">?</font> </div>";
					$table .= "</th>";
					$table .= "<td width=\"2\" bgcolor=\"#".$item["color"]."\">&nbsp;</td>";
					$value = $ress[$j]['resultados_grade'];
					$sum += $value;				
					$table .= "	<td width=\"90\"><font size=\"2\">".sprintf("%01.2f",$value)." puntos</font></td>";
					if($j==0){					
						$table .= "<td width = \"\" rowspan=\"".($numcategorias+2)."\" align=\"center\">";
						$table .= "<img src=\"imagenes/resultado.php?id=".$id."\"></td>";					
					}
					$table .= "</tr>";
					$resultadosEvaluar[$j] = $value;
					$j++;
				}
				
			$table .=  "<tr><td colspan=\"2\"><center><b>Promedio</b></center></td>
				<td><center><b>".sprintf("%01.2f",  ($sum/$numcategorias))."</b></center></td>
				</tr>";	
			$table .=  "<tr><td colspan=\"2\"><center><b>Total</b></center></td>
			<td><center><b>".sprintf("%01.2f",  $sum)."</b></center></td>
			</tr>";				
			$retro = $cuexml->obtenerRetroalimentacion();
			if($retro!=null)$data['resultado.fin'] =$cto['cuestionario_textofin'];
			else $data['resultado.fin'] ='';
		
			$urlretro = $cuexml->obtenerURLRetroalimentacion();
			$reglad = $cuexml->obtenerReglas();
			if($urlretro!=null && $reglad!=null)
			{
	
				
				$calificados = $cuestionario->califica($resultadosEvaluar,$reglad);
				require_once('./talleres/'.$urlretro);
				$res = obtenerTalleres($calificados);
				$retro2="<p class=\"title\">Retroalimentaci&oacute;n</p>.$res";			
				$data['resultado.retroalimentacion'] =$retro2;	
			}
			else $data['resultado.retroalimentacion'] ='';	
			$data['resultado.tabla'] = $table;
		}else if($cuexml->obtenerFormaCalificar()==2){
			include ("./examen/vulnerabilidad.php");
			$vul = new vulnerabilidad();
			$vul->cargaresultados($id);
			
	
			$resImp = "";
			foreach($vul->obtenerResultados() as $key=>$item){
				$resImp .= '<p>'.$key.' : '.$item.'</p>';	
			}
			
			$data['resultado.tabla']= "<p class='center'><img src=\"imagenes/resultadoVulnerabilidad.php?id=".$encriptedID."\"></p>
			<p class='center'>Gráfica de Vulnerabilidad </p>
			<p class='center'><img src=\"imagenes/resultadoVulnerabilidad2.php?id=".$encriptedID."\"></p>
			<p class='b500' ></p>
			";		
			$data['resultado.tabla'].=$resImp;		

			$data['resultado.retroalimentacion'] = '';			
			$data['resultado.fin'] = '';				}
	
		
		
		
				
		}else{//La desencripcion fue erronea o no existe el resultado. 
			content::armaMensaje($data,'Error',"No se pueden cargar los resultados. Falta de parametros.");
		}
	}else if($_GET && isset($_GET['recurso'])){
		$recurso  = $_GET['recurso'];
		$conn = new connect();
		
		$rec = $conn->queryArrayUnico("select aplicacion_id,	resultados_value
			from sac.sesion
			left join sac.aplicacion using (sesion_id)
			left join sac.resultados using (aplicacion_id) 
			where sesion.cuestionario_id in (7,8)
			and resultados_seccion = 'recurso_id' and resultados_value = ".$recurso."
			order by resultados_value asc,aplicacion.aplicacion_id asc");
		include_once('./classes/resultados.php');
		include_once('./lib/encryption.php');
		
		
			$id = $rec['aplicacion_id'];
			//////////////
			$resultados  = new resultados();
			$ress = $resultados->obtenerResultados($id);
			
			$aplicacion = $resultados->obtenerAplicacion($id);

			require_once('./classes/cuestionarioXML.php');
			require_once('./classes/cuestionario.php');
			
			$cuestionario = new cuestionario();
			$cto = $cuestionario->obtenerCuestionario($aplicacion['cuestionario_id']);
			
			$cuexml = new cuestionarioXML();
			$cuexml->abrir('./archivo/'.$cto['cuestionario_fuente']);		
			$data['cuestionario.nombre'] = $cto['cuestionario_nombre'];
			$data['cuestionario.tiempo'] = $aplicacion['aplicacion_tiempo'];			
			
	
			include ("./examen/vulnerabilidad.php");
			$vul = new vulnerabilidad();
			$vul->cargaresultados($id);
			
			$res = $vul->obtenerResultados();
			
			
			$preguntas = $cuexml->obtenerPreguntas();
			
			$preguntas = $preguntas['pregunta'];
			
			
			$resImp = "";
			
			
			
			foreach($preguntas as $pregunta){
				$item = $res[$pregunta['clave']];
				
				if($item=='0')$item="No";
				else if($item=='1')$item="Si";
				else if($item=='-1')$item="NA";								
				$resImp .= '<p>'.$pregunta['nombre'].' : <br><b>'.utf8_encode($item).'</b></p>';	
			}
		
			$data['resultado.tabla']= "<p class='center'>Evaluación </p><p class='center'></p><p class='b500' ></p>";		
			$data['resultado.tabla'].=$resImp;		

			$data['resultado.retroalimentacion'] = '';			
			$data['resultado.fin'] = '';						
			
		/////////////
		
	}else if($_GET && isset($_GET['sesionCSV'])){
		//$recurso  = $_GET['recurso'];
		$conn = new connect();
		$sesion = $_GET['sesionCSV'];
		include_once('./classes/resultados.php');
		include_once('./lib/encryption.php');
		require_once('./classes/cuestionarioXML.php');
		require_once('./classes/cuestionario.php');
			
		$query = "select aplicacion_id, sesion.cuestionario_id,	resultados_value,recurso_titulo,recurso_clave
			from sac.sesion
			left join sac.aplicacion using (sesion_id)
			left join sac.resultados using (aplicacion_id) 
			left join recursosmoviles.recurso on resultados_value = recurso_id			
			where sesion_id = ".$sesion."
			and resultados_seccion = 'recurso_id'
			-- and aplicacion.usuario_id = '6588'
			group by aplicacion_id
			order by resultados_value asc,aplicacion.aplicacion_id asc";
		$resultados = $conn->queryArray($query);


		$cuestionarioID= 0;
		$cuexml = new cuestionarioXML();
		foreach($resultados as $item){
			$cuestionarioID = $item['cuestionario_id'];

			$cuestionario = new cuestionario();
			$cto = $cuestionario->obtenerCuestionario($cuestionarioID);
			
			
			$cuexml->abrir('./archivo/'.$cto['cuestionario_fuente']);	
			$preguntas = $cuexml->obtenerPreguntas();	
			$preguntas = $preguntas['pregunta'];
			echo "aplicacion|clave|recurso|";
			foreach($preguntas as $pregunta){			
				echo(''.$pregunta['nombre'].'|');	
			}			
			
			echo "<br>";
			break;
		}
			include ("./examen/vulnerabilidad.php");
			$vul = new vulnerabilidad();

		
		foreach($resultados as $item){
			echo $item['aplicacion_id'].'|'. $item['recurso_clave'].'|'. $item['recurso_titulo'].'|';		
			$id = $item['aplicacion_id'];
			
			$resultados  = new resultados();
			$ress = $resultados->obtenerResultados($id);	
			
			$aplicacion = $resultados->obtenerAplicacion($id);
			$preguntas = $cuexml->obtenerPreguntas();
			$preguntas = $preguntas['pregunta'];
			$vul->cargaresultados($id);
			
			$res = $vul->obtenerResultados();
		
			$resImp = "";
			foreach($preguntas as $pregunta){
				$item = $res[$pregunta['clave']];
				
				if($item=='0')$item="No";
				else if($item=='1')$item="Si";
				else if($item=='-1')$item="NA";								
				echo(''.($item).'|');	
			}
			echo "<br>";
			/************/
			
		}
		
		die();
	}
	else{//Si no se mando el idDelResultado
		content::armaMensaje($data,'Error',"Falta de parametros.");
	}
	
	content::obtenerMenuUsuario($mensajes);		
		
	$mensajes['menu.location'] = 'Resultados';
		
?>