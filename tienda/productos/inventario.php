<?php
// Verificar si se ha enviado el formulario para habilitar/inhabilitar un producto
if (isset($_POST['producto_id']) && isset($_POST['estado'])) {
    $producto_id = $_POST['producto_id'];
    $estado = $_POST['estado'];

    // Realizar las operaciones necesarias en la base de datos para habilitar/inhabilitar el producto
    include("conexion.php");

    // Preparar la consulta
    $stmt = mysqli_prepare($conexion, "UPDATE ropa SET estado = ? WHERE id = ?");

    // Verificar si la preparación de la consulta fue exitosa
    if ($stmt) {
        // Vincular los parámetros
        mysqli_stmt_bind_param($stmt, "ii", $estado, $producto_id);

        // Ejecutar la consulta
        mysqli_stmt_execute($stmt);

        // Cerrar la declaración
        mysqli_stmt_close($stmt);
    }

    // Cerrar la conexión
    mysqli_close($conexion);

    // Redirigir al mismo archivo para evitar el reenvío del formulario
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../recursos.styles/inventario.css" class="style">
    <title>Document</title>
    <header>
        <nav class="navbar">
            <div class="navbar-brand">
                <label >Bienvenido administrador<i class="fa-solid fa-door-open"></i></label> 
            </div>
            <button class="navbar-toggle" onclick="toggleMenu()">&#9776;</button>
            <ul class="navbar-menu">
                <li><a href="../login.php\admin.php">Inicio</a></li>
                <li class="dropdown">
                    <a href="inventario.php">Inventario</a>
                </li>
                <li class="dropdown">
                    <a href="../productos/registro_productos.php">Registrar prendas de ropaa</a>
                </li>
                <li class="dropdown">
                        <li><a href="#">Pedidos</a></li> 
                </li>
                <li><a href="../index.php">Cerrar sesión</a></li>
            </ul>
        </nav>
    </header>
</head>
<body>
<center>
    <div class="container">
        <h3 class="container-heading">INVENTARIO</h3>
        <form class="mb-3" method="GET">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Buscar prendas de ropa " name="buscar" value="<?php echo isset($_GET['buscar']) ? $_GET['buscar'] : ''; ?>">
                <button class="btn btn-primary" type="submit">Buscar</button>
            </div>
        </form>
        
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
        
        <h3 class="container-heading">Lista de prendas de ropa </h3>
        <table class="table table-dark table-hover" border="3">
            <thead class="table bg-warning table-striped">
                <tr>
                   <th>id</th>
                    <th>Nombre de la marca</th>
                    <th>Categorias</th>
                    <th>Descripcion</th>
                    <th >Tallas</th>
                    <th>Generos</th>
                    <th>Precio</th>
                    <th>Porcentaje</th>
                    <th>total</th>
                    <th>Foto</th>
                    <th colspan="3">Edicion</th>
                </tr>
            </thead>
            <tbody>
                <?php
                     include("conexion.php");
                     $elementosPorPagina = 10; // Define el número de elementos por página
                     $paginaActual = isset($_GET['pagina']) ? $_GET['pagina'] : 1; // Obtiene la página actual desde la URL
                     $indiceInicial = ($paginaActual - 1) * $elementosPorPagina; // Calcular el índice inicial del primer elemento en la página actual
                     
                     $sqlCount = "SELECT COUNT(*) AS total FROM ropa";
                     $sql = "SELECT r.id, r.nombre, r.descripcion, r.precio, r.foto,r.estado,r.tallas,r.descuento,r.porcentaje, c.descripcion AS categoria, g.descripcion AS genero
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
                
                while ($row = mysqli_fetch_assoc($query)) {

                    ?>
     
                    <tr>
                        <td><?php echo $row['id'] ?></td>
                        <td><?php echo $row['nombre'] ?></td>
                        <td><?php echo $row['categoria'] ?></td>
                        <td><?php echo $row['descripcion'] ?></td>
                        <td><?php echo $row['tallas'] ?></td>
                        <td><?php echo $row['genero'] ?></td>
                        <td><?php echo $row['precio'] ?></td>
                        <td><?php echo $row['porcentaje'] ?></td>
                        <td><?php 
                        $porcentaje= $row['porcentaje'];
                        $precio =$row['precio'];
                        $precio_desc = (floatval($porcentaje) * floatval($precio) / 0.1); echo $precio_desc?></td>  
                        <td><?php $imagen= $row['foto'];echo "<img src='$imagen'  width='300'>"; ?></td>
                        <td>
                            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                <input type="hidden" name="producto_id" value="<?php echo $row['id']; ?>">
                                <?php if ($row['estado'] == 1) : ?>
                                    <input type="hidden" name="estado" value="0">
                                    <button type="submit" class="btn btn-danger">Inhabilitar</button>
                                <?php else : ?>
                                    <input type="hidden" name="estado" value="1">
                                    <button type="submit" class="btn btn-success">Habilitar</button>
                                <?php endif; ?>
                            </form>
                        </td>
                        <td><a href="editar.php?id=<?php echo $row['id'] ?>"><button type="button" class="btn btn-info btn default">Editar</button></a></td>
                        <td><a href="eliminar.php?id=<?php echo $row['id'] ?>"><button type="button" class="btn btn-danger btn default">Eliminar</button></a></td>
                    </tr>
                <?php
                }
                mysqli_close($conexion);
                ?>
            </tbody>
        </table>

       
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

</body>

