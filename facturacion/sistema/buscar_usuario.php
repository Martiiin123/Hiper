<?php

    session_start();
    // Impide buscar si no se tiene el rol de administrador 
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
        <?php
            //Convierte la cadena a minusculas y redirige a la lista principal si no hay coincidencias
            $busqueda =strtolower($_REQUEST['busqueda']);
            if(empty($busqueda)){
                header("location: lista_usuarios.php");
                mysqli_close($conection);
            }
        ?>

		<h1><i class="fa-solid fa-users-viewfinder"></i>  Lista de Usuarios</h1> 
        <a href="registro_usuario.php" class="btn_new" > <i class="fa-solid fa-user-plus"></i> Crear Usuario </a> 

        <!--Llamar al menu de la cabezera -->
        <form action="buscar_usuario.php" method="get" class="form_search"> 
            <input type="text" name="busqueda" id="busqueda" placeholder="Buscar" value="<?php echo $busqueda; ?>">
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

            //Convierte la palabra que se esta buscando en caso de que sea alguno de la columna de rol
            $rol = '';
            if($busqueda == 'administrado'){
                $rol = "or rol like '%1%' ";
            }else if($busqueda =='supervisor'){
                $rol = "or rol like '%2%'";
            }else if($busqueda =='vendedor'){
                $rol = "or rol like '%3%'";
            }

            //Cuanta los usuarios registrados creando una tabla temporar que almcena el numero 
            //Utilizaz la variable busqueda en todos los campos para encontra coincidencia 
            $sql_register = mysqli_query($conection, "select count(*) as total_registros from usuario
                                                                where  (
                                                                idusuario like '%$busqueda%' or
                                                                nombre like '%$busqueda%' or  
                                                                correo like '%$busqueda%' or   
                                                                usuario like '%$busqueda%' 
                                                                $rol
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


            // Llama a los campos que se mostran en la tabla de usuarios registrados
            // Se hace un inner join para obtner el rol de la tabla rol
            // Solo muestra los usuario con estado 1 (No eliminados)
            // Muestra los usuarios que cumplan con el campo de busqueda
                $query = mysqli_query($conection, "select u.idusuario, u.nombre, u.correo, u.usuario, r.rol from usuario u    
                inner join rol r on u.rol = r.idrol 
                where (
                    u.idusuario like '%$busqueda%' or
                    u.nombre like '%$busqueda%' or  
                    u.correo like '%$busqueda%'or   
                    u.usuario like '%$busqueda%' or
                    r.rol like '%$busqueda%'
                    )
                and estatus = 1 
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