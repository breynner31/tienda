<?php


require '../conexion.php';
require '../config.php';

$datos = array(); // Inicializa el array $datos

if(isset($_POST['action'])) {
    $action = $_POST['action'];
    $id = isset($_POST['id']) ? $_POST['id'] : 0;

    if ($action == 'agregar') {
        $cantidad = isset($_POST['cantidad']) ? $_POST['cantidad'] : 0;
        $respuesta = agregar($id, $cantidad);
        if($respuesta > 0) {
            $datos['ok'] = true;
        } else {
            $datos['ok'] = false;
        }
        $datos['sub'] = MONEDA . number_format($respuesta, 2, '.', ',');
    } else if($action == 'eliminar') {
        $datos['ok'] = eliminar($id);
    }  
    else {
        $datos['ok'] =false;
    }
}

echo json_encode($datos);

function agregar($id, $cantidad) {
    $res = 0;
    if($id > 0 && $cantidad > 0 && is_numeric($cantidad)) {
        if(isset($_SESSION['carrito']['productos'][$id])) {
            $_SESSION['carrito']['productos'][$id] = $cantidad;
            include '../conexion.php';
            $sql = $conexion->prepare("SELECT nombre, descripcion, precio, descuento,porcentaje FROM ropa WHERE id=? AND estado=0");
            $sql->execute([$id]);
            $row = $sql->fetch(PDO::FETCH_ASSOC);
            $nombre = $row['nombre'];
            $descripcion = $row['descripcion'];
            $precio = $row['precio'];
            $descuento = $row['descuento'];
            $porcentaje = $row['porcentaje'];
        
            $precio_desc = floatval($porcentaje) * floatval($precio) / 0.1;
            $res = $cantidad * $precio_desc;
            return $res;
        } else {
            return $res;
        }
    }
}


function eliminar($id){
    if($id > 0) {
        if (isset($_SESSION['carrito']['productos']{$id})){
            unset($_SESSION['carrito']['productos'][$id]);
            return true;
        }
    } else{
        return false;
    }
}