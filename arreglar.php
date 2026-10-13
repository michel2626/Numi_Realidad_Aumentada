<?php
$conn = new mysqli("localhost", "root", "", "numi");

// Esto buscará a los que se guardaron con el error (4) y les pondrá el correcto (1)
$sql = "UPDATE usuario_rol SET id_rol = 1 WHERE id_rol = 4";

if ($conn->query($sql) === TRUE) {
    echo "<h2>¡Listo! La base de datos se arregló sola. Ya puedes iniciar sesión.</h2>";
} else {
    echo "Hubo un error: " . $conn->error;
}
?>