<?php
session_start();
include_once 'conexion.php';
if(!isset($_SESSION['usuario']) || !isset($_SESSION['id'])) {
    header('HTTP/1.1 401 Unauthorized');
    echo json_encode(['error' => 'No autorizado']);
    exit();
}
$id_producto = $_POST['producto'] ?? null;
$cantidad = $_POST['cantidad'] ?? null;
$id_empleado = $_SESSION['id']; 
if(!$id_producto || !$cantidad || !is_numeric($cantidad) || $cantidad <= 0) {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['error' => 'Datos inválidos']);
    exit();
}

try {
    $stmt = $enlace->prepare("CALL sp_insertar_produccion3(?, ?, ?)");
    $stmt->bind_param("iii", $id_empleado, $id_producto, $cantidad);
    $stmt->execute();
    $result = $stmt->get_result();
    $response = $result->fetch_assoc();
    if(strpos($response['mensaje'], '❌ Error') !== false) {
        header('HTTP/1.1 400 Bad Request');
        echo json_encode(['error' => $response['mensaje']]);
    } else {
        echo json_encode([
            'success' => $response['mensaje'],
            'idRegistro' => $response['idRegistro']
        ]);
    }

} catch (Exception $e) {
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['error' => 'Error en el servidor: ' . $e->getMessage()]);
}
?>