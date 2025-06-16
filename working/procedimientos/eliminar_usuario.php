<?php
header('Content-Type: application/json');

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$servidor = "localhost";
$usuario = "root";
$clave = "";
$baseDeDatos = "industro_uno";

$enlace = mysqli_connect($servidor, $usuario, $clave, $baseDeDatos);
$enlace->set_charset("utf8");

session_start();
if(!isset($_SESSION['usuario'])){
    echo json_encode(['success' => false, 'error' => 'No autorizado']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);

try {
    $stmt = $enlace->prepare("CALL sp_eliminar_usuario(?)");
    $stmt->bind_param("i", $data['id']);
    $stmt->execute();
    
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>