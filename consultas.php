<?php
date_default_timezone_set('America/Mexico_City');
//$Folio='RG3001-193451';// SUPER NUEVO 2019
//$Folio='RG1812-153781';
//$Folio='RG2405-1610091';
//$Folio='RG0804-163051';
//$Folio='RG1304-163251';
//$Folio='RG3112-695811';//SUPER BUENO
//$Folio='RG1104-16951';
//$Folio='RG1712-152771';
//$Folio='RG1204-163741';
//$Folio='RG2011-1526';
$Folio='RG1904-168641';
$fecha_solicitada='2019-04-12';
$respuesta='0';
$dias_transf='127';
$dias_vencidos01='127';




include ("../../modulos/cobranza/conexion/conn.php");
$query01="SELECT 
		 credito.id as idcredito,
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
	"\n";
}
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
									AND abono.tipo_pago='0') IS NULL,pago.fecha_pago, (SELECT MAX(abono.fecha_deposito) 
																								FROM abono 
																									WHERE abono.pago_id=pago.id 
																									AND abono.tipo_pago='0'))) AS fu_sll,
					IF (pago.estatus='PAGADO', '".$fecha_solicitada."', 
						IF((SELECT MAX(abono.fecha_deposito) 
								FROM abono 
									WHERE abono.pago_id=pago.id 
									AND abono.tipo_pago='1') IS NULL,pago.fecha_pago, (SELECT MAX(abono.fecha_deposito) 
																								FROM abono 
																									WHERE abono.pago_id=pago.id 
																									AND abono.tipo_pago='1'))) AS fi_sll,
					IF (pago.estatus='PAGADO', '".$fecha_solicitada."', 
						IF((SELECT MAX(abono.fecha_deposito) 
								FROM abono 
									WHERE abono.pago_id=pago.id 
									AND abono.tipo_pago='2') IS NULL,pago.fecha_pago, (SELECT MAX(abono.fecha_deposito) 
																								FROM abono 
																									WHERE abono.pago_id=pago.id 
																									AND abono.tipo_pago='2'))) AS fm_sll,
					IF (pago.estatus='PAGADO', 0, pago.monto_capital) AS ca_sll,
					IF (pago.estatus='PAGADO', 0, pago.monto_intereses) AS in_sll, 
					IF (pago.fecha_pago<'".$fecha_solicitada."', 'VENCIDO',
						IF (pago.fecha_pago>='".$fecha_solicitada."' AND pago.fecha_pago<=DATE_ADD('".$fecha_solicitada."',INTERVAL 365 DAY),'VIGENTE', 'PENDIENTE')) AS es_sll, 
					pago.fecha_pago AS fp_sll,
					pago.estatus 
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
	}
////////////////VAR SUPERESPERCIALES
$fh_sll_es=new datetime($fecha_solicitada);
$ts_sll_es=$tasa/100;
$cpt_sll_es=($ca_sll[0]+$ca_sll[1]+$ca_sll[2]+$ca_sll[3]+$ca_sll[4]);
$itr_sll_es=($cp_sll[0]+$cp_sll[1]+$cp_sll[2]+$cp_sll[3]+$cp_sll[4]);
/////////////////

/////1RA AMORTIZACION///////////////////////////////////////////////////////////////////////////////////////////////////
IF ($es_sll[0]=='VIGENTE'){
	$capvigente00=$ca_sll[0]-$cp_sll[0];
	$fu_sll00=new datetime($fu_sll[0]);
		IF ($fu_sll00>$fh_sll_es){
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
		$fp_sll00=new datetime($fp_sll[0]);
			IF ($fp_sll00>$fu_sll00){
				echo $divte00=$fp_sll00->diff($fu_sll00)->format("%a").' -- 1  --';
			}ELSE{
				echo $divte00='0'.' -- 1  --';
			}
			IF ($fh_sll_es>$fu_sll00){
				echo $divdo00=$fh_sll_es->diff($fu_sll00)->format("%a").' -- 1  --';
			}ELSE{
				echo $divdo00='0'.' -- 1  --';
			}
		$ivgt00=round(((($capvencido00+$ca_sll[1]+$ca_sll[2]+$ca_sll[3])*$ts_sll_es)/360)*$divte00,0);
		$ivdo00=round(((($capvencido00)*$ts_sll_es)/360)*$divdo00,0);
		$intvencido00=$ivgt00+$ivdo00;
		$fm_sll00=new datetime($fm_sll[0]);
		IF ($fm_sll00<$fh_sll_es){
		$diamorator00=$fh_sll_es->diff($fm_sll00)->format("%a");
		}ELSE{
		$diamorator00=0;
		}
		$intmorator00=round(((($capvencido00)*($ts_sll_es*2))/360)*$diamorator00,0);
//echo 'VENCIDO';
		}ELSEIF($es_sll[0]=='PENDIENTE'){
			$cappendiente00=$ca_sll[0]-$cp_sll[0];
			$intpendiente00=$in_sll[0]-$ip_sll[0];
//echo 'PENDIENTE';
}
////

/////2DA AMORTIZACION///////////////////////////////////////////////////////////////////////////////////////////////////
IF ($es_sll[1]=='VIGENTE'){
	$capvigente01=$ca_sll[1]-$cp_sll[1];
	$fu_sll01=new datetime($fu_sll[1]);
	IF ($fu_sll01>$fh_sll_es){
		$diatranscu01=365-($fu_sll01->diff($fh_sll_es)->format("%a"));
	}ELSE{
		$diatranscu01=($fu_sll01->diff($fh_sll_es)->format("%a"));
		$amort_sll_es=2;
	}
	$intvigente01=round(((($capvigente01+$ca_sll[2]+$ca_sll[3])*$ts_sll_es)/360)*$diatranscu01,0);
//echo 'VIGENTE';
	}ELSEIF($es_sll[1]=='VENCIDO'){
		$amort_sll_es=2;
		$capvencido01=$ca_sll[1]-$cp_sll[1];
		$fu_sll01=new datetime($fu_sll[1]);
		$fp_sll01=new datetime($fp_sll[1]);
			IF ($fp_sll01>$fu_sll01){
			echo $divte01=$fp_sll01->diff($fu_sll01)->format("%a");
			}ELSE{
			echo $divte01=365;
			}
			IF ($fh_sll_es>$fu_sll01){
			echo $divdo01=$fh_sll_es->diff($fu_sll01)->format("%a");
			}ELSE{
			echo $divdo01=0;
			}
		$ivgt01=round(((($capvencido01+$ca_sll[2]+$ca_sll[3])*$ts_sll_es)/360)*$divte01,0);
		$ivdo01=round(((($capvencido01)*$ts_sll_es)/360)*$divdo00,0);
		$intvencido01=$ivgt01+$ivdo01;
		$fm_sll01=new datetime($fm_sll[1]);
		IF ($fm_sll01<$fh_sll_es){
		$diamorator01=$fh_sll_es->diff($fm_sll01)->format("%a");
		}ELSE{
		$diamorator01=0;
		}
		$intmorator01=round(((($capvencido01)*($ts_sll_es*2))/360)*$diamorator01,0);
//echo 'VENCIDO';
		}ELSEIF($es_sll[1]=='PENDIENTE'){
			$cappendiente01=$ca_sll[1]-$cp_sll[1];
			$intpendiente01=$in_sll[1]-$ip_sll[1];
//echo 'PENDIENTE';
}
////
/////3RA AMORTIZACION////////////////////////////////////////////////////////////////////////////////////////////////
IF ($es_sll[2]=='VIGENTE'){
	$capvigente02=$ca_sll[2]-$cp_sll[2];
	$fu_sll02=new datetime($fu_sll[2]);
	IF ($fu_sll02>$fh_sll_es){
		$diatranscu02=365-($fu_sll02->diff($fh_sll_es)->format("%a"));
	}ELSE{
		$diatranscu02=($fu_sll02->diff($fh_sll_es)->format("%a"));
		$amort_sll_es=3;
	}
	$intvigente02=round(((($capvigente02+$ca_sll[3])*$ts_sll_es)/360)*$diatranscu02,0);
//echo 'VIGENTE';
	}ELSEIF($es_sll[2]=='VENCIDO'){
		$amort_sll_es=3;
		$capvencido02=$ca_sll[2]-$cp_sll[2];
		$fu_sll02=new datetime($fu_sll[2]);
		$fp_sll02=new datetime($fp_sll[2]);
			IF ($fp_sll02>$fu_sll02){
			echo $divte02=$fp_sll02->diff($fu_sll02)->format("%a");
			}ELSE{
			echo $divte02=365;
			}
			IF ($fh_sll_es>$fu_sll02){
			echo $divdo02=$fh_sll_es->diff($fu_sll02)->format("%a");
			}ELSE{
			echo $divdo02=0;
			}
		$ivgt02=round(((($capvencido02+$ca_sll[3])*$ts_sll_es)/360)*$divte02,0);
		$ivdo02=round(((($capvencido02)*$ts_sll_es)/360)*$divdo00,0);
		$intvencido02=$ivgt02+$ivdo02;
		$fm_sll02=new datetime($fm_sll[2]);
		IF ($fm_sll02<$fh_sll_es){
		$diamorator02=$fh_sll_es->diff($fm_sll02)->format("%a");
		}ELSE{
		$diamorator02=0;
		}
		$intmorator02=round(((($capvencido02)*($ts_sll_es*2))/360)*$diamorator02,0);
//echo 'VENCIDO';
		}ELSEIF($es_sll[2]=='PENDIENTE'){
			$cappendiente02=$ca_sll[2]-$cp_sll[2];
			$intpendiente02=$in_sll[2]-$ip_sll[2];
//echo 'PENDIENTE';
}
////

/////4TA AMORTIZACION////////////////////////////////////////////////////////////////////////////
IF ($es_sll[3]=='VIGENTE'){
	$capvigente03=$ca_sll[3]-$cp_sll[3];
	$fu_sll03=new datetime($fu_sll[3]);
	IF ($fu_sll03>$fh_sll_es){
		$diatranscu03=365-($fu_sll03->diff($fh_sll_es)->format("%a"));
	}ELSE{
		$diatranscu03=($fu_sll03->diff($fh_sll_es)->format("%a"));
		$amort_sll_es=4;
	}
	$intvigente03=round((($capvigente03*$ts_sll_es)/360)*$diatranscu03,0);
//echo 'VIGENTE';
	}ELSEIF($es_sll[3]=='VENCIDO'){
		$amort_sll_es=4;
		$capvencido03=$ca_sll[3]-$cp_sll[3];
		$fu_sll03=new datetime($fu_sll[3]);
		$fp_sll03=new datetime($fp_sll[3]);
			IF ($fp_sll03>$fu_sll03){
			echo $divte03=$fp_sll03->diff($fu_sll03)->format("%a");
			}ELSE{
			echo $divte03=365;
			}
			IF ($fh_sll_es>$fu_sll03){
			echo $divdo03=$fh_sll_es->diff($fu_sll03)->format("%a");
			}ELSE{
			echo $divdo03=0;
			}
		$ivgt03=round(((($capvencido03)*$ts_sll_es)/360)*$divte03,0);
		$ivdo03=round(((($capvencido03)*$ts_sll_es)/360)*$divdo03,0);
		$intvencido03=round(((($capvencido03)*$ts_sll_es)/360)*$diavencido03,0);
		$fm_sll03=new datetime($fm_sll[3]);
		IF ($fm_sll03<$fh_sll_es){
		$diamorator03=$fh_sll_es->diff($fm_sll03)->format("%a");
		}ELSE{
		$diamorator03=0;
		}
		$intmorator03=round(((($capvencido03)*($ts_sll_es*2))/360)*$diamorator03,0);
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
	$pit_sll_es=$ivgt_sll+$ivdt_sll+$imrt_sll;
	$ptt_sll_es=$pct_sll_es+$pit_sll_es;
///////////////////////////////////////////////////----------------


///                                                        ///
////////////---------------------------------------///////////

///////////////////////////////////////////////////-----------------------------////////////////////////////////////////
?>
