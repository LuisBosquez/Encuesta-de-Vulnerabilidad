<?php 
/* +----------------------------------------------------------------------+
// | SAC Ver 2.0.1 TEC                                                    |
// +----------------------------------------------------------------------+
// | Copyright © 2004-2006. Cesar Sanchez						          |
// +----------------------------------------------------------------------+
// |  Controlado de resultados
// +----------------------------------------------------------------------+
// | Authors: Cesar Sanchez <oropeza@gmail.com>                           |
// +----------------------------------------------------------------------+
//
*/

require_once('./lib/load.php');
//$login->isLoged();	
$tp=&new templateParser('');
$data = array('template.body'=>'./templates/sac/content.tp');




include_once('./modulos/examen/resultadoSupervisar.php');

$tags2 = array_merge($data,settings::paginaSettings(),application::appSettings(),$mensajes);

$tags2['template.menuoptions']='';
$tp->parseTemplate($tags2);

     echo $tp->display();
?>