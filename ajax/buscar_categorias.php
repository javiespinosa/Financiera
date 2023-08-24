<?php

	/*-------------------------
	
	---------------------------*/
	include('is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado
	/* Connect To Database*/
	require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
	
	$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
	if (isset($_GET['id'])){
		$id=intval($_GET['id']);
		$query=mysqli_query($con, "select *, (select IF( count(*) >0, 'Tiene Creditos', 'No tiene Creditos')  from credito where cliente_id=c.id) as Credito0 from cliente C ");
	
		$count=mysqli_num_rows($query);
		if ($count==0){
			if ($delete1=mysqli_query($con,"DELETE FROM cliente WHERE id='".$id."'")){
			?>
			<div class="alert alert-success alert-dismissible" role="alert">
			  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			  <strong>Aviso!</strong> Datos eliminados exitosamente.
			</div>
			<?php 
		}else {
			?>
			<div class="alert alert-danger alert-dismissible" role="alert">
			  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			  <strong>Error!</strong> Lo siento algo ha salido mal intenta nuevamente.
			</div>
			<?php
			
		}		
		} 	
	}
	if($action == 'ajax'){
		// escaping, additionally removing everything that could be (html/javascript-) code
         $q = mysqli_real_escape_string($con,(strip_tags($_REQUEST['q'], ENT_QUOTES)));
		 $aColumns = array('nombre','apellidos');//Columnas de busqueda
		 $sTable = "cliente c";
		 $sWhere = "";
		if ( $_GET['q'] != "" )
		{
			$sWhere = "WHERE (";
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{
				$sWhere .= $aColumns[$i]." LIKE '%".$q."%' OR ";
			}
			$sWhere = substr_replace( $sWhere, "", -3 );
			$sWhere .= ')';
		}
		$sWhere.="order by nombre";
		include 'pagination.php'; //include pagination file
		//pagination variables
		$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
		$per_page = 9; //how much records you want to show
		$adjacents  = 4; //gap between pages after number of adjacents
		$offset = ($page - 1) * $per_page;
		//Count the total number of row in your table*/
		$count_query   = mysqli_query($con, "SELECT count(*) AS numrows FROM $sTable  $sWhere");
		$row= mysqli_fetch_array($count_query);
		$numrows = $row['numrows'];
		$total_pages = ceil($numrows/$per_page);
		$reload = './clientes.php';
		//main query to fetch the data
		//$sql="SELECT * , (select IF( count(*) >0, 'Tiene Creditos', 'No tiene Creditos')  from credito where cliente_id=c.id) Credito0,(select count(*) from credito where cliente_id=c.id) as cuentaCredito0  FROM  $sTable $sWhere LIMIT $offset,$per_page";
		$sql="SELECT * , (select concat('TIENE ', count(*),' CREDITOS')  from credito where cliente_id=c.id) Credito0,(select count(*) from credito where cliente_id=c.id) as cuentaCredito0  FROM  $sTable $sWhere LIMIT $offset,$per_page";
	    $query = mysqli_query($con, $sql);
		
		//loop through fetched data
		if ($numrows>0){
			
			?>
			<div class="table-responsive">
			  <table class="table">
				<tr  class="success">
					<th>NOMBRE</th>
					<th>APELLIDOS</th>
					<th>RFC</th>
					<th>FECHA REGISTRO</th>
					<th>NUMERO DE CREDITO</th>
					<th class='text-right'>VER HISTORIAL CLIENTE</th>
					
					
				</tr>
				<?php
				while ($row=mysqli_fetch_array($query)){
						$id=$row['id'];
						$nombre=$row['nombre'];
						$apellidos=$row['apellidos'];
						$rfc=$row['rfc'];
						$modificado=$row['modificado'];	
						$Credito0=$row['Credito0'];	
										
					?>
					<tr>					
						<td><?php echo $nombre; ?></td>
						<td><?php echo $apellidos; ?></td>
						<td><?php echo $rfc; ?></td>
						<td><?php echo $modificado;?></td>	
						<td><?php echo $Credito0;?></td>
									
					
					<td style='text-align: center;'>
					<a href="index.php?id=<?php echo $id;?>">
					<button type='button' class='btn btn-primary  btn-xs'>
					<span class='glyphicon glyphicon-ok' aria-hidden='true'>
					</span> HISTORIAL DE CREDITOS</button></a></td>
						
					</tr>
					<?php
				}
				?>
				<tr>
					<td colspan=4><span class="pull-right">
					<?php
					 echo paginate($reload, $page, $total_pages, $adjacents);
					?></span></td>
				</tr>
			  </table>
			</div>
			<?php
		}
	}
?>