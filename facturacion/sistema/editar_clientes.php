<?php

    session_start(); 

    //Llama al archivo de conexion y captura los datos
    include "../conexion.php";  
		
    if(!empty($_POST))
    {
        //Verifica los campos 
        $alert=' ';
        if(empty($_POST['rfc']) || empty($_POST['nombre']) || 
            empty($_POST['telefono']) || empty($_POST['direccion'])  )
        {
            $alert='<p class="msg_error"> Todos los campos son obligatorios </p>';
        }else{
             
            //Obtencion de los datos
            $idcliente = $_POST['id'];
            $rfc = $_POST['rfc'];
            $nombre = $_POST['nombre'];
            $telefono = $_POST['telefono'];
            $direccion =  $_POST['direccion'] ; 
 
            //Consulta que regresa si el usuaio o correo ya existen la la tabla de Cliente
            $query = mysqli_query($conection, "select * from cliente 
                                                        where (rfc ='$rfc' and idcliente != $idcliente)") ;
                    
            $result = mysqli_fetch_array($query);  
            //metodo para encontrar conincidencias
            if($result > 0 ){
                $alert='<p class="msg_error"> El usuario o correo ya existe. </p>';
            }else {
                //Inserta en tabla ClienteS
 
                $sql_update = mysqli_query($conection, "update cliente set  rfc='$rfc',nombre='$nombre',
                                                            telefono='$telefono',direccion='$direccion' 
                                                            where idcliente=$idcliente"); 

                if($sql_update){
                    $alert='<p class="msg_save"> Cliente actualizado correctamente. </p>';
                }else {
                    $alert='<p class="msg_error"> Error al actualizar el cliente </p>';
                }
            } 
        } 
    }
    
    // Si no encuentra un id registrado regresa directamente a la lista de cliente  * Muestra datos * 
    if(empty($_REQUEST['id']))
    {  
        header('location: lista_clientes.php'); 
        mysqli_close($conection);
    }
    $idcliente = $_REQUEST['id'];

    //Retorna los campos del id seleccionado de la lista de cliente al precionar "Editar"
    $sql = mysqli_query($conection, "select *from cliente 
                                    where idcliente = $idcliente and estatus=1 ");
  
    //Obtiene el numero de cadenas seleccionadas
    $result_sql = mysqli_num_rows($sql);

    //Si no encuentra resultado regresa a la lista de clientes 
    if($result_sql == 0 )
    { 
        header('location: lista_clientes.php'); 
    }else { 

        //Obtencion de los datos guardados en la tabla para plasmarlos en los input lineas (137 - 145)
        while ($data = mysqli_fetch_array($sql)){

            $idcliente = $data['idcliente'];
            $rfc = $data['rfc'];
            $nombre = $data['nombre'];
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
	<title>Actualizacion de Cliente</title>
</head>
<body>
	<!--Llamar al menu de la cabezera -->
	<?php
		include"includes/header.php";
	?>
	<section id="container">
		
        <div class="form_register">
            <h1> <i class="fa-solid fa-pen-to-square"></i> Actualizar Cliente</h1>
            <hr>
            <div class = "alert"> <?php
		        echo isset($alert) ? $alert: ''; //If simplificado para revisar si la varible alert tiene mensaje    
                ?>
            </div>

            <form action="" method="post">
                <input type="hidden" name="id" value="<?php echo $idcliente; ?>">
                <label for="rfc">RFC</label>     
                <input type="text" name="rfc" id="rfc" placeholder="RFC" value="<?php echo $rfc; ?>">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" placeholder="Nombre Completo" value="<?php echo $nombre; ?>">
                <label for="telefono">Telefono</label>
                <input type="number" name="telefono" id="telefono" placeholder="Telefono" value="<?php echo $telefono; ?>">
                <label for="direccion">Direccion</label>
                <input type="text" name="direccion" id="direccion" placeholder="Direccion Completa" value="<?php echo $direccion; ?>">
                
                <button type="submit" class="btn_save" > <i class="fa-solid fa-cloud-arrow-down"></i> Actualizar Cliente</button>

            </form>
            
        </div>

	</section>

	<?php
		include"includes/footer.php";
	?>
</body>
</html>