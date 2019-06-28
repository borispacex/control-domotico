<?php
	session_start();

	// declaración de variable
	$username = "";
	$email    = "";
	$errors = array();
	$_SESSION['success'] = "";

	// conectarse a la base de datos ('localhost','root','','control_domotico')
	$db = mysqli_connect('db', 'user', 'test', 'myDb');

	// REGISTRO DE USUARIO
	if (isset($_POST['reg_user'])) {
		// recibir todos los valores de entrada del formulario
		$username = mysqli_real_escape_string($db, $_POST['username']);
		$email = mysqli_real_escape_string($db, $_POST['email']);
		$password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
		$password_2 = mysqli_real_escape_string($db, $_POST['password_2']);
		$cod_registro = mysqli_real_escape_string($db, $_POST['cod_registro']);

		// validación de formularios: asegúrese de que el formulario esté correctamente rellenado
		if (empty($username)) {
			array_push($errors, "Se requiere nombre de usuario");
		}
		// verificar si el usuario existe
		$cola1 = "SELECT * FROM users WHERE username='$username'";
		$result1 = mysqli_query($db, $cola1);
		if (mysqli_num_rows($result1) == 1) {
			array_push($errors, "El nombre de usuario ya existe");
		}

		if (empty($email)) {
			array_push($errors, "Se requiere correo electronico");
		}
		// verificar si el correo existe
		$cola2 = "SELECT * FROM users WHERE email='$email'";
		$result2 = mysqli_query($db, $cola2);
		if (mysqli_num_rows($result2) == 1) {
			array_push($errors, "El correo electronico ya existe");
		}

		if (empty($password_1)) {
			array_push($errors, "Se requiere contraseña");
		}

		if ($password_1 != $password_2) {
			array_push($errors, "Las dos contraseñas no coinciden");
		}

		if(empty($cod_registro)){
			array_push($errors, "Se requiere codigo de registro");
		}else{
			$pila = "SELECT * FROM codes WHERE cod_reg='$cod_registro'";
			$result = mysqli_query($db, $pila);
			if (mysqli_num_rows($result) != 1) {
				array_push($errors, "El codigo de registro es ERRONEO");
			}
			/*
			if(strcmp("12345", $cod_registro)!=0){
				array_push($errors, "El codigo de registro es ERRONEO");
			}
			*/
		}


		// registrar usuario si no hay errores en el formulario
		if (count($errors) == 0) {
			$password = md5($password_1); //cifra la contraseña antes de guardarla en la base de datos
			$query = "INSERT INTO users (username, email, password)
					  VALUES('$username', '$email', '$password')";
			mysqli_query($db, $query);

			$_SESSION['username'] = $username;
			$_SESSION['success'] = "Ahora está conectado";
			header('location: index.php'); // redirigir a la página de inicio
		}

	}

	// REGISTRO DE USUARIO EN LA PÁGINA DE INICIO
	if (isset($_POST['login_user'])) {
		$username = mysqli_real_escape_string($db, $_POST['username']);
		$password = mysqli_real_escape_string($db, $_POST['password']);

		// asegurar que los campos de formulario se llenen correctamente
		if (empty($username)) {
			array_push($errors, "Se requiere nombre de usuario");
		}
		if (empty($password)) {
			array_push($errors, "Se requiere contraseña");
		}

		if (count($errors) == 0) {
			$password = md5($password);
			$query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
			$results = mysqli_query($db, $query);

			if (mysqli_num_rows($results) == 1) {
				// usuario de registro en
				$_SESSION['username'] = $username;
				$_SESSION['success'] = "Ahora está conectado";
				header('location: index.php'); // redirigir a la página de inicio
			}else {
				array_push($errors, "Combinación incorrecta de nombre de usuario / contraseña");
			}
		}
	}
?>