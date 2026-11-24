<?php
$host = "localhost";
$db   = "empresa";
$user = "root";
$pass = "12345678";   // en WAMP el root normalmente no tiene contraseña

try {
    $dsn = "mysql:host={$host};dbname={$db};charset=utf8mb4";
    $conexion = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false
    ]);
} catch (PDOException $e) {
    die("Error de conexión PDO: " . htmlspecialchars($e->getMessage()));
}
?>