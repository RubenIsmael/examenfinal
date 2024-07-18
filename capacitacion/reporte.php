<?php
// Incluir la conexión a la base de datos
include 'includes/db.php';

// Inicializar variables
$cursos = [];
$participantes = [];

// Consultar cursos para el ComboBox
$cursosQuery = "SELECT * FROM talleres";
$cursosStmt = $pdo->query($cursosQuery);
$cursos = $cursosStmt->fetchAll();

// Consultar participantes para el ComboBox
$participantesQuery = "SELECT * FROM participantes";
$participantesStmt = $pdo->query($participantesQuery);
$participantes = $participantesStmt->fetchAll();

// Manejar la consulta
$results = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cursoId = $_POST['curso'] ?? null;
    $participanteId = $_POST['participante'] ?? null;

    if ($cursoId) {
        // Consultar inscritos en el curso seleccionado
        $query = "
            SELECT p.nombre, p.apellido, p.email, p.telefono, i.fecha_inscripcion, t.nombre AS taller_nombre
            FROM inscripciones i
            JOIN participantes p ON i.participante_id = p.participante_id
            JOIN talleres t ON i.taller_id = t.taller_id
            WHERE i.taller_id = :cursoId
        ";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['cursoId' => $cursoId]);
        $results = $stmt->fetchAll();
    } elseif ($participanteId) {
        // Consultar talleres a los que pertenece el participante seleccionado
        $query = "
            SELECT t.nombre AS taller_nombre, t.descripcion, i.fecha_inscripcion
            FROM inscripciones i
            JOIN talleres t ON i.taller_id = t.taller_id
            WHERE i.participante_id = :participanteId
        ";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['participanteId' => $participanteId]);
        $results = $stmt->fetchAll();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Inscripciones</title>
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
            width: 80%;
            margin: 0 auto;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            margin-top: 20px;
        }
        h1 {
            text-align: center;
            color: #0056b3;
        }
        fieldset {
            border: 1px solid #0056b3;
            padding: 10px;
            margin-bottom: 15px;
        }
        legend {
            font-weight: bold;
            color: #0056b3;
        }
        label {
            margin-right: 10px;
        }
        select {
            padding: 5px;
            border: 1px solid #0056b3;
            border-radius: 4px;
        }
        button {
            background-color: #0056b3;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #003d7a;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #0056b3;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #0056b3;
            color: #fff;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .back-button {
            display: block;
            width: 200px;
            margin: 20px auto;
            background-color: #0056b3;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 4px;
            text-align: center;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
        }
        .back-button:hover {
            background-color: #003d7a;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Reporte de Inscripciones</h1>

        <!-- Formulario para seleccionar curso o participante -->
        <form method="post" action="">
            <fieldset>
                <legend>Filtrar por Curso</legend>
                <label for="curso">Curso:</label>
                <select id="curso" name="curso">
                    <option value="">Selecciona un curso</option>
                    <?php foreach ($cursos as $curso): ?>
                        <option value="<?= $curso['taller_id'] ?>"><?= htmlspecialchars($curso['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </fieldset>

            <fieldset>
                <legend>Filtrar por Participante</legend>
                <label for="participante">Participante:</label>
                <select id="participante" name="participante">
                    <option value="">Selecciona un participante</option>
                    <?php foreach ($participantes as $participante): ?>
                        <option value="<?= $participante['participante_id'] ?>"><?= htmlspecialchars($participante['nombre']) . ' ' . htmlspecialchars($participante['apellido']) ?></option>
                    <?php endforeach; ?>
                </select>
            </fieldset>

            <button type="submit">Buscar</button>
        </form>

        <!-- Mostrar resultados -->
        <?php if (!empty($results)): ?>
            <h2>Resultados</h2>
            <table>
                <thead>
                    <tr>
                        <?php if ($cursoId): ?>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Fecha de Inscripción</th>
                            <th>Taller</th>
                        <?php elseif ($participanteId): ?>
                            <th>Taller</th>
                            <th>Descripción</th>
                            <th>Fecha de Inscripción</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $row): ?>
                        <tr>
                            <?php if ($cursoId): ?>
                                <td><?= htmlspecialchars($row['nombre']) ?></td>
                                <td><?= htmlspecialchars($row['apellido']) ?></td>
                                <td><?= htmlspecialchars($row['email']) ?></td>
                                <td><?= htmlspecialchars($row['telefono']) ?></td>
                                <td><?= htmlspecialchars($row['fecha_inscripcion']) ?></td>
                                <td><?= htmlspecialchars($row['taller_nombre']) ?></td>
                            <?php elseif ($participanteId): ?>
                                <td><?= htmlspecialchars($row['taller_nombre']) ?></td>
                                <td><?= htmlspecialchars($row['descripcion']) ?></td>
                                <td><?= htmlspecialchars($row['fecha_inscripcion']) ?></td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <!-- Botón para regresar al menú -->
        <a href="menu.php" class="back-button">Regresar al Menú</a>
    </div>
</body>
</html>