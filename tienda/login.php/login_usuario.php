<?php
    include 'conexion.php';
    $nombre=$_POST['nombre'];
    $correo=$_POST['correo'];
    $telefono=$_POST['telefono'];
    $contrasena = $_POST['contrasena'];
    $contrasena = hash('sha512', $contrasena);


    // validamos 
    $validar_login ="SELECT * FROM usuarios WHERE  correo ='$correo' and contrasena='$contrasena' ";
    $resultado = mysqli_query ($conexion,$validar_login);
    $filas = mysqli_fetch_array($resultado);



    if (mysqli_num_rows($resultado)>0){
        
    if($filas['rol_id']==1){
        $_SESSION['correo'] =$contrasena;
        header("location: admin.php");
        exit;
    }
   
    if($filas['rol_id']==0){
        $_SESSION['correo'] =$contrasena;
        header("location: ../productos/bienvenida.php");
        exit;
    }
   


    }
else{        echo '
    <script>

        alert("Usuario no existe, por favor veifique los datos introducidos ");
        window.location ="../index.php";

    </script>

';
exit;

    }
    mysqli_close($conexion);
?>