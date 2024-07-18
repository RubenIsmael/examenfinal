<?php
require 'includes/db.php';

// Obtener todas las inscripciones con los nombres de los participantes y talleres
$stmt = $pdo->prepare("
    SELECT inscripciones.inscripcion_id, participantes.nombre AS participante_nombre, participantes.apellido AS participante_apellido, talleres.nombre AS taller_nombre, inscripciones.fecha_inscripcion
    FROM inscripciones
    INNER JOIN participantes ON inscripciones.participante_id = participantes.participante_id
    INNER JOIN talleres ON inscripciones.taller_id = talleres.taller_id
");
$stmt->execute();
$inscripciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener todos los participantes para el combo box
$stmt = $pdo->prepare("SELECT participante_id, nombre, apellido FROM participantes");
$stmt->execute();
$participantes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener todos los talleres para el combo box
$stmt = $pdo->prepare("SELECT taller_id, nombre FROM talleres");
$stmt->execute();
$talleres = $stmt->fetchAll(PDO::FETCH_ASSOC);

$update = false;
$id = 0;
$participante_id = '';
$taller_id = '';
$fecha_inscripcion = '';

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $update = true;
    $stmt = $pdo->prepare("SELECT * FROM inscripciones WHERE inscripcion_id = ?");
    $stmt->execute([$id]);
    $inscripcion = $stmt->fetch(PDO::FETCH_ASSOC);
    $participante_id = $inscripcion['participante_id'];
    $taller_id = $inscripcion['taller_id'];
    $fecha_inscripcion = $inscripcion['fecha_inscripcion'];
}

if (isset($_POST['insert'])) {
    $participante_id = $_POST['participante_id'];
    $taller_id = $_POST['taller_id'];
    $fecha_inscripcion = $_POST['fecha_inscripcion'];
    $stmt = $pdo->prepare("INSERT INTO inscripciones (participante_id, taller_id, fecha_inscripcion) VALUES (?, ?, ?)");
    $stmt->execute([$participante_id, $taller_id, $fecha_inscripcion]);
    header('Location: incripciones.php');
}

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $participante_id = $_POST['participante_id'];
    $taller_id = $_POST['taller_id'];
    $fecha_inscripcion = $_POST['fecha_inscripcion'];
    $stmt = $pdo->prepare("UPDATE inscripciones SET participante_id = ?, taller_id = ?, fecha_inscripcion = ? WHERE inscripcion_id = ?");
    $stmt->execute([$participante_id, $taller_id, $fecha_inscripcion, $id]);
    header('Location: incripciones.php');
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM inscripciones WHERE inscripcion_id = ?");
    $stmt->execute([$id]);
    header('Location: incripciones.php');
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Inscripciones</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="./public/css/bootstrap.min.css">
    <link rel="stylesheet" href="./public/css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('imagenes/fondo2.jpeg');
            background-size: cover;
            background-position: center;
            margin: 0;
            padding: 20px;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        select, input[type="date"] {
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            padding: 10px;
            border: none;
            border-radius: 4px;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .action-buttons {
            display: flex;
            gap: 10px;
        }
        .action-buttons a {
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 4px;
            color: #fff;
        }
        .action-buttons .edit {
            background-color: #ffc107;
        }
        .action-buttons .delete {
            background-color: #dc3545;
        }
        .menu-button {
            display: block;
            margin-bottom: 20px;
            background-color: #28a745;
            color: white;
            text-align: center;
            padding: 10px;
            text-decoration: none;
            border-radius: 4px;
        }
        .menu-button:hover {
            background-color: #218838;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Gestión de Inscripciones</h1>
        <a href="menu.php" class="menu-button">Regresar al Menú</a>
        <form action="incripciones.php" method="POST">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
            <select name="participante_id" required>
                <option value="">Seleccione un participante</option>
                <?php foreach ($participantes as $participante): ?>
                    <option value="<?php echo htmlspecialchars($participante['participante_id']); ?>" <?php if ($participante['participante_id'] == $participante_id) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($participante['nombre'] . ' ' . $participante['apellido']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <select name="taller_id" required>
                <option value="">Seleccione un taller</option>
                <?php foreach ($talleres as $taller): ?>
                    <option value="<?php echo htmlspecialchars($taller['taller_id']); ?>" <?php if ($taller['taller_id'] == $taller_id) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($taller['nombre']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="date" name="fecha_inscripcion" value="<?php echo htmlspecialchars($fecha_inscripcion); ?>" required>
            <?php if ($update): ?>
                <button type="submit" name="update">Actualizar</button>
            <?php else: ?>
                <button type="submit" name="insert">Insertar</button>
            <?php endif; ?>
        </form>
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Taller</th>
                    <th>Fecha de Inscripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($inscripciones as $inscripcion): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($inscripcion['participante_nombre']); ?></td>
                        <td><?php echo htmlspecialchars($inscripcion['participante_apellido']); ?></td>
                        <td><?php echo htmlspecialchars($inscripcion['taller_nombre']); ?></td>
                        <td><?php echo htmlspecialchars($inscripcion['fecha_inscripcion']); ?></td>
                        <td>
                            <div class="action-buttons">
                                <a href="incripciones.php?edit=<?php echo $inscripcion['inscripcion_id']; ?>" class="edit">Editar</a>
                                <a href="incripciones.php?delete=<?php echo $inscripcion['inscripcion_id']; ?>" class="delete" onclick="return confirm('¿Estás seguro de que quieres eliminar esta inscripción?');">Borrar</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
