<?php
session_start();
require_once "../config/database.php";

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

// Leer Alumnos
$query_leer_alumnos = "SELECT * FROM alumnos";
$statement_leer_alumnos = $pdo->prepare($query_leer_alumnos);
$statement_leer_alumnos->execute();
$alumnos = $statement_leer_alumnos->fetchAll(PDO::FETCH_ASSOC);

// Eliminar Alumno
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["eliminar_alumno"])) {
    $usuario_id = $_POST["eliminar_alumno"];

    // Eliminar de la tabla de alumnos
    $query_eliminar_alumno = "DELETE FROM alumnos WHERE usuario_id = :usuario_id";
    $statement_eliminar_alumno = $pdo->prepare($query_eliminar_alumno);
    $statement_eliminar_alumno->bindParam("usuario_id", $usuario_id);
    $statement_eliminar_alumno->execute();

    // Eliminar de la tabla de usuarios
    $query_eliminar_usuario = "DELETE FROM usuarios WHERE id = :usuario_id";
    $statement_eliminar_usuario = $pdo->prepare($query_eliminar_usuario);
    $statement_eliminar_usuario->bindParam("usuario_id", $usuario_id);
    $statement_eliminar_usuario->execute();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - CRUD Alumnos</title>
</head>
<body>
    <div>
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
        <h2>CRUD de Alumnos</h2>
        <button onclick="abrirModal()">Agregar Alumno</button>

        <!-- Tabla de Alumnos -->
        <table id="tablaAlumnos">
            <thead>
                <tr>
                    <th>#</th>
                    <th>DNI</th>
                    <th>Correo Electrónico</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Dirección</th>
                    <th>Fecha de Nacimiento</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($alumnos as $alumno) : ?>
                    <tr>
                        <td><?= $alumno["id"] ?></td>
                        <td><?= $alumno["dni"] ?></td>
                        <td><?= $alumno["correo"] ?></td>
                        <td><?= $alumno["nombre"] ?></td>
                        <td><?= $alumno["apellido"] ?></td>
                        <td><?= $alumno["direccion"] ?></td>
                        <td><?= $alumno["fecha_nacimiento"] ?></td>
                        <td>
                            <button onclick="eliminarAlumno(<?= $alumno['id'] ?>)">Eliminar</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div id="modal" style="display: none;">
        <form id="formAgregarAlumno" action="/handle_db/insert_alumno.php" method="POST">
        <label>DNI: <input type="text" name="dni"></label><br>
        <label>Correo Electrónico: <input type="text" name="correo"></label><br>
        <label>Nombre: <input type="text" name="nombre"></label><br>
        <label>Apellido: <input type="text" name="apellido"></label><br>
        <label>Dirección: <input type="text" name="direccion"></label><br>
        <label>Fecha de Nacimiento: <input type="text" name="fecha_nacimiento"></label><br>
        <button type="submit">Guardar</button>
        <button type="button" onclick="cerrarModal()">Cancelar</button>
    </form>
</div>

        <script>
            function abrirModal() {
                document.getElementById('modal').style.display = 'block';
            }

            function cerrarModal() {
                document.getElementById('modal').style.display = 'none';
            }

            // Función para eliminar alumno
            function eliminarAlumno(alumnoId) {
                if (confirm("¿Estás seguro de que quieres eliminar este alumno?")) {
                    // Crear un formulario dinámico
                    var form = document.createElement("form");
                    form.method = "POST";
                    
                    // Agregar un input oculto con el valor del ID del alumno
                    var input = document.createElement("input");
                    input.type = "hidden";
                    input.name = "eliminar_alumno";
                    input.value = alumnoId;
                    form.appendChild(input);

                    // Adjuntar el formulario al cuerpo del documento
                    document.body.appendChild(form);

                    // Enviar el formulario
                    form.submit();
                }
            }
        </script>
    </div>
</body>
</html>


