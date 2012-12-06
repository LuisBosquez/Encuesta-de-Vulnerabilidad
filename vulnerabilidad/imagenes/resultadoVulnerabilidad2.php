<?php


	include ("../lib/jpgraph/jpgraph.php");
	include ("../lib/jpgraph/jpgraph_bar.php");
	include ("../lib/jpgraph/jpgraph_line.php");
 	require_once('./../config/settings.php');       
 	include_once("./../classes/resultados.php");
	include ("../examen/vulnerabilidad.php");
	include_once('../lib/encryption.php');
	require_once('./../config/core.php');
	 
	$core = core::coreSettings();
   	define('URL',$core['siteDir']);
   	define('DIRECTORIO','./../');
   	
   	require_once("./../classes/connect.php");
	
 	require_once('../lib/login.php');
	$login = new login();
	session_start();
 	$login->isLoged();
	 	
			
	$enc= new Crypto();       
	$id = trim($enc->decrypt($_GET['id']));
	if(!is_numeric($id))die("Error fatal. ");
	
	$vul = new vulnerabilidad();
	$vul->cargaresultados($id);
	
	$lbl = array_merge(array(""),$vul->obtenerCategoriasAnalisisLabels(),array(""));
	$datay = array_merge(array(null),$vul->obtenerAnalisisVulne(),array(null));
 	
	



	 
	
    // Create the graph. These two calls are always required
	$graph = new Graph(710,350,"auto");

	$graph->SetScale('textlin',-1,2,0,25);
	
	$graph->yaxis->scale->ticks->Set(1); 
	// Add a drop shadow
	//$graph->yaxis->se
	//$graph->SetShadow();


	 
	$graph->xaxis->scale->ticks->Set(1);
	$graph->xaxis->SetTickLabels($lbl,null,true);
	$graph->xaxis->SetLabelAlign('center','top');
	
	$graph->xaxis->SetLabelAngle(90);
	$graph->img->SetMargin(50,20,10,180);

	// Create a bar pot
	$lineplot =new LinePlot($datay);
	$lineplot ->SetColor("blue");



	// Setup values
	$lineplot->value->Show();
	$lineplot->value->SetFormat('%d');
	$lineplot->value->SetMargin(10);
	$graph->xaxis->SetLabelSide(SIDE_DOWN);
	$graph->xaxis->SetPos(-1);

	
	$graph->yaxis->SetTickPositions(array(0,1));
	
	

	$graph->ygrid->Show(false,false);
	$graph->xgrid->Show(true,false);

	$graph->Add($lineplot);

	$graph->yaxis->title->Set(utf8_decode("Puntuación"));	
	$graph->yaxis->title->SetFont(FF_FONT2,FS_BOLD);
	$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);
	$graph->yaxis->title->SetColor('white');
	$graph->xaxis->SetColor('black','white');
	$graph->xaxis->SetFont(FF_FONT1);
	
	$graph->SetBackgroundGradient('#6699CC','#6699CC',2,BGRAD_MARGIN);
	// Display the graph
	$graph->Stroke();


 ?>