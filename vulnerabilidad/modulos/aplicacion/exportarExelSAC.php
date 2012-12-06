<?php


	$res_totales = null;
	$contestados = 0;
	$nocontestados=0;
	$renglon = 0;
	$fil = 0;
	$col = 0;
	$nu_alumnos=0;


$workbook = new Spreadsheet_Excel_Writer();
//print_r($gpo);
$workbook->send($gpo['grupo_clave']."-".$gpo['grupo_grupo'].".xls");

	$change = array(":","\\","/","?","?","*","[","]");
	$clave_n = str_replace($change, "", $gpo['grupo_clave']);
	$grupo_n = str_replace($change, "", $gpo['grupo_grupo']);
	$nombre_n = str_replace($change, "", $gpo['grupo_nombre']);		

	
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
	$worksheet->write(6, 2, $gpo['grupo_clave'].".".$row['grupo_grupo']." ".$row['grupo_nombre']."");
	$worksheet->write(7, 2, $cto['periodo_nombre']."");
	$worksheet->write(8, 2, utf8_decode($cto['asignacion_fechainicio']." Hora inicio:".$cto['asignacion_horainicio'].""));
	$worksheet->write(9, 2, $cto['asignacion_tiempo']."");

	$worksheet->write(11, 0, 'Respuestas de los alumnos');
	$worksheet->write(13, 0, 'Matricula');
	$worksheet->write(13, 1, 'Nombre');
	$worksheet->write(13, 2, 'Estatus');
	//print_r($cuexml->obtenerCategorias());

	foreach($cuexml->obtenerCategorias() as $key=>$dato)
	{
		$worksheet->write(13,($key+3), $dato['nombre']+"");
		$res_totales[$key] = 0;
    	$col = $key;
	}
	
	
	$worksheet->write(13,(++$col+3), "Promedio");
	$worksheet->write(13,(++$col+3), "Total");
	
	//TODO: CAMBIAR DE ESTATICO A DINAMICO
	//	$worksheet->write(13, ++$col+3, 'Modulo 1');
	//$worksheet->write(13, $col+4, 'Modulo 2');
	//$worksheet->write(13, $col+5, 'Modulo 3');

	// EMPEZAMOS A AGREGAR A LOS ALUMNOS//	
	$alumnos = $grupo->obtenerAlumnos($gpo['grupo_id']);
	$k=14;
	$i=14;
	$fil = 14;


	foreach ($alumnos as $datosAlumno) {
	   	$nu_alumnos++;
		$worksheet->write($fil, 0, $datosAlumno['usuario_usuario']);

		$usr = $usuarios->obtenerUsuarioUsuario($datosAlumno['usuario_usuario']);
		if($usr!=null) $worksheet->write($fil, 1, $usr['usuario_nombre'].' '.$usr['usuario_apellido']);
		else $worksheet->write($fil, 1, 'No dado de alta');
		
		$cuestres =null;

		if($usr!=null)$cuestres = $cuestionario->obtenerCuestioriosResueltos($usr['usuario_id'],$idCuestionario,$idGrupo);

		if($cuestres !=null){
			
			$worksheet->write($fil, 2, 'Contestado'); 
			$contestados++;
		}else {
			$worksheet->write($fil, 2, 'No Contestado');
			$nocontestados++;
		}
		
		// imprimimos el resultado del alumno
		$col=3;

		
		if($usr!=null && $cuestres!=null){
			$cuestres = $cuestres[0];
			$datos = 0;
			$ress = $resultados->obtenerResultados($cuestres['aplicacion_id']);
			$i = 0;	
			foreach($ress as $data){
				$i++;
				//$res_totales[$col-3] = $res_totales[$col-3] + $data['resultados_grade'];
				//$res[$col-3] = $data['resultados_grade'];				
				$worksheet->write($fil, $col++,$data['resultados_grade']+"");
				$datos = $datos + $data['resultados_grade'];				
			}		
			$worksheet->write($fil, $col++,$datos/$i+"");
     		$worksheet->write($fil, $col++,$datos+"");
			
				
			
			//TODO:Hacer dinamico
			
//			if((($res[0]+$res[1]+$res[2])/3)<7) $worksheet->write($fil, $col,'x');
//			if((($res[3]+$res[4])/2)<7) $worksheet->write($fil, $col+1,'x');
//			if((($res[5]+$res[6]+$res[7]+$res[8])/4)<7) $worksheet->write($fil, $col+2,'x');									
		}		
	$fil++;		
}	 // TERMINAMOS A LOS ALUMNOS
	
	


	//if(mysql_num_rows($result) == 0) $worksheet->write(14, 0, 'No existen alumnos asignados a este grupo');
	

// ESTADISTICAS


	$worksheet->write(6, 9,"Total Alumnos:");
	$worksheet->write(7, 9,"Cuestionarios Contestados:");
	$worksheet->write(8, 9,"Cuestionarios por Contestar:");
	
	$worksheet->write(6, 13,$nu_alumnos+"");
	$worksheet->write(7, 13,$contestados+"");
	$worksheet->write(8, 13,$nocontestados+"");
	$worksheet->write(7, 14,$contestados/$nu_alumnos,$formato_porcentaje);
	$worksheet->write(8, 14,$nocontestados/$nu_alumnos,$formato_porcentaje);
	
	// Imprime el promedio
	//$worksheet->write($fil, 0,"Promedio (Contando solo contestados)");
	
	//for($j = 0;$j<$cuexml->obtenerNumeroCategorias();$j++){
		//if($contestados>0)$worksheet->write($fil, $j+3,$res_totales[$j]/$contestados);
		//else $worksheet->write($fil, "0");		
//	}

	
	//TERMINAMOS EL ARCHIVO DE EXEL
	$worksheet->hideGridLines();

	$workbook->close();

?>