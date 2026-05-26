<?php

$conexion = mysqli_connect(
    "sql311.infinityfree.com",
    "if0_42002628",
    "F9I8I1yKGQYnd",
    "if0_42002628_yulls"
);

// VALIDAR CONEXIÓN
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// RECIBIR DATOS DEL FORMULARIO
$Nombre = $_POST['Nombre'];
$ApellidoP = $_POST['ApellidoP'];
$ApellidoM = $_POST['ApellidoM'];
$fechanacimiento = $_POST['fechanacimiento'];
$Curp = $_POST['Curp'];

// INSERTAR DATOS
$sql = "INSERT INTO Yulls
(Nombre, ApellidoP, ApellidoM, fechanacimiento, Curp)

VALUES

('$Nombre', '$ApellidoP', '$ApellidoM', '$fechanacimiento', '$Curp')";

// EJECUTAR
if (mysqli_query($conexion, $sql)) {

    echo "Datos guardados correctamente";

} else {

    echo "Error al guardar: " . mysqli_error($conexion);

}

// CERRAR CONEXIÓN
mysqli_close($conexion);

?>
