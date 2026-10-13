<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

$servername = "localhost";
$username = "root"; 
$password = "";     
$dbname = "numi"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Conexión BD fallida: " . $conn->connect_error]));
}

$id_alumno = isset($_POST['id_alumno']) ? intval($_POST['id_alumno']) : 0;
$id_leccion = isset($_POST['id_leccion']) ? intval($_POST['id_leccion']) : 0;
$porcentaje = isset($_POST['num_intentos']) ? intval($_POST['num_intentos']) : 0;
$tiempo_usado = isset($_POST['tiempo_usado']) ? intval($_POST['tiempo_usado']) : 0;
$intentos_fallidos = isset($_POST['intentos_fallidos']) ? intval($_POST['intentos_fallidos']) : 0;

$duracion_minutos = round($tiempo_usado / 60);
if ($duracion_minutos < 1 && $tiempo_usado > 0) {
    $duracion_minutos = 1;
}

if ($id_alumno <= 0 || $id_leccion <= 0) {
    die(json_encode([
        "status" => "error", 
        "message" => "ID Inválido -> Alumno: " . $id_alumno . " | Lección: " . $id_leccion
    ]));
}

$id_maestro_valido = 1; 
$sql_maestro = "SELECT id_maestro FROM maestros LIMIT 1";
$res_maestro = $conn->query($sql_maestro);
if ($res_maestro && $row_m = $res_maestro->fetch_assoc()) {
    $id_maestro_valido = intval($row_m['id_maestro']);
}

$sql_check = "SELECT id_progreso FROM progresos WHERE id_alumno = ? AND id_leccion = ? LIMIT 1";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("ii", $id_alumno, $id_leccion);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check && $result_check->num_rows > 0) {
    // Si ya existe registro, actualiza
    $sql_action = "UPDATE progresos SET num_intentos = ?, duracion_minutos = ?, fecha = NOW() WHERE id_alumno = ? AND id_leccion = ?";
    $stmt_action = $conn->prepare($sql_action);
    $stmt_action->bind_param("iiii", $porcentaje, $duracion_minutos, $id_alumno, $id_leccion);
} else {
    // Si es registro nuevo, inserta (Corregido a "iiiii" para 5 variables)
    $sql_action = "INSERT INTO progresos (id_alumno, id_maestro, id_leccion, id_tipo, duracion_minutos, num_intentos, fecha) 
                   VALUES (?, ?, ?, 1, ?, ?, NOW())";
    $stmt_action = $conn->prepare($sql_action);
    $stmt_action->bind_param("iiiii", $id_alumno, $id_maestro_valido, $id_leccion, $duracion_minutos, $porcentaje);
}

if ($stmt_action->execute()) {
    echo json_encode([
        "status" => "success", 
        "message" => "Progreso guardado correctamente.",
        "id_alumno" => $id_alumno,
        "leccion" => $id_leccion,
        "porcentaje" => $porcentaje
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "Error de MySQL: " . $stmt_action->error]);
}

$stmt_check->close();
$stmt_action->close();
$conn->close();
?>