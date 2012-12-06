<?php

	

	function redirect($to)
	{

 	  // require_once("libraries/config.inc");
	
	    $schema = "http";
	    $host = $_SERVER['HTTP_HOST'];
	    if (headers_sent()) return false;
	    else
	    {
     	   //  header("HTTP/1.1 301 Moved Permanently")
	        // header("HTTP/1.1 302 Found")
	        // header("HTTP/1.1 303 See Other")
	        header("Location: $schema://$host/"."$to");
	        exit();
	    }
	}

    function goTo($to)
    {
     echo '<SCRIPT LANGUAGE="JavaScript">';
	 echo 'var urlto = "'.$to.'";';
	 echo 'window.location.href= urlto;';
	 echo '</script>';
	}



?>