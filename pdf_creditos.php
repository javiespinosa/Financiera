<?php
$active_categoria="active";
$title="HISTORIAL DE CREDITOS";

require('./fpdf/fpdf.php');
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',11);

require_once ("config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
require_once ("config/conexion.php");//Contiene funcion que conecta a la base de datos
//Imagen izquierda
$pdf->Image('LOGOCAPP.png', 10, 10, 30, 25, 'PNG');
$pdf->Image('logo2.png',  50, 10, 130, 25, 'PNG');

$pdf->SetFont('Arial','B',15);
$pdf->Multicell(0,1,'   ');
$pdf->Multicell(0,60,'HISTORIAL DE CREDITOS DEL CLIENTE','C','C');

$id = $_GET['id'];
                   $orden1="SELECT  nombre, apellidos, localidad,municipio, rfc 
                            FROM cliente 
                                WHERE id = $id";
                   $paquete1=mysqli_query($con, $orden1);
                   $reg1=mysqli_fetch_array($paquete1);
$pdf->SetFont('Arial','B',11);
$pdf->SetY(90);
$pdf->SetX(10);
$pdf->Multicell(30,4,'FOLIO',1,'C');
$pdf->SetY(90);
$pdf->SetX(40);
$pdf->Multicell(160,4,'DESCRIPCION',1,'C');
                    $query="SELECT folio, descripcion FROM credito credito 
                            INNER JOIN tipo_credito  tipo_credito 
                                on credito.tipo_credito_id = tipo_credito.id 
                                   AND cliente_id=$id";
                    $count=mysqli_query($con,$query);
                    while($reg01=mysqli_fetch_array($count))
                    {
                        $pdf->SetFont('Arial','B',10);

                           $pdf->Cell(30,10,$reg01['folio'],1,0,"L");
                           $pdf->SetFont('Arial','B',6.1);
                           $pdf->Cell(160,10,$reg01['descripcion'],1,1,"C");
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

$pdf->SetFont('Arial','B',8);
    // 3º Una tabla con los articulos comprados
    $orden1="CLIENTE: ".$reg1[0]."\t ".$reg1[1]."\n LOCALIDAD: ".$reg1[2]."\n MUNICIPIO: ".$reg1[3]."\n RFC: ".$reg1[4];
    $pdf->SetXY(9, 53);
    $pdf->MultiCell(100,6,$orden1,0,"L");
    $pdf->SetXY(8.9, 70);
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(30,-39 ,"DATOS DEL CLIENTE:",0,'C'); 
// La cabecera de la tabla (en azulito sobre fondo rojo)

$pdf->SetFont('Arial','B',12);
$pdf->SetXY(86, 150);
$pdf->Cell(-80,12,"HISTORIAL DE CREDITOS ACTIVOS",0,'C','C');

$pdf->SetFont('Arial','B',10);
$pdf->SetY(160);
$pdf->SetX(10);
$pdf->Multicell(39,4,'FOLIO',1,'C');
$pdf->SetY(160);
$pdf->SetX(49);
$pdf->Multicell(30,4,'MONTO',1,'C');
$pdf->SetY(160);
$pdf->SetX(79);
$pdf->Multicell(37,4,'FECHA',1,'C');
$pdf->SetY(160);
$pdf->SetX(116);
$pdf->Multicell(30,4,'PAGO',1,'C');
$pdf->SetY(160);
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

$pdf->SetFont('Arial','',8);
//fin de cabeceras de tabls de pagos y abonos
$pdf->Multicell(0,1,'','B','L');
$pdf->Multicell(0,1,'','B','L');

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