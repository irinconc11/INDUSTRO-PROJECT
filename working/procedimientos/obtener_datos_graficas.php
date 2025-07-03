<?php
header('Content-Type: application/json');
error_reporting(0); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['id'])) {
    echo json_encode(['error' => 'Usuario no autenticado']);
    exit();
}
require_once 'conexion.php';

try {
    $stmt = $enlace->prepare("CALL sp_obtener_produccion_diaria(?)");
    $stmt->bind_param("i", $_SESSION['id']); // "i" = integer
    $stmt->execute();
    $diaria = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    $stmt = $enlace->prepare("CALL sp_obtener_produccion_mensual(?)");
    $stmt->bind_param("i", $_SESSION['id']);
    $stmt->execute();
    $mensual = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    $stmt = $enlace->prepare("CALL sp_obtener_distribucion_productos(?)");
    $stmt->bind_param("i", $_SESSION['id']);
    $stmt->execute();
    $productos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    echo json_encode([
        'diaria' => $diaria,
        'mensual' => $mensual,
        'productos' => $productos
    ]);
    exit();

} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
    exit();
}
?>