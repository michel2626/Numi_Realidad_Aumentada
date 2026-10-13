<?php
session_start();

// 1. EL PORTERO
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// 2. CONEXIÓN A LA BASE DE DATOS
try {
    $conex = new mysqli("localhost", "root", "", "numi");
    $conex->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    die("<div style='color:red; text-align:center; margin-top:50px; font-family:sans-serif;'>
            <h3>Error de conexión a la Base de Datos</h3>
            <p>Asegúrate de que MySQL esté encendido en XAMPP.</p>
            <p>Detalle técnico: " . $e->getMessage() . "</p>
         </div>");
}

    if (!isset($_SESSION['csrf_baja_alumno'])) {
      $_SESSION['csrf_baja_alumno'] = bin2hex(random_bytes(32));
    }
    $mensaje_baja_alumno = $_SESSION['mensaje_baja_alumno'] ?? '';
    unset($_SESSION['mensaje_baja_alumno']);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['dar_de_baja'])) {
      $token_enviado = $_POST['csrf_baja_alumno'] ?? '';
      $id_alumno_baja = filter_var($_POST['id_alumno'] ?? null, FILTER_VALIDATE_INT);

      if (!hash_equals($_SESSION['csrf_baja_alumno'], $token_enviado) || !$id_alumno_baja) {
        $_SESSION['mensaje_baja_alumno'] = 'La solicitud de baja no es válida. Inténtalo de nuevo.';
      } else {
        try {
          $conex->begin_transaction();

          $stmt_alumno_baja = $conex->prepare('SELECT id_usuario FROM alumnos WHERE id_alumno = ? FOR UPDATE');
          $stmt_alumno_baja->bind_param('i', $id_alumno_baja);
          $stmt_alumno_baja->execute();
          $alumno_baja = $stmt_alumno_baja->get_result()->fetch_assoc();
          $stmt_alumno_baja->close();

          if (!$alumno_baja) {
            throw new RuntimeException('El alumno ya no existe.');
          }

          $stmt_clase_baja = $conex->prepare('DELETE FROM clase_alumno WHERE id_alumno = ?');
          $stmt_clase_baja->bind_param('i', $id_alumno_baja);
          $stmt_clase_baja->execute();
          $stmt_clase_baja->close();

          if (!empty($alumno_baja['id_usuario'])) {
            $stmt_usuario_baja = $conex->prepare('DELETE FROM usuarios WHERE id_usuario = ?');
            $stmt_usuario_baja->bind_param('i', $alumno_baja['id_usuario']);
            $stmt_usuario_baja->execute();
            $stmt_usuario_baja->close();
          } else {
            $stmt_alumno_baja = $conex->prepare('DELETE FROM alumnos WHERE id_alumno = ?');
            $stmt_alumno_baja->bind_param('i', $id_alumno_baja);
            $stmt_alumno_baja->execute();
            $stmt_alumno_baja->close();
          }

          $conex->commit();
          $_SESSION['mensaje_baja_alumno'] = 'El alumno y sus registros fueron dados de baja.';
        } catch (Throwable $e) {
          $conex->rollback();
          error_log('Error al dar de baja un alumno: ' . $e->getMessage());
          $_SESSION['mensaje_baja_alumno'] = 'No fue posible dar de baja al alumno. No se modificaron sus datos.';
        }
      }

      header('Location: paneladmin.php#seccion-bajas');
      exit();
    }

// ====================================================================
// EXPORTAR A EXCEL
// ====================================================================
if (isset($_GET['exportar']) && $_GET['exportar'] === 'excel') {
  header('Content-Type: application/vnd.ms-excel; charset=utf-8');
  header('Content-Disposition: attachment; filename="Reporte_Usuarios_NUMI.xls"');
  header('Cache-Control: no-store, no-cache, must-revalidate');

    $sql_csv = "SELECT u.id_usuario, u.nombre, u.correo, r.nombre as rol_nombre 
                FROM usuarios u 
                LEFT JOIN usuario_rol ur ON u.id_usuario = ur.id_usuario
                LEFT JOIN roles r ON ur.id_rol = r.id_rol";
    $res_csv = $conex->query($sql_csv);

  $celda_excel = static function ($valor, $tipo = 'String', $estilo = '') {
    $atributo_estilo = $estilo !== '' ? ' ss:StyleID="' . $estilo . '"' : '';
    $valor = htmlspecialchars((string) $valor, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    return '<Cell' . $atributo_estilo . '><Data ss:Type="' . $tipo . '">' . $valor . '</Data></Cell>';
  };

  echo '<?xml version="1.0" encoding="UTF-8"?>';
  echo '<?mso-application progid="Excel.Sheet"?>';
  echo '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">';
  echo '<Styles><Style ss:ID="Encabezado"><Font ss:Bold="1"/></Style></Styles>';
  echo '<Worksheet ss:Name="Usuarios"><Table><Row>'
    . $celda_excel('ID', 'String', 'Encabezado')
    . $celda_excel('Nombre', 'String', 'Encabezado')
    . $celda_excel('Correo', 'String', 'Encabezado')
    . $celda_excel('Rol', 'String', 'Encabezado')
    . '</Row>';

    while ($fila = $res_csv->fetch_assoc()) {
    echo '<Row>'
      . $celda_excel($fila['id_usuario'], 'Number')
      . $celda_excel($fila['nombre'])
      . $celda_excel($fila['correo'])
      . $celda_excel(strtoupper($fila['rol_nombre'] ?? 'SIN ROL'))
      . '</Row>';
    }
  echo '</Table></Worksheet></Workbook>';
    exit();
}

// 3. LÓGICA PARA ELIMINAR USUARIO
if (isset($_GET['eliminar'])) {
    $id_eliminar = intval($_GET['eliminar']);
    $conex->query("DELETE FROM usuario_rol WHERE id_usuario = '$id_eliminar'");
    $conex->query("DELETE FROM usuarios WHERE id_usuario = '$id_eliminar'");
    header("Location: paneladmin.php");
    exit();
}

// 4. CONSULTA PARA OBTENER LOS USUARIOS
$sql = "SELECT u.id_usuario, u.nombre, u.correo,
         COALESCE(GROUP_CONCAT(DISTINCT r.nombre ORDER BY r.nombre SEPARATOR ', '), 'Sin rol') AS roles
        FROM usuarios u 
  LEFT JOIN usuario_rol ur ON u.id_usuario = ur.id_usuario
  LEFT JOIN roles r ON ur.id_rol = r.id_rol
  GROUP BY u.id_usuario, u.nombre, u.correo
  ORDER BY u.id_usuario";
$consulta = $conex->query($sql);

$sql_alumnos_baja = "SELECT a.id_alumno, a.id_usuario, a.nivel_educativo, a.grado, a.grupo,
              u.nombre, u.apellido_p, u.apellido_m,
              COUNT(p.id_leccion) AS lecciones_con_avance,
              ROUND(AVG(p.porcentaje), 1) AS indice_progreso
           FROM alumnos a
           LEFT JOIN usuarios u ON a.id_usuario = u.id_usuario
           LEFT JOIN (
             SELECT id_alumno, id_leccion, MAX(num_intentos) AS porcentaje
             FROM progresos
             GROUP BY id_alumno, id_leccion
           ) p ON p.id_alumno = a.id_alumno
           GROUP BY a.id_alumno, a.id_usuario, a.nivel_educativo, a.grado, a.grupo,
                u.nombre, u.apellido_p, u.apellido_m
           ORDER BY u.nombre, u.apellido_p, a.grado, a.grupo";
$consulta_alumnos_baja = $conex->query($sql_alumnos_baja);
$alumnos_primaria = [];
$alumnos_secundaria = [];
while ($consulta_alumnos_baja && $alumno = $consulta_alumnos_baja->fetch_assoc()) {
  if (stripos($alumno['nivel_educativo'], 'secundaria') !== false) {
    $alumnos_secundaria[] = $alumno;
  } else {
    $alumnos_primaria[] = $alumno;
  }
}

// ====================================================================
// 5. LÓGICA DE ESTADÍSTICAS DINÁMICAS (CONECTADO A BD)
// ====================================================================

function obtenerPromedioTemaGeneral($conex, $lecciones) {
    $lecciones_str = implode(',', $lecciones);
    $sql = "SELECT AVG(max_progreso) as promedio_general FROM (
                SELECT id_alumno, MAX(num_intentos) as max_progreso 
                FROM progresos 
                WHERE id_leccion IN ($lecciones_str) 
                GROUP BY id_alumno, id_leccion
            ) as subconsulta";
    
    $res = $conex->query($sql);
    if ($res && $row = $res->fetch_assoc()) {
        return round((float)$row['promedio_general'], 1);
    }
    return 0;
}

// ARREGLO PRIMARIA (Lecciones BD: 1 al 9)
$temas_primaria = [
  ['nombre' => 'Figuras Geométricas', 'etiqueta' => 'Prim. Figuras', 'promedio' => obtenerPromedioTemaGeneral($conex, [1, 4, 5])],
  ['nombre' => 'El Metro', 'etiqueta' => 'Prim. Metro', 'promedio' => obtenerPromedioTemaGeneral($conex, [2, 6, 7])],
  ['nombre' => 'Números Enteros', 'etiqueta' => 'Prim. Enteros', 'promedio' => obtenerPromedioTemaGeneral($conex, [3, 8, 9])]
];

// ARREGLO SECUNDARIA (Lecciones BD: 10 al 18)
$temas_secundaria = [
  ['nombre' => 'Ángulos', 'etiqueta' => 'Sec. Ángulos', 'promedio' => obtenerPromedioTemaGeneral($conex, [10, 11, 12])],
  ['nombre' => 'Ecuaciones Lineales', 'etiqueta' => 'Sec. Ecuaciones', 'promedio' => obtenerPromedioTemaGeneral($conex, [13, 14, 15])],
  ['nombre' => 'Jerarquía de Op.', 'etiqueta' => 'Sec. Jerarquía', 'promedio' => obtenerPromedioTemaGeneral($conex, [16, 17, 18])]
];

$rendimiento_primaria_es_demo = max(array_column($temas_primaria, 'promedio')) == 0;
$rendimiento_secundaria_es_demo = max(array_column($temas_secundaria, 'promedio')) == 0;

if ($rendimiento_primaria_es_demo) {
  $promedios_prueba_primaria = [88, 82, 74];
  foreach ($temas_primaria as $indice => $tema) {
    $temas_primaria[$indice]['promedio'] = $promedios_prueba_primaria[$indice];
  }
}

if ($rendimiento_secundaria_es_demo) {
  $promedios_prueba_secundaria = [78, 61, 55];
  foreach ($temas_secundaria as $indice => $tema) {
    $temas_secundaria[$indice]['promedio'] = $promedios_prueba_secundaria[$indice];
  }
}

$temas_ordenados_por_dificultad = [];
foreach (['Primaria' => $temas_primaria, 'Secundaria' => $temas_secundaria] as $nivel => $temas) {
  foreach ($temas as $tema) {
    $temas_ordenados_por_dificultad[] = [
      'nombre' => $tema['etiqueta'],
      'dificultad' => max(0, 100 - (float)$tema['promedio'])
    ];
  }
}
usort($temas_ordenados_por_dificultad, function ($a, $b) {
  return $b['dificultad'] <=> $a['dificultad'];
});
$dificultad_temas_labels = array_column($temas_ordenados_por_dificultad, 'nombre');
$dificultad_temas_datos = array_column($temas_ordenados_por_dificultad, 'dificultad');

// Calcular Reprobados (Menos de 60% de promedio)
$sql_reprobados = "SELECT COUNT(*) as total_reprobados FROM (
                    SELECT id_alumno, AVG(num_intentos) as prom 
                    FROM progresos GROUP BY id_alumno HAVING prom < 60
                   ) as subq";
$res_reprobados = $conex->query($sql_reprobados);
$total_reprobados = ($res_reprobados && $row = $res_reprobados->fetch_assoc()) ? $row['total_reprobados'] : 0;

// Contar Alumnos Activos
$res_alumnos_activos = $conex->query("SELECT COUNT(DISTINCT id_alumno) as total_activos FROM progresos");
$total_activos = ($res_alumnos_activos && $row = $res_alumnos_activos->fetch_assoc()) ? $row['total_activos'] : 0;

$promedio_escuela = 0;
foreach($temas_primaria as $t) { $promedio_escuela += $t['promedio']; }
foreach($temas_secundaria as $t) { $promedio_escuela += $t['promedio']; }
$promedio_escuela = round($promedio_escuela / 6, 1);

// ====================================================================
// 6. DATOS SIMULADOS PARA MÉTRICAS INEXISTENTES EN BD (Faltas/Suspensiones)
// ====================================================================
$asistencias_24h_labels = ["07:00","08:00","09:00","10:00","11:00","12:00","13:00","14:00"];
$inasistencias_datos = [2, 5, 1, 0, 3, 2, 8, 4]; // Faltas detectadas por hora
$total_faltas_hoy = array_sum($inasistencias_datos);

$reportes_suspensiones = [
    ['motivo' => 'Falta de respeto al docente', 'cantidad' => 4],
    ['motivo' => 'Uso indebido de plataforma', 'cantidad' => 2],
    ['motivo' => 'Inasistencia recurrente', 'cantidad' => 6],
    ['motivo' => 'Plagio en actividades', 'cantidad' => 1]
];
$total_suspensiones = 13;

$matricula_primaria_labels = ["6°"];
$matricula_primaria_datos = [27];
$matricula_secundaria_labels = ["1°", "2°", "3°"];
$matricula_secundaria_datos = [34, 29, 25];
?>
<!doctype html>
<html lang="es">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Panel de Administrador | NUMI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc4/dist/css/adminlte.min.css" crossorigin="anonymous" />
    <!-- Chart.js para las gráficas -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        :root {
            --numi-purple: #aa90d6;
            --numi-purple-dark: #886eb0;
            --numi-teal: #4bc4c4;
            --numi-dark: #161224;
            --numi-light: #f8f9fa;
            --numi-danger: #e74c3c;
            --numi-warning: #f1c40f;
        }
        body { background-color: var(--numi-light); }
        .app-header { background-color: var(--numi-purple) !important; border-bottom: none; }
        .app-header .nav-link { color: white !important; font-weight: 600; }
        .app-sidebar { background-color: var(--numi-dark) !important; color: white; }
        .sidebar-brand { background-color: rgba(0,0,0,0.2) !important; color: white !important; }
        .nav-sidebar .nav-link:hover { background-color: var(--numi-teal) !important; color: white !important; }
        .numi-title { font-family: 'Georgia', serif; color: var(--numi-dark); font-weight: bold; text-align: center; margin-top: 20px; }
        .numi-subtitle { text-align: center; color: #6c757d; margin-bottom: 30px; }
        .numi-card { border: none; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); background: white; margin-bottom: 20px;}
        .numi-panel-header { background-color: var(--numi-purple); color: white; padding: 15px; font-weight: bold; font-size: 1.1rem; }
        .numi-table-header th { color: white; font-weight: 600; border: none; }
        .th-dark { background-color: var(--numi-dark) !important; }
        .th-teal { background-color: var(--numi-teal) !important; }
        .kpi-box { padding: 20px; border-radius: 10px; color: white; text-align: center; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .kpi-box h3 { font-size: 2.5rem; font-weight: bold; margin: 0; }
        .kpi-box p { margin: 0; font-size: 1.1rem; opacity: 0.9; }
    </style>
  </head>
  <body class="layout-fixed sidebar-expand-lg">
    <div class="app-wrapper">
      
      <!-- NAVBAR -->
      <nav class="app-header navbar navbar-expand">
        <div class="container-fluid">
          <ul class="navbar-nav"><li class="nav-item"><a class="nav-link" data-lte-toggle="sidebar" href="#"><i class="bi bi-list"></i></a></li></ul>
          <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown user-menu">
              <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                <i class="bi bi-person-circle fs-4"></i><span class="d-none d-md-inline ms-2">Administrador</span>
              </a>
              <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                <li class="user-header" style="background-color: var(--numi-purple); color:white;">
                  <i class="bi bi-person-circle display-4 text-white"></i>
                  <p>Administrador <small>Acceso Total NUMI</small></p>
                </li>
                <li class="user-footer">
                  <a href="logout.php" class="btn btn-default btn-flat float-end">Cerrar Sesión</a>
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </nav>

      <!-- SIDEBAR -->
      <aside class="app-sidebar shadow" data-bs-theme="dark">
        <div class="sidebar-brand">
          <a href="#" class="brand-link text-decoration-none text-white">
            <span class="brand-text fw-bold" style="letter-spacing: 2px;">NUMI PANEL</span>
          </a>
        </div>
        <div class="sidebar-wrapper">
          <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation">
              <li class="nav-item"><a href="#seccion-kpis" class="nav-link"><i class="nav-icon bi bi-speedometer"></i><p>Métricas Generales</p></a></li>
              <li class="nav-item"><a href="#seccion-graficas" class="nav-link"><i class="nav-icon bi bi-pie-chart-fill"></i><p>Gráficas de Desempeño</p></a></li>
              <li class="nav-item"><a href="#seccion-usuarios" class="nav-link"><i class="nav-icon bi bi-people-fill"></i><p>Gestión de Usuarios</p></a></li>
            </ul>
          </nav>
        </div>
      </aside>

      <!-- CONTENIDO PRINCIPAL -->
      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="numi-title" style="margin-top:0;">Administración NUMI</h1>
                    <p class="numi-subtitle" style="margin-bottom:0;">Mapeo de Analíticas Generales y Gestión Escolar</p>
                </div>
                <a href="paneladmin.php?exportar=excel" class="btn text-white fw-bold shadow" style="background-color: var(--numi-teal);">
                    <i class="bi bi-file-earmark-excel-fill"></i> Exportar a Excel
                </a>
            </div>
          </div>
        </div>
        
        <div class="app-content">
          <div class="container-fluid">
            
            <!-- SECCIÓN KPIs -->
            <div id="seccion-kpis" class="row mb-4">
                <div class="col-lg-3 col-6">
                    <div class="kpi-box" style="background-color: var(--numi-teal);">
                        <h3><?php echo $total_activos; ?></h3>
                        <p>Alumnos Activos</p>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="kpi-box" style="background-color: var(--numi-danger);">
                        <h3><?php echo $total_reprobados; ?></h3>
                        <p>Índice Reprobados</p>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="kpi-box" style="background-color: var(--numi-warning); color: var(--numi-dark);">
                        <h3><?php echo $total_faltas_hoy; ?></h3>
                        <p>Faltas Hoy</p>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="kpi-box" style="background-color: var(--numi-dark);">
                        <h3><?php echo $total_suspensiones; ?></h3>
                        <p>Reportes / Suspensión</p>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN GRÁFICAS -->
            <div id="seccion-graficas" class="row mb-4">
              
              <!-- Gráfica Primaria -->
              <div class="col-lg-6">
                <div class="numi-card">
                  <div class="numi-panel-header">Rendimiento: Primaria<?php echo $rendimiento_primaria_es_demo ? ' (datos de prueba)' : ''; ?></div>
                  <div class="card-body">
                    <canvas id="chartPrimaria" height="250"></canvas>
                  </div>
                </div>
              </div>

              <!-- Gráfica Secundaria -->
              <div class="col-lg-6">
                <div class="numi-card">
                  <div class="numi-panel-header" style="background-color: var(--numi-teal);">Rendimiento: Secundaria<?php echo $rendimiento_secundaria_es_demo ? ' (datos de prueba)' : ''; ?></div>
                  <div class="card-body">
                    <canvas id="chartSecundaria" height="250"></canvas>
                  </div>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="numi-card">
                  <div class="numi-panel-header" style="background-color: var(--numi-danger);">Temas con mayor dificultad estimada</div>
                  <div class="card-body">
                    <div style="position: relative; height: 250px;">
                      <canvas id="chartDificultad"></canvas>
                    </div>
                    <small class="text-muted">Estimación calculada como 100% menos el promedio de rendimiento.</small>
                  </div>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="numi-card">
                  <div class="numi-panel-header">Matrícula: 6.º año de Primaria (datos de prueba)</div>
                  <div class="card-body">
                    <canvas id="chartMatriculaPrimaria" height="250"></canvas>
                  </div>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="numi-card">
                  <div class="numi-panel-header" style="background-color: var(--numi-teal);">Matrícula por grado: Secundaria (datos de prueba)</div>
                  <div class="card-body">
                    <canvas id="chartMatriculaSecundaria" height="250"></canvas>
                  </div>
                </div>
              </div>

              <!-- Gráfica Asistencias 24 hrs -->
              <div class="col-lg-8">
                <div class="numi-card">
                  <div class="numi-panel-header" style="background-color: var(--numi-dark);">Control de Inasistencias (Actualización 24H)</div>
                  <div class="card-body">
                    <canvas id="chartInasistencias" height="100"></canvas>
                  </div>
                </div>
              </div>

              <!-- Tabla Suspensiones -->
              <div class="col-lg-4">
                <div class="numi-card h-100">
                  <div class="numi-panel-header" style="background-color: var(--numi-danger);">Reportes de Suspensión</div>
                  <div class="card-body p-0">
                    <table class="table table-striped m-0">
                        <thead class="bg-light">
                            <tr><th>Motivo</th><th class="text-center">Casos</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach($reportes_suspensiones as $rep): ?>
                            <tr>
                                <td><?php echo $rep['motivo']; ?></td>
                                <td class="text-center fw-bold text-danger"><?php echo $rep['cantidad']; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                  </div>
                </div>
              </div>

            </div>

            <!-- SECCIÓN USUARIOS -->
            <div id="seccion-usuarios" class="row">
              <div class="col-12">
                <div class="numi-card mb-5">
                  <div class="numi-panel-header" style="background-color: var(--numi-purple-dark);">Gestión de Usuarios</div>
                  <div class="card-body p-0 table-responsive">
                    <table class="table table-hover text-nowrap align-middle mb-0">
                      <thead class="numi-table-header">
                        <tr>
                          <th class="th-dark text-center" style="width: 80px;">ID</th>
                          <th class="th-teal">Nombre de Usuario</th>
                          <th class="th-teal">Correo Electrónico</th>
                          <th class="th-teal">Rol de Acceso</th>
                          <th class="th-teal text-center">Acciones</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php while ($fila = $consulta->fetch_assoc()): ?>
                        <tr>
                          <td class="text-center fw-bold text-muted"><?php echo $fila['id_usuario']; ?></td>
                          <td class="fw-bold" style="color: var(--numi-dark);"><?php echo htmlspecialchars($fila['nombre'], ENT_QUOTES, 'UTF-8'); ?></td>
                          <td><?php echo htmlspecialchars($fila['correo'], ENT_QUOTES, 'UTF-8'); ?></td>
                          <td><span class="badge bg-secondary"><?php echo htmlspecialchars($fila['roles'] ?: 'Sin rol', ENT_QUOTES, 'UTF-8'); ?></span></td>
                          <td class="text-center">
                              <a href="paneladmin.php?eliminar=<?php echo $fila['id_usuario']; ?>" class="btn btn-sm btn-danger shadow-sm" onclick="return confirm('¿Eliminar definitivamente a <?php echo $fila['nombre']; ?>?');">
                                 <i class="bi bi-trash3-fill"></i> Eliminar
                              </a>
                          </td>
                        </tr>
                        <?php endwhile; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>

            <div id="seccion-bajas" class="row">
              <div class="col-12">
                <div class="numi-card mb-5">
                  <div class="numi-panel-header" style="background-color: var(--numi-danger);">Baja definitiva de alumnos</div>
                  <?php if ($mensaje_baja_alumno): ?>
                    <div class="alert alert-info m-3 mb-0"><?php echo htmlspecialchars($mensaje_baja_alumno, ENT_QUOTES, 'UTF-8'); ?></div>
                  <?php endif; ?>
                  <div class="card-body">
                    <div class="input-group mb-3" style="max-width: 520px;">
                      <span class="input-group-text"><i class="bi bi-search" aria-hidden="true"></i></span>
                      <input type="search" id="buscarAlumnoBaja" class="form-control" placeholder="Buscar por nombre, grado o grupo" aria-label="Buscar alumnos por nombre, grado o grupo">
                    </div>
                    <div id="sinResultadosAlumnos" class="alert alert-warning py-2" hidden>No se encontraron alumnos con esa búsqueda.</div>

                    <?php foreach (['Primaria' => $alumnos_primaria, 'Secundaria' => $alumnos_secundaria] as $nivel_lista => $lista_alumnos): ?>
                      <section data-grupo-alumnos>
                        <h5 class="mt-4 mb-2 fw-bold"><?php echo $nivel_lista; ?></h5>
                        <div class="table-responsive mb-3">
                          <table class="table table-hover text-nowrap align-middle mb-0">
                            <thead class="numi-table-header">
                              <tr>
                                <th class="th-teal">Alumno</th>
                                <th class="th-teal">Grado</th>
                                <th class="th-teal">Grupo</th>
                                <th class="th-teal text-center">Índice de progreso</th>
                                <th class="th-teal text-center">Acción</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php if ($lista_alumnos): ?>
                                <?php foreach ($lista_alumnos as $alumno_baja): ?>
                                  <?php
                                    $nombre_completo_baja = trim(implode(' ', array_filter([
                                        $alumno_baja['nombre'] ?? '',
                                        $alumno_baja['apellido_p'] ?? '',
                                        $alumno_baja['apellido_m'] ?? ''
                                    ])));
                                  ?>
                                  <tr data-alumno-row>
                                    <td class="fw-bold"><?php echo htmlspecialchars($nombre_completo_baja ?: 'Sin cuenta asociada', ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($alumno_baja['grado'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($alumno_baja['grupo'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td class="text-center">
                                      <?php if ((int)$alumno_baja['lecciones_con_avance'] > 0): ?>
                                        <?php echo number_format((float)$alumno_baja['indice_progreso'], 1); ?>%
                                      <?php else: ?>
                                        <span class="text-muted">Sin avances</span>
                                      <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                      <form method="POST" action="paneladmin.php#seccion-bajas" class="d-inline" onsubmit="return confirm('¿Dar de baja definitivamente a este alumno? Se eliminarán su cuenta, progresos y asignaciones.');">
                                        <input type="hidden" name="csrf_baja_alumno" value="<?php echo htmlspecialchars($_SESSION['csrf_baja_alumno'], ENT_QUOTES, 'UTF-8'); ?>">
                                        <input type="hidden" name="id_alumno" value="<?php echo (int)$alumno_baja['id_alumno']; ?>">
                                        <button type="submit" name="dar_de_baja" value="1" class="btn btn-sm btn-danger">
                                          <i class="bi bi-person-x-fill"></i> Dar de baja
                                        </button>
                                      </form>
                                    </td>
                                  </tr>
                                <?php endforeach; ?>
                              <?php else: ?>
                                <tr><td colspan="5" class="text-center text-muted py-3">No hay alumnos de <?php echo strtolower($nivel_lista); ?> registrados.</td></tr>
                              <?php endif; ?>
                            </tbody>
                          </table>
                        </div>
                      </section>
                    <?php endforeach; ?>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </main>
      
      <footer class="app-footer" style="background-color: var(--numi-purple); color: white; border-top: none;">
        <strong>Copyright &copy; 2026 TODOS LOS DERECHOS RESERVADOS A LA EMPRESA by <span style="font-weight: bold;">NU<span style="color: var(--numi-teal);">MI</span></span></strong>
      </footer>

    </div>

    <!-- SCRIPTS CORE -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc4/dist/js/adminlte.min.js" crossorigin="anonymous"></script>

    <!-- INICIALIZACIÓN DE GRÁFICAS CHART.JS -->
    <script>
      const buscarAlumnoBaja = document.getElementById('buscarAlumnoBaja');
      const normalizarBusquedaAlumno = (texto) => texto
        .toLocaleLowerCase('es')
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '');

      buscarAlumnoBaja.addEventListener('input', () => {
        const texto = normalizarBusquedaAlumno(buscarAlumnoBaja.value.trim());
        let coincidencias = 0;

        document.querySelectorAll('[data-grupo-alumnos]').forEach((grupo) => {
          const filas = grupo.querySelectorAll('[data-alumno-row]');
          let coincidenciasGrupo = 0;

          filas.forEach((fila) => {
            const coincide = normalizarBusquedaAlumno(fila.textContent).includes(texto);
            fila.hidden = !coincide;
            if (coincide) coincidenciasGrupo++;
          });

          coincidencias += coincidenciasGrupo;
          grupo.hidden = texto !== '' && coincidenciasGrupo === 0;
        });

        document.getElementById('sinResultadosAlumnos').hidden = texto === '' || coincidencias > 0;
      });

        // Datos Primaria pasados desde PHP
        const temasPrimaria = <?php echo json_encode(array_column($temas_primaria, 'nombre')); ?>;
        const promPrimaria = <?php echo json_encode(array_column($temas_primaria, 'promedio')); ?>;
        
        new Chart(document.getElementById('chartPrimaria'), {
            type: 'bar',
            data: {
                labels: temasPrimaria,
                datasets: [{
                  label: 'Promedio de Calificación (%)<?php echo $rendimiento_primaria_es_demo ? ' - Datos de prueba' : ''; ?>',
                    data: promPrimaria,
                    backgroundColor: '#aa90d6',
                    borderRadius: 5
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, max: 100 } } }
        });

        // Datos Secundaria pasados desde PHP
        const temasSecundaria = <?php echo json_encode(array_column($temas_secundaria, 'nombre')); ?>;
        const promSecundaria = <?php echo json_encode(array_column($temas_secundaria, 'promedio')); ?>;
        
        new Chart(document.getElementById('chartSecundaria'), {
            type: 'bar',
            data: {
                labels: temasSecundaria,
                datasets: [{
                  label: 'Promedio de Calificación (%)<?php echo $rendimiento_secundaria_es_demo ? ' - Datos de prueba' : ''; ?>',
                    data: promSecundaria,
                    backgroundColor: '#4bc4c4',
                    borderRadius: 5
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, max: 100 } } }
        });

          new Chart(document.getElementById('chartDificultad'), {
            type: 'bar',
            data: {
              labels: <?php echo json_encode($dificultad_temas_labels); ?>,
              datasets: [{
                label: 'Dificultad estimada (%)',
                data: <?php echo json_encode($dificultad_temas_datos); ?>,
                backgroundColor: '#e74c3c',
                borderRadius: 5
              }]
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              scales: {
                x: { ticks: { font: { size: 9 }, maxRotation: 0 } },
                y: { beginAtZero: true, max: 100 }
              }
            }
          });

          new Chart(document.getElementById('chartMatriculaPrimaria'), {
            type: 'bar',
            data: {
              labels: <?php echo json_encode($matricula_primaria_labels); ?>,
              datasets: [{
                label: 'Alumnos (datos de prueba)',
                data: <?php echo json_encode($matricula_primaria_datos); ?>,
                backgroundColor: '#aa90d6',
                borderRadius: 5
              }]
            },
            options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } }
          });

          new Chart(document.getElementById('chartMatriculaSecundaria'), {
            type: 'bar',
            data: {
              labels: <?php echo json_encode($matricula_secundaria_labels); ?>,
              datasets: [{
                label: 'Alumnos (datos de prueba)',
                data: <?php echo json_encode($matricula_secundaria_datos); ?>,
                backgroundColor: '#4bc4c4',
                borderRadius: 5
              }]
            },
            options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } }
          });

        // Datos Inasistencias (24H)
        const horas24 = <?php echo json_encode($asistencias_24h_labels); ?>;
        const faltas = <?php echo json_encode($inasistencias_datos); ?>;

        new Chart(document.getElementById('chartInasistencias'), {
            type: 'line',
            data: {
                labels: horas24,
                datasets: [{
                    label: 'Inasistencias Registradas (Hoy)',
                    data: faltas,
                    borderColor: '#161224',
                    backgroundColor: 'rgba(22, 18, 36, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, suggestedMax: 10 } } }
        });
    </script>
  </body>
</html>