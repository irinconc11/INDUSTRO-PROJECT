<?php
header('Content-Type: application/json');

try {
    // Configuración de la base de datos
    $servidor = "localhost";
    $usuario = "root";
    $clave = "";
    $baseDeDatos = "industro_uno";
    
    $enlace = mysqli_connect($servidor, $usuario, $clave, $baseDeDatos);
    
    if (!$enlace) {
        throw new Exception("Error de conexión: " . mysqli_connect_error());
    }

    // Obtener datos del POST
    $data = json_decode(file_get_contents('php://input'), true);
    $id_mat = $data['id_mat'] ?? null;
    $nom_mat = $data['nom_mat'] ?? null;
    $cant_mat = $data['cant_mat'] ?? null;
    
    if (!$id_mat || !$nom_mat || !$cant_mat) {
        throw new Exception("Datos incompletos");
    }
    
    // Llamar al procedimiento almacenado
    $query = "CALL sp_actualizar_stock(?, ?, ?)";
    $stmt = mysqli_prepare($enlace, $query);
    
    if (!$stmt) {
        throw new Exception("Error al preparar la consulta: " . mysqli_error($enlace));
    }
    
    mysqli_stmt_bind_param($stmt, "isi", $id_mat, $nom_mat, $cant_mat);
    $result = mysqli_stmt_execute($stmt);
    
    if (!$result) {
        throw new Exception("Error al ejecutar la consulta: " . mysqli_error($enlace));
    }
    
    // Obtener el mensaje de retorno
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    
    echo json_encode([
        'success' => true,
        'mensaje' => $row['mensaje'] ?? 'Material actualizado correctamente'
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'mensaje' => $e->getMessage()
    ]);
} finally {
    if (isset($enlace)) {
        mysqli_close($enlace);
    }
}
?>