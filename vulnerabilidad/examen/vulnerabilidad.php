<?php
	function enRango($val,$min,$max=null){
		if($max==null && $val==$min)return true;
		else if($val>=$min && $val <=$max)return true;
		else return false;
	}


class vulnerabilidad{

	private $resultados;
	private $analisis = null;
	private $analisisVulne = null;
	private $analisisVulneTotal = null;

	function obtenerAnalisis(){
		if($this->analisis==null)$this->analisaResultados();
		return $this->analisis;
	}
	function obtenerAnalisisVulne(){
		if($this->analisis==null)$this->analisaResultados();
		if($this->analisisVulne==null)$this->analisaNivelVulnerabilidad();
		return $this->analisisVulne;
	}

	function obtenerAnalisisVulneSumatoria(){
		if($this->analisis==null)$this->analisaResultados();
		if($this->analisisVulne==null)$this->analisaNivelVulnerabilidad();
		$sum = 0;
		foreach($this->analisisVulne as $res)$sum+=$res;

		return $sum;
	}

	function obtenerResultados(){
		return $this->resultados;
	}

	function obtenerIndiceVulnerabilidad(){
		if($this->analisis==null)$this->analisaResultados();
		if($this->analisisVulne==null)$this->analisaNivelVulnerabilidad();
		return $this->analisisVulneTotal;
	}

	function cargaresultados($idaplicacion){

	    $res = new resultados();
	    $arrRes = $res->obtenerResultadosSeccionesNumericas($idaplicacion);

	    unset($arrRes[0]);
	    $resultados = array();

	    foreach($arrRes as $item){
	    	if($item['resultados_value']==9||$item['resultados_value']==99)$resultados[$item['resultados_seccion']]=0;
	    	else {
	    		$resultados[$item['resultados_seccion']] = str_replace("#","",str_replace(" / ","",$item['resultados_value']));
	    	}
	    }
	    $this->resultados = &$resultados;

	}

	function obtenerCategoriasAnalisisLabels(){
		return array(
		 "1. Rel papá",
		 "2. Rel papá afecto-comunic",
		 "3. Rel papá control",
		 "4. Rel mamá",
		 "5. Rel mamá afecto-comunic",
		 "6. Rel mamá control",
		 "7. Edo. emoc",
		 "8. Autoestima",
		 "9. Pensamiento",
		 "10. Asertividad",
		 "11. Reglas en casa",
		 "12. Reglas escuela",
		 "13. Amigos",
		 "14. Tiempo Libre",
		 "15. Acts. culturales",
		 "16. Acts. descanso-recre",
		 "17. Ayuda en casa",
		 "18. Motivos",
		 "19. Alimentación",
		 "20. Percep. satisf.",
		 "21. Tabaco",
		 "22. Alcohol",
		 "23. Sust. med.",
		 "24. Drog. ileg."
		 );
	}

	function analisaResultados(){

		$resultados = &$this->resultados;
	    $datay=array();

		$datay[] = $resultados['13'] + $resultados['14'] + $resultados['15'] + $resultados['16'] + $resultados['17'] + $resultados['18'] + $resultados['19'] + $resultados['20'] + $resultados['21'] + $resultados['22'] + $resultados['23'] + $resultados['24'] + $resultados['25'] + $resultados['26'];
		$datay[]= $resultados['13'] + $resultados['14'] + $resultados['15'] + $resultados['16'] + $resultados['17'] + $resultados['18'] + $resultados['19'] + $resultados['23'] + $resultados['24'] + $resultados['25'] + $resultados['26'];
		$datay[]= $resultados['20'] + $resultados['21'] + $resultados['22'];
		$datay[]= $resultados['30'] + $resultados['31'] + $resultados['32'] + $resultados['33'] + $resultados['34'] + $resultados['35'] + $resultados['36'] + $resultados['37'] + $resultados['38'] + $resultados['39'] + $resultados['40'] + $resultados['41'] + $resultados['42'] + $resultados['43'];
		$datay[]= $resultados['30'] + $resultados['31'] + $resultados['32'] + $resultados['33'] + $resultados['34'] + $resultados['35'] + $resultados['36'] + $resultados['40'] + $resultados['41'] + $resultados['42'] + $resultados['43'];
		$datay[]= $resultados['37'] + $resultados['38'] + $resultados['39'];
		$datay[]= $resultados['79'] + $resultados['80'] + $resultados['81'] + $resultados['82'] + $resultados['83'] + $resultados['84'] + $resultados['85'] + $resultados['86'] + $resultados['87'] + $resultados['88'] + $resultados['89'] + $resultados['90'];
		$datay[]= $resultados['97'] + $resultados['98'] + $resultados['99'] + $resultados['100'] + $resultados['101'] + $resultados['102'] + $resultados['103'] + $resultados['104'] + $resultados['105'] + $resultados['106'] + $resultados['107'] + $resultados['108'] + $resultados['109'];
		$datay[]= $resultados['111'] + $resultados['112'] + $resultados['113'] + $resultados['114'];
		$datay[]= $resultados['115'] + $resultados['116'] + $resultados['117'] + $resultados['118'] + $resultados['119'];
		$datay[]= $resultados['120'] + $resultados['121']+ $resultados['122'];
		$datay[]= $resultados['125'] + $resultados['126'] + $resultados['127'] + $resultados['128'];
		$datay[]= $resultados['152'] + $resultados['159'] + $resultados['162'];
		$datay[] = $resultados['15'] + $resultados['151'] + $resultados['152'] + $resultados['153'] + $resultados['154'] + $resultados['155'] + $resultados['156'] + $resultados['157'] + $resultados['158'] + $resultados['159'] + $resultados['160'] + $resultados['161'] + $resultados['162'] + $resultados['163'] + $resultados['164'] + $resultados['165'] + $resultados['166'] + $resultados['167']+ $resultados['168'];
		$datay[]= $resultados['163'] + $resultados['166'] + $resultados['168'];
		$datay[]= $resultados['150'] + $resultados['153'] + $resultados['164'];
		$datay[]= $resultados['154'] + $resultados['155'] + $resultados['167'];
		$datay[]= $resultados['181'] + $resultados['182'] + $resultados['183'] + $resultados['184'] + $resultados['185'];
		$datay[]= $resultados['186'] + $resultados['187'] + $resultados['188'] + $resultados['189'] + $resultados['190'] + $resultados['191'] + $resultados['192'] + $resultados['193'] + $resultados['194'] + $resultados['195'] + $resultados['196'] + $resultados['197'];
		$datay[]= $resultados['27'] + $resultados['28'] + $resultados['44'] + $resultados['45'] + $resultados['91'] + $resultados['110'] + $resultados['147'] + $resultados['169'];


		// ************ PARA CONSIDERAR TIPO DE CONSUMO Y DEPENDENCIA DE TABACO  *********.
		if($resultados['48']=='2')$datay[]='0';
		else if($resultados['48']=='1' && $resultados['49']=='1' && $resultados['48']=='5')$datay[]='1';
		else if($resultados['50']=='3' || $resultados['50']=='4' )$datay[]='2';
		else if($resultados['50']=='2')$datay[]='3';
		else if($resultados['50']=='1' && ($resultados['51']==1 || $resultados['51']==2 ) && ($resultados['52']==1 || $resultados['52']==2 ))$datay[]='5';
		else if($resultados['50']=='1' && (($resultados['51']==1 || $resultados['51']==2 ) || ($resultados['52']==1 || $resultados['52']==2 )))$datay[]='5';
		else $datay[]='0';

		// ************ PARA OBTENR EL PUNTAJE DE CONSUMO DE ALCOHOL EN EL AUDIT *********.
		if($resultados['57']=='2')$datay[]='0';
		else if($resultados['57']=='1' && $resultados['58']=='1' && $resultados['59']=='0')$datay[]='1';
		else{
			//18 junio 09
			$val59 = $resultados['59'];
			$val60 = $resultados['60'];
			$val61 = $resultados['61'];

			if($val59>=1 && $val59<=4)$val59=1;
			else if($val59==0)$val59=0;

			if($val61>=1 && $val61<=4)$val61=1;
			else if($val61==0)$val59=0;

			$datay[]= $val59 + $val60 + $val61;

		}

		//*********PARA CONSIDERAR TIPO DE CONSUMO DE SUSTANCIAS M?DICAS
		if($resultados['171']=='2')$datay[]='0';
		else if($resultados['171']=='1' && $resultados['173']=='2')$datay[]='1';
		else if($resultados['171']=='1' && $resultados['173']=='1' && $resultados['175']=='5')$datay[]='2';
		else if($resultados['175']=='3' || $resultados['175']=='4')$datay[]='3';
		else if($resultados['175']=='2')$datay[]='4';
		else if($resultados['175']=='1')$datay[]='5';
		else $datay[]='0';

		//*********PARA CONSIDERAR TIPO DE CONSUMO DE DROGAS ILEGALES

		if($resultados['170']=='2')$datay[]='0';
		else if($resultados['170']=='1' && $resultados['172']=='2')$datay[]='1';
		else if($resultados['170']=='1' && $resultados['172']=='1' && $resultados['174']=='5')$datay[]='2';
		else if($resultados['174']=='3' || $resultados['174']=='4')$datay[]='3';
		else if($resultados['174']=='2')$datay[]='4';
		else if($resultados['174']=='1')$datay[]='5';
		else $datay[]='0';

		$this->analisis = $datay;

	}

	function analisaNivelVulnerabilidad(){



		$resultados = &$this->resultados;
		$datay = &$this->analisis;
		$datay_bis = array_fill(0,24,'0');
		$c=0;


		if($resultados[4]==1){//PREPARATORIA
			if(enRango($datay[$c],1,37))$datay_bis[$c]=1; //Rel papá
			if(enRango($datay[++$c],1,28))$datay_bis[$c]=1;
			if(enRango($datay[++$c],1,5))$datay_bis[$c]=1;
			if(enRango($datay[++$c],1,40))$datay_bis[$c]=1;
			if(enRango($datay[++$c],1,31))$datay_bis[$c]=1;
			if(enRango($datay[++$c],1,7))$datay_bis[$c]=1;
			if(enRango($datay[++$c],5,12))$datay_bis[$c]=1;//Edo. emoc
			if(enRango($datay[++$c],1,35))$datay_bis[$c]=1;//Autoestima
			if(enRango($datay[++$c],3,12))$datay_bis[$c]=1;//Pensamiento
			if(enRango($datay[++$c],1,13))$datay_bis[$c]=1;//"Asertividad" 10
			if(enRango($datay[++$c],1,8))$datay_bis[$c]=1;
			if(enRango($datay[++$c],1,11))$datay_bis[$c]=1;
			if(enRango($datay[++$c],1,4))$datay_bis[$c]=1;
			if(enRango($datay[++$c],1,45))$datay_bis[$c]=1;
			if(enRango($datay[++$c],1,3))$datay_bis[$c]=1;
			if(enRango($datay[++$c],1,6))$datay_bis[$c]=1;
			if(enRango($datay[++$c],1,5))$datay_bis[$c]=1;
			if(enRango($datay[++$c],7,10))$datay_bis[$c]=1;
			if(enRango($datay[++$c],15,39))$datay_bis[$c]=1;
			if(enRango($datay[++$c],1,23))$datay_bis[$c]=1;//Percep. satisf 20
			if(enRango($datay[++$c],5))$datay_bis[$c]=1;
			if(enRango($datay[++$c],4,12))$datay_bis[$c]=1;
			if(enRango($datay[++$c],3,5))$datay_bis[$c]=1;
			if(enRango($datay[++$c],3,5))$datay_bis[$c]=1;
		}
		else if($resultados[4]==2){
			if(enRango($datay[$c],1,36))$datay_bis[$c]=1; //Rel papá
			if(enRango($datay[++$c],1,28))$datay_bis[$c]=1;
			if(enRango($datay[++$c],1,6))$datay_bis[$c]=1;
			if(enRango($datay[++$c],1,41))$datay_bis[$c]=1;
			if(enRango($datay[++$c],1,32))$datay_bis[$c]=1;
			if(enRango($datay[++$c],1,7))$datay_bis[$c]=1;
			if(enRango($datay[++$c],5,12))$datay_bis[$c]=1;//Edo. emoc
			if(enRango($datay[++$c],1,35))$datay_bis[$c]=1;//Autoestima
			if(enRango($datay[++$c],3,12))$datay_bis[$c]=1;//Pensamiento
			if(enRango($datay[++$c],1,14))$datay_bis[$c]=1;//"Asertividad" 10
			if(enRango($datay[++$c],1,8))$datay_bis[$c]=1;
			if(enRango($datay[++$c],1,11))$datay_bis[$c]=1;
			if(enRango($datay[++$c],1,4))$datay_bis[$c]=1;
			if(enRango($datay[++$c],1,44))$datay_bis[$c]=1;
			if(enRango($datay[++$c],1,4))$datay_bis[$c]=1;
			if(enRango($datay[++$c],1,6))$datay_bis[$c]=1;
			if(enRango($datay[++$c],1,5))$datay_bis[$c]=1;//Ayuda en casa
			if(enRango($datay[++$c],6,10))$datay_bis[$c]=1;//Motivos
			if(enRango($datay[++$c],16,39))$datay_bis[$c]=1;//AlimentaciOn
			if(enRango($datay[++$c],1,24))$datay_bis[$c]=1;//Percep. satisf 20
			if(enRango($datay[++$c],5))$datay_bis[$c]=1;
			if(enRango($datay[++$c],4,12))$datay_bis[$c]=1;
			if(enRango($datay[++$c],3,5))$datay_bis[$c]=1;//Sust. med
			if(enRango($datay[++$c],3,5))$datay_bis[$c]=1;//Drog. ileg
		}

		$this->analisisVulne = $datay_bis;
		$total = 0;
		foreach($datay_bis as $item){
			$total += $item;

		}
		$this->analisisVulneTotal = $total;

	}


}


?>