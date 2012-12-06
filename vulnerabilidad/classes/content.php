<?php
class content {


	function getTP()
	{

		
		$tags=array(

		'template.header' => '../templates/nova2/tpheader.tp',
		'template.body'=> '../templates/nova2/tpbody.tp',
		'template.footer' => '../templates/nova2/tpfooter.tp',
		'menu.izquierda' => '../templates/nova2/leftMenu.tp',		
		'contenido.subcontenido' => '../templates/nova2/subContent.tp',		
		);
		
		return $tags;

	}

	static function getComboOpciones($nombre,$datos,$class='')
	{
		
		$sel = "";
		
		if($_POST && isset($_POST[$nombre]))$sel = $_POST[$nombre];
		
		
		$data = "";

		$data = "<select   tabindex=\"1\" class=\"".$class."\" name=\"".$nombre."\"  tabindex=\"1\"  >";
			$data.= "<option value=\"-1\">";
			$data.= "--";
			$data.= "</option>\n";		

		foreach($datos as $value)
		{
			

			$data.= "<option value=\"".$value['value']."\" ";
			
			if($sel==$value['value'])$data.= " selected";
			$data.= ">".$value['descripcion'];
			$data.= "</option>\n";
			
		}
		$data .= "</select>";	
		return $data;

	}

	
	
	
	static function generaFilaDeTabla($array,$num)
	{
		
		
	if($array == null){
    	$temp = "\n<tr >";
	  	$temp .= "\n<td  align=\"center\" colspan=\"10\">No existen registros.</td>";
		$temp .= "\n</tr>";
		return $temp;
	}
		$temp = '';	
		
	foreach($array as $tupla)
	{
		$temp.= "\n<tr>";
		foreach($tupla as $data)
		{
			
			$temp.= "\n\t<td>".$data.'</td>';
			
		}
		$temp.="\n</tr>";
				
		
	}
		return $temp;

		
	}
	static function generaFilaDeTablaSinTD($array,$num)
	{
		
		
		if($array == null){
	    	$temp = "<tr >";
		  	$temp .= "<td  align=\"center\" colspan=\"10\">No existen registros.</td>";
			$temp .= "</tr>";
			return $temp;
		}
		$temp = '';	
		
	foreach($array as $tupla)
	{
		$temp.= "<tr onmouseover=\"this.className='onHoverTable';\" onmouseout=\"this.className='';\" >";
		foreach($tupla as $data)
		{
			
			$temp.= ''.$data.'';
			
		}
		$temp.='</tr>';
				
		
	}
		return $temp;

		
	}
	static function armaMensaje(&$data,$titulo,$mensaje)
	{
		$data['template.contenido']= './templates/sac/mensaje.tp';
		$data['mensaje.titulo']= $titulo;
		$data['mensaje.mensaje']= $mensaje;
	
		return $data;
	}	
	
	function getPropiedades()
	{
		
		//$settings = core::coreSettings();
		//$site = $settings['siteDir'];
		/*
		$tags1=array(
		'pagina.index' => $site.'index.php',
		'pagina.index.registra' => $site.'index.php?registra',
		'pagina.index.login' => $site.'index.php?login',		
		'pagina.usuario' => $site.'usuario.php',
		'pagina.logout' => $site.'logout.php',
		'header.titulo' => 'Novacreations',
		'subcontenido.extra' => '',
		);
		
		
		
		$login=new login();
		
		if($login->isLoged())
		{
			$tags['login.forma']= '<h1>Usuario</h1>
			<h3>Estas conectado como '.$_SESSION['usuario_usuario'].'. </h3>
			<p> <a href="{pagina.usuario}">Administracion</a>.<br>Alta de contenido.</p>
			<p> <a href="{pagina.index}">Inicio</a>.<br>Regresa a la pagina principal. </p>	
			<p> <a href="{pagina.logout}">Logout</a>.<br>Salir del sistema</p>';
			
		}
		else		
		$tags['login.forma']='../templates/nova2/formas/login.tp';
		
		
		return array_merge($tags,$tags1);
*/
	}

	
	static function armarMenuUsuario()
	{
/*
	  	require_once(DIRECTORIO.'classes/usuario.php');		
		$usuario = new usuario();
		$dat1 = $usuario->obtenerOpcionesAdmin($_SESSION['usuario_nivel']);
		$dat2 = $usuario->obtenerCantidadOpcionesAdmin($_SESSION['usuario_nivel']);
		$ll['menu.izquierda.principal'] = "<dl class=\"nav3-grid\">".
		content::armarOpcionesadministrativas($dat1,$dat2)." </dl>";		
		return ($ll);
	*/	
	}	

	static function armarMenuGeneral()
	{
		/*
		require_once(DIRECTORIO.'classes/catalogos.php');	
		
		$catalogo = new catalogos();
		
		$data = '<dl class="nav3-grid">' ; 
		$data .= content::armarSubmenu('Archivos',$catalogo->obtenerMenuArchivos(),'archivo'); 
		$data .= content::armarSubmenu('Codigo',$catalogo->obtenerMenuCodigo(),'codigo'); 
		$data .= '</dl>' ; 
		$ll['menu.izquierda.principal']=$data;
		
		return ($ll);
		*/
	}

	
	static function armarSubmenu($titulo,$datos,$action)
	{
			/*
		   $data="
          <dt><a href=\"#\">".$titulo."</a></dt>";
		   
		   foreach($datos as $item)
		   {
		   $data.="<dd><a href=\"?".$action."&idCategoria=".$item['id']."\">".$item['nombre']."</a></dd>";		   	
		   }
		   
		   $data.="";
		
		return ($data);
		*/
	}
	
/*
	static function armarOpcionesadministrativas($datos,$cantidad)
	{	
		
		$resul = array();
		$buffer = "";
		$i = 1;
		$j = -1;
		$k = 0;
		foreach($datos as $value)
		{
			
			
			
			if($i == 1)
			{
			$arrtemp =	$cantidad[$k++];
			

			$buffer .= "<dt><a>".$arrtemp['descripcion']."</a></dt>";
			$j = $arrtemp['valor'];	
			
			} 
			

			$buffer .= "
		
		
				<dd><a href=\"?action=".$value['permiso_clave']."&id=".$value['permiso_id']."\">".$value['permiso_titulo']."</a></dd>";
			
			$i++;
			if( $i > $j)$i = 1;
			
		}
		return $buffer ;
		
		
	}
	
	
	




	

	
	static function armaCuadroAzul($titulo,$texto)
	{
		$data = '        <div class="subcontent-unit-border-blue">';
		
		if($titulo!='')
          $data .= '<div class="round-border-topleft"></div><div class="round-border-topright"></div>
          <h1 class="blue">'.$titul.'</h1>';
     $data .=' <p >'.$texto.'</p>
   
        </div>';
		return utf8_encode($data);
	}
	static function armaCuadroNaranja($titulo,$texto)
	{
		$data = '        <div class="subcontent-unit-border-orange">';
		
		if($titulo!='')
		{
          $data .= '<div class="round-border-topleft"></div><div class="round-border-topright"></div>
          <h1 class="orange">'.$titulo.'</h1>';
		
		}
          $data .=' <p >'.$texto.'</p>
   
        </div>';
		return utf8_encode($data);
	}
	/*/	
	
	static function obtenerMenuExamen(&$mensajes)
	{
		
	
	}
	
	static function obtenerMenuUsuario(&$mensajes)
	{
		
	$mensajes['template.menuoptions']=
	'
		.: <a href="usuario.php">Inicio</a> .::.
		 <a href="usuario.php?usuario">Usuario</a> .::.
		<a href="usuario.php?logout">Logout</a> .::.
		<a href="usuario.php?ayuda">Ayuda</a> :.
		';			
	
	}	
	
}
?>