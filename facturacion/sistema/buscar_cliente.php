<?php

    session_start(); 
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
	<title>Lista de Clientes</title>
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
                header("location: lista_clientes.php");
                mysqli_close($conection);
            }
        ?>

		<h1><i class="fa-solid fa-users-viewfinder"></i>  Lista de Clientes</h1> 
        <a href="registro_cliente.php" class="btn_new" > <i class="fa-solid fa-user-plus"></i> Crear Usuario </a> 

        <!--Llamar al menu de la cabezera -->
        <form action="buscar_cliente.php" method="get" class="form_search"> 
            <input type="text" name="busqueda" id="busqueda" placeholder="Buscar" value="<?php echo $busqueda; ?>">
            <button type="submit" class="btn_search"> <i class="fa-solid fa-magnifying-glass"></i> </button>
        </form>

        <table>
            <tr>
                <th>ID</th>
                <th>RFC</th>
                <th>Nombre</th>
                <th>Telefono</th>
                <th>Direccion</th> 
                <th>Acciones</th> 
            </tr>
 
            <?php
 

            

            //Cuanta los clientes registrados creando una tabla temporar que almcena el numero 
            //Utilizaz la variable busqueda en todos los campos para encontra coincidencia 
            $sql_register = mysqli_query($conection, "select count(*) as total_registros from cliente
                                                                where  (
                                                                idcliente like '%$busqueda%' or
                                                                rfc like '%$busqueda%' or  
                                                                nombre like '%$busqueda%' or  
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


            // Llama a los campos que se mostran en la tabla de clientes registrados 
            // Solo muestra los clientes con estado 1 (No eliminados)
            // Muestra los clientes que cumplan con el campo de busqueda
                $query = mysqli_query($conection, "select * from cliente 
                where (
                    idcliente like '%$busqueda%' or
                    rfc like '%$busqueda%' or  
                    nombre like '%$busqueda%' or  
                    telefono like '%$busqueda%'or   
                    direccion like '%$busqueda%' 
                    )
                and estatus = 1 
                order by idcliente ASC   
                limit $desde, $por_pagina ");

                mysqli_close($conection);
                 
                $result = mysqli_num_rows($query);
                //Si es mayor a uno imprime y llama al while
                if ($result > 0 ){

                    while($data = mysqli_fetch_array($query)){

            ?> 
                        <tr>
                            <td> <?php echo $data["idcliente"]  ?> </td>
                            <td> <?php echo $data["rfc"]  ?> </td>
                            <td> <?php echo $data["nombre"]  ?> </td>
                            <td> <?php echo $data["telefono"]  ?> </td>
                            <td> <?php echo $data["direccion"]  ?> </td> 
                            <td>
                                <a class="link_edit" href="editar_clientes.php?id=<?php echo $data["idcliente"]; ?>"> 
                                <i class="fa-solid fa-pen-to-square"></i> Editar </a>
                                 
                                <?php 
                                // Habilitar la opcion de eliminar solo para administradores
                                if($_SESSION['rol'] ==1 ) {  
                                ?>
                                |
                                <a class="link_delete" href="eliminar_confirmar_clientes.php?id=<?php echo $data["idcliente"]; ?>">
                                <i class="fa-solid fa-trash-can"></i> Eliminar</a>
                                <?php }  ?> 
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
