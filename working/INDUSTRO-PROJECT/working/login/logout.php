<?php

session_start();
session_unset();
session_destroy();

echo "<script>alert('Cierre de sesión exitoso')</script>";
header('location:/working/login/login_es.php');

exit();

?>