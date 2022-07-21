<?php

	session_start();
	// Impide eliminar usuarios si no se tiene el rol de administrador 
	if($_SESSION['rol'] != 1){
		header("location: ./");
	} 
	include "../conexion.php";

	//Si se da click en el boton aceptar mediante el metodo post
	if(!empty($_POST)){ 
 
		$idcliente = $_POST['idcliente'];

		//Query para eliminar definitivamente 
		// $query_delete = mysqli_query($conection, "delete from cliente where idcliente = $idcliente");
		
		//Estatus para cambiar el estatus de un cliente a 0 
		$query_delete =mysqli_query($conection, "update cliente set estatus = 0 where idcliente = $idcliente");
		mysqli_close($conection);

		if($query_delete){
            $alert= "<script>alert('Eliminado correctamente');</script>";
            echo $alert;
			header('location: lista_clientes.php'); 
		}else{
			echo "Error al eliminar el cliente";
		}
		 
	}

	// Si no encuentra un id registrado regresa directamente a la lista de clientes	  
	if(empty($_REQUEST['id'])  ) 
	{   
		header('location: lista_clientes.php'); 
		mysqli_close($conection);
		 
	}else{
		  
		$idcliente = $_REQUEST['id'];

		//Retorna los campos del id seleccionado de la lista de cliente  al precionar "Eliminar "
		$query = mysqli_query($conection, "select *from cliente  
										   where idcliente = $idcliente");
										
		mysqli_close($conection);

		//Obtiene el numero de cadenas seleccionadas
		$result = mysqli_num_rows($query);

		//Si no encuentra resultado regresa a la lista de clientes
		if($result > 0 ){
			//Obtencion de los datos guardados en la tabla para plasmarlos en los input lineas (?)
			while ($data = mysqli_fetch_array($query)){
			
				$rfc = $data['rfc'];
                $nombre = $data['nombre']; 

			}
		}else{
			header('location: lista_clientes.php');
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
			<p>RFC: <span> <?php echo $rfc; ?> </span></p> 
			<p>Nombre: <span> <?php echo $nombre; ?> </span></p> 

			<form method="post" action=""> 
				<input type="hidden" name="idcliente" value="<?php echo $idcliente; ?>">  
				<a href="lista_clientes.php" class="btn_cancel"> <i class="fa-solid fa-ban"></i>Cancelar</a> 
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