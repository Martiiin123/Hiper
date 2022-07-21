<?php

    session_start(); 
     // Impide registrar usuarios si no se tiene el rol de administrador 
     if($_SESSION['rol'] != 1 and $_SESSION['rol'] != 2){
        header("location: ./");
    } 
    include "../conexion.php";  
		
    if(!empty($_POST))
    {
        //Verifica los campos 
        $alert=' ';
        if(empty($_POST['proveedor']) || empty($_POST['contacto']) || 
            empty($_POST['telefono']) || empty($_POST['direccion'])  )
        {
            $alert='<p class="msg_error"> Todos los campos son obligatorios </p>';
        }else{
             
            //Obtencion de los datos
            $idproveedor = $_POST['id'];
            $proveedor = $_POST['proveedor'];
            $contacto = $_POST['contacto'];
            $telefono = $_POST['telefono'];
            $direccion =  $_POST['direccion'] ; 
  
                //Inserta en tabla provedor
 
                $sql_update = mysqli_query($conection, "update proveedor set  proveedor='$proveedor',contacto='$contacto',
                                                            telefono='$telefono',direccion='$direccion' 
                                                            where codproveedor=$idproveedor"); 

                if($sql_update){
                    $alert='<p class="msg_save"> Proveedor actualizado correctamente. </p>';
                }else {
                    $alert='<p class="msg_error"> Error al actualizar el provedor </p>';
                }
 
        } 
    }
    
    // Si no encuentra un id registrado regresa directamente a la lista de provedor  * Muestra datos * 
    if(empty($_REQUEST['id']))
    {  
        header('location: lista_proveedor.php'); 
        mysqli_close($conection);
    }
    $idproveedor = $_REQUEST['id'];

    //Retorna los campos del id seleccionado de la lista de cliente al precionar "Editar"
    $sql = mysqli_query($conection, "select *from proveedor 
                                    where codproveedor = $idproveedor and estatus=1");
  
    //Obtiene el numero de cadenas seleccionadas
    $result_sql = mysqli_num_rows($sql);

    //Si no encuentra resultado regresa a la lista de provedor 
    if($result_sql == 0 )
    { 
        header('location: lista_proveedor.php'); 
    }else { 

        //Obtencion de los datos guardados en la tabla para plasmarlos en los input lineas (137 - 145)
        while ($data = mysqli_fetch_array($sql)){

            $idproveedor = $data['codproveedor'];
            $proveedor = $data['proveedor'];
            $contacto = $data['contacto'];
            $telefono = $data['telefono'];
            $direccion = $data['direccion'];  

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
	<title>Actualizacion de Provedores</title>
</head>
<body>
	<!--Llamar al menu de la cabezera -->
	<?php
		include"includes/header.php";
	?>
	<section id="container">
		
        <div class="form_register">
            <h1> <i class="fa-solid fa-pen-to-square"></i> Actualizar Proveedor</h1>
            <hr>
            <div class = "alert"> <?php
		        echo isset($alert) ? $alert: ''; //If simplificado para revisar si la varible alert tiene mensaje    
                ?>
            </div>

            <form action="" method="post">
                <input type="hidden" name="id" value="<?php echo $idproveedor?>">
                <label for="proveedor">Proveedor</label>     
                <input type="text" name="proveedor" id="proveedor" placeholder="Nombre del Proveedor" value="<?php echo $proveedor?>">
                <label for="contacto">Contacto</label>
                <input type="text" name="contacto" id="contacto" placeholder="Nombre Completo del Contacto" value="<?php echo $contacto?>">
                <label for="telefono">Telefono</label>
                <input type="number" name="telefono" id="telefono" placeholder="Telefono" value="<?php echo $telefono?>">
                <label for="direccion">Direccion</label>
                <input type="text" name="direccion" id="direccion" placeholder="Direccion Completa" value="<?php echo $direccion?>">
               
                <button type="submit" class="btn_save" > <i class="fa-solid fa-cloud-arrow-down"></i> Actualizar Proveedor</button>
                 
            </form>
             
        </div>

	</section>

	<?php
		include"includes/footer.php";
	?>
</body>
</html>