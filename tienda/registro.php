<!doctype html>
<html lang="es">
<head>
  <title>Registrarse</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
  <link href="css/style.css" rel="stylesheet">
</head>
<body class="d-flex align-items-center">
  <div class="container-fluid">
    <div class="row justify-content-center" style="margin:20px;">
      <div class="col-lg-6 col-md-8 login-box">
        <div class="col-lg-12 login-title">
          Registrarse
        </div>
        <div class="col-lg-12 login-form">
          <form action="login.php/registro_usuario_be.php" method="POST"  action="" class="formulario__login">
            <div class="form-group">
              <label class="form-control-label">Nombre de usuario</label>
              <input type="text" class="form-control" type="text" minlength="4" maxlength="8" size="10" required placeholder="Nombre(4 o 8 caracteres): " name ="nombre" > 
            </div>
            <div class="form-group">
              <label class="form-control-label">Email</label>
              <input type="email" class="form-control" required="@gmail.com" placeholder="Correo Electronico" name ="correo">
            </div>
            <div class="form-group">
              <label class="form-control-label">Numero de telefono</label>
              <input type="text" class="form-control" required placeholder="Telefono" name ="telefono">
            </div>
            <div class="form-group">
              <label class="form-control-label">Contraseña</label>
              <input type="password" class="form-control" required placeholder="Contraseña" name ="contrasena">
              <label class="form-control-label">Confirmar Contraseña</label>
              <input type="password" class="form-control" required placeholder="Confirmar Contraseña" name ="confirmar_contrasena">
            </div>
            <div class="col-12 login-btm login-button justify-content-center d-flex">
              <button type="submit" class="btn btn-outline-primary">Crear cuenta</button>
              <button type="button" class="btn btn-outline-primary" onclick="window.location.href='index.php'">Inicio</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
    integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
  </script>
</body>
</html>