<?php
require_once 'conexion.php';

$sql = "SELECT * FROM empleados ORDER BY ID ASC";
$stmt = $conexion->query($sql);
$empleados = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Módulo de Empleados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light p-4">
    <div class="container bg-white p-4 rounded shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="fw-bold text-dark mb-0">Lista de Empleados</h2>
            <a href="agregar.php" class="btn btn-success fw-bold">
                <i class="bi bi-plus-lg me-1"></i> Nuevo Empleado
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle mb-0">
                <thead class="table-dark text-center">
                    <tr>
                        <th style="width: 70px;">ID</th>
                        <th>Nombre Completo</th>
                        <th>Correo</th>
                        <th style="width: 220px;">Foto</th>
                        <th style="width: 190px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($empleados) > 0): ?>
                        <?php foreach ($empleados as $emp): ?>
                            <?php
                                $nombreCompleto = trim($emp['Nombre'] . ' ' . $emp['ApellidoP'] . ' ' . ($emp['ApellidoM'] ?? ''));
                                $fotoNombre = !empty($emp['Foto']) ? $emp['Foto'] : 'default.jpg';
                                $rutaFoto = 'imagenes/' . $fotoNombre;
                                $imgSrc = file_exists($rutaFoto) ? $rutaFoto : 'imagenes/default.jpg';
                            ?>
                            <tr>
                                <td class="text-center fw-bold"><?php echo htmlspecialchars($emp['ID']); ?></td>
                                <td><?php echo htmlspecialchars($nombreCompleto); ?></td>
                                <td><?php echo htmlspecialchars($emp['Correo']); ?></td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        <img src="<?php echo htmlspecialchars($imgSrc); ?>" alt="Foto" class="rounded-circle border" style="width: 42px; height: 42px; object-fit: cover;">
                                        <span class="badge bg-secondary text-truncate" style="max-width: 130px;" title="<?php echo htmlspecialchars($fotoNombre); ?>">
                                            <?php echo htmlspecialchars($fotoNombre); ?>
                                        </span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <a href="editar.php?id=<?php echo $emp['ID']; ?>" class="btn btn-warning btn-sm fw-semibold">Editar</a>
                                    <a href="eliminar.php?id=<?php echo $emp['ID']; ?>" class="btn btn-danger btn-sm fw-semibold" onclick="return confirm('¿Deseas eliminar este registro?');">Eliminar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No hay empleados registrados.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
