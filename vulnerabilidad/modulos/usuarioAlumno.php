<?php


	unset($_SESSION['cuestionario']);
	unset($_SESSION['tiempo_ini']);
	unset($_SESSION['horaInicio']);
	unset($_SESSION['tiempo_max']);
	unset($_SESSION['tiempo_fin']);
	unset($_SESSION['horaInicio']);
	unset($_SESSION['respuestasAlm']);
	unset($_SESSION['id_cuestionario']);
	unset($_SESSION['id_grupo']);

	require_once('./classes/cuestionario.php');
	require_once('./lib/encryption.php');

	$enc = new Crypto();
	$cuestionario = new cuestionario();

	$mensajes['menu.location']='Usuario: '.$_SESSION['usuario_nombre'];
	$data['template.contenido']='./templates/sac/contentUsuarioPrincipal.tp';
	$datosresolver= $cuestionario->obtenerCuestionariosParaResolver($_SESSION['usuario_usuario']);
	$datosresueltos= $cuestionario->obtenerCuestionariosResueltos($_SESSION['usuario_id']);

	$resres = array();
	$resresueltos = array();

	foreach($datosresolver as $item)
	{
		$temp = array();
		$temp[] = $item['grupo_nombre'].'-'.$item['grupo_grupo'];
		$temp[] = $item['periodo_nombre'];
		$temp[] = $item['cuestionario_nombre'];
		$temp[] = $item['asignacion_fechainicio'].'<br>'.$item['asignacion_horainicio'];
		$temp[] = $item['asignacion_fechafin'].'<br>'.$item['asignacion_horafin'];

		$h_inicio =   $item['ts_inicio'];
		$h_actual = mktime();
		$h_fin = $item['ts_fin'];
		//TODO Todo dividir por grupos

		if($item['cuestionario_id'])
		{
		$res = $cuestionario->obtenerCuestioriosResueltos($_SESSION['usuario_id'],$item['cuestionario_id'],$item['grupo_id']);


		$idCuestionarioEnc = $enc->encrypt($item['cuestionario_id']);
		$idGrupoEnc = $enc->encrypt($item['grupo_id']);

		if($h_inicio>= $h_actual) $temp[] = "Programado";
		else if($h_fin<= $h_actual) $temp[] = "Fuera de Tiempo";
		else if($res!=null)$temp[] = "<b>Contestado</b>";
		else  $temp[] = "<a href=\"{url.usuario}?examen&idCu=".$idCuestionarioEnc."&idGp=".$idGrupoEnc ."\">CONTESTAR</a>";
		}
		$resres[] = $temp;

	}


	foreach($datosresueltos as $item)
	{


		$temp = array();
		$temp[] = $item['grupo_clave'].'-'.$item['grupo_nombre'];
		$temp[] = $item['periodo_nombre'];
		$temp[] = $item['cuestionario_nombre'];
		$temp[] = $item['aplicacion_fecha'];

		$idApl = $enc->encrypt($item['aplicacion_id']);
		//TODO medidas adicionales de seguridad al ver resultados ie solo ver los propios

		$temp[] = "<a href=\"{url.resultado}&idApl=".$idApl."\">Ver Resultado</a>";
		$resresueltos[] = $temp;

	}




	$data['tabla.cuestionarios']=content::generaFilaDeTabla($resres,6);
	$data['tabla.resultados']=content::generaFilaDeTabla($resresueltos,6);

?>