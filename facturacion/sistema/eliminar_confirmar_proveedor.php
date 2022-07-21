<?php

	session_start();
	// Impide registrar usuarios si no se tiene el rol de administrador 
    if($_SESSION['rol'] != 1 and $_SESSION['rol'] != 2){
        header("location: ./");
    } 
	include "../conexion.php";

	//Si se da click en el boton aceptar mediante el metodo post
	if(!empty($_POST)){ 
 
		$idproveedor = $_POST['codproveedor'];

		//Query para eliminar definitivamente 
		// $query_delete = mysqli_query($conection, "delete from proveedor where codproveedor = $idproveedor");
		
		//Estatus para cambiar el estatus de un proveedor a 0 
		$query_delete =mysqli_query($conection, "update proveedor set estatus = 0 where codproveedor = $idproveedor");
		mysqli_close($conection);

		if($query_delete){
            $alert= "<script>alert('Eliminado correctamente');</script>";
            echo $alert;
			header('location: lista_proveedor.php'); 
		}else{
			echo "Error al eliminar el proveedor";
		}
		 
	}

	// Si no encuentra un id registrado regresa directamente a la lista de clientes	  
	if(empty($_REQUEST['id'])  ) 
	{   
		header('location: lista_proveedor.php'); 
		mysqli_close($conection);
		 
	}else{
		  
		$idproveedor = $_REQUEST['id'];

		//Retorna los campos del id seleccionado de la lista de cliente  al precionar "Eliminar "
		$query = mysqli_query($conection, "select *from proveedor  
										   where codproveedor = $idproveedor");
										
		mysqli_close($conection);

		//Obtiene el numero de cadenas seleccionadas
		$result = mysqli_num_rows($query);

		//Si no encuentra resultado regresa a la lista de clientes
		if($result > 0 ){
			//Obtencion de los datos guardados en la tabla para plasmarlos en los input lineas (?)
			while ($data = mysqli_fetch_array($query)){
			 
                $proveedor = $data['proveedor']; 

			}
		}else{
			header('location: lista_proveedor.php');
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
	<title>Eliminar Proveedor</title>
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
			<p>RFC: <span> <?php  ?> </span></p> 
			<p>Proveedor: <span> <?php echo $proveedor; ?> </span></p> 

			<form method="post" action=""> 
				<input type="hidden" name="codproveedor" value="<?php echo $idproveedor; ?>">  
				<a href="lista_proveedor.php" class="btn_cancel"> <i class="fa-solid fa-ban"></i>Cancelar</a> 
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