<?php
session_start();

// Validamos si el alumno ya inició sesión tras el paso anterior
if (!isset($_SESSION['sesion_iniciada']) || $_SESSION['sesion_iniciada'] !== true) {
    // Si intentan entrar a esta pantalla sin registrarse antes, los mandamos al index
    header("Location: index.php");
    exit();
}

// Obtenemos el nombre que guardó tu primer formulario (ej. Axel o Regina)
$nombre_alumno = isset($_SESSION['usuario_nombre']) ? $_SESSION['usuario_nombre'] : "Estudiante";
?>
<!DOCTYPE html>
<html lang="es">
<head>
<title>Registro Estudiante | NUMI</title>
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

    .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }

    body {
        background-color: #f7f9fb;
        font-family: 'Inter', sans-serif;
    }

    /* --- ESTILOS DEL FORMULARIO EDITORIAL --- */
    .editorial-gradient {
        background: linear-gradient(135deg, #EEB4C5 0%, #A188DD 100%);
        color: white;
    }
    .form-container-box {
        background-color: #ffffff;
        border-radius: 5px;
        box-shadow: 0 25px 30px -12px rgba(0,0,0,0.15);
        overflow: hidden;
        border: 1px solid rgba(196, 198, 205, 0.2);
    }
    .form-editorial-input {
        width: 100%;
        border: none;
        border-bottom: 2px solid #e0e3e5;
        padding: 12px 0;
        background: transparent;
        outline: none;
        transition: border-color 0.3s ease;
        color: #191c1e;
        font-family: 'Inter', sans-serif;
    }
    .form-editorial-input:focus {
        border-bottom-color: #1e263a;
    }
    .form-editorial-label {
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        color: #000000;
        font-weight: 700;
        margin-bottom: 4px;
        display: block;
    }
    .editorial-btn {
        background: linear-gradient(135deg, #1e263a 0%, #343c51 100%);
        color: white;
        padding: 16px 40px;
        border: none;
        border-radius: 8px;
        font-family: 'Manrope', sans-serif;
        font-weight: 700;
        font-size: 0.875rem;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        cursor: pointer;
    }
    .editorial-btn:hover {
        opacity: 0.9;
        transform: translateY(-2px);
        color: white;
    }
    .editorial-btn .material-symbols-outlined {
        transition: transform 0.3s ease;
    }
    .editorial-btn:hover .material-symbols-outlined {
        transform: translateX(4px);
    }

    /* --- ESTILO PARA LA FLECHA DE VOLVER --- */
    .back-arrow-link {
        color: #64748b;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
    }
    .back-arrow-link:hover {
        color: #1e263a;
        transform: translateX(-4px);
        text-decoration: none;
    }
</style>
</head>
<body>

<div class="super_container">

    <header class="header">
        <div class="header_container" style="background-color: #A188DD; border-bottom: 1px solid #e2e8f0;">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="header_content d-flex flex-row align-items-center justify-content-center">
                            <div style="background-color: #ffffff00; padding: 10px; border-radius: 8px; display: inline-block;">
                                <div class="logo_container">
                                    <a href="index.php">
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

    <div class="container d-flex align-items-center justify-content-center" style="min-height: calc(100vh - 200px); padding-top: 40px; padding-bottom: 80px;">
        <div class="row w-100 justify-content-center align-items-stretch">
            
            <div class="col-12 col-lg-5 mb-4 mb-lg-0">
                <div class="form-container-box p-5 h-100">
                    <div class="mb-5">
                        <h3 style="font-family: 'Manrope', sans-serif; font-size: 1.75rem; font-weight: 800; color: #191c1e;">
                            ¡Hola, <?php echo htmlspecialchars($nombre_alumno); ?>!
                        </h3>
                        <p style="color: #64748b; font-size: 0.875rem;">Completa tus datos escolares para personalizar tu cuenta de Alumno.</p>
                    </div>

                    <form action="registro_universal.php" method="POST">
                        <input type="hidden" name="usuario_id" value="<?php echo $_SESSION['usuario_id']; ?>">
                        <input type="hidden" name="tipo_rol" value="alumno"> 

                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <label class="form-editorial-label" for="escuela">Escuela</label>
                                <input type="text" class="form-editorial-input" id="escuela" name="escuela" placeholder="Nombre de tu institución" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-editorial-label" for="grado">Grado</label>
                                <select class="form-editorial-input" id="grado" name="grado" style="appearance: auto; padding-right: 20px;" required>
                                    <option value="" disabled selected>Seleccionar grado...</option>
                                    <option value="5to Primaria">5to de Primaria</option>
                                    <option value="1ro Secundaria">1ro de Secundaria</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-editorial-label" for="grupo">Grupo</label>
                                <input type="text" class="form-editorial-input" id="grupo" name="grupo" placeholder="Ej. A, B, C" required>
                            </div>
                        </div>

                        <div class="mt-4 pt-2">
                            <button type="submit" class="editorial-btn">
                                Comenzar a Aprender
                                <span class="material-symbols-outlined">arrow_forward</span>
                            </button>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-5 pt-3" style="border-top: 1px solid #e2e8f0; font-size: 0.7rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 1px;">
                            <a href="index.php" class="back-arrow-link" title="Volver al Inicio">
                                <span class="material-symbols-outlined" style="font-size: 1.5rem;">arrow_back</span>
                            </a>
                            <a href="login_alumno.php" style="color: #64748b; text-decoration: none;">Acceso Alumno Existente</a>
                        </div>

                    </form>
                </div>
            </div>

            <div class="col-12 col-lg-5">
                <div class="form-container-box p-5 h-100 editorial-gradient d-flex flex-column justify-content-center" style="border: none;">
                    <span style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.15em; color: #000000; font-weight: 700;">Portal de Estudiantes</span>
                    <h3 style="font-family: 'Manrope', sans-serif; font-size: 2rem; font-weight: 800; color: #ffffff; line-height: 1.2; margin-top: 10px; margin-bottom: 20px;">
                        Explora y<br>Aprende Jugando.
                    </h3>
                    <div style="width: 50px; height: 2px; background-color: #000000; opacity: 0.3; margin-bottom: 30px;"></div>
                    <p style="color: #000000; font-size: 0.95rem; line-height: 1.8; margin-bottom: 30px;">
                        Al crear tu cuenta de estudiante, tendrás acceso a todos tus cursos, retos y herramientas interactivas. Transforma materias complejas en experiencias emocionantes que despierten tu curiosidad natural.
                    </p>
                    <div class="d-flex align-items-center" style="gap: 10px; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.15em; color: #ffffff; font-weight: 700;">
                        <span class="material-symbols-outlined" style="font-size: 1.5rem;">school</span>
                        <span>Acceso a tu material educativo</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

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