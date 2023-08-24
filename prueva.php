	<html> 
<head> 
   <title>PHP tutoriales</title> 
</head> 
<body> 
<h1><center><strong>PHP BUSCADOR CON SWITCH</strong></center></h1>

<?php 
$val=substr($folio, 0, 2);
  switch($_GET['folio']) { 
   case "RG":   			

        break;
   case "HA":   
   Header('Location:http://public_html/caja_refa/edoRefaaa.php');
        break;; 
   case "MI": 
   Header('Location:http://public_html/caja_refa/edoRefaaa.php');
   break;
   case "PI":   
   Header('Location:http://public_html/caja_refa/edoRefaaa.php');
   break;
      default:   
   } 
?>  
</body> 
</html> 


var $idioma = substr ($_SERVER["HTTP_ACCEPT_LANGUAGE"],0,2);

switch ($idioma) {
    case "de":
        Header('Location: http://deu.audioestudio.net');
        break;
    case "en":
        Header('Location: http://eng.audioestudio.net');
        break;
    case "fr":
        Header('Location: http://fra.audioestudio.net');
        break;
    case "es":
        Header('Location: http://esp.audioestudio.net'); 
        break;
    case "it":
        Header('Location: http://ita.audioestudio.net');
        break;
    case "ja":
        Header('Location: http://jap.audioestudio.net');
        break;
    default: 
	
   
    <?php 
    $val=substr($folio, 0, 2);
{ 
    if ($val=="RG") { 
        echo "<td class='text-right'>				
    <li class="">
        <a href="http://localhost/public_html/edoRefaaa.php?folio=<?php echo $user['folio'];">
        <i class='glyphicon glyphicon-tags'></i>EDO CUENTA</a></li>
      </td>";
        }elseif ($val=="HA"){ 
           echo " <td class='text-right'>				
            <li class="">
            <a href="http://localhost/public_html/edoRefaaa.php?folio=<?php echo $user['folio'];">
            <i class='glyphicon glyphicon-tags'></i>EDO CUENTA</a></li>
          </td>";
        }elseif ($val == "MI"){ 
            <td class='text-right'>				
            <li class="">
            <a href="http://localhost/public_html/edoRefaaa.php?folio=<?php echo $user['folio'];">
            <i class='glyphicon glyphicon-tags'>
            </i>EDO CUENTA</a></li>
          </td>
        }elseif ($val=="PI"){
            <td class='text-right'>				
            <li class="">
            s<a href="http://localhost/public_html/edoRefaaa.php?folio=<?php echo $user['folio'];">
            <i class='glyphicon glyphicon-tags'></i>EDO CUENTA</a></li>
          </td>
        } else {
        echo "Clave Incorrecta";
        }};
        ?> 

<?php
 $test=substr($folio, 0, 2);
 if ($test == "RG") {
    <td class='text-right'>				
    <li class=""><a href="http://localhost/public_html/edoRefaaa.php?folio=<?php echo $user['folio'];?>"><i class='glyphicon glyphicon-tags'></i>EDO CUENTA</a></li>
  </td>
   } 
 elseif ($test == "HA") { 
    <td class='text-right'>				
    <li class=""><a href="http://localhost/public_html/edoRefaaa.php?folio=<?php echo $user['folio'];?>"><i class='glyphicon glyphicon-tags'></i>EDO CUENTA</a></li>
  </td>
   }
 elseif ($test > 30) {
   echo "Sí, $test es mayor que 30.";
   }
 else {
   echo "No, $test es menor que 40, 35 y 30.";
   }
 ?>

    <?
if ($folio=="RG") { 
include ("Location:http://public_html/caja_refa/edoRefaaa.php");
}elseif ($folio=="HA"){ 
include ("cliente2.html"); 
}elseif ($folio == "MI"){ 
Include ("cliente3.html");
}elseif ($folio=="PI"){
include ("cliente2.html"); 
} else {
echo "Clave Incorrecta";
};
?>


<?php
switch($folio) {
case "RG":
include_once("cliente1.html")
break;
case "HA":
include_once("cliente2.html")
break;
case "MI":
include_once("cliente3.html")
break;
case "PI":
include_once("cliente3.html")
break;
default:
echo "Clave incorrecta";
break;
}
?>