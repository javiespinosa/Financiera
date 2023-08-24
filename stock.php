<?php
function TablaColores($header)
{
//Colores, ancho de línea y fuente en negrita
$this->SetFillColor(255,0,0);
$this->SetTextColor(255);
$this->SetDrawColor(128,0,0);
$this->SetLineWidth(.3);
$this->SetFont('','B');
//Cabecera

for($i=0;$i<count($header);$i++)
$this->Cell(40,7,$header[$i],1,0,'C',1);
$this->Ln();
//Restauración de colores y fuentes
$this->SetFillColor(224,235,255);
$this->SetTextColor(0);
$this->SetFont('');
//Datos
$fill=false;

$this->Cell(40,6,"hola",'LR',0,'L',$fill);
$this->Cell(40,6,"hola2",'LR',0,'L',$fill);
$this->Cell(40,6,"hola3",'LR',0,'R',$fill);
$this->Cell(40,6,"hola4",'LR',0,'R',$fill);
$this->Ln();
$fill=true;
$this->Cell(40,6,"col",'LR',0,'L',$fill);
$this->Cell(40,6,"col2",'LR',0,'L',$fill);
$this->Cell(40,6,"col3",'LR',0,'R',$fill);
$this->Cell(40,6,"col4",'LR',0,'R',$fill);
$fill=!$fill;
$this->Ln();
$this->Cell(160,0,'','T');
}
?> 


$query="SELECT * from pago where credito_id = 23";
$result0312=mysqli_query($con,$query);
$I=0;

while ($row0312=mysqli_fetch_array($result0312,MYSQLI_ASSOC)) {
	$I++;
$fecha_pago=$row0312['fecha_pago'];	
$monto_capital=$row0312['monto_capital'];
$monto_intereses=$row0312['monto_intereses'];

$fill=false;
$this->Cell(8,-200,''.$I.'','C',0,'C',$fill);
$this->Cell(20,-200,''.$fecha_pago.'','C',0,'C',$fill);
$this->Cell(19,-200,''.$monto_capital.'','C',0,'C',$fill);
$this->Cell(25,-200,''.$monto_intereses.'','C',0,'C',$fill);
$this->Cell(2,4,'');
$this->Ln();
$I+1;

}



$query041="SELECT (365 - X.dias) as dias 
	FROM (select TO_DAYS(fecha_pago) - TO_DAYS(fecha_pago_m)as dias from pago
	   WHERE credito_id=100 ) X";
	$result041 = mysqli_query($con,$query041);
	while($row041 = mysqli_fetch_array($result041,MYSQLI_ASSOC)) 
	{
	$dias=$row041['dias'];

	$fill=false;
	$this->Cell(250,-250,''.$dias.'','C',0,'C',$fill);
	$this->Cell(2,4,'C');
	$this->Ln();
	}

 