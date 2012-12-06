<?php
class core {

	//SAC
	//4 julio 2005
	function coreSettings() {

		//SMTP
		$settings['MAIL']['SMTP_HOST'] = '';
		$settings['MAIL']['SMTP_USER'] = '@';
		$settings['MAIL']['SMTP_PASS'] = '';
		$settings['MAIL']['SMTP_PORT'] = 25;
		$settings['MAIL']['SMTP_USERNAME'] = 'SAC';

		//	Sys vars
		if($_SERVER['HTTP_HOST']=='dte.ccm.itesm.mx')
			$settings['siteDir'] = 'http://dte.ccm.itesm.mx/vulnerabilidad/';
		else if($_SERVER['HTTP_HOST']=='127.0.0.1'){
			$settings['siteDir'] = 'http://127.0.0.1/vulnerabilidad/web/';	
		}
		
		//temp		
		//$settings['siteDir'] = 'http://127.0.0.1/vulnerabilidad/web';	
		
		//DB vars
		$settings['DB']['dbhost'] = 'dte.ccm.itesm.mx';
		$settings['DB']['dbusername'] = 'sac';
		$settings['DB']['dbpassword'] = 'we83mlwmj1';
		$settings['DB']['dbname'] = 'vulnerabilidad';
		
		
		$settings['cuestionario']['vulnerabilidad'] = 'vulnerabilidad';

		return $settings;
	}



}
?>