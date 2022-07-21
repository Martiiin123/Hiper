<nav>
			<ul>
				<li><a href="#"> <i class="fa-solid fa-certificate"></i> Inicio</a></li> 

				<?php 
					// Oculta el menu de Usuarios si no se es administrador
					if($_SESSION['rol'] == 1){ 
				?>
					<li class="principal">
						<a href="#"> <i class="fa-solid fa-id-card-clip"></i> Usuarios</a>
						<ul>
							<li><a href="registro_usuario.php"> <i class="fa-solid fa-user-plus"></i> Nuevo Usuario</a></li>
							<li><a href="lista_usuarios.php"> <i class="fa-solid fa-users-viewfinder"></i> Lista de Usuarios</a></li>
						</ul>
					</li>
				<?php 
					}
				?>
				<li class="principal">
					<a href="#"> <i class="fa-solid fa-user-tag"></i> Clientes</a>
					<ul>
						<li><a href="registro_cliente.php"> <i class="fa-solid fa-user-plus"></i>  Nuevo Cliente</a></li>
						<li><a href="lista_clientes.php"> <i class="fa-solid fa-users-rectangle"></i> Lista de Clientes</a></li>
					</ul>
				</li>
				<?php 
					// Oculta el menu de Usuarios si no se es administrador
					if($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2 ){ 
				?>
				<li class="principal"> 
					<a href="#"><i class="fa-solid fa-truck-fast"></i> Proveedores</a>
					<ul>
						<li><a href="registro_provedor.php">   <i class="fa-solid fa-cart-flatbed"></i> Nuevo Proveedor</a></li>
						<li><a href="lista_proveedor.php">   <i class="fa-solid fa-boxes-packing"></i> Lista de Proveedores</a></li>
					</ul>
				</li>
				<?php 
					}
				?>
				<li class="principal">
					<a href="#"> <i class="fa-solid fa-cart-shopping"></i> Productos</a>
					<ul>
					<?php 
					// Oculta el menu de Usuarios si no se es administrador
					if($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2 ){ 
					?>
						<li><a href="registro_producto.php"> <i class="fa-solid fa-cart-plus"></i> Nuevo Producto</a></li>
					<?php 
					}
					?>	
						<li><a href="lista_producto.php"> <i class="fa-solid fa-clipboard-list"></i> Lista de Productos</a></li>
					</ul>
				</li>
				<li class="principal">
					<a href="#"> <i class="fa-solid fa-file-invoice-dollar"></i>Facturas</a>
					<ul>
						<li><a href="#"> <i class="fa-solid fa-money-bill-transfer"></i> Nuevo Factura</a></li>
						<li><a href="#"> <i class="fa-solid fa-rectangle-list"></i> Facturas</a></li>
					</ul>
				</li>
			</ul>
		</nav>