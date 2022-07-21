<?php

    session_start();

    // Impide registrar usuarios si no se tiene el rol de administrador 
    if($_SESSION['rol'] != 1){
        header("location: ./");
    }

    //Llama al archivo de conexion y captura los datos
    include "../conexion.php";  
		
    if(!empty($_POST))
    {
        //Verifica los campos 
        $alert=' ';
        if(empty($_POST['nombre']) || empty($_POST['correo']) || empty($_POST['usuario']) || 
            empty($_POST['clave']) || empty($_POST['rol'])  )
        {
            $alert='<p class="msg_error"> Todos los campos son obligatorios </p>';
        }else{
             
            //Obtencion de los datos e incriptacion de la contraseña
            $nombre = $_POST['nombre'];
            $email = $_POST['correo'];
            $user = $_POST['usuario'];
            $clave = md5($_POST['clave']);
            $rol = $_POST['rol']; 

            //Verificacion de correo y usuario unico 
            $query = mysqli_query($conection, "select * from usuario where usuario ='$user' OR correo = '$email' "); 
            $result = mysqli_fetch_array($query); 
            //metodo para encontrar conincidencias
            if($result > 0 ){
                $alert='<p class="msg_error"> El usuario o correo ya existe. </p>';
            }else {
                //Inserta en tabla usuarios
                $query_insert = mysqli_query($conection, "insert into usuario(nombre,correo,usuario,clave,rol) 
                values ('$nombre','$email','$user','$clave','$rol') "); 

                if($query_insert){
                    $alert='<p class="msg_save"> Usuario insertado con exito. </p>';
                }else {
                    $alert='<p class="msg_error"> Error al crear usuario </p>';
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
	<title>Registro de Usuarios</title>
</head>
<body>
	<!--Llamar al menu de la cabezera -->
	<?php
		include"includes/header.php";
	?>
	<section id="container">
		
        <div class="form_register">
            <h1> <i class=" fa-solid fa-user-plus"></i>  Registro Usuario  <i class="fa-solid fa-id-card-clip"></i>  </h1>
            <hr>
            <div class = "alert"> <?php
		        echo isset($alert) ? $alert: ''; //If simplificado para revisar si la varible alert tiene mensaje    
                ?>
            </div>

            <form action="" method="post">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" placeholder="Nombre Completo">
                <label for="correo">Correo Electronico</label>
                <input type="email" name="correo" id="correo" placeholder="Correo Electronico">
                <label for="usuario">Usuario</label>
                <input type="text" name="usuario" id="usuario" placeholder="Usario">
                <label for="clave">Contraseña</label>
                <input type="password" name="clave" id="clave" placeholder="Contraseña">
                <label for="rol">Rol</label>

                <?php
                    $query_rol = mysqli_query($conection, "select*from rol"); //obtiene los datos de la tabla rol 
                    mysqli_close($conection);
                    $result_rol = mysqli_num_rows($query_rol); //cuenta las filas del query de la tabla rol
                       
                 ?> 

                <select name="rol" id="rol"> 
                    <?php
                        if($result_rol > 0 ){  
                            while ($rol = mysqli_fetch_array($query_rol)){   //Numero de veces que el ciclo while imprime opciones
                    ?>
                                <option value= "<?php echo $rol["idrol"]; ?> "> <?php echo $rol["rol"]?> </option> 
                    <?php
                            }
                        }
                    ?> 
                </select>  
                <button type="submit" class="btn_save" > <i class="fa-solid fa-cloud-arrow-down"></i> Crear Usuario</button>
            </form>
        </div>

	</section>

	<?php
		include"includes/footer.php";
	?>
</body>
</html>