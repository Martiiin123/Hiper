<?php 

use LDAP\Result;

    $alert = '';
    session_start();

    //Si la seccion ya existe entra al sistema directamente
    if(!empty($_SESSION['active']))
    {
        header('location: sistema/'); 
    }else 
    {
        //Verificacion de que los campos usuario y contraseña
        if(!empty($_POST)){
            if(empty($_POST['usuario']) || empty($_POST['clave']))
            {
                $alert = "Porfavor complete todos los campos";
            }else {
                //Conexion a la base de datos por el archivo conexion.php
                require_once "conexion.php";

                //real_escape_string Encripta la contraseña y evita ingresar caracteres como |@#¬€ 
                $user = mysqli_real_escape_string($conection, $_POST['usuario']); 
                $pass = md5(mysqli_real_escape_string($conection, $_POST['clave']));

                // Comprovacion de los campos usuario y contraseña 
                $query = mysqli_query($conection, "Select*from usuario where 
                usuario = '$user' and clave = '$pass' ");
 
                mysqli_close($conection);

                //Validacion si se encuentra un registro igual al ingresado
                $Result = mysqli_num_rows($query);
                if($Result>0)
                {
                    $data = mysqli_fetch_array($query);
                    //Seccion inciada con datos correctos 
                    $_SESSION['active'] = true;
                    $_SESSION['idUser'] = $data['idusuario'];
                    $_SESSION['nombre'] = $data['nombre'];
                    $_SESSION['email'] = $data['correo'];
                    $_SESSION['user'] = $data['usuario'];
                    $_SESSION['rol'] = $data['rol'];

                    //Redireccion a la pagina principal del sistema 
                    header('location: sistema/'); 
                }else{
                    $alert = "El usuario o la clave son incorrectas"; 
                    session_destroy();
                }
            } 
        }
    } 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login º Sistema de Facturacion </title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>

    <section id="container">

        <form action="" method="post">
            <h3>Iniciar Sesion</h3>
            <img src = "img/blog-wp-login.png" alt="Login">

            <input type ="text" name="usuario" placeholder="Usuario">
            <input type ="password" name="clave" placeholder="Contraseña">
            <div class="alert"> <?php echo isset($alert)? $alert : '';  ?>  </dive>
            <input type ="submit" value="INGRESAR">
        </form>

    </section>

</body>
</html>
 