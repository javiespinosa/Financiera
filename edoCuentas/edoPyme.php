<?php
date_default_timezone_set('America/Mexico_City');
$Folio=$_GET['folio'];
$fecha_solicitada=$_GET['fecha'];
$dias_vencidos=$_GET['dias_vencidos'];
$respuesta=0;
//$dias_transf=$_GET['dias_trans'];
$dias_transf=0;
$saldo_capital=0;
include ("../../modulos/cobranza/conexion/conn.php");
$query01="SELECT 
		 credito.id as idcredito,
		 credito.tipo_pago,
		 credito.plazo_meses,
		 cliente.id,
		 cliente.nombre,
		 cliente.apellidos,
		 cliente.rfc,
		 cliente.direccion,
		 cliente.localidad,
		 cliente.municipio,
		 cliente.efederativa,
		 credito.folio,
		 credito.cliente_id,
		 credito.monto,
		 credito.tasa_interes_ordinaria,
		 credito.fecha_contrato,
		 credito.fecha_ministracion
					FROM cliente, credito
						WHERE credito.cliente_id=cliente.id 
							AND credito.folio='".$Folio."'";
$result01 = mysqli_query($con,$query01);
while($row01 = mysqli_fetch_array($result01,MYSQLI_ASSOC)) 
{
	$id=$row01['id'];
	$idcredito=$row01['idcredito'];
	$modalidadC=$row01['tipo_pago'];
	$plazoC=$row01['plazo_meses'];
	$nombre=$row01['nombre'];
	$apellidos=$row01['apellidos'];
	$rfc=$row01['rfc'];
	$direccion=$row01['direccion'];
	$localidad=$row01['localidad'];
	$municipio=$row01['municipio'];
	$efederativa=$row01['efederativa'];
	$monto=$row01['monto'];
	$fecha_ministracion=$row01['fecha_ministracion'];
	$tasa=$row01['tasa_interes_ordinaria'];
	$fcontrato=$row01['fecha_contrato'];
	//$nombregrupo=$row01['nombreg'];
}

					if ($modalidadC=="SEMANAL") {
		    			# code...
		    			$modalidadC=8;
		    		}elseif ($modalidadC=="QUINCENAL") {
		    			# code...
		    			$modalidadC=15;
		    		}elseif ($modalidadC=="MENSUAL") {
		    			# code...
		    			$modalidadC=30;
		    		}elseif ($modalidadC=="BIMESTRAL") {
		    			# code...
		    			$modalidadC=60;
		    		}elseif ($modalidadC=="TRIMESTRAL") {
		    			# code...
		    			$modalidadC=90;
		    		}elseif ($modalidadC=="SEMESTRAL") {
		    			# code...
		    			$modalidadC=180;
		    		}elseif ($modalidadC="ANUAL") {
		    			# code...
		    			$modalidadC=360;
		    		}

$fecha_minis1 = strtotime ( $fecha_ministracion) ;
$fecha_minis1=date('d-m-Y', $fecha_minis1);

//Nombre de la fuente
$query02="select fondeadora.nombre from fondeadora,credito,disposicion,linea_madre where credito.disposicion_id=disposicion.id and disposicion.linea_madre_id=linea_madre.id and linea_madre.fondeadora_id=fondeadora.id and credito.folio='".$Folio."'";
$result02 = mysqli_query($con,$query02);
while($row02 = mysqli_fetch_array($result02,MYSQLI_ASSOC)) 
{
	$fondeadora=$row02['nombre'];
}

//Sumando abonos capital e intereses
$query03="SELECT id,fecha_pago 
			FROM pago 
				WHERE pago.estatus='VIGENTE' 
					AND credito_id='".$idcredito."' limit 1";
$result03 = mysqli_query($con,$query03);
while($row03 = mysqli_fetch_array($result03,MYSQLI_ASSOC)) 
{
	$idpago=$row03['id'];
	$fechapp = $row03['fecha_pago'];
	//echo $fechapp;
}

//hacemos una consulta nueva..
$query011="SELECT id,fecha_pago FROM pago WHERE pago.estatus='VIGENTE' AND credito_id='".$idcredito."' AND fecha_pago<'".$fecha_solicitada."'";
$result011=mysqli_query($con,$query011);
while ($row011=mysqli_fetch_array($result011,MYSQLI_ASSOC)) {
	# code...
	$id_pagos=$row011['id'];
	$fecha_pagos=$row011['fecha_pago'];
}

$query04="SELECT sum(monto) as capital 
				FROM abono 
					WHERE tipo_pago='0' and pago_id='".$idpago."'";
$result04 = mysqli_query($con,$query04);
while($row04 = mysqli_fetch_array($result04,MYSQLI_ASSOC)) 
{
	$capital_total=$row04['capital'];
}

$query05="SELECT sum(monto) as interes 
			FROM abono 
				WHERE tipo_pago='1' and pago_id='".$idpago."'";
$result05 = mysqli_query($con,$query05);
while($row05 = mysqli_fetch_array($result05,MYSQLI_ASSOC)) 
{
	$interes_total=$row05['interes'];
}
$query10="SELECT max(fecha_deposito) AS fdeposito 
			FROM abono 
				WHERE pago_id='".$idpago."'";
$result10 = mysqli_query($con,$query10);	
while($row10 = mysqli_fetch_array($result10,MYSQLI_ASSOC)) 
{
	$fecha_upago=$row10['fdeposito'];
	
}
//vemos si no pagaron antes
$queryFm="SELECT monto_capital, fecha_pago_m FROM pago WHERE pago.id=$idpago";
$resultFm = mysqli_query($con,$queryFm);	
while($rowF = mysqli_fetch_array($resultFm,MYSQLI_ASSOC)) 
{
	$capitalPago =$rowF['monto_capital'];
	$fechaAnticipada=$rowF['fecha_pago_m'];
	
}

$fechaPago = strtotime ( '+365 day' , strtotime ( $fecha_ministracion ) ) ;
$fechaPago = date ( 'Y-m-j' , $fechaPago );
$fecha2=$fecha_ministracion;

if (isset($fecha_upago) or !empty($fecha_upago)) {
	# code...
	$fecha_ministracion=$fecha_upago;
}elseif (isset($fechaAnticipada) or !empty($fechaAnticipada)) {
	$fecha_ministracion=$fechaAnticipada;
}
else {
	$fecha_ministracion=$fecha_ministracion;
}

//Obtenemos el total de abonos hechos en refaccionario
$queryAbonosP = "SELECT sum(a.monto) as monto FROM abono as a WHERE a.pago_id=$idpago AND a.tipo_pago='0'";
$resultAbonoP = mysqli_query($con,$queryAbonosP);
$totalAbonosP = 0;
while($rowAbonoP = mysqli_fetch_array($resultAbonoP,MYSQLI_ASSOC)) 
{
	$totalAbonosP = $rowAbonoP['monto'];
}

$saldoRetanteP =  $capitalPago-$totalAbonosP;
//Obtenemos el total de abonos hechos en refaccionario
$queryAbonos = "SELECT sum(a.monto) as monto FROM abono as a INNER JOIN pago as p ON p.id=a.pago_id INNER JOIN credito as c ON c.id=p.credito_id WHERE c.id=$idcredito AND a.tipo_pago='0'";

$resultAbono = mysqli_query($con,$queryAbonos);
$totalAbonos = 0;
while($rowAbono = mysqli_fetch_array($resultAbono,MYSQLI_ASSOC)) 
{
	$totalAbonos = $rowAbono['monto'];
}
$saldoRetante =  $monto-$totalAbonos;
$tasa_interes=$tasa/100;
//Obtenemos el total de abonos hechos en refaccionario en intereses
$queryAbonosI = "SELECT sum(monto) as mont FROM abono where pago_id='".$idpago."' and tipo_pago='1' ";

$resultAbonoI = mysqli_query($con,$queryAbonosI);
$totalIntereses = 0;
while($rowAbonoI = mysqli_fetch_array($resultAbonoI,MYSQLI_ASSOC)) 
{
	$totalIntereses = $rowAbonoI['mont'];
}
$queryAbonosIT = "SELECT sum(a.monto) as monts FROM abono as a INNER JOIN pago as p ON p.id=a.pago_id INNER JOIN credito as c ON c.id=p.credito_id WHERE c.id=$idcredito AND a.tipo_pago='1'";
$resultAbonoI = mysqli_query($con,$queryAbonosIT);
while($rowAbonosI = mysqli_fetch_array($resultAbonoI,MYSQLI_ASSOC)) 
{
	$abono_Intereses = $rowAbonosI['monts'];
}
$ivaA=0;
$sqlA="SELECT
		a.pago_id,a.tipo,a.fecha_deposito,a.no_autorizacion,a.comentarios,
					sum(if(tipo_pago=1, monto,null))as capital,
					sum(if(tipo_pago=2, monto,null))as interes,
					sum(if(tipo_pago=3, monto,null)) as moratorio,
					sum(if(tipo_pago=4, monto,null)) as iva,
					sum(monto) as total
					FROM
					abono a
					WHERE pago_id='".$idpago."'
					group by
					fecha_deposito ";
		$respuestaA=mysqli_query($con,$sqlA);
		while ($fila=mysqli_fetch_array($respuestaA)) {
			# code...
			$capitalA=$fila['capital'];
			$interesA=$fila['interes'];
			$moratotioA=$fila['moratorio'];
			$ivaA=$fila['iva'];
		}

if($totalIntereses>0){
	$fecha_ministracion= $fechaAnticipada;
}else{
	$fecha_ministracion=$fecha_ministracion;
}

$fechahoy=$fecha_solicitada;
//echo $monto;
//calcular dias transcurridos
$dias_trans=(strtotime($fechahoy)-strtotime($fecha_ministracion))/86400;
$dias_trans=abs($dias_trans);	

$interes_diario=round((($monto*$tasa_interes)/30),0);

$time=round(($plazoC*30)/$modalidadC,0);
$interes_ordinario=round((($monto*$tasa_interes)*$plazoC),0);
if ($totalIntereses>0) {
	# code...
	$interes_pago=round(($interes_ordinario/$time)-$totalIntereses,0);
}else{
$interes_pago=round($interes_ordinario/$time,0);	
}


if($totalAbonosP>0){
$interes_hoy =round($interes_pago,0);
$capitalPago = $saldoRetanteP; /** se comentario, descomentariar despues**/
}
else if($totalIntereses>0){
$interes_hoy=round($interes_pago,0);
$interes_hoy=$interes_hoy-$totalIntereses;	

}else{
$interes_hoy=round(($interes_pago),0);	

}

if ($ivaA>0 && $fechahoy<$fechapp) {
	# code...
	$iva=round(($interes_pago*16)/100,0)-$ivaA;
}else{
$iva=round(($interes_hoy*16)/100,0);	
}

/*if ($respuesta==1) {
		# code...
		$saldoRetanteP=$saldoRetante;
		$totalcapital=$saldoRetanteP;
		$totalcapital_001=$capitalPago;
	}
	else{
		$saldoRetanteP=$saldoRetanteP;
		$totalcapital=$capitalPago;
	}*/
	
if (strtotime($fechahoy) >strtotime($fechapp)){
	
	if ($totalIntereses>0) {
			# code...
	$dias_vencidos=$dias_vencidos;

	$dias_vencidos=round(abs($dias_vencidos));
		}else{
	$dias_vencidos=$dias_vencidos;

	$dias_vencidos=round(abs($dias_vencidos));
		}
		
	if ($respuesta==1) {
		# code...


	$interes_mora=round((($capitalPago*$tasa_interes*$dias_vencidos)/30)*2,0);		
	}else{
		
		$interes_mora=round((($capitalPago*$tasa_interes*$dias_vencidos)/30)*2,0);
	}
	if ($interes_mora>0) {
		# code...
		$iva=round((($interes_hoy*16/100)+$interes_mora*16/100),0);
	}
	$saldo_vencido=$interes_hoy+$interes_mora;

	if ($fechahoy>$fechapp) {
			$capital_vencido=$capitalPago;
		if ($id_pagos!=$idpago) {
			$capital_vencido=$saldoRetante;
			}else{
		if ($dias_vencidos>0) {
		# code...
			$capital_vencido=$capitalPago;
		}else{$capital_vencido=0;}
  			} 
		}
	//$capital_vencido=$saldoRetanteP;

	$interes_vencido=$interes_hoy;
	$total_vencido=$capital_vencido+$interes_vencido+$interes_mora;
	
	$pago_hoy=$capital_vencido+$interes_hoy+$interes_mora;

}
elseif (strtotime($fechahoy)==strtotime($fechaPago)) {
	# code...
	$interes_mora=0;
	$dias_vencidos=0;
	$capital_vencido=0;
	$interes_vencido=0;
	$saldo_vencido=0;
	$total_vencido=0;
	$total_vencido=$capital_vencido+$interes_vencido+$interes_mora;
	$pago_hoy=$saldoRetanteP+$interes_hoy+$interes_mora;
}
else{
	$interes_mora=0;
	$dias_vencidos=0;
	$capital_vencido=0;
	$interes_vencido=0;
	$saldo_vencido=0;
	$total_vencido=0;
	$total_vencido=$capital_vencido+$interes_vencido+$interes_mora;



	$pago_hoy=$saldoRetanteP+$interes_hoy+$interes_mora;
}


//fin
//Fecha de ministracion


//Conversion de fecha hoy
$fecha_hoy=$fecha_solicitada;
$dia=date("d", strtotime($fecha_hoy));

$mes = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
$mes=$mes[date("n", strtotime($fecha_hoy))-1];
$year=date("Y", strtotime($fecha_hoy));

//conversion de fecha de ministracion

$diam=date("d", strtotime($fecha2));

$mesm = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
$mesm=$mesm[date("n", strtotime($fecha2))-1];
$yearm=date("Y", strtotime($fecha2));

$sqlf="SELECT max(p.fecha_pago)as fvencimiento 
from pago p, credito c
where p.credito_id=c.id
and c.folio='".$Folio."'";
$res=mysqli_query($con,$sqlf);
while ($fila=mysqli_fetch_array($res)) {
	# code...
	$fecha_vencimiento=$fila['fvencimiento'];
}

$diav=date("d", strtotime($fecha_vencimiento));

$mesv = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
$mesv=$mesv[date("n", strtotime($fecha_vencimiento))-1];
$yearv=date("Y", strtotime($fecha_vencimiento));


require('classfpdf/fpdf.php');

class PDF extends FPDF
{
	//Cabecera de página
  	function Header()
  	{
  		/// Logo
		$this->Image('LOGOCAPP.png',10,8,33);
		
		// Arial bold 15
		$this->SetFont('Arial','',10);
		// Movernos a la derecha
		$this->Cell(40);
		// Título
		$this->Multicell(120,4,'CONSULTORES ASOCIADOS EN PRODUCCION',0,'C');
		// Salto de línea
		$this->Ln(1);
		$this->Cell(40);
		$this->Multicell(120,4,'PECUARIA S.A. DE .C.V. SOFOM E.N.R.',0,'C');
		$this->SetFont('Arial','',10);
		$this->Ln(1);
		$this->Cell(40);
		$this->SetFont('Arial','',8);
		//$this->Multicell(120,4,'R.F.C.: CAP-020715-JE3',0,'C');
		$this->SetFont('Arial','',10);
		$this->Ln(5);
		$this->Cell(196.3,1,'','B');
		$this->Ln(2);
	}
   //Pie de página -- desactivado -- comentariado
   function Footer()
   {
   		//Posición: a 1,5 cm del final
   		$this->SetY(-17);
		$this->Cell(196.3,1,'','B');
		$this->Ln(2);
		$this->SetFont('Arial','',7);
		$this->Multicell(0,4,'Oficina 8a Oriente Sur No. 125, Tuxtla Gutierrez, Chiapas, CP 29000, Col. Centro','C','C');
		$this->Multicell(0,4,'Tel/Fax: 961 61 24882 Email: cappsc@hotmail.com','C','C');
   }
   //Tabla_1
	function Tabla_1($header)
	{
		//---------fin de consulta para la tabla 1
		//Colores, ancho de línea y fuente en negrita
		$this->SetFillColor(0,120,140);
		$this->SetTextColor(255);
		$this->SetDrawColor(0,0,0);
		$this->SetLineWidth(.3);
		$this->SetFont('','B');
		//Cabecera

		//Restauración de colores y fuentes
		$this->SetFillColor(255,255,255);
		$this->SetTextColor(0);
		$this->SetFont('');
		$this->Image('Fiscal.png',1,59,80);
		//Datos
		/*$fill=false;
		$this->Cell(43.28,5,'','LT',0,'C',$fill);
		$this->Cell(43.25,5,'','TR',0,'C',$fill);
		$this->Cell(15.3,5,'','LR',0,'C',$fill);
		$this->Cell(43.25,5,'','LT',0,'C',$fill);
		$this->Cell(51.25,5,'','TR',0,'C',$fill);
		$this->Ln();
		   $fill=!$fill;
		$this->Cell(43.25,5,'','L',0,'C',$fill);
		$this->Cell(43.25,5,'','R',0,'C',$fill);
		$this->Cell(15.3,5,'','LR',0,'C',$fill);
		$this->Cell(43.25,5,'','L',0,'C',$fill);
		$this->Cell(51.25,5,'','R',0,'C',$fill);
		$this->Ln();
		$fill=!$fill;
		$this->Cell(43.25,5,'','L',0,'C',$fill);
		$this->Cell(43.25,5,'','R',0,'C',$fill);
		$this->Cell(15.3,5,'','LR',0,'C',$fill);
		$this->Cell(43.25,5,'','L',0,'C',$fill);
		$this->Cell(51.25,5,'','R',0,'C',$fill);
		$this->Ln();
		$fill=!$fill;
		$this->Cell(43.25,5,'','L',0,'C',$fill);
		$this->Cell(43.25,5,'','R',0,'C',$fill);
		$this->Cell(15.3,5,'','LR',0,'C',$fill);
		$this->Cell(43.25,5,'','L',0,'C',$fill);
		$this->Cell(51.25,5,'','R',0,'C',$fill);
		$this->Ln();
		$fill=!$fill;
		$this->Cell(43.25,5,'','L',0,'C',$fill);
		$this->Cell(43.25,5,'','R',0,'C',$fill);
		$this->Cell(15.3,5,'','LR',0,'C',$fill);
		$this->Cell(43.25,5,'','L',0,'C',$fill);
		$this->Cell(51.25,5,'','R',0,'C',$fill);
		$this->Ln();
		   $fill=!$fill;
		$this->Cell(43.25,5,'','L',0,'C',$fill);
		$this->Cell(43.25,5,'','R',0,'C',$fill);
		$this->Cell(15.3,5,'','LR',0,'C',$fill);
		$this->Cell(43.25,5,'','L',0,'C',$fill);
		$this->Cell(51.25,5,'','R',0,'C',$fill);
		$this->Ln();
		$fill=!$fill;
		$this->Cell(43.25,5,'','L',0,'C',$fill);
		$this->Cell(43.25,5,'','R',0,'C',$fill);
		$this->Cell(15.3,5,'','LR',0,'C',$fill);
		$this->Cell(43.25,5,'','L',0,'C',$fill);
		$this->Cell(51.25,5,'','R',0,'C',$fill);
		$this->Ln();
		$fill=!$fill;
		$this->Cell(43.25,5,'','L',0,'C',$fill);
		$this->Cell(43.25,5,'','R',0,'C',$fill);
		$this->Cell(15.3,5,'','LR',0,'C',$fill);
		$this->Cell(43.25,5,'','L',0,'C',$fill);
		$this->Cell(51.25,5,'','R',0,'C',$fill);
		$this->Ln();
		$fill=!$fill;
		$this->Cell(43.25,5,'','L',0,'C',$fill);
		$this->Cell(43.25,5,'','R',0,'C',$fill);
		$this->Cell(15.3,5,'','LR',0,'C',$fill);
		$this->Cell(43.25,5,'','L',0,'C',$fill);
		$this->Cell(51.25,5,'','R',0,'C',$fill);
		$this->Ln();
		$fill=!$fill;
		$this->Cell(43.25,5,'','BL',0,'C',$fill);
		$this->Cell(43.25,5,'','BR',0,'C',$fill);
		$this->Cell(15.3,5,'','LR',0,'C',$fill);
		$this->Cell(43.25,5,'','BL',0,'C',$fill);
		$this->Cell(51.25,5,'','BR',0,'C',$fill);
		$fill=true;
		   $this->Ln();*/
	}
	//TABLA 2
	function Tabla_2($header)
	{
		//---------fin de consulta para la tabla 1
		//Colores, ancho de línea y fuente en negrita
		$this->SetFillColor(0,120,140);
		$this->SetTextColor(255);
		$this->SetDrawColor(0,0,0);
		$this->SetLineWidth(.3);
		$this->SetFont('','B');
		//Cabecera

		//Restauración de colores y fuentes
		$this->SetFillColor(255,255,255);
		$this->SetTextColor(0);
		$this->SetFont('');
		//Datos
		$fill=false;
		$this->Cell(43.25,5,'','LT',0,'C',$fill);
		$this->Cell(43.25,5,'','T',0,'C',$fill);
		$this->Cell(15.3,5,'','T',0,'C',$fill);
		$this->Cell(43.25,5,'','T',0,'C',$fill);
		$this->Cell(51.25,5,'','TR',0,'C',$fill);
		$this->Ln();
		   $fill=!$fill;
		$this->Cell(43.25,5,'','L',0,'C',$fill);
		$this->Cell(43.25,5,'','',0,'C',$fill);
		$this->Cell(15.3,5,'','',0,'C',$fill);
		$this->Cell(43.25,5,'','',0,'C',$fill);
		$this->Cell(51.25,5,'','R',0,'C',$fill);
		$this->Ln();
		$fill=!$fill;
		$this->Cell(43.25,5,'','L',0,'C',$fill);
		$this->Cell(43.25,5,'','',0,'C',$fill);
		$this->Cell(15.3,5,'','',0,'C',$fill);
		$this->Cell(43.25,5,'','',0,'C',$fill);
		$this->Cell(51.25,5,'','R',0,'C',$fill);
		$this->Ln();
		$fill=!$fill;
		$this->Cell(43.25,5,'','L',0,'C',$fill);
		$this->Cell(43.25,5,'','',0,'C',$fill);
		$this->Cell(15.3,5,'','',0,'C',$fill);
		$this->Cell(43.25,5,'','',0,'C',$fill);
		$this->Cell(51.25,5,'','R',0,'C',$fill);
		$this->Ln();
		$fill=!$fill;
		$this->Cell(43.25,5,'','L',0,'C',$fill);
		$this->Cell(43.25,5,'','',0,'C',$fill);
		$this->Cell(15.3,5,'','',0,'C',$fill);
		$this->Cell(43.25,5,'','',0,'C',$fill);
		$this->Cell(51.25,5,'','R',0,'C',$fill);
		$this->Ln();
		   $fill=!$fill;
		$this->Cell(43.25,5,'','L',0,'C',$fill);
		$this->Cell(43.25,5,'','',0,'C',$fill);
		$this->Cell(15.3,5,'','',0,'C',$fill);
		$this->Cell(43.25,5,'','',0,'C',$fill);
		$this->Cell(51.25,5,'','R',0,'C',$fill);
		$this->Ln();
		$fill=!$fill;/*
		$this->Cell(43.25,5,'','L',0,'C',$fill);
		$this->Cell(43.25,5,'','',0,'C',$fill);
		$this->Cell(15.3,5,'','',0,'C',$fill);
		$this->Cell(43.25,5,'','',0,'C',$fill);
		$this->Cell(51.25,5,'','R',0,'C',$fill);
		$this->Ln();
		$fill=!$fill;*/
		$this->Cell(43.25,5,'','BL',0,'C',$fill);
		$this->Cell(43.25,5,'','B',0,'C',$fill);
		$this->Cell(15.3,5,'','B',0,'C',$fill);
		$this->Cell(43.25,5,'','B',0,'C',$fill);
		$this->Cell(51.25,5,'','BR',0,'C',$fill);
		$fill=true;
		   $this->Ln();
	}
	//FIN TABLA 2
 
 	//tabla 3
 	function Tabla_3($header)
	{
		//Colores, ancho de línea y fuente en negrita
		$this->SetFillColor(255,255,255);
		$this->SetTextColor(255);
		$this->SetDrawColor(0,0,0);
		$this->SetLineWidth(.3);
		$this->SetFont('','B');

		//Restauración de colores y fuentes
		$this->SetFillColor(255,255,255);
		$this->SetTextColor(0);
		$this->SetFont('');
		
	}
	//tabla 3
 	function Tabla_4($header)
	{	//include ("conexion/conn.php");
		GLOBAL $con;
		//Colores, ancho de línea y fuente en negrita
		$this->SetFillColor(255,255,255);
		$this->SetTextColor(255);
		$this->SetDrawColor(0,0,0);
		$this->SetLineWidth(.3);
		$this->SetFont('','B');

		//Restauración de colores y fuentes
		$this->SetFillColor(255,255,255);
		$this->SetTextColor(0);
		$this->SetFont('');
		
		$Folio=$_GET['folio'];

	//finalizamos la impresion.
		//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::	
$query1="SELECT id,monto,fecha_ministracion FROM credito WHERE folio='".$Folio."' "; /**RG0804-161921 liborio**/
$result1=mysqli_query($con,$query1);								/*** RG1612-1521**/
while ($row1=mysqli_fetch_array($result1,MYSQLI_ASSOC)) {
	# code...
	$id_credito=$row1['id'];
	$monto_credito=$row1['monto'];
	$fecha_ministracion=$row1['fecha_ministracion'];
}
$restante=0;
$queryAbonos = "SELECT sum(a.monto) as monto FROM abono as a INNER JOIN pago as p ON p.id=a.pago_id INNER JOIN credito as c ON c.id=p.credito_id WHERE c.id=$id_credito AND a.tipo_pago='0'";

$resultAbono = mysqli_query($con,$queryAbonos);
$totalAbonos = 0;
while($rowAbono = mysqli_fetch_array($resultAbono,MYSQLI_ASSOC)) 
{
	$totalAbonos = $rowAbono['monto'];
	
}
$restante=$monto_credito-$totalAbonos;
$query02="SELECT * FROM pago WHERE credito_id='".$id_credito."' ";
$result02=mysqli_query($con,$query02);
$x=0;
while ($row02=mysqli_fetch_array($result02,MYSQLI_ASSOC)) {
	# code...
	$id_pago=$row02['id'];
	$monto_capital=$row02['monto_capital'];
	$monto_intereses=$row02['monto_intereses'];

		$capital=0;
		$interes=0;
		$mora=0;
		
		$suma_monto=0;
		
//*****************************************************************************
		$query03="SELECT
					a.pago_id,a.tipo,a.fecha,a.fecha_deposito,a.no_autorizacion,a.comentarios,
					sum(if(tipo_pago=1, monto,null))as capital,
					sum(if(tipo_pago=2, monto,null))as interes,
					sum(if(tipo_pago=3, monto,null)) as moratorio,
					sum(if(tipo_pago=4, monto,null)) as iva,
					sum(monto) as total
					FROM
					abono a
					WHERE pago_id='".$id_pago."'
					group by
					fecha_deposito";
		
		$result03=mysqli_query($con,$query03);

		//$suma_capital=0;
		//$suma_interes=0;
		//$suma_mora=0;
		//$suma_iva=0;
		while ($row03=mysqli_fetch_array($result03,MYSQLI_ASSOC)) {
			# code...
			$x++;
			$Pago_id=$row03['pago_id'];	
			$Capital=$row03['capital'];
			$suma_capital+=$row03['capital'];
			$InteresOr=$row03['interes'];
			$suma_interes+=$row03['interes'];
			$Moratorio=$row03['moratorio'];
			$suma_mora+=$row03['moratorio'];
			$Iva=$row03['iva'];
			$suma_iva+=$row03['iva'];
			$fecha_deposito=$row03['fecha'];
			$fecha_deposito = strtotime ( $fecha_deposito) ;
			$fecha_deposito=date('d-m-Y', $fecha_deposito);
			$fecha_d=$row03['fecha_deposito'];
			$newDate = date("d / m / Y", strtotime($fecha_d));
			$tipo=$row03['tipo'];
			$no_autorizacion=$row03['moratorio'];
			$Total_Abonos=$row03['total'];
			$SaldoInsoluto =$monto_credito-$Capital;
			
			if ($tipo=="EFECTIVO") {
				# code...
				$no_autorizacion="N/A";
			}
					
					$fill=false;
					$this->Cell(7,5,''.$x.'',0,0,'C',$fill);
					$this->Cell(20,5,''.$Pago_id.'',0,0,'C',$fill);
					$this->Cell(26,5,''.$newDate.'',0,0,'J',$fill);
					$this->Cell(23,5,''.number_format($Capital,2).'',0,0,'J',$fill);
					$this->Cell(23,5,'$'.number_format($InteresOr,2).'',0,0,'J',$fill);
					$this->Cell(23,5,''.number_format($Moratorio,2).'',0,0,'J',$fill);
					$this->Cell(15,5,''.number_format($Iva,2).'',0,0,'J',$fill);
					$this->Cell(15,5,''.$no_autorizacion.'',0,0,'J',$fill);
					$this->Cell(25,5,''.number_format($Total_Abonos,2).'',0,0,'L',$fill);
					$this->Cell(28,5,'$'.number_format($SaldoInsoluto,2).'',0,0,'J',$fill);
					$this->Ln();
		$monto_credito=$SaldoInsoluto;
		
		}
		$x+1;
	}		
		$fill=false;
		$this->Cell(196,5,'','B',0,'C',$fill);
		$this->Ln();
		$this->Cell(196,1,'','TB',0,'C',$fill);
		$this->Ln();
		$this->Cell(7,5,'',0,0,'C',$fill);
		$this->Cell(20,5,'',0,0,'C',$fill);
		//$this->Cell(26,5,'',1,0,'C',$fill);
		$this->Cell(21,5,'',0,0,'C',$fill);
		$this->Cell(23,5,'$'.number_format($suma_capital,2).'',0,0,'C',$fill);
		$this->Cell(23,5,'$'.number_format($suma_interes,2).'',0,0,'C',$fill);
		$this->Cell(28,5,'$'.number_format($suma_mora,2).'',0,0,'J',$fill);
		$this->Cell(15,5,''.number_format($suma_iva,2).'',0,0,'J',$fill);

		$this->Ln();
		$this->Cell(7,5,'',0,0,'C',$fill);
		$this->Cell(25,5,'',0,0,'C',$fill);
		$this->Cell(40,5,'',0,0,'C',$fill);
		$this->Cell(29,5,'',0,0,'C',$fill);
		$this->Cell(30,5,'',0,0,'C',$fill);
		$this->Cell(30,5,'',0,0,'C',$fill);
		$this->Cell(35,5,'',0,0,'C',$fill);
		}
	
	function Tabla_5($header)
	{
		//Colores, ancho de línea y fuente en negrita
		$this->SetFillColor(0,120,140);
		$this->SetTextColor(255);
		$this->SetDrawColor(0,0,0);
		$this->SetLineWidth(.3);
		$this->SetFont('Arial','B',7);
		//Cabecera

		//Restauración de colores y fuentes
		$this->SetFillColor(255,255,255);
		$this->SetTextColor(0);
		$this->SetFont('');
		//Datos
		$fill=false;
		$this->Cell(58,4,'ING. FILADELFO MACIAS HERNANDEZ','T',0,'C',$fill);
		$this->Cell(5,4,'',0,0,'C',$fill);
		$this->Cell(66,4,'','',0,'C',$fill);
		$this->Cell(5,4,'',0,0,'C',$fill);
		$this->Cell(58,4,utf8_decode('C.P. GABRIEL LEON CAÑAVERAL'),'T',0,'C',$fill);
		$this->Ln();
		$this->Cell(58,3,'GERENTE GENERAL',0,0,'C',$fill);
		$this->Cell(5,3,'',0,0,'C',$fill);
		$this->Cell(66,3,'',0,0,'C',$fill);
		$this->Cell(5,3,'',0,0,'C',$fill);
		$this->Cell(58,3,'CONTADOR GENERAL',0,0,'C',$fill);
		$this->Ln();
		$this->Cell(58,3,'',0,0,'C',$fill);
		$this->Cell(5,3,'',0,0,'C',$fill);
		$this->Cell(66,3,'',0,0,'C',$fill);
		$this->Cell(5,3,'',0,0,'C',$fill);
		$this->Cell(58,3,'CEDULA P.: 7661293',0,0,'C',$fill);

	}
}

$pdf=new PDF('P','mm','Letter');

//Títulos de las columnas
$header01=array('','','','','','');
$header02=array('','','','','','');
$header03=array('','','','','','');
$pdf->AliasNbPages();

//Primera página
$pdf->AddPage();
$pdf->SetFont('Arial','B',15);
$pdf->Multicell(0,1,'   ');
$pdf->Multicell(0,5,'ESTADO DE CUENTA DE CREDITO','C','C');
$pdf->Multicell(0,0.5,'   ');
$pdf->SetFont('Arial','B',9);
$pdf->Multicell(100,5,'SALDOS AL DIA: '.$dia.' '.$mes.' '.$year.'','J');
$pdf->SetFont('Arial','B',7);
$pdf->Multicell(0,5,'CLIENTE: '.$nombre.' '.$apellidos.'','','');
$pdf->Multicell(0,5,'RFC: '.$rfc.'','','');
$pdf->Multicell(83,5,'DOMICILIO: '.$direccion.' '.$localidad.' '.$municipio.' '.$efederativa.'','J','J');
$pdf->Multicell(0,2,'   ');
$pdf->SetFont('Arial','',8);
$pdf->Multicell(0,5,'');
$pdf->Multicell(0,3,'');
$pdf->Multicell(0,5,'');
$pdf->Multicell(0,5,'');
$pdf->SetY(44);
$pdf->Tabla_1($header01);
$pdf->SetY(115);
$pdf->Tabla_2($header02);
$pdf->Multicell(0,2,' ');

//llenado del segundo cuadro
$pdf->SetY(44);
$pdf->SetX(111.7);
$pdf->Multicell(86.4,5,'',0,'C');
$pdf->SetFont('Arial','B',8);
$pdf->SetY(49);
$pdf->SetX(111.7);
$pdf->Multicell(38,5,'No. DE CREDITO:',0,'L');
$pdf->SetY(49);
$pdf->SetX(149.7);
$pdf->Multicell(56.4,5,''.$Folio.'',0,'R');
$pdf->SetY(54);
$pdf->SetX(111.7);
$pdf->Multicell(38,5,'MONTO OTORGADO:',0,'L');
$pdf->SetY(54);
$pdf->SetX(149.7);
$pdf->Multicell(56.4,5,'$'.number_format($monto,2).'',0,'R');
$pdf->SetY(59);
$pdf->SetX(111.7);
$pdf->Multicell(38,5,'FECHA DE APERTURA:',0,'L');
$pdf->SetFont('Arial','',8);
$pdf->SetY(59);
$pdf->SetX(149.7);
$pdf->Multicell(56.4,5,''.$diam.' / '.$mesm.' / '.$yearm.'',0,'R');
$pdf->SetFont('Arial','B',8);
$pdf->SetY(64);
$pdf->SetX(111.7);
$pdf->Multicell(38,5,'FECHA DE VENCIMIENTO:',0,'L');
$pdf->SetFont('Arial','',8);
$pdf->SetY(64);
$pdf->SetX(149.7);
$pdf->Multicell(56.4,5,''.$diav.' / '.$mesv.' / '.$yearv.'',0,'R');
$pdf->SetFont('Arial','B',8);
$pdf->SetY(69);
$pdf->SetX(111.7);
$pdf->Multicell(38,5,'PLAZO: (MENSUAL)',0,'L');
$pdf->SetFont('Arial','',8);
$pdf->SetY(69);
$pdf->SetX(149.7);
$pdf->Multicell(56.4,5,'12',0,'R');
$pdf->SetFont('Arial','B',8);
$pdf->SetY(74);
$pdf->SetX(111.7);
$pdf->Multicell(38,5,'TASA MENSUAL:',0,'L');
$pdf->SetFont('Arial','',8);
$pdf->SetY(74);
$pdf->SetX(149.7);
$pdf->Multicell(56.4,5,''.$tasa.'%',0,'R');
$pdf->SetFont('Arial','B',8);
$pdf->SetY(79);
$pdf->SetX(111.7);
$pdf->Multicell(25,5,'PRODUCTO:',0,'L');
$pdf->SetFont('Arial','',8);
$pdf->SetY(79);
$pdf->SetX(137);
$pdf->Multicell(69.4,5,'PYME RURAL - '.$fondeadora.'',0,'R');
$pdf->SetFont('Arial','B',8);
$pdf->SetY(84);
$pdf->SetX(111.7);
$pdf->Multicell(38,5,'No. DE CLIENTE:',0,'L');
$pdf->SetFont('Arial','',8);
$pdf->SetY(84);
$pdf->SetX(149.7);
$pdf->Multicell(56.4,5,''.$id.'',0,'R');
//fin del llenado del segundo cuadro
/*
//llenando el primer cuadro
$pdf->SetY(89);
$pdf->SetX(111.7);
$pdf->Multicell(86.4,5,'',0,'C');
$pdf->SetFont('Arial','B',8);
$pdf->SetY(89);
$pdf->SetX(111.7);
$pdf->Multicell(20,5,'NOMBRE:',0,'L');
$pdf->SetFont('Arial','',8);
$pdf->SetY(89);
$pdf->SetX(137);
$pdf->Multicell(69.4,5,''.$nombre.' '.$apellidos.'',0,'R');
$pdf->SetY(94);
$pdf->SetX(111.7);
$pdf->SetFont('Arial','B',8);
$pdf->Multicell(20,5,'RFC:',0,'L');
$pdf->SetY(94);
$pdf->SetX(137);
$pdf->SetFont('Arial','',8);
$pdf->Multicell(69.4,5,''.$rfc.'',0,'R');
$pdf->SetFont('Arial','B',8);
$pdf->SetY(99);
$pdf->SetX(111.7);
$pdf->Multicell(20,5,'DOMICILIO:',0,'L');
$pdf->SetFont('Arial','',8);
$pdf->SetY(99);
$pdf->SetX(137);
$pdf->Multicell(69.4,5,''.$direccion.' '.$localidad.' '.$municipio.' '.$efederativa.'',0,'R');
*/
//llenado del tercer cuadro
$pdf->SetY(115);
$pdf->SetX(10);
$pdf->Multicell(188.3,5,'',0,'L');
$pdf->SetY(120);
$pdf->SetX(10);
$pdf->Multicell(44,5,'Saldo inicial del periodo:',0,'L');
$pdf->SetY(120);
$pdf->SetX(54);
$pdf->Multicell(25,5,'$'.number_format($monto,2).'',0,'R');
$pdf->SetY(125);
$pdf->SetX(10);
$pdf->Multicell(44,5,'Capital insoluto:',0,'L');
$pdf->SetY(125);
$pdf->SetX(54);
$pdf->Multicell(25,5,'$'.number_format($saldoRetante,2).'',0,'R');
$pdf->SetY(130);
$pdf->SetX(10);
$pdf->Multicell(44,5,'Capital Vigente:',0,'L');
$pdf->SetY(130);
$pdf->SetX(54);
$capitalVigente=0;
if ($fecha_hoy>$fechapp) {
if ($id_pagos!=$idpago) {
	$capital_vencido=$capitalPago;
	$capitalVigente=$saldoRetante-$capital_vencido;
}else{
	if ($dias_vencidos>0) {
		# code...
		$capital_vencido=$capitalPago;
	}else{$capitalVigente=$saldoRetante;}
$capitalVigente=$saldoRetante-$capital_vencido;
	} 
}else{$capitalVigente=$saldoRetante;}
$pdf->Multicell(25,5,'$'.number_format($capitalVigente,2).'',0,'R');
$pdf->SetY(135);
$pdf->SetX(10);
$pdf->Multicell(44,5,'Intereses ordinarios:',0,'L');
$pdf->SetY(135);
$pdf->SetX(54);
$pdf->Multicell(25,5,'$'.number_format($interes_hoy,2).'',0,'R');
$pdf->SetY(140);
$pdf->SetX(10);
$pdf->Multicell(44,5,'I.V.A.:',0,'L');
$pdf->SetY(140);
$pdf->SetX(54);
$pdf->Multicell(25,5,'$'.number_format($iva,2).'',0,'R');
//inicializamos la cuenta como al principio
$pdf->SetY(120);
$pdf->SetX(79);
$pdf->Multicell(20,5,'Cargos:',0,'R');
$pdf->SetY(120);
$pdf->SetX(99);
$pdf->Multicell(25,5,'$'.number_format($monto,2).'',0,'R');
$pdf->SetY(125);
$pdf->SetX(70);
$pdf->Multicell(30,5,'Capital a Pagar:',0,'R');
$pdf->SetY(125);
$pdf->SetX(99);
if ($respuesta==1) {
	# code...
	$capitalPago=$saldoRetanteP;
}
$pdf->Multicell(25,5,'$'.number_format($capitalPago,2).'',0,'R');
$pdf->SetY(130);
$pdf->SetX(79);
$pdf->Multicell(20,3,'Aplic. Seguro y otros:',0,'R');
$pdf->SetY(133);
$pdf->SetX(99);
$pdf->Multicell(25,3,'$0.00',0,'R');
$pdf->SetY(136);
$pdf->SetX(79);
$pdf->Multicell(20,5,'Abono capital:',0,'R');
$pdf->SetY(136);
$pdf->SetX(99);
$pdf->Multicell(25,5,'$'.number_format($totalAbonos,2).'',0,'R');
$pdf->SetY(142);
$pdf->SetX(79);
$pdf->Multicell(20,5,'Abono int.:',0,'R');
$pdf->SetY(142);
$pdf->SetX(99);
$pdf->Multicell(25,5,'$'.number_format($abono_Intereses,2).'',0,'R');

$pdf->SetY(120);
$pdf->SetX(124);
$pdf->Multicell(24,5,'Saldo vencido:',0,'L');
$pdf->SetY(120);
$pdf->SetX(163);
$pdf->Multicell(43.3,5,'',0,'R');
$pdf->SetY(125);
$pdf->SetX(124);
if ($fecha_hoy>$fechapp) {

if ($id_pagos!=$idpago) {
	$capital_vencido=$capitalPago;
}else{
if ($dias_vencidos>0) {
		# code...
		$capital_vencido=$capitalPago;
	}else{$capital_vencido=0;}
  } 
}
$pdf->Multicell(39,5,'Capital vencido:',0,'L');
$pdf->SetY(125);
$pdf->SetX(163);
$pdf->Multicell(43.3,5,'$'.number_format($capital_vencido,2).'',0,'R');
$pdf->SetY(130);
$pdf->SetX(124);
$pdf->Multicell(39,5,'Intereses vencidos:',0,'L');
$pdf->SetY(130);
$pdf->SetX(163);
$pdf->Multicell(43.3,5,'$'.number_format($interes_vencido,2).'',0,'R');
$pdf->SetY(135);
$pdf->SetX(124);
$pdf->Multicell(39,5,'Intereses moratorios:',0,'L');
$pdf->SetY(135);
$pdf->SetX(163);
$pdf->Multicell(43.3,5,'$'.number_format($interes_mora,2).'',0,'R');
$pdf->SetY(140);
$pdf->SetX(124);
$pdf->Multicell(39,5,'Total vencido:',0,'L');
$pdf->SetY(140);
$pdf->SetX(163);
$pdf->Multicell(43.3,5,'$'.number_format($total_vencido,2).'',0,'R');

//fin del llenado del tercer cuadro
$pdf->Multicell(0,6,' ');
$pdf->Multicell(0,3,'MOVIMIENTOS EFECTUADOS:');
$pdf->Multicell(0,5,' ');

//cabeceras de tabla de pagos y abonos.
$pdf->SetFont('Arial','B',8);
$pdf->SetY(155);
$pdf->SetX(10);
$pdf->Multicell(7,4,'N/P',0,'C');
$pdf->SetY(155);
$pdf->SetX(15);
$pdf->Multicell(25,4,'ID PAGO',0,'C');
$pdf->SetY(155);
$pdf->SetX(35);
$pdf->Multicell(25,4,'FECHA ABONOS',0,'C');
$pdf->SetY(155);
$pdf->SetX(55);
$pdf->Multicell(26,4,'CAPITAL',0,'C');
$pdf->SetY(155);
$pdf->SetX(75);
$pdf->Multicell(29,4,'INTERES ORD.',0,'C');
$pdf->SetY(155);
$pdf->SetX(95);
$pdf->Multicell(29,4,'MORATORIO',0,'R');
$pdf->SetY(155);
$pdf->SetX(110);
$pdf->Multicell(29,4,'IVA',0,'R');
$pdf->SetY(155);
$pdf->SetX(135);
$pdf->Multicell(30,4,'NUM. AUT.',0,'C');
$pdf->SetY(155);
$pdf->SetX(153);
$pdf->Multicell(30,4,'ABONO TOTAL',0,'R');
$pdf->SetY(155);
$pdf->SetX(171);
$pdf->Multicell(35,4,'SALDO REST.',0,'R');
$pdf->SetFont('Arial','',8);
//fin de cabeceras de tabls de pagos y abonos
$pdf->Multicell(0,1,'','B','L');
$pdf->Multicell(0,1,'','B','L');
//PRIMERA LINEA OTORGAMIENTO DE CREDITO

//// CONSULTA PARA OBTENER LAS FECHAS DE VENCIMIENTO PARA CADA PAGO
/*$queryfechas = "SELECT fecha_pago FROM pago WHERE credito_id='".$idcredito."'";
$resultfechas = mysqli_query($con,$queryfechas);
$fila=mysqli_num_rows($resultfechas);
$totalfechas = 1;
$y=163;
while($rowfechas = mysqli_fetch_array($resultfechas,MYSQLI_ASSOC)) 
{
$pdf->SetXY(17,$y);
$pdf->SetFont('Arial','B',8);
$pdf->Multicell(25,4,''.$fecha=$rowfechas['fecha_pago'].'',0,'C');
$pdf->setXY(1,$y);
$pdf->Multicell(25,4,''.$totalfechas.'',0,'C');
$y+=5;
$totalfechas++;
}*/
///
$pdf->SetFont('Arial','',8);
$pdf->SetY(162);
$pdf->SetX(162);
$pdf->Multicell(36.3,4,'',0,'R');

//FIN PRIMERA LINEA OTORGAMIENTO DE CREDITO
$pdf->Multicell(0,0.5,' ');
$pdf->Tabla_3($header03);
$y1=$pdf->GetY();
$x1=$pdf->GetX();
$pdf->Tabla_4($header03);
$y2=$pdf->GetY();

$pos1=$x1+0;
$pdf->SetXY($pos1,$y2);

$fill=false;
$pdf->Cell(196,1,'','TB',0,'C',$fill);
$pdf->Ln(4);
$pdf->Cell(7,5,'',0,0,'C',$fill);
$pdf->Cell(25,5,'',0,0,'C',$fill);
$pdf->Cell(40,5,'',0,0,'C',$fill);
$pdf->Cell(29,5,'',0,0,'C',$fill);
$pdf->Cell(30,5,'Capital:','TL',0,'R',$fill);
$pdf->Cell(30,5,'$'.number_format($capitalPago,2).'','TR',0,'R',$fill);
$pdf->Cell(35,5,'',0,0,'C',$fill);
$pdf->Ln();
$fill=false;
$pdf->Cell(7,5,'',0,0,'C',$fill);
$pdf->Cell(25,5,'',0,0,'C',$fill);
$pdf->Cell(40,5,'',0,0,'C',$fill);
$pdf->Cell(29,5,'',0,0,'C',$fill);
$pdf->Cell(30,5,'Intereses:','L',0,'R',$fill);
$intereses01=$interes_hoy+$interes_mora;
$pdf->Cell(30,5,'$'.number_format($intereses01,2).'','R',0,'R',$fill);
$pdf->Cell(35,5,'',0,0,'C',$fill);
$pdf->Ln();
$pdf->Cell(7,5,'',0,0,'C',$fill);
$pdf->Cell(25,5,'',0,0,'C',$fill);
$pdf->Cell(40,5,'',0,0,'C',$fill);
$pdf->Cell(29,5,'',0,0,'C',$fill);
$pdf->Cell(30,5,'Iva:','L',0,'R',$fill);
$pdf->Cell(30,5,'$'.number_format($iva,2).'','R',0,'R',$fill);
$pdf->Cell(35,5,'',0,0,'C',$fill);
$pdf->Ln();
$pdf->Cell(7,5,'','',0,'C',$fill);
$pdf->Cell(25,5,'',0,0,'C',$fill);
$pdf->Cell(40,5,'',0,0,'C',$fill);
$pdf->Cell(29,5,'',0,0,'C',$fill);
$pdf->Cell(30,5,'Total a pagar:','LB',0,'R',$fill);
if (strtotime($fecha_hoy)>=strtotime($fechapp)) {
	$pdf->Cell(30,5,'$'.number_format($pago_hoy+$iva,2).'','RB',0,'R',$fill);
} else {
	$pdf->Cell(30,5,'$'.number_format($pago_hoy+$iva,2).'','RB',0,'R',$fill);
}
$pdf->Cell(35,5,'',0,0,'C',$fill);

//tabla de firma del inge
$pdf->SetY(249);
$pdf->SetX(10);
$pdf->Tabla_5($header03);
$pdf->Output();
?>
