<?php
require_once 'conexion.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre    = trim($_POST['nombre'] ?? '');
    $apellidoP = trim($_POST['apellidop'] ?? '');
    $apellidoM = trim($_POST['apellidoM'] ?? '');
    $correo    = trim($_POST['correo'] ?? '');
    $fotoNom   = trim($_POST['foto'] ?? '') ?: 'default.jpg';

    // Procesar subida de foto si se eligió archivo
    if (isset($_FILES['archivo_foto']) && $_FILES['archivo_foto']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['archivo_foto']['name'], PATHINFO_EXTENSION));
        $permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (in_array($ext, $permitidas)) {
            if (!is_dir('imagenes')) {
                mkdir('imagenes', 0777, true);
            }
            $nuevoNombre = time() . '_' . uniqid() . '.' . $ext;
            if (move_uploaded_file($_FILES['archivo_foto']['tmp_name'], 'imagenes/' . $nuevoNombre)) {
                $fotoNom = $nuevoNombre;
            }
        } else {
            $error = "Formato de imagen no válido. Usa JPG, PNG, GIF o WEBP.";
        }
    }

    if (!$error) {
        try {
            $sql = "INSERT INTO empleados (Nombre, ApellidoP, ApellidoM, Correo, Foto)
                    VALUES (:nombre, :apellidop, :apellidom, :correo, :foto)";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([
                ':nombre'    => $nombre,
                ':apellidop' => $apellidoP,
                ':apellidom' => $apellidoM !== '' ? $apellidoM : null,
                ':correo'    => $correo,
                ':foto'      => $fotoNom
            ]);
            header('Location: index.php');
            exit;
        } catch (PDOException $e) {
            $error = "Error al guardar el registro: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Empleado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">
    <div class="container bg-white p-4 rounded shadow-sm" style="max-width: 500px;">
        <h3 class="mb-3 fw-bold">Nuevo Empleado</h3>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form action="agregar.php" method="POST" enctype="multipart/form-data">
            <div class="mb-2">
                <label class="form-label">Nombre:</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>
            <div class="mb-2">
                <label class="form-label">Apellido Paterno:</label>
                <input type="text" name="apellidop" class="form-control" required>
            </div>
            <div class="mb-2">
                <label class="form-label">Apellido Materno:</label>
                <input type="text" name="apellidoM" class="form-control">
            </div>
            <div class="mb-2">
                <label class="form-label">Correo Electrónico:</label>
                <input type="email" name="correo" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Foto de Perfil (subir archivo o indicar nombre):</label>
                <input type="file" name="archivo_foto" class="form-control mb-2" accept="image/*">
                <input type="text" name="foto" class="form-control" value="default.jpg" placeholder="ej. foto1.png">
            </div>
            <button type="submit" class="btn btn-primary w-100 fw-semibold">Guardar Empleado</button>
            <a href="index.php" class="btn btn-secondary w-100 mt-2 fw-semibold">Cancelar</a>
        </form>
    </div>
</body>
</html>
