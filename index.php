<?php
// Conexión a la base de datos
$conexion = new mysqli('localhost', 'root', '', 'formulario');

if ($conexion->connect_error) {
    die('Error de conexión: ' . $conexion->connect_error);
}

// Insertar datos en la base de datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitizar y validar los datos
    $nombre = trim($_POST['nombre']);
    $correo = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);
    $telefono = trim($_POST['telefono']);
    $fecha_nacimiento = $_POST['fecha-nacimiento'];
    $genero = $_POST['gender'] ?? '';
    $direccion = trim($_POST['direccion']);
    $direccion2 = trim($_POST['direccion2']);
    $pais = trim($_POST['pais']);
    $ciudad = trim($_POST['ciudad']);
    $region = trim($_POST['region']);
    $codigo_postal = trim($_POST['codigo-postal']);

    // Validar que los campos requeridos no estén vacíos
    if ($nombre && $correo && $telefono && $fecha_nacimiento && $genero && $direccion && $pais && $ciudad && $region && $codigo_postal) {
        $stmt = $conexion->prepare("INSERT INTO registros (nombre, correo, telefono, fecha_nacimiento, genero, direccion, direccion2, pais, ciudad, region, codigo_postal)
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssssss", $nombre, $correo, $telefono, $fecha_nacimiento, $genero, $direccion, $direccion2, $pais, $ciudad, $region, $codigo_postal);

        if ($stmt->execute()) {
            echo "<script>alert('Registro agregado correctamente.');</script>";
        } else {
            echo "<script>alert('Error al agregar el registro.');</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Por favor, completa todos los campos requeridos.');</script>";
    }
}

// Eliminar un registro
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $stmt = $conexion->prepare("DELETE FROM registros WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('Registro eliminado correctamente.');</script>";
    } else {
        echo "<script>alert('Error al eliminar el registro.');</script>";
    }

    $stmt->close();
    header('Location: index.php');
    exit();
}

// Obtener todos los registros
$registros = $conexion->query("SELECT * FROM registros");

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="main-container">
        <!-- Contenedor del Formulario -->
        <section class="container">
            <header>Formulario de inscripción</header>
            <form action="" method="post" class="form">
                <!-- Formulario -->
                <div class="input-box">
                    <label>Nombre completo</label>
                    <input type="text" name="nombre" placeholder="Introduzca su nombre completo" required />
                </div>
                <div class="input-box">
                    <label>Correo Electrónico</label>
                    <input type="email" name="correo" placeholder="Introduzca su Correo Electrónico" required />
                </div>
                <div class="column">
                    <div class="input-box">
                        <label>Número de Teléfono</label>
                        <input type="tel" name="telefono" placeholder="Introduzca su Número de Teléfono" required />
                    </div>
                    <div class="input-box">
                        <label>Fecha de Nacimiento</label>
                        <input type="date" name="fecha-nacimiento" required />
                    </div>
                </div>
                <div class="gender-box">
                    <h3>Género</h3>
                    <div class="gender-option">
                        <div class="gender">
                            <input type="radio" id="check-male" name="gender" value="Masculino" required />
                            <label for="check-male">Masculino</label>
                        </div>
                        <div class="gender">
                            <input type="radio" id="check-female" name="gender" value="Femenino" required />
                            <label for="check-female">Femenino</label>
                        </div>
                        <div class="gender">
                            <input type="radio" id="check-other" name="gender" value="Prefiero no contestar" required />
                            <label for="check-other">Prefiero no contestar</label>
                        </div>
                    </div>
                </div>
                <div class="input-box address">
                    <label>Dirección</label>
                    <input type="text" name="direccion" placeholder="Introduzca su Dirección" required />
                    <input type="text" name="direccion2" placeholder="Introduzca su Dirección en la línea 2" />
                    <div class="column">
                        <div class="select-box">
                            <select name="pais" required>
                                <option hidden>País</option>
                                <option value="México">México</option>
                                <option value="Japón">Japón</option>
                                <option value="Argentina">Argentina</option>
                                <option value="Colombia">Colombia</option>
                            </select>
                        </div>
                        <input type="text" name="ciudad" placeholder="Introduzca su ciudad" required />
                    </div>
                    <div class="column">
                        <input type="text" name="region" placeholder="Introduzca su Región" required />
                        <input type="number" name="codigo-postal" placeholder="Introduzca el código postal" required />
                    </div>
                </div>
                <button type="submit">Enviar</button>
            </form>
        </section>
        <div class="main-container">
        <!-- Tabla de registros -->
        <section class="container-net">
            <header>Registros</header>
            <div class="table-container">
            <table border="1">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th>Fecha de Nacimiento</th>
                        <th>Género</th>
                        <th>Dirección</th>
                        <th>Ciudad</th>
                        <th>País</th>
                        <th>Código Postal</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $registros->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id']) ?></td>
                            <td><?= htmlspecialchars($row['nombre']) ?></td>
                            <td><?= htmlspecialchars($row['correo']) ?></td>
                            <td><?= htmlspecialchars($row['telefono']) ?></td>
                            <td><?= htmlspecialchars($row['fecha_nacimiento']) ?></td>
                            <td><?= htmlspecialchars($row['genero']) ?></td>
                            <td><?= htmlspecialchars($row['direccion']) ?></td>
                            <td><?= htmlspecialchars($row['ciudad']) ?></td>
                            <td><?= htmlspecialchars($row['pais']) ?></td>
                            <td><?= htmlspecialchars($row['codigo_postal']) ?></td>
                            <td>
                                <a href="editar.php?id=<?= $row['id'] ?>">Editar</a>
                                <a href="?eliminar=<?= $row['id'] ?>" onclick="return confirm('¿Seguro que deseas eliminar este registro?')">Eliminar</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        
        </section>
    </div>
</body>
</html>
