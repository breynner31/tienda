
<?php

    // incluimos la conexion 

    include 'conexion.php';

    // variables 
    $nombre=$_POST['nombre'];
    $correo=$_POST['correo'];
    $telefono=$_POST['telefono'];
    $contrasena = $_POST['contrasena'];
    $contrasena=hash('sha512',$contrasena);
    $confirmar_contrasena=$_POST['confirmar_contrasena'];
    $confirmar_contrasena=hash('sha512',$confirmar_contrasena);
    $query ="INSERT INTO usuarios(nombre,correo,telefono,contrasena,rol_id,confirmar_contrasena) 
             VALUES('$nombre','$correo','$telefono','$contrasena','0','$confirmar_contrasena')";

    $verificar_usuario =mysqli_query($conexion,"SELECT * FROM usuarios WHERE nombre='$nombre'");

    if(mysqli_num_rows($verificar_usuario)>0){
        echo ' 
            <script>
            alert("Este nombre ya esta registrado, intenta con otro diferente")
            window.location ="../registro.php";
            </script>
        ';
        exit();
    }

    $verificar_correo =mysqli_query($conexion,"SELECT * FROM usuarios WHERE correo='$correo'");

    if(mysqli_num_rows($verificar_correo)>0){
        echo ' 
            <script>
            alert("Este correo ya esta registrado, intenta con otro diferente")
            window.location ="../registro.php";
            </script>
        ';
        exit();
    }

    $verificar_telefono =mysqli_query($conexion,"SELECT * FROM usuarios WHERE telefono='$telefono'");

    if(mysqli_num_rows($verificar_telefono)>0){
        echo ' 
            <script>
            alert("Este telefono ya esta registrado, intenta con otro diferente")
            window.location ="../registro.php";
            </script>
        ';
        exit();
    }
    $verificar_contrasena =mysqli_query($conexion,"SELECT * FROM usuarios WHERE contrasena='$contrasena' != confirmar_contrasena ='$confirmar_contrasena'");
    if(mysqli_num_rows($verificar_contrasena)){
        echo ' 
            <script>
            alert("Esta contraseña  no coinciden , intenta de nuevo ")
            window.location ="../registro.php";
            </script>
        ';
        exit();
    }
    $ejecutar =mysqli_query($conexion,$query);

    if($ejecutar){
        echo '

            <script>
            alert("usuario almacenado exitosamente");
            window.location = "../registro.php";
            </script>
        
        ';
    }else{
        echo '

            <script>
            alert("intentando de nuevo ");
            window.location = "../registro.php";
        
        ';
    }

    mysqli_close($conexion);
?>