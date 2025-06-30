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
    $nom_mat = $data['nom_mat'] ?? null;
    $cant_mat = $data['cant_mat'] ?? null;
    
    if (!$nom_mat || !$cant_mat) {
        throw new Exception("Datos incompletos");
    }
    
    // Primero insertar el nuevo material (necesitarás crear este procedimiento)
    $query = "INSERT INTO stock (nom_mat, cant_mat, DateActu) VALUES (?, ?, CURDATE())";
    $stmt = mysqli_prepare($enlace, $query);
    
    if (!$stmt) {
        throw new Exception("Error al preparar la consulta: " . mysqli_error($enlace));
    }
    
    mysqli_stmt_bind_param($stmt, "si", $nom_mat, $cant_mat);
    $result = mysqli_stmt_execute($stmt);
    
    if (!$result) {
        throw new Exception("Error al ejecutar la consulta: " . mysqli_error($enlace));
    }
    
    // Obtener el ID del nuevo material
    $new_id = mysqli_insert_id($enlace);
    
    echo json_encode([
        'success' => true,
        'mensaje' => "✅ Material ID $new_id agregado correctamente",
        'id' => $new_id
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