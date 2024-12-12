<?php
// Conexión a la base de datos
$conexion = new mysqli('localhost', 'root', '', 'formulario');

if ($conexion->connect_error) {
    die('Error de conexión: ' . $conexion->connect_error);
}

// Verificar si se ha recibido un ID en la URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Obtener los datos del registro según el ID
    $resultado = $conexion->query("SELECT * FROM registros WHERE id = $id");
    $registro = $resultado->fetch_assoc();

    if (!$registro) {
        die("Registro no encontrado.");
    }
} else {
    die("ID no especificado.");
}

// Guardar los cambios cuando se envía el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $fecha_nacimiento = $_POST['fecha-nacimiento'];
    $genero = $_POST['gender'];
    $direccion = $_POST['direccion'];
    $direccion2 = $_POST['direccion2'];
    $pais = $_POST['pais'];
    $ciudad = $_POST['ciudad'];
    $region = $_POST['region'];
    $codigo_postal = $_POST['codigo-postal'];

    $query = "UPDATE registros SET 
                nombre = '$nombre',
                correo = '$correo',
                telefono = '$telefono',
                fecha_nacimiento = '$fecha_nacimiento',
                genero = '$genero',
                direccion = '$direccion',
                direccion2 = '$direccion2',
                pais = '$pais',
                ciudad = '$ciudad',
                region = '$region',
                codigo_postal = '$codigo_postal'
              WHERE id = $id";

    if ($conexion->query($query) === TRUE) {
        header('Location: index.php');
        exit;
    } else {
        echo "Error: " . $conexion->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Registro</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <section class="container">
        <header>Editar Registro</header>
        <form action="" method="post" class="form">
            <!-- Formulario para editar los datos -->
            <div class="input-box">
                <label>Nombre completo</label>
                <input type="text" name="nombre" value="<?= $registro['nombre'] ?>" required />
            </div>
            <div class="input-box">
                <label>Correo Electrónico</label>
                <input type="text" name="correo" value="<?= $registro['correo'] ?>" required />
            </div>
            <div class="column">
                <div class="input-box">
                    <label>Número de Teléfono</label>
                    <input type="number" name="telefono" value="<?= $registro['telefono'] ?>" required />
                </div>
                <div class="input-box">
                    <label>Fecha de Nacimiento</label>
                    <input type="date" name="fecha-nacimiento" value="<?= $registro['fecha_nacimiento'] ?>" required />
                </div>
            </div>
            <div class="gender-box">
                <h3>Género</h3>
                <div class="gender-option">
                    <div class="gender">
                        <input type="radio" id="check-male" name="gender" value="Masculino" <?= $registro['genero'] == 'Masculino' ? 'checked' : '' ?> />
                        <label for="check-male">Masculino</label>
                    </div>
                    <div class="gender">
                        <input type="radio" id="check-female" name="gender" value="Femenino" <?= $registro['genero'] == 'Femenino' ? 'checked' : '' ?> />
                        <label for="check-female">Femenino</label>
                    </div>
                    <div class="gender">
                        <input type="radio" id="check-other" name="gender" value="Prefiero no contestar" <?= $registro['genero'] == 'Prefiero no contestar' ? 'checked' : '' ?> />
                        <label for="check-other">Prefiero no contestar</label>
                    </div>
                </div>
            </div>
            <div class="input-box address">
                <label>Dirección</label>
                <input type="text" name="direccion" value="<?= $registro['direccion'] ?>" required />
                <input type="text" name="direccion2" value="<?= $registro['direccion2'] ?>" />
                <div class="column">
                    <div class="select-box">
                        <select name="pais" required>
                            <option value="México" <?= $registro['pais'] == 'México' ? 'selected' : '' ?>>México</option>
                            <option value="Japón" <?= $registro['pais'] == 'Japón' ? 'selected' : '' ?>>Japón</option>
                            <option value="Argentina" <?= $registro['pais'] == 'Argentina' ? 'selected' : '' ?>>Argentina</option>
                            <option value="Colombia" <?= $registro['pais'] == 'Colombia' ? 'selected' : '' ?>>Colombia</option>
                        </select>
                    </div>
                    <input type="text" name="ciudad" value="<?= $registro['ciudad'] ?>" required />
                </div>
                <div class="column">
                    <input type="text" name="region" value="<?= $registro['region'] ?>" required />
                    <input type="number" name="codigo-postal" value="<?= $registro['codigo_postal'] ?>" required />
                </div>
            </div>
            <button type="submit">Guardar Cambios</button>
        </form>
    </section>
</body>
</html>
