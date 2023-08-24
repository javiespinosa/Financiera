<?php
	require_once ("config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("config/conexion.php");//Contiene funcion que conecta a la base de datos
	date_default_timezone_set('America/Mexico_City');
	
$IdMicro=$_REQUEST['IdMicro'];
$intx2=$_REQUEST['intx1'];
$capx2=$_REQUEST['capx1'];
$x2=$_REQUEST['moratot'];
$intvtot=$_REQUEST['intvtot'];

$query00="select * from microcredito where IdMicrocredito='$IdMicro'";

	$result = mysql_query($query00);    

   while($row = mysql_fetch_array($result)) 
   { 

 
//---------------Consulta Monto Solicitado-------------
$MontoCreSolicM=$row["MontoCreSolicM"];
$NumContrat=$row["NoContratM"];
//Clase para Conversion a letras del monto solicitado-------------
/*include ("NumEnLetras.php");
//-------------- Programa principal ------------------------

 $num22=$MontoCreSolicM;
 $GL1=$MontoCreSolicM*.10;
 $GL=number_format($GL1);
 $num23=$GL1;

 $V=new EnLetras();
$LetMonto=$V->ValorEnLetras($num22,"PESOS 00/100");
$LetGL=$V->ValorEnLetras($num23,"PESOS 00/100");

//fin de la conversion*/

//Consulta Tasa Credito
$TasaInterMenM=($row["TasaInterMenM"])*100;
	

//Consulta de Fecha de Contrato
$FecContratM=$row["FecContratM"];
setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
$dd=strftime ("%d",strtotime($FecContratM));
$mm=strftime ("%B",strtotime($FecContratM));
		if($mm=='enero'){
		$mm='ENERO';
		$mm1='01';
		}elseif($mm=='febrero'){
		$mm='FEBRERO';
		$mm1='02';
		}elseif($mm=='marzo'){
		$mm='MARZO';
		$mm1='03';
		}elseif($mm=='abril'){
		$mm='ABRIL';
		$mm1='04';
		}elseif($mm=='mayo'){
		$mm='MAYO';
		$mm1='05';
		}elseif($mm=='junio'){
		$mm='JUNIO';
		$mm1='06';
		}elseif($mm=='julio'){
		$mm='JULIO';
		$mm1='07';
		}elseif($mm=='agosto'){
		$mm='AGOSTO';
		$mm1='08';
		}elseif($mm=='septiembre'){
		$mm='SEPTIEMBRE';
		$mm1='09';
		}elseif($mm=='octubre'){
		$mm='OCTUBRE';
		$mm1='10';
		}elseif($mm=='noviembre'){
		$mm='NOVIEMBRE';
		$mm1='11';
		}elseif($mm=='diciembre'){
		$mm='DICIEMBRE';
		$mm1='12';
		}
$yy=strftime ("%Y",strtotime($FecContratM));
//FECHA 12
$f12=$row["FXIIM"];
$dd12=strftime ("%d",strtotime($f12));
$mm12=strftime ("%m",strtotime($f12));
if($mm12=='01'){
		$mm121='ENERO';
		}elseif($mm12=='02'){
		$mm121='FEBRERO';
		}elseif($mm12=='03'){
		$mm121='MARZO';
		}elseif($mm12=='04'){
		$mm121='ABRIL';
		}elseif($mm12=='05'){
		$mm121='MAYO';
		}elseif($mm12=='06'){
		$mm121='JUNIO';
		}elseif($mm12=='07'){
		$mm121='JULIO';
		}elseif($mm12=='08'){
		$mm121='AGOSTO';
		}elseif($mm12=='09'){
		$mm121='SEPTIEMBRE';
		}elseif($mm12=='1O'){
		$mm121='OCTUBRE';
		}elseif($mm12=='11'){
		$mm121='NOVIEMBRE';
		}elseif($mm12=='12'){
		$mm121='DICIEMBRE';
		}
$yy12=strftime ("%Y",strtotime($f12));
//--FIN DE FECHA 12

//FECHA HOY
$fhoy=date('d-m-Y');
$dhoy=strftime ("%d",strtotime($fhoy));
$mhoy=strftime ("%m",strtotime($fhoy));
if($mhoy=='01'){
		$mhoy='ENERO';
		}elseif($mhoy=='02'){
		$mhoy='FEBRERO';
		}elseif($mhoy=='03'){
		$mhoy='MARZO';
		}elseif($mhoy=='04'){
		$mhoy='ABRIL';
		}elseif($mhoy=='05'){
		$mhoy='MAYO';
		}elseif($mhoy=='06'){
		$mhoy='JUNIO';
		}elseif($mhoy=='07'){
		$mhoy='JULIO';
		}elseif($mhoy=='08'){
		$mhoy='AGOSTO';
		}elseif($mhoy=='09'){
		$mhoy='SEPTIEMBRE';
		}elseif($mhoy=='1O'){
		$mhoy='OCTUBRE';
		}elseif($mhoy=='11'){
		$mhoy='NOVIEMBRE';
		}elseif($mhoy=='12'){
		$mhoy='DICIEMBRE';
		}
$yhoy=strftime ("%Y",strtotime($fhoy));

//--FIN DE FECHA hoy

//Consulta Id del Acreditado
$IdCliente=$row["IdCliente"];
//consulta del acreditado
			$query001="select * from cliente
			where IdCliente='$IdCliente'";

			$result00 = mysql_query($query001);    

  		    while($row00 = mysql_fetch_array($result00)) 
   			{ 
			$PNomC=$row00["PNomC"];
			$SNomC=$row00["SNomC"];
			$ApatC=$row00["ApatC"];
			$AmatC=$row00["AmatC"];
			$DomC=$row00["DomC"];
			$ColC=$row00["ColC"];
			$MpioC=$row00["MpioC"];
			$EdoC=$row00["EdoC"];
   } 
   mysql_free_result($result00);
//finaliza consulta del acreditado
//Consulta Id del Aval
$IdAvalM=$row["IdAvalM"];
//consulta del detalles del Aval
			$query002="select * from cliente
			where IdCliente='$IdAvalM'";

			$result02 = mysql_query($query002);    

  		    while($row01 = mysql_fetch_array($result02)) 
   			{ 
			$PNomCA=$row01["PNomC"];
			$SNomCA=$row01["SNomC"];
			$ApatCA=$row01["ApatC"];
			$AmatCA=$row01["AmatC"];
			$DomCA=$row01["DomC"];
			$ColCA=$row01["ColC"];
			$MpioCA=$row01["MpioC"];
			$EdoCA=$row01["EdoC"]; 
   } 
   mysql_free_result($result02);
//finaliza consulta del Aval	 
//Consulta del Neagocio del Acreditado
$IdCliente=$row["IdCliente"];
//consulta del negocio
			$query003="select * from negocio
			where IdCliente='$IdCliente'";

			$result03 = mysql_query($query003);    

  		    while($row02 = mysql_fetch_array($result03)) 
   			{ 
			$GiroN=$row02["GiroN"];
			$ActN=$row02["ActN"];
		    } 
   mysql_free_result($result03);
//finaliza Consulta del Neagocio del Acreditado

$IdFuente=$row["IdFuente"];
//consulta de la fuente
			$query004="select * from fuente
			where IdFuente='$IdFuente'";

			$result04 = mysql_query($query004);    

  		    while($row04 = mysql_fetch_array($result04)) 
   			{ 
			$NomFuenF=$row04["NomFuenF"];
			} 
   mysql_free_result($result04);
//finaliza Consulta de la fuente

$IdMicrocredito=$row["IdMicrocredito"];
//consulta de la fuente
			$query005="select *,SUM(abonomicrocredito.AbonoCapital) as TotAboCap,SUM(abonomicrocredito.AbonoInteres) as TotAboInt from abonomicrocredito
			where IdMicrocredito='$IdMicrocredito'";

			$result05 = mysql_query($query005);    

  		    while($row05 = mysql_fetch_array($result05)) 
   			{ 
//---------------Consultando los abonos realizadod-------------
$AbonoCapital=$row05['AbonoCapital'];
$AbonoInteres=$row05['AbonoInteres'];
$TotAboCap=$row05['TotAboCap'];
$TotAboInt=$row05['TotAboInt'];
//---------------fin de las consultas realizadasConsultando los abonos realizadod-------------
			} 
   mysql_free_result($result05);
//finaliza Consulta de la fuente

   } 
   mysql_free_result($result);

//------------------------------------------------------------pdf
	require('./fpdf/fpdf.php');

class PDF extends FPDF
{
//Cabecera de p�gina
  function Header()
{
	/// Logo
	$this->Image('LOGOCAPP.png',10,8,33);
	// Arial bold 15
	$this->SetFont('Arial','',10);
	// Movernos a la derecha
	$this->Cell(40);
	// T�tulo
	$this->Multicell(120,4,'CONSULTORES ASOCIADOS EN PRODUCCION',0,'C');
	// Salto de l�nea
	$this->Ln(1);
	$this->Cell(40);
	$this->Multicell(120,4,'PECUARIA S.A. DE .C.V. SOFOM E.N.R.',0,'C');
	$this->SetFont('Arial','',10);
	$this->Ln(1);
	$this->Cell(40);
	$this->SetFont('Arial','',8);
	$this->Multicell(120,4,'R.F.C.: CAP-020715-JE3',0,'C');
	$this->SetFont('Arial','',10);
	$this->Ln(1);
	$this->Cell(196.3,1,'','B');
	$this->Ln(2);
}
   
   //Pie de p�gina -- desactivado -- comentariado
   function Footer()
   {
     //Posici�n: a 1,5 cm del final
	$this->SetY(-17);
	$this->Cell(196.3,1,'','B');
	$this->Ln(2);
	$this->SetFont('Arial','',7);
	$this->Multicell(0,4,'Oficina 8a Oriente Sur No. 125, Tuxtla Guti�rrez, Chiapas, CP 29000, Col. Centro','C','C');
	$this->Multicell(0,4,'Tel/Fax: 961 61 24882 Email: cappsc@hotmail.com','C','C');
   }
   //Tabla_1
function Tabla_1($header)
{
//---------fin de consulta para la tabla 1
//Colores, ancho de l�nea y fuente en negrita
$this->SetFillColor(0,120,140);
$this->SetTextColor(255);
$this->SetDrawColor(0,0,0);
$this->SetLineWidth(.3);
$this->SetFont('','B');
//Cabecera
/*for($i=0;$i<count($header);$i++)
$this->Cell(31.4,6,$header[$i],1,0,'C',1);
$this->Ln();*/
//Restauraci�n de colores y fuentes
$this->SetFillColor(255,255,255);
$this->SetTextColor(0);
$this->SetFont('');
//Datos
$fill=false;
$this->Cell(43.25,5,'','LT',0,'C',$fill);
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
   $this->Ln();

}
 //TABLA 2
 
function Tabla_2($header)
{
//---------fin de consulta para la tabla 1
//Colores, ancho de l�nea y fuente en negrita
$this->SetFillColor(0,120,140);
$this->SetTextColor(255);
$this->SetDrawColor(0,0,0);
$this->SetLineWidth(.3);
$this->SetFont('','B');
//Cabecera
/*for($i=0;$i<count($header);$i++)
$this->Cell(31.4,6,$header[$i],1,0,'C',1);
$this->Ln();*/
//Restauraci�n de colores y fuentes
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
$fill=!$fill;
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
//---------consulta para la tabla 3
include ("conexion/conn.php");

$IdMicro=$_REQUEST['IdMicro'];

$query00="select *,abonomicrocredito.IdMicrocredito as IdMicroAbo from abonomicrocredito,microcredito
where abonomicrocredito.IdMicrocredito='$IdMicro' and microcredito.IdMicrocredito='$IdMicro'";
$result = mysql_query($query00);    
$contador=0;

   if($row = mysql_fetch_array($result)) {
$IdMicroAbo=$row["IdMicroAbo"];
if(!empty($IdMicroAbo) and !isset($IdMicroAbo)){
	//Colores, ancho de l�nea y fuente en negrita
$this->SetFillColor(255,255,255);
$this->SetTextColor(255);
$this->SetDrawColor(0,0,0);
$this->SetLineWidth(.3);
$this->SetFont('','B');
//Cabecera
//Restauraci�n de colores y fuentes
$this->SetFillColor(255,255,255);
$this->SetTextColor(0);
$this->SetFont('');
$contador++;
$fill=false;
$this->Cell(7,5,''.$contador.'','',0,'C',$fill);
$this->Cell(20,5,'SIN ABONOS REALIZADOS','',0,'C',$fill);
$this->Ln();
$fill=!$fill;
$fill=true;
$this->Cell(188.3,0,'','');
}else{
//Colores, ancho de l�nea y fuente en negrita
$this->SetFillColor(255,255,255);
$this->SetTextColor(255);
$this->SetDrawColor(0,0,0);
$this->SetLineWidth(.3);
$this->SetFont('','B');
//Cabecera

/*for($i=0;$i<count($header);$i++)
$this->Cell(7,6,"",1,0,'C',0);
$this->Cell(20,6,"",1,0,'C',0);
$this->Cell(45,6,"",1,0,'C',0);
$this->Cell(25,6,"",1,0,'C',1);
$this->Cell(25,6,"",1,0,'C',1);
$this->Cell(30,6,"",1,0,'C',1);
$this->Cell(36.3,"",1,1,0,'C',1);
$this->Ln();*/
//Restauraci�n de colores y fuentes
$this->SetFillColor(255,255,255);
$this->SetTextColor(0);
$this->SetFont('');
do  {
$FecPagAbo=$row["FecPagAbo"];
setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
$dd=strftime ("%d",strtotime($FecPagAbo));
$mm=strftime ("%B",strtotime($FecPagAbo));
		if($mm=='enero'){
		$mm='ENERO';
		$mm1='01';
		}elseif($mm=='febrero'){
		$mm='FEBRERO';
		$mm1='02';
		}elseif($mm=='marzo'){
		$mm='MARZO';
		$mm1='03';
		}elseif($mm=='abril'){
		$mm='ABRIL';
		$mm1='04';
		}elseif($mm=='mayo'){
		$mm='MAYO';
		$mm1='05';
		}elseif($mm=='junio'){
		$mm='JUNIO';
		$mm1='06';
		}elseif($mm=='julio'){
		$mm='JULIO';
		$mm1='07';
		}elseif($mm=='agosto'){
		$mm='AGOSTO';
		$mm1='08';
		}elseif($mm=='septiembre'){
		$mm='SEPTIEMBRE';
		$mm1='09';
		}elseif($mm=='octubre'){
		$mm='OCTUBRE';
		$mm1='10';
		}elseif($mm=='noviembre'){
		$mm='NOVIEMBRE';
		$mm1='11';
		}elseif($mm=='diciembre'){
		$mm='DICIEMBRE';
		$mm1='12';
		}
$yy=strftime ("%Y",strtotime($FecPagAbo));
$contador++;
$fill=false;
$this->Cell(7,5,''.$contador.'','',0,'C',$fill);
$this->Cell(20,5,''.$dd.'/'.$mm1.'/'.$yy.'','',0,'C',$fill);
$this->Cell(45,5,'','',0,'C',$fill);
$this->Cell(25,5,'','',0,'C',$fill);
$this->Cell(25,5,''.number_format($row["AbonoCapital"]).'.00','',0,'R',$fill);
$this->Cell(30,5,''.number_format($row["AbonoCapital"]+$row["AbonoInteres"]).'.00','',0,'R',$fill);
$this->Cell(36.3,5,''.number_format($row["SaldoCap"]).'.00','',0,'R',$fill);
$this->Ln();
$fill=!$fill;
} while ($row = mysql_fetch_array($result));
$fill=true;
   $this->Cell(188.3,0,'','');
   }
   }
   mysql_free_result($result);
//---------fin de consulta para la tabla 3
 /*//Colores, ancho de l�nea y fuente en negrita
$this->SetFillColor(0,120,140);
$this->SetTextColor(255);
$this->SetDrawColor(0,0,100);
$this->SetLineWidth(.3);
$this->SetFont('','B');
//Cabecera

for($i=0;$i<count($header);$i++)
$this->Cell(31.4,6,$header[$i],1,0,'C',1);
$this->Ln();
//Restauraci�n de colores y fuentes
$this->SetFillColor(224,235,255);
$this->SetTextColor(0);
$this->SetFont('');
//Datos
$fill=false;
$this->Cell(43.25,5,''.$AbonoCapital.'','LT',0,'C',$fill);
$this->Cell(43.25,5,'','T',0,'C',$fill);
$this->Cell(15.3,5,'','T',0,'C',$fill);
$this->Cell(43.25,5,'','T',0,'C',$fill);
$this->Cell(43.25,5,'','TR',0,'C',$fill);
$this->Ln();
   $fill=!$fill;
$this->Cell(43.25,5,'','L',0,'C',$fill);
$this->Cell(43.25,5,'','',0,'C',$fill);
$this->Cell(15.3,5,'','',0,'C',$fill);
$this->Cell(43.25,5,'','',0,'C',$fill);
$this->Cell(43.25,5,'','R',0,'C',$fill);
$fill=true;
   $this->Ln();
   $this->Cell(188.3,0,'','T');
 //fin tabla 3*/

}
   
}

$pdf=new PDF('P','mm','Letter');
//T�tulos de las columnas
$header01=array('','','','','','');
$header02=array('','','','','','');
$header03=array('','','','','','');
$pdf->AliasNbPages();
//Primera p�gina
$pdf->AddPage();
$pdf->SetFont('Arial','B',15);
$pdf->Multicell(0,1,'   ');
$pdf->Multicell(0,5,'ESTADO DE CUENTA DE CR�DITO','C','C');
$pdf->Multicell(0,0.5,'   ');
$pdf->SetFont('Arial','',8);
$pdf->Multicell(0,5,'SALDOS AL DIA: '.$dhoy.' DE '.$mhoy.' DE '.$yhoy.'','','');
$pdf->Multicell(0,5,'SUCURSAL: 0101-CAPP TUXTLA','','');
$pdf->Multicell(0,2,'   ');
$pdf->SetFont('Arial','',8);
$pdf->Multicell(0,5,'');
$pdf->Multicell(0,3,'');
$pdf->Multicell(0,5,'');
$pdf->Multicell(0,5,'');
$pdf->SetY(44);
$pdf->Tabla_1($header01);
$pdf->SetY(98);
$pdf->Tabla_2($header02);
$pdf->Multicell(0,2,' ');
//llenado el primer cuadro
$pdf->SetY(44);
$pdf->SetX(10);
$pdf->Multicell(86.4,5,'',0,'C');
$pdf->SetFont('Arial','B',8);
$pdf->SetY(49);
$pdf->SetX(10);
$pdf->Multicell(20,5,'NOMBRE:',0,'R');
$pdf->SetFont('Arial','',8);
$pdf->SetY(49);
$pdf->SetX(30);
$pdf->Multicell(66.4,5,''.$PNomC.' '.$SNomC.' '.$ApatC.' '.$AmatC.'',0,'L');
$pdf->SetY(54);
$pdf->SetX(10);
$pdf->Multicell(20,5,'',0,'C');
$pdf->SetFont('Arial','B',8);
$pdf->SetY(59);
$pdf->SetX(10);
$pdf->Multicell(20,5,'DOMICILIO:',0,'R');
$pdf->SetFont('Arial','',8);
$pdf->SetY(59);
$pdf->SetX(30);
$pdf->Multicell(66.4,5,''.$DomC.' '.$ColC.' '.$MpioC.' '.$EdoC.'',0,'C');
//llenado del segundo cuadro
$pdf->SetY(44);
$pdf->SetX(111.7);
$pdf->Multicell(86.4,5,'',0,'C');
$pdf->SetFont('Arial','B',8);
$pdf->SetY(49);
$pdf->SetX(111.7);
$pdf->Multicell(38,5,'No. DE CR�DITO:',0,'L');
$pdf->SetY(49);
$pdf->SetX(149.7);
$pdf->Multicell(56.4,5,''.$NumContrat.'',0,'R');
$pdf->SetY(54);
$pdf->SetX(111.7);
$pdf->Multicell(38,5,'MONTO OTORGADO:',0,'L');
$pdf->SetY(54);
$pdf->SetX(149.7);
$pdf->Multicell(56.4,5,'$'.number_format($MontoCreSolicM).'.00',0,'R');
$pdf->SetY(59);
$pdf->SetX(111.7);
$pdf->Multicell(38,5,'FECHA DE APERTURA:',0,'L');
$pdf->SetFont('Arial','',8);
$pdf->SetY(59);
$pdf->SetX(149.7);
$pdf->Multicell(56.4,5,''.$dd.' DE '.$mm.' DE '.$yy.'',0,'R');
$pdf->SetFont('Arial','B',8);
$pdf->SetY(64);
$pdf->SetX(111.7);
$pdf->Multicell(38,5,'FECHA DE VENCIMIENTO:',0,'L');
$pdf->SetFont('Arial','',8);
$pdf->SetY(64);
$pdf->SetX(149.7);
$pdf->Multicell(56.4,5,''.$dd12.' DE '.$mm121.' DE '.$yy12.'',0,'R');
$pdf->SetFont('Arial','B',8);
$pdf->SetY(69);
$pdf->SetX(111.7);
$pdf->Multicell(38,5,'PLAZO: (MESES)',0,'L');
$pdf->SetFont('Arial','',8);
$pdf->SetY(69);
$pdf->SetX(149.7);
$pdf->Multicell(56.4,5,'6',0,'R');
$pdf->SetFont('Arial','B',8);
$pdf->SetY(74);
$pdf->SetX(111.7);
$pdf->Multicell(38,5,'TASA MENSUAL:',0,'L');
$pdf->SetFont('Arial','',8);
$pdf->SetY(74);
$pdf->SetX(149.7);
$pdf->Multicell(56.4,5,''.$TasaInterMenM.' %',0,'R');
$pdf->SetFont('Arial','B',8);
$pdf->SetY(79);
$pdf->SetX(111.7);
$pdf->Multicell(38,5,'PRODUCTO:',0,'L');
$pdf->SetFont('Arial','',8);
$pdf->SetY(79);
$pdf->SetX(149.7);
$pdf->Multicell(56.4,5,'MICRO-'.$NomFuenF.'',0,'R');
$pdf->SetFont('Arial','B',8);
$pdf->SetY(84);
$pdf->SetX(111.7);
$pdf->Multicell(38,5,'No. DE CONTROL:',0,'L');
$pdf->SetFont('Arial','',8);
$pdf->SetY(84);
$pdf->SetX(149.7);
$pdf->Multicell(56.4,5,''.$IdMicro.'',0,'R');
//fin del llenado del segundo cuadro
//llenado del tercer cuadro
$pdf->SetY(98);
$pdf->SetX(10);
$pdf->Multicell(188.3,5,'',0,'L');
$pdf->SetY(103);
$pdf->SetX(10);
$pdf->Multicell(44,5,'SALDO INICIAL DEL PERIODO:',0,'L');
$pdf->SetY(103);
$pdf->SetX(54);
$pdf->Multicell(25,5,''.number_format($MontoCreSolicM).'.00',0,'R');
$pdf->SetY(108);
$pdf->SetX(10);
$pdf->Multicell(44,5,'CAPITAL INSOLUTO:',0,'L');
$pdf->SetY(108);
$pdf->SetX(54);
$pdf->Multicell(25,5,''.number_format($MontoCreSolicM-$TotAboCap).'.00',0,'R');
$pdf->SetY(113);
$pdf->SetX(10);
$pdf->Multicell(44,5,'INTERESES ORDINARIOS:',0,'L');
$pdf->SetY(113);
$pdf->SetX(54);
$pdf->Multicell(25,5,''.number_format($TotAboInt).'.00',0,'R');
$pdf->SetY(118);
$pdf->SetX(10);
$pdf->Multicell(44,5,'I.V.A.:',0,'L');
$pdf->SetY(118);
$pdf->SetX(54);
$pdf->Multicell(25,5,'0.00',0,'R');
$pdf->SetY(103);
$pdf->SetX(79);
$pdf->Multicell(20,5,'CARGOS:',0,'R');
$pdf->SetY(103);
$pdf->SetX(99);
$pdf->Multicell(25,5,''.number_format($MontoCreSolicM).'.00',0,'R');
$pdf->SetY(108);
$pdf->SetX(79);
$pdf->Multicell(20,5,'ABONOS:',0,'R');
$pdf->SetY(108);
$pdf->SetX(99);
$pdf->Multicell(25,5,''.number_format($TotAboCap).'.00',0,'R');
$pdf->SetY(103);
$pdf->SetX(124);
$pdf->Multicell(39,5,'',0,'L');
$pdf->SetY(103);
$pdf->SetX(163);
$pdf->Multicell(35.3,5,'',0,'R');
$pdf->SetY(108);
$pdf->SetX(124);
$pdf->Multicell(39,5,'SALDO VENCIDO:',0,'L');
$pdf->SetY(108);
$pdf->SetX(163);
$pdf->Multicell(43.3,5,''.number_format($capx2+$intx2).'.00',0,'R');
$pdf->SetY(113);
$pdf->SetX(124);
$pdf->Multicell(39,5,'INTERESES VENCIDOS:',0,'L');
$pdf->SetY(113);
$pdf->SetX(163);
$pdf->Multicell(43.3,5,''.number_format($intvtot).'.00',0,'R');
$pdf->SetY(118);
$pdf->SetX(124);
$pdf->Multicell(39,5,'INTERESES MORATORIOS:',0,'L');
$pdf->SetY(118);
$pdf->SetX(163);
$pdf->Multicell(43.3,5,''.number_format($x2).'.00',0,'R');
$pdf->SetY(123);
$pdf->SetX(124);
$pdf->Multicell(39,5,'TOTAL VENCIDO:',0,'L');
$pdf->SetY(123);
$pdf->SetX(163);
$pdf->Multicell(43.3,5,''.number_format($capx2+$intx2+$x2+$intvtot).'.00',0,'R');
//fin del llenado del tercer cuadro
$pdf->Multicell(0,6,' ');
$pdf->Multicell(0,3,'MOVIMIENTOS EFECTUADOS:');
$pdf->Multicell(0,5,' ');
//cabeceras de tabla de pagos y abonos.
$pdf->SetFont('Arial','B',8);
$pdf->SetY(138);
$pdf->SetX(10);
$pdf->Multicell(7,4,'N/P',0,'C');
$pdf->SetY(138);
$pdf->SetX(17);
$pdf->Multicell(20,4,'FECHA',0,'C');
$pdf->SetY(138);
$pdf->SetX(37);
$pdf->Multicell(45,4,'DESCRIPCION',0,'C');
$pdf->SetY(138);
$pdf->SetX(82);
$pdf->Multicell(25,4,'CARGOS',0,'C');
$pdf->SetY(138);
$pdf->SetX(111);
$pdf->Multicell(29,4,'ABONOS',0,'C');
$pdf->SetY(138);
$pdf->SetX(140);
$pdf->Multicell(30,4,'PAGO',0,'C');
$pdf->SetY(138);
$pdf->SetX(170);
$pdf->Multicell(35.3,4,'SALDO DE CAPITAL',0	,'C');
$pdf->SetFont('Arial','',8);
//fin de cabeceras de tabls de pagos y abonos
$pdf->Multicell(0,1,'','B','L');
$pdf->Multicell(0,1,'','B','L');
//PRIMERA LINEA OTORGAMIENTO DE CREDITO
$pdf->SetY(145);
$pdf->SetX(17);
$pdf->Multicell(20,4,''.$dd.'/'.$mm1.'/'.$yy.'',0,'C');
$pdf->SetY(145);
$pdf->SetX(37);
$pdf->Multicell(45,4,'OTORGAMIENTO DEL CREDITO',0,'C');
$pdf->SetY(145);
$pdf->SetX(82);
$pdf->Multicell(25,4,''.number_format($MontoCreSolicM).'.00',0,'C');
$pdf->SetY(145);
$pdf->SetX(162);
$pdf->Multicell(36.3,4,''.number_format($MontoCreSolicM).'.00',0,'R');
//FIN PRIMERA LINEA OTORGAMIENTO DE CREDITO
$pdf->Multicell(0,0.5,' ');
$pdf->Tabla_3($header03);
$pdf->Multicell(0,-0.5,' ');
$pdf->Multicell(0,1,'','B','L');
$pdf->Multicell(0,1,'','B','L');
$pdf->Multicell(0,1,' ');
$pdf->SetFont('Arial','B',8);	
$pdf->SetX(107);
$pdf->Cell(25,5,''.number_format($TotAboCap).'.00','',0,'R');
$pdf->Cell(30,5,'','',0,'R');
$pdf->Cell(36.3,5,''.number_format($MontoCreSolicM-$TotAboCap).'.00','',0,'R');	
$pdf->SetFont('Arial','',8);


//fin llenado del primer cuadro
//$pdf->AddPage();

$pdf->Output();
?>