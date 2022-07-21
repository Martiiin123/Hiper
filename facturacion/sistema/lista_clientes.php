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

		<h1> <i class="fa-solid fa-users-rectangle"></i> Lista de Clientes</h1> 
        <a href="registro_cliente.php" class="btn_new" > <i class="fa-solid fa-user-plus"></i> Crear Cliente </a> 

        <!--Llamar al menu de la cabezera -->
        <form action="buscar_cliente.php" method="get" class="form_search"> 
            <input type="text" name="busqueda" id="busqueda" placeholder="Buscar">
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
            $sql_register = mysqli_query($conection, "select count(*) as total_registros from cliente where estatus = 1");
            //Regresa el numero de query numero de cleintes
            $result_register = mysqli_fetch_array($sql_register);
            //Accedemos al dato crea de total de registros
            $total_registro = $result_register['total_registros'];
            //Numero de registros por pagina
            $por_pagina =5;
            

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
                $query = mysqli_query($conection, "select *from cliente
                where estatus = 1 
                order by idcliente ASC   
                limit $desde, $por_pagina ");
                
                mysqli_close($conection);
                 
                //Cuenta la cantidad de registros
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
                                <i class="fa-solid fa-pen-to-square"></i>Editar </a>
                               

                                <?php  
                                // Habilitar la opcion de eliminar solo para administradores
                                 if($_SESSION['rol'] ==1 ) {  ?> 
                                |
                                <a class="link_delete" href="eliminar_confirmar_clientes.php?id=<?php echo $data["idcliente"]; ?>">
                                <i class="fa-solid fa-trash-can"></i> Eliminar</a>
                                <?php } ?>
                            </td>
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