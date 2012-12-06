<?php
require_once('./lib/minixml-1.3/minixml.inc.php');

/**
 * Clase que parsea un archivo xml y lo convierte en datos. Aqui se encuentran los metodos de enlace
 * acciones javascript
 * @author Cesar Sanchez oropeza@gmail.com
 * @date 21 Agosto 2007
 */
class cuestionarioXML
 {

 	private $cuestionario;
 	private $numPreguntas;
 	private $numCategorias;

 	private $tipoTitulo;
 	private $multiplicador;
	private $calificar;

 	private $modo;
 	private $numeracion; 	
 	
 	private $tipoidentificador;
 	
 	private $textoInicio;

 	function cuestionarioXML(){}
	
 	function obtenerCantidadPreguntas(){return $this->numPreguntas;}
  	function obtenerMultiplicador(){ return $this->multiplicador ; }
  	function obtenerNumeroCategorias(){	return $this->numCategorias ;	}
  	function obtenerFormaCalificar(){	return $this->calificar ;	}
   	function obtenerFormaNumeracion(){	return $this->numeracion ;	}  	
   	function obtenerFormaIdenticadorPregunta(){	return $this->tipoidentificador ;	}  	


 	function abrir($dir)
 	{
 		;
 		$xmlDoc = new MiniXMLDoc();
		if(!file_exists($dir))exit("Error Fatal. Archivo XML no encontrado. ");
		$xmlDoc->fromFile($dir);
		
		
		$this->cuestionario = $xmlDoc->toArray();
		
		
		
	

		$this->numPreguntas = $this->cuestionario['cuestionario']['preguntas']['pregunta']['_num'];
		unset($this->cuestionario['cuestionario']['preguntas']['pregunta']['_num']);
		
		
		if(!isset($this->cuestionario['cuestionario']['categorias']['catego']['_num'])){
			$this->numCategorias  = 1;
		}else{
			$this->numCategorias = $this->cuestionario['cuestionario']['categorias']['catego']['_num'];
			unset($this->cuestionario['cuestionario']['categorias']['catego']['_num']);
		}

		
		
		// Modo de guardar los resultados
		if(isset($this->cuestionario['cuestionario']['modo']))	$this->modo = $this->cuestionario['cuestionario']['modo'];	
		else $this->modo = 2;
		
		//Que imprime en la parte superior (titulo de la categoria)
		if(isset($this->cuestionario['cuestionario']['titulos']))	$this->tipoTitulo = $this->cuestionario['cuestionario']['titulos'];
		else $this->tipoTitulo = 1;
		
		
		
		$this->calificar = $this->cuestionario['cuestionario']['calificar'];
		$this->numeracion = $this->cuestionario['cuestionario']['numeracion'];		
		$this->tipoidentificador = $this->cuestionario['cuestionario']['tipoidentificador'];			


		if(isset($this->cuestionario['cuestionario']['multiplicador']))
			$this->multiplicador = $this->cuestionario['cuestionario']['multiplicador'];
		else $this->multiplicador = null;

 	}

 	/**
 	 * Regresa un arreglo con las categorias existentes
 	 * @return arreglo categorias
 	 */
 	function obtenerCategorias(){

 		return $this->cuestionario['cuestionario']['categorias']['catego'];
 	}


  	/**
 	 * Regresa un arreglo con una categoria
 	 * @return arreglo categorias
 	 */
 	function obtenerCategoria($id){
 		if($this->numCategorias==1){
 			
 			return $this->cuestionario['cuestionario']['categorias']['catego'];
 		}
 		
 		if(isset( $this->cuestionario['cuestionario']['categorias']['catego'][$id]))
	 		return $this->cuestionario['cuestionario']['categorias']['catego'][$id];
	 	else return null;
 	}

 	/**
 	 * Obtiene la cantidad de paginas en base a la cantidad de preguntas por pagina
 	 *TODO Verificar que funcione para numeros cerrados
 	 * @param numero preguntas por pagina
 	 * @return numero cantidad de paginas
 	 */
 	function obtenerCantidadPaginas($cantidad){

 		if($this->modo==1){
	 		$temp = 0;
	 		$pag = $this->numPreguntas/$cantidad;
	 		$temp = (intval($pag));
	 		if(($pag%10)>0)$temp++;
	 		return $temp;
 		}else if($this->modo==2){
 			return $this->numCategorias;
 		}
 		else return 1;
 	}

 	/**
 	 * Obtener las respuestas de un cuestionaro para una pregunta
 	 * @param entero id de la pregunta
 	 * @return arreglo de respuestas
 	 */
   	function obtenerRespuestas($id){
		if(isset($this->cuestionario['cuestionario']['preguntas']['pregunta'][$id]['respuesta'])){
		unset($this->cuestionario['cuestionario']['preguntas']['pregunta'][$id]['respuesta']["_num"]);
		return $this->cuestionario['cuestionario']['preguntas']['pregunta'][$id]['respuesta'];
		}


		else return array();
 	}

  	/**
 	 * Obtener las respuestas de un cuestionaro para una pregunta
 	 * @param entero id de la pregunta
 	 * @return arreglo de respuestas
 	 */
   	function obtenerRespuesta($id){
		if(isset($this->cuestionario['cuestionario']['preguntas']['pregunta'][$id]['respuesta'])){	
			return $this->cuestionario['cuestionario']['preguntas']['pregunta'][$id]['respuesta'];
		}


		else return array();
 	} 	
 	
    function obtenerPreguntaClave($clave){
    	
    	//print_r($this->cuestionario['cuestionario']['preguntas']['pregunta']);
    	
    	foreach($this->cuestionario['cuestionario']['preguntas']['pregunta'] as $item){
    		if($item['clave']==$clave)return $item; 
    	
    		
    	}
    	
		/*if(isset($this->cuestionario['cu/estionario']['preguntas']['pregunta'][$id])){
		return $this->cuestionario['cuestionario']['preguntas']['pregunta'][$id];
		}


		else return array();*/
 	} 	
 	
 	
    function obtenerPregunta($id){
		if(isset($this->cuestionario['cuestionario']['preguntas']['pregunta'][$id])){
		return $this->cuestionario['cuestionario']['preguntas']['pregunta'][$id];
		}


		else return array();
 	} 	

    function obtenerPreguntas(){
		if(isset($this->cuestionario['cuestionario']['preguntas'])){
		return $this->cuestionario['cuestionario']['preguntas'];
		}


		else return array();
 	} 	
 	
  	/**
 	 * Obtener el tipo de candado que se aplica
 	 * @param entero id de la pregunta
 	 * @return id tipo de candado
 	 */
   	function obtenerCandadoPregunta($id){

		if(isset($this->cuestionario['cuestionario']['preguntas']['pregunta'][$id]['candado'])){
			return $this->cuestionario['cuestionario']['preguntas']['pregunta'][$id]['candado'];
		}
		else return 0;
 	} 	

   	function obtenerAtributoPregunta($indice,$att){

		if(isset($this->cuestionario['cuestionario']['preguntas']['pregunta'][$indice][$att])){
			return $this->cuestionario['cuestionario']['preguntas']['pregunta'][$indice][$att];
		}
		else return null;
 	} 	 	
 	
  	function obtenerInstruccines(){
 		if(isset($this->cuestionario['cuestionario']['textos']['instrucciones']))
		return $this->cuestionario['cuestionario']['textos']['instrucciones'];
		else return "";
 	}
 	
   	function obtenerTextoInicio(){
 		if(isset($this->cuestionario['cuestionario']['textos']['inicio']))
		return $this->cuestionario['cuestionario']['textos']['inicio'];
		else return null;
 	} 	
 	
  	function obtenerReglas(){
 		if(isset($this->cuestionario['cuestionario']['reglas'])){
		 	unset($this->cuestionario['cuestionario']['reglas']['regla']['_num']);
 			return $this->cuestionario['cuestionario']['reglas']['regla'];
 		}
 		else return array();
 	}

//***//
  	function isBotonAnterior()
 	{
 		if(isset($this->cuestionario['cuestionario']['botonAnterior']))
		return $this->cuestionario['cuestionario']['botonAnterior'];
		else return null;
 	}
 	function obtenerURLRetroalimentacion()
 	{
 		if(isset($this->cuestionario['cuestionario']['URLRetroalimentacion']))
		return $this->cuestionario['cuestionario']['URLRetroalimentacion'];
		else return null;
 	}
  	function obtenerRetroalimentacion()
 	{
 		if(isset($this->cuestionario['cuestionario']['retroalimentacion']))
 		{
 			return $this->cuestionario['cuestionario']['retroalimentacion'];
 		}
 		else return null;
 	}




 	function obtenerDescripcionPregunta($id){return $this->cuestionario['cuestionario']['preguntas']['pregunta'][$id]['nombre'];}
  	function obtenerTipoPregunta($id){return $this->cuestionario['cuestionario']['preguntas']['pregunta'][$id]['tipo'];}
   	function obtenerValorPregunta($id){return $this->cuestionario['cuestionario']['preguntas']['pregunta'][$id]['valor']; }
   	function obtenerCategoriaPregunta($id){return $this->cuestionario['cuestionario']['preguntas']['pregunta'][$id]['categoria'];}
   	
    function obtenerSubtituloPregunta($id){
    	
    	
    	if(isset($this->cuestionario['cuestionario']['preguntas']['pregunta'][$id]['subtitulo']))
	    	return $this->cuestionario['cuestionario']['preguntas']['pregunta'][$id]['subtitulo'];
	    else return null;
    }
   	
   	function obtenerImagenPregunta($id){
   		if(isset($this->cuestionario['cuestionario']['preguntas']['pregunta'][$id]['imagen']))
   		return $this->cuestionario['cuestionario']['preguntas']['pregunta'][$id]['imagen'];
   		else return null;
   	}
   	
   	
    function obtenerAccion($id){
    	
    	//print_r($this->cuestionario['cuestionario']['acciones']);
   		if(isset($this->cuestionario['cuestionario']['acciones']['accion'][$id]))
   		return $this->cuestionario['cuestionario']['acciones']['accion'][$id];
   		else return "";
   	}   	

    function obtenerDependenciaCandado($id){

   		if(isset($this->cuestionario['cuestionario']['preguntas']['pregunta'][$id]['candadoDepende']))
   		return $this->cuestionario['cuestionario']['preguntas']['pregunta'][$id]['candadoDepende'];
   		else return null;
   	}   	
   	
   	/**
   	 * Regresa el titulo POR pagina
   	 *
   	 * @param unknown_type $pagina
   	 * @return unknown
   	 */
 	function obtenerTitulo($pagina){

 		$data="";
 		switch ($this->tipoTitulo){
 			case 1:return null;//No imprime ningun titulo en la pagina
			case 2:
				$catego = ($this->obtenerCategoria(($pagina-1)));
				$data= $catego['nombre'];
				break;

			default:break;
		}
		return $data;

 	}

 	function obtenerModoTitulo()
 	{

 		return $this->tipoTitulo;


 	}

 	function imprimir()
 	{
 		echo "<pre>";
		print_r($this->cuestionario['cuestionario']);
		echo "</pre>";
 	}


 	function calcularIndiceInicio($pagina){

 		if($this->modo==1){
	 		$indiceInicio = ($pagina-1)*PREGUNTAS_POR_PAGINA;
	 		return $indiceInicio;
 		}else if($this->modo==2){
 			
 			$catego = $this->obtenerCategoria($pagina-1);
 			
 			return $catego['keyPregunta'];
 		}
 		else return 1;
 	}


 	function calcularIndiceFin($pagina){

 		if($this->modo==1){
			$indiceInicio = ($pagina-1)*PREGUNTAS_POR_PAGINA;
			$indiceFin = $indiceInicio+PREGUNTAS_POR_PAGINA	;
	 		return $indiceFin;
 		}else if($this->modo==2){
			
 			if($this->numCategorias==1)return $this->numPreguntas;
 			
 			$catego = $this->obtenerCategoria($pagina);
 			if($catego!=null) return $catego['keyPregunta'];
 			else return $this->numPreguntas;


 		}
 		else return 1;
 	}


 }


?>