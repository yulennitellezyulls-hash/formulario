<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ====== DATOS DE CONEXIÓN ======
$host      = "sql311.infinityfree.com";
$usuario   = "if0_42002628";
$password  = "F9I8IlyKGQYnd";
$basedatos = "if0_42002628_yulls"; 

$conn = new mysqli($host, $usuario, $password, $basedatos);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$mensaje = "";

// ====== AGREGAR ======
if (isset($_POST["accion"]) && $_POST["accion"] == "agregar") {
    $stmt = $conn->prepare(
        "INSERT INTO Yulls (Nombre, ApellidoP, ApellidoM, fechanacimiento, Curp)
         VALUES (?, ?, ?, ?, ?)"
    );
    $stmt->bind_param("sssss", $_POST["nombre"], $_POST["apellidoP"],
                      $_POST["apellidoM"], $_POST["fecha"], $_POST["curp"]);
    $mensaje = $stmt->execute() ? "Registro agregado." : "Error: " . $stmt->error;
    $stmt->close();
}

// ====== ACTUALIZAR ======
if (isset($_POST["accion"]) && $_POST["accion"] == "actualizar") {
    $stmt = $conn->prepare(
        "UPDATE Persona SET Nombre=?, ApellidoP=?, ApellidoM=?, fechanacimiento=?, Curp=?
         WHERE id_persona=?"
    );
    $stmt->bind_param("sssssi", $_POST["nombre"], $_POST["apellidoP"],
                      $_POST["apellidoM"], $_POST["fecha"], $_POST["curp"], $_POST["id"]);
    $mensaje = $stmt->execute() ? "Registro actualizado." : "Error: " . $stmt->error;
    $stmt->close();
}

// ====== BORRAR ======
if (isset($_GET["borrar"])) {
    $stmt = $conn->prepare("DELETE FROM Persona WHERE id_persona=?");
    $stmt->bind_param("i", $_GET["borrar"]);
    $mensaje = $stmt->execute() ? "Registro borrado." : "Error: " . $stmt->error;
    $stmt->close();
}

// ====== CARGAR DATOS PARA EDITAR ======
$editar = null;
if (isset($_GET["editar"])) {
    $stmt = $conn->prepare("SELECT * FROM Persona WHERE id_persona=?");
    $stmt->bind_param("i", $_GET["editar"]);
    $stmt->execute();
    $editar = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

// ====== BÚSQUEDA ======
$busqueda = isset($_GET["busqueda"]) ? trim($_GET["busqueda"]) : "";
if ($busqueda != "") {
    $stmt = $conn->prepare(
        "SELECT * FROM Persona WHERE Nombre LIKE ? OR Curp LIKE ? ORDER BY id_persona DESC"
    );
    $like = "%" . $busqueda . "%";
    $stmt->bind_param("ss", $like, $like);
    $stmt->execute();
    $resultado = $stmt->get_result();
} else {
    $resultado = $conn->query("SELECT * FROM Yulls ORDER BY id_persona DESC");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 20px auto;
            padding: 0 15px;
            color: #333;
        }
        h2 { color: #2c3e50; }
        input { padding: 6px; margin: 4px 0; }
        input[type="text"], input[type="date"] { width: 100%; }
        button, .btn {
            padding: 7px 14px;
            background: #3498db;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }
        .mensaje { background: #e8f5e9; padding: 10px; border-radius: 4px; color: #256029; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f2f2f2; }
        .icono { text-decoration: none; font-size: 18px; margin: 0 4px; }
    </style>
</head>
<body>

    <?php if ($mensaje != "") echo "<p class='mensaje'>$mensaje</p>"; ?>

    <h2><?php echo $editar ? "Editar persona" : "Agregar persona"; ?></h2>
    <form method="POST" action="formulario.php">
        <input type="hidden" name="accion" value="<?php echo $editar ? "actualizar" : "agregar"; ?>">
        <?php if ($editar): ?>
            <input type="hidden" name="id" value="<?php echo $editar['id_persona']; ?>">
        <?php endif; ?>

        Nombre:
        <input type="text" name="nombre" maxlength="50" required
               value="<?php echo $editar ? htmlspecialchars($editar['Nombre']) : ''; ?>">
        Apellido Paterno:
        <input type="text" name="apellidoP" maxlength="50" required
               value="<?php echo $editar ? htmlspecialchars($editar['ApellidoP']) : ''; ?>">
        Apellido Materno:
        <input type="text" name="apellidoM" maxlength="50" required
               value="<?php echo $editar ? htmlspecialchars($editar['ApellidoM']) : ''; ?>">
        Fecha de nacimiento:
        <input type="date" name="fecha" required
               value="<?php echo $editar ? $editar['fechanacimiento'] : ''; ?>">
        CURP:
        <input type="text" name="curp" maxlength="18" required
               value="<?php echo $editar ? htmlspecialchars($editar['Curp']) : ''; ?>">

        <br>
        <button type="submit"><?php echo $editar ? "Actualizar" : "Agregar"; ?></button>
        <?php if ($editar): ?>
            <a href="formulario.php" class="btn">Cancelar</a>
        <?php endif; ?>
    </form>

    <h2>Personas registradas</h2>
    <form method="GET" action="formulario.php">
        <input type="text" name="busqueda" placeholder="Buscar por nombre o CURP"
               value="<?php echo htmlspecialchars($busqueda); ?>" style="width:60%;">
        <button type="submit">Buscar</button>
        <a href="formulario.php" class="btn">Mostrar todos</a>
    </form>

    <?php
    if ($resultado && $resultado->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>ID</th><th>Nombre</th><th>Apellido P</th>
              <th>Apellido M</th><th>Fecha Nac.</th><th>CURP</th><th>Acciones</th></tr>";
        while ($fila = $resultado->fetch_assoc()) {
            $id = $fila['id_persona'];
            echo "<tr>
                    <td>{$id}</td>
                    <td>{$fila['Nombre']}</td>
                    <td>{$fila['ApellidoP']}</td>
                    <td>{$fila['ApellidoM']}</td>
                    <td>{$fila['fechanacimiento']}</td>
                    <td>{$fila['Curp']}</td>
                    <td>
                        <a href='formulario.php?editar={$id}' class='icono' title='Editar'>✏️</a>
                        <a href='formulario.php?borrar={$id}' class='icono' title='Borrar'
                           onclick=\"return confirm('¿Borrar este registro?');\">❌</a>
                    </td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No se encontraron registros.</p>";
    }
    $conn->close();
    ?>

</body>
</html>