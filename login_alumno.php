<?php
session_start();

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

$error_login = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $correo_ingresado = trim($_POST['correo']);
    $contrasena_ingresada = $_POST['contrasena'];

    if (!empty($correo_ingresado) && !empty($contrasena_ingresada)) {
        
        // Hacemos un INNER JOIN para jalar el rol del alumno desde 'usuario_rol' como lo hace tu registro
        $sql = "SELECT u.*, ur.id_rol 
                FROM usuarios u
                INNER JOIN usuario_rol ur ON u.id_usuario = ur.id_usuario
                WHERE u.correo = ?";
                
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $correo_ingresado);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 1) {
            $usuario = $resultado->fetch_assoc();
            
            // Verificamos la contraseña encriptada con password_verify (tal como la guarda tu registro.php)
            if (password_verify($contrasena_ingresada, $usuario['contrasena'])) {
                
                // Inicializamos las variables de sesión idénticas a tu ecosistema
                $_SESSION['usuario_id']     = $usuario['id_usuario']; 
                $_SESSION['usuario_nombre'] = $usuario['nombre']; 
                $_SESSION['rol']            = 'usuario'; // "usuario" es el valor por defecto en tu registro
                $_SESSION['id_rol']         = $usuario['id_rol']; // Guarda el número 2 de alumno
                $_SESSION['sesion_iniciada'] = true;
                
                // =======================================================================
                // COMPROBACIÓN REQUERIDA: VERIFICAR SI YA LLENÓ SU ESCUELA
                // =======================================================================
                $id_user_actual = $usuario['id_usuario'];
                
                // Buscamos si este id_usuario ya existe registrado en tu tabla 'alumnos'
                $sql_escuela = "SELECT id_usuario FROM alumnos WHERE id_usuario = ? LIMIT 1";
                $stmt_escuela = $conn->prepare($sql_escuela);
                $stmt_escuela->bind_param("i", $id_user_actual);
                $stmt_escuela->execute();
                $res_escuela = $stmt_escuela->get_result();
                
                if ($res_escuela->num_rows === 1) {
                    // SI YA TIENE REGISTRO EN LA TABLA ALUMNOS: Activamos su etiqueta de estudiante
                    $_SESSION['registro_completo'] = true;
                } else {
                    // SI NO TIENE REGISTRO EN LA TABLA ALUMNOS: Lo dejamos oculto hasta que la llene
                    $_SESSION['registro_completo'] = false;
                }
                $stmt_escuela->close();
                // =======================================================================
                
                // Redirección directa al inicio con tu menú activo
                header("Location: index.php");
                exit(); 

            } else {
                $error_login = "La contraseña ingresada es incorrecta.";
            }
        } else {
            $error_login = "El correo electrónico no está registrado o no tiene asignado un rol de alumno.";
        }
        $stmt->close();
    } else {
        $error_login = "Por favor, completa todos los campos.";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Alumnos - NUMI</title>
    <link rel="stylesheet" href="styles/bootstrap4/bootstrap.min.css">
    <style>
        body { background: #f6f5f8; height: 100vh; display: flex; align-items: center; justify-content: center; font-family: sans-serif; }
        .login-card { background: white; padding: 40px; border-radius: 24px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); width: 100%; max-width: 420px; border: 1px solid #e2e8f0; }
        .btn-custom { background: #51CCCC; color: white; font-weight: bold; padding: 12px; border-radius: 12px; border: none; transition: 0.3s; width: 100%; }
        .btn-custom:hover { background: #40aaaa; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(81, 204, 204, 0.3); }
        .form-control { border-radius: 10px; height: 45px; }
        label { font-weight: 700; font-size: 0.85rem; color: #141022; }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="text-center mb-4">
            <img src="imagenes/logo (1).png" alt="Logo" style="height: 55px;">
            <h3 class="mt-3" style="font-weight: 900;">Ingreso Alumnos</h3>
            <p class="text-muted small">Accede a tu cuenta de estudiante en NUMI</p>
        </div>

        <?php if ($error_login): ?>
            <div class="alert alert-danger text-center small" style="border-radius: 10px;"><?php echo $error_login; ?></div>
        <?php endif; ?>

        <form method="POST" action="login_alumno.php">
            <div class="form-group">
                <label>Correo electrónico</label>
                <input type="email" name="correo" class="form-control" placeholder="ejemplo@correo.com" required>
            </div>
            <div class="form-group">
                <label>Contraseña</label>
                <input type="password" name="contrasena" class="form-control" placeholder="********" required>
            </div>
            <button type="submit" class="btn btn-custom mt-4">INGRESAR</button>
            
            <div class="text-center mt-3">
                <small class="text-muted">¿No tienes cuenta? <a href="registro_vista.php" style="color: #51CCCC; font-weight: bold;">Regístrate aquí</a></small>
            </div>
        </form>
    </div>

</body>
</html>