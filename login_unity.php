<?php
// ====================================================================
// SOLUCIÓN PARA UNITY: Forzar el tipo JSON y cerrar la conexión HTTP
// ====================================================================
header("Access-Control-Allow-Origin: *");
header("Connection: close");
header('Content-Type: application/json; charset=utf-8');

session_start();

$host = "localhost";
$usuario_db = "root";
$password_db = "";
$nombre_db = "numi";

$conex = mysqli_connect($host, $usuario_db, $password_db, $nombre_db);

if (!$conex) {
    die(json_encode(["status" => "error", "message" => "Error de conexion en servidor"]));
}
mysqli_set_charset($conex, "utf8mb4");

if (isset($_POST['correo']) && isset($_POST['contrasena'])) {
    
    $correo = trim($_POST['correo']);
    $contrasena_enviada = trim($_POST['contrasena']); 

    // Buscamos directamente en la base de datos al usuario y su id_alumno
    $consulta = "SELECT u.id_usuario, u.contrasena, a.id_alumno 
                 FROM usuarios u 
                 LEFT JOIN alumnos a ON u.id_usuario = a.id_usuario 
                 WHERE u.correo = ?";
                 
    $stmt = mysqli_prepare($conex, $consulta);
    mysqli_stmt_bind_param($stmt, "s", $correo);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $usuario = mysqli_fetch_assoc($resultado);
        
        // Comparamos la contraseña ingresada en Unity con la contraseña encriptada de la BD
        if (password_verify($contrasena_enviada, $usuario['contrasena'])) {
            
            // Verificamos si este usuario tiene un registro en la tabla de alumnos
            if (!empty($usuario['id_alumno'])) {
                // ¡Es un alumno válido! Devolvemos su ID real para guardar el progreso
                echo json_encode([
                    "status" => "success",
                    "id_usuario" => intval($usuario['id_usuario']),
                    "id_alumno" => intval($usuario['id_alumno'])
                ]);
            } else {
                echo json_encode(["status" => "error", "message" => "Esta cuenta no pertenece a un alumno"]);
            }
            
        } else {
            echo json_encode(["status" => "error", "message" => "Contrasena incorrecta"]); 
        }
    } else {
        echo json_encode(["status" => "error", "message" => "El correo no esta registrado"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Campos vacios en peticion"]);
}
?>