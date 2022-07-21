 
<?php 

    //Seccion privada para que siempre sea necesario logearse 
    if(empty($_SESSION['active']))
    {
        header('location: ../'); 
    } 
?>
  <header>
            <div class="header">
                
                <h1>Sistema Facturación</h1>
                <div class="optionsBar">
                    <!--Mostrar fecha con la funcsion   -->
                    <p>México​ ,    <?php echo fechaC(); ?>    </p>
                    <span>|</span>
                    <!-- Mostrar nombre del usuario loggeado  -->
                    <span class="user">     <?php echo $_SESSION['user']; ?>   </span>
 
                    <img class="photouser" src="img/user.png" alt="Usuario">

                    <!--salir de la secion llamando al archivo salir.php --> 
                    <a href="salir.php"><img class="close" src="img/salir.png" alt="Salir del sistema" title="Salir"></a>
                </div>
            </div> 
            <!--Llamar al menu  -->
    <?php
        include"nav.php";
     ?>
     </header>

     <!--Aparece recuadro con los datos del producto seleccionado para agregar  -->
     <div class="modal">
        <div class="bodyModal">
             
        </div>
     </div> 