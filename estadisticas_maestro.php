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

// Verificar sesión
if (!isset($_SESSION['id_maestro'])) {
    die("Error: Faltan datos del maestro. Por favor, inicia sesión nuevamente.");
}

$id_maestro = $_SESSION['id_maestro'];

// 1. OBTENER PROGRESO POR LECCIÓN
function obtenerProgresoLeccion($id_leccion, $id_alumno, $conn) {
    $sql = "SELECT MAX(num_intentos) as max_progreso FROM progresos WHERE id_leccion = ? AND id_alumno = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    if (!$stmt) return 0;
    
    $stmt->bind_param("ii", $id_leccion, $id_alumno);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    
    return isset($res['max_progreso']) ? intval($res['max_progreso']) : 0;
}

// 2. LECCIONES DINÁMICAS
$lecciones = [];
$sql_lecciones = "SELECT id_leccion, nombre, objetivo FROM lecciones ORDER BY id_leccion ASC";
$res_lecciones = $conn->query($sql_lecciones);
if ($res_lecciones) {
    while($row = $res_lecciones->fetch_assoc()) {
        $lecciones[] = $row;
    }
}

// 3. ALUMNOS VINCULADOS A ESTE MAESTRO
$sql_alumnos = "
    SELECT DISTINCT a.id_alumno, u.nombre, u.apellido_p, u.apellido_m, a.grado, a.grupo 
    FROM alumnos a 
    INNER JOIN usuarios u ON a.id_usuario = u.id_usuario 
    INNER JOIN clase_alumno ca ON a.id_alumno = ca.id_alumno 
    INNER JOIN clases c ON ca.id_clase = c.id_clase 
    WHERE c.id_maestro = ? 
    ORDER BY u.nombre ASC
";
$stmt_alumnos = $conn->prepare($sql_alumnos);
$stmt_alumnos->bind_param("i", $id_maestro);
$stmt_alumnos->execute();
$resultado_estudiantes = $stmt_alumnos->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Estadísticas de Alumnos | NUMI</title>
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
        .stat-card { background: #ffffff; border-radius: 16px; box-shadow: 0 10px 30px rgba(20, 16, 34, 0.05); overflow: hidden; border: 1px solid #e2e8f0; margin-bottom: 20px; }
        
        /* Modificaciones para el botón desplegable */
        .stat-header { background: linear-gradient(90deg, #A188DD, #51CCCC); padding: 15px 25px; display: flex; align-items: center; cursor: pointer; transition: opacity 0.2s; }
        .stat-header:hover { opacity: 0.9; }
        .dropdown-icon { color: white; font-size: 28px; transition: transform 0.3s; }
        .stat-header[aria-expanded="true"] .dropdown-icon { transform: rotate(180deg); }

        .avatar { background: white; border-radius: 50%; width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; font-weight: 800; color: #A188DD; margin-right: 15px; font-size: 20px; font-family: 'Manrope', sans-serif; flex-shrink: 0; }
        .stat-table { width: 100%; border-collapse: collapse; text-align: left; margin: 0; }
        .stat-table th { padding: 12px 25px; font-weight: 700; color: #141022; font-size: 13px; text-transform: uppercase; border-bottom: 2px solid #e2e8f0; }
        .stat-table td { padding: 15px 25px; border-bottom: 1px solid #edf2f7; vertical-align: middle; }
        .progress-circle { width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; }
        .progress-circle-inner { width: 38px; height: 38px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; color: #141022; font-size: 11px; }
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
                        <div class="header_content d-flex flex-row align-items-center justify-content-between">
                            <div style="background-color: #ffffff00; padding: 10px; border-radius: 8px;">
                                <a href="index.html">
                                    <img src="imagenes/logosinfondo.png" alt="Logo NUMI" style="height:60px;">
                                </a>
                            </div>
                            <a href="crear_clase.php" class="btn btn-light" style="font-weight: 700; color: #A188DD; border-radius: 8px;">Volver a Clases</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div style="height: 130px;"></div>

    <!-- CONTENIDO PRINCIPAL -->
    <div class="container mb-5" style="padding-top: 20px; padding-bottom: 80px;">
        <div class="row justify-content-center mb-5">
            <div class="col-lg-10 text-center">
                <h2 style="font-family: 'Manrope', sans-serif; font-weight: 800; color: #191c1e;">Panel de Progreso</h2>
                <p style="color: #64748b; font-size: 16px; font-weight: 500;">Monitoriza el avance individual de tus alumnos en Unity</p>
            </div>
        </div> 

        <div class="accordion" id="accordionAlumnos">
        <?php 
        if ($resultado_estudiantes && $resultado_estudiantes->num_rows > 0): 
            while ($alumno = $resultado_estudiantes->fetch_assoc()): 
                $id_alum = $alumno['id_alumno'];
                $nombre_completo = htmlspecialchars($alumno['nombre'] . ' ' . $alumno['apellido_p']);
                $grado_grupo = htmlspecialchars($alumno['grado'] . " - " . $alumno['grupo']);
        ?>
        
        <div class="stat-card">
            <!-- CABECERA DESPLEGABLE -->
            <div class="stat-header collapsed" data-toggle="collapse" data-target="#collapse_<?php echo $id_alum; ?>" aria-expanded="false" aria-controls="collapse_<?php echo $id_alum; ?>">
                <div class="avatar"><?php echo strtoupper(substr($alumno['nombre'], 0, 1)); ?></div>
                <div style="flex-grow: 1;">
                    <h3 style="color: white; margin: 0; font-size: 18px; font-family: 'Manrope', sans-serif; font-weight: 700;"><?php echo $nombre_completo; ?></h3>
                    <span style="color: rgba(255,255,255,0.8); font-size: 13px; font-weight: 500;">Grupo: <?php echo $grado_grupo; ?></span>
                </div>
                <div>
                    <span class="material-symbols-outlined dropdown-icon">expand_more</span>
                </div>
            </div>

            <!-- CONTENIDO OCULTO (TABLA DE NIVELES) -->
            <div id="collapse_<?php echo $id_alum; ?>" class="collapse" data-parent="#accordionAlumnos">
                <div class="p-0">
                    <table class="stat-table">
                        <thead>
                            <tr>
                                <th style="width: 70%;">Nivel / Lección</th>
                                <th style="width: 30%; text-align: center;">Completado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lecciones as $lec): 
                                $progreso_actual = obtenerProgresoLeccion($lec['id_leccion'], $id_alum, $conn);
                            ?>
                            <tr>
                                <td style="color: #475569;">
                                    <strong style="color: #141022; font-family: 'Inter', sans-serif;"><?php echo htmlspecialchars($lec['nombre']); ?></strong><br>
                                    <span style="font-size: 12px;"><?php echo htmlspecialchars($lec['objetivo']); ?></span>
                                </td>
                                <td>
                                    <div class="progress-circle" style="background: conic-gradient(#51CCCC <?php echo $progreso_actual; ?>%, #e2e8f0 0);">
                                        <div class="progress-circle-inner"><?php echo $progreso_actual; ?>%</div>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <?php 
            endwhile; 
        else: 
        ?>
            <div class="alert alert-info text-center" style="border-radius: 12px; padding: 20px; border: 1px dashed #cbd5e1; background: #f8fafc; color: #64748b;">
                <span class="material-symbols-outlined d-block mb-2" style="font-size: 2rem;">analytics</span>
                Aún no tienes alumnos vinculados a tus clases. Crea una clase primero.
            </div>
        <?php endif; ?>
        </div> <!-- Fin de accordionAlumnos -->
    </div>

    <!-- FOOTER -->
    <footer class="footer mt-auto">
        <div class="footer_background" style="background-image:url(imagenes/background.png)"></div>
        <div class="container">
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