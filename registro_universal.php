<?php
session_start(); 

// 1. Verificación de sesión con el nombre de variable correcto
if (!isset($_SESSION['usuario_id'])) {
    die("Error: No hay una sesión activa. Por favor, regístrate de nuevo.");
}

$id_usuario_existente = $_SESSION['usuario_id'];

// ==========================================
// CONFIGURACIÓN DE LA BASE DE DATOS
// ==========================================
$host = "localhost";
$usuario_db = "root";      
$password_db = "";         
$nombre_db = "numi";       

$conn = new mysqli($host, $usuario_db, $password_db, $nombre_db);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

// ==========================================
// PROCESAR EL FORMULARIO
// ==========================================
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $tipo_rol = $_POST['tipo_rol'] ?? '';

    if (empty($tipo_rol)) {
        die("Error: No se especificó el tipo de rol.");
    }

    $conn->begin_transaction();

    try {
        // PASO 1: DETERMINAR ID_ROL
        $id_rol = 0;
        if ($tipo_rol == 'admin' || $tipo_rol == 'administrador') {
            $id_rol = 1;
        } elseif ($tipo_rol == 'alumno' || $tipo_rol == 'estudiante') {
            $id_rol = 2;
        } elseif ($tipo_rol == 'maestro' || $tipo_rol == 'docente') {
            $id_rol = 3;
        } elseif ($tipo_rol == 'tutor') {
            $id_rol = 4;
        }

        if ($id_rol === 0) {
            throw new Exception("Rol no válido seleccionado.");
        }

        // PASO 2: INSERTAR EN usuario_rol
        $sql_rol = "INSERT IGNORE INTO usuario_rol (id_usuario, id_rol) VALUES (?, ?)";
        $stmt_rol = $conn->prepare($sql_rol);
        $stmt_rol->bind_param("ii", $id_usuario_existente, $id_rol);
        $stmt_rol->execute();

        // PASO 3: INSERTAR O ACTUALIZAR EN TABLAS ESPECÍFICAS
        if ($tipo_rol == 'alumno') {
            $escuela = $_POST['escuela'] ?? '';
            $grado = $_POST['grado'] ?? '';
            $grupo = $_POST['grupo'] ?? '';

            $sql_alumno = "INSERT INTO alumnos (id_usuario, escuela, grado, grupo) VALUES (?, ?, ?, ?)
                           ON DUPLICATE KEY UPDATE escuela = ?, grado = ?, grupo = ?";
            $stmt_alumno = $conn->prepare($sql_alumno);
            $stmt_alumno->bind_param("issssss", $id_usuario_existente, $escuela, $grado, $grupo, $escuela, $grado, $grupo);
            $stmt_alumno->execute();
            
            // ¡NUEVO! Obtenemos el id_alumno de la base de datos para el código de vinculación del Tutor
            $res_alumno = $conn->query("SELECT id_alumno FROM alumnos WHERE id_usuario = $id_usuario_existente LIMIT 1");
            if ($alumno_data = $res_alumno->fetch_assoc()) {
                $_SESSION['id_alumno'] = $alumno_data['id_alumno'];
            }
            
        } elseif ($tipo_rol == 'maestro') {
            $escuela = $_POST['escuela'] ?? '';
            $grado = $_POST['grado'] ?? '';

            $sql_maestro = "INSERT INTO maestros (id_usuario, escuela, grado) VALUES (?, ?, ?)
                            ON DUPLICATE KEY UPDATE escuela = ?, grado = ?";
            $stmt_maestro = $conn->prepare($sql_maestro);
            $stmt_maestro->bind_param("issss", $id_usuario_existente, $escuela, $grado, $escuela, $grado);
            $stmt_maestro->execute();
            
            // Guardamos la escuela y grado en sesión para usarlos en el panel de crear clase
            $_SESSION['escuela_maestro'] = $escuela;
            $_SESSION['grado_maestro'] = $grado;
            
        } elseif ($tipo_rol == 'tutor') {
            $id_alumno = $_POST['id_alumno'] ?? 0;
            
            $sql_tutor = "INSERT INTO tutores (id_usuario, id_alumno) VALUES (?, ?)
                          ON DUPLICATE KEY UPDATE id_alumno = ?";
            $stmt_tutor = $conn->prepare($sql_tutor);
            $stmt_tutor->bind_param("iii", $id_usuario_existente, $id_alumno, $id_alumno);
            $stmt_tutor->execute();
        }

        $conn->commit();
        
        // ==========================================
        // ACTUALIZACIÓN DE VARIABLES DE SESIÓN CLAVE
        // ==========================================
        $_SESSION['id_rol'] = $id_rol;
        $_SESSION['rol'] = $tipo_rol; 
        $_SESSION['sesion_iniciada'] = true;
        $_SESSION['registro_completo'] = true; 

        $res_user = $conn->query("SELECT nombre FROM usuarios WHERE id_usuario = $id_usuario_existente");
        if($user_data = $res_user->fetch_assoc()){
            $_SESSION['usuario_nombre'] = $user_data['nombre'];
        } else {
            $_SESSION['usuario_nombre'] = "Estudiante"; 
        }
        
        // Obtenemos el id_maestro autoincremental de la base de datos si es maestro
        if ($tipo_rol == 'maestro') {
            $res_maestro = $conn->query("SELECT id_maestro FROM maestros WHERE id_usuario = $id_usuario_existente LIMIT 1");
            if ($maestro_data = $res_maestro->fetch_assoc()) {
                $_SESSION['id_maestro'] = $maestro_data['id_maestro'];
            }
        }

        // Redirección condicional:
        if ($tipo_rol == 'maestro') {
            header("Location: crear_clase.php");
        } else {
            header("Location: index.php");
        }
        exit();

    } catch (Exception $e) {
        $conn->rollback();
        die("Error al guardar los datos: " . $e->getMessage());
    }
}
$conn->close();
?>