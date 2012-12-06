<?php

class settings
{
	//4 julio 2005
	function paginaSettings() {
		$pagina['template.maestro']='./templates/sac/aplicacion/menu.tp';
		
		$pagina['template.header']='./templates/sac/header.tp';
		$pagina['template.footer']='./templates/sac/footer.tp';
		$pagina['template.menu']='./templates/sac/menuUp.tp';
		$pagina['template.tooltip']='./templates/tooltip.mensajes';
		
		$pagina['url.inicio'] = URL.'index.php';
		$pagina['url.registro'] =  URL.'index.php?registro';
		$pagina['url.login'] = '';
		$pagina['url.logout'] = '';
		$pagina['url.usuarios'] = URL.'usuario.php?usuarios';
		$pagina['url.usuario'] = URL.'usuario.php';
		$pagina['url.grupos'] = URL.'usuario.php?grupos';
		$pagina['url.sesion'] = URL.'usuario.php?sesion';
		$pagina['url.cuestionarios'] = URL.'usuario.php?cuestionario';

		$pagina['url.examen'] = URL.'examen.php?contestar';
		$pagina['url.resultado'] = URL.'examen.php?resultado';
		$pagina['mensaje.error']='';
		$pagina['mensaje.nota']='';		
		$pagina['logo.main']=URL.'imagenes/logo_sac.jpg';
		
		
		$pagina['imagen.verdetalle'] = "<img src=\"imagenes/b_search.png\" border=\"0\" onMouseOver=\"stm(Text[7],Style[17])\" href=\"#\" onMouseOut=\"htm()\">";
		$pagina['imagen.asignarcuestionario'] = "<img src=\"imagenes/checkbox3.gif\" width=\"16\" border=\"0\"  onMouseOver=\"stm(Text[8],Style[17])\" href=\"#\" onMouseOut=\"htm()\">";
		$pagina['imagen.agregar'] = "<img border=\"0\" width=\"16\" src=\"imagenes/page-add.gif\">";
		$pagina['imagen.bajaalumno'] = "<img onMouseOver=\"stm(Text[6],Style[17])\" href=\"#\" onMouseOut=\"htm()\" src=\"imagenes/delete3.gif\" border=\"0\" >";
		$pagina['imagen.verCuestionario']="<img src=\"imagenes/b_search.png\" border=\"0\" alt=\"Ver Detalles\" onMouseOver=\"stm(Text[16],Style[17])\" onMouseOut=\"htm()\" >";
		$pagina['imagen.editarCuestionario']="<img onMouseOver=\"stm(Text[17],Style[17])\" onMouseOut=\"htm()\" src=\"imagenes/b_edit.png\" border=\"0\" alt=\"Editar\">";
		$pagina['imagen.guardarCuestionario']="<img onMouseOver=\"stm(Text[18],Style[17])\" onMouseOut=\"htm()\" src=\"imagenes/b_save.png\" border=\"0\" alt=\"Guardar\">";				
		$pagina['imagen.activar']="<img onMouseOver=\"stm(Text[15],Style[2])\" onMouseOut=\"htm()\" src=\"imagenes/help_icon.gif\" width=\"11\" height=\"11\" border=\"0\">";
		
		return $pagina;
	}
	
}

?>