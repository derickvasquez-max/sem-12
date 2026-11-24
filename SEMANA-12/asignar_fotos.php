<?php
// Ejecutar UNA vez: http://localhost/SEMANA-12/asignar_fotos.php
require 'conexion.php';

$fotos = array_map('basename', glob(__DIR__ . '/imagenes/*.jpg'));
$fotos = array_values(array_filter($fotos, fn($f) => $f !== 'default.jpg'));
sort($fotos);

$ids = $conexion->query("SELECT ID FROM empleados ORDER BY ID")->fetchAll(PDO::FETCH_COLUMN);

$stmt = $conexion->prepare("UPDATE empleados SET Foto = :foto WHERE ID = :id");
foreach ($ids as $i => $id) {
    if (isset($fotos[$i])) {
        $stmt->execute([':foto' => $fotos[$i], ':id' => $id]);
    }
}

header('Location: index.php');
exit;
