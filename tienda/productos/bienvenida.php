<?php
include 'conexion.php';
require 'config.php';


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
            <form class="mb-3" method="GET">
                <div class="input-group">
                <input type="text" class="form-control" placeholder="Buscar prendas de ropa " name="buscar" value="<?php echo isset($_GET['buscar']) ? $_GET['buscar'] : ''; ?>">
                <button class="btn btn-primary" type="submit">Buscar</button>
            </div>
            </form>
            <button class="navbar-toggle" onclick="toggleMenu()">&#9776;</button>
            <ul class="navbar-menu">
            <form method="GET">
            <label for="genero">Género:</label>
            <select name="genero" id="genero">
            <option value="">Todos</option>
               <option value="hombre">Hombre</option>
                <option value="mujer">Mujer</option>
                <option value="niño">Niño</option>
                <option value="niña">Niña</option>

            </select>

            <label for="categoria">Categoría:</label>
            <select name="categoria" id="categoria">
                <option value="">Todas</option>
                <option value="camisas">Camisas</option>
                <option value="pantalon">Pantalon</option>
                <option value="gorras">Gorras</option>
                <option value="zapatos">Zapatos</option>
                <option value="medias">Medias</option>
                <option value="trajes de baño">Trajes de baño</option>
                <option value="pijaamas de mujer">Pijamas de muejer</option>
                <option value="pijamas de hombre">Pijamas de hombre</option>
                <option value="chaquetas">Chaquetas</option>
                <option value="busos">Busos</option>
            </select>

            <button type="submit">Filtrar</button>
            </form>
            <a href="clases/chequeo.php" class="fa-solid fa-cart-shopping" >   Carrito de compras</a><span id="num_cart" class="badge bg-secondary" ><?php echo $num_cart; ?>  </span>
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
                $sql = "SELECT r.id, r.nombre, r.descripcion, r.precio, r.foto,r.estado, r.porcentaje, r.descuento, r.tallas, c.descripcion AS categoria, g.descripcion AS genero
                        FROM ropa AS r
                        JOIN categorias AS c ON r.categoria_id = c.id
                        JOIN genero AS g ON r.genero_id = g.id WHERE estado=0" 
                        ;

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
                $sql .= " ORDER BY nombre ASC LIMIT  $indiceInicial, $elementosPorPagina";

                $query = mysqli_query($conexion, $sql);
                ?>
                <div class="productos-container">
                <div class="productos-container">
<?php   
while ($row = mysqli_fetch_assoc($query)) {
?>
<div class="producto">
    <div class="foto">
        <?php $imagen = $row['foto'];
        echo "<img src='$imagen' width='300'>"; ?>
    </div>
    <div class="detalles">
        <div class="detalle">
            <label for="nombre-marca">Nombre de la marca:</label>
            <span class="valor"><?php echo $row['nombre'] ?></span>
        </div>
       
        <div class="detalle">
            <label for="precio">Precio:</label>
            <span class="valor"><?php $precio = $row['precio']; echo MONEDA .number_format($precio,2,'.',',')?></span>
        </div>
        <div class="detalle">
            <label for="categoria">Categoría:</label>
            <span class="valor"><?php echo $row['categoria'] ?></span>
        </div>
        <div class="detalle">
            <label for="porcentaje">Porcentaje:</label>
            <span class="valor"><?php echo $row['porcentaje'] ?></span>
        </div>
        <div class="detalle">
            <label for="tallas">Tallas:</label>
            <span class="valor"><?php echo $row['tallas'] ?></span>
        </div>
        <div class="detalle">
            <label for="descuento">descuento:</label>
            <span class="valor"><?php echo $row['descuento'] ?></span>
        </div>
        <div class="detalle">
            <label for="genero">Género:</label>
            <span class="valor"><?php echo $row['genero'] ?></span>
        </div>
    </div>
    
    <div class="botones">
    
        <div class="btn-group">
      
        <a href="detalles.php?id=<?php echo $row['id']; ?>&token=<?php echo hash_hmac('sha1',$row['id'],KEY_TOKEN);?>"class="boton-detalles" target="_black">detalles</a>
        <button class="btn btn-outline-success" type="button" onclick="addProducto(<?php echo $row['id']; ?>, '<?php echo hash_hmac('sha1',$row['id'],KEY_TOKEN);?>')">Agregar al carrito</button>
          </div>
    </div>
</div>

<?php
}
mysqli_close($conexion);
?>
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
<div class="actualizar">
            <?php
            // Generar enlaces de paginación
            for ($i = 1; $i <= $totalPaginas; $i++) {
                echo ' <a href="?pagina=' . $i . '" class="btn btn-primary"> Actualizar' . '</a>';
            }
            ?>
        </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../recursos.styles/bienvenida.js"></script>
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