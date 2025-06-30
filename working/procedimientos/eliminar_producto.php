<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include('conexion.php');
header('Content-Type: application/json');

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);

    try {
        $resultado = mysqli_query($enlace, "CALL sp_eliminar_producto($id)");

        if ($resultado) {
            $fila = mysqli_fetch_assoc($resultado);

            $mensaje = $fila['mensaje'] ?? 'Producto eliminado correctamente';
            echo json_encode(['estado' => 'exito', 'mensaje' => $mensaje]);
        } else {
            echo json_encode(['estado' => 'error', 'mensaje' => 'No se pudo ejecutar el procedimiento']);
        }
    } catch (Exception $e) {
        echo json_encode(['estado' => 'error', 'mensaje' => 'Error: '.$e->getMessage()]);
    }
} else {
    echo json_encode(['estado' => 'error', 'mensaje' => 'ID no recibido']);
}

?>