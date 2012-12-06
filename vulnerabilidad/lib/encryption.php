<?php

class Crypto
{
	var $key;

    function Crypto( $llave = 'SAC2.012JUL07') 
	{
		$this->key = $llave; 
    }

    function encrypt ( $strtoencrypt )
    {
	
		$td = mcrypt_module_open('tripledes', '', 'ecb', '');
		$iv = mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
		mcrypt_generic_init($td, $this->key, $iv);
		$encrypted_data = mcrypt_generic($td, $strtoencrypt);
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);	
		return  bin2hex($encrypted_data);
	
    }


    function decrypt ( $strtodecrypt )
    {
	
		$bindata="";
		 
		for ($i=0;$i<strlen($strtodecrypt);$i+=2) {
		   $bindata.=chr(hexdec(substr($strtodecrypt,$i,2)));
		}					
		$td = mcrypt_module_open('tripledes', '', 'ecb', '');
		$iv = mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
		return mcrypt_decrypt ( 'tripledes' , $this->key,$bindata, "ecb" , $iv);		
		
    }


} 

?>
