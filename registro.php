<?php
session_start(); 

$host = "localhost";
$usuario_db = "root";
$password_db = "";
$nombre_db = "numi";

$conex = mysqli_connect($host, $usuario_db, $password_db, $nombre_db);

if (!$conex) {
    die("Error crítico: No se pudo conectar a la base de datos NUMI. " . mysqli_connect_error());
}
mysqli_set_charset($conex, "utf8mb4");

if (isset($_POST['register'])) {
    // Validamos que los campos básicos obligatorios no estén vacíos
    if (!empty($_POST['nombre']) && !empty($_POST['correo']) && !empty($_POST['contrasena'])) {
        
        // 1. LIMPIEZA Y SEGURIDAD DE DATOS (mysqli_real_escape_string evita Inyección SQL)
        $nombre     = mysqli_real_escape_string($conex, trim($_POST['nombre']));
        $apellido_p = mysqli_real_escape_string($conex, trim($_POST['apellido_p'] ?? ''));
        $apellido_m = mysqli_real_escape_string($conex, trim($_POST['apellido_m'] ?? ''));
        $edad       = (int)($_POST['edad'] ?? 0);
        $sexo       = mysqli_real_escape_string($conex, trim($_POST['sexo'] ?? ''));
        $correo     = mysqli_real_escape_string($conex, trim($_POST['correo']));
        $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);

        // 2. ATRAPAMOS EL ROL OCULTO DEL FORMULARIO
        $tipo_rol_recibido = $_POST['tipo_rol'] ?? 'usuario_nuevo'; 

        // ASIGNAMOS EL ID DEL ROL Y EL NOMBRE PARA LA SESIÓN
        if ($tipo_rol_recibido === 'maestro') {
            $id_rol_asignado = 2; 
            $nombre_rol_sesion = 'maestro';
        } elseif ($tipo_rol_recibido === 'tutor') {
            $id_rol_asignado = 3; 
            $nombre_rol_sesion = 'tutor';
        } else {
            $id_rol_asignado = 1; // ID 1 en BD (será alumno)
            $nombre_rol_sesion = 'usuario'; // Etiqueta para tu menú
        }

        // 3. INSERTAMOS EN LA TABLA PRINCIPAL 'usuarios'
        $consulta_user = "INSERT INTO usuarios (nombre, apellido_p, apellido_m, edad, sexo, correo, contrasena, id_rol) 
                          VALUES ('$nombre', '$apellido_p', '$apellido_m', '$edad', '$sexo', '$correo', '$contrasena', '$id_rol_asignado')";
        
        if (mysqli_query($conex, $consulta_user)) {
            
            // OBTENEMOS EL ID DEL USUARIO RECIÉN CREADO
            $nuevo_id_usuario = mysqli_insert_id($conex);
            
            // 4. ASIGNAMOS EL ROL EN LA TABLA 'usuario_rol'
            $consulta_rol = "INSERT INTO usuario_rol (id_usuario, id_rol) 
                             VALUES ('$nuevo_id_usuario', '$id_rol_asignado')";
            mysqli_query($conex, $consulta_rol);

            // 5. GUARDAR DATOS ESPECÍFICOS SEGÚN EL ROL (Opcional, si los pides en tu HTML)
            if ($tipo_rol_recibido === 'maestro') {
                $escuela = mysqli_real_escape_string($conex, $_POST['escuela_maestro'] ?? '');
                $grado   = mysqli_real_escape_string($conex, $_POST['grado_maestro'] ?? '');
                $sql_maestro = "INSERT INTO maestros (id_usuario, escuela, grado) VALUES ('$nuevo_id_usuario', '$escuela', '$grado')";
                mysqli_query($conex, $sql_maestro);
                
            } elseif ($tipo_rol_recibido === 'tutor') {
                $id_alumno = (int)($_POST['id_alumno_vinculo'] ?? 0);
                $sql_tutor = "INSERT INTO tutores (id_usuario, id_alumno) VALUES ('$nuevo_id_usuario', '$id_alumno')";
                mysqli_query($conex, $sql_tutor);
                
            } else { // Es alumno/usuario
                $escuela = mysqli_real_escape_string($conex, $_POST['escuela_alumno'] ?? '');
                $grado   = mysqli_real_escape_string($conex, $_POST['grado_alumno'] ?? '');
                $grupo   = mysqli_real_escape_string($conex, $_POST['grupo_alumno'] ?? '');
                $sql_alumno = "INSERT INTO alumnos (id_usuario, escuela, grado, grupo) VALUES ('$nuevo_id_usuario', '$escuela', '$grado', '$grupo')";
                mysqli_query($conex, $sql_alumno);
            }

            // 6. CREAMOS LA SESIÓN DE LOGIN AUTOMÁTICO
            $_SESSION['usuario_id']        = $nuevo_id_usuario;
            $_SESSION['usuario_nombre']    = $nombre;
            $_SESSION['rol']               = $nombre_rol_sesion; 
            $_SESSION['id_rol']            = $id_rol_asignado;   
            $_SESSION['sesion_iniciada']   = true;
            $_SESSION['registro_completo'] = true;

            // 7. REDIRIGIMOS AL USUARIO DEPENDIENDO DE SU ROL
            if ($nombre_rol_sesion === 'maestro' || $nombre_rol_sesion === 'admin') {
                header("Location: paneladmin.php"); 
            } else {
                header("Location: index.php"); 
            }
            exit();
            
        } else {
            // Si algo falla al crear el usuario en la BD principal
            echo "<h3 class='bad'>Error al crear usuario: " . mysqli_error($conex) . "</h3>";
        }
    } else {
        // Si dejaron campos obligatorios vacíos
        echo "<h3 class='bad'>Por favor, completa todos los campos requeridos (Nombre, correo y contraseña).</h3>";
    }
}
?>