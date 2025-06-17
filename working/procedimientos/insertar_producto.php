<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conexion = new mysqli("localhost", "root", "", "industro_uno");
if ($conexion->connect_error) {
    die("❌ Error de conexión: " . $conexion->connect_error);
}

$nombre = $_POST["nombre"] ?? '';
$cantidad = (int) ($_POST["cantidad"] ?? 0);
$precio = (int) ($_POST["precio"] ?? 0);
$foto = $_POST["foto"] ?? '';

$stmt = $conexion->prepare("CALL sp_insertar_producto(?, ?, ?, ?)");
if (!$stmt) {
    die("❌ Error al preparar: " . $conexion->error);
}

if (!$stmt->bind_param("siis", $nombre, $cantidad, $precio, $foto)) {
    die("❌ Error al bindear: " . $stmt->error);
}

if ($stmt->execute()) {
    $resultado = $stmt->get_result();
    if ($resultado && $fila = $resultado->fetch_assoc()) {
        echo "✅ " . $fila["mensaje"] . " (ID: " . $fila["id_producto"] . ")";
    } else {
        echo "✅ Producto insertado correctamente.";
    }
} else {
    echo "❌ Error al ejecutar: " . $stmt->error;
}

$stmt->close();
$conexion->close();
?>
