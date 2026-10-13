<?php
session_start();

// 1. Verificar que sea un tutor autenticado
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['rol']) || $_SESSION['rol'] !== 'tutor') {
    header("Location: login_alumno.php");
    exit();
}

$id_usuario_tutor = $_SESSION['usuario_id'];
$nombre_tutor = $_SESSION['usuario_nombre'] ?? 'Tutor';

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
// FUNCIÓN PARA OBTENER EL PROGRESO
// ==========================================
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

// ==========================================
// BUSCAR AL HIJO VINCULADO
// ==========================================
$sql_vinculo = "SELECT t.id_alumno, a.escuela, a.grado, a.grupo, u.nombre AS nombre_alumno, u.correo 
                FROM tutores t 
                JOIN alumnos a ON t.id_alumno = a.id_alumno 
                JOIN usuarios u ON a.id_usuario = u.id_usuario 
                WHERE t.id_usuario = ? LIMIT 1";

$stmt = $conn->prepare($sql_vinculo);
$stmt->bind_param("i", $id_usuario_tutor);
$stmt->execute();
$resultado_hijo = $stmt->get_result();
$hijo = $resultado_hijo->fetch_assoc();

// Variables para materias y lecciones
$clases_alumno = [];
$lecciones = [];

if ($hijo) {
    $id_alumno = $hijo['id_alumno'];
    
    // Consultar clases
    $sql_clases = "SELECT c.nombre_clase, m.escuela AS maestro_escuela 
                   FROM clase_alumno ca
                   JOIN clases c ON ca.id_clase = c.id_clase
                   LEFT JOIN maestros m ON c.id_maestro = m.id_maestro
                   WHERE ca.id_alumno = ?";
    $stmt_clases = $conn->prepare($sql_clases);
    if($stmt_clases) {
        $stmt_clases->bind_param("i", $id_alumno);
        $stmt_clases->execute();
        $clases_alumno = $stmt_clases->get_result();
    }

    // Consultar el catálogo de lecciones dinámicas
    $sql_lecciones = "SELECT id_leccion, nombre, objetivo FROM lecciones ORDER BY id_leccion ASC";
    $res_lecciones = $conn->query($sql_lecciones);
    if ($res_lecciones) {
        while($row = $res_lecciones->fetch_assoc()) {
            $lecciones[] = $row;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Progreso del Alumno - NUMI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="styles/bootstrap4/bootstrap.min.css">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@400;700;800&family=Inter:wght@400;500;600&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap');

        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        body { background-color: #f7f9fb; font-family: 'Inter', sans-serif; }
        
        /* Estilos de Tarjeta Desplegable */
        .stat-card { background: #ffffff; border-radius: 16px; box-shadow: 0 10px 30px rgba(20, 16, 34, 0.05); overflow: hidden; border: 1px solid #e2e8f0; margin-bottom: 20px; }
        .stat-header { background: linear-gradient(90deg, #A188DD, #51CCCC); padding: 15px 25px; display: flex; align-items: center; cursor: pointer; transition: opacity 0.2s; }
        .stat-header:hover { opacity: 0.9; }
        .dropdown-icon { color: white; font-size: 28px; transition: transform 0.3s; }
        .stat-header[aria-expanded="true"] .dropdown-icon { transform: rotate(180deg); }
        
        .avatar { background: white; border-radius: 50%; width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; font-weight: 800; color: #A188DD; margin-right: 15px; font-size: 20px; font-family: 'Manrope', sans-serif; flex-shrink: 0; }
        
        /* Tablas y Círculos de Progreso */
        .stat-table { width: 100%; border-collapse: collapse; text-align: left; margin: 0; }
        .stat-table th { padding: 12px 25px; font-weight: 700; color: #141022; font-size: 13px; text-transform: uppercase; border-bottom: 2px solid #e2e8f0; background: #f8fafc; }
        .stat-table td { padding: 15px 25px; border-bottom: 1px solid #edf2f7; vertical-align: middle; }
        .progress-circle { width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; }
        .progress-circle-inner { width: 38px; height: 38px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; color: #141022; font-size: 11px; }
        
        .info-box { background: #f8fafc; padding: 15px; border-radius: 8px; border: 1px solid #e2e8f0; height: 100%; }
        .info-label { font-size: 0.8rem; color: #64748b; text-transform: uppercase; font-weight: 700; margin-bottom: 5px; }
        .info-value { font-size: 1.1rem; color: #1e293b; font-weight: 700; margin: 0; }
    </style>
</head>
<body>

    <!-- Barra Superior -->
    <div style="background-color: #A188DD; padding: 15px 30px; color: white; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h3 style="margin: 0; font-family: 'Manrope', sans-serif; font-weight: 700; font-size: 1.4rem;">Portal de Tutor</h3>
        <a href="index.php" style="background: white; color: #A188DD; padding: 6px 15px; border-radius: 20px; font-weight: bold; text-decoration: none; font-size: 0.9rem;">Volver al Inicio</a>
    </div>

    <div class="container" style="margin-top: 40px; margin-bottom: 80px;">
        
        <div class="row justify-content-center mb-4">
            <div class="col-lg-10 text-center">
                <h2 style="font-family: 'Manrope', sans-serif; font-weight: 800; color: #191c1e;">¡Hola, <?php echo htmlspecialchars($nombre_tutor); ?>!</h2>
                <p style="color: #64748b; font-size: 16px; font-weight: 500;">Aquí puedes monitorear el avance de tu hijo en la plataforma.</p>
            </div>
        </div> 

        <?php if ($hijo): ?>
            <div class="accordion" id="accordionHijo">
                <div class="stat-card">
                    
                    <!-- CABECERA DESPLEGABLE -->
                    <div class="stat-header" data-toggle="collapse" data-target="#collapseHijo" aria-expanded="true" aria-controls="collapseHijo">
                        <div class="avatar"><?php echo strtoupper(substr($hijo['nombre_alumno'], 0, 1)); ?></div>
                        <div style="flex-grow: 1;">
                            <h3 style="color: white; margin: 0; font-size: 18px; font-family: 'Manrope', sans-serif; font-weight: 700;"><?php echo htmlspecialchars($hijo['nombre_alumno']); ?></h3>
                            <span style="color: rgba(255,255,255,0.8); font-size: 13px; font-weight: 500;">Código ID: #<?php echo htmlspecialchars($hijo['id_alumno']); ?></span>
                        </div>
                        <div>
                            <span class="material-symbols-outlined dropdown-icon">expand_more</span>
                        </div>
                    </div>

                    <!-- CONTENIDO DESPLEGABLE -->
                    <div id="collapseHijo" class="collapse show" data-parent="#accordionHijo">
                        
                        <!-- Información Escolar -->
                        <div style="padding: 25px; border-bottom: 1px solid #e2e8f0;">
                            <div class="row">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <div class="info-box">
                                        <p class="info-label">Escuela</p>
                                        <p class="info-value"><?php echo htmlspecialchars($hijo['escuela']); ?></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <p class="info-label">Grado y Grupo</p>
                                        <p class="info-value"><?php echo htmlspecialchars($hijo['grado']) . ' / ' . htmlspecialchars($hijo['grupo']); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Progreso de Lecciones -->
                        <div style="padding: 0;">
                            <?php if (!empty($lecciones)): ?>
                                <table class="stat-table">
                                    <thead>
                                        <tr>
                                            <th style="width: 70%;">Lecciones de Unity</th>
                                            <th style="width: 30%; text-align: center;">Porcentaje de Avance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($lecciones as $lec): 
                                            $progreso_actual = obtenerProgresoLeccion($lec['id_leccion'], $id_alumno, $conn);
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
                            <?php else: ?>
                                <div style="padding: 20px; text-align: center; color: #64748b;">Aún no hay lecciones registradas en el curso.</div>
                            <?php endif; ?>
                        </div>

                        <!-- Clases Inscritas -->
                        <div style="padding: 0;">
                            <table class="stat-table" style="border-top: 2px solid #e2e8f0;">
                                <thead>
                                    <tr>
                                        <th style="width: 70%; background: #fff5f8;">Materia / Clase Vinculada</th>
                                        <th style="width: 30%; background: #fff5f8; text-align: center;">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($clases_alumno && $clases_alumno->num_rows > 0): ?>
                                        <?php while ($clase = $clases_alumno->fetch_assoc()): ?>
                                            <tr>
                                                <td style="font-weight: 600; color: #1e293b;">📘 <?php echo htmlspecialchars($clase['nombre_clase'] ?? 'Sin nombre'); ?></td>
                                                <td style="text-align: center;">
                                                    <span style="background-color: #51CCCC; color: white; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: bold;">En curso</span>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="2" style="text-align: center; color: #64748b; padding: 20px;">El alumno aún no se ha inscrito a ninguna clase escolar.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

        <?php else: ?>
            <div class="alert alert-info text-center" style="border-radius: 12px; padding: 40px; border: 1px dashed #cbd5e1; background: #ffffff; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                <span class="material-symbols-outlined d-block mb-3" style="font-size: 3rem; color: #EEB4C5;">person_off</span>
                <h3 style="color: #1e293b; font-weight: bold; margin-bottom: 15px;">Ningún alumno vinculado</h3>
                <p style="color: #64748b; max-width: 500px; margin: 0 auto 20px auto;">
                    Tu cuenta de tutor no está conectada con ningún código de estudiante todavía. Por favor, asegúrate de haber colocado el ID correcto de tu hijo durante el registro.
                </p>
                <a href="logout.php" class="btn" style="background-color: #A188DD; color: white; font-weight: bold; border-radius: 20px; padding: 10px 25px;">Cerrar Sesión e Intentar Nuevamente</a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Scripts necesarios para que funcione el desplegable (Bootstrap 4) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>