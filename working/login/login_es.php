<?php
$servidor = "localhost";
$usuario = "root";
$clave = "";
$baseDeDatos = "industro_uno";

$conexion = mysqli_connect($servidor, $usuario, $clave, $baseDeDatos);
$conexion->set_charset("utf8");

    if (!$conexion) {
        die("Error de conexión: " . mysqli_connect_error());
    }

$mensajeError = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ingresar'])) {
    if (!empty($_POST['ingresar'])){
        $usuario = strtolower(trim($_POST['user'] ?? ''));
        $clave = trim($_POST['pass'] ?? '');
        $rol = intval($_POST['rol'] ?? 0);
        
        if (empty($usuario) || empty($clave) || empty($rol)) {
            $mensajeError = '<div class="alert alert-danger mx-auto custom-alert" role="alert"><b>Todos los campos son requeridos</b></div>';
        } else {
            $stmt = $conexion->prepare("SELECT id, password , id_rol, email FROM registro WHERE nomUsuario = ? AND id_rol = ?");
            $stmt->bind_param("si", $usuario, $rol);
            $stmt->execute();
            $resultado = $stmt->get_result();   

            if ($datos = $resultado->fetch_object()) {
                if (password_verify($clave, $datos->password)){
                    session_start();
                    
                    $_SESSION['usuario'] = $usuario;
                    $_SESSION['id'] = $datos->id;
                    $_SESSION['rol'] = $datos->id_rol;
                    $_SESSION['email'] = $datos->email;

                    if ($datos->id_rol == 1) {  
                        header("location:/working/vistas/admon.php");
                        exit();
                    } elseif ($datos->id_rol == 2) {
                        header("location:/working/vistas/empleado.php");
                        exit();
                    }
                } else {
                    $mensajeError = '<div class="alert alert-danger mx-auto custom-alert" role="alert"><b>Contraseña incorrecta.</b></div>';
                }
            } else {
                $mensajeError = '<div class="alert alert-danger mx-auto custom-alert" role="alert"><b>Usuario o rol no válido.</b></div>';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/working/css/queexistalaguienasi.css">
    <link rel="icon" href="/working/imagenes/logo_industro_.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/8fe42e8551.js" crossorigin="anonymous"></script>
    <title>Industro inicio de sesión</title>
</head>

<body>
    <div class="MainContenedor">    
        <div class="head_container">
            <img src="/working/imagenes/logo_industro_.png" alt="" class="logo">
            <div class="heading">INICIA SESIÓN</div>    
        </div>
        <?php echo $mensajeError;?>
        <form name="inicioSesion"action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="loginForm" autocomplete="off">
            <div class="inputIcon">
                <i class="fa-solid fa-circle-user"></i>
                <input type="text" name="user" class="LoginInput" id="usernameInput" placeholder="Nombre de usuario" autocomplete="off">
                
            </div>
            <div class="inputIcon">
                <i class="fa-solid fa-key"></i>
                <input type="password" name="pass"class="LoginInput" id="passwordInput" placeholder="Contraseña" autocomplete="off">
            </div>
            <div class="SelectContenedor">
                <select name="rol" id="roleSelect"  class="SelectSmall">
                    <option value="" disabled selected hidden>Cargo</option>
                    <option value="1">Administrador</option>
                    <option value="2">Empleado</option>
                </select>
                <select name="lang" id="langSelect" class="SelectSmall">
                    <option value="" disabled selected hidden>Idioma</option>
                    <option value= "ING" id="eng">ING</option>
                    <option value= "ESP" id="esp">ESP</option>
                </select>
            </div>  
            <a class="Forgot-Password" href="/working/authentication/passChange.php">¿Olvidaste tu contraseña?</a>
            <a class="Forgot-Password" href="/working/authentication/registro.php">Crear una cuenta</a>      
            <input type="submit" class="LButtom" id="loginButton" name="ingresar" value="Ingresar">
            
        </form>



    </div>
<script>
langSelect.addEventListener("change", function() {
const selectedLanguage = this.value;
    
    if (selectedLanguage === "ING") {
        window.location.href = "/working/login/login_en.php";
    }
});
</script>
<script>

window.onload = function() {
    document.getElementById("usernameInput").value = "";
    document.getElementById("passwordInput").value = "";
    document.getElementById("roleSelect").selectedIndex = 0;
    document.getElementById("langSelect").selectedIndex = 0;
};
</script>
</body>
</html>

