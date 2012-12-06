<?php

	include ("../lib/jpgraph/jpgraph.php");
	include ("../lib/jpgraph/jpgraph_bar.php");
	include ("../lib/jpgraph/jpgraph_line.php");
	require_once('./../config/core.php');

    $core = core::coreSettings();
    define('URL',$core['siteDir']);
 	define('DIRECTORIO','./../');

 	require_once('./../config/settings.php');
    require_once("./../classes/connect.php");
    require_once("./../classes/resultados.php");
    require_once("./../classes/reportes.php");    

   // $res = new resultados();
   $rep = new reporte();
   
   	$periodoId = $_GET['periodo'];
   
    $arrRes = $rep->obtenerResultadosdelCHE($periodoId);

    $datay=array();

    foreach($arrRes as $item)
    {
    	$datay[] = $item['resultados_grade'];

    }

    // Create the graph. These two calls are always required
	$graph = new Graph(400,400,"auto");

	$graph->SetScale('textlin',0,12,0,8);
	$graph->yaxis->scale->ticks->Set(2); // Set major and minor tick to 10
	// Add a drop shadow
	$graph->SetShadow();

	// Setup labels
	$lbl = array("A","B","C","D",
	"E","F","G","H",'I');
	$graph->xaxis->scale->ticks->Set(1); // Set major and minor tick to 10
	$graph->xaxis->SetTickLabels($lbl);
	$graph->xaxis->SetLabelAlign('center','top');
	// Adjust the margin a bit to make more room for titles
	$graph->img->SetMargin(40,30,20,40);

	// Create a bar pot
	$lineplot =new LinePlot($datay);
	$lineplot ->SetColor("blue");


	// Adjust fill color
	//$lineplot->SetFillColor('orange');

	// Setup values
	$lineplot->value->Show();
	//$lineplot->value->SetFormat('%d');
	//$lineplot->value->SetFont(FF_FONT1,FS_BOLD);


		 $ydata2  = array(7,7,7,7,7,7,7,7,7);
		$lineplot2 =new LinePlot($ydata2);
		$lineplot2 ->SetColor("red");
		$lineplot2 ->SetWeight(2);

$graph->ygrid->Show(true,true);
$graph->xgrid->Show(true,false);

	$graph->Add($lineplot);
		$graph->Add( $lineplot2);

	// Setup the titles
	//$graph->title->Set("Centered values for bars");
	//$graph->xaxis->title->Set("D");
	$graph->yaxis->title->Set("Puntuacion");

	$graph->title->SetFont(FF_FONT1,FS_BOLD);
	$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
	$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);

	// Display the graph
	$graph->Stroke();


 ?>