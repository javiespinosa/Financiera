<html>
    	<body>


		<tr>
                  <td>Fecha Ministración:</td>
                  <td>
                     <input type="date" class="datepicker" id="fecha" name="dato1" required>
                  </td>
                </tr>
    		<form method ="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
				
			<tr>
                    <td>monto:</td>
                    <td>
                      <div class="input-field">
                        <i class="material-icons prefix"></i>
                        <input id="co" name="co" type="text" pattern="[0-9]+" class="validate" required>
                        <label for="icon_prefix">Ingrese el monto en pesos</label>
                      </div>
                    </td>
                  </tr>
				  <tr>
                  <td>Tasa Interés:</td>
                  <td>
                    <div class="input-field">
                      <!--<i class="material-icons prefix">attach_money</i>-->
                      <input id="i" name="i" type="text"  required>
                      <label for="icon_prefix">Ingrese la tasa de interes</label>
                    </div>
                  </td>
                </tr>
				<tr>
                  <td>Plazo:</td>
                  <td>
                    <div class="input-field">
                      <!--<i class="material-icons prefix">attach_money</i>-->
                      <input id="n" name="n" type="text" pattern="[0-9]+" class="validate" required>
                      <label for="icon_prefix">Plazo en años</label>
                    </div>
                  </td>
                </tr>
			
				<tr>
                  <td>Modalidad de Pago:</td>
                  <td>
                    <div class="input-field col s12">
                      <!--<i class="material-icons prefix">attach_money</i>-->
                      <select id="tipo" name="tipo" required>
                        <option value="24">Quincenal</option>
                        <option value="12">Mensual</option>
                        <option value="6">Bimestral</option>
                        <option value="4">Trimestral</option>
                        <option value="2">Semestral</option>
                        <option value="1">Anual</option>
                      </select>
                    </div>
                  </td>
                </tr>


       
    			<input type="submit" name= "calcular" value="Calcular" />
			</form>
			</tr>
			<input value = "GENERAR PDF" class = "boton" type = "button" onclick = "javascript:window.location='simulador04.php'">

    		<table border = "1">
    			<?php
    				if (isset ($_POST["calcular"]))
    				{
    					//Volcado de variables desde form
    					$co = $_POST["co"];
    					$i = $_POST["i"];
    					$n = $_POST["n"];
    					$a = $_POST["tipo"];
     
    					//Variables de cálculo
              $lm = $n*$a;
              $si= $co / ($n*$a);
              $iva= ($i*16)/100;
    					$op1 = ($i/$a)/100;
    					$op2 = pow((1+$op1),$lm);
    					$op3 = $op2-1;
    					$cn = $co*($op1*$op2)/$op3;
     
    					//echo $cn;
     
    					//Iniciando acumuladores
    					$tar = 0;
    					$cpr = $co;
              echo "<tr><td>Tiempo</td>

              <td>Inter&eacute;s</td>
              <td>Amortizaci&oacute;n</td>
			  <td>Iva</td>
			  <td>Cpt. Pend.</td>
			  <td>TOTAL A PAGAR.</td></tr>";
    					echo "<tr><td>0</td><td>0</td><td>0</td><td>0</td>";
    					echo "<td>$cpr</td></tr>";
     
    					for ($tr=1;$tr<=$lm;$tr++)
    					{
    						$pr = round($si,2);
          //interes
                            $ir = round($cpr*$op1,2);
    						$ar = round($pr,2);
    						$tar = round(($ir*16)/100,2);
    						$cpo = round($cpr,2);
							$cpr = $cpr - $ar;
							$SALDO = ($pr + $ir + $tar);
     
    						echo "<tr>";
    						echo "<td>$tr</td>";
    					//	echo "<td>$pr</td>";
    						echo "<td>$ir</td>";
    						echo "<td>$ar</td>";
    						echo "<td>$tar</td>";
							echo "<td>$cpr</td>";
							echo "<td>$SALDO</td>";
    						echo "</tr>";
    					}
    				}
    			?>
    		</table>
    	</body>
    </html>