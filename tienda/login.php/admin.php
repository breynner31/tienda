<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../recursos.styles/admin.css">
    <script src="https://kit.fontawesome.com/0dd34c83b9.js" crossorigin="anonymous"></script>
    <title>Panel de administrador</title>
</head>
<body>
<header>
        <nav class="navbar">
            <div class="navbar-brand">
               <label>Bienvenido administrador <i class="fa-solid fa-door-open"></i></label>
            </div>
            <button class="navbar-toggle" onclick="toggleMenu()">&#9776;</button>
            <ul class="navbar-menu">
                <li><a href="admin.php">Inicio</a></li>
                <li class="dropdown">
                        <li><a href="../productos/inventario.php">Inventario</a></li> 
                </li>
                <li class="dropdown">
                <li><a href="../productos/registro_productos.php">Registrar prendas de ropa</a></li>
                <li class="dropdown">
                        <li><a href="#">Pedidos</a></li> 
                </li>
                </li>
                <li><a href="../index.php">Cerrar sesión</a></li>
            </ul>
        </nav>
    </header>
    <div class="content">
        <h1>Panel de control   <i class="fa-solid fa-user-tie"></i></h1>
    </div>

    <script src="../recursos.styles/script.js"></script>
</body>
</html>