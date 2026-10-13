<?php
// conexion.php

$servername = "localhost";
$username = "root";
$password = ""; // En XAMPP (Windows) va vacío "". Si usas MAMP (Mac) cámbialo a "root".
$dbname = "numi"; // El nombre de tu base de datos en phpMyAdmin

// Crear la conexión con la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);

// Habilitar caracteres especiales como acentos y la Ñ
$conn->set_charset("utf8mb4");

// Comprobar si la conexión falló
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>