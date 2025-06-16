<?php
include 'conexion.php'; // Asegúrate de incluir tu conexión

$id = $_GET['id'];

// Llama al procedimiento almacenado sp_obtener_usuario_por_id
$stmt = $enlace->prepare("CALL sp_obtener_usuario_por_id(?)");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if($result && $row = $result->fetch_assoc()) {
    echo json_encode($row);
} else {
    echo json_encode(['error' => 'Usuario no encontrado']);
}

$stmt->close();
$enlace->close();
?>
