<?php 
// Conexión
$servidor = "localhost";
$usuario = "root";
$clave = "";
$baseDeDatos = "industro_uno";

$conexion = new mysqli($servidor, $usuario, $clave, $baseDeDatos);
$conexion->set_charset("utf8");

if ($conexion->connect_error) {
    die("❌ Conexión fallida: " . $conexion->connect_error);
}

// Recoger datos del formulario
$idProd = $_POST['idProd'];
$nomProd = $_POST['nomProd'];
$cantProd = $_POST['cantProd'];
$precio = $_POST['precio'];

// Manejar la foto
$fotoNombre = null;

// Verifica si se subió un nuevo archivo
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $fotoTmp = $_FILES['foto']['tmp_name'];
    $fotoNombre = basename($_FILES['foto']['name']);
    
    // Ruta de destino para guardar la imagen
    $rutaDestino = '../imagenes_productos/' . $fotoNombre;
    
    // Mover la foto subida a la carpeta destino
    if (!move_uploaded_file($fotoTmp, $rutaDestino)) {
        echo "❌ Error al mover la imagen al servidor.";
        exit;
    }
} else {
    // Si no se sube imagen, conservar la anterior (si fue enviada)
    $fotoNombre = $_POST['foto_antigua'] ?? null;
}

// Llamar al procedimiento almacenado
$stmt = $conexion->prepare("CALL sp_actualizar_producto(?, ?, ?, ?, ?)");
$stmt->bind_param("isiis", $idProd, $nomProd, $cantProd, $precio, $fotoNombre);

if ($stmt->execute()) {
    $resultado = $stmt->get_result();
    $mensaje = $resultado->fetch_assoc();
    echo $mensaje['mensaje'];
} else {
    echo "❌ Error al ejecutar el procedimiento.";
}

$stmt->close();
$conexion->close();
?>
