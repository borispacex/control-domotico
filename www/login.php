<?php include('server.php') ?>
<!DOCTYPE html>
<html>
<head>
	<title>Iniciar sesion</title>
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
		<h2>iniciar sesión</h2>
	</div>
	
	<form method="post" action="login.php">

		<?php include('errors.php'); ?>

		<div class="input-group">
			<label>Nombre de usuario</label>
			<input type="text" name="username" >
		</div>
		<div class="input-group">
			<label>Contraseña</label>
			<input type="password" name="password">
		</div>
		<div class="input-group">
			<button type="submit" class="btn" name="login_user">Iniciar sesión</button>
		</div>
		<p>
			¿Todavía no eres miembro? <a href="register.php">Regístrate</a>
		</p>
	</form>


</body>
</html>