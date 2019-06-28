<?php include('server.php'); ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Sistema de registro</title>
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
		<h2>Registro</h2>
	</div>
	
	<form method="post" action="register.php">

		<?php include('errors.php'); ?>

		<div class="input-group">
			<label>Nombre de usuario</label>
			<input type="text" name="username" value="<?php echo $username; ?>">
		</div>
		<div class="input-group">
			<label>Correo electrónico</label>
			<input type="email" name="email" value="<?php echo $email; ?>">
		</div>
		<div class="input-group">
			<label>Contraseña</label>
			<input type="password" name="password_1">
		</div>
		<div class="input-group">
			<label>Confirmar contraseña</label>
			<input type="password" name="password_2">
		</div>
		<div class="input-group">
			<label>Codigo de registro</label>
			<input type="password" name="cod_registro">
		</div>
		<div class="input-group">
			<button type="submit" class="btn" name="reg_user">registro</button>
		</div>
		
		<p>
			¿Ya eres usuario? <a href="login.php">iniciar sesion</a>
		</p>
	</form>
</body>
</html>