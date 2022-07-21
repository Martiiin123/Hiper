<?php

    session_start(); 
     // Impide registrar usuarios si no se tiene el rol de administrador 
     if($_SESSION['rol'] != 1 and $_SESSION['rol'] != 2){
        header("location: ./");
    }
    include "../conexion.php";
 
?> 

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<!--Llamar a la carpeta de css y js -->
	<?php
		include"includes/scripts.php";
	?>
	<title>Lista de Productos</title>
</head>
<body>
	<!--Llamar al menu de la cabezera -->
	<?php
		include"includes/header.php";
	?>
	<section id="container">

		<h1> <i class="fa-solid fa-clipboard-list"></i> Lista de Productos </h1> 
        <a href="registro_producto.php" class="btn_new" ><i class="fa-solid fa-cart-plus"></i> Crear Producto </a> 

        <!--Llamar al menu de la cabezera -->
        <form action="buscar_producto.php" method="get" class="form_search"> 
            <input type="text" name="busqueda" id="busqueda" placeholder="Buscar">
            <button type="submit" class="btn_search"> <i class="fa-solid fa-magnifying-glass"></i> </button>
        </form>

        <table>
            <tr>
                <th>Codigo</th>
                <th>Descripcion</th>
                <th>Precio</th>
                <th>Existencia</th>
                <th>Proveedor</th> 
                <th>Foto </th> 
                <th>Acciones</th> 
            </tr>
 
            <?php

            //Cuanta los productos registrados creando una tabla temporal que almcena el numero 
            $sql_register = mysqli_query($conection, "select count(*) as total_registros from producto where estatus = 1");
            //Regresa el numero de query numero de cleintes
            $result_register = mysqli_fetch_array($sql_register);
            //Accedemos al dato crea de total de registros
            $total_registro = $result_register['total_registros'];
            //Numero de registros por pagina
            $por_pagina =4; 
            

            if(empty($_GET['pagina'])){
                $pagina = 1;
            }else {
                $pagina = $_GET['pagina'];   
            }

            //Variable que declara el registro en el cual el paginador inicia 
            $desde = ($pagina - 1) * $por_pagina;
            //Variable que redondea los registros que se van a mostrar y muestra el total de paginas en la barra inferior
            $total_paginas = ceil($total_registro / $por_pagina);  


            // Llama a los campos que se mostran en la tabla de producto registrados 
            // Solo muestra los productos con estado 1 (No eliminados) 
            // Se hace un inner join para mostrar el nombre del proveedor y no solo el num de codigo de este
                $query = mysqli_query($conection, "select p.codproducto, p.descripcion, p.precio, p.existencia, 
                                                        pr.proveedor, p.foto 
                                                        from producto p
                                                        inner join proveedor pr
                                                        on p.proveedor = pr.codproveedor
                                                        where p.estatus = 1 
                                                        order by p.codproducto ASC   
                                                        limit $desde, $por_pagina ");
                
                mysqli_close($conection);

                 
            //Cuenta la cantidad de registros
            $result = mysqli_num_rows($query);
            //Si es mayor a uno imprime y llama al while
            if ($result > 0 ){

                while($data = mysqli_fetch_array($query)){

                    //Si el resultado del query en su campo foto regresa algo diferente a la imagen defaul
                    // Entrar a la carpeta de las fotos y muestra su respectiva imagen
                    // de lo contrario muestra la foto defauk
                    if($data['foto'] != 'img_producto.png' ){
                        $foto = 'img/uploads/'.$data['foto'];
                    }else {
                        $foto = 'img/'.$data['foto'];
                    }

            ?> 
                        <tr class = "row<?php echo $data["codproducto"] ?>  ">
                            <td> <?php echo $data["codproducto"]  ?> </td>
                            <td> <?php echo $data["descripcion"]  ?> </td>
                            <td class = "celPrecio" > <?php echo $data["precio"]  ?> </td>
                            <td class = "celExistencia" > <?php echo $data["existencia"]  ?> </td>
                            <td> <?php echo $data["proveedor"]  ?> </td>  
                            <td class="img_producto"> <img src="<?php echo $foto;  ?> " alt="<?php echo $data["descripcion"];  ?>">    </td> 

                            <?php  
                                // Habilitar las opcs para administradores y supervisores
                                 if($_SESSION['rol'] ==1  || $_SESSION['rol'] ==2) {  ?> 

                            <td>
                                <!-- La clase add_produc invoca el codigo js-->
                                <a class="link_add add_product" product="<?php echo $data["codproducto"]; ?>" 
                                href="#"> 
                                <i class="fas fa-plus"></i>Agregar </a>
                                
                                |
                                <a class="link_edit" href="editar_producto.php?id=<?php echo $data["codproducto"]; ?>"> 
                                <i class="fa-solid fa-pen-to-square"></i>Editar </a>
                                
                                |
                                <a class="link_delete" href="eliminar_confirmar_producto.php?id=<?php echo $data["codproducto"]; ?>">
                                <i class="fa-solid fa-trash-can"></i> Eliminar</a>
                            </td>

                            <?php } ?>
                        </tr> 
            <?php
                    }
        
                }
            ?> 
        </table>

        <div class="paginador"> 
            <ul>
                <?php
                //Muestra los dos primeros botones para navegar entre las paginas 
                    if($pagina != 1 ){ 
                        ?> 
                        <li><a href="?pagina=<?php echo 1; ?>"> |< </a></li>
 
                        <li><a href="?pagina=<?php echo $pagina - 1; ?>"> << </a></li>
                    
                        <?php  
                    }
                    //Ciclo for que muestra el total de paginas y permite navegar entre ellas 
                    for($i=1; $i <= $total_paginas; $i++){
                        //Condicional que igual la posicion con la pagina seleccionada para señalar en que pagina estoy ubicado
                        if($i == $pagina){
                            echo '<li class="pageSelected"> '.$i.' </li> ';
                        }else {
                            echo '<li><a href="?pagina='.$i.'">'.$i.'</a></li> ';
                        } 
                    }

                    //Muestra los dos botones finales para navegar entre las paginas 
                    if($pagina != $total_paginas){    
                    ?> 
                        <li><a href="?pagina=<?php echo $pagina + 1; ?>">  >> </a></li>
         
                        <li><a href="?pagina=<?php echo $total_paginas ?>"> >| </a></li>
                    <?php  
                    }
                    ?>
            </ul>
        </div>

	</section>


	<!--Llamar al pie de pagina-->
	<?php
		include"includes/footer.php";
	?>
</body>
</html>