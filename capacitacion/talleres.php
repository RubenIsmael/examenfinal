<?php
require 'includes/db.php';

// Inicializar variables
$id = $nombre = $descripcion = $fecha = $ubicacion = '';
$update = false;

// Insertar taller
if (isset($_POST['insert'])) {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $fecha = $_POST['fecha'];
    $ubicacion = $_POST['ubicacion'];
    
    // Verificar si el nombre del taller ya existe
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM talleres WHERE nombre = ?");
    $stmt->execute([$nombre]);
    $count = $stmt->fetchColumn();
    
    if ($count > 0) {
        echo "<script>alert('El nombre del taller ya está en uso.'); window.location.href = 'talleres.php';</script>";
    } else {
        $stmt = $pdo->prepare("INSERT INTO talleres (nombre, descripcion, fecha, ubicacion) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nombre, $descripcion, $fecha, $ubicacion]);
        header("Location: talleres.php");
    }
}


// Editar taller
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $update = true;
    $stmt = $pdo->prepare("SELECT * FROM talleres WHERE taller_id = ?");
    $stmt->execute([$id]);
    $taller = $stmt->fetch(PDO::FETCH_ASSOC);
    $nombre = $taller['nombre'];
    $descripcion = $taller['descripcion'];
    $fecha = $taller['fecha'];
    $ubicacion = $taller['ubicacion'];
}

// Actualizar taller
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $fecha = $_POST['fecha'];
    $ubicacion = $_POST['ubicacion'];

    $stmt = $pdo->prepare("UPDATE talleres SET nombre = ?, descripcion = ?, fecha = ?, ubicacion = ? WHERE taller_id = ?");
    $stmt->execute([$nombre, $descripcion, $fecha, $ubicacion, $id]);
    header("Location: talleres.php");
}

// Borrar taller
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    
    // Verificar si el taller tiene inscripciones
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM inscripciones WHERE taller_id = ?");
    $stmt->execute([$id]);
    $count = $stmt->fetchColumn();
    
    if ($count > 0) {
        echo "<script>alert('No se puede eliminar el taller porque tiene inscripciones asociadas.'); window.location.href = 'talleres.php';</script>";
    } else {
        $stmt = $pdo->prepare("DELETE FROM talleres WHERE taller_id = ?");
        $stmt->execute([$id]);
        header("Location: talleres.php");
    }
}


// Obtener todos los talleres
$stmt = $pdo->prepare("SELECT * FROM talleres");
$stmt->execute();
$talleres = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Talleres</title>
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
        input[type="text"], input[type="date"], textarea {
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
        <h1>Gestión de Talleres</h1>
        <a href="menu.php" class="menu-button">Regresar al Menú</a>
        <form action="talleres.php" method="POST">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
            <input type="text" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>" placeholder="Nombre del taller" required>
            <textarea name="descripcion" placeholder="Descripción del taller" required><?php echo htmlspecialchars($descripcion); ?></textarea>
            <input type="date" name="fecha" value="<?php echo htmlspecialchars($fecha); ?>" required>
            <input type="text" name="ubicacion" value="<?php echo htmlspecialchars($ubicacion); ?>" placeholder="Ubicación del taller" required>
            <?php if ($update): ?>
                <button type="submit" name="update">Actualizar</button>
            <?php else: ?>
                <button type="submit" name="insert">Insertar</button>
            <?php endif; ?>
        </form>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Fecha</th>
                    <th>Ubicación</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($talleres as $taller): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($taller['taller_id']); ?></td>
                        <td><?php echo htmlspecialchars($taller['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($taller['descripcion']); ?></td>
                        <td><?php echo htmlspecialchars($taller['fecha']); ?></td>
                        <td><?php echo htmlspecialchars($taller['ubicacion']); ?></td>
                        <td>
                            <div class="action-buttons">
                                <a href="talleres.php?edit=<?php echo $taller['taller_id']; ?>" class="edit">Editar</a>
                                <a href="talleres.php?delete=<?php echo $taller['taller_id']; ?>" class="delete" onclick="return confirm('¿Estás seguro de que quieres eliminar este taller?');">Borrar</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
