<?php
// ----- Configuración inicial crítica -----
header('Content-Type: application/json; charset=utf-8');
while (ob_get_level()) ob_end_clean(); // Limpiar buffers de salida
ob_start();

// ----- Conexión a la base de datos -----
$servidor = "localhost";
$usuario = "root";
$clave = "";
$baseDeDatos = "industro_uno";

try {
    $conexion = new mysqli($servidor, $usuario, $clave, $baseDeDatos);
    $conexion->set_charset("utf8mb4");

    if ($conexion->connect_error) {
        throw new Exception("❌ Error de conexión: " . $conexion->connect_error);
    }

    // ----- Validación de datos -----
    $idProd = isset($_POST['idProd']) ? intval($_POST['idProd']) : null;
    $nomProd = isset($_POST['nomProd']) ? trim($_POST['nomProd']) : null;
    $cantProd = isset($_POST['cantProd']) ? intval($_POST['cantProd']) : null;
    $precio = isset($_POST['precio']) ? floatval($_POST['precio']) : null;
    $fotoNombre = isset($_POST['foto_antigua']) ? $_POST['foto_antigua'] : null;

    if (!$idProd || !$nomProd || !$cantProd || !$precio) {
        throw new Exception("❌ Datos incompletos");
    }

    // ----- Manejo de la imagen -----
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $carpetaDestino = '../imagenes_productos/';
        if (!is_dir($carpetaDestino)) mkdir($carpetaDestino, 0777, true);
        
        $extension = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $fotoNombre = uniqid() . '.' . $extension;
        $rutaDestino = $carpetaDestino . $fotoNombre;

        if (!move_uploaded_file($_FILES['foto']['tmp_name'], $rutaDestino)) {
            throw new Exception("❌ Error al subir la imagen");
        }
    }

    // ----- Ejecutar el procedimiento almacenado -----
    $stmt = $conexion->prepare("CALL sp_actualizar_producto(?, ?, ?, ?, ?)");
    if (!$stmt) {
        throw new Exception("❌ Error al preparar la consulta: " . $conexion->error);
    }

    $stmt->bind_param("isiis", $idProd, $nomProd, $cantProd, $precio, $fotoNombre);

    if (!$stmt->execute()) {
        throw new Exception("❌ Error al ejecutar: " . $stmt->error);
    }

    // ----- Limpieza crítica de resultados adicionales -----
    // 1. Descarta el primer resultado (el SELECT del mensaje)
    $resultado = $stmt->get_result();
    $mensaje = $resultado ? $resultado->fetch_assoc()['mensaje'] : null;

    // 2. Descarta otros resultados si existen
    while ($stmt->more_results()) {
        $stmt->next_result();
        if ($result = $stmt->get_result()) {
            while ($result->fetch_row()) {}
        }
    }

    // ----- Respuesta JSON limpia -----
    $response = [
        "success" => true,
        "mensaje" => $mensaje ?? "✅ Producto actualizado correctamente"
    ];

} catch (Exception $e) {
    $response = [
        "success" => false,
        "error" => $e->getMessage()
    ];
} finally {
    // ----- Limpieza final -----
    if (isset($stmt)) $stmt->close();
    if (isset($conexion)) $conexion->close();
    
    ob_end_clean();
    die(json_encode($response, JSON_UNESCAPED_UNICODE));
}
?>