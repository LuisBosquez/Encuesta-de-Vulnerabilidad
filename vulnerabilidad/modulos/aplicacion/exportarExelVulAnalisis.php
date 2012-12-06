<?php
	include_once("./classes/resultados.php");
	include ("./examen/vulnerabilidad.php");
	
	    
	$res_totales = null;
	$contestados = 0;
	$nocontestados=0;
	$renglon = 0;
	$fil = 0;
	$col = 0;
	$nu_alumnos=0;


	$workbook = new Spreadsheet_Excel_Writer();
	
	if($idSesion!=-1)$nombreArchivo= $cto['nombre'];
	else $nombreArchivo=$gpo['grupo_clave']."-".$gpo['grupo_grupo'];
	
	$workbook->send($nombreArchivo.".xls");

	$change = array(":","\\","/","?","?","*","[","]");
	$clave_n = "";
	$grupo_n = "";
	$nombre_n = "";
	if($gpo!=null){
		$clave_n = str_replace($change, "", $gpo['grupo_clave']);
		$grupo_n = str_replace($change, "", $gpo['grupo_grupo']);
		$nombre_n = str_replace($change, "", $gpo['grupo_nombre']);
	}


	$worksheet =& $workbook->addWorksheet($clave_n.".".$grupo_n);
	$worksheet->setColumn(1,1,20);
	$worksheet->setColumn(2,2,18);

	$worksheet->setColumn(3,$cuexml->obtenerNumeroCategorias()+2,5);

	// Formatos disponibles
	$formato_titulo =& $workbook->addFormat();
	$formato_titulo->setBold();
	$formato_titulo->setMerge();
	$formato_titulo->setBorder(2);

	$formato_fecha =& $workbook->addFormat();
	$formato_fecha->setBold();
	$formato_fecha->setMerge();
	$formato_fecha->setBorder(1);

	$formato_porcentaje  =& $workbook->addFormat();
	$formato_porcentaje->setNumFormat("00%");


	$formato_texto =& $workbook->addFormat();


	//Cargamos los datos

	$worksheet->write(0, 0, utf8_decode('Sistema de Administración de Cuestionarios'),$formato_titulo);
		$worksheet->write(0, 1, "", $formato_titulo );
		$worksheet->write(0, 2, "", $formato_titulo);

	$worksheet->write(1, 0, 'Reporte Generado: '.date("F j, Y, g:i a"),$formato_fecha);
		$worksheet->write(1, 1, "",$formato_fecha);
		$worksheet->write(1, 2, "",$formato_fecha);

	//$worksheet->mergeCells(3,0,9,2);
	$worksheet->write(3, 0, 'Datos del Cuestionario Aplicado',$formato_texto);
	$worksheet->write(4, 0, 'Cuestionario');
	$worksheet->write(5, 0, utf8_decode('Descripción'));
		$worksheet->write(6, 0, 'Grupo');
	$worksheet->write(7, 0, 'Periodo');
	$worksheet->write(8, 0, utf8_decode('Fecha Apliación'));
	$worksheet->write(9, 0, 'Tiempo para contestar');

	$worksheet->write(4, 2, $cto['cuestionario_nombre'],$formato_texto);
	$worksheet->write(5, 2, $cto['cuestionario_descripcion'],$formato_texto);
	$worksheet->write(7, 2, $cto['periodo_nombre']."");
	
	if($idSesion!=-1){
		$worksheet->write(6, 2, $cto['nombre']);
		$worksheet->write(8, 2, utf8_decode($cto['hora_inicio']));
		
	}else{
		$worksheet->write(6, 2, $gpo['grupo_clave'].".".$row['grupo_grupo']." ".$row['grupo_nombre']."");
		$worksheet->write(8, 2, utf8_decode($cto['asignacion_fechainicio']." Hora inicio:".$cto['asignacion_horainicio'].""));
	}
	
	if(isset($cto['asignacion_tiempo']))$worksheet->write(9, 2, $cto['asignacion_tiempo']."");
	else $worksheet->write(9, 2, $cto['tiempo']."");

	$worksheet->write(11, 0, 'Respuestas de los alumnos');
	$worksheet->write(13, 0, 'Matricula');
	$worksheet->write(13, 1, 'Nombre');
	
	for($i = 0; $i<24;$i++)$worksheet->write(13,($i+3), ($i+1));
	for($i = 0; $i<24;$i++)$worksheet->write(13,($i+28), ($i+1));

	// EMPEZAMOS A AGREGAR A LOS ALUMNOS//
	if($idSesion!=-1){
		$apl = $cuestionario->obtenerAplicacionesSesion($idSesion,$idCuestionario);
		$k=14;
		$fil = 14;
		
		foreach ($apl as $datosAlumno) {
			
		   	$nu_alumnos++;
			$worksheet->write($fil, 0, $datosAlumno['usuario_usuario']);
			$usr = $usuarios->obtenerUsuarioUsuario($datosAlumno['usuario_usuario']);
			$worksheet->write($fil, 1, $usr['usuario_nombre'].' '.$usr['usuario_apellido']);
			
			$col=3;			
			$vul = new vulnerabilidad();
			$vul->cargaresultados($datosAlumno['aplicacion_id']);
			$datay = $vul->obtenerAnalisis();
			foreach($datay as $data)$worksheet->write($fil, $col++,$data);
			$col++;
			$datay = $vul->obtenerAnalisisVulne();
			foreach($datay as $data)$worksheet->write($fil, $col++,$data);										
			$fil++;
		}	 // TERMINAMOS A LOS ALUMNOS		
	}

	$worksheet->write(6, 9,"Total Alumnos:");
	$worksheet->write(6, 13,$nu_alumnos+"");
	
	$worksheet->hideGridLines();
	$workbook->close();

?>