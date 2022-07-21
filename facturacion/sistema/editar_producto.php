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
    if(empty($_POST['proveedor']) || empty($_POST['producto']) || empty($_POST['precio']) || 
        empty($_POST['id'])  || empty($_POST['foto_actual']) || empty($_POST['foto_remove']))
    {
        $alert='<p class="msg_error"> Todos los campos son obligatorios </p>';
    }else if ( $_POST['precio']<= 0)
    {
        $alert='<p class="msg_error"> El precio y/o existencia deben ser mayor a 0 </p>';
    }else{

        $codproducto = $_POST['id'];
        
        //Obtencion de los datos 
        $proveedor = $_POST['proveedor'];
        $producto = $_POST['producto'];
        $precio = $_POST['precio']; 
        $imgProducto = $_POST['foto_actual']; 
        $imgRemove = $_POST['foto_remove'];

        //Obtencion de los datos de la foto
        $foto = $_FILES['foto'];
        $nombre_foto = $foto['name'];
        $type = $foto['type'];
        $url_tmp = $foto['tmp_name'];

        // Imagen por deafaul si no se guarda foto
        $upd ='';

        //Ruta de almacenamiento de la foto si es que se agrega una
        if($nombre_foto != ''){
            $destino = 'img/uploads/';
            //Enciptacion de la foto para no tener nombre repetido y se le agrega el formato jpg siempre  
            // Y declaracion del destino de la foto
            $img_nombre = 'img_'.md5(date('d-m-Y M:m:s'));
            $imgProducto = $img_nombre.'.jpg';
            $src  = $destino.$imgProducto;
        }else{
            // Si se a eliminado la imagen la variable toma el valor de las foto por decfecto  ********
            if($_POST['foto_actual'] != $_POST['foto_remove']){
                $imgProducto ='img_producto.png';
            }
        }
          
            //Actualiza en tabla prodcuto
            $query_update = mysqli_query($conection, "UPDATE producto SET descripcion = $producto, proveedor = $proveedor,
                                                                    precio = $precio, foto = '$imgProducto' 
                                                                    WHERE codproducto = $codproducto"); 
                                                                    
            $alert='<p class="msg_save"> Si entró </p>'  ;

            if($query_update){

                // En caso de que se seleccionara otra foto de los archivos guardados actualiza con la nueva imagen el campo
                if(($nombre_foto != '' && ($_POST['foto_actual'] != 'img_producto.png' )) || ($_POST['foto_actual'] != $_POST['foto_remove'])){
                    unlink('img/uploads/'.$_POST['foto_actual']);
                }

                if($nombre_foto != ''){
                    // Mueve la direccion temporal a la direccion de de la carpeta imagenes 
                    move_uploaded_file($url_tmp, $src);
                    $alert='<p class="msg_save"> Producto actualizado con exito. </p>';
                }
                
            }else {
                $alert='<p class="msg_error"> Error al actualizar el producto32reewrew </p>';     
            } 
    }
}
 
// Validacion de producto no vacio o repetido
if (empty($_REQUEST['id'])) {
    
    header("location: lista_producto.php");
}else { 
    $id_producto = $_REQUEST['id'];

    if(!is_numeric($id_producto)){
        header("location: lista_producto.php");
    }

    // Query que realiza un inner join entre las tablas producto y provedor para mostrar datos de ambas tablas
    $query_producto = mysqli_query($conection, "select p.codproducto, p.descripcion, p.precio, p.foto, pr.codproveedor, pr.proveedor
                                                 from producto p 
                                                 inner join proveedor pr 
                                                 on p.proveedor = pr.codproveedor 
                                                 where p.codproducto = $id_producto and p.estatus = 1");
    $result_prducto = mysqli_num_rows($query_producto);

    $foto = '';
    $classRemove = 'notBlock';

    // Si encuentra valores en el query
    if($result_prducto > 0){
        // Toma el array de los datos del query y lo asignamos 
        $data_producto = mysqli_fetch_assoc($query_producto);

        // Si el resiltadO en su campo foto contiene otra que no sea la foto por defecto, busca en la carpeta de las imagenes
        // Y asigna la ruta a la variable foto
        if($data_producto['foto'] != 'img_producto.png'){
            $classRemove = '';
            $foto = '<img  id="img" src="img/uploads/'.$data_producto['foto'].'" alt="Producto">';
        }

        print_r($data_producto);
    }else {
        
        $alert='<p class="msg_error"> sdsd</p>';
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
<title>Actualizar Productos</title>
</head>
<body>
<!--Llamar al menu de la cabezera -->
<?php
    include"includes/header.php";
?>
<section id="container">
    
    <div class="form_register">
        <h1  > <i class="fa-solid fa-pen-to-square"></i> Actualizar Producto  </h1>
        <hr>
        <div class = "alert"> <?php
            echo isset($alert) ? $alert: ''; //If simplificado para revisar si la varible alert tiene mensaje    
            ?>
        </div>

        <form action="" method="post" enctype="multipart/form-data">

            <!--  -->
            <input type="hidden" name="id" value="<?php echo $data_producto['codproveedor']; ?>"> 
            <input type="hidden" id="foto_actual" name="foto_actual" value="<?php echo $data_producto['foto']; ?>"> 
            <input type="hidden" id="foto_remove" name="foto_remove" value="<?php echo $data_producto['foto']; ?>"> 

            <label for="proveedor">Proveedor</label>   
            <?php 
                // Encuentra los campos solicitados de la tabla proveedor para mostrarlos en el option
                $query_proveedor = mysqli_query($conection, "select codproveedor, proveedor from proveedor 
                where estatus = 1 order by proveedOR ASC");
                $result_proveedor = mysqli_num_rows($query_proveedor);
                mysqli_close($conection);
            ?>  
            <select name="proveedor" id="proveedor" class="notItemOne">
                <!-- Codigo para que el proveedor de cada producto aparesca seleccionado al cargar la pag -->
                <option value="<?php echo $data_producto['codproveedor']; ?>" selected> <?php echo $data_producto['proveedor']; ?></option>
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
            <input type="text" name="producto" id="producto" placeholder="Nombre del producto" value="<?php echo $data_producto['descripcion']; ?>">
            <label for="precio">Precio</label>
            <input type="number" name="precio" id="precio" placeholder="Precio" value="<?php echo $data_producto['precio']; ?>">

            <div class="photo">
                <label for="foto">Foto</label>
                    <div class="prevPhoto">
                    <span class="delPhoto <?php echo $classRemote; ?>">X</span>
                    <label for="foto"></label>
                    <?php echo $foto; ?>
                    </div>
                    <div class="upimg">
                    <input type="file" name="foto" id="foto">
                    </div>
                    <div id="form_alert"></div>
            </div>
           
            <button type="submit" class="btn_save" > <i class="fa-solid fa-cloud-arrow-down"></i> Actualizar Producto</button>
        </form>
    </div>

</section>

<?php
    include"includes/footer.php";
?>
</body>
</html>