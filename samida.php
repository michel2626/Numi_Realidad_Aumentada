<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Samida / AR Manual</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="Unicat project">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="styles/bootstrap4/bootstrap.min.css">
<link href="plugins/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="styles/contact.css">
<link rel="stylesheet" type="text/css" href="styles/contact_responsive.css">

<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet"/>

<style>
/* ==========================================================
   ESTILOS ORIGINALES DE TU PLANTILLA (TARJETAS ANTERIORES)
   ========================================================== */
.roles-section { background-color: #f7f9fb; padding: 60px 0; }
.role-card {
    background-color: #ffffff; padding: 2.5rem 1.5rem; border-radius: 1rem;
    display: flex; flex-direction: column; border-top: 5px solid #EEB4C5;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08); transition: all 0.4s ease;
    height: 100%; text-align: center;
}
.role-card:hover { box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12); transform: translateY(-8px); }
.role-icon { font-size: 3rem; color: #51CCCC; margin-bottom: 1rem; display: block; }
.role-title { font-size: 1.3rem; font-weight: 700; color: #191c1e; margin-bottom: 0.8rem; }
.role-desc { font-size: 0.9rem; color: #43474c; line-height: 1.6; margin-bottom: 1.5rem; flex-grow: 1; }
.role-link {
    display: inline-flex; align-items: center; justify-content: center;
    background-color: #EEB4C5; color: white !important; padding: 0.6rem 1.2rem;
    border-radius: 0.5rem; font-weight: 600; text-decoration: none !important; transition: all 0.2s;
}
.btn-get-started {
    display: flex; align-items: center; justify-content: center; gap: 6px;
    margin: 60px auto 0; width: fit-content; padding: 1rem 2.5rem; font-weight: 600;
    color: white !important; background-color: #51CCCC; border-radius: 0.5rem;
    transition: all 0.2s; text-decoration: none !important; box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
}
.btn-get-started:hover { transform: translateY(-3px); box-shadow: 0 15px 25px rgba(0, 0, 0, 0.2); }

/* ==========================================================
   NUEVOS ESTILOS: SECCIONES DE VIDEO, INTRO Y MISION/VISION
   ========================================================== */
:root {
    --primary: #001e40;
    --primary-container: #003366;
    --surface: #f7f9fb;
    --on-surface: #191c1e;
    --on-surface-variant: #43474f;
    --surface-container-low: #f2f4f6;
    --surface-container-highest: #e0e3e5;
    --surface-container-lowest: #ffffff;
    --secondary-container: #d6e3fe;
    --tertiary-container: #592300;
    --on-tertiary-container: #d8885c;
    --tertiary-fixed: #ffdbca;
    --tertiary: #381300;
    
    --font-headline: 'Manrope', sans-serif;
    --font-body: 'Inter', sans-serif;
    
    --radius-lg: 0.5rem;
    --radius-xl: 0.75rem;
    --shadow-sm: 0px 4px 20px rgba(25,28,30,0.06);
    --shadow-lg: 0px 10px 30px rgba(25,28,30,0.12);
}

/* Contenedor principal para no afectar Bootstrap */
.main-content-custom {
    font-family: var(--font-body);
    background-color: var(--surface);
    color: var(--on-surface);
    line-height: 1.5;
    padding-bottom: 4rem; /* Espacio antes del footer */
}

.main-content-custom h1, 
.main-content-custom h2, 
.main-content-custom h3 {
    font-family: var(--font-headline);
    margin-top: 0;
}

.cc-container {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 2rem;
}

/* --- HERO VIDEO --- */
.hero-section {
    padding-top: 4rem; /* Ajustado para dar espacio debajo del header */
    margin-bottom: 4rem;
}
.video-wrapper {
    position: relative; width: 100%; aspect-ratio: 16 / 9;
    border-radius: var(--radius-xl); overflow: hidden;
    box-shadow: 0px 4px 20px rgba(25,28,30,0.1);
}
.video-overlay-center {
    position: absolute; inset: 0; background-color: rgba(0, 30, 64, 0.2);
    display: flex; align-items: center; justify-content: center; z-index: 10;
}
.play-button {
    width: 5rem; height: 5rem; background-color: rgba(255, 255, 255, 0.9);
    border-radius: 50%; display: flex; align-items: center; justify-content: center;
    color: var(--primary); box-shadow: var(--shadow-lg); transition: transform 0.2s; border: none; cursor: pointer;
}
.play-button:hover { transform: scale(1.1); }
.video-image { width: 100%; height: 100%; object-fit: cover; }
.video-gradient-bottom {
    position: absolute; bottom: 0; left: 0; right: 0; padding: 2rem;
    background: linear-gradient(to top, rgba(0, 30, 64, 0.8), transparent); z-index: 20;
}
.video-title { color: white; font-size: 2.25rem; font-weight: 800; letter-spacing: -0.025em; margin-bottom: 0.5rem; }
.video-subtitle { color: rgba(255, 255, 255, 0.8); max-width: 42rem; margin-bottom: 0; }

/* --- INTRO SECCIÓN (CORREGIDA) --- */
        .intro-section { padding: 80px 0; background-color: #f7f9fb; }
        .intro-grid { display: grid; grid-template-columns: 1fr; gap: 40px; align-items: center; }
        @media (min-width: 992px) { .intro-grid { grid-template-columns: 1.2fr 1fr; } }

        .intro-tag { color: var(--primary); font-weight: 800; text-transform: uppercase; font-size: 14px; letter-spacing: 2px; display: block; margin-bottom: 15px; }
        .intro-title { font-size: 2.8rem; font-weight: 800; line-height: 1.1; margin-bottom: 25px; color: #191c1e; }
        .intro-text { color: #43474f; font-size: 1.1rem; line-height: 1.7; margin-bottom: 30px; }

        /* --- STATS / IMÁGENES --- */
        .stats-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        .stats-col-2 { margin-top: 30px; }
        .img-box { height: 200px; border-radius: 15px; overflow: hidden; margin-bottom: 15px; }
        .img-box img { width: 100%; height: 100%; object-fit: cover; }
        
        .stat-box { height: 130px; border-radius: 15px; padding: 20px; display: flex; flex-direction: column; justify-content: flex-end; margin-bottom: 15px; }
        .stat-box.blue { background-color: #EEB4C5; }
        .stat-box.brown { background-color: #A188DD; }
        .stat-number { font-size: 2rem; font-weight: 800; color: white; display: block; line-height: 1; }
        .stat-label { font-size: 12px; font-weight: 700; text-transform: uppercase; color: rgba(255,255,255,0.7); letter-spacing: 1px; }
        .stat-box.brown .stat-number { color: #ffffff; }

/* --- CARDS (Bento) --- */
.cards-section { margin-bottom: 2rem; }
.cards-grid { display: grid; grid-template-columns: 1fr; gap: 2rem; }
@media (min-width: 768px) { .cards-grid { grid-template-columns: repeat(3, 1fr); } }
.cc-card { padding: 2.5rem; border-radius: var(--radius-xl); background-color: var(--surface-container-lowest); box-shadow: var(--shadow-sm); transition: all 0.3s; }
.cc-card:hover { box-shadow: var(--shadow-lg); }
.cc-card.highlight { background-color: var(--primary); color: white; }
@media (min-width: 768px) { .cc-card.highlight { transform: translateY(-1rem); } }
.card-icon { width: 3.5rem; height: 3.5rem; border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; margin-bottom: 2rem; }
.card-icon.blue { background-color: var(--secondary-container); color: var(--primary); }
.card-icon.white { background-color: rgba(255,255,255,0.1); color: white; }
.card-icon.orange { background-color: var(--tertiary-fixed); color: var(--tertiary); }
.card-icon .material-symbols-outlined { font-size: 1.875rem; }
.cc-card h3 { font-size: 1.5rem; font-weight: 700; margin-bottom: 1rem; }
.cc-card.highlight h3 { color: white; }
.cc-card:not(.highlight) h3 { color: var(--primary); }
.cc-card p { line-height: 1.625; color: var(--on-surface-variant); margin-bottom: 0;}
.cc-card.highlight p { color: rgba(255, 255, 255, 0.8); }
.cc-card-footer { margin-top: 2rem; padding-top: 2rem; border-top: 1px solid rgba(195, 198, 209, 0.15); }
.cc-card.highlight .cc-card-footer { border-top: 1px solid rgba(255,255,255,0.1); }
.cc-card-link { display: flex; align-items: center; font-size: 0.875rem; font-weight: 700; color: var(--primary); text-decoration: none;}
.cc-card.highlight .cc-card-link { color: white; }
.cc-card-link .material-symbols-outlined { margin-left: 0.5rem; transition: transform 0.2s; font-size: 1.2rem;}
.cc-card-link:hover { text-decoration: none; color: inherit; }
.cc-card-link:hover .material-symbols-outlined { transform: translateX(0.25rem); }
</style>
</head>
<body>

<div class="super_container">

 <header class="header">
        
        <style>
            .main_nav li a::after {
                display: none !important;
            }
            /* ESTO SUBE Y CENTRA LAS LETRAS DEL MENU AL NIVEL DEL LOGO */
            .main_nav {
                display: flex !important;
                align-items: center !important;
                margin-bottom: 0 !important;
            }
            .main_nav_contaner {
                display: flex !important;
                align-items: center !important;
            }
        </style>

        <div class="header_container" style="background-color: #A188DD; box-shadow: 0px 3px 10px rgba(0,0,0,0.1);">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="header_content d-flex flex-row align-items-center justify-content-start">
                            
                          <div class="logo_container" style="display: flex; align-items: center;">
<a href="#">
        <!-- Dejamos solo la imagen en un tamaño grande de 115px de alto -->
        <img src="imagenes/logo_SAMIDA.png" alt="Samida Logo" style="height: 115px; width: auto; object-fit: contain;">
    </a>
</div>
                            
                            <nav class="main_nav_contaner ml-auto">
                                <ul class="main_nav" style="margin-bottom: 0; display: flex; align-items: center;">
                                    
                                    <li><a href="index.php" style="color: white !important;">Inicio</a></li>
                                    <li><a href="about.php" style="color: white !important;">Sobre Nosotros</a></li>
                                    <li><a href="courses.php" style="color: white !important;">Cursos</a></li>
                                    <li><a href="contact.php" style="color: white !important;">Herramientas</a></li>
                                    <li class="active"><a href="samida.php" style="color: white !important;">SAMIDA</a></li>

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
    </header>

  <div class="home">
        <div class="breadcrumbs_container">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="breadcrumbs"></div>
                    </div>
                </div>
            </div>
        </div>          
    </div>

  <main class="main-content-custom">
    
    <!-- SECCIÓN EQUIPO: Con espacio amplio entre fotos (gx-5) -->
<!-- SECCIÓN EQUIPO: Formato Tarjetas con Espaciado Masivo -->
<div class="team" style="padding: 120px 0; background-color: #fcfbfe;">
    <div class="container" style="max-width: 1400px;"> <!-- Contenedor ampliado para permitir márgenes gigantes -->
        
        <div class="row">
            <div class="col">
                <div class="section_title_container text-center" style="margin-bottom: 80px;">
                    <h2 class="section_title" style="font-size: 38px; font-weight: 700; color: #191c1e; margin-bottom: 15px;">El mejor equipo de trabajo</h2>
                    <div class="section_subtitle"><p style="font-size: 16px; color: #666;">Nuestro equipo está formado por diseñadores y desarrolladores.</p></div>
                </div>
            </div>
        </div>
        
        <div class="row">
            
            <!-- TARJETA 1 -->
            <div class="col-lg-3 col-md-6" style="padding: 0 35px; margin-bottom: 50px;"> <!-- Espaciado masivo entre columnas -->
                <div class="card h-100" style="border: 1px solid #f0edf7; border-radius: 20px; box-shadow: 0 15px 35px rgba(0,0,0,0.06); padding: 25px; text-align: center; background: #ffffff; transition: transform 0.3s ease;">
                    <div class="team_image">
                        <img src="imagenes/fotoSarahy.png" alt="Sarahy Zanahua" style="width: 100%; height: 260px; object-fit: cover; border-radius: 15px; background-color: #e9ecef;">
                    </div>
                    <div class="card-body" style="padding: 25px 0 10px 0;">
                        <h3 style="font-size: 20px; font-weight: 700; text-decoration: none; color: #191c1e; margin-bottom: 8px;">Sarahy Zanahua</h3>
                        <p style="color: #777; font-size: 14px; margin-bottom: 18px;">Diseñadora</p>
                        <a href="#" style="color: #A188DD; font-size: 24px; text-decoration: none;"><i class="fa fa-instagram" aria-hidden="true"></i></a>
                    </div>
                </div>
            </div>

            <!-- TARJETA 2 -->
            <div class="col-lg-3 col-md-6" style="padding: 0 35px; margin-bottom: 50px;">
                <div class="card h-100" style="border: 1px solid #f0edf7; border-radius: 20px; box-shadow: 0 15px 35px rgba(0,0,0,0.06); padding: 25px; text-align: center; background: #ffffff; transition: transform 0.3s ease;">
                    <div class="team_image">
                        <img src="imagenes/fotoDayanapng.png" alt="Dayana Ortiz" style="width: 100%; height: 260px; object-fit: cover; border-radius: 15px; background-color: #e9ecef;">
                    </div>
                    <div class="card-body" style="padding: 25px 0 10px 0;">
                        <h3 style="font-size: 20px; font-weight: 700; text-decoration: none; color: #191c1e; margin-bottom: 8px;">Dayana Ortiz</h3>
                        <p style="color: #777; font-size: 14px; margin-bottom: 18px;">Diseñadora & Desarrolladora</p>
                        <a href="#" style="color: #A188DD; font-size: 24px; text-decoration: none;"><i class="fa fa-instagram" aria-hidden="true"></i></a>
                    </div>
                </div>
            </div>

            <!-- TARJETA 3 -->
            <div class="col-lg-3 col-md-6" style="padding: 0 35px; margin-bottom: 50px;">
                <div class="card h-100" style="border: 1px solid #f0edf7; border-radius: 20px; box-shadow: 0 15px 35px rgba(0,0,0,0.06); padding: 25px; text-align: center; background: #ffffff; transition: transform 0.3s ease;">
                    <div class="team_image">
                        <img src="imagenes/fotoMichelle.png" alt="Joana Michelle" style="width: 100%; height: 260px; object-fit: cover; border-radius: 15px; background-color: #e9ecef;">
                    </div>
                    <div class="card-body" style="padding: 25px 0 10px 0;">
                        <h3 style="font-size: 20px; font-weight: 700; text-decoration: none; color: #191c1e; margin-bottom: 8px;">Joana Michelle</h3>
                        <p style="color: #777; font-size: 14px; margin-bottom: 18px;">Programadora y Diseñadora</p>
                        <a href="#" style="color: #A188DD; font-size: 24px; text-decoration: none;"><i class="fa fa-instagram" aria-hidden="true"></i></a>
                    </div>
                </div>
            </div>

            <!-- TARJETA 4 -->
            <div class="col-lg-3 col-md-6" style="padding: 0 35px; margin-bottom: 50px;">
                <div class="card h-100" style="border: 1px solid #f0edf7; border-radius: 20px; box-shadow: 0 15px 35px rgba(0,0,0,0.06); padding: 25px; text-align: center; background: #ffffff; transition: transform 0.3s ease;">
                    <div class="team_image">
                        <img src="imagenes/fotoSarahi.png" alt="Sarahi Santa Maria" style="width: 100%; height: 260px; object-fit: cover; border-radius: 15px; background-color: #e9ecef;">
                    </div>
                    <div class="card-body" style="padding: 25px 0 10px 0;">
                        <h3 style="font-size: 20px; font-weight: 700; text-decoration: none; color: #191c1e; margin-bottom: 8px;">Sarahi Santa Maria</h3>
                        <p style="color: #777; font-size: 14px; margin-bottom: 18px;">Diseñadora</p>
                        <a href="#" style="color: #A188DD; font-size: 24px; text-decoration: none;"><i class="fa fa-instagram" aria-hidden="true"></i></a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

    <section class="intro-section" style="padding: 140px 0; background-color: #fcfbfe; border-top: 1px solid #f0edf7; border-bottom: 1px solid #f0edf7;">
        <div class="cc-container intro-grid">
            <div>
                <span class="intro-tag" style="letter-spacing: 2px; font-weight: 600; color: #A188DD;">INNOVACIÓN EDUCATIVA</span>
                <h2 class="intro-title" style="margin-top: 20px; margin-bottom: 30px; line-height: 1.35; font-size: 32px; font-weight: 700;">
                    El aprendizaje va aumentando.<br/> ¿Estás Listo para la Experiencia?
                </h2>
                <p class="intro-text" style="line-height: 1.9; color: #555; font-size: 16px;">
                    La Realidad Aumentada no es solo una tecnología; es una nueva forma de ver, aprender e interactuar con el mundo. Está transformando industrias, revolucionando la educación y enriqueciendo nuestras vidas. Prepárate para descubrir cómo la magia de la RA lleva tu realidad a una nueva dimensión.
                </p>
            </div>
            <div class="stats-grid">
                <div>
                    <div class="img-box">
                        <img alt="Modelado 3D" src="imagenes/pingui2.png"/>
                    </div>
                    <div class="stat-box blue">
                        <span class="stat-number">100%</span>
                        <span class="stat-label">Interactivo</span>
                    </div>
                </div>
                <div class="stats-col-2">
                    <div class="stat-box brown">
                        <span class="stat-number">3D</span>
                        <span class="stat-label">Modelos</span>
                    </div>
                    <div class="img-box">
                        <img alt="Realidad Aumentada" src="imagenes/jugando.png"/>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section style="padding: 140px 0; background-color: #f8f9fa;">
        <div class="container">
            <div class="row d-flex align-items-stretch"> 
                
                <div class="col-md-4 mb-5">
                    <div class="card h-100 bounce-card" style="border: none; border-top: 5px solid #51CCCC; box-shadow: 0 10px 30px rgba(0,0,0,0.06); border-radius: 16px; padding: 35px 30px;">
                        <div style="color: #51CCCC; margin-bottom: 25px;">
                            <span class="material-symbols-outlined" style="font-size: 48px;">rocket_launch</span>
                        </div>
                        <h3 style="font-weight: 700; color: #191c1e; margin-bottom: 18px; font-size: 22px;">Misión</h3>
                        <p style="color: #43474c; flex-grow: 1; line-height: 1.8; font-size: 15px;">
                            En SAMIDA Estudio Creativo Digital diseñamos soluciones estratégicas que fortalecen la presencia de nuestros clientes en el entorno digital. Integramos innovación tecnológica y diseño de calidad para crear productos que conectan marcas con sus audiencias, convirtiendo ideas en resultados que marcan tendencia.
                        </p>
                    </div>
                </div>

                <div class="col-md-4 mb-5">
                    <div class="card h-100 bounce-card" style="border: none; border-top: 5px solid #EEB4C5; box-shadow: 0 10px 30px rgba(0,0,0,0.06); border-radius: 16px; padding: 35px 30px;">
                        <div style="color: #EEB4C5; margin-bottom: 25px;">
                            <span class="material-symbols-outlined" style="font-size: 48px;">visibility</span>
                        </div>
                        <h3 style="font-weight: 700; color: #191c1e; margin-bottom: 18px; font-size: 22px;">Visión</h3>
                        <p style="color: #43474c; flex-grow: 1; line-height: 1.8; font-size: 15px;">
                            SAMIDA aspira a consolidarse como un referente en innovación y liderazgo digital, siendo la opción principal para empresas que buscan evolución constante y solidez visual. Busca anticiparse a los cambios tecnológicos y destacar a nivel global con soluciones creativas e integrales.
                        </p>
                    </div>
                </div>

                <div class="col-md-4 mb-5">
                    <div class="card h-100 bounce-card" style="border: none; border-top: 5px solid #A188DD; box-shadow: 0 10px 30px rgba(0,0,0,0.06); border-radius: 16px; padding: 35px 30px;">
                        <div style="color: #A188DD; margin-bottom: 25px;">
                            <span class="material-symbols-outlined" style="font-size: 48px;">groups</span> 
                        </div>
                        <h3 style="font-weight: 700; color: #191c1e; margin-bottom: 18px; font-size: 22px;">Valores Corporativos</h3>
                        <p style="color: #43474c; flex-grow: 1; line-height: 1.8; font-size: 15px;">
                            Nuestros valores se basan en la innovación, creando soluciones fuera de lo convencional; el compromiso, actuando con responsabilidad y enfoque en resultados; la calidad, integrando diseño y funcionalidad con precisión; y la adaptabilidad, evolucionando constantemente ante los cambios tecnológicos.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>
</main>
<style>
    .bounce-card:hover {
        transform: translateY(-10px); /* El "rebote" hacia arriba */
        box-shadow: 0 12px 30px rgba(0,0,0,0.1) !important; /* Sombra más profunda al flotar */
    }
</style>
    </main>
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
                                        <a href="#"><div class="footer_logo_text">NU<span style="color: #51CCCC;">MI</span></div></a>
                                    </div>
                                    <div class="footer_about_text"><p>Tu camino hacia la excelencia educativa con tecnología de vanguardia.</p></div>
                                    <div class="footer_social">
                                        <ul><li><a href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a></li></ul>
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
                        </div>
                    </div>
                </div>
            </div>
            <div class="row copyright_row">
                <div class="col">
                    <div class="copyright d-flex flex-lg-row flex-column align-items-center justify-content-start">
                        <div class="cr_text">
Copyright &copy;<script>document.write(new Date().getFullYear());</script> TODOS LOS DERECHOS RESERVADOS A LA EMPRESA <i class="fa fa-heart-o" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">NUMI</a>
</div>
                        <div class="ml-lg-auto cr_links">
                            <ul class="cr_list">
                            </ul>
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
<script src="plugins/greensock/TweenMax.min.js"></script>
<script src="plugins/greensock/TimelineMax.min.js"></script>
<script src="plugins/scrollmagic/ScrollMagic.min.js"></script>
<script src="plugins/greensock/animation.gsap.min.js"></script>
<script src="plugins/greensock/ScrollToPlugin.min.js"></script>
<script src="plugins/OwlCarousel2-2.2.1/owl.carousel.js"></script>
<script src="plugins/easing/easing.js"></script>
<script src="plugins/parallax-js-master/parallax.min.js"></script>
<script src="js/custom.js"></script>
</body>
</html>