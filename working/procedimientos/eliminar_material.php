<?php
// working/procedimientos/eliminar_material.php

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

header('Content-Type: application/json');

try {
    // Validar que se reciba el ID del material
    if (!isset($_POST['id_mat']) || empty($_POST['id_mat'])) {
        throw new Exception('ID de material no proporcionado');
    }

    $id_mat = intval($_POST['id_mat']);

    // Conexión a la base de datos
    $servidor = "localhost";
    $usuario = "root";
    $clave = "";
    $baseDeDatos = "industro_uno";

    $enlace = mysqli_connect($servidor, $usuario, $clave, $baseDeDatos);
    if (!$enlace) {
        throw new Exception('Error de conexión: ' . mysqli_connect_error());
    }
    $enlace->set_charset("utf8");

    // Llamar al procedimiento almacenado
    $stmt = $enlace->prepare("CALL sp_eliminar_stock(?)");
    $stmt->bind_param("i", $id_mat);
    $stmt->execute();
    
    // Obtener el resultado del procedimiento
    $result = $stmt->get_result();
    $mensaje = $result->fetch_assoc()['mensaje'];
    
    $stmt->close();
    mysqli_close($enlace);

    echo json_encode([
        'estado' => 'exito',
        'mensaje' => $mensaje
    ]);

} catch (Exception $e) {
    echo json_encode([
        'estado' => 'error',
        'mensaje' => 'Error al eliminar material: ' . $e->getMessage()
    ]);
}
?>