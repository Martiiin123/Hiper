<?php

    session_start();
 
    //Llama al archivo de conexion y captura los datos
    include "../conexion.php";  
		
    if(!empty($_POST))
    {
        //Verifica los campos 
        $alert='';
        if(empty($_POST['rfc']) || empty($_POST['nombre']) || empty($_POST['telefono']) || 
            empty($_POST['direccion'])  )
        {
            $alert='<p class="msg_error"> Todos los campos son obligatorios </p>';
        }else{
             
            //Obtencion de los datos 
            $rfc = $_POST['rfc'];
            $nombre = $_POST['nombre'];
            $telefono = $_POST['telefono']; 
            $direccion = $_POST['direccion']; 
            $usuario_id = $_SESSION['idUser'];
  
            //Verificacion de RFC unico
            $query = mysqli_query($conection, "select * from cliente where rfc ='$rfc' "); 
            $result = mysqli_fetch_array($query); 
  
            //metodo para encontrar conincidencias
            if($result > 0 ){
                $alert='<p class="msg_error"> El RFC ya esta registrado. </p>';
            }else {
                //Inserta en tabla usuarios
                $query_insert = mysqli_query($conection, "insert into cliente(rfc,nombre,telefono,direccion,usuario_id) 
                values('$rfc','$nombre','$telefono','$direccion','$usuario_id') "); 

                if($query_insert){
                    $alert='<p class="msg_save"> Cliente guardado con exito. </p>';
                }else {
                    $alert='<p class="msg_error"> Error al crear cliente </p>';     
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
	<title>Registro de Clientes</title>
</head>
<body>
	<!--Llamar al menu de la cabezera -->
	<?php
		include"includes/header.php";
	?>
	<section id="container">
		
        <div class="form_register">
            <h1 class="registro_clientes_tag"> <i class="fa-solid fa-user-plus" ></i> Registro Cliente<i class="fa-solid fa-user-tag"></i></h1>
            <hr>
            <div class = "alert"> <?php
		        echo isset($alert) ? $alert: ''; //If simplificado para revisar si la varible alert tiene mensaje    
                ?>
            </div>

            <form action="" method="post">
                <label for="rfc">RFC</label>     
                <input type="text" name="rfc" id="rfc" placeholder="RFC">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" placeholder="Nombre Completo">
                <label for="telefono">Telefono</label>
                <input type="number" name="telefono" id="telefono" placeholder="Telefono">
                <label for="direccion">Direccion</label>
                <input type="text" name="direccion" id="direccion" placeholder="Direccion Completa">
               
                <button type="submit" class="btn_save" > <i class="fa-solid fa-cloud-arrow-down"></i> Registar Cliente</button>
            </form>
        </div>

	</section>

	<?php
		include"includes/footer.php";
	?>
</body>
</html>