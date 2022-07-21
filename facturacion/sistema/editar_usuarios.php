<?php

    session_start();
    // Impide  editar usuarios si no se tiene el rol de administrador 
    if($_SESSION['rol'] != 1){
        header("location: ./");
    }
    //Llama al archivo de conexion y captura los datos
    include "../conexion.php";  
		
    if(!empty($_POST))
    {
        //Verifica los campos 
        $alert=' ';
        if(empty($_POST['nombre']) || empty($_POST['correo']) || 
            empty($_POST['usuario']) || empty($_POST['rol'])  )
        {
            $alert='<p class="msg_error"> Todos los campos son obligatorios </p>';
        }else{
             
            //Obtencion de los datos e incriptacion de la contraseña
            $iduser = $_POST['id'];
            $nombre = $_POST['nombre'];
            $email = $_POST['correo'];
            $user = $_POST['usuario'];
            $clave = md5($_POST['clave']);
            $rol = $_POST['rol']; 
 
            //Consulta que regresa si el usuaio o correo ya existen la la tabla de usuario
            $query = mysqli_query($conection, "select * from usuario 
                                                        where (usuario ='$user' and idusuario != $iduser)
                                                        OR (correo = '$email' and idusuario != $iduser) ") ;
                    
            $result = mysqli_fetch_array($query);  
            //metodo para encontrar conincidencias
            if($result > 0 ){
                $alert='<p class="msg_error"> El usuario o correo ya existe. </p>';
            }else {
                //Inserta en tabla usuarios

                if(empty($_POST['clave'])){

                    $sql_update = mysqli_query($conection, "update usuario set nombre='$nombre',correo='$email',
                                                            usuario='$user',rol='$rol' where idusuario=$iduser");
                }else {
                    $sql_update = mysqli_query($conection, "update usuario set nombre='$nombre', correo='$email', clave='$clave',
                                                            usuario='$user', rol='$rol' where idusuario=$iduser");
                }

                if($sql_update){
                    $alert='<p class="msg_save"> Usuario actualizado correctamente. </p>';
                }else {
                    $alert='<p class="msg_error"> Error al actualizar el  usuario </p>';
                }
            } 
        } 
    }
    
    // Si no encuentra un id registrado regresa directamente a la lista de usuarios  * Muestra datos * 
    if(empty($_REQUEST['id']))
    {  
        header('location: lista_usuarios.php'); 
        mysqli_close($conection);
    }
    $iduser = $_REQUEST['id'];

    //Retorna los campos del id seleccionado de la lista de usuario al precionar "Editar"
    $sql = mysqli_query($conection, "select u.idusuario, u.nombre, u.correo, u.usuario, (u.rol) as idrol, (r.rol) as rol
                                    from usuario u 
                                    inner join rol r 
                                    on u.rol = r.idrol
                                    where idusuario = $iduser and estatus=1");
  
    //Obtiene el numero de cadenas seleccionadas
    $result_sql = mysqli_num_rows($sql);

    //Si no encuentra resultado regresa a la lista de usuarios 
    if($result_sql == 0 )
    {
        print_r($_GET); exit;
        header('location: lista_usuarios.php'); 
    }else {

        $option = '';

        //Obtencion de los datos guardados en la tabla para plasmarlos en los input lineas (137 - 145)
        while ($data = mysqli_fetch_array($sql)){

            $iduser = $data['idusuario'];
            $nombre = $data['nombre'];
            $correo = $data['correo'];
            $usuario = $data['usuario'];
            $idrol = $data['idrol'];
            $rol = $data['rol']; 

            //If para obtener los datos del campo select de la tabla de rol
            if($idrol == 1){
                $option = '<option value= "'.$idrol.'" select> '.$rol.' </option> ';
            }else if($idrol == 2){
                $option = '<option value= "'.$idrol.'" select> '.$rol.' </option> ';
            }else if($idrol == 3){
                $option = '<option value= "'.$idrol.'" select> '.$rol.' </option> ';
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
	<title>Actualizacion de Usuarios</title>
</head>
<body>
	<!--Llamar al menu de la cabezera -->
	<?php
		include"includes/header.php";
	?>
	<section id="container">
		
        <div class="form_register">
            <h1> <i class="fa-solid fa-pen-to-square"></i> Actualizar Usuario</h1>
            <hr>
            <div class = "alert"> <?php
		        echo isset($alert) ? $alert: ''; //If simplificado para revisar si la varible alert tiene mensaje    
                ?>
            </div>

            <form action="" method="post">
                <input type="hidden" name="id" value="<?php echo$iduser; ?>">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" placeholder="Nombre Completo" value="<?php echo$nombre; ?>">
                <label for="correo">Correo Electronico</label>
                <input type="email" name="correo" id="correo" placeholder="Correo Electronico" value="<?php echo$correo; ?>">
                <label for="usuario">Usuario</label>
                <input type="text" name="usuario" id="usuario" placeholder="Usario" value="<?php echo$usuario; ?>">
                <label for="clave">Contraseña</label>
                <input type="password" name="clave" id="clave" placeholder="Contraseña"  >
                <label for="rol">Rol</label>

                <?php
                     include "../conexion.php"; 
                    $query_rol = mysqli_query($conection, "select*from rol"); //obtiene los datos de la tabla rol
                    mysqli_close($conection);
                    $result_rol = mysqli_num_rows($query_rol); //cuenta las filas del query de la tabla rol
                       
                 ?> 

                <select name="rol" id="rol" class="notItemOne"> 
                    <?php
                        echo $option; 
                        // Entra si existe al menos un rol en la tabla 
                        if($result_rol > 0 ){  
                            while ($rol = mysqli_fetch_array($query_rol)){   //Numero de veces que el ciclo while imprime opciones
                    ?>
                                <!-- Muestra las opciones que estan en la tabla rol -->
                                <option value= "<?php echo $rol["idrol"]; ?>"><?php echo $rol["rol"]?> </option> 
                    <?php
                            }
                        }
                    ?> 
                </select> 
                <button type="submit" class="btn_save" > <i class="fa-solid fa-cloud-arrow-down"></i> Actualizar Usuario</button>
            </form>
        </div>

	</section>

	<?php
		include"includes/footer.php";
	?>
</body>
</html>