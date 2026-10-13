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

// Verificar que las variables de sesión existan
if (!isset($_SESSION['id_maestro']) || !isset($_SESSION['escuela_maestro']) || !isset($_SESSION['grado_maestro'])) {
    die("Error: Faltan datos del maestro. Por favor, inicia sesión nuevamente.");
}

$id_maestro = $_SESSION['id_maestro'];
$escuela = $_SESSION['escuela_maestro'];
$grado = $_SESSION['grado_maestro'];

// 1. GENERAR UN CÓDIGO ÚNICO (6 caracteres alfanuméricos)
$codigo_generado = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);

$mensaje_exito = "";
$mensaje_error = "";

// 2. PROCESAR EL FORMULARIO SI EL MAESTRO DA CLIC EN "CREAR CLASE"
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_clase = $conn->real_escape_string($_POST['nombre_clase']);
    $codigo = $conn->real_escape_string($_POST['codigo_unico']);
    $alumnos_seleccionados = $_POST['alumnos'] ?? []; 

    // Insertar la nueva clase
    $query_clase = "INSERT INTO clases (id_maestro, nombre_clase, codigo_unico) VALUES ('$id_maestro', '$nombre_clase', '$codigo')";
    
    if ($conn->query($query_clase) === TRUE) {
        $id_clase_nueva = $conn->insert_id;

        // Vincular a los alumnos seleccionados
        if (!empty($alumnos_seleccionados)) {
            foreach ($alumnos_seleccionados as $id_alumno) {
                $id_alumno_clean = intval($id_alumno);
                $query_vincular = "INSERT INTO clase_alumno (id_clase, id_alumno) VALUES ('$id_clase_nueva', '$id_alumno_clean')";
                $conn->query($query_vincular);
            }
        }
        $mensaje_exito = "¡Clase creada con éxito! El código de acceso es: <strong>$codigo</strong>";
    } else {
        $mensaje_error = "Error al crear la clase: " . $conn->error;
    }
}

// 3. CONSULTAR ALUMNOS DE LA MISMA ESCUELA Y GRADO
$query_alumnos = "SELECT a.id_alumno, u.nombre, u.apellido_p, u.apellido_m, a.grupo 
                  FROM alumnos a 
                  INNER JOIN usuarios u ON a.id_usuario = u.id_usuario 
                  WHERE a.escuela = '$escuela' AND a.grado = '$grado'";
$resultado_alumnos = $conn->query($query_alumnos);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Crear Nueva Clase | NUMI</title>
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

        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        body { background-color: #f7f9fb; font-family: 'Inter', sans-serif; }
        .editorial-gradient { background: linear-gradient(135deg, #EEB4C5 0%, #A188DD 100%); color: white; }
        .form-container-box { background-color: #ffffff; border-radius: 12px; box-shadow: 0 10px 40px rgba(0,0,0,0.05); overflow: hidden; border: 1px solid rgba(196, 198, 205, 0.2); }
        .form-editorial-input { width: 100%; border: none; border-bottom: 1px solid #e2e8f0; padding: 12px 0 8px 0; background: transparent; outline: none; transition: border-color 0.3s ease; color: #191c1e; font-family: 'Inter', sans-serif; }
        .form-editorial-input:focus { border-bottom-color: #2b3548; }
        .form-editorial-label { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.15em; color: #64748b; font-weight: 700; margin-bottom: 4px; display: block; }
        .editorial-btn { background-color: #2b3548; color: white; padding: 14px 20px; border: none; border-radius: 6px; font-family: 'Manrope', sans-serif; font-weight: 700; font-size: 0.875rem; transition: all 0.3s ease; display: inline-flex; align-items: center; justify-content: center; gap: 8px; width: 100%; cursor: pointer; }
        .editorial-btn:hover { background-color: #1a2233; transform: translateY(-2px); color: white; }
        .editorial-btn .material-symbols-outlined { transition: transform 0.3s ease; font-size: 1.2rem; }
        .editorial-btn:hover .material-symbols-outlined { transform: translateX(4px); }
        .back-arrow-link { color: #64748b; text-decoration: none; transition: all 0.3s ease; display: inline-flex; align-items: center; }
        .back-arrow-link:hover { color: #2b3548; transform: translateX(-4px); text-decoration: none; }
        .students-table-container { max-height: 250px; overflow-y: auto; border: 1px solid #e2e8f0; border-radius: 8px; margin-top: 10px; }
        .custom-table { width: 100%; margin-bottom: 0; font-size: 0.875rem; }
        .custom-table th { background-color: #f8fafc; color: #64748b; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.1em; padding: 10px 12px; border-bottom: 1px solid #e2e8f0; position: sticky; top: 0; z-index: 1; }
        .custom-table td { padding: 10px 12px; vertical-align: middle; border-bottom: 1px solid #f1f5f9; }
        .custom-checkbox { width: 18px; height: 18px; cursor: pointer; accent-color: #A188DD; }
        .badge-info-numi { background-color: #f1f5f9; color: #475569; font-size: 0.75rem; padding: 6px 12px; border-radius: 20px; font-weight: 600; display: inline-block; }
        /* Estilo para el nuevo botón de estadísticas */
        .btn-stats { background-color: rgba(255,255,255,0.9); color: #A188DD; font-weight: 800; border-radius: 8px; padding: 12px 20px; border: none; display: inline-flex; align-items: center; justify-content: center; gap: 8px; text-decoration: none; transition: all 0.3s; box-shadow: 0 4px 15px rgba(0,0,0,0.1); width: 100%; margin-top: 20px; }
        .btn-stats:hover { background-color: #ffffff; transform: translateY(-3px); color: #8b6dcc; text-decoration: none; }
    </style>
</head>
<body>

<div class="super_container">
    <!-- HEADER -->
    <header class="header">
        <div class="header_container" style="background-color: #A188DD; border-bottom: 1px solid #e2e8f0;">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="header_content d-flex flex-row align-items-center justify-content-center">
                            <div style="background-color: #ffffff00; padding: 10px; border-radius: 8px; display: inline-block;">
                                <div class="logo_container">
                                    <a href="index.html">
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

    <!-- CONTENIDO PRINCIPAL -->
    <div class="container d-flex align-items-center justify-content-center" style="min-height: calc(100vh - 200px); padding-top: 40px; padding-bottom: 80px;">
        <div class="row w-100 justify-content-center align-items-stretch">
            
            <!-- COLUMNA IZQUIERDA: FORMULARIO -->
            <div class="col-12 col-lg-7 mb-4 mb-lg-0">
                <div class="form-container-box p-4 p-md-5 h-100">
                    <div class="mb-4">
                        <h3 style="font-family: 'Manrope', sans-serif; font-size: 1.75rem; font-weight: 800; color: #191c1e; margin-bottom: 5px;">Crear Nueva Clase</h3>
                        <p style="color: #64748b; font-size: 0.875rem;">Configura la materia y asigna los alumnos para tu nuevo grupo.</p>
                    </div>

                    <?php if (!empty($mensaje_exito)): ?>
                        <div class="alert alert-success border-0 shadow-sm" role="alert" style="border-radius: 8px; background-color: #d1e7dd; color: #0f5132;">
                            <?php echo $mensaje_exito; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($mensaje_error)): ?>
                        <div class="alert alert-danger border-0 shadow-sm" role="alert" style="border-radius: 8px;">
                            <?php echo $mensaje_error; ?>
                        </div>
                    <?php endif; ?>

                    <form action="" method="POST">
                        <div class="row">
                            <div class="col-md-7 mb-4">
                                <label class="form-editorial-label" for="nombre_clase">NOMBRE DE LA CLASE / MATERIA</label>
                                <input type="text" class="form-editorial-input" id="nombre_clase" name="nombre_clase" placeholder="Ej. Ciencias Naturales 1A" required>
                            </div>

                            <div class="col-md-5 mb-4">
                                <label class="form-editorial-label" for="codigo_unico">CÓDIGO ÚNICO DE CLASE</label>
                                <input type="text" class="form-editorial-input" id="codigo_unico" name="codigo_unico" value="<?php echo $codigo_generado; ?>" readonly style="background: #f8fafc; font-weight: 800; letter-spacing: 2px; color: #A188DD;">
                            </div>
                        </div>

                        <!-- SECCIÓN SELECCIÓN DE ALUMNOS -->
                        <div class="mt-2 mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-editorial-label mb-0">SELECCIONAR ALUMNOS REGISTRADOS</label>
                                <span class="badge-info-numi">
                                    <?php echo htmlspecialchars($escuela); ?> — <?php echo htmlspecialchars($grado); ?>
                                </span>
                            </div>

                            <?php if ($resultado_alumnos && $resultado_alumnos->num_rows > 0): ?>
                                <div class="students-table-container">
                                    <table class="table custom-table table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-center" style="width: 50px;">
                                                    <span class="material-symbols-outlined" style="font-size: 1.1rem; vertical-align: middle;">check_box</span>
                                                </th>
                                                <th>Nombre Completo del Alumno</th>
                                                <th class="text-center" style="width: 80px;">Grupo</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while($row = $resultado_alumnos->fetch_assoc()): ?>
                                            <tr>
                                                <td class="text-center">
                                                    <input type="checkbox" class="custom-checkbox" name="alumnos[]" value="<?php echo $row['id_alumno']; ?>" checked>
                                                </td>
                                                <td style="font-weight: 500; color: #334155;">
                                                    <?php echo htmlspecialchars($row['nombre'] . ' ' . $row['apellido_p'] . ' ' . $row['apellido_m']); ?>
                                                </td>
                                                <td class="text-center" style="font-weight: 700; color: #64748b;">
                                                    <?php echo htmlspecialchars($row['grupo']); ?>
                                                </td>
                                            </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="p-3 text-center style-no-alumnos" style="background: #f8fafc; border-radius: 8px; border: 1px dashed #cbd5e1; color: #64748b; font-size: 0.85rem;">
                                    <span class="material-symbols-outlined d-block mb-1" style="font-size: 1.8rem; color: #94a3b8;">group_off</span>
                                    No hay alumnos registrados aún para <strong><?php echo htmlspecialchars($escuela); ?></strong> (<?php echo htmlspecialchars($grado); ?>).
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- BOTÓN DE ENVÍO -->
                        <div class="mt-4 pt-2">
                            <button type="submit" class="editorial-btn">
                                Guardar Clase y Alumnos
                                <span class="material-symbols-outlined">add_task</span>
                            </button>
                        </div>

                        <!-- SECCIÓN INFERIOR: VOLVER AL PANEL -->
                        <div class="d-flex justify-content-between align-items-center mt-4 pt-3" style="border-top: 1px solid #e2e8f0; font-size: 0.7rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 1px;">
                            <a href="paneladmin.php" class="back-arrow-link" title="Volver al Panel Principal">
                                <span class="material-symbols-outlined" style="font-size: 1.5rem;">arrow_back</span>
                            </a>
                            <span style="color: #64748b;">Ecosistema NUMI Docentes</span>
                        </div>
                    </form>
                </div>
            </div>

            <!-- COLUMNA DERECHA: PANEL VISUAL DEGRADADO -->
            <div class="col-12 col-lg-5">
                <div class="form-container-box p-5 h-100 editorial-gradient d-flex flex-column justify-content-center" style="border: none;">
                    <span style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.15em; color: #000000; font-weight: 700;">Gestión de Aulas</span>
                    <h3 style="font-family: 'Manrope', sans-serif; font-size: 2rem; font-weight: 800; color: #ffffff; line-height: 1.2; margin-top: 10px; margin-bottom: 20px;">
                        Organiza tu<br>Clase.
                    </h3>
                    <div style="width: 50px; height: 2px; background-color: #000000; opacity: 0.3; margin-bottom: 30px;"></div>
                    <p style="color: #000000; font-size: 0.95rem; line-height: 1.8; margin-bottom: 30px;">
                        Genera grupos dinámicos en segundos. El código único autogenerado permitirá que tus alumnos se conecten directamente a tus lecciones interactiva y actividades de Realidad Aumentada.
                    </p>
                    <div class="d-flex align-items-center mb-3" style="gap: 10px; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.15em; color: #ffffff; font-weight: 700;">
                        <span class="material-symbols-outlined" style="font-size: 1.5rem;">school</span>
                        <span>Vinculación Automática de Estudiantes</span>
                    </div>

                    <!-- AQUÍ ESTÁ EL NUEVO BOTÓN QUE LLEVA A LAS ESTADÍSTICAS -->
                    <a href="estadisticas_maestro.php" class="btn-stats">
                        <span class="material-symbols-outlined">analytics</span>
                        Ver Progreso de Alumnos
                    </a>

                </div>
            </div>

        </div>
    </div>

    <!-- FOOTER -->
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
                                    <div class="footer_social">
                                        <ul>
                                            <li><a href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
                                        </ul>
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