<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
<title>Perfiles - Herramientas NUMI</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="Unicat project">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="styles/bootstrap4/bootstrap.min.css">
<link href="plugins/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="styles/contact.css">
<link rel="stylesheet" type="text/css" href="styles/contact_responsive.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />

<style>
/* --- ESTILOS DE LAS TARJETAS AJUSTADOS --- */
.roles-section {
    background-color: #f7f9fb;
    padding: 60px 0;
}
.role-card {
    background-color: #ffffff;
    padding: 2.5rem 1.5rem;
    border-radius: 1rem;
    display: flex;
    flex-direction: column;
    border-top: 5px solid #EEB4C5;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
    transition: all 0.4s ease;
    height: 100%;
    text-align: center;
}
.role-card:hover {
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
    transform: translateY(-8px);
}
.role-icon {
    font-size: 3rem;
    color: #51CCCC;
    margin-bottom: 1rem;
    display: block;
}
.role-title {
    font-size: 1.3rem;
    font-weight: 700;
    color: #191c1e;
    margin-bottom: 0.8rem;
}
.role-desc {
    font-size: 0.9rem;
    color: #43474c;
    line-height: 1.6;
    margin-bottom: 1.5rem;
    flex-grow: 1;
}
.role-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background-color: #EEB4C5;
    color: white !important;
    padding: 0.6rem 1.2rem;
    border-radius: 0.5rem;
    font-weight: 600;
    text-decoration: none !important;
    transition: all 0.2s;
}

/* --- BOTÓN "CONOCER PERFILES" CENTRADO Y CON SOMBRA --- */
.btn-get-started {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    margin: 60px auto 0;
    width: fit-content;
    padding: 1rem 2.5rem;
    font-weight: 600;
    color: white !important;
    background-color: #51CCCC;
    border-radius: 0.5rem;
    transition: all 0.2s;
    text-decoration: none !important;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
}
.btn-get-started:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 25px rgba(0, 0, 0, 0.2);
}

/* --- ESTILOS HERO ORIGINALES --- */
.hero-section { padding: 6rem 0; position: relative; z-index: 1; }
.hero-image-container { 
    position: relative; border-radius: 2rem; overflow: hidden; 
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); transition: transform 0.5s ease; 
}
.hero-img { width: 100%; aspect-ratio: 4/3; object-fit: cover; }
.hero-title { font-size: 3.5rem; font-weight: 800; line-height: 1.1; color: #1f2937; margin-bottom: 1.5rem; }
.hero-text { font-size: 1.125rem; line-height: 1.7; color: #4b5563; margin-bottom: 2rem; }
.badge-new { 
    display: inline-block; padding: 0.5rem 1rem; font-size: 0.75rem; font-weight: 700; 
    text-transform: uppercase; color: #2563eb; background-color: #eff6ff; border-radius: 9999px; margin-bottom: 1.5rem; 
}

/* --- ESTILOS PARA LAS TARJETAS DE APK --- */
.apk-section {
    padding: 80px 0;
    background-color: #f7f9fb;
}
.apk-card {
    background-color: #ffffff;
    padding: 3.5rem 2rem;
    border-radius: 1.5rem;
    text-align: center;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
    height: 100%;
    position: relative;
    border-bottom: 5px solid transparent;
}
.apk-card.vr-card {
    border-bottom-color: #A188DD; 
}
.apk-card.ar-card {
    border-bottom-color: #51CCCC; 
}
.apk-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
}
.apk-icon {
    font-size: 4.5rem;
    margin-bottom: 1.5rem;
    display: inline-block;
}
.vr-icon { color: #A188DD; }
.ar-icon { color: #51CCCC; }
.apk-title {
    font-size: 1.5rem;
    font-weight: 800;
    color: #191c1e;
    margin-bottom: 1rem;
}
.apk-desc {
    color: #64748b;
    font-size: 1rem;
    line-height: 1.6;
    margin-bottom: 2.5rem;
}
.btn-download {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 1rem 2rem;
    border-radius: 50px;
    color: white !important;
    font-weight: 700;
    text-decoration: none !important;
    transition: all 0.2s;
}
.btn-download.btn-vr {
    background-color: #A188DD;
    box-shadow: 0 4px 15px rgba(161, 136, 221, 0.4);
}
.btn-download.btn-ar {
    background-color: #51CCCC;
    box-shadow: 0 4px 15px rgba(81, 204, 204, 0.4);
}
.btn-download:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}
</style>
</head>
<body>

<div class="super_container">

    <header class="header">
        <style>
            .main_nav li a::after {
                display: none !important;
            }
        </style>

        <div class="header_container" style="background-color: #A188DD; box-shadow: 0px 3px 10px rgba(0,0,0,0.1);">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="header_content d-flex flex-row align-items-center justify-content-start">
                            
                            <div class="logo_container">
                                <a href="index.php">
                                    <img src="imagenes/logo (1).png" alt="Unicat Logo" style="height:60px;">
                                </a>
                            </div>
                            
                            <nav class="main_nav_contaner ml-auto" style="display: flex; align-items: center; height: 100%;">
                                <ul class="main_nav" style="margin-bottom: 0; display: flex; align-items: center;">
                                    <li><a href="index.php" style="color: white !important;">Inicio</a></li>
                                    <li><a href="about.php" style="color: white !important;">Sobre Nosotros</a></li>
                                    <li><a href="courses.php" style="color: white !important;">Cursos</a></li>
                                    <li class="active"><a href="contact.php" style="color: white !important;">Herramientas</a></li>
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
</div>
    
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center mb-5">
                <div class="col-lg-6 mb-20 mb-lg-0">
                    <div class="hero-image-container">
                        <img src="imagenes/pinguinobonito.png" alt="Professional Workspace" class="hero-img">
                    </div>
                </div>

                <div class="col-lg-6 px-lg-5">
                    <div class="content-overlay">
                        <span class="badge-new">Nuevos Estándares</span>
                        <h1 class="hero-title">Tu Puerta al Aprendizaje Inteligente</h1>
                        <p class="hero-text">
                            Crear un perfil en NUMI no es solo completar un registro, es activar una herramienta diseñada para evolucionar contigo. Al unirte, dejas de ser un espectador para convertirte en el protagonista de tu propia ruta educativa.
                        </p>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center text-center mt-5 pt-5 border-top border-light">
                <div class="col-lg-8">
                    <h2 class="mb-4 font-weight-bold" style="color: #1f2937;">Una Experiencia Personalizada y Sin Límites</h2>
                    <p class="hero-text mb-5">
                        Hemos diseñado cada interacción para que tu única preocupación sea aprender; nosotros nos encargamos de que la plataforma se sienta como un aliado natural.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================== -->
    <!-- SECCIÓN DE DESCARGA DE APKS                -->
    <!-- ========================================== -->
    <section class="apk-section">
        <div class="container">
            <!-- Título de la sección -->
            <div class="row justify-content-center mb-5 pb-3">
               <div class="col-lg-8 text-center">
    <h2 class="font-weight-bold" style="color: #1f2937; font-size: 2.5rem; margin-bottom: 1rem;">Nuestras Aplicaciones</h2>
    <p style="color: #64748b; font-size: 1.1rem; line-height: 1.6;">
        Lleva el aprendizaje al siguiente nivel. Haz clic en el botón de descarga; descargando las APK podrás jugar y aprender con los modelos designados para brindarte experiencias inmersivas e interactivas.
    </p>
</div>
            </div>
            
            <div class="row">
                <!-- TARJETA REALIDAD VIRTUAL (VR) -->
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="apk-card vr-card">
                        <span class="material-symbols-outlined apk-icon vr-icon">360</span>
                        <h3 class="apk-title">NUMI - Realidad Virtual</h3>
                        <p class="apk-desc">
                            Sumérgete en entornos de aprendizaje 100% interactivos. Explora conceptos complejos de manera visual y práctica como si estuvieras literalmente dentro de ellos.
                        </p>
                        <!-- Reemplaza "ruta_a_tu_app_vr.apk" por el enlace real a tu archivo -->
                        <a href="ruta_a_tu_app_vr.apk" class="btn-download btn-vr" download>
                            <span class="material-symbols-outlined">download</span>
                            Descargar APK (VR)
                        </a>
                    </div>
                </div>

                <!-- TARJETA REALIDAD AUMENTADA (AR) -->
                <div class="col-lg-6">
                    <div class="apk-card ar-card">
                        <span class="material-symbols-outlined apk-icon ar-icon">view_in_ar</span>
                        <h3 class="apk-title">NUMI - Realidad Aumentada</h3>
                        <p class="apk-desc">
                            Combina el mundo real con elementos digitales. Escanea tus materiales de estudio y mira cómo cobran vida a través de la cámara de tu teléfono inteligente.
                        </p>
                        <!-- Reemplaza "ruta_a_tu_app_ar.apk" por el enlace real a tu archivo -->
                        <a href="ruta_a_tu_app_ar.apk" class="btn-download btn-ar" download>
                            <span class="material-symbols-outlined">download</span>
                            Descargar APK (AR)
                        </a>
                    </div>
                </div>
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