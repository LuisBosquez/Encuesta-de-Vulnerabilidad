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
		
	 if(isset($_GET['carreras'])){
		$periodoId = $_GET['periodo'];
		$periodo = $catalogo->obtenerPeriodoId($periodoId);
	 	
		include_once('./classes/reportes.php');			
		$carreraid = $_SESSION['carrera_id'];
		$cat = new catalogos();
		$carrera = $cat->obtenerCarrera($_SESSION['carrera_id']);			
		
		$cat = new catalogos();
		$rep = new reporte();			
		$cat->obtenerCarreras();
		$carreras = $rep->obtenerCarrerasYAlumnosID($carreraid,$periodoId);
			
		$info ="<h1>CHE. Resultados por Carreras ".$periodo['descripcion']."</h1><ul>";
		$als = 0;
		foreach($carreras as $item){
			$alcont = $rep->obtenerCantidadCheContestadosPorCarrera($item['carrera_id'],$periodoId);
			$info.="<li><b>".$item['carrera_nombrecorto'].' : '.utf8_encode($item['carrera_nombre'])."</b>";
			$info.="<p>Alumnos: ".$item['alumnos']." Alumnos con resultados: ".$alcont['alumnos']." (".(($alcont['alumnos']/$item['alumnos'])*100)."%) </p>";
			$info .= envolverCuadro('imagenes/reporte1.php?id='.$item['carrera_id']."&periodo=".$periodoId,$cuexml);
			$info.="<br>";
			$als+=$item['alumnos'];
			$info .="</li>";
		}
	$info .="</ul>";
			
			$mensajes['contenido']= "<h3>Alumnos: ".$als."</h3>".$info;
		}		

		else{
		
		$mensajes['contenido']= '<ul>
			<li><a href="usuario.php?reportes&carreras&periodo=2">CHE. Ver Reprotes de la Carrera Ene08</a></li>
			<li><a href="usuario.php?reportes&carreras&periodo=1">CHE. Ver Reprotes de la Carrera Ago07</a></li></ul>';
		
		}


?>