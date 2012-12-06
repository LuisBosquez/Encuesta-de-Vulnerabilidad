<?php
require_once(DIRECTORIO.'config/core.php');
class connect extends core {
	var $query;
	var $link;
	var $result;
	//Establish connection to DB
	function connect(){

		$settings = core::coreSettings();

		$host = $settings['DB']['dbhost'];
		$db = $settings['DB']['dbname'];
		$user = $settings['DB']['dbusername'];
		$pass = $settings['DB']['dbpassword'];
		$this->link = mysql_connect($host, $user, $pass) or die("Error fatal ".mysql_error());
		mysql_select_db($db);
		register_shutdown_function(array(&$this, 'close'));
	}
	//Execute query
	function query($query_in) {
		$this->query = $query_in;
		$this->result = mysql_query($query_in, $this->link);
		echo mysql_error();
		return $this->result;
	}

	function queryArray($query_in) {
		$this->query = $query_in;
		$this->result = mysql_query($query_in, $this->link);
		echo mysql_error();

			$arreglo = array();
			while($linea = mysql_fetch_assoc($this->result))
			{
				$arreglo[] = $linea;
			}

		return $arreglo;

	}

	function queryArrayUnico($query_in) {
		$this->query = $query_in;
		$this->result = mysql_query($query_in, $this->link);
		echo mysql_error();

			$arreglo = array();
			while($linea = mysql_fetch_assoc($this->result))
			{
				$arreglo[] = $linea;
				return $arreglo[0];
			}

		return null;

	}
	//Fetch result array
	function fetchResult($result_in) {
		return mysql_fetch_assoc($result_in);
	}
	//Return total rows
	function totalRows($result){
		return mysql_num_rows($result);
	}
	//Close connection
	function close() {
		//mysql_close($this->link);
	}
	//Return last query
	function getQuery() {
		return $this->query;
	}
	function generateSQLInsert($tabla,$array)
	{
		$col = array();
		$dato = array();
		$string_col = "";
		$string_dato = "";

		foreach($array as $key=>$data)
		{
			$col[] = $key;
			if($data=="")$dato[] = ""."null"."";
			else $dato[] = "'".$data."'";
		}
		$string_col = "(".implode(",", $col).")";
		$string_dato = "(".implode(",", $dato).")";
		$query = "INSERT INTO ".$tabla." ".$string_col." VALUES ".$string_dato;

		$this->query($query);
		if($this->result)
			return true;
		else
			return false;
	}


	function generateSQLUpdate($tabla,$array,$condicion)
	{
		$dato = array();
		foreach($array as $key=>$data)
		{
			$dato[] = $key." = '".$data."'";
		}

		$cambios = " ".implode(",", $dato)." ";

		$query = "UPDATE ".$tabla." SET ".$cambios." WHERE ".$condicion;
echo 
		$this->query($query);
		if($this->result)
			return true;
		else
			return false;
	}
}
?>