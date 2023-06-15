<?php

    include("conexion.php");
    $id=$_POST['id'];
    $nombre=$_POST['nombre'];
    $descripcion=$_POST['lugar'];
    $foto=$_POST['foto'];
    $precio=$_POST['precio'];
    $categoria_id=$_POST['categoria_id'];
    $genero_id=$_POST['genero_id'];

    $sql="UPDATE ropa SET nombre='$nombre',precio='$precio', foto='$foto' ,descripcion='$descripcion' , categoria_id='$categoria_id' , genero_id='$genero_id' where id='$id'";
    $query=mysqli_query($conexion,$sql);

        if($query){
            echo '
            <script>
                alert("Editado perfectamnete lo redirigir a la lista de ropa")
                window.location ="inventario.php";
            </script>
        ';
        exit();
        }

?>