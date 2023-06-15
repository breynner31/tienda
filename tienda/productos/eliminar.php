<?php

include("conexion.php");
 
$id=$_GET['id'];

$sql="DELETE FROM  ropa  where id='$id'";
$query=mysqli_query($conexion,$sql);

    if($query){
        echo'
    
        <script>alert("ropa borrado exitosamente");
        window.location ="inventario.php";
        </script>
    
    ';
    
    }


?>