<?php

// send text/palin e-mail message
// using the mail server from localhost

// path to smtp.php from XPM2 package
require_once(DIRECTORIO.'lib/XPM2/smtp.php');	

class mail
{
	var $mail = null;
	var $settings;
	
	function mail()
	{
		require_once(DIRECTORIO.'config/core.php');		
		$this->settings = core::coreSettings();
		
		$this->mail = new SMTP;	
		$this->mail->Delivery('relay');
		$this->mail->Relay($this->settings['MAIL']['SMTP_HOST'], $this->settings['MAIL']['SMTP_USER'], $this->settings['MAIL']['SMTP_PASS'], $this->settings['MAIL']['SMTP_PORT'], 'autodetect');
				

	
	}
	

	function enviaDe($to,$fromNombre,$fromMail,$titulo,$texto)
	{				
		
		
		$this->mail->From($fromMail,$fromNombre);
		$this->mail->AddTo($to);
		$this->mail->Text($texto);
		$sent = $this->mail->Send($titulo);
		return $sent;
		
	}	
	
	function envia($to,$titulo,$texto)
	{				
		
		
		$this->mail->From($this->settings['MAIL']['SMTP_USER'], $this->settings['MAIL']['SMTP_USERNAME']);
		$this->mail->AddTo($to);
		$this->mail->Text($texto);
		$sent = $this->mail->Send($titulo);
		return $sent;
		
	}
	
	
	
}





?>