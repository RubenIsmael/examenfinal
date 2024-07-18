<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Menú Principal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: url('imagenes/fondo.jpg') no-repeat center center fixed;
            background-size: cover;
        }
        .menu-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
            text-align: center;
        }
        .menu-title {
            font-size: 2em;
            color: black;
            margin-bottom: 20px;
        }
        .menu {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }
        .menu button {
            background-color: #007bff;
            border: none;
            border-radius: 10px;
            padding: 15px 20px;
            margin: 10px;
            cursor: pointer;
            display: flex;
            align-items: center;
            color: white;
            font-size: 1em;
            transition: background-color 0.3s, transform 0.3s;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            opacity: 0;
            transform: translateY(20px);
        }
        .menu button img {
            margin-right: 10px;
            width: 30px;
            height: 30px;
        }
        .menu button:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }
        .menu button:active {
            transform: translateY(2px);
        }
    </style>
</head>
<body>

<div class="menu-container">
    <div class="menu-title">Menú Principal</div>
    <div class="menu">
        <button onclick="window.location.href='talleres.php'">
            <img src="imagenes/talleres.png" alt="Talleres">
            Talleres
        </button>
        <button onclick="window.location.href='participantes.php'">
            <img src="imagenes/participantes.png" alt="Participantes">
            Participantes
        </button>
        <button onclick="window.location.href='incripciones.php'">
            <img src="imagenes/inscripcion.png" alt="Inscripciones">
            Inscripciones
        </button>
        <!-- Nuevo botón para Reportes -->
        <button onclick="window.location.href='reporte.php'">
            <img src="imagenes/reporte.png" alt="Reportes">
            Reportes
        </button>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Mensaje de bienvenida
        alert("Bienvenido al Menú Principal");

        // Animaciones de entrada para los botones
        const buttons = document.querySelectorAll('.menu button');
        buttons.forEach((button, index) => {
            setTimeout(() => {
                button.style.opacity = 1;
                button.style.transform = 'translateY(0)';
            }, index * 100);
        });

        // Manejar eventos de clic adicionales
        buttons.forEach(button => {
            button.addEventListener('click', function() {
                console.log(`Botón ${button.textContent.trim()} clickeado`);
            });
        });

        // Asignar eventos de mouseover y mouseout a los botones
        buttons.forEach(button => {
            button.addEventListener('mouseover', () => changeContainerColor('#f0f0f0'));
            button.addEventListener('mouseout', () => changeContainerColor('white'));
        });
    });

    // Función para cambiar el color de fondo del contenedor del menú al pasar el cursor sobre los botones
    function changeContainerColor(color) {
        document.querySelector('.menu-container').style.backgroundColor = color;
    }
</script>

</body>
</html>
