<?php
require 'config.php'; // Incluye el archivo de configuración
require 'conexion.php'; // Incluye el archivo de conexión a la base de datos


$id = isset($_GET['id']) ? $_GET['id'] : ''; // Obtiene el valor del parámetro 'id' de la URL
$token = isset($_GET['token']) ? $_GET['token'] : ''; // Obtiene el valor del parámetro 'token' de la URL

if($id == '' || $token == ''){
    // Si el 'id' o 'token' están vacíos, muestra un mensaje de error y termina la ejecución
    echo 'error al procesar la peticion';
    exit;
} else {
    $token_tmp = hash_hmac('sha1', $id, KEY_TOKEN); // Genera un token temporal basado en el 'id' y la clave

    if ($token == $token_tmp) { // Compara el token recibido con el token temporal generado
        $sql = $conexion->prepare("SELECT COUNT(id) FROM ropa WHERE id=? AND estado=1");
        $sql->bind_param("i", $id); // Vincula el parámetro 'id' a la consulta preparada
        $sql->execute(); // Ejecuta la consulta
        $sql->bind_result($count); // Vincula el resultado de la consulta a la variable '$count'
        $sql->fetch(); // Obtiene el resultado de la consulta

        if ($count > 0) {
            $sql = $conexion->prepare("SELECT nombre, descripcion, precio FROM ropa WHERE id=? AND estado=1 LIMIT 1");
            $sql->bind_param("i", $id); // Vincula el parámetro 'id' a la consulta preparada
            $sql->execute(); // Ejecuta la consulta
            $sql->bind_result($nombre, $descripcion, $precio); // Vincula los resultados de la consulta a las variables
            
        }
        
        // ...
    }
}
?>
<?php

    include 'conexion.php';
if (!isset($_SESSION['correo'])) {
    echo '
    <script>
        alert("Inicie sesión, por favor verifique los datos introducidos.");
        window.location = "../index.php";
    </script>';
}
?>

<?php 
    include 'conexion.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda online</title>
    <link rel="stylesheet" href="../recursos.styles/bienvenida.css" class="style">

    <script src="https://kit.fontawesome.com/0dd34c83b9.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<header>
        <nav class="navbar">
            <div class="navbar-brand">
            <h4 class="container-heading">Tienda online<i class="fa-brands fa-shopify"></i></h4>
            </div>
          
            <button class="navbar-toggle" onclick="toggleMenu()">&#9776;</button>
            <ul class="navbar-menu">
            <a href="#" class="fa-solid fa-cart-shopping" >   Carrito de compras</a><span id="num_cart" class="badge bg-secondary" ><?php echo $num_Cart; ?>  </span>
            <li><a href="../index.php">Cerrar sesión</a></li>
            </ul>
        </nav>
    </header>
<center>
      
        <h3 class="container-heading">Nuestros productos</h3>
        <div class="productos-container">
                <?php
                    include("../login.php/conexion.php");
                $elementosPorPagina = 10; // Define el número de elementos por página
                $paginaActual = isset($_GET['pagina']) ? $_GET['pagina'] : 1; // Obtiene la página actual desde la URL
                $indiceInicial = ($paginaActual - 1) * $elementosPorPagina; // Calcular el índice inicial del primer elemento en la página actual
                
                $sqlCount = "SELECT COUNT(*) AS total FROM ropa";
                $sql = "SELECT r.id, r.nombre, r.descripcion, r.precio, r.foto,r.estado, c.descripcion AS categoria, g.descripcion AS genero
                        FROM ropa AS r
                        JOIN categorias AS c ON r.categoria_id = c.id
                        JOIN genero AS g ON r.genero_id = g.id";

                $filtroGenero = isset($_GET['genero']) ? $_GET['genero'] : '';
                $filtroCategoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';

                $whereClause = '';

                if (!empty($filtroGenero)) {
                    $whereClause .= " WHERE g.descripcion = '$filtroGenero'";
                }

                if (!empty($filtroCategoria)) {
                    $whereClause .= !empty($whereClause) ? " AND c.descripcion = '$filtroCategoria'" : " WHERE c.descripcion = '$filtroCategoria'";
                }

                $sql .= $whereClause;

                if (isset($_GET['buscar'])) {
                    $busqueda = $_GET['buscar'];
                    $sqlCount = "SELECT COUNT(*) AS total FROM ropa WHERE nombre LIKE '%$busqueda%'";
                    $sql .= " WHERE r.nombre LIKE '%$busqueda%'";
                    
                    if (!empty($filtroGenero)) {
                        $sql .= " AND g.descripcion = '$filtroGenero'";
                    }

                    if (!empty($filtroCategoria)) {
                        $sql .= " AND c.descripcion = '$filtroCategoria'";
                    }
                }

                // Obtener la cantidad total de tiendas
                $queryCount = mysqli_query($conexion, $sqlCount);
                $row = mysqli_fetch_assoc($queryCount);
                $totalElementos = $row['total'];

                // Calcular la cantidad total de páginas
                $totalPaginas = ceil($totalElementos / $elementosPorPagina);

                // Obtener las tiendas paginadas y ordenadas alfabéticamente
                $sql .= " ORDER BY nombre ASC LIMIT $indiceInicial, $elementosPorPagina";

                $query = mysqli_query($conexion, $sql);
                ?>
                <div class="productos-container">
                <div class="productos-container">
                <main>
    <div class="container">
        <div class="row">
            <div class="col-md-6 order-md-1">
                <div class="foto">
                    <?php
                    include("conexion.php");
                    
                    $id=$_GET['id'];
                    
                    $sql="SELECT * FROM ropa WHERE id='$id'";
                    $query=mysqli_query($conexion,$sql);
                    if ($row = mysqli_fetch_assoc($query)) {
                        $imagen = $row['foto'];
                        echo "<img src='$imagen' width='300'>";
                    }
                    ?>
                  
                </div>
                <div class="container-2">
                    <div class="nombre">
               <h2 ><?php 
               $nombre=$row['nombre'];
                echo $nombre ?></h2>
                    </div>
                <div class="precio">
                <?php 
                            include 'conexion.php';
                            $nombre = $row['nombre'];
                            $precio = $row['precio'];
                            $descripcion = $row['descripcion'];                            $tallas = $row['tallas'];
                            $descuento= $row['descuento'];

                $porcentaje=$row['porcentaje'];
                if( $porcentaje>0 ){ ?>
                    <p><del><?php 
                echo MONEDA. number_format($precio,2,'.',','); ?>
                </del>
                </p>
                </div>
                <div class="descuento">
                  <h2>
                  <small class="text-success">  <?php echo $porcentaje; ?>% descuento</small>
                    <?php 
                echo MONEDA. number_format($descuento); ?>
                
                </h2>
                </div>
                <div class="precio-2">
                <?php }else{ ?>
                    <h2> 
                        <?php echo MONEDA . number_format($precio,2,'.',',');?>
                    </h2>
                <?php } ?>
                </div>
                
                <div class="descripcion">
                <p>
                    <?php echo $row['descripcion'];?>
                </p>
                </div>
                </div>
                <div class="botones-shop">
                <button class="btn btn-primary" type="button"  >Comprar ahora</button>
                <button class="btn btn-outline-primary" type="button" onclick="addProducto(<?php echo $id; ?>, '<?php echo $token_tmp; ?>')">Agregar al carrito</button>
                </div>
            </div>
        </div>
    </div>
</main>
</div>
       
        <div class="pagination">
            <?php if ($totalPaginas > 1) : ?>
                <a href="?pagina=1" class="pagination-link <?php echo $paginaActual == 1 ? 'active' : ''; ?>">Primera</a>
                <?php if ($paginaActual > 1) : ?>
                    <a href="?pagina=<?php echo $paginaActual - 1; ?>" class="pagination-link">&laquo;</a>
                <?php endif; ?>
                <?php for ($i = 1; $i <= $totalPaginas; $i++) : ?>
                    <a href="?pagina=<?php echo $i; ?>" class="pagination-link <?php echo $paginaActual == $i ? 'active' : ''; ?>"><?php echo $i; ?></a>
                <?php endfor; ?>
                <?php if ($paginaActual < $totalPaginas) : ?>
                    <a href="?pagina=<?php echo $paginaActual + 1; ?>" class="pagination-link">&raquo;</a>
                <?php endif; ?>
                <a href="?pagina=<?php echo $totalPaginas; ?>" class="pagination-link <?php echo $paginaActual == $totalPaginas ? 'active' : ''; ?>">Última</a>
            <?php endif; ?>
        </div>

    </div>
</center>
<div class="pagination">
            <?php
            // Generar enlaces de paginación
            for ($i = 1; $i <= $totalPaginas; $i++) {
                echo ' <a href="?pagina=' . $i . '" class="btn btn-primary"> actualizar' . '</a>';
            }
            ?>
        </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../recursos.styles\bienvenida.js"></script>

   
  <script>
                // configurar nuestra peticion en ajax
                function addProducto(id, token) {
                    let url ='clases/carrito.php'
                    let formData = new FormData()
                    formData.append('id', id)
                    formData.append('token', token)
                    // aca enviamos los datos mediante el metodo post
                    fetch(url, {
                        method: 'POST',
                        body: formData,
                        mode:'cors'
                        // aqui nos da un resultado
                    }).then(response => response.json())
                    .then(data => {
                        if(data.ok) {
                            let elemento = document.getElementById("num_cart")
                            elemento.innerHTML = data.numero
                        }
                    })
                }

            </script>


</body>

</html>