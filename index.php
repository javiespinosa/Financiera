<?php
	/* Connect To Database*/
require_once ("config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
require_once ("config/conexion.php");//Contiene funcion que conecta a la base de datos

	$active_categoria="active";
  $title="HISTORIAL DE CREDITOS";

//if(isset($body) || $body == true)
  $id = $_GET['id'];
  $query1=mysqli_query($con, "SELECT * FROM cliente where id=$id");
  $count=mysqli_num_rows($query1);

  $query=mysqli_query($con, "SELECT * FROM credito where cliente_id =$id");
    $count=mysqli_num_rows($query);
    

?>      
<!DOCTYPE html>
<html lang="en">
<head>
	<?php include("head.php");?>
  </head>
  <body>
 	<?php
	include("navbar.php");
	?> 
    <div class="container">
		     <div class="panel panel-success">
		     <div class="panel-heading">
         <div class="container-fluid">
         <div class="row padding">
         <div class="col-md-12">
         <H3>	<?php $h3 = "DATOS DEL CLIENTE ACREDITADO";  
            echo '<h3>'.$h3.'</h3>'
	?>     </H3>
             <div class="col-md-8">
             </div>
             </div>
             </div>
        	        <div class="table-responsive">
    <table class="table table-bordered table-striped">
<thead>
      <tr>
          <th>NOMBRE DEL CLIENTE</th>  
             <th>APELLIDOS</th>  
                <th>LOCALIDAD</th>    
                   <th>MUNICIPIO</th>    
      </tr>
</thead>
    <tbody>
       <?php 
             while ($user=$query1->fetch_assoc()) {   ?>
                    <tr class="<?php if($user['activo']==''){ echo 'active';}else{ echo 'danger';} ?>">
                        <td><?php echo $user['nombre']; ?></td>
                        <td><?php echo $user['apellidos']; ?></td>
                        <td><?php echo $user['localidad']; ?></td>
                        <td><?php echo $user['municipio']; ?></td>
            <?php } ?>
    </tbody>
    </table>	
						</div>		
              </div>
              <div class="container">
        	    <div class="col-md-19">
    	                 <?php $h1 = "REPORTE DE CREDITOS";  
                       echo '<h1>'.$h1.'</h1>'
		                  	?>
              <div class="col-md-8">
		               <td class='text-right'>				
						           <h4><li class="">
                       <a href="pdf_creditos.php?id=<?php echo $id;?>">
                       <i class='glyphicon glyphicon-tags'>
                       </i> GENERAR PDF</a></li><h4>
				            </td>
              </div>
              </div>
              </div>
              <div class="table-responsive">
                    <table class="table table-bordered table-striped">
              <thead>
                    <tr>
                       <H3><th>FOLIO</th></H3>  
                       <H3><th>LOCALIZACION</th></H3>
                       <H3><th>MONTO</th></H3>
                       <H3><th>F / CONTRATO</th></H3>
                       <H3><th>TIPO PAGO </th></H3>
                       <H3><th>ESTATUS</th></H3>
		                   <H3><th class='text-right'>EDO.CUENTA</th></H3>
                    </tr>
              </thead>
    <tbody>
                    <?php 
		              	while ($user=$query->fetch_assoc()) {  
                  ?>
                    <tr class="<?php if($user['activo']=='A'){ echo 'active';}else{ echo 'danger';} ?>">
                       <td><?php echo $user['folio']; ?></td>
                       <td><?php echo $user['localizacion_inversion'];?></td>
                       <td><?php echo $user['monto']; ?></td>
                       <td><?php echo $user['fecha_contrato'];?></td> 
                       <td><?php echo $user['tipo_pago'];?></td>
                       <td><?php echo $user['status_actual'];?></td>

              <?php 
    $tipo_c=$user['tipo_credito_id'];
    //echo $val;
{ 
    if ($tipo_c=='3') {
    $tipo_c; 
           echo "<td style='text-align: center;'>
           <a href=http://172.16.0.3/consis/edoRefaaa.php?folio=".$user['folio'].">
           <button type='button' class='btn btn-primary  btn-xs'>
           <span class='glyphicon glyphicon-ok' aria-hidden='true'>
           </span>VER EDO.CUENTA</button></a></td>";
          
          }
           elseif ($tipo_c=='1'){ 
            $tipo_c; 
            echo "<td style='text-align: center;'>
           <a href=http://172.16.0.3/consis/edoMicro.php?folio=".$user['folio'].">
           <button type='button' class='btn btn-primary  btn-xs'>
           <span class='glyphicon glyphicon-ok' aria-hidden='true'>
           </span>VER EDO.CUENTA</button></a></td>";

           }
           elseif ($tipo_c=='2'){ 
            $tipo_c; 
            echo "<td style='text-align: center;'>
           <a href=http://172.16.0.3/consis/edo_cuenta_avio.php?folio=".$user['folio'].">
           <button type='button' class='btn btn-primary  btn-xs'>
           <span class='glyphicon glyphicon-ok' aria-hidden='true'>
           </span>VER EDO.CUENTA</button></a></td>";
          }
          elseif ($tipo_c=='4'){ 
           $tipo_c; 
           echo "<td style='text-align: center;'>
          <a href=http://172.16.0.3/consis/edoPyme.php?folio=".$user['folio'].">
          <button type='button' class='btn btn-primary  btn-xs'>
          <span class='glyphicon glyphicon-ok' aria-hidden='true'>
          </span>VER EDO.CUENTA</button></a></td>";
         
          

          
          
       /* echo "<td style='text-align: center;'><a href=edoRefaaa.php?folio=".$user['folio']."><button type='button' class='btn-primary 
      </td>";*/
      /*
        }elseif ($val=="HA"){ 
           echo " <td class='text-right'>				
            <li class="">
            <a href="http://localhost/consis/edoRefaaa.php?folio=<?php echo $user['folio'];">
            <i class='glyphicon glyphicon-tags'></i>EDO CUENTA</a></li>
          </td>";
        }elseif ($val == "MI"){ 
            <td class='text-right'>				
            <li class="">
            <a href="http://localhost/consis/edoRefaaa.php?folio=<?php echo $user['folio'];">
            <i class='glyphicon glyphicon-tags'>
            </i>EDO CUENTA</a></li>
          </td>
        }elseif ($val=="PI"){
            <td class='text-right'>				
            <li class="">
            s<a href="http://localhost/public_html/edoRefaaa.php?folio=<?php echo $user['folio'];">
            <i class='glyphicon glyphicon-tags'></i>EDO CUENTA</a></li>
          </td>*/
        } else {
        }};
        ?> 
          <script src="js/bootstrap.min.js"></script>
	<script src="js/bootstrap-datepicker.js"></script>
         <?php } ?>
        </tbody>
    </table>
						</div>		
			</form>
</div>
				<div id="resultados"></div><!-- Carga los datos ajax -->
				<div class='outer_div'></div><!-- Carga los datos ajax -->
			</div>
		</div>
	</div>
	<hr>
	<?php
	include("footer.php");
	?>
  </body>
</html>
