<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Sobre Nosotros</title>
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
<link rel="stylesheet" type="text/css" href="styles/about.css">
<link rel="stylesheet" type="text/css" href="styles/about_responsive.css">
</head>
<body>

<div class="super_container">

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
                                    <li><a href="index.php" style="color: white !important;">Inicio</a></li>
                                    <li class="active"><a href="about.php" style="color: white !important;">Sobre Nosotros</a></li>
                                    <li><a href="courses.php" style="color: white !important;">Cursos</a></li>
                                    <li><a href="contact.php" style="color: white !important;">Herramientas</a></li>
                                    <li><a href="samida.php" style="color: white !important;">Samida</a></li>

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
                                                        
                                                        <!-- VALIDACIÓN EXACTA DE ETIQUETAS -->
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
                                                                <!-- ID DEL ALUMNO -->
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
                                                        <!-- BOTÓN PROGRESO PARA EL TUTOR -->
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
	</div>

	<!-- About -->
<div class="about" style="padding-top: 80px; padding-bottom: 80px; background-color: #fcfbfe;">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="section_title_container text-center" style="margin-bottom: 60px;">
                    <h2 class="section_title" style="font-size: 38px; font-weight: 700; color: #191c1e; margin-bottom: 15px;">Bienvenidos a NUMI</h2>
                    <div class="section_subtitle"><p style="font-size: 16px; color: #666;">¡Hola! Soy NUMI. ¡Súmate a la aventura de los números y descubre que aprender es tan divertido como jugar!</p></div>
                </div>
            </div>
        </div>
        
        <div class="row about_row align-items-stretch">
            
            <!-- About Item 1: Historia -->
            <div class="col-lg-4 col-md-4 mb-5 about_col">
                <div class="about_item card h-100" style="border: none; border-radius: 20px; box-shadow: 0 10px 35px rgba(0,0,0,0.06); overflow: hidden; background-color: #ffffff; transition: transform 0.3s ease;">
                    <div class="about_item_image" style="text-align: center; background-color: #ffffff; padding-top: 20px;">
                        <!-- object-fit: contain asegura que la imagen se vea completa sin cortes -->
                        <img src="imagenes/img3.png" alt="Nuestra Historia" style="width: 100%; height: 220px; object-fit: contain;">
                    </div>
                    <!-- d-flex flex-column mantiene la estructura de tarjeta -->
                    <div class="card-body d-flex flex-column" style="padding: 30px;">
                        <div class="about_item_title" style="margin-bottom: 15px;">
                            <h3 style="font-size: 22px; font-weight: 700;"><a href="#" style="text-decoration: none; color: #191c1e;">Nuestra Historia</a></h3>
                        </div>
                        <!-- flex-grow: 1 asegura que las tres tarjetas midan lo mismo -->
                        <div class="about_item_text" style="flex-grow: 1;">
                            <p style="color: #555; line-height: 1.7; font-size: 15px; margin: 0;">
                                La misión de NUMI, inspirada en la propuesta original de Joana Michelle Rosas Reyes, es transformar las matemáticas de conceptos abstractos en experiencias tangibles y dinámicas.<br><br>
                                A través de una plataforma web que integra Realidad Virtual y Realidad Aumentada, buscamos que los estudiantes de 5.º de primaria a 1.º de secundaria dejen atrás los libros estáticos. Nuestro compromiso es ofrecer herramientas tecnológicas que hablen su mismo lenguaje, facilitando un aprendizaje interactivo que despierte la curiosidad y el dominio matemático en las nuevas generaciones.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- About Item 2: Misión -->
            <div class="col-lg-4 col-md-4 mb-5 about_col">
                <div class="about_item card h-100" style="border: none; border-radius: 20px; box-shadow: 0 10px 35px rgba(0,0,0,0.06); overflow: hidden; background-color: #ffffff; transition: transform 0.3s ease;">
                    <div class="about_item_image" style="text-align: center; background-color: #ffffff; padding-top: 20px;">
                        <img src="imagenes/img4.png" alt="Nuestra Misión" style="width: 100%; height: 220px; object-fit: contain;">
                    </div>
                    <div class="card-body d-flex flex-column" style="padding: 30px;">
                        <div class="about_item_title" style="margin-bottom: 15px;">
                            <h3 style="font-size: 22px; font-weight: 700;"><a href="#" style="text-decoration: none; color: #191c1e;">Nuestra Misión</a></h3>
                        </div>
                        <div class="about_item_text" style="flex-grow: 1;">
                            <p style="color: #555; line-height: 1.7; font-size: 15px; margin: 0;">
                                "Transformar el aprendizaje de las matemáticas en una experiencia inmersiva y emocionante para estudiantes de quinto y sexto de primaria, así como de primero de secundaria. A través de la integración de Realidad Aumentada (AR), Realidad Virtual (VR) y una plataforma web interactiva, buscamos que los alumnos dejen de ver los números como un reto difícil y empiecen a verlos como una herramienta divertida para descubrir el mundo."
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- About Item 3: Visión -->
            <div class="col-lg-4 col-md-4 mb-5 about_col">
                <div class="about_item card h-100" style="border: none; border-radius: 20px; box-shadow: 0 10px 35px rgba(0,0,0,0.06); overflow: hidden; background-color: #ffffff; transition: transform 0.3s ease;">
                    <div class="about_item_image" style="text-align: center; background-color: #ffffff; padding-top: 20px;">
                        <img src="imagenes/img1.png" alt="Nuestra Visión" style="width: 100%; height: 220px; object-fit: contain;">
                    </div>
                    <div class="card-body d-flex flex-column" style="padding: 30px;">
                        <div class="about_item_title" style="margin-bottom: 15px;">
                            <h3 style="font-size: 22px; font-weight: 700;"><a href="#" style="text-decoration: none; color: #191c1e;">Nuestra Visión</a></h3>
                        </div>
                        <div class="about_item_text" style="flex-grow: 1;">
                            <p style="color: #555; line-height: 1.7; font-size: 15px; margin: 0;">
                                "Convertirnos en la plataforma educativa líder en innovación tecnológica para el cierre de la primaria y el inicio de la secundaria, siendo reconocidos por revolucionar la enseñanza de las matemáticas. Nuestra meta es que cada estudiante de 5º, 6º y 1º de secundaria descubra su potencial mediante un aprendizaje entretenido, dinámico y digital que los prepare con éxito para los retos académicos del futuro."
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

	<div class="feature">
        <div class="feature_background" style="background-image:url(images/courses_background.jpg)"></div>
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="section_title_container text-center">
                        <h2 class="section_title">¡Que Puedes Aprender!</h2>
                        <div class="section_subtitle"><p>¡Con NUMI, dominar las matemáticas de primaria y secundaria es un juego de niños! Aprende de forma fácil, interactiva y, sobre todo, ¡muy divertida!</p></div>
                    </div>
                </div>
            </div>
            <div class="row feature_row">

                <div class="col-lg-6 feature_col">
                    <div class="feature_content">
                        <div class="accordions">
                            
                            <div class="elements_accordions">

                                <div class="accordion_container">
                                    <div class="accordion d-flex flex-row align-items-center"><div>Enfoque en Beneficios del Estudiante</div></div>
                                    <div class="accordion_panel">
                                        <p>Potencia tu aprendizaje con desafíos a tu medida. Con NUMI, no solo estudias; resuelves retos interactivos diseñados para tu grado. Aprende matemáticas, ciencias y lógica de una forma que realmente entiendas y disfrutes.</p>
                                    </div>
                                </div>

                                <div class="accordion_container">
                                    <div class="accordion d-flex flex-row align-items-center active"><div>Enfoque en la Misión del Proyecto NUMI</div></div>
                                    <div class="accordion_panel">
                                        <p>Nuestra misión: Hacer el aprendizaje irresistible. Creemos que cada niño y adolescente tiene un potencial infinito. NUMI existe para transformar materias complejas en experiencias emocionantes que despierten tu curiosidad natural.</p>
                                    </div>
                                </div>

                                <div class="accordion_container">
                                    <div class="accordion d-flex flex-row align-items-center"><div>Mezcla Motivacional y Técnica Reconocido como el mejor apoyo escolar digital</div></div>
                                    <div class="accordion_panel">
                                        <p>Únete a miles de estudiantes que ya han mejorado sus calificaciones y su confianza gracias a la plataforma de aprendizaje más completa para estos grados críticos.</p>
                                    </div>
                                </div>

                                <div class="accordion_container">
                                    <div class="accordion d-flex flex-row align-items-center"><div>¡Que las matemáticas dejen de ser un problema!</div></div>
                                    <div class="accordion_panel">
                                        <p>En NUMI transformamos los temas difíciles de 5º, 6º y secundaria en retos que puedes superar. Descubre una forma de aprender donde tú eres el protagonista y los resultados se notan desde el primer día.</p>
                                    </div>
                                </div>

                            </div>

                        </div>
                        </div>
                </div>

                <div class="col-lg-6 feature_col">
                    <div class="feature_video d-flex flex-column align-items-center justify-content-center" style="padding: 20px;">
                        
                        <video width="100%" controls style="border-radius: 10px; box-shadow: 0px 10px 20px rgba(0,0,0,0.15);">
                            <source src="video/video_salon.mp4" type="video/mp4">
                            Tu navegador no soporta la reproducción de videos.
                        </video>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

	<style>
		/* --- SECCIÓN DESTACADA NUMI (OFFLINE) --- */
.numi-feature-section {
    display: flex;
    flex-direction: column;
    width: 100%;
    max-width: 1440px;
    margin: 60px auto; /* Espacio arriba y abajo para separar de tus otras secciones */
    background-color: #f2f4f6; /* Color de fondo suave */
    font-family: 'Inter', sans-serif;
    border-radius: 8px;
    overflow: hidden;
}

@media (min-width: 768px) {
    .numi-feature-section {
        flex-direction: row;
        min-height: 70vh;
    }
}

.numi-feature-content {
    flex: 5;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 4rem 2rem;
}

@media (min-width: 768px) {
    .numi-feature-content {
        padding: 4rem 5rem;
    }
}

.numi-feature-label {
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.2em;
    color: #43474c;
    font-weight: 600;
    margin-bottom: 1.5rem;
}

.numi-feature-title {
    font-family: 'Manrope', sans-serif;
    font-size: 3rem;
    font-weight: 800;
    color: #191c1e;
    line-height: 1.1;
    margin-bottom: 2rem;
}

@media (min-width: 1024px) {
    .numi-feature-title { font-size: 4.5rem; }
}

.numi-feature-divider {
    width: 2.5rem;
    height: 2px;
    background-color: #9fa6bf; /* Tono azul/morado de NUMI */
    margin-bottom: 2rem;
}

.numi-feature-desc {
    font-size: 1.125rem;
    color: #43474c;
    line-height: 1.6;
    margin-bottom: 3rem;
    max-width: 450px;
}

.numi-feature-buttons {
    display: flex;
    gap: 1.5rem;
    align-items: center;
    flex-wrap: wrap;
}

.numi-btn-primary {
    background: linear-gradient(to bottom right, #343c51, #1e263a);
    color: white;
    padding: 1rem 2rem;
    border: none;
    border-radius: 0.25rem;
    font-weight: 600;
    cursor: pointer;
    transition: transform 0.2s ease, opacity 0.2s;
}

.numi-btn-primary:hover { opacity: 0.9; }
.numi-btn-primary:active { transform: scale(0.95); }

.numi-btn-secondary {
    background: transparent;
    color: #1e263a;
    padding: 1rem 0.5rem;
    border: none;
    font-weight: 600;
    cursor: pointer;
    text-decoration: underline;
    text-underline-offset: 4px;
}

.numi-feature-image {
    flex: 7;
    position: relative;
    overflow: hidden;
    min-height: 300px;
}

.numi-feature-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: all 0.7s ease;
}

.numi-feature-image img:hover {
    transform: scale(1.02);
}
	</style>
	<!-- Team -->


	

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
<script src="plugins/colorbox/jquery.colorbox-min.js"></script>
<script src="js/about.js"></script>
</body>
</html>