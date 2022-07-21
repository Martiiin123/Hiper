<?php

    include "../conexion.php";  
    session_start();

    //print_r($_POST); 

    if(!empty($_POST)){
        // Extrae datos del producto
        if($_POST['action'] == 'infoProducto')
        {
            $producto_id = $_POST['producto'];

            // Query que regresa los datos necesario del producto seleccionado 
            $query = mysqli_query($conection, "SELECT codproducto, descripcion, precio, existencia from producto
                                                where codproducto = $producto_id and estatus = 1");
                                        
            mysqli_close($conection);

            $result = mysqli_num_rows($query);

            if($result > 0 ){
                // Devuelve el arreglo en un formato
                $data = mysqli_fetch_assoc($query);
                // Regresa en un formato Json los resultados del query
                // Quita caracteres especiales
                echo json_encode($data, JSON_UNESCAPED_UNICODE);
                exit;
            }
            echo 'error';
            exit;
 
        }


        // Agrega producto a la entrada
        if($_POST['action'] == 'addProduct')
        {
            // Valida si los campos estan vacios
             if (!empty($_POST['cantidad']) || !empty($_POST['precio']) || !empty($_POST['producto_id'])){

                // Asigna valor a las variables con los campos de recuadro "Agregar"
                $cantidad = $_POST['cantidad'];
                $precio = $_POST['precio'];
                $producto_id = $_POST['producto_id'];
                $usuario_id = $_SESSION['idUser'];

                $query_insert = mysqli_query($conection, "insert into entradas (codproducto, cantidad,precio, usuario_id) 
                                                                    values ($producto_id, $cantidad, $precio, $usuario_id)");

                if($query_insert){
                    // Ejecuta procedimiento almacenado
                    $query_upd = mysqli_query($conection, "CALL actualizar_precio_producto($cantidad, $precio, $producto_id)");
                    $result_pro = mysqli_num_rows($query_upd);

                    // Valida si se encontro resultados del query anterior
                    if ($result_pro > 0){
                        $data = mysqli_fetch_assoc($query_upd);

                        // Agregamos el id del producto a los datos de la variable $data
                        $data['producto_id'] = $producto_id;

                        // Regresa en un formato Json los resultados del query
                       // Quita caracteres especiales
                        echo json_encode($data, JSON_UNESCAPED_UNICODE);
                        exit;
                    }
                }else {
                    echo 'error';
                }
                mysqli_close($conection);
             }else{
                echo 'error';
             }
             exit;
        }

    }  
    exit;
?>                                                                           