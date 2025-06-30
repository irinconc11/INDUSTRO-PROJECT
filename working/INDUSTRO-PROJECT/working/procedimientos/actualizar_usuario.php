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
    // Verifica que todos los campos necesarios estén presentes
    $requiredFields = ['id', 'nombre', 'apellido', 'nomUsuario', 'email', 'tipoDocumento', 'numeroDocumento', 'id_rol'];
    foreach ($requiredFields as $field) {
        if (!isset($data[$field])) {
            throw new Exception("Falta el campo requerido: $field");
        }
    }

    
    $stmt = $enlace->prepare("CALL sp_actualizar_usuario1(?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "issssssi", 
        $data['id'],
        $data['nombre'],
        $data['apellido'],
        $data['nomUsuario'],
        $data['email'],
        $data['tipoDocumento'],
        $data['numeroDocumento'],
        $data['id_rol']
    );
    $stmt->execute();
    
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>