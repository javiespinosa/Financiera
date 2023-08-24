<?php
$active_categoria="active";
$title="HISTORIAL DE CREDITOS";


// Queremos hacer en pdf la factura numero 1 de la tipica BBDD de facturacion
require('./fpdf/fpdf.php');
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',11);

require_once ("config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
require_once ("config/conexion.php");//Contiene funcion que conecta a la base de datos
//Imagen izquierda
$pdf->Image('logo3.png', 30, 10, 17, 25, 'PNG');
$pdf->Image('logo2.png',  50, 10, 130, 25, 'PNG');

$id = $_GET['id'];


                   $orden1="SELECT  nombre, apellidos, localidad,municipio, rfc 
                            FROM cliente 
                                WHERE id = $id";
                   $paquete1=mysqli_query($con, $orden1);
                   $reg1=mysqli_fetch_array($paquete1);



                    $query=mysqli_query($con, "SELECT * 
                                               FROM tipo_credito 
                                                     where id =2");
                    $count=mysqli_num_rows($query);
                    while($count=mysqli_fetch_array($query))
            {
                    $pdf->SetXY(80, 86);
                    $pdf->SetFillColor(255,0,0);
                    $pdf->SetTextColor(0,0,0);
                    $pdf->SetFont('Arial','',7);
                    $pdf->SetX(35);
                    $pdf->Cell(45,8,$count['nombre'],1,0,"L");
                    $pdf->SetFont('Arial','',7);
                    $pdf->SetX(80);
                    $pdf->Cell(120,8,$count['descripcion'],1,0,"L");
            } 
    $orden2="SELECT * 
                FROM credito 
                           where cliente_id =$id";
    $paquete2=mysqli_query($con,$orden2);


$pdf->SetXY(200, 28);
$pdf->Cell(-180,10,"CONSULTORES ASOCIADOS EN PRODUCCION",0,0,'C'); 
$pdf->SetXY(200, 31);
$pdf->Cell(-180,10,"PECUARIA S.A. DE .C.V. SOFOM E.N.R.",0,0,'C'); 
$pdf->SetXY(114, 45);


    // 3º Una tabla con los articulos comprados
    $orden1="CLIENTE: ".$reg1[0]."\t ".$reg1[1]."\n LOCALIDAD: ".$reg1[2]."\n MUNICIPIO: ".$reg1[3]."\n RFC: ".$reg1[4];
    $pdf->SetXY(9, 53);
    $pdf->MultiCell(100,6,$orden1,0,"L");
    $pdf->SetXY(8.9, 70);
    $pdf->SetFont('Arial','',11);
    $pdf->Cell(30,-39 ,"DATOS DEL CLIENTE:",0,'C'); 


// La cabecera de la tabla (en azulito sobre fondo rojo)

$pdf->SetFont('Arial','B',10);
$pdf->SetXY(81, 126);
$pdf->Cell(-82,12,"HISTORIAL DE CREDITOS ACTIVOS",0,'C','C');

$pdf->SetFont('Arial','B',8);
$pdf->SetY(135);
$pdf->SetX(10);
$pdf->Multicell(39,4,'FOLIO',1,'C');
$pdf->SetY(135);
$pdf->SetX(49);
$pdf->Multicell(30,4,'MONTO',1,'C');
$pdf->SetY(135);
$pdf->SetX(79);
$pdf->Multicell(37,4,'FECHA',1,'C');
$pdf->SetY(135);
$pdf->SetX(116);
$pdf->Multicell(30,4,'PAGO',1,'C');
$pdf->SetY(135);
$pdf->SetX(146);
$pdf->Multicell(55,4,'ESTATUS ACTUAL',1,'C');

// Los datos (en negro)
$pdf->SetTextColor(0,0,0);

while($reg2=mysqli_fetch_array($paquete2))
{
       $pdf->SetX(10);
       $pdf->Cell(39,10,$reg2['folio'],1,0,"L");
       $pdf->Cell(30,10,$reg2['monto'],1,0,"C");
       $pdf->Cell(37,10,$reg2['fecha_contrato'],1,0,"C");
       $pdf->Cell(30,10,$reg2['tipo_pago'],1,0,"C");
       $pdf->Cell(55,10,$reg2['status_actual'],1,1,"C");

}
//fin del llenado del tercer cuadro

$pdf->SetFont('Arial','B',8);
$pdf->SetY(80);
$pdf->SetX(10);
$pdf->Multicell(25,4,'FOLIO',1,'C');
$pdf->SetY(80);
$pdf->SetX(35);
$pdf->Multicell(45,4,'NOMBRE',1,'C');
$pdf->SetY(80);
$pdf->SetX(80);
$pdf->Multicell(120,4,'DESCRIPCION',1,'C');


$pdf->SetFont('Arial','',8);
//fin de cabeceras de tabls de pagos y abonos
$pdf->Multicell(0,1,'','B','L');
$pdf->Multicell(0,1,'','B','L');



$pdf->SetFont('Arial','B',15);
$pdf->Multicell(0,1,'   ');
$pdf->Multicell(0,-92,'HISTORIAL DE CREDITOS DEL CLIENTE','C','C');


	//Posición: a 1,5 cm del final
    $pdf->SetY(-33);
    //$this->Tabla_5($header03);
    $pdf->Ln(2);
    $pdf->Cell(190.3,1,'','B');
    $pdf->Ln(2);
    $pdf->SetFont('Arial','',7);
    $pdf->Multicell(0,4,'Oficina 8a Oriente Sur No. 125, Tuxtla Gutierrez, Chiapas, CP 29000, Col. Centro','C','C');
    $pdf->Multicell(0,4,'Tel/Fax: 961 61 24882 Email: cappsc@hotmail.com','C','C');



            $pdf->SetFillColor(1000,80,10);
			$pdf->SetTextColor(255);
			$pdf->SetDrawColor(0,0,0);
			$pdf->SetLineWidth(.3);
			$pdf->SetFont('Arial','B',7);
			//Cabecera
	
			//Restauración de colores y fuentes
			$pdf->SetFillColor(255,255,255);
			$pdf->SetTextColor(0);
			$pdf->SetFont('');
			//Datos
            $fill=false;
            $pdf->SetY(255);
            $pdf->SetX(10);
			$pdf->Cell(65,4,'ING. FILADELFO MACIAS HERNANDEZ','T',0,'C',$fill);
			$pdf->Cell(5,4,'',0,0,'C',$fill);
			$pdf->Cell(56,4,'','',0,'C',$fill);
			$pdf->Cell(5,4,'',0,0,'C',$fill);
			$pdf->Cell(65,4,utf8_decode('C.P. GABRIEL LEON CAÑAVERAL'),'T',0,'C',$fill);
			$pdf->Ln();
			$pdf->Cell(65,3,'GERENTE GENERAL',0,0,'C',$fill);
			$pdf->Cell(5,3,'',0,0,'C',$fill);
			$pdf->Cell(56,3,'',0,0,'C',$fill);
			$pdf->Cell(5,3,'',0,0,'C',$fill);
			$pdf->Cell(65,3,'CONTADOR GENERAL',0,0,'C',$fill);
			$pdf->Ln();
			$pdf->Cell(65,3,'',0,0,'C',$fill);
			$pdf->Cell(5,3,'',0,0,'C',$fill);
			$pdf->Cell(56,3,'',0,0,'C',$fill);
			$pdf->Cell(5,3,'',0,0,'C',$fill);
			$pdf->Cell(65,3,'CEDULA P.: 7661293',0,0,'C',$fill);
// El documento enviado al navegador
$pdf->Output();
?>