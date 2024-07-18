<?php
require 'includes/db.php';

// Inicializar variables
$id = $nombre = $apellido = $email = $telefono = '';
$update = false;

// Insertar participante
if (isset($_POST['insert'])) {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];

    // Verificar si ya existe un participante con el mismo nombre y apellido
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM participantes WHERE nombre = ? AND apellido = ?");
    $stmt->execute([$nombre, $apellido]);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        echo "<script>alert('Ya existe un participante con el mismo nombre y apellido.');</script>";
    } else {
        $stmt = $pdo->prepare("INSERT INTO participantes (nombre, apellido, email, telefono) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nombre, $apellido, $email, $telefono]);
        header("Location: participantes.php");
    }
}


// Editar participante
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $update = true;
    $stmt = $pdo->prepare("SELECT * FROM participantes WHERE participante_id = ?");
    $stmt->execute([$id]);
    $participante = $stmt->fetch(PDO::FETCH_ASSOC);
    $nombre = $participante['nombre'];
    $apellido = $participante['apellido'];
    $email = $participante['email'];
    $telefono = $participante['telefono'];
}

// Actualizar participante
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];

    $stmt = $pdo->prepare("UPDATE participantes SET nombre = ?, apellido = ?, email = ?, telefono = ? WHERE participante_id = ?");
    $stmt->execute([$nombre, $apellido, $email, $telefono, $id]);
    header("Location: participantes.php");
}

// Borrar participante
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    // Verificar si el participante tiene inscripciones asociadas
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM inscripciones WHERE participante_id = ?");
    $stmt->execute([$id]);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        echo "<script>alert('No se puede eliminar este participante porque tiene inscripciones asociadas.');</script>";
    } else {
        $stmt = $pdo->prepare("DELETE FROM participantes WHERE participante_id = ?");
        $stmt->execute([$id]);
        header("Location: participantes.php");
    }
}


// Obtener todos los participantes
$stmt = $pdo->prepare("SELECT * FROM participantes");
$stmt->execute();
$participantes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Participantes</title>
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
        input[type="text"], input[type="email"] {
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        input[type="date"], input[type="tel"] {
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
        <h1>Gestión de Participantes</h1>
        <a href="menu.php" class="menu-button">Regresar al Menú</a>
        <form action="participantes.php" method="POST">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
            <input type="text" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>" placeholder="Nombre del participante" required>
            <input type="text" name="apellido" value="<?php echo htmlspecialchars($apellido); ?>" placeholder="Apellido del participante" required>
            <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" placeholder="Correo electrónico" required>
            <input type="tel" name="telefono" value="<?php echo htmlspecialchars($telefono); ?>" placeholder="Teléfono" required>
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
                    <th>Apellido</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($participantes as $participante): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($participante['participante_id']); ?></td>
                        <td><?php echo htmlspecialchars($participante['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($participante['apellido']); ?></td>
                        <td><?php echo htmlspecialchars($participante['email']); ?></td>
                        <td><?php echo htmlspecialchars($participante['telefono']); ?></td>
                        <td>
                            <div class="action-buttons">
                                <a href="participantes.php?edit=<?php echo $participante['participante_id']; ?>" class="edit">Editar</a>
                                <a href="participantes.php?delete=<?php echo $participante['participante_id']; ?>" class="delete" onclick="return confirm('¿Estás seguro de que quieres eliminar este participante?');">Borrar</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>