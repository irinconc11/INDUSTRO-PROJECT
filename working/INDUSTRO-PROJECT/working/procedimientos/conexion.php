<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    $servidor = "localhost";
    $usuario = "root";
    $clave = "";
    $baseDeDatos = "industro_uno";

    $enlace = mysqli_connect($servidor, $usuario, $clave, $baseDeDatos);

    if (!$enlace) {
        die("Error de conexión: " . mysqli_connect_error());
    }       

    $enlace -> set_charset("utf8");
?>