<?php

    session_start();
    // Impide visualizar la lista de usuarios si no se tiene el rol de administrador 
    if($_SESSION['rol'] != 1){
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
	<title>Lista de Usuarios</title>
</head>
<body>
	<!--Llamar al menu de la cabezera -->
	<?php
		include"includes/header.php";
	?>
	<section id="container">

		<h1> <i class="fa-solid fa-users-viewfinder"></i> Lista de Usuarios</h1> 
        <a href="registro_usuario.php" class="btn_new" > <i class="fa-solid fa-user-plus"></i> Crear Usuario </a> 

        <!--Llamar al menu de la cabezera -->
        <form action="buscar_usuario.php" method="get" class="form_search"> 
            <input type="text" name="busqueda" id="busqueda" placeholder="Buscar"> 
            <button type="submit" class="btn_search"> <i class="fa-solid fa-magnifying-glass"></i> </button>
        </form>

        <table>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Usuario</th>
                <th>Rol</th>
                <th>Acciones</th> 
            </tr>
 
            <?php

            //Cuanta los usuarios registrados creando una tabla temporar que almcena el numero 
            $sql_register = mysqli_query($conection, "select count(*) as total_registros from usuario where estatus = 1");
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


            // Llama a los campos que se mostran en la tabla de usuarios registrados
            // Se hace un inner join para obtner el rol de la tabla rol
            // Solo muestra los usuario con estado 1 (No eliminados) 
                $query = mysqli_query($conection, "select u.idusuario, u.nombre, u.correo, u.usuario, r.rol from usuario u    
                inner join rol r on u.rol = r.idrol where estatus = 1 
                order by u.idusuario ASC   
                limit $desde, $por_pagina ");
                
                mysqli_close($conection);
                 
                $result = mysqli_num_rows($query);
                //Si es mayor a uno imprime y llama al while
                if ($result > 0 ){

                    while($data = mysqli_fetch_array($query)){

            ?> 
                        <tr>
                            <td> <?php echo $data["idusuario"]  ?> </td>
                            <td> <?php echo $data["nombre"]  ?> </td>
                            <td> <?php echo $data["correo"]  ?> </td>
                            <td> <?php echo $data["usuario"]  ?> </td>
                            <td> <?php echo $data["rol"]  ?> </td>
                            <td>
                                <a class="link_edit" href="editar_usuarios.php?id=<?php echo $data["idusuario"]; ?>"> 
                                <i class="fa-solid fa-pen-to-square"></i> Editar </a>
                                
                                <!-- Condicional para impedir la eliminacion del usuario principal -->
                                <?php if($data["idusuario"] != 1 ){ 
                                ?>
                                |
                                <a class="link_delete" href="eliminar_confirmar_usuario.php?id=<?php echo $data["idusuario"]; ?>">
                                <i class="fa-solid fa-trash-can"></i> Eliminar</a>
                                <?php }  ?> 
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