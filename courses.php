<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Courses</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="Unicat project">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="styles/bootstrap4/bootstrap.min.css">
<link href="plugins/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="plugins/colorbox/colorbox.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="plugins/OwlCarousel2-2.2.1/owl.carousel.css">
<link rel="stylesheet" type="text/css" href="plugins/OwlCarousel2-2.2.1/owl.theme.default.css">
<link rel="stylesheet" type="text/css" href="plugins/OwlCarousel2-2.2.1/animate.css">
<link rel="stylesheet" type="text/css" href="styles/courses.css">
<link rel="stylesheet" type="text/css" href="styles/courses_responsive.css">
</head>
<body>

<div class="super_container">

    <!-- Header -->
    <header class="header">
            
        <!-- Header Content -->
        <div class="header_container" style="background-color: #A188DD; box-shadow: 0px 3px 10px rgba(0,0,0,0.1);">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="header_content d-flex flex-row align-items-center justify-content-start">
                            <div class="logo_container">
                                <a href="#">
                                    <img src="imagenes/logo (1).png" alt="Unicat Logo" style="height:60px;">
                                </a>
                            </div>
                            
                            <nav class="main_nav_contaner ml-auto">
                                <ul class="main_nav" style="display: flex; align-items: center; margin-bottom: 0;">
                                    <li><a href="index.php" style="color: white !important;">Inicio</a></li>
                                    <li><a href="about.php" style="color: white !important;">Sobre Nosotros</a></li>
                                    <li class="active"><a href="courses.php" style="color: white !important;">Cursos</a></li>
                                    <li><a href="contact.php" style="color: white !important;">Herramientas</a></li>
                                    <li><a href="samida.php" style="color: white !important;">SAMIDA</a></li>

                                    <li style="margin-left: 25px; list-style: none;">
                                        <?php if(isset($_SESSION['usuario_nombre'])): ?>
                                            
                                            <div style="display: flex; align-items: center;">
                                                <div style="width: 48px; height: 48px; background-color: <?php echo (isset($_SESSION['rol']) && ($_SESSION['rol'] === 'admin' || $_SESSION['rol'] === 'maestro')) ? '#EEB4C5' : '#51CCCC'; ?>; border-radius: 50%; overflow: hidden; border: 2px solid white; margin-right: 12px; display: flex; align-items: center; justify-content: center;">
                                                    <img src="imagenes/contento.png" alt="Perfil" style="width: 100%; height: 100%; object-fit: cover;">
                                                </div>
                                                
                                                <div style="display: flex; flex-direction: column; justify-content: center; line-height: 1.3;">
                                                    <div style="display: flex; align-items: center; gap: 8px;">
                                                        <span style="font-weight: bold; color: white; font-size: 17px; letter-spacing: 0.3px;">
                                                            <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?>
                                                        </span>
                                                        
                                                        <!-- ETIQUETAS DE ROL -->
                                                        <?php if(isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
                                                            <span style="font-size: 11px; font-weight: 800; color: #141022; background: #EEB4C5; padding: 3px 8px; border-radius: 6px; text-transform: uppercase; letter-spacing: 0.5px;">Admin</span>
                                                        
                                                        <?php elseif(isset($_SESSION['rol']) && $_SESSION['rol'] === 'maestro'): ?>
                                                            <span style="font-size: 11px; font-weight: 800; color: white; background: #A188DD; padding: 3px 8px; border-radius: 6px; text-transform: uppercase; letter-spacing: 0.5px;">Maestro</span>
                                                        
                                                        <?php elseif(isset($_SESSION['rol']) && $_SESSION['rol'] === 'tutor'): ?>
                                                            <span style="font-size: 11px; font-weight: 800; color: white; background: #51CCCC; padding: 3px 8px; border-radius: 6px; text-transform: uppercase; letter-spacing: 0.5px;">Tutor</span>

                                                        <?php elseif(isset($_SESSION['rol']) && $_SESSION['rol'] === 'alumno'): ?>
                                                            <div style="display: flex; gap: 5px; align-items: center;">
                                                                <span style="font-size: 12px; font-weight: 700; color: white; background: rgba(255, 255, 255, 0.25); padding: 3px 9px; border-radius: 6px; letter-spacing: 0.3px;">Estudiante</span>
                                                                <?php if(isset($_SESSION['id_alumno'])): ?>
                                                                    <span style="font-size: 11px; font-weight: 800; color: #141022; background: #51CCCC; padding: 3px 8px; border-radius: 6px; letter-spacing: 0.5px;" title="Dale este código a tu tutor">ID Tutor: #<?php echo htmlspecialchars($_SESSION['id_alumno']); ?></span>
                                                                <?php endif; ?>
                                                            </div>
                                                        
                                                        <?php else: ?>
                                                            <span style="font-size: 12px; font-weight: 700; color: white; background: rgba(255, 255, 255, 0.25); padding: 3px 9px; border-radius: 6px; letter-spacing: 0.3px;">Usuario</span>
                                                        <?php endif; ?>
                                                    </div>
                                                    
                                                    <div style="display: flex; align-items: center; gap: 10px; margin-top: 3px;">
                                                        <?php if(isset($_SESSION['rol']) && ($_SESSION['rol'] === 'admin' || $_SESSION['rol'] === 'maestro')): ?>
                                                            <a href="paneladmin.php" style="font-size: 11px; font-weight: bold; color: #A188DD; background: white; padding: 2px 6px; border-radius: 4px; text-decoration: none;">PANEL</a>
                                                        <?php elseif(isset($_SESSION['rol']) && $_SESSION['rol'] === 'tutor'): ?>
                                                            <a href="progreso_hijo.php" style="font-size: 11px; font-weight: bold; color: #51CCCC; background: white; padding: 2px 6px; border-radius: 4px; text-decoration: none;">PROGRESO</a>
                                                        <?php endif; ?>
                                                        <a href="logout.php" style="font-size: 12px; text-decoration: underline; color: #f0f0f0; opacity: 0.9;">Salir</a>
                                                    </div>
                                                </div>
                                            </div>

                                        <?php else: ?>

                                            <a href="nuevo_registro.php" style="display: flex; align-items: center; text-decoration: none;">
                                                <div style="width: 45px; height: 45px; background-color: #EEB4C5; border-radius: 50%; display: flex; align-items: center; justify-content: center; overflow: hidden; margin-right: 10px; border: 2px solid white;">
                                                    <img src="imagenes/icono_registro.png" alt="Icono Registro" style="width: 60%; height: 60%; object-fit: contain;">
                                                </div>
                                                <span style="font-weight: bold; color: black; background-color: #EEB4C5; padding: 8px 18px; border-radius: 20px; font-size: 14px;">
                                                    Registrarse Ahora!
                                                </span>
                                            </a>

                                        <?php endif; ?>
                                    </li>
                                </ul>

                            </nav>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Header Search Panel -->
        <div class="header_search_container">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="header_search_content d-flex flex-row align-items-center justify-content-end">
                            <form action="#" class="header_search_form">
                                <input type="search" class="search_input" placeholder="Search" required="required">
                                <button class="header_search_button d-flex flex-column align-items-center justify-content-center">
                                    <i class="fa fa-search" aria-hidden="true"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>          
        </div>          
    </header>

	<!-- Menu -->
	<div class="menu d-flex flex-column align-items-end justify-content-start text-right menu_mm trans_400">
		<div class="menu_close_container"><div class="menu_close"><div></div><div></div></div></div>
		<div class="search">
			<form action="#" class="header_search_form menu_mm">
				<input type="search" class="search_input menu_mm" placeholder="Search" required="required">
				<button class="header_search_button d-flex flex-column align-items-center justify-content-center menu_mm">
					<i class="fa fa-search menu_mm" aria-hidden="true"></i>
				</button>
			</form>
		</div>
		<nav class="menu_nav">
			<ul class="menu_mm">
				<li class="menu_mm"><a href="index.html">Home</a></li>
				<li class="menu_mm"><a href="#">About</a></li>
				<li class="menu_mm"><a href="#">Courses</a></li>
				<li class="menu_mm"><a href="#">Blog</a></li>
				<li class="menu_mm"><a href="#">Page</a></li>
				<li class="menu_mm"><a href="contact.html">Contact</a></li>
			</ul>
		</nav>
	</div>
	
	<!-- Home -->
	<div class="home">
		<div class="breadcrumbs_container">
			<div class="container">
				<div class="row">
					<div class="col">
						<div class="breadcrumbs">
						</div>
					</div>
				</div>
			</div>
		</div>			
	</div>

	<style>
        /* 1. RESET Y ESTILOS GLOBALES */
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Roboto', sans-serif; background-color: #F9FAFB; color: #1f2937; }
        .main_nav li a { font-family: 'Roboto', sans-serif !important; font-weight: 500; text-transform: none; }

        /* 3. ESTILOS DE LA TARJETA */
        .card { background-color: white; border-radius: 1.5rem; overflow: hidden; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1); display: flex; flex-direction: column; position: relative; }
        .card-img-wrapper { height: 16rem; position: relative; }
        .card-img { width: 100%; height: 100%; object-fit: cover; }
        .bg-pink { background-color: #fce7f3; }
        .bg-orange { background-color: #ffedd5; }
        .bg-purple { background-color: #f3e8ff; }
        .card-body { padding: 2rem; display: flex; flex-direction: column; flex-grow: 1; position: relative; z-index: 10; }
        .badge { display: inline-block; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; margin-bottom: 1rem; align-self: flex-start; }
        .badge-blue { background-color: #dbeafe; color: #A188DD; }
        .badge-green { background-color: #dcfce7; color: #16a34a; }
        .badge-purple { background-color: #f3e8ff; color: #A188DD; }
        .card-title { font-size: 1.875rem; font-weight: 700; color: #1f2937; margin-bottom: 2rem; }
        .lesson-list { display: flex; flex-direction: column; gap: 1rem; }
        .lesson-item { display: flex; align-items: center; gap: 1rem; padding: 1rem; border-radius: 1rem; background-color: #f9fafb; cursor: pointer; transition: background-color 0.2s ease; }
        .lesson-item:hover { background-color: #f3f4f6; }
        .lesson-text { font-weight: 500; color: #A188DD; }

        /* --- ESTILOS DE LA SECCIÓN DE DOCUMENTO (FUNCIONAL SIN INTERNET) --- */
        .mi-doc-section { font-family: Arial, Helvetica, sans-serif; padding: 4rem 1rem; }
        .mi-doc-img-wrapper { position: relative; padding: 1rem; z-index: 1; }
        .mi-doc-img-fondo { position: absolute; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0, 70, 145, 0.05); border-radius: 1rem; transform: rotate(2deg); z-index: -1; }
        .mi-doc-img-box { border-radius: 1rem; overflow: hidden; box-shadow: 0 20px 40px rgba(0, 70, 145, 0.15); }
        .mi-doc-img-box img { width: 100%; height: 500px; object-fit: cover; display: block; }
        .mi-doc-badge { position: absolute; top: 2.5rem; left: 2.5rem; background-color: #d7e2ff; color: #004691; padding: 0.4rem 1rem; border-radius: 50px; font-size: 0.75rem; font-weight: bold; text-transform: uppercase; letter-spacing: 0.1em; }
        .mi-doc-kicker { font-size: 0.85rem; font-weight: 700; color: #004691; text-transform: uppercase; letter-spacing: 0.2em; margin-bottom: 0.5rem; }
        .mi-doc-title { font-size: 3.5rem; font-weight: 800; color: #191c1e; line-height: 1.1; margin-bottom: 1.5rem; }
        .mi-doc-desc { font-size: 1.1rem; color: #424752; line-height: 1.6; margin-bottom: 2rem; }
        .mi-doc-info { display: flex; align-items: center; background-color: #f2f4f6; padding: 1.5rem; border-radius: 0.75rem; border-left: 4px solid rgba(0, 70, 145, 0.3); margin-bottom: 2rem; }
        .mi-doc-info-icon { font-size: 1.5rem; margin-right: 1rem; }
        .mi-doc-info-text strong { color: #191c1e; font-size: 0.9rem; display: block; }
        .mi-doc-info-text span { color: #424752; font-size: 0.85rem; }
        .mi-doc-botones { display: flex; flex-wrap: wrap; gap: 1rem; align-items: center; }
        .mi-doc-btn-primary { background: linear-gradient(135deg, #EEB4C5, #EEB4C5); color: white; padding: 1rem 2rem; border-radius: 0.75rem; font-weight: bold; border: none; box-shadow: 0 10px 20px rgba(0, 70, 145, 0.3); cursor: pointer; transition: transform 0.2s, box-shadow 0.2s; text-decoration: none; display: inline-flex; align-items: center; }
        .mi-doc-btn-primary:hover { transform: translateY(-2px); box-shadow: 0 15px 30px rgba(0, 70, 145, 0.4); color: white; }
        @media (max-width: 991px) { .mi-doc-title { font-size: 2.5rem; } .mi-doc-img-box img { height: 350px; } .mi-doc-badge { top: 1.5rem; left: 1.5rem; } }
    </style>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "numi";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// -------------------------------------------------------------
// RECOLECTOR DE SESIÓN ULTRA-COMPATIBLE
// -------------------------------------------------------------
$id_alumno_sesion = 0;

if (isset($_SESSION['id_alumno']))   $id_alumno_sesion = intval($_SESSION['id_alumno']);
if (isset($_SESSION['alumno_id']))   $id_alumno_sesion = intval($_SESSION['alumno_id']);
if (isset($_SESSION['id_alumnos']))  $id_alumno_sesion = intval($_SESSION['id_alumnos']);

if ($id_alumno_sesion == 0) {
    $id_usuario_generico = 0;
    if (isset($_SESSION['id_usuario'])) $id_usuario_generico = intval($_SESSION['id_usuario']);
    if (isset($_SESSION['usuario_id'])) $id_usuario_generico = intval($_SESSION['usuario_id']);
    if (isset($_SESSION['id']))         $id_usuario_generico = intval($_SESSION['id']);
    
    if ($id_usuario_generico > 0) {
        $stmt_u = $conn->prepare("SELECT id_alumno FROM alumnos WHERE id_usuario = ? LIMIT 1");
        if ($stmt_u) {
            $stmt_u->bind_param("i", $id_usuario_generico);
            $stmt_u->execute();
            $res_u = $stmt_u->get_result();
            if ($row_u = $res_u->fetch_assoc()) {
                $id_alumno_sesion = intval($row_u['id_alumno']);
            }
            $stmt_u->close();
        }
    }
}

if ($id_alumno_sesion == 0) {
    $nombre_busqueda = "";
    if (isset($_SESSION['usuario_nombre'])) $nombre_busqueda = $_SESSION['usuario_nombre'];
    if (isset($_SESSION['nombre_usuario'])) $nombre_busqueda = $_SESSION['nombre_usuario'];
    if (isset($_SESSION['usuario']))        $nombre_busqueda = $_SESSION['usuario'];
    
    if (!empty($nombre_busqueda)) {
        $stmt_n = $conn->prepare("SELECT id_alumno FROM alumnos WHERE id_usuario IN (SELECT id_usuario FROM usuarios WHERE nombre = ?) LIMIT 1");
        if ($stmt_n) {
            $stmt_n->bind_param("s", $nombre_busqueda); 
            $stmt_n->execute();
            $res_n = $stmt_n->get_result();
            if ($row_n = $res_n->fetch_assoc()) {
                $id_alumno_sesion = intval($row_n['id_alumno']);
            }
            $stmt_n->close();
        }
    }
}

if ($id_alumno_sesion > 0) {
    $_SESSION['id_alumno'] = $id_alumno_sesion;
}

// ==========================================
// IDENTIFICAR NIVEL DEL ALUMNO
// ==========================================
$nivel_educativo = 'primaria'; // Valor por defecto

if (isset($_SESSION['nivel'])) {
    $nivel_educativo = strtolower($_SESSION['nivel']);
} elseif (isset($_SESSION['nivel_educativo'])) {
    $nivel_educativo = strtolower($_SESSION['nivel_educativo']);
} elseif ($id_alumno_sesion > 0) {
    $stmt_niv = $conn->prepare("SELECT nivel_educativo FROM alumnos WHERE id_alumno = ? LIMIT 1");
    if ($stmt_niv) {
        $stmt_niv->bind_param("i", $id_alumno_sesion);
        $stmt_niv->execute();
        $res_niv = $stmt_niv->get_result();
        if ($row_niv = $res_niv->fetch_assoc()) {
            if(!empty($row_niv['nivel_educativo'])) {
                $nivel_educativo = strtolower($row_niv['nivel_educativo']);
            }
        }
        $stmt_niv->close();
    }
}

// -------------------------------------------------------------
// CONSULTA DE AVANCES
// -------------------------------------------------------------
function obtenerProgresoLeccion($id_leccion, $id_alumno, $conn) {
    if ($id_alumno <= 0) return ['porcentaje' => 0];

    $sql = "SELECT MAX(num_intentos) as max_progreso FROM progresos WHERE id_leccion = ? AND id_alumno = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) return ['porcentaje' => 0];
    
    $stmt->bind_param("ii", $id_leccion, $id_alumno);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    
    $porcentaje_real = isset($res['max_progreso']) ? intval($res['max_progreso']) : 0;
    return ['porcentaje' => $porcentaje_real];
}

// ==========================================
// MAPEO DE NIVELES (VARIABLES ORIGINALES)
// ==========================================

// --- PRIMARIA ---
$fig_n1 = obtenerProgresoLeccion(1, $id_alumno_sesion, $conn);
$fig_n2 = obtenerProgresoLeccion(4, $id_alumno_sesion, $conn);
$fig_n3 = obtenerProgresoLeccion(5, $id_alumno_sesion, $conn);

$met_n1 = obtenerProgresoLeccion(2, $id_alumno_sesion, $conn);
$met_n2 = obtenerProgresoLeccion(6, $id_alumno_sesion, $conn);
$met_n3 = obtenerProgresoLeccion(7, $id_alumno_sesion, $conn);

$num_n1 = obtenerProgresoLeccion(3, $id_alumno_sesion, $conn);
$num_n2 = obtenerProgresoLeccion(8, $id_alumno_sesion, $conn);
$num_n3 = obtenerProgresoLeccion(9, $id_alumno_sesion, $conn);

$promedio_fig = round(($fig_n1['porcentaje'] + $fig_n2['porcentaje'] + $fig_n3['porcentaje']) / 3);
$promedio_met = round(($met_n1['porcentaje'] + $met_n2['porcentaje'] + $met_n3['porcentaje']) / 3);
$promedio_num = round(($num_n1['porcentaje'] + $num_n2['porcentaje'] + $num_n3['porcentaje']) / 3);

// --- SECUNDARIA ---
$ang_n1 = obtenerProgresoLeccion(10, $id_alumno_sesion, $conn);
$ang_n2 = obtenerProgresoLeccion(11, $id_alumno_sesion, $conn);
$ang_n3 = obtenerProgresoLeccion(12, $id_alumno_sesion, $conn);

$ecu_n1 = obtenerProgresoLeccion(13, $id_alumno_sesion, $conn);
$ecu_n2 = obtenerProgresoLeccion(14, $id_alumno_sesion, $conn);
$ecu_n3 = obtenerProgresoLeccion(15, $id_alumno_sesion, $conn);

$jer_n1 = obtenerProgresoLeccion(16, $id_alumno_sesion, $conn);
$jer_n2 = obtenerProgresoLeccion(17, $id_alumno_sesion, $conn);
$jer_n3 = obtenerProgresoLeccion(18, $id_alumno_sesion, $conn);

$promedio_ang = round(($ang_n1['porcentaje'] + $ang_n2['porcentaje'] + $ang_n3['porcentaje']) / 3);
$promedio_ecu = round(($ecu_n1['porcentaje'] + $ecu_n2['porcentaje'] + $ecu_n3['porcentaje']) / 3);
$promedio_jer = round(($jer_n1['porcentaje'] + $jer_n2['porcentaje'] + $jer_n3['porcentaje']) / 3);

// Helper function para renderizar filas
function renderFilaNivel($nombre, $porcentaje) {
    echo "
    <tr style='border-bottom: 1px solid #edf2f7; background: #ffffff;'>
        <td style='padding: 12px 15px 12px 45px; color: #64748b; font-weight: 500;'>↳ $nombre</td>
        <td style='display: none; padding: 12px; text-align: center;'>$porcentaje%</td>
        <td style='padding: 12px; text-align: center;'>1</td>
        <td style='padding: 12px; text-align: center;'>$porcentaje%</td>
        <td style='padding: 12px; text-align: center;'>
            <div style='width: 40px; height: 40px; border-radius: 50%; background: conic-gradient(#51CCCC $porcentaje%, #e2e8f0 0); display: flex; align-items: center; justify-content: center; margin: 0 auto;'>
                <div style='width: 30px; height: 30px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #141022; font-size: 10px;'>$porcentaje%</div>
            </div>
        </td>
    </tr>";
}
?>

<div class="container mt-5 mb-5" style="font-family: 'Poppins', -apple-system, sans-serif;">
    <div class="text-center mb-4">
        <h2 style="font-weight: 800; color: #141022; font-size: 32px; margin-bottom: 5px;">Cursos Disponibles En NUMI</h2>
        <p style="color: #64748b; font-size: 16px; font-weight: 500;">Mapeo de Analíticas Generales y Desempeño Escolar</p>
    </div>

    <div style="background: #ffffff; border-radius: 16px; box-shadow: 0 15px 35px rgba(20, 16, 34, 0.06); overflow: hidden; border: 1px solid #e2e8f0;">
        <div style="background: linear-gradient(90deg, #A188DD, #51CCCC); padding: 15px; text-align: center;">
            <h3 style="color: white; margin: 0; font-size: 20px; font-weight: 700; letter-spacing: 0.5px;">
                Panel de Desempeño <?php echo ($id_alumno_sesion > 0) ? '(' . ucfirst($nivel_educativo) . ')' : ''; ?>
            </h3>
        </div>

        <div style="padding: 25px;">
            <div class="table-responsive" style="border-radius: 8px; overflow: hidden; border: 1px solid #e2e8f0;">
                <table class="table" style="width: 100%; margin-bottom: 0; border-collapse: collapse; text-align: left; vertical-align: middle;">
                    <thead>
                        <tr style="color: white; font-size: 14px; text-align: center;">
                            <th style="background: #141022; padding: 12px; font-weight: 700; text-align: left; width: 40%;">Asignatura / Tema</th>
                            <th style="background: #51CCCC; padding: 12px; font-weight: 700; width: 20%;">Lecciones</th>
                            <th style="background: #141022; padding: 12px; font-weight: 700; width: 20%;">Completado</th>
                            <th style="background: #51CCCC; padding: 12px; font-weight: 700; width: 20%;">Porcentaje promedio</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 14px; color: #334155;">
                        
                        <?php if ($id_alumno_sesion > 0): ?>
                            <!-- MUESTRA MATERIAS SOLO SI EL ALUMNO INICIÓ SESIÓN -->
                            <tr style="background: #f1f5f9; border-bottom: 2px solid #cbd5e1;">
                                <td colspan="4" style="padding: 12px 15px; font-weight: 800; color: #141022; font-size: 16px;">
                                    <span style="background: #51CCCC; color: white; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 20px; text-transform: uppercase; margin-right: 10px;">Curso</span> Bloque Temático Activo (Unity)
                                </td>
                            </tr>
                            
                            <?php if ($nivel_educativo === 'secundaria'): ?>
                                <!-- SECUNDARIA -->
                                <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                                    <td style="padding: 15px; color: #1e293b; font-weight: 700; font-size: 15px;">🔹 Ángulos</td>
                                    <td colspan="2"></td>
                                    <td style="padding: 15px; text-align: center; font-weight: bold; color: #A188DD;">Promedio: <?php echo $promedio_ang; ?>%</td>
                                </tr>
                                <?php renderFilaNivel("Nivel 1 (Básico)", $ang_n1['porcentaje']); renderFilaNivel("Nivel 2 (Intermedio)", $ang_n2['porcentaje']); renderFilaNivel("Nivel 3 (Avanzado)", $ang_n3['porcentaje']); ?>

                                <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                                    <td style="padding: 15px; color: #1e293b; font-weight: 700; font-size: 15px;">🔹 Ecuaciones Lineales</td>
                                    <td colspan="2"></td>
                                    <td style="padding: 15px; text-align: center; font-weight: bold; color: #A188DD;">Promedio: <?php echo $promedio_ecu; ?>%</td>
                                </tr>
                                <?php renderFilaNivel("Nivel 1 (Básico)", $ecu_n1['porcentaje']); renderFilaNivel("Nivel 2 (Intermedio)", $ecu_n2['porcentaje']); renderFilaNivel("Nivel 3 (Avanzado)", $ecu_n3['porcentaje']); ?>

                                <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                                    <td style="padding: 15px; color: #1e293b; font-weight: 700; font-size: 15px;">🔹 Jerarquía de Operaciones</td>
                                    <td colspan="2"></td>
                                    <td style="padding: 15px; text-align: center; font-weight: bold; color: #A188DD;">Promedio: <?php echo $promedio_jer; ?>%</td>
                                </tr>
                                <?php renderFilaNivel("Nivel 1 (Básico)", $jer_n1['porcentaje']); renderFilaNivel("Nivel 2 (Intermedio)", $jer_n2['porcentaje']); renderFilaNivel("Nivel 3 (Avanzado)", $jer_n3['porcentaje']); ?>

                            <?php else: ?>
                                <!-- PRIMARIA -->
                                <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                                    <td style="padding: 15px; color: #1e293b; font-weight: 700; font-size: 15px;">🔹 Figuras Geométricas</td>
                                    <td colspan="2"></td>
                                    <td style="padding: 15px; text-align: center; font-weight: bold; color: #A188DD;">Promedio: <?php echo $promedio_fig; ?>%</td>
                                </tr>
                                <?php renderFilaNivel("Nivel 1 (Básico)", $fig_n1['porcentaje']); renderFilaNivel("Nivel 2 (Intermedio)", $fig_n2['porcentaje']); renderFilaNivel("Nivel 3 (Avanzado)", $fig_n3['porcentaje']); ?>

                                <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                                    <td style="padding: 15px; color: #1e293b; font-weight: 700; font-size: 15px;">🔹 El Metro</td>
                                    <td colspan="2"></td>
                                    <td style="padding: 15px; text-align: center; font-weight: bold; color: #A188DD;">Promedio: <?php echo $promedio_met; ?>%</td>
                                </tr>
                                <?php renderFilaNivel("Nivel 1 (Básico)", $met_n1['porcentaje']); renderFilaNivel("Nivel 2 (Intermedio)", $met_n2['porcentaje']); renderFilaNivel("Nivel 3 (Avanzado)", $met_n3['porcentaje']); ?>

                                <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                                    <td style="padding: 15px; color: #1e293b; font-weight: 700; font-size: 15px;">🔹 Números Enteros</td>
                                    <td colspan="2"></td>
                                    <td style="padding: 15px; text-align: center; font-weight: bold; color: #A188DD;">Promedio: <?php echo $promedio_num; ?>%</td>
                                </tr>
                                <?php renderFilaNivel("Nivel 1 (Básico)", $num_n1['porcentaje']); renderFilaNivel("Nivel 2 (Intermedio)", $num_n2['porcentaje']); renderFilaNivel("Nivel 3 (Avanzado)", $num_n3['porcentaje']); ?>
                            <?php endif; ?>
                            
                        <?php else: ?>
                            <!-- MUESTRA MENSAJE SI EL USUARIO NO HA INICIADO SESIÓN -->
                            <tr style="background: #f8fafc;">
                                <td colspan="4" style="padding: 40px 15px; text-align: center; color: #64748b; font-weight: 500; font-size: 15px;">
                                    <i class="fa fa-lock" aria-hidden="true" style="font-size: 24px; color: #cbd5e1; margin-bottom: 10px; display: block;"></i>
                                    Inicia sesión o regístrate como estudiante para visualizar tu progreso y temario activo.
                                </td>
                            </tr>
                        <?php endif; ?>

                    </tbody>
                </table>
            </div>

            <?php if ($id_alumno_sesion > 0): ?>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px;">
                <button style="background: #A188DD; color: white; border: none; padding: 10px 20px; border-radius: 8px; font-size: 14px; font-weight: 700; cursor: pointer; box-shadow: 0 4px 12px rgba(161,136,221,0.2);">
                    Exportar todas las filas (CSV)
                </button>
                <span style="font-size: 14px; color: #64748b; font-weight: 600;">9 elementos desglosados (3 temas x 3 niveles)</span>
            </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<section class="mi-doc-section container">
    <div class="row align-items-center">
        <div class="col-lg-7 mb-5 mb-lg-0">
            <div class="mi-doc-img-wrapper">
                <div class="mi-doc-img-fondo"></div> <div class="mi-doc-img-box">
                    <img src="imagenes/imagenestargets.png" alt="Edificio Arquitectura">
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <h1 class="mi-doc-title">Manual de<br>Targets</h1>
            <p class="mi-doc-desc">Descarga nuestro manual interactivo y descubre cómo los componentes cobran vida ante tus ojos. Solo tienes que pulsar el botón de abajo, abrir el archivo y preparar tu cámara para la experiencia.</p>
            <div class="mi-doc-info">
                <div class="mi-doc-info-icon">ℹ️</div>
                <div class="mi-doc-info-text">
                    <strong>Especificaciones de como usar los Targets</strong>
                </div>
            </div>
            <a href="Documentos/IntroduccionaRealidadAumentadaNumiAR.pdf" download="Manual_Numi.pdf" class="mi-doc-btn-primary">
                <span style="margin-right: 8px;">↓</span> Download PDF
            </a>
        </div>
    </div>
</section>
    
    <footer class="footer">
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
                                        <p>Lorem ipsum dolor sit ametium, consectetur adipiscing elit.</p>
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
                                    <div class="footer_title">Contactanos</div>
                                    <div class="footer_contact_info">
                                        <ul>
                                            <li>Correo: numi09052025@gmail.com</li>
                                            <li>UTCV creditos NUMI</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div> <div class="row copyright_row">
                            <div class="col">
                                <div class="copyright d-flex flex-lg-row flex-column align-items-center justify-content-start">
                                    <div class="cr_text">
                                        Copyright ©<script>document.write(new Date().getFullYear());</script> TODOS LOS DERECHOS RESERVADOS A LA EMPRESA <i class="fa fa-heart-o" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">NUMI</a>
                                    </div>
                                </div>
                            </div>
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
<script src="plugins/OwlCarousel2-2.2.1/owl.carousel.js"></script>
<script src="plugins/easing/easing.js"></script>
<script src="plugins/parallax-js-master/parallax.min.js"></script>
<script src="plugins/colorbox/jquery.colorbox-min.js"></script>
<script src="js/courses.js"></script>
</body>
</html>