<?php
session_start();

$error_login = "";

// 1. PROCESAR EL FORMULARIO AQUÍ MISMO
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Conexión a la base de datos
    $host = "localhost";
    $usuario_db = "root";
    $password_db = "";
    $nombre_db = "numi";

    $conex = mysqli_connect($host, $usuario_db, $password_db, $nombre_db);

    if (!$conex) {
        die("Error de conexión: " . mysqli_connect_error());
    }
    mysqli_set_charset($conex, "utf8mb4");

    $correo = mysqli_real_escape_string($conex, trim($_POST['correo'] ?? ''));
    $contrasena_ingresada = trim($_POST['contrasena'] ?? ' ');

    if (!empty($correo) && !empty($contrasena_ingresada)) {
        
        // Buscar al usuario en la BD
        $consulta = "SELECT id_usuario, nombre, contrasena, id_rol FROM usuarios WHERE correo = '$correo'";
        $resultado = mysqli_query($conex, $consulta);

        if ($resultado && mysqli_num_rows($resultado) > 0) {
            $usuario = mysqli_fetch_assoc($resultado);

            // Verificar contraseña encriptada
            if (password_verify($contrasena_ingresada, $usuario['contrasena'])) {
                
                $id_rol = $usuario['id_rol'];
                $id_usuario_general = $usuario['id_usuario'];
                
                // Variables de sesión base
                $_SESSION['usuario_id']      = $id_usuario_general;
                $_SESSION['usuario_nombre']  = $usuario['nombre'];
                $_SESSION['id_rol']          = $id_rol;
                $_SESSION['sesion_iniciada'] = true;
                
                $destino = 'index.php'; // Destino por defecto

                // Asignar variables de sesión específicas según su rol
                if ($id_rol == 1) {
                    $_SESSION['rol'] = 'alumno'; // Cambiado de 'usuario' a 'alumno'
                    
                    // Buscar el ID específico del alumno y su nivel
                    $consulta_alumno = "SELECT id_alumno, nivel_educativo FROM alumnos WHERE id_usuario = '$id_usuario_general'";
                    $res_al = mysqli_query($conex, $consulta_alumno);
                    if ($res_al && mysqli_num_rows($res_al) > 0) {
                        $datos_alumno = mysqli_fetch_assoc($res_al);
                        $_SESSION['id_alumno'] = $datos_alumno['id_alumno'];
                        $_SESSION['nivel'] = $datos_alumno['nivel_educativo'];
                    }
                    
                } elseif ($id_rol == 2) {
                    $_SESSION['rol'] = 'maestro';
                    
                    // Buscar el ID específico del maestro
                    $consulta_maestro = "SELECT id_maestro FROM maestros WHERE id_usuario = '$id_usuario_general'";
                    $res_ma = mysqli_query($conex, $consulta_maestro);
                    if ($res_ma && mysqli_num_rows($res_ma) > 0) {
                        $datos_maestro = mysqli_fetch_assoc($res_ma);
                        $_SESSION['id_maestro'] = $datos_maestro['id_maestro'];
                    }
                    
                } elseif ($id_rol == 3) {
                    $_SESSION['rol'] = 'tutor';
                    
                    // Buscar el ID específico del tutor
                    $consulta_tutor = "SELECT id_tutor FROM tutores WHERE id_usuario = '$id_usuario_general'";
                    $res_tu = mysqli_query($conex, $consulta_tutor);
                    if ($res_tu && mysqli_num_rows($res_tu) > 0) {
                        $datos_tutor = mysqli_fetch_assoc($res_tu);
                        $_SESSION['id_tutor'] = $datos_tutor['id_tutor'];
                    }
                    
                } elseif ($id_rol == 4) { // Por si manejas un rol de administrador
                    $_SESSION['rol'] = 'admin';
                    $destino = 'paneladmin.php'; // Redirigir admin a su panel
                } else {
                    $_SESSION['rol'] = 'usuario';
                }

                // Redirigir a su pantalla correspondiente
                header("Location: " . $destino);
                exit();

            } else {
                $error_login = "Contraseña incorrecta. Inténtalo de nuevo.";
            }
        } else {
            $error_login = "No encontramos ninguna cuenta con este correo.";
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - NUMI</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        .primary-gradient {
            background: linear-gradient(135deg, #A188DD 0%, #8B5CF6 100%);
        }
        .ambient-shadow {
            box-shadow: 0px 20px 40px rgba(0, 0, 0, 0.08);
        }
        .btn-nav-custom {
            background: white;
            color: #A188DD;
            padding: 8px 20px;
            border-radius: 50px;
            font-weight: bold;
            transition: 0.3s;
            text-decoration: none;
        }
        .btn-nav-custom:hover {
            background: #000000;
            transform: scale(1.05);
        }
        .footer {
            position: relative;
            color: rgb(0, 0, 0);
            padding-top: 50px;
            padding-bottom: 20px;
        }
        .footer_background {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background-repeat: no-repeat;
            background-size: cover;
            z-index: -1;
        }
    </style>
</head>
<body class="bg-slate-50 flex flex-col min-h-screen">

    <header class="header">
        <div class="header_container" style="background-color: #A188DD; border-bottom: 1px solid #e2e8f0;">
            <div class="container mx-auto px-6 py-4">
                <!-- AQUÍ CAMBIÉ justify-between a justify-center PARA CENTRAR EL LOGO -->
                <div class="flex items-center justify-center w-full">
                    <div class="logo_container">
                        <a href="index.php">
                            <img src="imagenes/logosinfondo.png" alt="Logo NUMI" style="height:60px;">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="flex-grow flex flex-col md:flex-row items-center justify-center py-12 md:py-24">
    
    <div class="container mx-auto flex flex-col md:flex-row items-stretch overflow-hidden bg-white ambient-shadow rounded-3xl max-w-6xl">
        
        <section class="hidden md:flex md:w-[50%] primary-gradient flex-col justify-center px-12 py-20 text-white">
            <h1 class="text-5xl font-bold mb-6">Gestiona tu <br>aprendizaje.</h1>
            <p class="text-lg opacity-90 max-w-md mb-8">
                Accede a la plataforma inteligente de NUMI para continuar con tu formación académica de vanguardia.
            </p>
            <div class="flex flex-wrap gap-3">
                <span class="px-4 py-2 bg-white/20 rounded-full text-xs">✓ Seguridad SSL</span>
                <span class="px-4 py-2 bg-white/20 rounded-full text-xs">✓ Acceso Rápido</span>
            </div>
        </section>

        <section class="w-full md:w-[50%] flex flex-col justify-center p-8 md:p-16 bg-white">
            <div class="w-full">
                <div class="mb-10">
                    <h2 class="text-3xl font-bold text-slate-800">Bienvenido</h2>
                    <p class="text-slate-500">Ingresa tus credenciales para acceder</p>
                </div>

                <!-- ALERTA DE ERROR SI LA CONTRASEÑA O CORREO ESTÁN MAL -->
                <?php if (!empty($error_login)): ?>
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                        <p class="font-bold">Error</p>
                        <p><?php echo $error_login; ?></p>
                    </div>
                <?php endif; ?>

                <!-- NOTA: El action="" está vacío para que todo se procese en este mismo archivo -->
                <form action="" method="POST" class="space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Correo electrónico</label>
                        <input type="email" name="correo" required 
                            class="w-full px-4 py-4 rounded-xl bg-slate-50 border border-slate-200 focus:border-[#A188DD] outline-none transition-all"
                            placeholder="tu@correo.com">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Contraseña</label>
                        <input type="password" name="contrasena" required 
                            class="w-full px-4 py-4 rounded-xl bg-slate-50 border border-slate-200 focus:border-[#A188DD] outline-none transition-all"
                            placeholder="••••••••">
                    </div>

                    <button type="submit" 
                        class="w-full py-4 rounded-xl bg-[#A188DD] text-white font-bold text-lg shadow-lg hover:bg-[#8B5CF6] transition-all transform hover:scale-[1.01] active:scale-[0.98]">
                        Iniciar Sesión
                    </button>
                </form>

                <div class="mt-10 pt-6 border-t border-slate-100 text-center">
                    <p class="text-slate-500 text-sm">¿No tienes cuenta? 
                        <a href="registro.php" class="text-[#A188DD] font-bold hover:underline">Regístrate ahora</a>
                    </p>
                </div>
            </div>
        </section>

    </div>
    </main>

    <footer class="footer mt-auto" style="position: relative; color: rgb(0, 0, 0) !important;">
    <div class="footer_background" style="background-image:url(imagenes/background.png); position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-size: cover; z-index: -1;"></div>
    
    <div class="container mx-auto px-6 py-12">
        <div class="flex flex-col md:flex-row justify-between gap-10">

            <div class="md:w-1/3">
                <div class="footer_logo_container mb-4">
                    <a href="#" style="text-decoration: none;">
                        <div class="text-3xl font-bold text-white">NU<span style="color: #51CCCC;">MI</span></div>
                    </a>
                </div>
                <div class="text-black/90">
                    <p>Gestiona el aprendizaje de forma inteligente y divertida.</p>
                </div>
                <div class="mt-4">
                    <a href="#" class="text-white hover:text-[#51CCCC] transition-colors">
                        <i class="fa fa-instagram text-2xl" aria-hidden="true"></i>
                    </a>
                </div>
            </div>

            <div class="md:w-1/3">
                <div class="text-xl font-bold text-white mb-4">Contáctanos</div>
                <div class="text-black/90">
                    <ul class="space-y-2" style="list-style: none; padding: 0;">
                        <li><i class="fa fa-envelope-o mr-2"></i> numi09052025@gmail.com</li>
                        <li><i class="fa fa-university mr-2"></i> UTCV créditos NUMI</li>
                    </ul>
                </div>
            </div>

            <div class="md:w-1/3"></div>
            
        </div>

        <div class="border-t border-white/20 mt-10 pt-6">
            <div class="flex flex-col md:flex-row justify-between items-center text-sm text-black/70">
                <div class="cr_text">
                    Copyright ©<script>document.write(new Date().getFullYear());</script> 
                    <span class="font-bold">TODOS LOS DERECHOS RESERVADOS A LA EMPRESA</span> 
                    <i class="fa fa-heart text-red-400" aria-hidden="true"></i> by 
                    <a href="#" class="text-white font-bold hover:underline">NUMI</a>
                </div>
            </div>
        </div>
    </div>
    </footer>

</body>
</html>