<?php
session_start();

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}
if (isset($_GET['accion']) && $_GET['accion'] == 'maestros') {
    header("Location: maestros_crud.php");
    exit();
}

if (isset($_GET['accion']) && $_GET['accion'] == 'alumnos') {
    header("Location: alumnos_crud.php");
    exit();
}
if (isset($_GET['accion']) && $_GET['accion'] == 'permisos') {
    header("Location: permisos_crud.php");
    exit();
}
if (isset($_GET['accion']) && $_GET['accion'] == 'clases') {
    header("Location: clases_crud.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<div>
            <div>

            </div>
            <div>
                <h5>admin</h5>
                <h5>Administrador</h5>
            </div>
            <div>
                <h5>MENU ADMINISTRACION</h5>
                <p><a href="?accion=permisos">Permisos</a></p>
                <p><a href="?accion=maestros">Maestros</a></p>
                <p><a href="?accion=alumnos">Alumnos</a></p>
                <p><a href="?accion=clases">Clases</a></p>
            </div>
        </div>


        <p>HOLA MUNDO</p>
</body>
</html>