<?php
require_once 'conexion.php';

$id = $_GET['id'] ?? null;

if ($id) {
    // 1. Obtener el nombre de la foto para eliminar el archivo físico del servidor
    $stmtFoto = $conexion->prepare("SELECT Foto FROM empleados WHERE ID = :id");
    $stmtFoto->execute([':id' => $id]);
    $emp = $stmtFoto->fetch();

    if ($emp && !empty($emp['Foto']) && $emp['Foto'] !== 'default.jpg') {
        $rutaFoto = 'imagenes/' . $emp['Foto'];
        if (file_exists($rutaFoto)) {
            @unlink($rutaFoto);
        }
    }

    // 2. Eliminar el registro de la base de datos
    $sql = "DELETE FROM empleados WHERE ID = :id";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([':id' => $id]);
}

header('Location: index.php');
exit;
?>
