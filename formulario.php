<?php

$conexion = mysqli_connect(
    "sql311.infinityfree.com",
    "if0_42002628",
    "F9I8IlyKGQYnd",
    "if0_42002628_yulls"
);

if(!$conexion){
    die("Error de conexión");
}

$Nombre = $_POST['Nombre'];
$ApellidoP = $_POST['ApellidoP'];
$ApellidoM = $_POST['ApellidoM'];
$fechanacimiento = $_POST['fechanacimiento'];
$Curp = $_POST['Curp'];

$sql = "INSERT INTO Yulls
(Nombre, ApellidoP, ApellidoM, fechanacimiento, Curp)

VALUES
('$Nombre','$ApellidoP','$ApellidoM','$fechanacimiento','$Curp')";

if(mysqli_query($conexion,$sql)){
    echo "Datos guardados correctamente";
}else{
    echo "Error al guardar";
}

?>
