<?php
ob_start(); // <-- SOLUCIÓN 1: Evita bloqueos de redirección almacenando el buffer
session_start();

// =========================================================
// PROCESAMIENTO DEL FORMULARIO DE REGISTRO
// =========================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tipo_rol']) && $_POST['tipo_rol'] === 'tutor') {
    
    // Conexión a la base de datos
    $host = "localhost";
    $usuario_db = "root";
    $password_db = "";
    $nombre_db = "numi";

    $conn = new mysqli($host, $usuario_db, $password_db, $nombre_db);
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }
    $conn->set_charset("utf8mb4");

    // Recibir y limpiar datos
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $password = password_hash($_POST['contrasena'], PASSWORD_BCRYPT);
    $id_alumno = intval($_POST['id_alumno']);

    // 1. Insertar el usuario tutor (Con las columnas e id_rol corregidos)
    $id_rol_tutor = 3;
    $sql_user = "INSERT INTO usuarios (nombre, correo, contrasena, id_rol) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql_user);
    $stmt->bind_param("sssi", $nombre, $correo, $password, $id_rol_tutor);

    if ($stmt->execute()) {
        $id_usuario_tutor = $stmt->insert_id;

        // 2. Crear la vinculación en la tabla tutores
        $sql_tutor = "INSERT INTO tutores (id_usuario, id_alumno) VALUES (?, ?)";
        $stmt_tutor = $conn->prepare($sql_tutor);
        $stmt_tutor->bind_param("ii", $id_usuario_tutor, $id_alumno);
        
        // SOLUCIÓN 2: Verificamos que esta segunda consulta no falle por un ID incorrecto
        if ($stmt_tutor->execute()) {
            
            // 3. Iniciar la sesión del tutor
            $_SESSION['usuario_id'] = $id_usuario_tutor;
            $_SESSION['usuario_nombre'] = $nombre;
            $_SESSION['rol'] = 'tutor';
            $_SESSION['registro_completo'] = true;

            // 4. Redirigir directamente al panel (PHP + Fallback en JavaScript)
            header("Location: progreso_hijo.php");
            echo '<script>window.location.href = "progreso_hijo.php";</script>';
            exit();
            
        } else {
            // Si el ID del alumno no existe, borramos el usuario creado para no dejar datos huérfanos
            $conn->query("DELETE FROM usuarios WHERE id_usuario = $id_usuario_tutor");
            $error_msg = "Error: El Código de Alumno ingresado no existe. Por favor, verifica el número.";
        }
    } else {
        $error_msg = "Error al registrar tu cuenta. Es posible que este correo ya esté en uso.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<title>Registro Tutor | NUMI</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="styles/bootstrap4/bootstrap.min.css">
<link href="plugins/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="styles/contact.css">
<link rel="stylesheet" type="text/css" href="styles/contact_responsive.css">

<style>
    @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@400;700;800&family=Inter:wght@400;500;600&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap');

    .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }

    body {
        background-color: #f7f9fb;
        font-family: 'Inter', sans-serif;
    }

    /* --- ESTILOS DEL FORMULARIO TUTOR --- */
    .editorial-gradient {
        background: linear-gradient(135deg, #EEB4C5 0%, #A188DD 100%);
        color: white;
    }
    .form-container-box {
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.05);
        overflow: hidden;
        border: 1px solid rgba(196, 198, 205, 0.2);
    }
    .form-editorial-input {
        width: 100%;
        border: none;
        border-bottom: 1px solid #e2e8f0;
        padding: 12px 0 8px 0;
        background: transparent;
        outline: none;
        transition: border-color 0.3s ease;
        color: #191c1e;
        font-family: 'Inter', sans-serif;
    }
    .form-editorial-input:focus {
        border-bottom-color: #2b3548;
    }
    .form-editorial-label {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        color: #64748b;
        font-weight: 700;
        margin-bottom: 4px;
        display: block;
    }
    .editorial-btn {
        background-color: #2b3548;
        color: white;
        padding: 14px 20px;
        border: none;
        border-radius: 6px;
        font-family: 'Manrope', sans-serif;
        font-weight: 700;
        font-size: 0.875rem;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        cursor: pointer;
    }
    .editorial-btn:hover {
        background-color: #1a2233;
        transform: translateY(-2px);
        color: white;
    }
    .editorial-btn .material-symbols-outlined {
        transition: transform 0.3s ease;
        font-size: 1.2rem;
    }
    .editorial-btn:hover .material-symbols-outlined {
        transform: translateX(4px);
    }

    /* --- ESTILO PARA LA FLECHA DE VOLVER --- */
    .back-arrow-link {
        color: #64748b;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
    }
    .back-arrow-link:hover {
        color: #2b3548;
        transform: translateX(-4px);
        text-decoration: none;
    }
</style>
</head>
<body>

<div class="super_container">

    <header class="header">
        <div class="header_container" style="background-color: #A188DD; border-bottom: 1px solid #e2e8f0;">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="header_content d-flex flex-row align-items-center justify-content-center">
                            
                            <div style="background-color: #ffffff00; padding: 10px; border-radius: 8px; display: inline-block;">
                                <div class="logo_container">
                                    <a href="index.php">
                                        <img src="imagenes/logosinfondo.png" alt="Logo NUMI" style="height:60px;">
                                    </a>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div style="height: 130px;"></div>

    <div class="container d-flex align-items-center justify-content-center" style="min-height: calc(100vh - 200px); padding-top: 40px; padding-bottom: 80px;">
        <div class="row w-100 justify-content-center align-items-stretch">
            
            <div class="col-12 col-lg-5 mb-4 mb-lg-0">
                <div class="form-container-box p-5 h-100">
                    <div class="mb-5">
                        <h3 style="font-family: 'Manrope', sans-serif; font-size: 1.75rem; font-weight: 800; color: #191c1e; margin-bottom: 5px;">Crear Cuenta de Tutor</h3>
                        <p style="color: #64748b; font-size: 0.875rem;">Involúcrate y apoya el desarrollo de tus estudiantes.</p>
                    </div>

                    <?php if (isset($error_msg)): ?>
                        <div class="alert alert-danger" style="font-size: 0.85rem; border-radius: 6px; padding: 10px; margin-bottom: 20px;">
                            <strong>¡Atención!</strong> <?php echo $error_msg; ?>
                        </div>
                    <?php endif; ?>

                    <form action="registro_tutor.php" method="POST">
                        <input type="hidden" name="tipo_rol" value="tutor"> 

                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <label class="form-editorial-label" for="nombre">NOMBRE COMPLETO</label>
                                <input type="text" class="form-editorial-input" id="nombre" name="nombre" placeholder="Ej. Carlos Mendoza" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <label class="form-editorial-label" for="correo">CORREO ELECTRÓNICO</label>
                                <input type="email" class="form-editorial-input" id="correo" name="correo" placeholder="correo@ejemplo.com" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <label class="form-editorial-label" for="contrasena">CONTRASEÑA</label>
                                <input type="password" class="form-editorial-input" id="contrasena" name="contrasena" placeholder="••••••••" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <label class="form-editorial-label" for="id_alumno">CÓDIGO / ID DEL ALUMNO</label>
                                <input type="number" class="form-editorial-input" id="id_alumno" name="id_alumno" placeholder="Ej. 10452" required title="Ingresa el ID o código de tu hijo/estudiante">
                            </div>
                        </div>

                        <div class="mt-4 pt-2">
                            <button type="submit" class="editorial-btn">
                                Finalizar Registro
                                <span class="material-symbols-outlined">arrow_forward</span>
                            </button>
                        </div>

                        <!-- SECCIÓN INFERIOR: FLECHA Y ENLACE DE ACCESO -->
                        <div class="d-flex justify-content-between align-items-center mt-5 pt-3" style="border-top: 1px solid #e2e8f0; font-size: 0.7rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 1px;">
                            <a href="contact.php" class="back-arrow-link" title="Volver a la página de Acceso">
                                <span class="material-symbols-outlined" style="font-size: 1.5rem;">arrow_back</span>
                            </a>
                            <a href="login.html" style="color: #64748b; text-decoration: none;">Acceso Tutor Existente</a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-12 col-lg-5">
                <div class="form-container-box p-5 h-100 editorial-gradient d-flex flex-column justify-content-center" style="border: none;">
                    <span style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.15em; color: #000000; font-weight: 700;">Portal de Tutores</span>
                    <h3 style="font-family: 'Manrope', sans-serif; font-size: 2rem; font-weight: 800; color: #ffffff; line-height: 1.2; margin-top: 10px; margin-bottom: 20px;">
                        Acompaña<br>su Progreso.
                    </h3>
                    <div style="width: 50px; height: 2px; background-color: #000000; opacity: 0.3; margin-bottom: 30px;"></div>
                    <p style="color: #000000; font-size: 0.95rem; line-height: 1.8; margin-bottom: 30px;">
                        Regístrate como tutor para supervisar el avance de tus estudiantes, revisar sus calificaciones, recibir reportes de desempeño y apoyarlos de cerca en su desarrollo dentro de NUMI.
                    </p>
                    <div class="d-flex align-items-center" style="gap: 10px; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.15em; color: #ffffff; font-weight: 700;">
                        <span class="material-symbols-outlined" style="font-size: 1.5rem;">diversity_3</span>
                        <span>Monitoreo de Desempeño Escolar</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <footer class="footer mt-auto">
        <div class="footer_background" style="background-image:url(imagenes/background.png)"></div>
        <div class="container">
            <div class="row footer_row">
                <div class="col">
                    <div class="footer_content">
                        <div class="row">

                            <div class="col-lg-3 footer_col">
                                <div class="footer_section footer_about">
                                    <div class="footer_logo_container">
                                        <a href="#">
                                            <div class="footer_logo_text">NU<span style="color: #51CCCC;">MI</span></div>
                                        </a>
                                    </div>
                                    <div class="footer_about_text">
                                        <p>Gestiona el aprendizaje de forma inteligente y divertida.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 footer_col">
                                <div class="footer_section footer_contact">
                                    <div class="footer_title">Contáctanos</div>
                                    <div class="footer_contact_info">
                                        <ul>
                                            <li>Correo: numi09052025@gmail.com</li>
                                            <li>UTCV créditos NUMI</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-3 footer_col"></div>
                            
                        </div>
                    </div>
                </div>
            </div>

            <div class="row copyright_row">
                <div class="col">
                    <div class="copyright d-flex flex-lg-row flex-column align-items-center justify-content-start">
                        <div class="cr_text">
                            Copyright ©<script>document.write(new Date().getFullYear());</script> TODOS LOS DERECHOS RESERVADOS A LA EMPRESA <i class="fa fa-heart-o" aria-hidden="true"></i> by <a href="#" target="_blank">NUMI</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div>

<script src="js/jquery-3.2.1.min.js"></script>
<script src="styles/bootstrap4/popper.js"></script>
<script src="styles/bootstrap4/bootstrap.min.js"></script>
</body>
</html>