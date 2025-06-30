<?php
header('Content-Type: application/json');
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Conexión a la base de datos
    $servidor = "localhost";
    $usuario = "root";
    $clave = "";
    $baseDeDatos = "industro_uno";
    
    $enlace = mysqli_connect($servidor, $usuario, $clave, $baseDeDatos);
    if (!$enlace) {
        throw new Exception("Error de conexión: " . mysqli_connect_error());
    }
    $enlace->set_charset("utf8");

    // Validar datos recibidos
    if(empty($_POST['idProd']) || empty($_POST['nomProd']) || empty($_POST['cantProd']) || empty($_POST['precio'])) {
        throw new Exception("Todos los campos son requeridos");
    }

    // Obtener datos
    $idProd = (int)$_POST['idProd'];
    $nomProd = mysqli_real_escape_string($enlace, $_POST['nomProd']);
    $cantProd = (int)$_POST['cantProd'];
    $precio = (float)$_POST['precio'];
    $foto_antigua = $_POST['foto_antigua'] ?? '';
    
    // Manejo de la imagen
    $foto = $foto_antigua;
    
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $directorio = "../imagenes_productos/";
        if (!file_exists($directorio)) {
            mkdir($directorio, 0777, true);
        }
        
        $extension = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $nombreArchivo = 'producto_'.$idProd.'_'.time().'.'.$extension;
        $archivoDestino = $directorio . $nombreArchivo;
        
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $archivoDestino)) {
            $foto = $nombreArchivo;
            
            // Eliminar la foto antigua si existe
            if ($foto_antigua && file_exists($directorio . $foto_antigua)) {
                unlink($directorio . $foto_antigua);
            }
        }
    }
    
    // Llamar al procedimiento almacenado
    $stmt = $enlace->prepare("CALL sp_actualizar_producto(?, ?, ?, ?, ?)");
    $stmt->bind_param("isids", $idProd, $nomProd, $cantProd, $precio, $foto);
    $stmt->execute();
    
    echo json_encode([
        'estado' => 'exito',
        'mensaje' => 'Producto actualizado correctamente',
        'id' => $idProd
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'estado' => 'error',
        'mensaje' => 'Error al actualizar el producto: ' . $e->getMessage()
    ]);
} finally {
    if (isset($stmt)) $stmt->close();
    if (isset($enlace)) $enlace->close();
}
?>