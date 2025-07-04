<?php
$conexion = new mysqli("localhost", "root", "", "industro_uno");
$conexion->set_charset("utf8");

$productos = [];

$resultado = $conexion->query("CALL sp_obtener_productos()");

if ($resultado) {
    while ($fila = $resultado->fetch_assoc()) {
        $productos[] = [
            'idProd' => $fila['idProd'],
            'nomProd' => $fila['nomProd']
        ];
    }
    $resultado->free(); 
    $conexion->next_result();
}

echo json_encode($productos);
?>
