<?php 
session_start(); 

if (!isset($_SESSION['username'])) {
	$_SESSION['msg'] = "Debes iniciar sesión primero";
	header('location: login.php');
}

if (isset($_GET['logout'])) {
	session_destroy();
	unset($_SESSION['username']);
	header("location: login.php");
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Control Domotico</title>
	<script src='mqttws31.js' type='text/javascript'></script> 
	<!-- https://api.cloudmqtt.com/js/mqttws31.js -->
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div class="contenido">
		<ul class="backgroundslider">
			<li></li>
			<li></li>
			<li></li>
		</ul>
	</div>
	
	<div class="header">
		<h2>Bienvenido</h2>
	</div>
	<div class="content">
		<!-- notification message -->
		<?php if (isset($_SESSION['success'])) : ?>
			<div class="error success" >
				<h3>
					<?php 
					echo $_SESSION['success']; 
					unset($_SESSION['success']);
					?>
				</h3>
			</div>
		<?php endif ?>

		<!-- logged in user information -->
		<?php  if (isset($_SESSION['username'])) : ?>
			<p>Bienvenido <strong><?php echo $_SESSION['username']; ?></strong></p>
			
				<h2 align="center">CONTROL DOMOTICO</h2>
				<h3 align="center">DEL LABORATIO DE COMPUTACION</h3>
			<br>
			<h4>Mostrar:</h4>
			<div>
				<a>Temperatura: </a>
				<a id ="temperatura">-</a>
			</div>
			<div>
				<a>Estado del LED: </a>
				<a id ="salidaDigital">-</a>
			</div>
			<div>
				<a>Estado de la puerta: </a>
				<a id ="salidaDigital2">-</a>
			</div>
			<div>
				<a>Estado de la Alarma: </a>
				<a id ="salidaDigital3">-</a>
			</div>
			<div>
				<a>Brillo del LED: </a>
				<a id ="salidaAnalogica">-</a>
			</div>
			 <br>
			<h4>Publicaciones:</h4>
			<div>
				<a>Encender o Apagar LED: </a>
				<button type='button' onclick='OnOff("ENCENDER")'>ENCENDER</button>
				<button type='button' onclick='OnOff("APAGAR")'>APAGAR</button>
			</div>
			<div>
				<a>Brillo del LED: </a>
				<input type="range" id="myRange" min="0" max="1023"  onmouseup="enviarSalidaAnalogica()">
			</div>
			<div>
				<a>Abrir o Cerrar Puerta: </a>
				<button type='button' onclick='OnOff("ABRIR")'>ABRIR</button>
				<button type='button' onclick='OnOff("CERRAR")'>CERRAR</button>
			</div>
			<div>
				<a>Activar o Desactivar Alarma: </a>
				<button type='button' onclick='OnOff("ACTIVAR")'>ACTIVAR</button>
				<button type='button' onclick='OnOff("PARAR")'>DESACTIVAR</button>
			</div>
			<script>      
				usuario = 'control_domotico';
				contrasena = '123456';

				function OnOff(dato){
					message = new Paho.MQTT.Message(dato);
					message.destinationName = '/' + usuario + '/salidaDigital'
					client.send(message);
				};

				function enviarSalidaAnalogica(){
					var dato = document.getElementById("myRange").value;
					message = new Paho.MQTT.Message(dato);
					message.destinationName = '/' + usuario + '/salidaAnalogica'
					client.send(message);
				};
				
      // called when the client connects
      function onConnect() {
        // Once a connection has been made, make a subscription and send a message.
        console.log("onConnect");
        client.subscribe("#");
    }
    
      // called when the client loses its connection
      function onConnectionLost(responseObject) {
      	if (responseObject.errorCode !== 0) {
      		console.log("onConnectionLost:", responseObject.errorMessage);
      		setTimeout(function() { client.connect() }, 5000);
      	}
      }
      
      // called when a message arrives
      function onMessageArrived(message) {
        if (message.destinationName == '/' + usuario + '/' + 'temperatura') { //acá coloco el topic
        	document.getElementById("temperatura").textContent = message.payloadString  + " ºC";
        }
        if (message.destinationName == '/' + usuario + '/' + 'pulsador') { //acá coloco el topic
        	document.getElementById("salidaDigital").textContent = message.payloadString;
        }
        if (message.destinationName == '/' + usuario + '/' + 'salidaDigital') { //acá coloco el topic
        	if(message.payloadString == 'ENCENDER'){
        		document.getElementById("salidaDigital").textContent = 'ENCENDIDO';
        	}
        	if(message.payloadString == 'APAGAR'){
        		document.getElementById("salidaDigital").textContent = 'APAGADO';
        	}
        	if(message.payloadString == 'ABRIR'){
        		document.getElementById("salidaDigital2").textContent = 'ABIERTO';
        	}
        	if(message.payloadString == 'CERRAR'){
        		document.getElementById("salidaDigital2").textContent = 'CERRADO';
        	}
        	if(message.payloadString == 'ACTIVAR'){
        		document.getElementById("salidaDigital3").textContent = 'ACTIVADA';
        	}
        	if(message.payloadString == 'PARAR'){
        		document.getElementById("salidaDigital3").textContent = 'DESACTIVADA';
        	}
        }

        if (message.destinationName == '/' + usuario + '/' + 'salidaAnalogica') { //acá coloco el topic
        	document.getElementById("salidaAnalogica").textContent = message.payloadString;
        }
    }

    function onFailure(invocationContext, errorCode, errorMessage) {
    	var errDiv = document.getElementById("error");
    	errDiv.textContent = "Could not connect to WebSocket server, most likely you're behind a firewall that doesn't allow outgoing connections to port 39627";
    	errDiv.style.display = "block";
    }
    
    var clientId = "ws" + Math.random();
        // Create a client instance
        var client = new Paho.MQTT.Client("m14.cloudmqtt.com", 39423, clientId);
        
        // set callback handlers
        client.onConnectionLost = onConnectionLost;
        client.onMessageArrived = onMessageArrived;
        
        // connect the client
        client.connect({
        	useSSL: true,
        	userName: usuario,
        	password: contrasena,
        	onSuccess: onConnect,
        	onFailure: onFailure
        });        
    </script>
    

    <p> <a href="index.php?logout='1'" style="color: red;">cerrar sesión</a> </p>
<?php endif ?>
</div>

</body>
</html>