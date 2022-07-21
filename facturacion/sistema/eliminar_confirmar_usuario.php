<?php

	session_start();
	// Impide eliminar usuarios si no se tiene el rol de administrador 
	if($_SESSION['rol'] != 1){
		header("location: ./");
	} 
	include "../conexion.php";

	//Si se da click en el boton aceptar mediante el metodo post
	if(!empty($_POST)){ 

		//Condicional de seguridad para proteger al usuario principal
		if($_POST['idusuario'] ==1 ){
			header('location: lista_usuarios.php');
			mysqli_close($conection);
			exit;
		}

		$idusuario = $_POST['idusuario'];

		//Query para eliminar definitivamente 
		// $query_delete = mysqli_query($conection, "delete from usuario where idusuario = $idusuario");
		
		//Estatus para cambiar el estatus de un cliente a 0 
		$query_delete =mysqli_query($conection, "update usuario set estatus = 0 where idusuario = $idusuario");
		mysqli_close($conection);

		if($query_delete){
			header('location: lista_usuarios.php'); 
		}else{
			echo "Error al eliminar el usuario";
		}
		 
	}

	// Si no encuentra un id registrado regresa directamente a la lista de usuarios	
	// Si el id es el del usuario principal tambien regresa a la lisita de usuario 
	if(empty($_REQUEST['id']) || $_REQUEST['id']==1) 
	{   
		header('location: lista_usuarios.php'); 
		mysqli_close($conection);
		 
	}else{
		  
		$idusuario = $_REQUEST['id'];

		//Retorna los campos del id seleccionado de la lista de usuario al precionar "Eliminar "
		$query = mysqli_query($conection, "select u.nombre, u.usuario, r.rol 
										from usuario u  
										inner join rol r 
										on u.rol = r.idrol where u.idusuario = $idusuario");
										
		mysqli_close($conection);

		//Obtiene el numero de cadenas seleccionadas
		$result = mysqli_num_rows($query);

		//Si no encuentra resultado regresa a la lista de usuarios
		if($result > 0 ){
			//Obtencion de los datos guardados en la tabla para plasmarlos en los input lineas (?)
			while ($data = mysqli_fetch_array($query)){
			
				$nombre = $data['nombre'];
           		$usuario = $data['usuario'];
            	$rol = $data['rol']; 
			}
		}else{
			header('location: lista_usuarios.php');
		}
	}

?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<!--Llamar a la carpeta de css y js -->
	<?php
		include"includes/scripts.php";
	?>
	<title>Eliminar Usuario</title>
</head>
<body> 
	<!--Llamar al menu de la cabezera -->
	<?php
		include"includes/header.php";
	?>
	<!-- Contenedor que muestra los datos y pregunta si esta seguro de eliminar -->
	<section id="container">
		<div class="data_delete">
			<br> 
			<h2>¿Esta seguro de eliminar el registro seleccionado?</h2>
			<br>
			<p>Nombre: <span> <?php echo $nombre; ?> </span></p>
			<p>Usuario: <span> <?php echo $usuario; ?> </span></p>
			<p>Tipo de Usuario: <span> <?php echo $rol; ?> </span></p>

			<form method="post" action=""> 
				<input type="hidden" name="idusuario" value="<?php echo $idusuario; ?>">  
				<a href="lista_usuarios.php" class="btn_cancel"> <i class="fa-solid fa-ban"></i>Cancelar</a> 
 				<button type="submit" class="btn_ok" ><i class="fa-solid fa-trash-can"></i> Aceptar </button>
			</form>
		</div>


	</section>

	<!--Llamar al pie de pagina-->
	<?php
		include"includes/footer.php";
	?>
</body>
</html>