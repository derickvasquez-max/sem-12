<?php
require_once 'conexion.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: index.php');
    exit;
}

$stmt = $conexion->prepare("SELECT * FROM empleados WHERE ID = :id");
$stmt->execute([':id' => $id]);
$empleado = $stmt->fetch();

if (!$empleado) {
    header('Location: index.php');
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre    = trim($_POST['nombre'] ?? '');
    $apellidoP = trim($_POST['apellidoP'] ?? '');
    $apellidoM = trim($_POST['apellidoM'] ?? '');
    $correo    = trim($_POST['correo'] ?? '');
    $fotoNom   = trim($_POST['foto'] ?? '') ?: 'default.jpg';

    if (isset($_FILES['archivo_foto']) && $_FILES['archivo_foto']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['archivo_foto']['name'], PATHINFO_EXTENSION));
        $permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (in_array($ext, $permitidas)) {
            if (!is_dir('imagenes')) {
                mkdir('imagenes', 0777, true);
            }
            $nuevoNombre = time() . '_' . uniqid() . '.' . $ext;
            if (move_uploaded_file($_FILES['archivo_foto']['tmp_name'], 'imagenes/' . $nuevoNombre)) {
                // Borrar foto anterior si no es default.jpg
                if (!empty($empleado['Foto']) && $empleado['Foto'] !== 'default.jpg' && file_exists('imagenes/' . $empleado['Foto'])) {
                    @unlink('imagenes/' . $empleado['Foto']);
                }
                $fotoNom = $nuevoNombre;
            }
        } else {
            $error = "Formato de imagen no válido.";
        }
    }

    if (!$error) {
        try {
            $sql = "UPDATE empleados
                    SET Nombre = :nombre,
                        ApellidoP = :apellidop,
                        ApellidoM = :apellidom,
                        Correo = :correo,
                        Foto = :foto
                    WHERE ID = :id";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([
                ':nombre'    => $nombre,
                ':apellidop' => $apellidoP,
                ':apellidom' => $apellidoM !== '' ? $apellidoM : null,
                ':correo'    => $correo,
                ':foto'      => $fotoNom,
                ':id'        => $id
            ]);
            header('Location: index.php');
            exit;
        } catch (PDOException $e) {
            $error = "Error al actualizar: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Empleado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">
    <div class="container bg-white p-4 rounded shadow-sm" style="max-width: 500px;">
        <h3 class="mb-3 fw-bold">Editar Empleado #<?php echo htmlspecialchars($empleado['ID']); ?></h3>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form action="editar.php?id=<?php echo htmlspecialchars($empleado['ID']); ?>" method="POST" enctype="multipart/form-data">
            <div class="mb-2">
                <label class="form-label">Nombre:</label>
                <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($empleado['Nombre']); ?>" required>
            </div>
            <div class="mb-2">
                <label class="form-label">Apellido Paterno:</label>
                <input type="text" name="apellidoP" class="form-control" value="<?php echo htmlspecialchars($empleado['ApellidoP']); ?>" required>
            </div>
            <div class="mb-2">
                <label class="form-label">Apellido Materno:</label>
                <input type="text" name="apellidoM" class="form-control" value="<?php echo htmlspecialchars($empleado['ApellidoM'] ?? ''); ?>">
            </div>
            <div class="mb-2">
                <label class="form-label">Correo Electrónico:</label>
                <input type="email" name="correo" class="form-control" value="<?php echo htmlspecialchars($empleado['Correo']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Reemplazar Foto (opcional):</label>
                <input type="file" name="archivo_foto" class="form-control mb-2" accept="image/*">
                <input type="text" name="foto" class="form-control" value="<?php echo htmlspecialchars($empleado['Foto'] ?? ''); ?>">
            </div>
            <button type="submit" class="btn btn-warning w-100 fw-semibold">Actualizar Registro</button>
            <a href="index.php" class="btn btn-secondary w-100 mt-2 fw-semibold">Cancelar</a>
        </form>
    </div>
</body>
</html>
