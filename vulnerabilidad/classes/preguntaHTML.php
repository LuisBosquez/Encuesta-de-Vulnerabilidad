<?php
/**
 * Clase que se encarga de crear la representacion visual de las preguntas, asi como sus validaciones y
 * acciones javascript
 * @author Cesar Sanchez oropeza@gmail.com
 * @date 21 Agosto 2007
 */
class preguntaHTML
{
static function bbcode_format($var) {
    $search = array(
        '/\[br\]/',  '/\[bi\]/',  '/\[bf\]/'
        );

    $replace = array(
        '<br>',  '<b>','</b>'
        );

    $var = preg_replace ($search, $replace, $var);

    return $var;
}

	function preguntaHTML(){


	}

	static function obtenerPRegunta($id = -1,$tipo = -1,$value=-1){

		$data = "";
		$cuest = $_SESSION['cuestionario'];
		$preguntaActual = $cuest->obtenerPregunta($id);
		$imagen = 	$cuest->obtenerImagenPregunta($id);
		$preguntaActual_ID = '';
		if(isset($preguntaActual['id']))$preguntaActual_ID = $preguntaActual['id'];
		else $preguntaActual_ID = $id;
		$idNombrePregunta = $id;
		if($imagen!=null)$data.="<p><img src=\"archivo/".$imagen."\"></p>";
		$ID_PREGUNTA='';
		if(isset($preguntaActual['id'])){
			$ID_PREGUNTA = $preguntaActual['id'];
		}
		else $ID_PREGUNTA = $id;

		$data .="<div class='respuestas'>";
		switch ($tipo) {

			case 'OM':
				if($ID_PREGUNTA == "p6")
					$data .= "<ul>";	

				$respuesta =  $cuest->obtenerRespuesta($id);

				$arr_resp = explode("|",$respuesta);
				array_pop($arr_resp);
				
				foreach($arr_resp as $item){
					$datos_res = explode("=",$item);
					$selected = '';
					if($value==$datos_res[1]) $selected = 'CHECKED';
					if($ID_PREGUNTA == "p6") $data .= "<li>";
					
					$data.="<label>";
					$data.= "<input class=\"required\" type=\"radio\" name=\"".$ID_PREGUNTA."\" id=\"".$ID_PREGUNTA."\" value=\"".$datos_res[1]."\" ".$selected.">&nbsp;".str_replace(' ','&nbsp;',$datos_res[0])."&nbsp;</label>";
					
					if($ID_PREGUNTA == "p6") $data .= "</li>";
				}
				
				if($ID_PREGUNTA == "p6")
					$data .= "</ul>";	
				
				break;
			case 'OM_Array':
//				$data.= "<p>";
				foreach($cuest->obtenerRespuestas($id) as $item){
					$c = "";
					$arr_resp = explode("=",$item);
					if(strlen($value."")>0 && $value==$arr_resp[1]){
						$c="CHECKED";
						$flag = true;
					}
					$data.="<div class='opcion'>";
					$data.="<label>";
					$data.= "<input type=\"radio\" name=\"".$ID_PREGUNTA."\" id=\"".$ID_PREGUNTA."\" value=\"".$arr_resp[1]."\" $c> ".$arr_resp[0]." </label></div>";
				}
//				$data.= "</p>";
				break;

			case 'AB':
				$data.=  "<p>";
				if($value==99 || $value==999)$value ='';
				$data.=  "<input name=\"".$ID_PREGUNTA."\" id=\"".$ID_PREGUNTA."\"  type=\"text\" value=\"$value\" maxlength=\"500\" size=\"40\">";
				$data.=  "</p>";
				break;
			case 'CB_Array':  // Respuestas multiples
				$c = "";
//				$data .= "<p>";
				foreach($cuest->obtenerRespuestas($id) as $item){
					$c = "";
					$arr_resp = explode("=",$item);
					if(is_array($value))if(in_array($arr_resp[1],$value))$c="CHECKED";
					$accion = "";
					if(isset($reglas[$arr_resp[1]])) $accion = $arr_resp[1];
					$data.=  "<div class='opcion'><label><input ".$accion." type=\"checkbox\" name=\"".$ID_PREGUNTA."[]\" id=\"".$ID_PREGUNTA."[]\" value=\"".$arr_resp[1]."\" $c>".($arr_resp[0])."</label></div>";
				}
//				$data.=  "</p>";

				break;
			case 'ESPECIFICA': //La ultima opcion permite especificar
				$data .=  "<p>";

				if($value==9 || $value==99 || $value==999)$value ='';

				foreach($cuest->obtenerRespuestas($id) as $item){
					$arr_resp = explode("=",$item);

					if($arr_resp[1]=='on'){

					if($value)
						$enabled = '';
						$c = '';
						$velueFinal = '';
						if($value=='' || strlen($value)<=1)$enabled = 'disabled';
						else{
							$c  = 'checked';
							$velueFinal = $value;
						}
						$data .=  "<input name=\"".$ID_PREGUNTA."\" id=\"".$ID_PREGUNTA."\" ".$c." type=\"radio\"   onClick=\"enableOtros('".$ID_PREGUNTA."-TEXT');\" >\n";
						$data .=  "<label>".utf8_decode($arr_resp[0])."</label> \n";
						$data .=  "<input type=\"text\" name=\"".$ID_PREGUNTA."\" value=\"".$velueFinal."\" id=\"".$ID_PREGUNTA."-TEXT\" ".$enabled."><br>\n";
					}else{
						$c = '';
						if($value==$arr_resp[1])$c  = 'checked';

						$data .=  "<input type=\"radio\" id=\"".$ID_PREGUNTA."\" name=\"".$ID_PREGUNTA."\" value=\"".$arr_resp[1]."\" $c onClick=\"javascript:disableOtros('".$ID_PREGUNTA."-TEXT');\" >\n";
						$data .=  "<label>".utf8_decode($arr_resp[0])."</label><br>\n";
					}
				}

				$data .=  "</p>";
				break;
			case 1:	/**  Si No	*/
				$a="";$b="";$c="";
				if($value==1)$b="selected";
				else if($value=='0')$c="selected";
				else $a="selected";
				$data.= " <select class=\"sino\" name=\"$idNombrePregunta\" id=\"$idNombrePregunta\">\n";
	 			$data.=	 "\t<option value=\"-1\" $a>--</option>\n";
	 			$data.=	 "\t<option value=\"1\" $b>Si</option>\n";
	 			$data.=	 "\t<option value=\"0\" $c>No</option>\n";
				$data.="</select>\n";
				break;
			case 2: /*  V F */
				$a="";$b="";$c="";
				if($value==1)$b="selected";
				else if($value=='0')$c="selected";
				else $a="selected";
				$data.= " <select class=\"sino\" name=\"$idNombrePregunta\" id=\"$idNombrePregunta\">\n";
	 			$data.=	 "\t<option value=\"-1\" $a>--</option>\n";
	 			$data.=	 "\t<option value=\"1\" $b>Verdadero</option>\n";
	 			$data.=	 "\t<option value=\"0\" $c>Falso</option>\n";
				$data.="</select>\n";
				break;

			case 8: //PREGUNTA ABIERTA NUMERICA
				$data.=  "<p>";
				$data.=  "<input name=\"".$idNombrePregunta."\" id=\"".$idNombrePregunta."\"  type=\"text\" value=\"$value\" maxlength=\"3\">";
				$data.=  "</p>";
				break;
			case 9: //PREGUNTA ABIERTA TEXTO LIBRE
				$data.=  "<p>";
				$data.=  "<input name=\"".$idNombrePregunta."\"  type=\"text\" value=\"$value\">";
				$data.=  "</p>";
				break;

			default: $data = "<P><B>ERROR: NO EXISTE EL MODO DE PRGUNTA. REPORTE. </B></P>";break;
		}
		$data.='</div>';
		return preguntaHTML::bbcode_format($data)."\n";

	}

	static function obtenerRegla2($idPregunta,$indice,$eval,$tipoPregunta)	{
		$arr_resp = explode(" ",$eval);
		$reglas = array('','');

		foreach($arr_resp as $item){

			if($item=="req"){

				if($tipoPregunta=="OM" || $tipoPregunta=="OM_Array"){
					$reglas[1] .= "if(!validaReqCB('".$idPregunta."',\"".($indice)."\"))return false;\n";
				}else if($tipoPregunta=="CB" || $tipoPregunta=="CB_Array"){
					$reglas[1] .= "if(!validaReqCheckBox('".$idPregunta."[]',\"".($indice)."\"))return false;\n";
				}else if($tipoPregunta=="ESPECIFICA" ){
					$reglas[1] .= "if(!validaReqCBEspecifica('".$idPregunta."',\"".($indice)."\"))return false;\n";
				}else{
					$reglas[0] .= "frmvalidator.addValidation(\"".$idPregunta."\",\"req\",\"".$indice."\");\n";
				}
			}else if($item=="reqnum"){
				$reglas[0] .= "frmvalidator.addValidation(\"".$idPregunta."\",\"req\",\"".$indice."\");\n";
				$reglas[0] .= "frmvalidator.addValidation(\"".$idPregunta."\",\"num\",\"Responda solamente con numeros: ".$indice."\");\n";
			}

		}

		return $reglas;



	}




}


?>