<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$servidor = 'localhost';
$usuario = 'root';
$clave = '';
$baseDeDatos = 'industro_uno';

$enlace = mysqli_connect($servidor, $usuario, $clave, $baseDeDatos);

if (!$enlace) {
    die("Error de conexión: " . mysqli_connect_error());
}

$enlace->set_charset("utf8");

$mensajeExito = '';
$mensajeError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmar'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $cargo = $_POST['cargo'];
    $clave = $_POST['password'];
    $confirmarClave = $_POST['confirmPassword'];

    if (empty($username) || empty($email) || empty($cargo) || empty($clave) || empty($confirmarClave)) {
        $mensajeError = '<div class="alert alert-danger mx-auto custom-alert" role="alert"><b>Error: Faltan campos por rellenar</b></div>';
    } else if ($clave !== $confirmarClave) {
        $mensajeError = '<div class="alert alert-danger mx-auto custom-alert" role="alert"><b>Las contraseñas ingresadas no coinciden</b></div>';
    } else if (strlen($clave) < 8) {
        $mensajeError = '<div class="alert alert-danger mx-auto custom-alert" role="alert"><b>Error: La contraseña debe tener al menos 8 caracteres</b></div>';
    } else {
        $consulta = "SELECT * FROM registro WHERE nomUsuario = ? AND email = ? AND id_rol = ?";
        $stmt = $enlace->prepare($consulta);
        $stmt->bind_param("ssi", $username, $email, $cargo);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 1) {
            
            $claveHash = password_hash($clave, PASSWORD_DEFAULT);

            
            $actualizar = "UPDATE registro SET password = ? WHERE nomUsuario = ? AND email = ? AND id_rol = ?";
            $stmtUpdate = $enlace->prepare($actualizar);
            $stmtUpdate->bind_param("sssi", $claveHash, $username, $email, $cargo);
            $stmtUpdate->execute();

            $mensajeExito = '<div class="alert alert-success">Contraseña actualizada correctamente</div>';
        } else {
            $mensajeError = '<div class="alert alert-danger">Usuario no encontrado</div>';
        }
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/working/css/styles2.css">
    <link rel="icon" href="/working/imagenes/logo_industro_.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/8fe42e8551.js" crossorigin="anonymous"></script>
    <title>Cambio de Contraseña - Industro</title>
</head>
<body>
    <div class="MainContenedor">
        <div class="head_container">
            <img src="/working/imagenes/logo_industro_.png" alt="Logo Industro" class="logo">
            <div class="heading">CAMBIO DE CONTRASEÑA</div>
        </div>
        
        <form action="#" method="post" class="RecuperacionForm" id="recuperacionForm">
            <?php 
            echo $mensajeExito;
            echo $mensajeError; 
            ?>
            <div class="inputIcon">
                <i class="fa-solid fa-user"></i>
                <input type="text" id="nombre" name='username' class="LoginInput" placeholder="Nombre de usuario" required>
            </div>
            
            <div class="inputIcon">
                <i class="fa-solid fa-envelope"></i>
                <input type="email" id="email" name ='email'class="LoginInput" placeholder="Correo electrónico" required>
            </div>
            
            <div class="inputIcon">
                <i class="fa-solid fa-building-user"></i>
                <select  id="area" name='cargo' required class="LoginInput">
                    <option value="" disabled selected hidden>Área/Campo</option>
                    <option value="1">Administrador</option>
                    <option value="2">Empleado</option>
                </select>
            </div>

            <div class="inputIcon">
                <i class="fa-solid fa-lock"></i>
                <input type="password" id="nuevaPassword" name='password' class="LoginInput" placeholder="Nueva contraseña" required>
            </div>
            
            <div class="inputIcon">
                <i class="fa-solid fa-lock"></i>
                <input type="password" id="confirmarPassword" name='confirmPassword' class="LoginInput" placeholder="Confirmar nueva contraseña" required>
            </div>
            
            <input type="submit" name='confirmar' class="LButtom" id="cambioPasswordButton" value="Confirmar">

            <div class="VolverLogin" style="margin-top: 30px;">
               <span class="Forgot-Password"><a href="/working/login/login_es.php" ><b>Iniciar sesión</a></span>
            </div>
        </form>
    </div>
</body>
</html>