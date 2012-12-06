<?php
	require_once('./config/core.php');
    $core = core::coreSettings();
    define('URL',$core['siteDir']);
 	define('DIRECTORIO','./');
 	require_once('./config/constantes.php');
 	require_once('./config/settings.php');
    require_once('./classes/connect.php');
	require_once('./config/application.php');
	require_once('./classes/content.php');
    require_once('./classes/template.php');

	require_once('./lib/login.php');
	$login = new login();
	session_start();
?>