<?php 
	/* Connect To Database*/
	require_once ("config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("config/conexion.php");//Contiene funcion que conecta a la base de datos
	
	$active_categoria="active";
    $title="credito";

    $query=mysqli_query($con, "SELECT * FROM tipo_credito where id =1");
    $count=mysqli_num_rows($query);
?>       
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>GENERAR REPORTES -</title>
<meta name="keywords" content="">
<meta name="description" content="">
<!-- Meta Mobil
================================================== -->
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
</head>
<body>
	<div class="container-fluid">
        <div class="row padding">
        	<div class="col-md-12">
            	<?php $h1 = "Reporte de pagos";  
            	 echo '<h1>'.$h1.'</h1>'
				?>
        <div class="col-md-12">
      
        <td class='text-right'>				
						<h4><li class=""><a href="stock.php?id=<?php echo $id;?>"><i class='glyphicon glyphicon-tags'></i> GENERAR PDF</a></li><h4>
					</td>
</div>
            </div>
        </div>
    	<div class="row">
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>FECHA DE PAGO</th>
            <th>MONTO CAPITAL</th>
            <th>MONTO DE INTERES</th>

          </tr>
        </thead>
        <tbody>
        <?php 
			while ($user=$query->fetch_assoc()) {   ?>
          <tr class="<?php if($user['activo']=='A'){ echo 'active';}else{ echo 'danger';} ?>">
            <td><?php echo $user['nombre']; ?></td>
             <td><?php echo $user['descripcion']; ?></td>
              <td><?php echo $user['tipo_pago']; ?></td>

         <?php } ?>
        </tbody>
      </table>
             
</body>
</html>