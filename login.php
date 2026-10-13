<?php
session_start();

// 1. AQUÍ INVENTAS TUS DATOS DE ADMINISTRADOR ÚNICO
$mi_correo_unico = "adminNUMI@gmail.com"; // <-- Cambia esto por tu correo
$mi_contrasena_unica = "NumiADMIN123"; // <-- Cambia esto por tu contraseña secreta

$error_login = "";

// 2. PROCESAR EL FORMULARIO (Solo si se presiona el botón)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $correo_ingresado = $_POST['correo'] ?? '';
    $contrasena_ingresada = $_POST['contrasena'] ?? '';

    if (!empty($correo_ingresado) && !empty($contrasena_ingresada)) {
        
        // 3. Comparamos los datos ingresados con los tuyos
        if ($correo_ingresado === $mi_correo_unico && $contrasena_ingresada === $mi_contrasena_unica) {
            
            // GUARDAR SESIÓN
            $_SESSION['usuario_id'] = 1; // Un ID genérico
            $_SESSION['nombre_usuario'] = 'Administrador Numi';
            $_SESSION['rol'] = 'admin'; // El rol que te da acceso
            
            // Redirección al panel
            header("Location: paneladmin.php");
            exit(); 

        } else {
            $error_login = "Correo o contraseña incorrectos.";
        }
    } else {
        $error_login = "Por favor, llena todos los campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - NUMI</title>
    <link rel="stylesheet" href="styles/bootstrap4/bootstrap.min.css">
    <style>
        body { background: #f7f9fb; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-card { background: white; padding: 40px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 100%; max-width: 400px; border-top: 5px solid #EEB4C5; }
        .btn-custom { background: #EEB4C5; color: white; font-weight: bold; transition: 0.3s; }
        .btn-custom:hover { background: #d8a1b1; color: white; }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="text-center mb-4">
            <img src="imagenes/logo (1).png" alt="Logo" style="height: 50px;">
            <h3 class="mt-3">Iniciar Sesión como ADMINISTRADOR</h3>
        </div>

        <?php if ($error_login): ?>
            <div class="alert alert-danger text-center"><?php echo $error_login; ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="form-group">
                <label>Correo Electrónico</label>
                <input type="email" name="correo" class="form-control" placeholder="ejemplo@correo.com" required>
            </div>
            <div class="form-group">
                <label>Contraseña</label>
                <input type="password" name="contrasena" class="form-control" placeholder="********" required>
            </div>
            <button type="submit" class="btn btn-custom btn-block">ENTRAR AL SISTEMA</button>
            
            <div class="text-center mt-4">
                <small><a href="index.php">Regresa al inicio</a></small>
            </div>
        </form>
    </div>

</body>
</html>