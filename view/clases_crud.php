<?php
session_start();
require_once "../config/database.php";

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

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["eliminar_clase"])) {
    $id = $_POST["eliminar_clase"];

    // Eliminar de la tabla de clases
    $query_eliminar_clase = "DELETE FROM clases WHERE id = :id";
    $statement_eliminar_clase = $pdo->prepare($query_eliminar_clase);
    $statement_eliminar_clase->bindParam("id", $id);
    $statement_eliminar_clase->execute();

}

$query_leer_clases = "SELECT * FROM clases";
$statement_leer_clases = $pdo->prepare($query_leer_clases);
$statement_leer_clases->execute();
$clases = $statement_leer_clases->fetchAll(PDO::FETCH_ASSOC);

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
            <h2>CRUD de Clases</h2>
        <button onclick="abrirModal()">Agregar clase</button>

        <!-- Tabla de Clases -->
        <table id="tablaClases">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Clase</th>
                    <th>Maestro</th>
                    <th>Alumnos asignados</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clases as $clase) : ?>
                    <tr>
                        <td><?= $clase["id"] ?></td>
                        <td><?= $clase["nombre"] ?></td>
                        
                        <td>
                            <button onclick="eliminarAlumno(<?= $clase['id'] ?>)">Eliminar</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div id="modal" style="display: none;">
        <form id="formAgregarClase" action="/handle_db/insert_clase.php" method="POST">
        <label>Nombre de la materia: <input type="text" name="clase"></label><br>
        <label>Maestros disponibles para la clase: <input type="text" name="maestro"></label><br>
        <button type="submit">Guardar</button>
        <button type="button" onclick="cerrarModal()">Cancelar</button>
    </form>
    </div>
</div>

<script>
            function abrirModal() {
                document.getElementById('modal').style.display = 'block';
            }

            function cerrarModal() {
                document.getElementById('modal').style.display = 'none';
            }

            // Función para eliminar clase
            function eliminarClase(claseId) {
                if (confirm("¿Estás seguro de que quieres eliminar esta clase?")) {
                    // Crear un formulario dinámico
                    var form = document.createElement("form");
                    form.method = "POST";
                    
                    // Agregar un input oculto con el valor del ID de la clase
                    var input = document.createElement("input");
                    input.type = "hidden";
                    input.name = "eliminar_clase";
                    input.value = claseId;
                    form.appendChild(input);

                    // Adjuntar el formulario al cuerpo del documento
                    document.body.appendChild(form);

                    // Enviar el formulario
                    form.submit();
                }
            }
        </script>
</body>
</html>