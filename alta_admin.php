<?php
session_start();
// PROTECCIÓN: Solo si ya eres admin puedes ver este formulario
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit();
}
?>

<h2>Registrar un nuevo Administrador</h2>
<form action="procesar_admin.php" method="POST">
    <input type="hidden" name="id_rol" value="1"> <button type="submit" name="register_admin">Crear Administrador VIP</button>
</form>