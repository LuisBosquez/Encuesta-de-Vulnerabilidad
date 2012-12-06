<?php
/**
 * Muestra los resultados al alumno.
 * @author Cesar
 */

	$data['template.contenido']='./templates/sac/examen/resultadoExamenVulAlu.html';


	if($_GET && isset($_GET['idApl']))
	{
		include_once('./classes/resultados.php');
		include_once('./lib/encryption.php');

		$enc= new Crypto();
		$idEncript = $_GET['idApl'];
		$id = trim($enc->decrypt($_GET['idApl']));

		$ress=null;
		if(is_numeric($id)){
			$resultados  = new resultados();
			$ress = $resultados->obtenerResultados($id);
		}
		$cuestID = 0;
		if($ress!=null){
			$aplicacion = $resultados->obtenerAplicacion($id);

			require_once('./classes/cuestionarioXML.php');
			require_once('./classes/cuestionario.php');

			$cuestionario = new cuestionario();
			$cto = $cuestionario->obtenerCuestionario($aplicacion['cuestionario_id']);
			$cuestID = $cto['cuestionario_id'];

			$cuexml = new cuestionarioXML();
			$cuexml->abrir('./archivo/'.$cto['cuestionario_fuente']);
			$data['cuestionario.nombre'] = $cto['cuestionario_nombre'];
			$data['cuestionario.tiempo'] = $aplicacion['aplicacion_tiempo'];


			if($cuexml->obtenerFormaCalificar()==1){
				$data['cuestionario.tiempo'] .="<br><b>Al pasar el mouse por los signos de interrogación te aparecerá la descripción de la categoria</b>";
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

			$reglad = $cuexml->obtenerReglas();

			if($reglad!=null){

				$talleres = $cuestionario->califica($resultadosEvaluar,$reglad);
				ob_start();
				include_once("./talleres/talleresSistemaChe.php");
				$salida1 = ob_get_contents();
				ob_end_clean();
				$data['resultado.retroalimentacion'] ="<h3 class=\"center\">Retroalimentaci&oacute;n</h3>".$salida1;
			}
			else $data['resultado.retroalimentacion'] ='';
			$data['resultado.tabla'] = $table;
		}else if($cuexml->obtenerFormaCalificar()==2 && ($cuestID==3||$cuestID==4 || $cuestID==7 || $cuestID==8)){
			$data['template.contenido']='./templates/sac/examen/resultadoExamenSencillo.html';

			$data['resultado.tabla'] = '<br><br><b><p class="center">Gracias por tu paticipaci&oacute;n. </p>';
			$data['resultado.retroalimentacion'] = '';
			$data['resultado.fin'] = '';
		}else if($cuexml->obtenerFormaCalificar()==2 && ($cuestID==5)){
			$data['resultado.tabla'] = '<br><br><b><p class="center">Dato capturado por tu paticipaci&oacute;n.
				<br><br><b><a href="index.php?kkd3o223f2">Capturar otro</a></b></p>';
			$data['resultado.retroalimentacion'] = '';
			$data['resultado.fin'] = '';
		}else if($cuexml->obtenerFormaCalificar()==2 ){

			include ("./examen/vulnerabilidad.php");

			$vul = new vulnerabilidad();
			$vul->cargaresultados($id);

			$resVul = $vul->obtenerAnalisisVulneSumatoria();




			$data['resultado.tabla'] = '<br>'.
				"<p class='center'><img src=\"imagenes/resultadoVulnerabilidad2.php?id=".$idEncript."\"></p>";

			$data['resultado.tabla'] .= "";

			if($resVul>=10)$data['resultado.retroalimentacion'] = 'TE PEDIMOS  ACUDAS AL DEPARTAMENTO DE PREVENCI&Oacute;N UBICADO EN EL PISO 3 DEL EDIFICIO DEL CENTRO ESTUDIANTIL<br> PARA TENER  UNA ENTREVISTA CONFIDENCIAL SOBRE TUS RESULTADOS; EN UN HORARIO DE 8:00 A 17:00 HRS. ';
			else $data['resultado.retroalimentacion'] = 'PARA CONOCER M&Aacute;S SOBRE TUS RESULTADOS Y RECIBIR UNA ASESOR&Iacute;A PERSONALIZADA <br> PUEDES ACUDIR AL DEPARTAMENTO DE PREVENCI&Oacute;N <br>UBICADO EN EL PISO 3 DEL EDIFICIO DEL CENTRO ESTUDIANTIL; EN UN HORARIO DE 8:00 A 17:00 HRS.';

			$data['resultado.fin'] = '';
		}


		}else{//La desencripcion fue erronea o no existe el resultado.
			content::armaMensaje($data,'Error',"No se pueden cargar los resultados. Falta de parametros.");
		}
	}
	else{//Si no se mando el idDelResultado
		content::armaMensaje($data,'Error',"Falta de parametros.");
	}

	if(!isset($_SESSION['usuario_nivel'])){
		$mensajes['template.menuoptions'] = '<a href="usuario.php?logout">Salir</a> .::.';
	}else content::obtenerMenuUsuario($mensajes);

	$mensajes['menu.location'] = 'Resultados';

?>