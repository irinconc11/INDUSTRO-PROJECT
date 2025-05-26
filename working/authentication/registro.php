<?php
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    $servidor = "localhost";
    $usuario = "root";
    $clave = "";
    $baseDeDatos = "industro_uno";

    $enlace = mysqli_connect($servidor, $usuario, $clave, $baseDeDatos);

    if (!$enlace) {
        die("Error de conexión: " . mysqli_connect_error());
    }       

    $enlace -> set_charset("utf8");
    

    if(isset($_POST['registro'])){
        $nombre= mysqli_real_escape_string($enlace, $_POST['nombre']);
        $apellido= mysqli_real_escape_string($enlace, $_POST['apellido']);
        $username= mysqli_real_escape_string($enlace,$_POST['username']);
        $email= mysqli_real_escape_string($enlace,$_POST['email']);
        $tipoDocumento= mysqli_real_escape_string($enlace,$_POST['tipoDocumento']);
        $numeroDocumento= mysqli_real_escape_string($enlace,($_POST['numeroDocumento']));
        $password = $_POST['password'];
        $id_rol = (int)$_POST['id_rol'];
        $mensajeError='';
        $mensajeExito='';
        if(strlen($password) < 8){
            $mensajeError='<div class="alert alert-danger mx-auto custom-alert" role="alert"><b>Error: La contraseña debe tener por lo menos 8 caracteres</div>'; 
            
        }elseif (!preg_match('/[A-Z]/', $password)){
            $mensajeError = '<div class="alert alert-danger mx-auto custom-alert" role="alert"><b>Error:</b> La contraseña debe contener al menos una letra mayúscula.</div>';
        }elseif (!preg_match('/[0-9]/', $password)){
            $mensajeError = '<div class="alert alert-danger mx-auto custom-alert" role="alert"><b>Error:</b> La contraseña debe contener al menos un número.</div>';
        }elseif (!preg_match('/[\W_]/', $password)){
            $mensajeError = '<div class="alert alert-danger mx-auto custom-alert" role="alert"><b>Error:</b> La contraseña debe contener al menos un carácter especial.</div>';
        }else{

            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $verificar = $enlace->prepare("SELECT id FROM registro WHERE nomUsuario = ? OR email= ?");
            $verificar->bind_param("ss", $username, $email);
            $verificar->execute();
            $verificar->store_result();

            if($verificar->num_rows > 0){
                $mensajeError='<div class="alert alert-danger mx-auto custom-alert" role="alert"><b>Error: Usuario o correo ya registrados.</div>'; 
                
            }else{
                $verificar->close();
                $stmt= $enlace->prepare("INSERT INTO registro (nombre, apellido, nomUsuario,  email, tipoDocumento, numeroDocumento, password, id_rol)VALUES(?,?,?,?,?,?,?,?)");

                $stmt->bind_param("sssssssi", $nombre, $apellido, $username, $email, $tipoDocumento, $numeroDocumento, $passwordHash, $id_rol);

                if($stmt->execute()){
                    $mensajeExito = '<div class="alert alert-success mx-auto custom-alert" role="alert"><b>Registro exitoso.</b> ¡Ya puedes iniciar sesión!</div>';

                }else{
                    $mensajeError='<div class="alert alert-danger mx-auto custom-alert" role="alert"><b>Error al registrar. </div>'; 
                    echo "<script>alert('Error al registrar: " . addslashes($stmt->error) . "');</script>";
                }
                $stmt -> close();
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
    <title>Registrarse - Industro</title>
</head>
<body>
    <div class="MainContenedor">
       
        <div class="head_container">
            <img src="/working/imagenes/logo_industro_.png" alt="Logo Industro" class="logo">
            <div class="heading">REGISTRO DE USUARIO</div>
        </div>
        <?php 
            if (!empty($mensajeError)) echo $mensajeError; 
            if (!empty($mensajeExito)) echo $mensajeExito; 
        ?>
         
    
        <form action="#" method="post" class="RegistroForm" id="registroForm" name= "industro_uno" autocomplete="off">
            <div class="inputIcon">
                
                <i class="fa-solid fa-user"></i>
                <input type="text" id="nombres" class="LoginInput" name="nombre" placeholder="Nombres" required autocomplete="off">
            </div>
            
            <div class="inputIcon">
                <i class="fa-solid fa-user"></i>
                <input type="text" id="apellidos" class="LoginInput" name="apellido" placeholder="Apellidos" required autocomplete="off">
            </div>
            
             <div class="inputIcon">
                <i class="fa-solid fa-user-pen"></i>
                <input type="text" id="username" class="LoginInput" name="username" placeholder="Nombre de usuario" required autocomplete="off">
            </div>

            <div class="inputIcon">
                <i class="fa-solid fa-id-card"></i>
                <select name="tipoDocumento" id="tipoDocumento" required class="LoginInput" autocomplete="off">
                    <option value="" disabled selected hidden>Tipo de Documento</option>
                    <option value="Cédula de Ciudadanía">Cédula de Ciudadanía</option>
                    <option value="Tarjeta de Identidad">Tarjeta de Identidad</option>
                    <option value="Cédula de Extranjería">Cédula de Extranjería</option>
                    <option value="Registro Civil">Registro Civil</option>
                </select>
            </div>


            <div class="inputIcon">
                <i class="fa-solid fa-id-card-clip"></i>
                <input type="text" id="documento" class="LoginInput" name="numeroDocumento" placeholder="Numero de documento" required autocomplete="off">
            </div>

            
            <div class="inputIcon">
                <i class="fa-solid fa-envelope"></i>
                <input type="email" id="email" class="LoginInput" name="email" placeholder="Correo Electrónico" required autocomplete="off">
            </div>
            
            <div class="inputIcon">
                <i class="fa-solid fa-key"></i>
                <input type="password" id="password" class="LoginInput" placeholder="Contraseña" name="password" required autocomplete="off">
            </div>
            
            <div class="SelectContenedor">
                <select name="id_rol" id="cargo" required class="SelectSmall" autocomplete="off">
                    <option value="" disabled selected hidden>Cargo</option>
                    <option value="1">Administrador</option>
                    <option value="2">Empleado</option>
                </select>
                
                <select name="idioma" id="langSelect" class="SelectSmall" autocomplete="off">
                    <option value="" disabled selected hidden>Idioma</option>
                    <option value="Inglés">en</option>
                    <option value="Español">es</option>
                </select>
            </div>
            
            <div class="checkbox-container">
                <input type="checkbox" id="terminos" name="terms" required autocomplete="off">
                <label for="terminos">Acepto los términos y condiciones</label>
            </div>
            <span class="Forgot-Password"><a href="/working/login/login_es.php" ><b>Iniciar sesión</a></span>
            <input type="submit" class="LButtom" id="registroButton" name="registro" value="Registrarse" autocomplete="off">
        </form>
    </div>e
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
    document.getElementById("nombres").value = "";
    document.getElementById("apellidos").value = "";
    document.getElementById("username").value = "";
    document.getElementById("documento").value = "";
    document.getElementById("email").value = "";
    document.getElementById("password").value = "";
    document.getElementById("tipoDocumento").selectedIndex = 0;
    document.getElementById("cargo").selectedIndex = 0;
    document.getElementById("langSelect").selectedIndex = 0;
    document.getElementById("terminos").checked = false;
};

</script>
</body>
</html>
<?php

