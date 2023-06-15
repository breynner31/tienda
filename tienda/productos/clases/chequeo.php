<?php
include '../conexion.php';
require '../config.php';

$producto = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos'] : null;

$lista_carrito = array();

if ($producto != null) {
    foreach ($producto as $id => $cantidad) {
        $sql = $conexion->prepare("SELECT id, nombre, precio, descuento, ? AS cantidad FROM ropa WHERE id=? AND estado=0");
        $sql->bind_param("ii", $cantidad, $id);
        if ($sql->execute()) {
            $resultado = $sql->get_result()->fetch_assoc();
            if ($resultado) {
                $lista_carrito[] = $resultado;
            } 
        } else {
            echo "Error al ejecutar la consulta: " . $sql->error;
        }
    }
} else {
    echo "No hay productos en el carrito";
}

$total = 0;
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda online</title>
    <link rel="stylesheet" href="../../recursos.styles/bienvenida.css" class="style">
    <script src="https://kit.fontawesome.com/0dd34c83b9.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <header>
        <nav class="navbar">
            <div class="navbar-brand">
                <h4 class="container-heading">Tienda online<i class="fab fa-shopify"></i></h4>
            </div>
            <form class="mb-3" method="GET">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Buscar prendas de ropa" name="buscar" value="<?php echo isset($_GET['buscar']) ? $_GET['buscar'] : ''; ?>">
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
                        <option value="pantalon">Pantalón</option>
                        <option value="gorras">Gorras</option>
                        <option value="zapatos">Zapatos</option>
                        <option value="medias">Medias</option>
                        <option value="trajes de baño">Trajes de baño</option>
                        <option value="pijamas de mujer">Pijamas de mujer</option>
                        <option value="pijamas de hombre">Pijamas de hombre</option>
                        <option value="chaquetas">Chaquetas</option>
                        <option value="busos">Busos</option>
                    </select>

                    <button type="submit">Filtrar</button>
                </form>
                <a href="#" class="fas fa-cart-shopping"> Carrito de compras</a>
                <span id="num_cart" class="badge bg-secondary"><?php echo $num_cart; ?></span>
                <li><a href="../index.php">Cerrar sesión</a></li>
            </ul>
        </nav>
    </header>
    <center>
        <main>
            <div class="container">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Precio</th>
                                <th>Cantidad</th>
                                <th>Subtotal</th>
                                <th>Eliminar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($lista_carrito == null) {
                               echo '<tr><td colspan="5" class="text-center">Lista vacía</td></tr>';
                            } else {
                                foreach ($lista_carrito as $producto) {
                                    $_id = $producto['id'];
                                    $nombre = $producto['nombre'];
                                    $precio = $producto['precio'];
                                    $cantidad = $producto['cantidad'];
                                    $porcentaje = $producto['descuento'];
                                    $precio_desc = floatval($porcentaje) * floatval($precio) / 0.1;
                                    $subtotal = $precio * $cantidad ;
                                    $total = $subtotal;
                            ?>
                                    <tr>
                                        <td><?php echo $nombre; ?></td>
                                        <td><?php echo MONEDA . number_format($precio, 2, ',', '.'); ?></td>
                                        <td><input type="number" min="1" max="10" step="1" value="<?php echo $cantidad ?>" size="5" id="cantidad<?php echo $_id; ?>" onchange="actualizaCantidad(this.value, <?php echo $_id; ?>)"></td>
                                        <td>
                                            <div class="subtotal_<?php echo $_id; ?>" name="subtotal[]"><?php echo MONEDA . number_format($subtotal, 2, ',', '.'); ?></div>
                                        </td>
                                        <td><a href="#" id="eliminar" class="btn btn-warning btn-sm" data-bs-id="<?php echo $_id; ?>" data-bs-toggle="modal" data-bs-target="#eliminaModal">Eliminar</a></td>
                                    </tr>
                            <?php }
                            } ?>
                            <tr>
                                <td colspan="3"></td>
                                <td colspan="2">
                                    <p class="h3" id="total"><?php echo MONEDA . number_format($total, 2, ',', '.'); ?></p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                            <button> <a href="../../pago.php"> pago</a></button>
            </div>
        </main>
        <!-- Botón para abrir el modal -->

        <!-- Modal -->
        <div class="modal fade" id="eliminaModal" tabindex="-1" aria-labelledby="#eliminaModal" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="#eliminaModal">Alerta</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        ¿Deseas eliminar el producto de la lista?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button id="btn-elimina"type="button" class="btn btn-danger" onclick="eliminar()">Eliminar</button>
                    </div>
                </div>
            </div>
        </div>
    </center>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../recursos.js/bienvenida.js"></script>
    <script>
        let eliminaModal = document.getElementById('eliminaModal');
        eliminaModal.addEventListener('show.bs.modal', function(event){
            let button = event.relatedTarget;
            let id = button.getAttribute('data-bs-id');
            let buttonElimina = eliminaModal.querySelector('.modal-footer #btn-elimina');
            buttonElimina.value = id;
        });
        
        // Configurar nuestra petición en Ajax
        function actualizaCantidad($cantidad, $_id) {
            let url = 'actualizar_carrito.php';
            let formData = new FormData();
            formData.append('action', 'agregar');
            formData.append('id', $_id);
            formData.append('cantidad', $cantidad);
            
            // Enviar los datos mediante el método POST
            fetch(url, {
                method: 'POST',
                body: formData,
                mode: 'cors'
            }).then(response => response.json())
                .then(data => {
                    if (data.ok) {
                        let divsubtotal = document.getElementById('subtotal_' + $_id);
                        divsubtotal.innerHTML = data.sub;

                        let total = 0.00;
                        let list = document.getElementsByName('subtotal[]');

                        for (let i = 0; i < list.length; i++) {
                            total += parseFloat(list[i].innerHTML.replace(/[$,]/g,''));
                        }

                        total = new Intl.NumberFormat('COL$.', {
                            minimumFractionDigits : 2
                        }).format(total);

                        document.getElementById('total').innerHTML = '<?php echo MONEDA; ?>' + total;
                    }
                });
        }

        function eliminar() {
            let botonElimina = document.getElementById('btn-elimina');
            let id = botonElimina.value;

            let url = 'actualizar_carrito.php';
            let formData = new FormData();
            formData.append('action', 'eliminar');
            formData.append('id', id);
            
            // Enviar los datos mediante el método POST
            fetch(url, {
                method: 'POST',
                body: formData,
                mode: 'cors'
            }).then(response => response.json())
                .then(data => {
                    if (data.ok) {
                        location.reload();
                    }
                });
        }
    </script>
</body>
</html>