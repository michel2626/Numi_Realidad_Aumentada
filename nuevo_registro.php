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

// ==========================================
// PROCESAR EL FORMULARIO (TODO EN UNO)
// ==========================================
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    
    // 1. Recibir datos generales (Aplicando regla de la primera letra en Mayúscula)
    $nombre = mb_convert_case(trim($_POST['nombre'] ?? ''), MB_CASE_TITLE, "UTF-8");
    $apellido_p = mb_convert_case(trim($_POST['apellido_p'] ?? ''), MB_CASE_TITLE, "UTF-8");
    $apellido_m = mb_convert_case(trim($_POST['apellido_m'] ?? ''), MB_CASE_TITLE, "UTF-8");
    $edad = (int)($_POST['edad'] ?? 0);
    $sexo = $_POST['sexo'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $contrasena_plana = $_POST['contrasena'] ?? '';
    $tipo_rol = $_POST['tipo_rol'] ?? '';

    // Encriptar la contraseña por seguridad
    $contrasena = password_hash($contrasena_plana, PASSWORD_DEFAULT);

    if (empty($tipo_rol)) {
        $error = "Error: No se especificó el tipo de rol.";
    } elseif ($tipo_rol == 'alumno' && ($edad < 5 || $edad > 15)) {
        $error = "Error: La edad permitida para estudiantes es de 5 a 15 años.";
    } elseif (($tipo_rol == 'maestro' || $tipo_rol == 'tutor') && ($edad < 18 || $edad > 90)) {
        $error = "Error: La edad permitida para este rol es de 18 a 90 años.";
    } else {
        $conn->begin_transaction();

        try {
            // PASO 1: DETERMINAR ID_ROL PRIMERO
            // (1 = alumno, 2 = maestro, 3 = tutor) - Se omite admin porque no se registra por aquí
            $id_rol = 0;
            if ($tipo_rol == 'alumno') { $id_rol = 1; } 
            elseif ($tipo_rol == 'maestro') { $id_rol = 2; } 
            elseif ($tipo_rol == 'tutor') { $id_rol = 3; }

            // PASO 2: INSERTAR EL USUARIO NUEVO (Incluyendo el id_rol)
            $sql_user = "INSERT INTO usuarios (nombre, apellido_p, apellido_m, edad, sexo, correo, contrasena, id_rol) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt_user = $conn->prepare($sql_user);
            $stmt_user->bind_param("sssisssi", $nombre, $apellido_p, $apellido_m, $edad, $sexo, $correo, $contrasena, $id_rol);
            $stmt_user->execute();
            
            // Obtenemos el ID del usuario que se acaba de crear
            $id_usuario_nuevo = $stmt_user->insert_id;

            // PASO 3: INSERTAR EN usuario_rol
            $sql_rol = "INSERT IGNORE INTO usuario_rol (id_usuario, id_rol) VALUES (?, ?)";
            $stmt_rol = $conn->prepare($sql_rol);
            $stmt_rol->bind_param("ii", $id_usuario_nuevo, $id_rol);
            $stmt_rol->execute();

            // PASO 4: INSERTAR EN TABLAS ESPECÍFICAS
            if ($tipo_rol == 'alumno') {
                $nivel_educativo = $_POST['nivel_educativo'] ?? '';
                $escuela = $_POST['escuela_alumno'] ?? '';
                $grado = $_POST['grado_alumno'] ?? '';
                $grupo = $_POST['grupo_alumno'] ?? '';

                $sql_alumno = "INSERT INTO alumnos (id_usuario, nivel_educativo, escuela, grado, grupo) VALUES (?, ?, ?, ?, ?)";
                $stmt_alumno = $conn->prepare($sql_alumno);
                // "issss" significa: integer, string, string, string, string
                $stmt_alumno->bind_param("issss", $id_usuario_nuevo, $nivel_educativo, $escuela, $grado, $grupo);
                $stmt_alumno->execute();
                
                $_SESSION['id_alumno'] = $stmt_alumno->insert_id;
                
            } elseif ($tipo_rol == 'maestro') {
                $escuela = $_POST['escuela_maestro'] ?? '';
                $grado = $_POST['grado_maestro'] ?? '';

                $sql_maestro = "INSERT INTO maestros (id_usuario, escuela, grado) VALUES (?, ?, ?)";
                $stmt_maestro = $conn->prepare($sql_maestro);
                $stmt_maestro->bind_param("iss", $id_usuario_nuevo, $escuela, $grado);
                $stmt_maestro->execute();
                
                $_SESSION['escuela_maestro'] = $escuela;
                $_SESSION['grado_maestro'] = $grado;
                $_SESSION['id_maestro'] = $stmt_maestro->insert_id;
                
            } elseif ($tipo_rol == 'tutor') {
                $id_alumno = $_POST['id_alumno_vinculo'] ?? 0;
                
                $sql_tutor = "INSERT INTO tutores (id_usuario, id_alumno) VALUES (?, ?)";
                $stmt_tutor = $conn->prepare($sql_tutor);
                $stmt_tutor->bind_param("ii", $id_usuario_nuevo, $id_alumno);
                $stmt_tutor->execute();
            }

            $conn->commit();
            
            // ==========================================
            // INICIAR SESIÓN AUTOMÁTICAMENTE
            // ==========================================
            $_SESSION['usuario_id'] = $id_usuario_nuevo;
            $_SESSION['usuario_nombre'] = $nombre;
            $_SESSION['id_rol'] = $id_rol;
            $_SESSION['rol'] = $tipo_rol; 
            $_SESSION['sesion_iniciada'] = true;
            $_SESSION['registro_completo'] = true; 

            // Redirección condicional:
            if ($tipo_rol == 'maestro') {
                header("Location: crear_clase.php");
            } else {
                header("Location: index.php");
            }
            exit();

        } catch (Exception $e) {
            $conn->rollback();
            $error = "Error al guardar los datos: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>NUMI - Registro</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        :root {
            --primary: #51CCCC;
            --purple-header: #9B82DF;
            --pink-accent: #FFB3C6;
            --green-accent: #78C6A3;
            --bg-light: #EBF3FF;
            --text-dark: #141022;
            --text-gray: #64748b;
            --white: #ffffff;
            --border: #e2e8f0;
        }

        body, html {
            background-color: var(--bg-light) !important;
            color: var(--text-dark);
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .header {
            background-color: var(--purple-header);
            box-shadow: 0px 4px 15px rgba(0,0,0,0.1);
            width: 100%;
            padding: 15px 0;
            text-align: center;
        }

        .registration_section { padding-top: 40px; padding-bottom: 60px; }
        .login-card { background: var(--white); padding: 2.5rem; border-radius: 1.5rem; box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08); border: none; }
        .form-group label { font-weight: 700; font-size: 0.85rem; margin-bottom: 0.5rem; color: var(--text-dark); }
        .btn-login { width: 100%; background: var(--purple-header); color: white; padding: 1rem; border-radius: 12px; border: none; font-weight: bold; font-size: 1.1rem; cursor: pointer; transition: all 0.3s; margin-top: 15px; }
        .btn-login:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(155,130,223,0.4); }

        .role-selector { display: flex; gap: 15px; margin-bottom: 25px; }
        .role-option { flex: 1; text-align: center; }
        .role-option input[type="radio"] { display: none; }
        .role-card { padding: 20px 10px; border: 2px solid var(--border); border-radius: 15px; cursor: pointer; transition: all 0.3s ease; font-weight: 800; font-size: 0.95rem; color: var(--text-gray); background: white; display: block; margin: 0; }
        .role-card i { display: block; font-size: 28px; margin-bottom: 10px; }

        #rol_alumno:checked + .role-card { background-color: var(--pink-accent); color: white; border-color: var(--pink-accent); transform: translateY(-4px); box-shadow: 0 10px 20px rgba(255, 179, 198, 0.4); }
        #rol_maestro:checked + .role-card { background-color: var(--green-accent); color: white; border-color: var(--green-accent); transform: translateY(-4px); box-shadow: 0 10px 20px rgba(120, 198, 163, 0.4); }
        #rol_tutor:checked + .role-card { background-color: var(--primary); color: white; border-color: var(--primary); transform: translateY(-4px); box-shadow: 0 10px 20px rgba(81, 204, 204, 0.4); }

        .dynamic-section { display: none; background: #F8FAFC; padding: 20px; border-radius: 15px; border: 2px dashed #CBD5E1; margin-bottom: 20px; }
        
        /* Ajuste para los inputs con la regla de mayusculas */
        .input-capitalizado { text-transform: capitalize; }
    </style>
</head>
<body>

    <header class="header position-relative">
        <a href="index.php">
            <img src="imagenes/logo (1).png" alt="NUMI Logo" style="height: 70px;">
        </a>
        <a href="index.php" class="btn position-absolute d-none d-md-inline-block" style="right: 20px; top: 50%; transform: translateY(-50%); background-color: var(--green-accent); color: white; font-weight: bold; border-radius: 8px;">
            <i class="fa fa-home"></i> Regresar al inicio
        </a>
    </header>

    <main class="registration_section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    
                    <div class="login-card">
                        <h2 style="font-weight: 900; margin-bottom: 5px; text-align: center; color: var(--purple-header);">¡Únete a la familia NUMI!</h2>
                        <p class="text-muted mb-4" style="text-align: center;">Selecciona tu perfil y personaliza tu experiencia.</p>
                        
                        <?php if(isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>

                        <form action="nuevo_registro.php" method="POST" id="registroForm">
                            
                            <div class="role-selector">
                                <div class="role-option">
                                    <input type="radio" name="tipo_rol" id="rol_alumno" value="alumno" required onchange="mostrarCampos()">
                                    <label class="role-card" for="rol_alumno"><i class="fa fa-book"></i> Estudiante</label>
                                </div>
                                <div class="role-option">
                                    <input type="radio" name="tipo_rol" id="rol_maestro" value="maestro" onchange="mostrarCampos()">
                                    <label class="role-card" for="rol_maestro"><i class="fa fa-hourglass-half"></i> Maestro</label>
                                </div>
                                <div class="role-option">
                                    <input type="radio" name="tipo_rol" id="rol_tutor" value="tutor" onchange="mostrarCampos()">
                                    <label class="role-card" for="rol_tutor"><i class="fa fa-eye"></i> Tutor</label>
                                </div>
                            </div>
                            <hr>

                            <div class="form-group">
                                <label>Nombre(s)</label>
                                <input type="text" name="nombre" class="form-control input-capitalizado" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Apellido Paterno</label>
                                    <input type="text" name="apellido_p" class="form-control input-capitalizado" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Apellido Materno</label>
                                    <input type="text" name="apellido_m" class="form-control input-capitalizado">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label>Edad</label>
                                    <input type="number" name="edad" id="input_edad" class="form-control" required>
                                </div>
                                <div class="col-md-8 form-group">
                                    <label>Sexo</label>
                                    <select name="sexo" class="form-control">
                                        <option value="Masculino">Masculino</option>
                                        <option value="Femenino">Femenino</option>
                                        <option value="Otro">Otro</option>
                                    </select>
                                </div>
                            </div>

                            <!-- SECCIÓN ALUMNOS CORREGIDA (Nivel + Escuela + Grado + Grupo) -->
                            <div id="campos_alumno" class="dynamic-section">
                                <h6 style="font-weight: 800; color: #E84A70;">Datos del Estudiante</h6>
                                
                                <div class="form-group mt-2">
                                    <label>Nivel Educativo</label>
                                    <select name="nivel_educativo" id="req_nivel_alum" class="form-control">
                                        <option value="">Selecciona un nivel...</option>
                                        <option value="Primaria">Primaria</option>
                                        <option value="Secundaria">Secundaria</option>
                                    </select>
                                </div>

                                <div class="form-group mt-2">
                                    <label>Escuela</label>
                                    <input type="text" name="escuela_alumno" id="req_escuela_alum" class="form-control">
                                </div>
                                <div class="row">
                                    <div class="col-6 form-group">
                                        <label>Grado</label>
                                        <input type="text" name="grado_alumno" id="req_grado_alum" class="form-control">
                                    </div>
                                    <div class="col-6 form-group">
                                        <label>Grupo</label>
                                        <input type="text" name="grupo_alumno" id="req_grupo_alum" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div id="campos_maestro" class="dynamic-section">
                                <h6 style="font-weight: 800; color: #2D8A5F;">Datos del Maestro</h6>
                                <div class="form-group mt-2">
                                    <label>Escuela donde labora</label>
                                    <input type="text" name="escuela_maestro" id="req_escuela_mae" class="form-control">
                                </div>
                                <div class="form-group"><label>Grado que imparte</label><input type="text" name="grado_maestro" id="req_grado_mae" class="form-control"></div>
                            </div>

                            <div id="campos_tutor" class="dynamic-section">
                                <h6 style="font-weight: 800; color: var(--primary);">Vinculación</h6>
                                <p style="font-size: 0.8rem; color: #666; margin-bottom: 10px;">Pídele al alumno que inicie sesión y te pase el "ID Tutor" que sale en la barra superior.</p>
                                <div class="form-group"><label>ID del Alumno a vincular</label><input type="number" name="id_alumno_vinculo" id="req_id_alumno" class="form-control"></div>
                            </div>

                            <hr>
                            <div class="form-group"><label>Correo electrónico</label><input type="email" name="correo" class="form-control" required></div>
                            <div class="form-group"><label>Contraseña</label><input type="password" name="contrasena" class="form-control" required></div>

                            <button type="submit" name="register" class="btn-login">Comenzar Aventura</button>                        
                        </form>
                    </div>
                    
                    <div style="text-align: center; margin-top: 20px;">
                        <p style="color: #666; font-size: 14px;">¿Ya tienes una cuenta? <a href="iniciarsesion.php" style="color: #9B82DF; text-decoration: none; font-weight: bold;">Inicia sesión aquí</a></p>
                    </div>

                </div>
            </div>
        </div>
    </main>

<script>
    function mostrarCampos() {
        document.getElementById('campos_alumno').style.display = 'none';
        document.getElementById('campos_maestro').style.display = 'none';
        document.getElementById('campos_tutor').style.display = 'none';

        // Desactivamos temporalmente todos los campos
        document.getElementById('req_nivel_alum').required = false;
        document.getElementById('req_escuela_alum').required = false;
        document.getElementById('req_grado_alum').required = false;
        document.getElementById('req_grupo_alum').required = false;
        document.getElementById('req_escuela_mae').required = false;
        document.getElementById('req_grado_mae').required = false;
        document.getElementById('req_id_alumno').required = false;

        document.getElementById('input_edad').min = 0;
        document.getElementById('input_edad').max = 100;

        let rol_seleccionado = document.querySelector('input[name="tipo_rol"]:checked');

        if (rol_seleccionado) {
            let valor = rol_seleccionado.value;
            if (valor === 'alumno') {
                document.getElementById('campos_alumno').style.display = 'block';
                // Activamos los campos del alumno
                document.getElementById('req_nivel_alum').required = true;
                document.getElementById('req_escuela_alum').required = true;
                document.getElementById('req_grado_alum').required = true;
                document.getElementById('req_grupo_alum').required = true;
                document.getElementById('input_edad').min = 5;
                document.getElementById('input_edad').max = 15;
            } else if (valor === 'maestro') {
                document.getElementById('campos_maestro').style.display = 'block';
                document.getElementById('req_escuela_mae').required = true;
                document.getElementById('req_grado_mae').required = true;
                document.getElementById('input_edad').min = 18;
                document.getElementById('input_edad').max = 90;
            } else if (valor === 'tutor') {
                document.getElementById('campos_tutor').style.display = 'block';
                document.getElementById('req_id_alumno').required = true;
                document.getElementById('input_edad').min = 18;
                document.getElementById('input_edad').max = 90;
            }
        }
    }

    // Capitalizar las letras iniciales en los inputs de nombre al escribir
    document.querySelectorAll('.input-capitalizado').forEach(function(input) {
        input.addEventListener('input', function(e) {
            let p = e.target.selectionStart;
            e.target.value = e.target.value.toLowerCase().replace(/(?:^|\s)\S/g, function(a) { return a.toUpperCase(); });
            e.target.selectionStart = p;
            e.target.selectionEnd = p;
        });
    });

    // Llamar a la función al cargar la página por si se recarga y hay algo seleccionado
    document.addEventListener("DOMContentLoaded", mostrarCampos);
</script>

</body>
</html>