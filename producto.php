<html>
	<head>
		<title>Boton Condicional</title>
	</head>
	<script type="text/javascript">
		function enviar(){
      
			var texto = document.getElementById("txtBox").value;
			if (texto == "google"){
				window.location = "http://www.google.com/"
			} else if (texto == "facebook"){
				window.location = "http://www.facebook.com/"
			} else {
				window.location = "http://www.lawebdelprogramador.com/foros/PHP/1520519-caja-de-texto-condicional.html"
			}
		}
	</script>
	<body>
		<input type="text" id="txtBox" name="txtBox"/>
		<button onclick="enviar()">Enviar</button>
	</body>
</html>