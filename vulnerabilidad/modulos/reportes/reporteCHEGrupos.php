<?php
	require_once('./classes/cuestionarioXML.php');
	require_once('./classes/cuestionario.php');
	require_once('./classes/catalogos.php');
				
	$cuestionario = new cuestionario();
	$cto = $cuestionario->obtenerCuestionario(1);
	
	$catalogo = new catalogos();
	
	
	$cuexml = new cuestionarioXML();
	$cuexml->abrir('./archivo/'.$cto['cuestionario_fuente']);		
	$data['cuestionario.nombre'] = $cto['cuestionario_nombre'];
		
	function envolverCuadro($src,$cuexml){

		$numcategorias = $cuexml->obtenerNumeroCategorias();		
		$tooltip ="";
		foreach($cuexml->obtenerCategorias() as $key=>$item)$tooltip .= "Text[".$key."]=[\"".utf8_decode($item["title"])."\",\"".utf8_decode($item["data"])."\"]\n";						
		$table ='<SCRIPT language="JavaScript1.2" >	'.$tooltip.'	var TipId="tiplayer";	var FiltersEnabled = 1; 	mig_clay();</SCRIPT>'	;
		$table .= "<table class='basica grande'><thead>";
		$j = 0;							
		foreach($cuexml->obtenerCategorias() as $key=>$item){				
			$table .="<tr>";
			$table .= "<th width=\"260\">";			
			$table .= "<div onMouseOver=\"stm(Text[$key],Style[0])\" onMouseOut=\"htm()\">";
			$table .=  utf8_decode($item["nombre"]);
			$table .=  " <font color=\"gray\" size = \"1\">(?)</font>: ".utf8_decode($item["title"])."</div>";
			$table .= "</th>";
			$table .= "<td width=\"2\" bgcolor=\"#".$item["color"]."\">&nbsp;</td>";
			if($j==0){					
				$table .= "<td width = \"\" rowspan=\"".($numcategorias)."\" align=\"center\">";
				$table .= "<img src=\"".$src."\"></td>";					
			}
			$table .= "</tr>";
			$j++;
		}	
		$table .="</thead></table>";		
		return $table;		
	}


	$data['template.contenido']='./templates/sac/contentMain.tp';	

	$mensajes['menu.location']= 'Reportes';
		
	if(isset($_GET['global']) && isset($_GET['periodo'])){
		$periodoId = $_GET['periodo'];
		$periodo = $catalogo->obtenerPeriodoId($periodoId);
		include_once('./classes/reportes.php');	
		$rep = new reporte();	
		$resltd = $rep->obtenerNumeroResultadosdelCHE($periodoId);
		$info ="<h1>CHE. Resultados Globales consolidados ".$periodo['descripcion']."</h1><ul>";
		$info.="<p><center>Total cuestionarios resuerltos: ".$resltd['resultados']."</center></p>";
		$info .= envolverCuadro('imagenes/reporte3.php?periodo='.$periodoId,$cuexml);
		$mensajes['contenido']= $info;
	}
	else if(isset($_GET['carreras'])){		
		include_once('./classes/reportes.php');			
		$periodoId = $_GET['periodo'];
		$periodo = $catalogo->obtenerPeriodoId($periodoId);			
		$cat = new catalogos();
		$rep = new reporte();			
		$cat->obtenerCarreras();
		$carreras = $rep->obtenerCarrerasYAlumnos($periodoId);
		
		$info ="<h1>CHE. Resultados por Carreras ".$periodo['descripcion']."</h1><ul>";
		$als = 0;
		foreach($carreras as $item){
			$alcont = $rep->obtenerCantidadCheContestadosPorCarrera($item['carrera_id'],$periodoId);
			$info.="<p><center><b>".$item['carrera_nombrecorto'].' : '.utf8_encode($item['carrera_nombre'])."</b></center></p>";
			$info.="<p><center>Alumnos: ".$item['alumnos']." Alumnos con resultados: ".$alcont['alumnos']." (".(($alcont['alumnos']/$item['alumnos'])*100)."%) </center></p>";
			$info .= envolverCuadro('imagenes/reporte1.php?id='.$item['carrera_id'].'&periodo='.$periodoId,$cuexml);
			$info.="<br>";
			$als+=$item['alumnos'];
		}
		$info .="</ul>";
		$mensajes['contenido']= "<h3>Alumnos: ".$als."</h3>".$info;
	}		
	else if(isset($_GET['grupos'])){
		include_once('./classes/reportes.php');			
		$periodoId = $_GET['periodo'];
		$periodo = $catalogo->obtenerPeriodoId($periodoId);				
		$cat = new catalogos();
		$rep = new reporte();			
		$cat->obtenerCarreras();
		$grupos = $rep->ontenerGruposyAlumno($periodoId);
		$info ="<h1>CHE. Resultados por Grupo ".$periodo['descripcion']."</h1><ul>";
		$als = 0;
		foreach($grupos as $item){
			$alcont = $rep->obtenerCantidadCheConetsatdosPorGrupo($item['grupo_id']);
			$info.="<p><center><b>".$item['grupo_clave'].' : '.utf8_encode($item['grupo_nombre'])."</b></center></p>";
			$info.="<p><center>Alumnos por grupo: ".$item['alumnos'];
			$info.=" Alumnos con resultados: ".$alcont['alumnos']." (".(($alcont['alumnos']/$item['alumnos'])*100)."%) </center></p>";
			if($alcont['alumnos']>0)
				$info .= envolverCuadro('imagenes/reporte2.php?id='.$item['grupo_id'],$cuexml);
				else $info.="<p><center>Sin resultados</center></p>";
				$info.="<br><br>";
				$info .="</p>";
			}
			$info .="</ul>";
			$mensajes['contenido']= $info;
		}
	else{
		
		$mensajes['contenido']= '<ul>
			<li><a href="usuario.php?reportes&grupos&periodo=2">CHE. Ver Reportes Grupos Ene08</a></li>
			<li><a href="usuario.php?reportes&carreras&periodo=2">CHE. Ver Reportes Carreras Ene08</a></li>
			<li><a href="usuario.php?reportes&global&periodo=2">CHE. Ver Reporte Global Ene08</a></li>
			</ul><ul>
		
			<li><a href="usuario.php?reportes&grupos&periodo=1">CHE. Ver Reportes Grupos Ago07</a></li>
			<li><a href="usuario.php?reportes&carreras&periodo=1">CHE. Ver Reportes Carreras Ago07</a></li>
			<li><a href="usuario.php?reportes&global&periodo=1">CHE. Ver Reporte Global Ago07</a></li>
			
			</ul>';
		
		}
	


?>