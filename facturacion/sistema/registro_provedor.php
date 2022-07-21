<?php

    session_start();

    // Impide registrar usuarios si no se tiene el rol de administrador 
    if($_SESSION['rol'] != 1 and $_SESSION['rol'] != 2){
        header("location: ./");
    }
 
    //Llama al archivo de conexion y captura los datos
    include "../conexion.php";  
		
    if(!empty($_POST))
    {
        //Verifica los campos 
        $alert='';
        if(empty($_POST['proveedor']) || empty($_POST['contacto']) || empty($_POST['telefono']) || 
            empty($_POST['direccion'])   )
        {
            $alert='<p class="msg_error"> Todos los campos son obligatorios </p>';
        }else{
             
            //Obtencion de los datos 
            $proveedor = $_POST['proveedor'];
            $contacto = $_POST['contacto'];
            $telefono = $_POST['telefono']; 
            $direccion = $_POST['direccion']; 
            $usuario_id = $_SESSION['idUser'];
  
            //Verificacion de provedor unico
            $query = mysqli_query($conection, "select * from proveedor where proveedor ='$proveedor' "); 
            $result = mysqli_fetch_array($query); 
  
            //metodo para encontrar conincidencias
            if($result > 0 ){
                $alert='<p class="msg_error"> El Provedor ya esta registrado. </p>';
            }else {
                //Inserta en tabla usuarios
                $query_insert = mysqli_query($conection, "insert into proveedor(proveedor,contacto,telefono,direccion,usuario_id) 
                values('$proveedor','$contacto','$telefono','$direccion','$usuario_id') "); 

                if($query_insert){
                    $alert='<p class="msg_save"> Proveedor guardado con exito. </p>';
                }else {
                    $alert='<p class="msg_error"> Error al crear Proveedor </p>';     
                }
            } 
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
	<title>Registro de Provedores</title>
</head>
<body>
	<!--Llamar al menu de la cabezera -->
	<?php
		include"includes/header.php";
	?>
	<section id="container">
		
        <div class="form_register">
            <h1 class="registro_provedores_tag"> <i class="fa-solid fa-user-plus"></i>Registro Proveedor<i class="fa-solid fa-truck-fast"></i> </h1>
            <hr>
            <div class = "alert"> <?php
		        echo isset($alert) ? $alert: ''; //If simplificado para revisar si la varible alert tiene mensaje    
                ?>
            </div>

            <form action="" method="post">
                <label for="proveedor">Proveedor</label>     
                <input type="text" name="proveedor" id="proveedor" placeholder="Nombre del Proveedor">
                <label for="contacto">Contacto</label>
                <input type="text" name="contacto" id="contacto" placeholder="Nombre Completo del Contacto">
                <label for="telefono">Telefono</label>
                <input type="number" name="telefono" id="telefono" placeholder="Telefono">
                <label for="direccion">Direccion</label>
                <input type="text" name="direccion" id="direccion" placeholder="Direccion Completa">
               
                <button type="submit" class="btn_save" > <i class="fa-solid fa-cloud-arrow-down"></i> Registar Proveedor</button>
            </form>
        </div>

	</section>

	<?php
		include"includes/footer.php";
	?>
</body>
</html>