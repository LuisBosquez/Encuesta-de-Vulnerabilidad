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
	$datay = array_merge(array(null),$vul->obtenerAnalisis(),array(null));
    
	// Create the graph.
	$graph = new Graph(700,500,"auto");
	
	$graph->SetScale('textlin',0,60,0,25);
	$graph->SetShadow();
		
	$graph->yaxis->scale->ticks->Set(2); // Set major and minor tick to 10
	$graph->yaxis->title->Set("Puntuacion");	

	$graph->xaxis->scale->ticks->Set(1);
	$graph->xaxis->SetTickLabels($lbl,null,true);
	$graph->xaxis->SetLabelAlign('center','top');	
	$graph->xaxis->SetLabelAngle(90);
	
	$graph->ygrid->Show(true,true);
	$graph->xgrid->Show(true,false);
	
	
	$graph->img->SetMargin(40,30,20,160);

	
	$lineplot =new LinePlot($datay);
	$lineplot ->SetColor("blue");

	$lineplot->value->Show();
	$lineplot->value->SetFormat('%d');
	$lineplot->value->SetMargin(20);
	

	$graph->Add($lineplot);
		
	$graph->title->SetFont(FF_FONT1,FS_BOLD);
	$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
	$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);

	// Display the graph
	$graph->Stroke();


 ?>