<?php
$categoriasInfo = "SELECT * FROM categorias";
$viewDatauser = "SELECT * FROM ropa";
$generoInfo = "SELECT * FROM genero";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="recursos/foto_tta.png">
    <link rel="stylesheet" href="../recursos.styles/registro_productos.css">
    <script src="https://kit.fontawesome.com/0dd34c83b9.js" crossorigin="anonymous"></script>
    <title>Registro de Ropa</title>
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="navbar-brand">
                <a href="#">Bienvenido administrador <i class="fa-solid fa-door-open"></i></a>
            </div>
            <button class="navbar-toggle" onclick="toggleMenu()">&#9776;</button>
            <ul class="navbar-menu">
                <li><a href="../login.php\admin.php">Inicio</a></li>
                <li class="dropdown">
                    <a href="inventario.php">Inventario</a>
                </li>
                <li class="dropdown">
                    <a href="registro_productos.php">Registrar prendas de ropa</a>
                </li>
                <li><a href="../index.php">Cerrar sesión</a></li>
            </ul>
        </nav>
    </header>
    <div class="container">
        <div class="form-container">
            <form action="registro_ropa_be.php" method="POST" enctype="multipart/form-data">
                <h2>Registrar prendas de ropa</h2>
                <input type="text" required placeholder="Nombre de la marca" name="nombre">
                <label>Asigna una Categoría</label>
                <select class="js-example-basic-single" style="width: 100%" name="categorias" id="categorias">
                    <?php
                    include("conexion.php");
                    $result = mysqli_query($conexion, $categoriasInfo);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<option value="' . $row["id"] . '">' . $row["descripcion"] . '</option>';
                    }
                    ?>
                </select>
                <textarea type="text" required placeholder="Descripción de la ropa" name="descripcion"></textarea>
                <input type="text" required placeholder="Tallas" name="tallas">
                <label>Asigna un Género</label>
                <select class="js-example-basic-single" style="width: 100%" name="genero" id="genero">
                    <?php
                    include("conexion.php");
                    $result = mysqli_query($conexion, $generoInfo);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<option value="' . $row["id"] . '">' . $row["descripcion"] . '</option>';
                    }
                    ?>
                </select>
                <input type="text" required placeholder="Precio" name="precio">
                <input type="text" placeholder="Porcentaje del descuento " name="porcentaje">
                <input type="file" required name="foto" placeholder="subir">

               
                <div style="display: flex; gap: 10px;">
                    <button class="btn draw-border" type="submit">Registrar</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>