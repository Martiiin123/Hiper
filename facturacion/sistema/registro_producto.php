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
        if(empty($_POST['proveedor']) || empty($_POST['producto']) || empty($_POST['precio'])  || 
            empty($_POST['cantidad']) )
        {
            $alert='<p class="msg_error"> Todos los campos son obligatoriofsdfdsfsds </p>';
        }else if ( $_POST['precio']<= 0 ||  $_POST['cantidad']<= 0 )
        {
            $alert='<p class="msg_error"> El precio y/o existencia deben ser mayor a 0 </p>';
        }
        else{
             
            //Obtencion de los datos 
            $proveedor = $_POST['proveedor'];
            $producto = $_POST['producto'];
            $precio = $_POST['precio']; 
            $cantidad = $_POST['cantidad']; 
            $usuario_id = $_SESSION['idUser'];

            //Obtencion de los datos de la foto
            $foto = $_FILES['foto'];
            $nombre_foto = $foto['name'];
            $type = $foto['type'];
            $url_tmp = $foto['tmp_name'];

            // Imagen por deafaul si no se guarda foto
            $imgProducto ='img_producto.png';

            //Ruta de almacenamiento de la foto si es que se agrega una
            if($nombre_foto != ''){
                $destino = 'img/uploads/';
                //Enciptacion de la foto para no tener nombre repetido y se le agrega el formato jpg siempre  
                // Y declaracion del destino de la foto
                $img_nombre = 'img_'.md5(date('d-m-Y M:m:s'));
                $imgProducto = $img_nombre.'.jpg';
                $src  = $destino.$imgProducto;
            }
  
            //Verificacion de producto unico
            $query = mysqli_query($conection, "select * from producto where descripcion ='$producto' "); 
            $result = mysqli_fetch_array($query); 
  
            //metodo para encontrar conincidencias
            if($result > 0 ){
                $alert='<p class="msg_error"> El Producto ya esta registrado. </p>';
            }else {
                //Inserta en tabla usuarios
                $query_insert = mysqli_query($conection, "insert into producto(proveedor,descripcion,
                                                                        precio,existencia,usuario_id,foto) 
                values('$proveedor','$producto','$precio','$cantidad','$usuario_id','$imgProducto') "); 

                if($query_insert){
                    if($nombre_foto != ''){
                        // Mueve la direccion temporal a la direccion de de la carpeta imagenes 
                        move_uploaded_file($url_tmp, $src);
                    }
                    $alert='<p class="msg_save"> Producto guardado con exito. </p>';
                }else {
                    $alert='<p class="msg_error"> Error al crear Producto </p>';     
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
	<title>Registro de Productos</title>
</head>
<body>
	<!--Llamar al menu de la cabezera -->
	<?php
		include"includes/header.php";
	?>
	<section id="container">
		
        <div class="form_register">
            <h1  > <i class="fa-solid fa-user-plus"></i>Registro Producto<i class="fa-solid fa-cart-shopping"></i> </h1>
            <hr>
            <div class = "alert"> <?php
		        echo isset($alert) ? $alert: ''; //If simplificado para revisar si la varible alert tiene mensaje    
                ?>
            </div>

            <form action="" method="post" enctype="multipart/form-data">
                <label for="proveedor">Proveedor</label>   
                <?php 
                    // Encuentra los campos solicitados de la tabla proveedor para mostrarlos en el option
                    $query_proveedor = mysqli_query($conection, "select codproveedor, proveedor from proveedor 
                    where estatus = 1 order by proveedOR ASC");
                    $result_proveedor = mysqli_num_rows($query_proveedor);
                    mysqli_close($conection);
                ?>  
                <select name="proveedor" id="proveedor">
                <?php
                    if($result_proveedor > 0 ){ 
                        // $Proveedor obtiene los datos de la consulta de la linea 81*
                        while($proveedor = mysqli_fetch_array($query_proveedor)){
                ?> 
                    <option value="<?php echo $proveedor['codproveedor'];?>"> <?php echo $proveedor['proveedor']?>  </option>
                <?php
                        }
                    }
                ?>
                         
                </select>  
 
                <label for="producto">Producto</label> 
                <input type="text" name="producto" id="producto" placeholder="Nombre del producto">
                <label for="precio">Precio</label>
                <input type="number" name="precio" id="precio" placeholder="Precio">
                <label for="cantidad">Cantidad</label>
                <input type="number" name="cantidad" id="cantidad" placeholder="Cantidad de existencia">
                <div class="photo">
                    <label for="foto">Foto</label>
                        <div class="prevPhoto">
                        <span class="delPhoto notBlock">X</span>
                        <label for="foto"></label>
                        </div>
                        <div class="upimg">
                        <input type="file" name="foto" id="foto">
                        </div>
                        <div id="form_alert"></div>
                </div>
               
                <button type="submit" class="btn_save" > <i class="fa-solid fa-cloud-arrow-down"></i> Registar Producto</button>
            </form>
        </div>

	</section>

	<?php
		include"includes/footer.php";
	?>
</body>
</html>