<?php
	require_once ("config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("config/conexion.php");//Contiene funcion que conecta a la base de datos
	date_default_timezone_set('America/Mexico_City');
	$Folio=$_GET['folio'];
	$fecha_solicitada= date('Y-m-d');
	//$respuesta=$_GET['opcion'];
	$dias_transf=0;
	$saldo_capital=0;

$query01="SELECT 
		 credito.id as idcredito,
		 IF ((SELECT status FROM garantia_liquida WHERE garantia_liquida.credito_id=credito.id)='VIGENTE',(SELECT garantia_liquida.monto_total FROM garantia_liquida WHERE garantia_liquida.credito_id=credito.id),0) AS garantia_liquida,
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
    $gl_press=$row01['garantia_liquida'];
	$idcredito=$row01['idcredito'];
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

$fecha_minis1 = strtotime ( $fecha_ministracion) ;
$fecha_minis1=date('d-m-Y', $fecha_minis1);

//Nombre de la fuente
$query02="SELECT fondeadora.nombre 
				FROM fondeadora,
					 credito,
					 disposicion,
					 linea_madre 
					 	WHERE credito.disposicion_id=disposicion.id 
						 	AND disposicion.linea_madre_id=linea_madre.id 
							 	AND linea_madre.fondeadora_id=fondeadora.id 
								 	AND credito.folio='".$Folio."'";
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
$query011="SELECT id,
				  fecha_pago 
				  	FROM pago 
					  	WHERE pago.estatus='VIGENTE' 
						  	AND credito_id='".$idcredito."' 
							  	AND fecha_pago<'".$fecha_solicitada."'";
$result011=mysqli_query($con,$query011);
while ($row011=mysqli_fetch_array($result011,MYSQLI_ASSOC)) {
	# code...
	$id_pagos=$row011['id'];
	$fecha_pagos=$row011['fecha_pago'];
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


//$saldoRetanteP =  $capitalPago-$totalAbonosP;
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

$queryAbonosIT = "SELECT sum(a.monto) as monts FROM abono as a INNER JOIN pago as p ON p.id=a.pago_id INNER JOIN credito as c ON c.id=p.credito_id WHERE c.id=$idcredito AND a.tipo_pago='1'";
$resultAbonoI = mysqli_query($con,$queryAbonosIT);
while($rowAbonosI = mysqli_fetch_array($resultAbonoI,MYSQLI_ASSOC)) 
{
	$abono_Intereses = $rowAbonosI['monts'];
}
//CONDICIONAMOS SI YA HUBO ALGUN ABONO A INTERES PARA EFECTUAR LA RESTA
/*if($saldoRetanteP>0){
$fecha_ministracion=$fecha_upago;


$fechahoy=$fecha_solicitada;

$dias_trans=$dias_transf;	

$interes_diario=round((($saldo_capital*$tasa_interes*365)/360)/365,0);


if($totalAbonosP>0){
$interes_hoy =round((($saldoRetante*$tasa_interes*$dias_trans)/360),0);
$capitalPago = $saldoRetanteP; /** se comentario, descomentariar despues**/
//}

	//entra si la fecha de hoy es mayor a la fecha del pago vigente
	/*if ($id_pagos!=$idpago) {
		# code...
	$capi=$totalcapital;
	$capitalPagos=$capital_Pagos + $totalcapital;
	$capitalPago=$capitalPagos;
	$totalcapital=$capitalPagos;
	
	/************************************************************************************/
	/*$dias_vencidos01=(strtotime($fechahoy)-strtotime($fechapp))/86400;
	$dias_vencidos02=(strtotime($fechahoy)-strtotime($fecha_pagos))/86400;
	$interes_mora=(($totalcapital*$tasa_interes*$dias_vencidos)/360)*2;
	$capital_vencido01=$capitalPago;
	}else{
		$capitalPagos=$capitalPago+$totalcapital;
	}*/

if (strtotime($fechahoy) >strtotime($fechapp)){
	
	if ($totalIntereses>0) {
			# code...
	$dias_vencidos=(strtotime($fechahoy)-strtotime($fecha_upago))/86400;

	$dias_vencidos=round(abs($dias_vencidos));
		}else{
	$dias_vencidos=(strtotime($fechahoy)-strtotime($fechapp))/86400;

	$dias_vencidos=round(abs($dias_vencidos));
		}
	if ($respuesta==1) {
		# code...

		$interes_mora=round((($saldoRetante*$tasa_interes*$dias_vencidos)/360)*2,0);

	}else{
		
	$interes_mora=round((($totalcapital*$tasa_interes*$dias_vencidos)/360)*2,0);		
	}
	$saldo_vencido=$interes_hoy+$interes_mora;

	if ($fechahoy>$fechapp) {

		if ($id_pagos!=$idpago) {
			$capital_vencido=$saldoRetanteP;
			}else{
		if ($dias_vencidos>0) {
		# code...
			$capital_vencido=$saldoRetanteP;
		}else{$capital_vencido=0;}
  			} 
		}
	//$capital_vencido=$saldoRetanteP;

	$interes_vencido=$interes_hoy;
	$total_vencido=$capital_vencido+$interes_vencido+$interes_mora;
	
	$pago_hoy=$saldoRetanteP+$interes_hoy+$interes_mora;

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

/////SUPERPROGRAMACION Y SUPERLOGICA DE SUPERLALO

/////////////////////////////////////////////////-------------------------------------/////////////////////////////////
$sllquery00="SELECT credito.id, 
					pago.credito_id, 
					pago.id,
					IF (pago.estatus='PAGADO', 0, (SELECT SUM(abono.monto) 
														FROM abono 
															WHERE abono.pago_id=pago.id 
															AND abono.tipo_pago='0')) AS cp_sll,
					IF (pago.estatus='PAGADO', 0, (SELECT SUM(abono.monto) 
														FROM abono 
															WHERE abono.pago_id=pago.id 
															AND abono.tipo_pago='1')) AS ip_sll,
					IF (pago.estatus='PAGADO', 0, (SELECT SUM(abono.monto) 
														FROM abono 
															WHERE abono.pago_id=pago.id 
															AND abono.tipo_pago='2')) AS im_sll,
					IF (pago.estatus='PAGADO', '".$fecha_solicitada."', 
						IF((SELECT MAX(abono.fecha_deposito) 
								FROM abono 
									WHERE abono.pago_id=pago.id 
									AND abono.tipo_pago='0') IS NULL,pago.fecha_pago_m, (SELECT MAX(abono.fecha_deposito) 
																								FROM abono 
																									WHERE abono.pago_id=pago.id 
																									AND abono.tipo_pago='0'))) AS fu_sll,
					IF (pago.estatus='PAGADO', '".$fecha_solicitada."', 
						IF((SELECT MAX(abono.fecha_deposito) 
								FROM abono 
									WHERE abono.pago_id=pago.id 
									AND abono.tipo_pago='1') IS NULL,pago.fecha_pago_m, (SELECT MAX(abono.fecha_deposito) 
																								FROM abono 
																									WHERE abono.pago_id=pago.id 
																									AND abono.tipo_pago='1'))) AS fi_sll,
					IF (pago.estatus='PAGADO', '".$fecha_solicitada."', 
						IF((SELECT MAX(abono.fecha_deposito) 
								FROM abono 
									WHERE abono.pago_id=pago.id 
									AND abono.tipo_pago='2') IS NULL,pago.fecha_pago, pago.fecha_pago)) AS fm_sll,
					IF (pago.estatus='PAGADO', 0, pago.monto_capital) AS ca_sll,
					IF (pago.estatus='PAGADO', 0, pago.monto_intereses) AS in_sll, 
					IF (pago.fecha_pago<'".$fecha_solicitada."', 'VENCIDO',
						IF(pago.fecha_pago>='".$fecha_solicitada."' AND pago.fecha_pago<=DATE_ADD('".$fecha_solicitada."',INTERVAL 365 DAY),'VIGENTE', 'PENDIENTE')) AS es_sll, 
					pago.fecha_pago AS fp_sll,
					pago.estatus AS st_sll
					FROM credito, pago
						WHERE credito.id=pago.credito_id 
						AND credito.folio='".$Folio."'";
$sllresult00 = mysqli_query($con,$sllquery00);
	WHILE($row01 = mysqli_fetch_array($sllresult00)) 
	{
		$cp_sll[]=$row01['cp_sll'];
		$ip_sll[]=$row01['ip_sll'];
		$im_sll[]=$row01['im_sll'];
		$fu_sll[]=$row01['fu_sll'];
		$fm_sll[]=$row01['fm_sll'];
		$ca_sll[]=$row01['ca_sll'];
		$in_sll[]=$row01['in_sll'];
		$es_sll[]=$row01['es_sll'];	
		$fp_sll[]=$row01['fp_sll'];	
		$st_sll[]=$row01['st_sll'];
	}
////////////////VAR SUPERESPERCIALES
$fh_sll_es=new datetime($fecha_solicitada);
$ts_sll_es=$tasa/100;
$cpt_sll_es=($ca_sll[0]+$ca_sll[1]+$ca_sll[2]+$ca_sll[3]+$ca_sll[4]);
$itr_sll_es=($cp_sll[0]+$cp_sll[1]+$cp_sll[2]+$cp_sll[3]+$cp_sll[4]);
/////////////////
/////1RA AMORTIZACION
IF ($es_sll[0]=='VIGENTE'){
	$capvigente00=$ca_sll[0]-$cp_sll[0];
	$fu_sll00=new datetime($fp_sll[0]);
		IF ($fu_sll00>=$fh_sll_es){
			$diatranscu00=365-($fu_sll00->diff($fh_sll_es)->format("%a"));
		}ELSE{
			$diatranscu00=($fu_sll00->diff($fh_sll_es)->format("%a"));
			$amort_sll_es=1;
		}
	$intvigente00=round(((($capvigente00+$ca_sll[1]+$ca_sll[2]+$ca_sll[3]+$ca_sll[4])*$ts_sll_es)/360)*($diatranscu00),0);
//echo 'VIGENTE';
	}ELSEIF($es_sll[0]=='VENCIDO'){
		$amort_sll_es=1;
		$capvencido00=$ca_sll[0]-$cp_sll[0];
		$fu_sll00=new datetime($fu_sll[0]);
		IF ($fu_sll00<$fh_sll_es){
		$diavencido00=$fh_sll_es->diff($fu_sll00)->format("%a");
		}ELSE{
		$diavencido00=0;
		}
		$intvencido00=round(((($capvencido00+$ca_sll[1]+$ca_sll[2]+$ca_sll[3])*$ts_sll_es)/360)*$diavencido00,0);
		$fm_sll00=new datetime($fm_sll[0]);
		IF ($fm_sll00<$fh_sll_es){
		$diamorator00=$fh_sll_es->diff($fm_sll00)->format("%a");
		}ELSE{
		$diamorator00=0;
		}
		$intmorator00=round(((($capvencido00)*($ts_sll_es*2))/360)*$diamorator00,0)-$im_sll[0];
//echo 'VENCIDO';
		}ELSEIF($es_sll[0]=='PENDIENTE'){
			$cappendiente00=$ca_sll[0]-$cp_sll[0];
			$intpendiente00=$in_sll[0]-$ip_sll[0];
//echo 'PENDIENTE';
}
////
/////2DA AMORTIZACION
IF ($es_sll[1]=='VIGENTE'){
	$capvigente01=$ca_sll[1]-$cp_sll[1];
	$fu_sll01=new datetime($fp_sll[1]);
	IF ($fu_sll01>=$fh_sll_es){
		$diatranscu01=365-($fu_sll01->diff($fh_sll_es)->format("%a"));
	}ELSE{
		$diatranscu01=($fu_sll01->diff($fh_sll_es)->format("%a"));
		$amort_sll_es=2;
	}
	$intvigente01=round(((($capvigente01+$ca_sll[2]+$ca_sll[3])*$ts_sll_es)/360)*$diatranscu01,0);
//echo 'VIGENTE';
	}ELSEIF($es_sll[1]=='VENCIDO'){
		IF($st_sll[0]=='VIGENTE'){
			$diavencido01=0;	
		}ELSE{
			$fu_sll01=new datetime($fu_sll[1]);
		IF ($fu_sll01<$fh_sll_es){
		$diavencido01=$fh_sll_es->diff($fu_sll01)->format("%a");
		}ELSE{
		$diavencido01=0;
		}
		}
		$amort_sll_es=2;
		$capvencido01=$ca_sll[1]-$cp_sll[1];
		
		$intvencido01=round(((($capvencido01+$ca_sll[2]+$ca_sll[3])*$ts_sll_es)/360)*$diavencido01,0);
		$fm_sll01=new datetime($fm_sll[1]);
		IF ($fm_sll01<$fh_sll_es){
		$diamorator01=$fh_sll_es->diff($fm_sll01)->format("%a");
		}ELSE{
		$diamorator01=0;
		}
		$intmorator01=round(((($capvencido01)*($ts_sll_es*2))/360)*($diamorator01),0)-$im_sll[1];
//echo 'VENCIDO';
		}ELSEIF($es_sll[1]=='PENDIENTE'){
			$cappendiente01=$ca_sll[1]-$cp_sll[1];
			$intpendiente01=$in_sll[1]-$ip_sll[1];
//echo 'PENDIENTE';
}
////
/////3RA AMORTIZACION
IF ($es_sll[2]=='VIGENTE'){
	$capvigente02=$ca_sll[2]-$cp_sll[2];
	$fu_sll02=new datetime($fp_sll[2]);
	IF ($fu_sll02>=$fh_sll_es){
		$diatranscu02=365-($fu_sll02->diff($fh_sll_es)->format("%a"));
	}ELSE{
		$diatranscu02=($fu_sll02->diff($fh_sll_es)->format("%a"));
		$amort_sll_es=3;
	}
	$intvigente02=round(((($capvigente02+$ca_sll[3])*$ts_sll_es)/360)*$diatranscu02,0);
//echo 'VIGENTE';
	}ELSEIF($es_sll[2]=='VENCIDO'){
		IF($st_sll[1]=='VIGENTE'){
			$diavencido02=0;	
		}ELSE{
			$fu_sll02=new datetime($fu_sll[2]);
		IF ($fu_sll02<$fh_sll_es){
		$diavencido02=$fh_sll_es->diff($fu_sll02)->format("%a");
		}ELSE{
		$diavencido02=0;
		}
		}
		$amort_sll_es=3;
		$capvencido02=$ca_sll[2]-$cp_sll[2];		

		$intvencido02=round(((($capvencido02+$ca_sll[3])*$ts_sll_es)/360)*$diavencido02,0);
		$fm_sll02=new datetime($fm_sll[2]);
		IF ($fm_sll02<$fh_sll_es){
		$diamorator02=$fh_sll_es->diff($fm_sll02)->format("%a");
		}ELSE{
		$diamorator02=0;
		}
		$intmorator02=round(((($capvencido02)*($ts_sll_es*2))/360)*$diamorator02,0)-$im_sll[2];
//echo 'VENCIDO';
		}ELSEIF($es_sll[2]=='PENDIENTE'){
			$cappendiente02=$ca_sll[2]-$cp_sll[2];
			$intpendiente02=$in_sll[2]-$ip_sll[2];
//echo 'PENDIENTE';
}
////
/////4TA AMORTIZACION
IF ($es_sll[3]=='VIGENTE'){
	$capvigente03=$ca_sll[3]-$cp_sll[3];
	$fu_sll03=new datetime($fp_sll[3]);
	IF ($fu_sll03>=$fh_sll_es){
		$diatranscu03=365-($fu_sll03->diff($fh_sll_es)->format("%a"));
	}ELSE{
		$diatranscu03=($fu_sll03->diff($fh_sll_es)->format("%a"));
		$amort_sll_es=4;
	}
		$intvigente03=round((($capvigente03*$ts_sll_es)/360)*$diatranscu03,0);
//echo 'VIGENTE';
	}ELSEIF($es_sll[3]=='VENCIDO'){
		$fp_sll03=new datetime($fp_sll[3]);
		IF($st_sll[2]=='VIGENTE'){
			$diavencido03=0;	
		}ELSE{
			$fu_sll03=new datetime($fu_sll[3]);
		IF ($fu_sll03<$fh_sll_es){
		$diavencido03=$fh_sll_es->diff($fu_sll03)->format("%a");
		}ELSE{
		$diavencido03=0;
		}
		}

		$amort_sll_es=4;
		$capvencido03=$ca_sll[3]-$cp_sll[3];
		
		$intvencido03=round(((($capvencido03)*$ts_sll_es)/360)*$diavencido03,0);
		$fm_sll03=new datetime($fm_sll[3]);
		IF ($fm_sll03<$fh_sll_es){
		$diamorator03=$fh_sll_es->diff($fm_sll03)->format("%a");
		}ELSE{
		$diamorator03=0;
		}
		$intmorator03=round(((($capvencido03)*($ts_sll_es*2))/360)*$diamorator03,0)-$im_sll[3];
//echo 'VENCIDO';
		}ELSEIF($es_sll[3]=='PENDIENTE'){
		$cappendiente03=$ca_sll[3]-$cp_sll[3];
		$intpendiente03=$in_sll[3]-$ip_sll[3];
//echo 'PENDIENTE';
}
////

/////////////
//' CAPITAL VIGENTE : ';
$cvgt_sll=$capvig_sll=$capvigente00+$capvigente01+$capvigente02+$capvigente03;
//' CAPITAL VENCIDO : ';
$cvdt_sll=$capven_sll=$capvencido00+$capvencido01+$capvencido02+$capvencido03;
//' INTERES ORDINARIO VIGENTE : ';
$ivgt_sll=$intvig_sll=$intvigente00+$intvigente01+$intvigente02+$intvigente03;
//' INTERES ORDINARIO VENCIDO : ';
$ivdt_sll=$intven_sll=$intvencido00+$intvencido01+$intvencido02+$intvencido03;
//echo ' INTERES MORATORIO : ';
$imrt_sll=$intmor_sll=$intmorator00+$intmorator01+$intmorator02+$intmorator03;
IF ($cvdt_sll>0){
	$ctp_sll_es=$cvdt_sll;
	$itp_sll_es=$ivdt_sll+$imrt_sll;
	$pgt_sll_es=$ctp_sll_es+$itp_sll_es;
}ELSE{
	$ctp_sll_es=$cvgt_sll;
	$itp_sll_es=$ivgt_sll;
	$pgt_sll_es=$cvgt_sll+$ivgt_sll;
}
	$pct_sll_es=$cpt_sll_es-$itr_sll_es;
	$pit_sll_es=$ivdt_sll+$imrt_sll;
	$ptt_sll_es=$pct_sll_es+$pit_sll_es;
///////////////////////////////////////////////////-----------------------------////////////////////////////////////////






////////
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



$fecha_vencimiento = strtotime ( '+1460 day' , strtotime ( $fecha2) ) ;
$fecha_vencimiento = date ( 'Y-m-j' , $fecha_vencimiento );

$diav=date("d", strtotime($fecha_vencimiento));

$mesv = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
$mesv=$mesv[date("n", strtotime($fecha_vencimiento))-1];
$yearv=date("Y", strtotime($fecha_vencimiento));


require('./fpdf/fpdf.php');

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
		$this->SetY(-20);
		//$this->Tabla_5($header0);
		$this->Ln(2);
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
		$this->SetFillColor(255,0,0);
		$this->SetTextColor(255);
		$this->SetDrawColor(0,0,0);
		$this->SetLineWidth(.3);
		$this->SetFont('','B');

		$this->SetFillColor(224,235,255);
		$this->SetTextColor(0);
		$this->SetFont('');
		
	}
	//tabla 3
 	function Tabla_4($header)
	{	//include ("conexion/conn.php");
		GLOBAL $con;
		$this->SetFillColor(255,255,255);
		$this->SetTextColor(255);
		$this->SetDrawColor(0,0,0);
		$this->SetLineWidth(.3);
		$this->SetFont('','B');
		/////cabecera
		for($ii=0;$ii<count($header);$ii++)
		$this->Cell(0,0,$header[$ii],0,0,'C',1);
		$this->Ln();
		//Restauración de colores y fuentes

		$this->SetFillColor(224,235,255);
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
$query02="SELECT id,monto_capital,monto_intereses FROM pago WHERE credito_id='".$id_credito."' ";
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
		//$suma_capital=0;
		//$suma_interes=0;
		//$suma_mora=0;
		$suma_monto=0;
		
//*****************************************************************************
		$query03="SELECT
					a.pago_id,a.tipo,a.fecha_deposito,a.no_autorizacion,a.comentarios,
					sum(if(tipo_pago=1, monto,null))as capital,
					sum(if(tipo_pago=2, monto,null))as interes,
					sum(if(tipo_pago=3, monto,null)) as moratorio,
					sum(monto) as total
					FROM
					abono a
					WHERE pago_id='".$id_pago."'
					group by
					fecha_deposito ";
		
		$result03=mysqli_query($con,$query03);
		
		while ($row03=mysqli_fetch_array($result03,MYSQLI_ASSOC)) {
			# code...
			$x++;
			$Pago_id=$row03['pago_id'];	
			$Capital=$row03['capital'];
			$suma_capital +=$row03['capital'];
			$InteresOr=$row03['interes'];
			$suma_interes +=$row03['interes'];
			$Moratorio=$row03['moratorio'];
			$suma_mora +=$row03['moratorio'];
			$fecha_deposito=$row03['fecha_deposito'];
			$fecha_deposito = strtotime ( $fecha_deposito) ;
			$fecha_deposito=date('d-m-Y', $fecha_deposito);
			$tipo=$row03['tipo'];
			$no_autorizacion=$row03['no_autorizacion'];
			$Total_Abonos=$row03['total'];
			$SaldoInsoluto =$monto_credito-$Capital;
			
			if ($tipo=="EFECTIVO") {
				# code...
				$no_autorizacion="N/A";
			}
			
					$fill=false;
					$this->Cell(7,5,''.$x.'','L',0,'C',$fill);
					$this->Cell(22,5,''.$Pago_id.'','L',0,'C',$fill);
					$this->Cell(25,5,''.$fecha_deposito.'','L',0,'C',$fill);
					$this->Cell(22,5,''.number_format($Capital,2).'','L',0,'R',$fill);
					$this->Cell(4,5,'',0,0,'R',$fill);
					$this->Cell(22,5,''.number_format($InteresOr,2).'','L',0,'R',$fill);
					$this->Cell(4,5,'',0,0,'R',$fill);
					$this->Cell(18,5,''.number_format($Moratorio,2).'','L',0,'R',$fill);
					$this->Cell(2,5,'','',0,'R',$fill);
					$this->Cell(24,5,''.$no_autorizacion.'','LR',0,'C',$fill);
					$this->Cell(24,5,''.number_format($Total_Abonos,2).'','',0,'R',$fill);
					$this->Cell(2,5,'','R',0,'R',$fill);
					$this->Cell(18,5,''.number_format($SaldoInsoluto,2).'','',0,'R',$fill);
					$this->Cell(2,5,'','R',0,'R',$fill);
					
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
		$this->Ln();
		$this->Cell(7,5,'',0,0,'C',$fill);
		$this->Cell(22,5,'',0,0,'C',$fill);
		$this->Cell(25,5,'',0,0,'C',$fill);
		$this->Cell(26,5,'$'.number_format($suma_capital,2).'',0,0,'C',$fill);
		$this->Cell(26,5,'$'.number_format($suma_interes,2).'',0,0,'C',$fill);
		$this->Cell(20,5,'$'.number_format($suma_mora,2).'',0,0,'C',$fill);
		$this->Cell(24,5,'',0,0,'C',$fill);
		$this->Cell(24,5,'$'.number_format($suma_capital+$suma_interes+$suma_mora,2).'',0,0,'C',$fill);
		$this->Cell(22,5,'',0,0,'C',$fill);

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
		$this->Cell(65,4,'ING. FILADELFO MACIAS HERNANDEZ','T',0,'C',$fill);
		$this->Cell(5,4,'',0,0,'C',$fill);
		$this->Cell(56,4,'','',0,'C',$fill);
		$this->Cell(5,4,'',0,0,'C',$fill);
		$this->Cell(65,4,utf8_decode('C.P. GABRIEL LEON CAÑAVERAL'),'T',0,'C',$fill);
		$this->Ln();
		$this->Cell(65,3,'GERENTE GENERAL',0,0,'C',$fill);
		$this->Cell(5,3,'',0,0,'C',$fill);
		$this->Cell(56,3,'',0,0,'C',$fill);
		$this->Cell(5,3,'',0,0,'C',$fill);
		$this->Cell(65,3,'CONTADOR GENERAL',0,0,'C',$fill);
		$this->Ln();
		$this->Cell(65,3,'',0,0,'C',$fill);
		$this->Cell(5,3,'',0,0,'C',$fill);
		$this->Cell(56,3,'',0,0,'C',$fill);
		$this->Cell(5,3,'',0,0,'C',$fill);
		$this->Cell(65,3,'CEDULA P.: 7661293',0,0,'C',$fill);

	}
}

$pdf=new PDF('P','mm','Letter');

//Títulos de las columnas
$header01=array('','','','','','');
$header02=array('','','','','','');
$header=array('','','','','','','','','');
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
$pdf->SetY(33);
$pdf->SetX(111.7);
$pdf->Multicell(38,5,'FOLIO DE CREDITO:',0,'L');
$pdf->SetY(33);
$pdf->SetX(149.7);
$pdf->Multicell(56.4,5,''.$Folio.'',0,'R');
$pdf->SetY(36);
$pdf->SetX(111.7);
$pdf->Multicell(38,5,'MONTO OTORGADO:',0,'L');
$pdf->SetY(36);
$pdf->SetX(149.7);
$pdf->Multicell(56.4,5,'$'.number_format($monto,2).'',0,'R');
$pdf->SetY(39);
$pdf->SetX(111.7);
$pdf->Multicell(38,5,'FECHA DE APERTURA:',0,'L');
$pdf->SetFont('Arial','',8);
$pdf->SetY(39);
$pdf->SetX(149.7);
$pdf->Multicell(56.4,5,''.$diam.' / '.$mesm.' / '.$yearm.'',0,'R');
$pdf->SetFont('Arial','B',8);
$pdf->SetY(42);
$pdf->SetX(111.7);
$pdf->Multicell(38,5,'FECHA DE VENCIMIENTO:',0,'L');
$pdf->SetFont('Arial','',8);
$pdf->SetY(42);
$pdf->SetX(149.7);
$pdf->Multicell(56.4,5,''.$diav.' / '.$mesv.' / '.$yearv.'',0,'R');
$pdf->SetFont('Arial','B',8);
$pdf->SetY(45);
$pdf->SetX(111.7);
$pdf->Multicell(38,5,'PLAZO: (ANUAL)',0,'L');
$pdf->SetFont('Arial','',8);
$pdf->SetY(45);
$pdf->SetX(149.7);
$pdf->Multicell(56.4,5,'4',0,'R');
$pdf->SetFont('Arial','B',8);
$pdf->SetY(48);
$pdf->SetX(111.7);
$pdf->Multicell(38,5,'TASA MENSUAL:',0,'L');
$pdf->SetFont('Arial','',8);
$pdf->SetY(48);
$pdf->SetX(149.7);
$pdf->Multicell(56.4,5,''.$tasa.'%',0,'R');
$pdf->SetFont('Arial','B',8);
$pdf->SetY(51);
$pdf->SetX(111.7);
$pdf->Multicell(25,5,'PRODUCTO:',0,'L');
$pdf->SetFont('Arial','',8);
$pdf->SetY(51);
$pdf->SetX(137);
$pdf->Multicell(69.4,5,'REFACCIONARIO GANADERO',0,'R');
$pdf->SetFont('Arial','B',8);
$pdf->SetY(54);
$pdf->SetX(111.7);
$pdf->Multicell(38,5,'FUENTE:',0,'L');
$pdf->SetFont('Arial','',8);
$pdf->SetY(54);
$pdf->SetX(149.7);
$pdf->Multicell(56.4,5,''.$fondeadora.'',0,'R');
$pdf->SetFont('Arial','B',8);
$pdf->SetY(57);
$pdf->SetX(111.7);
$pdf->Multicell(38,5,'No. DE CLIENTE:',0,'L');
$pdf->SetFont('Arial','',8);
$pdf->SetY(57);
$pdf->SetX(149.7);
$pdf->Multicell(56.4,5,''.$id.'',0,'R');

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
$pdf->Multicell(44,5,'Interes ordinario:',0,'L');
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
$pdf->Multicell(25,5,'$'.number_format($intven_sll,2).'',0,'R');
$pdf->SetY(135);
$pdf->SetX(10);
$pdf->Multicell(44,5,'Capital vigente:',0,'L');
$pdf->SetY(135);
$pdf->SetX(54);
$pdf->Multicell(25,5,'$'.number_format($capvig_sll,2).'',0,'R');
//$pdf->Multicell(25,5,'$'.number_format($intven_sll,2).'',0,'R');
$pdf->SetY(140);
$pdf->SetX(10);
$pdf->Multicell(44,5,'Interes vigente:',0,'L');
$pdf->SetY(140);
$pdf->SetX(54);
$pdf->Multicell(25,5,'$'.number_format($intvig_sll,2).'',0,'R');
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
$pdf->Multicell(25,5,'$'.number_format($capven_sll,2).'',0,'R');
$pdf->SetY(130);
$pdf->SetX(79);
$pdf->Multicell(20,3,'Aplic. Seguro y otros:',0,'R');
$pdf->SetY(133);
$pdf->SetX(99);
$pdf->Multicell(25,3,'$0.00',0,'R');
$pdf->SetY(136);
$pdf->SetX(79);
$pdf->Multicell(22,5,'Abono capital:',0,'R');
$pdf->SetY(136);
$pdf->SetX(99);
$pdf->Multicell(25,5,'$'.number_format($totalAbonos,2).'',0,'R');
$pdf->SetY(142);
$pdf->SetX(79);
$pdf->Multicell(22,5,'Abono interes:',0,'R');
$pdf->SetY(142);
$pdf->SetX(99);
$pdf->Multicell(25,5,'$'.number_format($abono_Intereses,2).'',0,'R');

$pdf->SetY(120);
$pdf->SetX(124);
$pdf->SetFont('Arial','B',8);
$pdf->Multicell(82,5,'SALDO VENCIDO',0,'C');
$pdf->SetFont('Arial','',8);
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
$pdf->Multicell(43.3,5,'$'.number_format($capven_sll,2).'',0,'R');
$pdf->SetY(130);
$pdf->SetX(124);
$pdf->Multicell(39,5,'Intereses vencidos:',0,'L');
$pdf->SetY(130);
$pdf->SetX(163);
$pdf->Multicell(43.3,5,'$'.number_format($intven_sll-$intvig_sll,2).'',0,'R');
$pdf->SetY(135);
$pdf->SetX(124);
$pdf->Multicell(39,5,'Intereses moratorios:',0,'L');
$pdf->SetY(135);
$pdf->SetX(163);
$pdf->Multicell(43.3,5,'$'.number_format($intmor_sll,2).'',0,'R');
$pdf->SetY(140);
$pdf->SetX(124);
$pdf->SetFont('Arial','B',8);
$pdf->Multicell(39,5,'Total vencido:',0,'L');
$pdf->SetY(140);
$pdf->SetX(163);
$pdf->Multicell(43.3,5,'$'.number_format($capven_sll+$intven_sll+$intmor_sll-$intvig_sll,2).'',0,'R');
$pdf->SetFont('Arial','',8);

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
$pdf->SetX(17);
$pdf->Multicell(22,4,'CONTROL',0,'C');
$pdf->SetY(155);
$pdf->SetX(39);
$pdf->Multicell(25,4,'FECHA',0,'C');
$pdf->SetY(155);
$pdf->SetX(64);
$pdf->Multicell(26,4,'CAPITAL',0,'C');
$pdf->SetY(155);
$pdf->SetX(90);
$pdf->Multicell(26,4,'INTERES',0,'C');
$pdf->SetY(155);
$pdf->SetX(116);
$pdf->Multicell(20,4,'MORATORIO',0,'R');
$pdf->SetY(155);
$pdf->SetX(136);
$pdf->Multicell(24,4,'AUT',0,'C');
$pdf->SetY(155);
$pdf->SetX(160);
$pdf->Multicell(24,4,'ABONO TOTAL',0,'R');
$pdf->SetY(155);
$pdf->SetX(184);
$pdf->Multicell(22,4,'SALDO',0,'C');
$pdf->SetFont('Arial','',8);
//fin de cabeceras de tabls de pagos y abonos
$pdf->Multicell(0,1,'','B','L');
$pdf->Multicell(0,1,'','B','L');

$pdf->SetFont('Arial','',8);
$pdf->SetY(162);
$pdf->SetX(162);
$pdf->Multicell(36.3,4,'',0,'R');

//FIN PRIMERA LINEA OTORGAMIENTO DE CREDITO
$pdf->Multicell(0,0.5,' ');
$pdf->Tabla_3($header02);
$y1=$pdf->GetY();
$x1=$pdf->GetX();
$pdf->Tabla_4($header);
$y2=$pdf->GetY();

$pos1=$x1+0;
$pdf->SetXY($pos1,$y2);

$fill=false;
$pdf->Cell(196,1,'','TB',0,'C',$fill);
$pdf->Ln(4);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(65,4,'SALDOS PARA LIQUIDAR EL CREDITO',1,0,'C',$fill);
$pdf->SetFont('Arial','',8);
//$pdf->Cell(25,5,'',0,0,'C',$fill);
$pdf->Cell(7,5,'',0,0,'C',$fill);
$pdf->Cell(29,5,'',0,0,'C',$fill);
$pdf->Cell(30,5,'',0,0,'C',$fill);
//$pdf->Cell(30,4,'SALDOS POR PAGAR','LT',0,'R',$fill);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(65,4,'SALDOS POR PAGAR AMORTIZACION No. '.$amort_sll_es,'LTR',0,'C',$fill);
$pdf->SetFont('Arial','',8);
$pdf->Ln();
$fill=false;
$pdf->Cell(30,5,'CAPITAL:','LT',0,'R',$fill);
$pdf->Cell(35,5,'$'.number_format($pct_sll_es,2).'','TR',0,'R',$fill);
$pdf->Cell(7,5,'',0,0,'C',$fill);
$pdf->Cell(29,5,'',0,0,'C',$fill);
$pdf->Cell(30,5,'',0,0,'R',$fill);
$intereses01=$interes_hoy+$interes_mora;
$pdf->Cell(30,5,'CAPITAL:','LT',0,'R',$fill);
$pdf->Cell(35,5,'$'.number_format($ctp_sll_es,2).'','TR',0,'R',$fill);
$pdf->Ln();
$pdf->Cell(30,5,'INTERESES:','L',0,'R',$fill);
$pdf->Cell(35,5,'$'.number_format($pit_sll_es,2).'','R',0,'R',$fill);
$pdf->Cell(7,5,'',0,0,'C',$fill);
$pdf->Cell(29,5,'',0,0,'C',$fill);
$pdf->Cell(30,5,'',0,0,'R',$fill);
$pdf->Cell(30,5,'INTERESES:','L',0,'R',$fill);
$pdf->Cell(35,5,'$'.number_format($itp_sll_es,2).'','R',0,'R',$fill);
$pdf->Ln();
$pdf->Cell(30,5,'GARANTIA LIQUIDA:','LB',0,'R',$fill);
$pdf->Cell(35,5,'- $'.number_format($gl_press,2).'','RB',0,'R',$fill);
$pdf->Cell(7,5,'',0,0,'C',$fill);
$pdf->Cell(29,5,'',0,0,'C',$fill);
$pdf->Cell(30,5,'',0,0,'R',$fill);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(30,5,'TOTAL A PAGAR:','LTB',0,'R',$fill);
$pdf->Cell(35,5,'$'.number_format($pgt_sll_es,2).'','RTB',0,'R',$fill);
$pdf->SetFont('Arial','',8);
$pdf->Ln();
$pdf->SetFont('Arial','B',8);
$pdf->Cell(30,5,'TOTAL A PAGAR:','LB',0,'R',$fill);
$pdf->Cell(35,5,'$'.number_format(($pct_sll_es+$pit_sll_es)-$gl_press,2).'','RB',0,'R',$fill);
$pdf->Output();
?>
