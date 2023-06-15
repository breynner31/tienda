<?php
// incluimos la conexión
include 'conexion.php';

// variables
$nombre = $_POST['nombre'];
$precio = $_POST['precio'];
$foto = $_FILES['foto']['name'];
$foto_tmp = $_FILES['foto']['tmp_name'];
$descripcion = $_POST['descripcion'];
$categoria = $_POST['categorias'];
$genero = $_POST['genero'];
$tallas = $_POST['tallas'];
$porcentaje= $_POST['porcentaje'];
$precio_desc = (floatval($porcentaje) * floatval($precio) / 0.1);
// mover la imagen a la ruta
$ruta_imagen = 'img/' . $foto;
move_uploaded_file($foto_tmp, $ruta_imagen);

$query = "INSERT INTO ropa (nombre, precio, foto, descripcion, categoria_id, genero_id,tallas,porcentaje) VALUES ('$nombre', '$precio', '$ruta_imagen', '$descripcion', '$categoria', '$genero','$tallas','$porcentaje')";

// verificar si el nombre ya existe en la base de datos
$verificar_nombre = mysqli_query($conexion, "SELECT * FROM ropa WHERE nombre = '$nombre'");
if (mysqli_num_rows($verificar_nombre) > 0) {
    echo '
        <script>
            alert("Este nombre ya está registrado, intenta con otro diferente");
            window.location = "registro_productos.php";
        </script>
    ';
    exit();
}

// verificar si la foto ya existe en la base de datos
$verificar_foto = mysqli_query($conexion, "SELECT * FROM ropa WHERE foto = '$ruta_imagen'");
if (mysqli_num_rows($verificar_foto) > 0) {
    echo '
        <script>
            alert("Esta foto ya está registrada, intenta con otra diferente");
            window.location = "registro_productos.php";
        </script>
    ';
    exit();
}

// verificar si la descripción ya existe en la base de datos
$verificar_descripcion = mysqli_query($conexion, "SELECT * FROM ropa WHERE descripcion = '$descripcion'");
if (mysqli_num_rows($verificar_descripcion) > 0) {
    echo '
        <script>
            alert("Esta descripción ya está registrada, intenta con otra diferente");
            window.location = "registro_productos.php";
        </script>
    ';
    exit();
}

$ejecutar = mysqli_query($conexion, $query);

if ($ejecutar) {
    echo '
        <script>
            alert("Ropa almacenada exitosamente");
            window.location = "registro_productos.php";
        </script>
    ';
} else {
    echo '
        <script>
            alert("Inténtalo de nuevo");
            window.location = "registro_productos.php";
        </script>
    ';
}

mysqli_close($conexion);
?>