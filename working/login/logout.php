<?php
session_start();
session_unset();
session_destroy();
header('Location: /working/login/login_es.php');
exit();
?>
