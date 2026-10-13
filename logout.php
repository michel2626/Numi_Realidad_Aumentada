<?php
session_start();
// Limpiamos todas las variables de sesión
session_unset();
// Destruimos la sesión por completo
session_destroy();

// Redirigimos al inicio
header("Location: index.php");
exit();
?>