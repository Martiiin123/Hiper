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
	<title>Lista de Proveedores</title>
</head>
<body>
	<!--Llamar al menu de la cabezera -->
	<?php
		include"includes/header.php";
	?>
	<section id="container">
        <?php
            //Convierte la cadena a minusculas y redirige a la lista principal si no hay coincidencias
            $busqueda =strtolower($_REQUEST['busqueda']);
            if(empty($busqueda)){
                header("location: lista_proveedor.php");
                mysqli_close($conection);
            }
        ?>

		<h1> <i class="fa-solid fa-boxes-packing"></i> Lista de Proveedores</h1> 
        <a href="registro_provedor.php" class="btn_new" >  <i class="fa-solid fa-cart-flatbed"></i>Crear Proveedor </a> 

        <!--Llamar al menu de la cabezera -->
        <form action="buscar_proveedor.php" method="get" class="form_search"> 
            <input type="text" name="busqueda" id="busqueda" placeholder="Buscar" value="<?php echo $busqueda; ?>">
            <button type="submit" class="btn_search"> <i class="fa-solid fa-magnifying-glass"></i> </button>
        </form>

        <table>
            <tr>
               <th>ID</th>
                <th>Proveedor</th>
                <th>Contacto</th>
                <th>Telefono</th>
                <th>Direccion</th> 
                <th>Fecha de registro</th> 
                <th>Acciones</th> 
            </tr>
 
            <?php
 

            

            //Cuanta los proveedores registrados creando una tabla temporar que almcena el numero 
            //Utilizaz la variable busqueda en todos los campos para encontra coincidencia 
            $sql_register = mysqli_query($conection, "select count(*) as total_registros from proveedor
                                                                where  (
                                                                codproveedor like '%$busqueda%' or
                                                                proveedor like '%$busqueda%' or  
                                                                contacto like '%$busqueda%' or  
                                                                telefono like '%$busqueda%' or   
                                                                direccion like '%$busqueda%'  
                                                                ) and  estatus = 1");

            //Regresa el numero de query numero de usuarios
            $result_register = mysqli_fetch_array($sql_register);
            //Accedemos al dato crea de total de registros
            $total_registro = $result_register['total_registros'];
            //Numero de registros por pagina
            $por_pagina = 5;
            

            if(empty($_GET['pagina'])){
                $pagina = 1;
            }else {
                $pagina = $_GET['pagina'];   
            }

            //Variable que declara el registro en el cual el paginador inicia 
            $desde = ($pagina - 1) * $por_pagina;
            //Variable que redondea los registros que se van a mostrar y muestra el total de paginas en la barra inferior
            $total_paginas = ceil($total_registro / $por_pagina);  


            // Llama a los campos que se mostran en la tabla de proveedores registrados 
            // Solo muestra los proveedores con estado 1 (No eliminados)
            // Muestra los proveedores que cumplan con el campo de busqueda
                $query = mysqli_query($conection, "select * from proveedor 
                where (
                    codproveedor like '%$busqueda%' or
                    proveedor like '%$busqueda%' or  
                    contacto like '%$busqueda%' or  
                    telefono like '%$busqueda%'or   
                    direccion like '%$busqueda%' 
                    )
                and estatus = 1 
                order by codproveedor ASC   
                limit $desde, $por_pagina ");

                mysqli_close($conection);
                 
                $result = mysqli_num_rows($query);
                //Si es mayor a uno imprime y llama al while
                if ($result > 0 ){

                    while($data = mysqli_fetch_array($query)){
                        $formato = 'Y-m-d H:i:s';
                        $fecha = DateTime::createFromFormat($formato, $data["date_add"]);

            ?> 
                        <tr>
                            <td> <?php echo $data["codproveedor"]  ?> </td>
                            <td> <?php echo $data["proveedor"]  ?> </td>
                            <td> <?php echo $data["contacto"]  ?> </td>
                            <td> <?php echo $data["telefono"]  ?> </td>
                            <td> <?php echo $data["direccion"]  ?> </td> 
                            <td> <?php echo $fecha->format('d-m-Y') ?> </td> 
                            <td>
                                <a class="link_edit" href="editar_proveedor.php?id=<?php echo $data["codproveedor"]; ?>"> 
                                <i class="fa-solid fa-pen-to-square"></i> Editar </a>
                               
                                |
                                <a class="link_delete" href="eliminar_confirmar_proveedor.php?id=<?php echo $data["codproveedor"]; ?>">
                                <i class="fa-solid fa-trash-can"></i> Eliminar</a>
                              
                            </td>
                        </tr> 
            <?php
                    }
        
                }
            ?> 
        </table>

        <?php
            // Validacion para saber si existe coincidencia, en caso de que no no muestra nada
            if($total_registro != 0){ 
        ?>
            <div class="paginador"> 
                <ul>
                    <?php
                    //Muestra los dos primeros botones para navegar entre las paginas 
                        if($pagina != 1 ){ 
                            ?> 
                            <li><a href="?pagina=<?php echo 1; ?>&busqueda=<?php echo $busqueda; ?>"> |< </a></li>
    
                            <li><a href="?pagina=<?php echo $pagina - 1; ?>&busqueda=<?php echo $busqueda; ?>"> << </a></li>
                        
                            <?php  
                        }
                        //Ciclo for que muestra el total de paginas y permite navegar entre ellas 
                        for($i=1; $i <= $total_paginas; $i++){
                            //Condicional que igual la posicion con la pagina seleccionada para señalar en que pagina estoy ubicado
                            if($i == $pagina){
                                echo '<li class="pageSelected"> '.$i.' </li> ';
                            }else {
                                echo '<li><a href="?pagina='.$i.'&busqueda='.$busqueda.'">'.$i.'</a></li> ';
                            } 
                        }

                        //Muestra los dos botones finales para navegar entre las paginas 
                        if($pagina != $total_paginas){    
                        ?> 
                            <li><a href="?pagina=<?php echo $pagina + 1; ?>&busqueda=<?php echo $busqueda; ?>">  >> </a></li>
            
                            <li><a href="?pagina=<?php echo $total_paginas ?>&busqueda=<?php echo $busqueda; ?>"> >| </a></li>
                        <?php  
                        }
                        ?>
                </ul>
            </div>
        <?php 
            } 
        ?>

	</section>


	<!--Llamar al pie de pagina-->
	<?php
		include"includes/footer.php";
	?>
</body>
</html> 
