<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Unicat</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="Unicat project">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="styles/bootstrap4/bootstrap.min.css">
<link href="plugins/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="plugins/OwlCarousel2-2.2.1/owl.carousel.css">
<link rel="stylesheet" type="text/css" href="plugins/OwlCarousel2-2.2.1/owl.theme.default.css">
<link rel="stylesheet" type="text/css" href="plugins/OwlCarousel2-2.2.1/animate.css">
<link rel="stylesheet" type="text/css" href="styles/main_styles.css">
<link rel="stylesheet" type="text/css" href="styles/responsive.css">
</head>
<body>

<div class="super_container">

    <div class="top_bar_login ml-auto" style="padding: 10px 0;">
        <?php if (isset($_SESSION['usuario_nombre']) && isset($_SESSION['registro_completo']) && $_SESSION['registro_completo'] === true): ?>
            
            <div style="display: flex; align-items: center;">
                <div style="width: 48px; height: 48px; background-color: <?php echo (isset($_SESSION['rol']) && ($_SESSION['rol'] === 'admin' || $_SESSION['rol'] === 'maestro')) ? '#EEB4C5' : '#51CCCC'; ?>; border-radius: 50%; overflow: hidden; border: 2px solid white; margin-right: 12px;">
                    <img src="imagenes/contento.png" alt="Perfil" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                
                <div style="display: flex; flex-direction: column; justify-content: center; line-height: 1.3;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span style="font-weight: bold; color: white; font-size: 17px; letter-spacing: 0.3px;">
                            <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?>
                        </span>
                        
                        <!-- ETIQUETAS DE ROL (TOP BAR) -->
                        <?php if(isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
                            <span style="font-size: 11px; font-weight: 800; color: #141022; background: #EEB4C5; padding: 3px 8px; border-radius: 6px; text-transform: uppercase; letter-spacing: 0.5px;">Admin</span>
                        
                        <?php elseif(isset($_SESSION['rol']) && $_SESSION['rol'] === 'maestro'): ?>
                            <span style="font-size: 11px; font-weight: 800; color: white; background: #A188DD; padding: 3px 8px; border-radius: 6px; text-transform: uppercase; letter-spacing: 0.5px;">Maestro</span>
                        
                        <!-- NUEVA ETIQUETA PARA EL TUTOR -->
                        <?php elseif(isset($_SESSION['rol']) && $_SESSION['rol'] === 'tutor'): ?>
                            <span style="font-size: 11px; font-weight: 800; color: white; background: #51CCCC; padding: 3px 8px; border-radius: 6px; text-transform: uppercase; letter-spacing: 0.5px;">Tutor</span>
                        
                        <?php elseif(isset($_SESSION['rol']) && $_SESSION['rol'] === 'alumno'): ?>
                            <div style="display: flex; gap: 5px; align-items: center;">
                                <span style="font-size: 12px; font-weight: 700; color: white; background: rgba(255, 255, 255, 0.25); padding: 3px 9px; border-radius: 6px; letter-spacing: 0.3px;">Estudiante</span>
                                <!-- AQUI MOSTRAMOS EL ID DEL ALUMNO PARA EL TUTOR -->
                                <?php if(isset($_SESSION['id_alumno'])): ?>
                                    <span style="font-size: 11px; font-weight: 800; color: #141022; background: #51CCCC; padding: 3px 8px; border-radius: 6px; letter-spacing: 0.5px;" title="Dale este código a tu tutor para vincular tu cuenta">ID Tutor: #<?php echo $_SESSION['id_alumno']; ?></span>
                                <?php endif; ?>
                            </div>
                        
                        <?php else: ?>
                            <span style="font-size: 12px; font-weight: 700; color: white; background: rgba(255, 255, 255, 0.25); padding: 3px 9px; border-radius: 6px; letter-spacing: 0.3px;">Usuario</span>
                        <?php endif; ?>
                    </div>
                    
                    <div style="display: flex; align-items: center; gap: 10px; margin-top: 3px;">
                        <?php if(isset($_SESSION['rol']) && ($_SESSION['rol'] === 'admin' || $_SESSION['rol'] === 'maestro')): ?>
                            <a href="paneladmin.php" style="font-size: 11px; font-weight: bold; color: #A188DD; background: white; padding: 2px 6px; border-radius: 4px; text-decoration: none;">PANEL</a>
                        <!-- BOTÓN PARA QUE EL TUTOR VAYA A SU PANEL -->
                        <?php elseif(isset($_SESSION['rol']) && $_SESSION['rol'] === 'tutor'): ?>
                            <a href="progreso_hijo.php" style="font-size: 11px; font-weight: bold; color: #51CCCC; background: white; padding: 2px 6px; border-radius: 4px; text-decoration: none;">PROGRESO</a>
                        <?php endif; ?>
                        <a href="logout.php" style="font-size: 12px; text-decoration: underline; color: #f0f0f0; opacity: 0.9;">Salir</a>
                    </div>
                </div>
            </div>

        <?php else: ?>

            <div style="display: flex; align-items: center; gap: 15px;">
                <a href="login_alumno.php" style="color: white; font-weight: 600; font-size: 15px; text-decoration: none;">Iniciar Sesión</a>
                <a href="nuevo_registro.php" style="color: #A188DD; background: white; font-weight: bold; font-size: 14px; padding: 6px 16px; border-radius: 20px; text-decoration: none; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">Registrarse</a>
            </div>

        <?php endif; ?>
    </div>

    <header class="header">
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
                                    <li class="active"><a href="#" style="color: white !important;">Inicio</a></li>
                                    <li><a href="about.php" style="color: white !important;">Sobre Nosotros</a></li>
                                    <li><a href="courses.php" style="color: white !important;">Cursos</a></li>
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
                                                        
                                                        <!-- ETIQUETAS DE ROL (MAIN NAV) -->
                                                        <?php if(isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
                                                            <span style="font-size: 11px; font-weight: 800; color: #141022; background: #EEB4C5; padding: 3px 8px; border-radius: 6px; text-transform: uppercase; letter-spacing: 0.5px;">Admin</span>
                                                        
                                                        <?php elseif(isset($_SESSION['rol']) && $_SESSION['rol'] === 'maestro'): ?>
                                                            <span style="font-size: 11px; font-weight: 800; color: white; background: #A188DD; padding: 3px 8px; border-radius: 6px; text-transform: uppercase; letter-spacing: 0.5px;">Maestro</span>
                                                        
                                                        <!-- NUEVA ETIQUETA PARA EL TUTOR -->
                                                        <?php elseif(isset($_SESSION['rol']) && $_SESSION['rol'] === 'tutor'): ?>
                                                            <span style="font-size: 11px; font-weight: 800; color: white; background: #51CCCC; padding: 3px 8px; border-radius: 6px; text-transform: uppercase; letter-spacing: 0.5px;">Tutor</span>
                                                        
                                                        <?php elseif(isset($_SESSION['rol']) && $_SESSION['rol'] === 'alumno'): ?>
                                                            <div style="display: flex; gap: 5px; align-items: center;">
                                                                <span style="font-size: 12px; font-weight: 700; color: white; background: rgba(255, 255, 255, 0.25); padding: 3px 9px; border-radius: 6px; letter-spacing: 0.3px;">Estudiante</span>
                                                                <!-- AQUI MOSTRAMOS EL ID DEL ALUMNO PARA EL TUTOR -->
                                                                <?php if(isset($_SESSION['id_alumno'])): ?>
                                                                    <span style="font-size: 11px; font-weight: 800; color: #141022; background: #51CCCC; padding: 3px 8px; border-radius: 6px; letter-spacing: 0.5px;" title="Dale este código a tu tutor para vincular tu cuenta">ID Tutor: #<?php echo $_SESSION['id_alumno']; ?></span>
                                                                <?php endif; ?>
                                                            </div>
                                                        
                                                        <?php else: ?>
                                                            <span style="font-size: 12px; font-weight: 700; color: white; background: rgba(255, 255, 255, 0.25); padding: 3px 9px; border-radius: 6px; letter-spacing: 0.3px;">Usuario</span>
                                                        <?php endif; ?>
                                                    </div>
                                                    
                                                    <div style="display: flex; align-items: center; gap: 10px; margin-top: 3px;">
                                                        <?php if(isset($_SESSION['rol']) && ($_SESSION['rol'] === 'admin' || $_SESSION['rol'] === 'maestro')): ?>
                                                            <a href="crear_clase.php" style="font-size: 11px; font-weight: bold; color: #A188DD; background: white; padding: 2px 6px; border-radius: 4px; text-decoration: none;">PANEL</a>
                                                        <!-- BOTÓN PARA QUE EL TUTOR VAYA A SU PANEL -->
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
                                                    <img src="imagenes/ganador.png" alt="Icono Ganador Pinguino" style="width: 60%; height: 60%; object-fit: contain;">
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
	<div class="menu d-flex flex-column align-items-end justify-content-start text-right menu_mm trans_400">
    <div class="menu_close_container">
        <div class="menu_close"><div></div><div></div></div>
    </div>
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

<div class="home" style="margin-top: 110px;">
    <div class="home_slider_container">
        
        <div class="owl-carousel owl-theme home_slider">
            
            <div class="owl-item">
                <div class="home_slider_background" style="background-image:url(images/banner1.png); background-size: cover; background-position: center; background-repeat: no-repeat;"></div>
                <div class="home_slider_content">
                    <div class="container">
                        <div class="row">
                            <div class="col text-center">
                                <div style="background-color: rgba(255, 255, 255, 0.33); padding: 10px; border-radius: 10px; display: inline-block;">
                                </div>
                                <div class="home_slider_form_container">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="owl-item">
                <div class="home_slider_background" style="background-image:url(images/banner2.png); background-size: cover; background-position: center; background-repeat: no-repeat;"></div>
                <div class="home_slider_content">
                    <div class="container">
                        <div class="row">
                            <div class="col text-center">
                                <div style="background-color: rgba(255, 255, 255, 0.85); padding: 30px; border-radius: 15px; display: inline-block;">
                                </div>
                                <div class="home_slider_form_container">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
		<!-- Home Slider Nav -->

		<div class="home_slider_nav home_slider_prev"><i class="fa fa-angle-left" aria-hidden="true"></i></div>
		<div class="home_slider_nav home_slider_next"><i class="fa fa-angle-right" aria-hidden="true"></i></div>
	</div>

	<!-- Features -->

	<div class="features">
		<div class="container">
			<div class="row">
				<div class="col">
					<div class="section_title_container text-center">
						<h2 class="section_title">Bienvenido a NUMI</h2>
						<div class="section_subtitle"><p>Descubre un espacio de aprendizaje interactivo. Todo lo que necesitas para impulsar tu educación y desarrollar nuevas habilidades en un solo lugar.</p></div>
					</div>
				</div>
			</div>
			<div class="row features_row">
				
				<!-- Features Item -->
				<div class="col-lg-3 feature_col">
					<div class="feature text-center trans_400">
						<div class="feature_icon"><img src="images/icon_1.png" alt=""></div>
						<h3 class="feature_title">Aprendizaje Interactivo</h3>
						<div class="feature_text"><p>Aprende de maestros y especialistas dedicados a guiar tu desarrollo académico en cada paso.</p></div>
					</div>
				</div>

				<!-- Features Item -->
				<div class="col-lg-3 feature_col">
					<div class="feature text-center trans_400">
						<div class="feature_icon"><img src="images/icon_2.png" alt=""></div>
						<h3 class="feature_title">Libros y Biblioteca</h3>
						<div class="feature_text"><p>Accede a una amplia colección de libros, guías y recursos de estudio siempre disponibles para ti.</p></div>
					</div>
				</div>

				<!-- Features Item -->
				<div class="col-lg-3 feature_col">
					<div class="feature text-center trans_400">
						<div class="feature_icon"><img src="images/icon_3.png" alt=""></div>
						<h3 class="feature_title">Mejores Cursos</h3>
						<div class="feature_text"><p>Explora diferentes cursos con los más altos estándares para fortalecer tu aprendizaje.</p></div>
					</div>
				</div>

				<!-- Features Item -->
				<div class="col-lg-3 feature_col">
					<div class="feature text-center trans_400">
						<div class="feature_icon"><img src="images/icon_4.png" alt=""></div>
						<h3 class="feature_title">Premios y Reconocimientos</h3>
						<div class="feature_text"><p>Medir tu progreso, alcanza tus metas y obtén reconocimientos que validen tu esfuerzo.</p></div>
					</div>
				</div>

			</div>
		</div>
	</div>

	<!-- Popular Courses -->

	<div class="courses">
		<div class="section_background parallax-window" data-parallax="scroll" data-image-src="images/courses_background.jpg" data-speed="0.8"></div>
		<div class="container">
			<div class="row">
				<div class="col">
					<div class="section_title_container text-center">
						<h2 class="section_title">AVENTURA MATEMATICA!!!</h2>
						<div class="section_subtitle"><p>Nos esforzamos para que cada lección sea clara, divertida y útil.</p></div>
					</div>
				</div>
			</div>
			<div class="row courses_row">
				
				<div class="row justify-content-center">
    
    <div class="col-lg-4 course_col">
        <div class="course">
            <div class="course_image"><img src="imagenes/libro1.jpeg" alt=""></div>
            <div class="course_body">
                <h3 class="course_title"><a href="course.html">Desafios Matematicos</a></h3>
                <div class="course_teacher">Quinto Grado</div>
                <div class="course_text">
                    <p>Desarrolla el razonamiento lógico mediante la resolución de problemas prácticos con fracciones, geometría y porcentajes aplicados a la vida diaria.</p>
                </div>
            </div>
            <div class="course_footer">
                <div class="course_footer_content d-flex flex-row align-items-center justify-content-start">
                    <div class="course_info">
                        <i class="fa fa-graduation-cap" aria-hidden="true"></i>
                        <span>20 Estudiantes</span>
                    </div>
                    <div class="course_info">
                        <i class="fa fa-star" aria-hidden="true"></i>
                        <span>5 Estrellas</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 course_col">
        <div class="course">
            <div class="course_image"><img src="imagenes/libro3.jpeg" alt=""></div>
            <div class="course_body">
                <h3 class="course_title"><a href="course.html">Saberes y Pensamiento</a></h3>
                <div class="course_teacher">Primero De Secundaria</div>
                <div class="course_text">
                    <p>Integra matemáticas y ciencias bajo el modelo de la Nueva Escuela Mexicana, enfocándose en resolver problemas del entorno y la comunidad mediante proyectos.</p>
                </div>
            </div>
            <div class="course_footer">
                <div class="course_footer_content d-flex flex-row align-items-center justify-content-start">
                    <div class="course_info">
                        <i class="fa fa-graduation-cap" aria-hidden="true"></i>
                        <span>20 Estudiantes</span>
                    </div>
                    <div class="course_info">
                        <i class="fa fa-star" aria-hidden="true"></i>
                        <span>5 Estrellas</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

			</div>
            <div class="row mt-4">
                <div class="col">
                    
                </div>
            </div>
        </div>
    </div>

	<!-- Counter -->

	<div class="counter">
		<div class="counter_background" style="background-image:url(imagenes/background.png)"></div>
		<div class="container">
			<div class="row">
				<div class="col-lg-6">
					<div class="counter_content">
						<h2 class="counter_title">COMIENZA AHORA!!!</h2>
						<div class="counter_text"><p>“Trabajamos con creatividad e innovación para transformar el aprendizaje en una experiencia emocionante, divertida y significativa para los niños. En Numi buscamos que cada actividad motive la curiosidad, fomente el pensamiento y convierta el aprendizaje en una aventura llena de descubrimientos.”</p></div>

						<!-- Milestones -->

						<div class="milestones d-flex flex-md-row flex-column align-items-center justify-content-between">
							
						</div>
					</div>

				</div>
			</div>

			<div class="counter_form">
    <div class="row fill_height">
        <div class="col fill_height">
            <form class="counter_form_content d-flex flex-column align-items-center justify-content-center" action="#">
                <div class="counter_form_title">EMPEZAR CURSOS</div>
                
                <div class="counter_text_display" style="color: #000000; text-align: center; margin: 20px 0; font-size: 16px; line-height: 1.6;">
                    ¡Bienvenido a la plataforma de aprendizaje NUMI! <br> 
                    Haz clic en el botón de abajo para explorar todos nuestros cursos disponibles y comenzar tu camino al éxito.
                </div>

                <a href="courses.html" class="counter_form_button d-flex align-items-center justify-content-center" style="text-decoration: none;">
                    Comenzar
                </a>
            </form>
        </div>
    </div>
</div>

		</div>
	</div>

				

<style>
	.blog {
    margin-top: 80px; /* Puedes cambiar este número si quieres más o menos espacio */
}
    .blog_post {
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin-bottom: 30px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        max-width: 400px; /* Ancho de la tarjeta */
        margin: 0 auto 30px auto;
    }
    .blog_post:hover {
        transform: translateY(-8px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }
    .blog_post_image img {
        width: 100%;
        height: 250px;
        object-fit: cover;
        display: block;
        background-color: #f4f4f4; /* Color de fondo por si no carga la imagen */
    }
    .blog_post_body {
        padding: 25px;
    }
    .blog_post_title a {
        font-size: 20px;
        font-weight: bold;
        color: #333;
        text-decoration: none;
        display: block;
        margin-bottom: 15px;
        line-height: 1.3;
    }
    .blog_post_title a:hover {
        color: #8a2be2; /* Morado al pasar el mouse */
    }
    .blog_post_meta ul {
        list-style: none;
        padding: 0;
        margin: 0 0 15px 0;
        display: flex;
        gap: 15px;
    }
    .blog_post_meta ul li a {
        font-size: 13px;
        color: #888;
        text-decoration: none;
        text-transform: uppercase;
    }
    .blog_post_text p {
        font-size: 14px;
        color: #666;
        line-height: 1.6;
    }
    .load_more a {
        display: inline-block;
        padding: 10px 25px;
        background-color: #8a2be2; /* Botón morado */
        color: #fff;
        text-decoration: none;
        border-radius: 20px;
        font-weight: bold;
    }
    .load_more a:hover {
        background-color: #6a1b9a;
    }
</style>

<div class="blog">
    <div class="container">
        <div class="row">
            
            <div class="col-md-4">
                <div class="blog_post_container">
                    <div class="blog_post trans_200">
                        <div class="blog_post_image">
                            <img src="imagenes/mate.png" alt="Matemáticas Divertidas" onerror="this.src='https://via.placeholder.com/400x250?text=NUMI+Primaria'">
                        </div>
                        <div class="blog_post_body">
                            <div class="blog_post_title">
                                <a href="blog_single.html">Matemáticas divertidas para 5º y 6º de Primaria</a>
                            </div>
                            <div class="blog_post_meta">
                                <ul>
                                    <li><a href="#">NUMI Team</a></li>
                                    <li><a href="#">Mar 13, 2026</a></li>
                                </ul>
                            </div>
                            <div class="blog_post_text">
                                <p>Descubre cómo NUMI transforma las fracciones y multiplicaciones en una aventura interactiva que tus hijos amarán...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="blog_post_container">
                    <div class="blog_post trans_200">
                        <div class="blog_post_image">
                            <img src="imagenes/secu.png" alt="Desafío Secundaria" onerror="this.src='https://via.placeholder.com/400x250?text=NUMI+Secundaria'">
                        </div>
                        <div class="blog_post_body">
                            <div class="blog_post_title">
                                <a href="blog_single.html">Prepárate para la Secundaria con nuestros desafíos</a>
                            </div>
                            <div class="blog_post_meta">
                                <ul>
                                    <li><a href="#">NUMI Team</a></li>
                                    <li><a href="#">Mar 10, 2026</a></li>
                                </ul>
                            </div>
                            <div class="blog_post_text">
                                <p>El paso a la secundaria puede ser difícil, pero con los módulos de álgebra de NUMI, estarás un paso adelante de todos...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="blog_post_container">
                    <div class="blog_post trans_200">
                        <div class="blog_post_image">
                            <img src="imagenes/aprende.png" alt="Método de aprendizaje" onerror="this.src='https://via.placeholder.com/400x250?text=Metodo+NUMI'">
                        </div>
                        <div class="blog_post_body">
                            <div class="blog_post_title">
                                <a href="blog_single.html">¿Por qué aprender con NUMI es más fácil y rápido?</a>
                            </div>
                            <div class="blog_post_meta">
                                <ul>
                                    <li><a href="#">Admin</a></li>
                                    <li><a href="#">Mar 05, 2026</a></li>
                                </ul>
                            </div>
                            <div class="blog_post_text">
                                <p>Analizamos cómo el aprendizaje visual y los personajes como Pingüi ayudan a retener conceptos matemáticos complejos...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div> </div> </div> ```

        <div class="row">
            
        </div>
    </div>
</div>

	<!-- Latest News -->

	

	
						

	<!-- Footer -->

	<footer class="footer">
		<div class="footer_background" style="background-image:url(imagenes/background.png)"></div>
		<div class="container">
			<div class="row footer_row">
				<div class="col">
					<div class="footer_content">
						<div class="row">

							<div class="col-lg-3 footer_col">
					
								<!-- Footer About -->
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
					
								<!-- Footer Contact -->
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

							<div class="col-lg-3 footer_col">
							

			<div class="row copyright_row">
				<div class="col">
					<div class="copyright d-flex flex-lg-row flex-column align-items-center justify-content-start">
						<div class="cr_text"><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
Copyright &copy;<script>document.write(new Date().getFullYear());</script> TODOS LOS DERECHOS RESERVADOS A LA EMPRESA <i class="fa fa-heart-o" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">NUMI</a>
<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></div>
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